<?php
namespace Eventin\PreviewPlaceholder;

defined( 'ABSPATH' ) || exit;

/**
 * Central registry for the shipped "preview placeholder" event and its linked
 * speaker/organizer users and schedule posts.
 *
 * The placeholder event exists only to power Template Builder previews when a
 * template has no selected preview event. It is hidden from admin and front-end
 * lists but rendered normally inside a template preview (blocks read it by ID).
 *
 * The seeder stores the IDs of its most recent run in three options, but those
 * options name one copy only. Sites seeded before 4.1.19 could end up with two or
 * three copies (see PreviewPlaceholderEventSeeder), and each run overwrote the
 * options, leaving every earlier copy unreferenced and therefore visible. So the
 * marker meta written on each record — not the options — is the source of truth
 * here: whatever carries the marker is a placeholder record and gets hidden. The
 * options are still folded in, so records seeded before the marker existed are
 * covered too.
 *
 * Lookups go through $wpdb directly. These accessors are called from inside
 * `pre_get_posts` / `pre_get_users` handlers, where a nested WP_Query or
 * WP_User_Query would recurse into those same hooks forever.
 */
class PreviewPlaceholder {
    const OPTION_EVENT_ID     = 'etn_preview_placeholder_event_id';
    const OPTION_USER_IDS     = 'etn_preview_placeholder_user_ids';
    const OPTION_SCHEDULE_IDS = 'etn_preview_placeholder_schedule_ids';
    const MARKER_META         = '_etn_preview_placeholder';

    /**
     * Transient holding the resolved registry, so the marker lookups below don't
     * run on every query of a page load.
     */
    const CACHE_KEY = 'etn_preview_placeholder_registry';

    /**
     * Per-request memo of the resolved registry.
     *
     * @var array|null
     */
    private static $registry = null;

    /**
     * The placeholder event to preview: the seeder's own copy when it is still
     * present, otherwise the oldest surviving marked copy.
     *
     * @return int 0 when the site has no placeholder event.
     */
    public static function event_id(): int {
        $option_id = (int) get_option( self::OPTION_EVENT_ID, 0 );
        $events    = self::registry()['events'];

        if ( $option_id > 0 && in_array( $option_id, $events, true ) ) {
            return $option_id;
        }

        return $events ? (int) min( $events ) : 0;
    }

    /**
     * Whether this site has a placeholder event at all.
     */
    public static function event_exists(): bool {
        return ! empty( self::registry()['events'] );
    }

    /**
     * Every placeholder event on this site. More than one means the site was seeded
     * concurrently before 4.1.19 and carries duplicates (see PreviewPlaceholderCleanup).
     *
     * @return int[]
     */
    public static function event_ids(): array {
        return self::registry()['events'];
    }

    /**
     * Every placeholder speaker + organizer user ID.
     */
    public static function user_ids(): array {
        return self::registry()['users'];
    }

    /**
     * Every placeholder schedule post ID.
     */
    public static function schedule_ids(): array {
        return self::registry()['schedules'];
    }

    /**
     * Post IDs (events + schedules) to exclude from admin/REST/front-end lists.
     * Empty when the site has no placeholder event, so nothing is hidden spuriously.
     */
    public static function excluded_post_ids(): array {
        $registry = self::registry();

        if ( ! $registry['events'] ) {
            return [];
        }

        return array_values( array_unique( array_merge( $registry['events'], $registry['schedules'] ) ) );
    }

    /**
     * Drop the cached registry. Call after seeding or removing placeholder records.
     */
    public static function flush(): void {
        self::$registry = null;
        delete_transient( self::CACHE_KEY );

        // The admin events list is cached for three hours with the exclusions already
        // applied, so it would keep serving the old set well after this changes.
        delete_transient( 'etn_event_list' );
    }

    /**
     * Resolve every placeholder record on this site.
     *
     * @return array{events:int[],schedules:int[],users:int[]}
     */
    private static function registry(): array {
        if ( null !== self::$registry ) {
            return self::$registry;
        }

        $cached = get_transient( self::CACHE_KEY );

        if ( is_array( $cached ) && isset( $cached['events'], $cached['schedules'], $cached['users'] ) ) {
            return self::$registry = $cached;
        }

        self::$registry = self::build_registry();

        set_transient( self::CACHE_KEY, self::$registry, DAY_IN_SECONDS );

        return self::$registry;
    }

    /**
     * Read the marked records straight from the database and merge in the IDs the
     * seeder recorded, discarding anything that no longer exists.
     *
     * @return array{events:int[],schedules:int[],users:int[]}
     */
    private static function build_registry(): array {
        global $wpdb;

        $marked = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT p.ID, p.post_type
                   FROM {$wpdb->posts} p
             INNER JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID
                  WHERE pm.meta_key = %s
                    AND p.post_type IN ( 'etn', 'etn-schedule' )",
                self::MARKER_META
            )
        );

        $events    = [];
        $schedules = [];

        foreach ( (array) $marked as $row ) {
            if ( 'etn' === $row->post_type ) {
                $events[] = (int) $row->ID;
            } else {
                $schedules[] = (int) $row->ID;
            }
        }

        $users = array_map(
            'intval',
            (array) $wpdb->get_col(
                $wpdb->prepare(
                    "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = %s",
                    self::MARKER_META
                )
            )
        );

        // Fold in the seeder's own record of its last run, in case those records
        // predate the marker meta. Anything already deleted is dropped below.
        $events[]  = (int) get_option( self::OPTION_EVENT_ID, 0 );
        $schedules = array_merge( $schedules, array_map( 'intval', (array) get_option( self::OPTION_SCHEDULE_IDS, [] ) ) );
        $users     = array_merge( $users, array_map( 'intval', (array) get_option( self::OPTION_USER_IDS, [] ) ) );

        return [
            'events'    => self::existing_posts( $events, 'etn' ),
            'schedules' => self::existing_posts( $schedules, 'etn-schedule' ),
            'users'     => self::existing_users( $users ),
        ];
    }

    /**
     * Filter a raw ID list down to posts that still exist with the given type.
     *
     * @param int[]  $ids
     * @param string $post_type
     *
     * @return int[]
     */
    private static function existing_posts( array $ids, string $post_type ): array {
        global $wpdb;

        $ids = array_values( array_unique( array_filter( array_map( 'intval', $ids ) ) ) );

        if ( ! $ids ) {
            return [];
        }

        $placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );

        $found = $wpdb->get_col(
            $wpdb->prepare(
                // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- placeholders built from an int count.
                "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND ID IN ( {$placeholders} )",
                array_merge( [ $post_type ], $ids )
            )
        );

        return array_map( 'intval', (array) $found );
    }

    /**
     * Filter a raw ID list down to users that still exist.
     *
     * @param int[] $ids
     *
     * @return int[]
     */
    private static function existing_users( array $ids ): array {
        global $wpdb;

        $ids = array_values( array_unique( array_filter( array_map( 'intval', $ids ) ) ) );

        if ( ! $ids ) {
            return [];
        }

        $placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );

        $found = $wpdb->get_col(
            $wpdb->prepare(
                // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- placeholders built from an int count.
                "SELECT ID FROM {$wpdb->users} WHERE ID IN ( {$placeholders} )",
                $ids
            )
        );

        return array_map( 'intval', (array) $found );
    }
}
