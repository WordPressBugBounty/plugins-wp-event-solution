<?php
/**
 * Object-level (per-record) authorization helpers.
 *
 * Eventin grants its `etn_manage_*` capabilities to Contributor and Author by
 * default (see Permission::default_role_permissions()). Those capabilities are
 * *collection-level* — they say "this user may use the events screen", not
 * "this user may edit event 412". Until 4.1.20 most per-id REST routes checked
 * only the capability, so any Contributor could update, delete or read objects
 * belonging to other users including administrators (BOLA).
 *
 * This class is the single place that answers "does the caller own this
 * object?". Controllers call it from their `*_permissions_check` methods in
 * addition to — never instead of — the capability check.
 *
 * Ownership model:
 *   - Posts (events, schedules): `post_author`.
 *   - Speakers/organizers (WP users): the `author` user meta that
 *     SpeakerController writes on create.
 *   - Orders: the order's event owner, or the customer the order belongs to.
 *
 * `manage_options` always passes — administrators are not scoped.
 *
 * @package Eventin
 */

namespace Eventin\AccessControl;

defined( 'ABSPATH' ) || exit;

/**
 * Ownership class
 */
class Ownership {

    /**
     * Does the current user bypass all ownership scoping?
     *
     * Administrators (and anyone a site grants `manage_options` to) manage the
     * whole install, so per-object checks do not apply to them.
     *
     * @return bool
     */
    public static function is_unscoped() {
        /**
         * Filter whether the current user bypasses per-object ownership checks.
         *
         * Multi-organizer setups (WCFM, Dokan) can widen this to let a store
         * manager act on their vendors' objects.
         *
         * @param bool $unscoped Whether ownership checks are skipped.
         */
        return (bool) apply_filters( 'eventin_ownership_is_unscoped', current_user_can( 'manage_options' ) );
    }

    /**
     * Can the current user act on this post?
     *
     * @param int         $post_id   Post id to test.
     * @param string|null $post_type Optional expected post type. When given, a
     *                               post of any other type is rejected outright
     *                               so an id from one collection cannot be used
     *                               to reach another.
     *
     * @return bool
     */
    public static function can_manage_post( $post_id, $post_type = null ) {
        $post_id = absint( $post_id );

        if ( ! $post_id ) {
            return false;
        }

        $post = get_post( $post_id );

        if ( ! $post ) {
            return false;
        }

        if ( $post_type && $post_type !== $post->post_type ) {
            return false;
        }

        if ( self::is_unscoped() ) {
            return true;
        }

        $user_id = get_current_user_id();

        if ( ! $user_id ) {
            return false;
        }

        $owns = ( (int) $post->post_author === (int) $user_id );

        /**
         * Filter the per-post ownership decision.
         *
         * @param bool     $owns    Whether the current user owns the post.
         * @param \WP_Post $post    The post being tested.
         * @param int      $user_id Current user id.
         */
        return (bool) apply_filters( 'eventin_can_manage_post', $owns, $post, $user_id );
    }

    /**
     * Can the current user act on every one of these posts?
     *
     * Used by bulk routes, which must not partially succeed on someone else's
     * records. Returns false on an empty list — an empty bulk request is a
     * client error, and returning true would read as "authorized".
     *
     * @param array       $post_ids  Post ids to test.
     * @param string|null $post_type Optional expected post type.
     *
     * @return bool
     */
    public static function can_manage_posts( $post_ids, $post_type = null ) {
        $post_ids = array_filter( array_map( 'absint', (array) $post_ids ) );

        if ( ! $post_ids ) {
            return false;
        }

        foreach ( $post_ids as $post_id ) {
            if ( ! self::can_manage_post( $post_id, $post_type ) ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Can the current user act on this speaker/organizer user record?
     *
     * Speakers and organizers are WP users, so this defers to WordPress's own
     * `edit_user` meta capability first — that is what stops a Contributor from
     * touching an administrator's account. On top of that, a non-admin must be
     * the recorded creator (the `author` user meta SpeakerController writes on
     * create), so one Contributor cannot manage another Contributor's speakers.
     *
     * @param int $target_user_id User id to test.
     *
     * @return bool
     */
    public static function can_manage_user( $target_user_id ) {
        $target_user_id = absint( $target_user_id );

        if ( ! $target_user_id || ! get_userdata( $target_user_id ) ) {
            return false;
        }

        if ( self::is_unscoped() ) {
            return true;
        }

        $user_id = get_current_user_id();

        if ( ! $user_id ) {
            return false;
        }

        // Own account is always manageable.
        if ( (int) $target_user_id === (int) $user_id ) {
            return true;
        }

        // WordPress's own gate. A Contributor holds no `edit_users`, so this is
        // false for every account but their own — which is what blocks the
        // role/meta overwrite and the account deletion.
        if ( ! current_user_can( 'edit_user', $target_user_id ) ) {
            return false;
        }

        $author = absint( get_user_meta( $target_user_id, 'author', true ) );
        $owns   = ( $author && $author === (int) $user_id );

        /**
         * Filter the per-user ownership decision.
         *
         * @param bool $owns           Whether the current user owns the record.
         * @param int  $target_user_id User being tested.
         * @param int  $user_id        Current user id.
         */
        return (bool) apply_filters( 'eventin_can_manage_user', $owns, $target_user_id, $user_id );
    }

    /**
     * Can the current user act on this order?
     *
     * An order is reachable by the organizer who owns the event it was placed
     * against, and by the customer who placed it. Everyone else is refused,
     * including holders of `etn_manage_event` who do not own the event — that
     * is the residual half of CVE-2026-4109.
     *
     * @param int $order_id Order post id.
     *
     * @return bool
     */
    public static function can_manage_order( $order_id ) {
        $order_id = absint( $order_id );

        if ( ! $order_id ) {
            return false;
        }

        $order = get_post( $order_id );

        if ( ! $order || 'etn-order' !== $order->post_type ) {
            return false;
        }

        if ( self::is_unscoped() ) {
            return true;
        }

        $user_id = get_current_user_id();

        if ( ! $user_id ) {
            return false;
        }

        // The customer who placed the order.
        $customer_id = absint( get_post_meta( $order_id, 'customer_id', true ) );

        if ( $customer_id && $customer_id === (int) $user_id ) {
            return true;
        }

        // The organizer who owns the event the order was placed against.
        $event_id = absint( get_post_meta( $order_id, 'event_id', true ) );
        $owns     = false;

        if ( $event_id ) {
            $event = get_post( $event_id );
            $owns  = ( $event && (int) $event->post_author === (int) $user_id );
        }

        /**
         * Filter the per-order ownership decision.
         *
         * @param bool $owns     Whether the current user may act on the order.
         * @param int  $order_id Order being tested.
         * @param int  $user_id  Current user id.
         */
        return (bool) apply_filters( 'eventin_can_manage_order', $owns, $order_id, $user_id );
    }
}
