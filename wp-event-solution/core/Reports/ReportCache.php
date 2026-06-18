<?php

namespace Eventin\Reports;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;

/**
 * Caches the expensive /eventin/v2/reports responses.
 *
 * Revenue is computed live over the full wp_postmeta EAV (250k+ orders); a
 * single all-time scan costs several seconds and the dashboard runs it ~14
 * times per request. Caching the assembled report makes warm loads sub-second.
 *
 * Invalidation is version-based: every cache key embeds an option-stored
 * version counter, so a single bump() invalidates every cached variant at once
 * without enumerating transients. A short TTL is the final self-healing net.
 *
 * @package Eventin\Reports
 */
class ReportCache implements HookableInterface {

    /**
     * Option holding the cache version counter.
     *
     * @var string
     */
    const VERSION_OPTION = 'etn_reports_cache_version';

    /**
     * Order/attendee meta keys that affect revenue or the dashboard counts.
     * A change to any of these must invalidate the cache.
     *
     * @var string[]
     */
    const REVENUE_META_KEYS = [
        'status',
        'total_price',
        'discount_total',
        'tax_total',
        'etn_refunds',
        'etn_status',
        'etn_event_id',
        'event_id',
    ];

    /**
     * Post types whose lifecycle invalidates the report cache.
     *
     * @var string[]
     */
    const WATCHED_POST_TYPES = [ 'etn-order', 'etn-attendee', 'etn' ];

    /**
     * Run $callback, caching its result keyed by $parts + version + user.
     *
     * @param   array     $parts     Parts that uniquely identify this report.
     * @param   callable  $callback  Produces the report when not cached.
     *
     * @return  mixed
     */
    public static function remember( array $parts, callable $callback ) {
        $ttl = (int) apply_filters( 'eventin_reports_cache_ttl', 300 );

        // TTL of 0 disables caching entirely (escape hatch / debugging).
        if ( $ttl <= 0 ) {
            return call_user_func( $callback );
        }

        $key    = self::build_key( $parts );
        $cached = get_transient( $key );

        if ( false !== $cached ) {
            return $cached;
        }

        $value = call_user_func( $callback );

        set_transient( $key, $value, $ttl );

        return $value;
    }

    /**
     * Build a transient key from the report parts, version and current user.
     *
     * The user id is part of the key because non-admins receive
     * author-scoped revenue (see RevenueReport::get_total_revenue).
     *
     * @param   array  $parts  Parts that uniquely identify this report.
     *
     * @return  string
     */
    private static function build_key( array $parts ) {
        $version = (int) get_option( self::VERSION_OPTION, 1 );
        $user_id = get_current_user_id();
        $raw     = wp_json_encode( $parts ) . '|v' . $version . '|u' . $user_id;

        return 'etn_reports_' . md5( $raw );
    }

    /**
     * Invalidate every cached report by bumping the version counter.
     *
     * @return  void
     */
    public static function bump_version() {
        $version = (int) get_option( self::VERSION_OPTION, 1 );

        update_option( self::VERSION_OPTION, $version + 1, false );
    }

    /**
     * Register invalidation hooks.
     *
     * Two layers: explicit domain actions (fast, fire on the happy paths) and
     * WordPress core post/meta lifecycle nets (catch any path that mutates an
     * order/attendee without firing the custom action). Every handler collapses
     * to a single cheap option write, so redundant firing is harmless.
     *
     * @return  void
     */
    public function register_hooks(): void {
        $domain_actions = [
            'eventin_order_completed',
            'eventin_order_status_completed',
            'eventin_order_status_failed',
            'eventin_order_failed',
            'eventin_order_refund',
            'eventin_after_order_create',
            'eventin_after_order_update',
            'eventin_order_before_delete',
            'eventin_order_deleted',
            'eventin_order_attendee_created',
            'eventin/refund/created',
            'eventin/refund/undone',
            'eventin_attendee_created',
            'eventin_attendee_payment_completed',
            'eventin_attendee_updated',
            'eventin_attendee_deleted',
            'eventin_attendee_trashed',
            'eventin_attendee_untrashed',
        ];

        foreach ( $domain_actions as $action ) {
            add_action( $action, [ __CLASS__, 'bump_version' ] );
        }

        // Core lifecycle safety nets.
        add_action( 'save_post_etn-order', [ __CLASS__, 'bump_version' ] );
        add_action( 'save_post_etn-attendee', [ __CLASS__, 'bump_version' ] );

        add_action( 'before_delete_post', [ $this, 'maybe_bump_on_post' ] );
        add_action( 'wp_trash_post', [ $this, 'maybe_bump_on_post' ] );
        add_action( 'untrashed_post', [ $this, 'maybe_bump_on_post' ] );

        // Status / price changes are often written via update_post_meta without
        // re-saving the post, so the save_post net alone would miss them.
        add_action( 'updated_post_meta', [ $this, 'maybe_bump_on_meta' ], 10, 3 );
        add_action( 'added_post_meta', [ $this, 'maybe_bump_on_meta' ], 10, 3 );
        add_action( 'deleted_post_meta', [ $this, 'maybe_bump_on_meta_deleted' ], 10, 2 );
    }

    /**
     * Bump when a watched post type is deleted / trashed / untrashed.
     *
     * @param   int  $post_id  Post being changed.
     *
     * @return  void
     */
    public function maybe_bump_on_post( $post_id ) {
        if ( in_array( get_post_type( $post_id ), self::WATCHED_POST_TYPES, true ) ) {
            self::bump_version();
        }
    }

    /**
     * Bump when a revenue-affecting meta key changes on a watched post type.
     *
     * @param   int     $meta_id   Meta row id (unused).
     * @param   int     $post_id   Post the meta belongs to.
     * @param   string  $meta_key  Meta key being changed.
     *
     * @return  void
     */
    public function maybe_bump_on_meta( $meta_id, $post_id, $meta_key ) {
        if ( ! in_array( $meta_key, self::REVENUE_META_KEYS, true ) ) {
            return;
        }

        $this->maybe_bump_on_post( $post_id );
    }

    /**
     * Bump on deleted meta. The deleted_post_meta hook passes meta ids first,
     * then object id and key, with a different signature than the add/update
     * hooks, so it has its own thin adapter.
     *
     * @param   array   $meta_ids  Deleted meta row ids (unused).
     * @param   int     $post_id   Post the meta belonged to.
     * @param   string  $meta_key  Meta key being deleted.
     *
     * @return  void
     */
    public function maybe_bump_on_meta_deleted( $meta_ids, $post_id ) {
        // deleted_post_meta does not reliably pass a single key, so bump on any
        // meta deletion for a watched post type (rare; cost is one option write).
        $this->maybe_bump_on_post( $post_id );
    }
}
