<?php
namespace Eventin\PreviewPlaceholder;

defined( 'ABSPATH' ) || exit;

/**
 * Central registry for the shipped "preview placeholder" event and its linked
 * speaker/organizer users and schedule posts. Reads the IDs stored by the seeder.
 *
 * The placeholder event exists only to power Template Builder previews when a
 * template has no selected preview event. It is hidden from admin and front-end
 * lists but rendered normally inside a template preview (blocks read it by ID).
 */
class PreviewPlaceholder {
    const OPTION_EVENT_ID     = 'etn_preview_placeholder_event_id';
    const OPTION_USER_IDS     = 'etn_preview_placeholder_user_ids';
    const OPTION_SCHEDULE_IDS = 'etn_preview_placeholder_schedule_ids';
    const MARKER_META         = '_etn_preview_placeholder';

    /**
     * The seeded placeholder event ID (0 if never seeded).
     */
    public static function event_id(): int {
        return (int) get_option( self::OPTION_EVENT_ID, 0 );
    }

    /**
     * Whether the placeholder event still exists as an `etn` post.
     */
    public static function event_exists(): bool {
        $id = self::event_id();
        return $id > 0 && get_post_type( $id ) === 'etn';
    }

    /**
     * Seeded speaker + organizer user IDs.
     */
    public static function user_ids(): array {
        return array_map( 'intval', (array) get_option( self::OPTION_USER_IDS, [] ) );
    }

    /**
     * Seeded schedule post IDs.
     */
    public static function schedule_ids(): array {
        return array_map( 'intval', (array) get_option( self::OPTION_SCHEDULE_IDS, [] ) );
    }

    /**
     * Post IDs (event + schedules) to exclude from admin/REST/front-end lists.
     * Empty when the event no longer exists, so nothing is excluded spuriously.
     */
    public static function excluded_post_ids(): array {
        if ( ! self::event_exists() ) {
            return [];
        }
        return array_values( array_unique( array_merge( [ self::event_id() ], self::schedule_ids() ) ) );
    }
}
