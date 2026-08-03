<?php
namespace Eventin\PreviewPlaceholder;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Event\Event_Model;
use Etn\Core\Schedule\Schedule_Model;
use Etn\Core\Speaker\User_Model;
use Eventin\Support\DbLock;

/**
 * Seeds the hidden "Applied AI & Machine Learning Summit 2026" placeholder event
 * (plus its speakers, organizers, schedules, and banner) exactly once per site.
 * Powers Template Builder previews when a template has no selected event.
 */
class PreviewPlaceholderEventSeeder {

    /**
     * Name of the mutex that serialises concurrent seed attempts.
     */
    const LOCK = 'etn_preview_placeholder_seed_lock';

    /**
     * Create the placeholder event and all linked records. Idempotent: no-ops once
     * the event exists. On failure, releases the lock so a later boot retries.
     *
     * Seeding takes seconds (banner resize, ~20 users, schedules, the event). It used
     * to run from `admin_init`, which admin-ajax.php fires too, so every background
     * AJAX request landing inside that window started its own full run — which is how
     * customer sites ended up with two and three copies of the placeholder event. The
     * existence check alone cannot prevent that: the option it reads is not written
     * until the run is nearly finished, so every overlapping request sees "not seeded
     * yet". Hence the lock below, which is what makes the current call site
     * (`Upgrade::register()`, on activation and on upgrade) safe to enter more than
     * once.
     */
    public function seed(): void {
        if ( PreviewPlaceholder::event_exists() ) {
            return;
        }

        // Atomic claim. A concurrent request that loses here does nothing at all.
        if ( ! DbLock::acquire( self::LOCK ) ) {
            return;
        }

        // Re-check now that we hold the lock: a run that finished between our check
        // above and this claim has already created everything.
        //
        // Flush first, so this reads the database rather than the registry cache.
        // That cache lives for a day and is written by the very first check above —
        // so a run that created the event and then died before its `finally` (fatal,
        // timeout) leaves the records in place with a cached registry that still says
        // "no placeholder here". The lock expires after five minutes; without this
        // flush the next run would trust that stale cache and seed a second copy,
        // which is exactly the duplication this seeder exists to have fixed.
        PreviewPlaceholder::flush();

        if ( PreviewPlaceholder::event_exists() ) {
            DbLock::release( self::LOCK );
            return;
        }

        $fixture = require \Wpeventin::plugin_dir() . 'core/PreviewPlaceholder/data/summit-event.php';

        try {
            $banner = $this->insert_banner();

            // Create users, building a source-ID → new-ID map so schedule topics can be rewired.
            $id_map        = [];
            $user_ids      = [];
            $speaker_ids   = [];
            $organizer_ids = [];

            foreach ( array_values( $fixture['speakers'] ) as $i => $speaker ) {
                $new_id = $this->create_user( $speaker, 'speaker' );
                if ( $new_id ) {
                    $user_ids[]    = $new_id;
                    $speaker_ids[] = $new_id;
                    $source_id     = $fixture['_source_speaker_ids'][ $i ] ?? null;
                    if ( $source_id ) {
                        $id_map[ (int) $source_id ] = $new_id;
                    }
                }
            }
            foreach ( array_values( $fixture['organizers'] ) as $i => $organizer ) {
                $new_id = $this->create_user( $organizer, 'organizer' );
                if ( $new_id ) {
                    $user_ids[]      = $new_id;
                    $organizer_ids[] = $new_id;
                    $source_id       = $fixture['_source_organizer_ids'][ $i ] ?? null;
                    if ( $source_id ) {
                        $id_map[ (int) $source_id ] = $new_id;
                    }
                }
            }

            // Create schedules, remapping the speaker references embedded in topics.
            $schedule_ids = [];
            foreach ( $fixture['schedules'] as $schedule ) {
                $meta = $this->remap_schedule_speakers( $schedule['meta'], $id_map );
                $model = new Schedule_Model();
                $sid   = $model->create_and_return_post_id( array_merge(
                    [ 'post_title' => $schedule['post_title'], 'post_status' => 'publish' ],
                    $meta
                ) );
                if ( $sid ) {
                    $schedule_ids[] = (int) $sid;
                    // Marked on creation rather than in a batch at the end: a run that
                    // dies part-way would otherwise leave unmarked records behind, and
                    // the visibility layer can only hide what carries the marker.
                    update_post_meta( (int) $sid, PreviewPlaceholder::MARKER_META, '1' );
                }
            }

            // Assemble event meta: fixture meta + banner + linked record IDs.
            $event_meta = $fixture['event']['meta'];
            if ( $banner ) {
                $event_meta['event_banner']    = $banner['image_url'];
                $event_meta['event_banner_id'] = $banner['attach_id'];
            }
            $event_meta['etn_event_speaker']   = $speaker_ids;
            $event_meta['etn_event_organizer'] = $organizer_ids;
            $event_meta['etn_event_schedule']  = $schedule_ids;

            $event    = new Event_Model();
            $event_id = $event->create_and_return_post_id( array_merge( [
                'post_title'   => $fixture['event']['post_title'],
                'post_content' => $fixture['event']['post_content'],
                'post_excerpt' => $fixture['event']['post_excerpt'],
                'post_status'  => 'publish',
            ], $event_meta ) );

            if ( ! $event_id ) {
                return; // nothing usable created; the finally below frees the lock to retry
            }

            // Mark before anything else can fail, so the event is hideable even if the
            // rest of this run does not complete.
            update_post_meta( $event_id, PreviewPlaceholder::MARKER_META, '1' );

            if ( $banner ) {
                set_post_thumbnail( $event_id, $banner['attach_id'] );
            }

            update_option( PreviewPlaceholder::OPTION_USER_IDS, $user_ids );
            update_option( PreviewPlaceholder::OPTION_SCHEDULE_IDS, $schedule_ids );
            update_option( PreviewPlaceholder::OPTION_EVENT_ID, (int) $event_id );
        } catch ( \Throwable $e ) {
            // Never fatal a customer's site over a demo seed. Retry next boot.
            return;
        } finally {
            // Flushed here rather than after the writes above so a run that dies
            // part-way still publishes the records it did manage to mark — the
            // visibility layer reads the registry, and a stale one would leave them
            // showing until the cache expired.
            PreviewPlaceholder::flush();

            // Released on every path, including the catch above: the real guard against
            // re-seeding is event_exists(), so holding the lock past this run would only
            // block a legitimate retry.
            DbLock::release( self::LOCK );
        }
    }

    /**
     * Rewrite the sandbox speaker IDs embedded in a schedule's topics to the
     * newly-created user IDs. Unknown IDs are dropped so no dangling references survive.
     */
    private function remap_schedule_speakers( array $meta, array $id_map ): array {
        if ( empty( $meta['etn_schedule_topics'] ) || ! is_array( $meta['etn_schedule_topics'] ) ) {
            return $meta;
        }
        foreach ( $meta['etn_schedule_topics'] as &$topic ) {
            if ( empty( $topic['speakers'] ) || ! is_array( $topic['speakers'] ) ) {
                continue;
            }
            $mapped = [];
            foreach ( $topic['speakers'] as $old_id ) {
                if ( isset( $id_map[ (int) $old_id ] ) ) {
                    $mapped[] = $id_map[ (int) $old_id ];
                }
            }
            $topic['speakers'] = $mapped;
        }
        unset( $topic );
        return $meta;
    }

    /**
     * Create one etn-speaker / etn-organizer user from a fixture row.
     *
     * @return int User ID, or 0 on failure.
     */
    private function create_user( array $row, string $category ): int {
        $model = new User_Model();
        $args  = [
            'etn_speaker_title'         => $row['title'],
            'display_name'              => $row['title'],
            'etn_speaker_designation'   => $row['designation'] ?? '',
            'etn_company_name'          => $row['company_name'] ?? '',
            'etn_speaker_summery'       => $row['summery'] ?? '',
            'etn_speaker_website_email' => $row['website_email'] ?? '',
            'etn_speaker_social'        => is_array( $row['social'] ?? null ) ? $row['social'] : [],
            'etn_speaker_category'      => [ $category ],
            'date'                      => gmdate( 'Y-m-d H:i:s' ),
        ];
        $user_id = $model->create( $args );

        if ( is_wp_error( $user_id ) || ! $user_id ) {
            return 0;
        }

        // Marked here rather than in a batch at the end of seed(): a run that dies
        // part-way would otherwise leave unmarked demo users visible in the
        // Speakers/Organizers lists with nothing pointing at them.
        update_user_meta( (int) $user_id, PreviewPlaceholder::MARKER_META, '1' );
        update_user_meta( (int) $user_id, 'hide_user', '1' ); // hidden from WP Users list (existing filter)

        return (int) $user_id;
    }

    /**
     * Copy the bundled banner into the media library.
     *
     * @return array{image_url:string,attach_id:int}|null
     */
    private function insert_banner(): ?array {
        $matches = glob( dirname( __DIR__, 2 ) . '/assets/images/preview-placeholder-banner.*' );
        $source  = $matches ? $matches[0] : '';
        if ( ! $source || ! file_exists( $source ) ) {
            return null;
        }
        $upload   = wp_upload_dir();
        $filename = wp_unique_filename( $upload['path'], basename( $source ) );
        $target   = trailingslashit( $upload['path'] ) . $filename;
        if ( ! copy( $source, $target ) ) {
            return null;
        }
        $filetype  = wp_check_filetype( $filename, null );
        $attach_id = wp_insert_attachment( [
            'guid'           => trailingslashit( $upload['url'] ) . $filename,
            'post_mime_type' => $filetype['type'],
            'post_title'     => sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ], $target );
        if ( is_wp_error( $attach_id ) || ! $attach_id ) {
            return null;
        }
        require_once ABSPATH . 'wp-admin/includes/image.php';
        wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $target ) );
        return [ 'image_url' => wp_get_attachment_url( $attach_id ), 'attach_id' => (int) $attach_id ];
    }
}
