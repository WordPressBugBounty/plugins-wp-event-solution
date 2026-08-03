<?php
namespace Eventin\PreviewPlaceholder;

defined( 'ABSPATH' ) || exit;

use Eventin\Support\DbLock;

/**
 * Removes the duplicate placeholder events left behind by concurrent seeding.
 *
 * Before 4.1.19 the seeder was guarded only by an option written on its last line,
 * so several overlapping admin requests could each run a full seed and a site could
 * end up with two or three copies of the placeholder event, its schedules, its
 * banner and its ~20 demo speaker/organizer users. 4.1.19 makes that impossible
 * (PreviewPlaceholderEventSeeder holds a DbLock) and hides every copy rather than
 * just the last one (PreviewPlaceholder reads the marker meta), but the surplus
 * rows are still sitting in affected databases. This deletes them.
 *
 * The rules are deliberately conservative, because this runs unattended on live
 * customer sites:
 *
 * - Only records carrying MARKER_META are ever touched. Anything a customer made
 *   is invisible to this class.
 * - An event with attendees or orders attached is never deleted, even if it is a
 *   duplicate. Someone has taken a booking against it, so it is a real event now.
 * - Users are only deleted when their roles are entirely Eventin speaker/organizer
 *   roles and they have authored no posts.
 *
 * Whatever cannot be deleted is left in place and stays hidden — the visibility
 * layer covers every marked copy either way, so cleanup is housekeeping, not the
 * fix. The outcome is recorded in the etn_preview_placeholder_cleanup_report
 * option so support can see what happened on a customer site.
 */
class PreviewPlaceholderCleanup {

    /**
     * Name of the mutex serialising cleanup runs.
     */
    const LOCK = 'etn_preview_placeholder_cleanup_lock';

    /**
     * Option holding the report of the last run.
     */
    const OPTION_REPORT = 'etn_preview_placeholder_cleanup_report';

    /**
     * Roles a marked user may hold and still be deletable.
     */
    const DELETABLE_ROLES = [ 'etn-speaker', 'etn-organizer' ];

    /**
     * Collapse the placeholder records down to a single event and its own linked
     * records. Safe to call repeatedly: with one copy left there is nothing to do.
     *
     * @return array Report of what was kept, deleted, and skipped.
     */
    public function run(): array {
        $report = [
            'survivor'          => 0,
            'deleted_events'    => [],
            'deleted_schedules' => [],
            'deleted_users'     => [],
            'skipped_events'    => [],
            'skipped_users'     => [],
        ];

        if ( ! DbLock::acquire( self::LOCK ) ) {
            return $report;
        }

        try {
            $events = PreviewPlaceholder::event_ids();

            if ( ! $events ) {
                return $report;
            }

            $survivor           = $this->pick_survivor( $events );
            $report['survivor'] = $survivor;

            foreach ( $events as $event_id ) {
                if ( $event_id === $survivor ) {
                    continue;
                }

                if ( $this->has_bookings( $event_id ) ) {
                    // Somebody bought a ticket for this copy. It is not ours to delete.
                    $report['skipped_events'][] = $event_id;
                    continue;
                }

                $this->delete_banner( $event_id );
                wp_delete_post( $event_id, true );
                $report['deleted_events'][] = $event_id;
            }

            // A schedule or speaker still attached to ANY event we left standing has to
            // stay, or a copy we deliberately spared would lose its agenda and lineup.
            $kept_events = array_merge( [ $survivor ], $report['skipped_events'] );

            $keep_schedules = $this->linked_ids( $kept_events, [ 'etn_event_schedule' ] );
            $keep_users     = $this->linked_ids( $kept_events, [ 'etn_event_speaker', 'etn_event_organizer' ] );

            foreach ( PreviewPlaceholder::schedule_ids() as $schedule_id ) {
                if ( in_array( $schedule_id, $keep_schedules, true ) ) {
                    continue;
                }
                wp_delete_post( $schedule_id, true );
                $report['deleted_schedules'][] = $schedule_id;
            }

            require_once ABSPATH . 'wp-admin/includes/user.php';

            foreach ( PreviewPlaceholder::user_ids() as $user_id ) {
                if ( in_array( $user_id, $keep_users, true ) ) {
                    continue;
                }
                if ( ! $this->is_deletable_user( $user_id ) ) {
                    $report['skipped_users'][] = $user_id;
                    continue;
                }
                wp_delete_user( $user_id );
                $report['deleted_users'][] = $user_id;
            }

            // Point the seeder's own bookkeeping at the surviving preview event and its
            // own records, so the options and the marked records agree again.
            update_option( PreviewPlaceholder::OPTION_EVENT_ID, $survivor );
            update_option( PreviewPlaceholder::OPTION_SCHEDULE_IDS, $this->linked_ids( [ $survivor ], [ 'etn_event_schedule' ] ) );
            update_option( PreviewPlaceholder::OPTION_USER_IDS, $this->linked_ids( [ $survivor ], [ 'etn_event_speaker', 'etn_event_organizer' ] ) );

            update_option( self::OPTION_REPORT, $report, false );
        } catch ( \Throwable $e ) {
            // Housekeeping must never fatal a customer's site. Duplicates stay hidden.
            return $report;
        } finally {
            PreviewPlaceholder::flush();
            DbLock::release( self::LOCK );
        }

        return $report;
    }

    /**
     * The copy to keep: the one the seeder recorded if it is still among the marked
     * events, otherwise the oldest, which is the one most likely to be referenced by
     * a template the customer already configured.
     *
     * @param int[] $events
     */
    private function pick_survivor( array $events ): int {
        $recorded = (int) get_option( PreviewPlaceholder::OPTION_EVENT_ID, 0 );

        if ( $recorded > 0 && in_array( $recorded, $events, true ) ) {
            return $recorded;
        }

        return (int) min( $events );
    }

    /**
     * Read ID lists out of events' meta (speakers, organizers, schedules), keeping
     * only IDs that are actually placeholder records.
     *
     * @param int[]    $event_ids
     * @param string[] $meta_keys
     *
     * @return int[]
     */
    private function linked_ids( array $event_ids, array $meta_keys ): array {
        $ids = [];

        foreach ( $event_ids as $event_id ) {
            foreach ( $meta_keys as $key ) {
                $value = get_post_meta( (int) $event_id, $key, true );
                $ids   = array_merge( $ids, array_map( 'intval', (array) $value ) );
            }
        }

        $known = array_merge( PreviewPlaceholder::schedule_ids(), PreviewPlaceholder::user_ids() );

        return array_values( array_unique( array_filter( array_intersect( $ids, $known ) ) ) );
    }

    /**
     * Whether any attendee or order points at this event. Attendees store the link in
     * `etn_event_id`, orders in `event_id`.
     */
    private function has_bookings( int $event_id ): bool {
        global $wpdb;

        $count = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*)
                   FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                  WHERE pm.meta_value = %s
                    AND ( ( pm.meta_key = 'etn_event_id' AND p.post_type = 'etn-attendee' )
                       OR ( pm.meta_key = 'event_id'     AND p.post_type = 'etn-order'    ) )",
                (string) $event_id
            )
        );

        return (int) $count > 0;
    }

    /**
     * Delete the banner attachment a duplicate run copied into the media library.
     * Only the attachment this event owns is removed.
     */
    private function delete_banner( int $event_id ): void {
        $ids = [
            (int) get_post_meta( $event_id, 'event_banner_id', true ),
            (int) get_post_thumbnail_id( $event_id ),
        ];

        foreach ( array_unique( array_filter( $ids ) ) as $attachment_id ) {
            if ( 'attachment' !== get_post_type( $attachment_id ) ) {
                continue;
            }
            // Don't remove an image another event is also using.
            if ( $this->attachment_in_use_elsewhere( $attachment_id, $event_id ) ) {
                continue;
            }
            wp_delete_attachment( $attachment_id, true );
        }
    }

    /**
     * Whether a post other than $event_id references this attachment as its banner or
     * featured image.
     */
    private function attachment_in_use_elsewhere( int $attachment_id, int $event_id ): bool {
        global $wpdb;

        $count = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->postmeta}
                  WHERE meta_key IN ( 'event_banner_id', '_thumbnail_id' )
                    AND meta_value = %s
                    AND post_id <> %d",
                (string) $attachment_id,
                $event_id
            )
        );

        return (int) $count > 0;
    }

    /**
     * Whether a marked user is safe to remove: Eventin speaker/organizer roles only,
     * and no authored content.
     */
    private function is_deletable_user( int $user_id ): bool {
        if ( $user_id === get_current_user_id() ) {
            return false;
        }

        $user = get_userdata( $user_id );

        if ( ! $user ) {
            return false;
        }

        $roles = (array) $user->roles;

        if ( ! $roles || array_diff( $roles, self::DELETABLE_ROLES ) ) {
            return false;
        }

        return 0 === (int) count_user_posts( $user_id, 'any' );
    }
}
