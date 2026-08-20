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
     * The id a per-object route is actually going to act on.
     *
     * Read the subject from the URL, never from `get_param()`. Two reasons:
     *
     *   1. `WP_REST_Request::get_param()` resolves JSON/POST body parameters
     *      BEFORE the URL parameters, so a body key can shadow the id in the
     *      path.
     *   2. A permission callback shared by a per-id route and a bulk route must
     *      not let the bulk route's `ids` parameter stand in as the subject of a
     *      per-id request. That is exactly how the 4.1.20 delete checks were
     *      bypassed: `DELETE /events/<victim>` carrying `{"ids":[<own event>]}`
     *      was authorised against the caller's own event and then executed
     *      against the victim's, because the check preferred `ids` and the
     *      handler only ever read `id`.
     *
     * A route with no `id` in its path returns 0, which callers should read as
     * "this is the collection route".
     *
     * @param \WP_REST_Request $request Request being authorised.
     *
     * @return int Post/user id from the URL, or 0.
     */
    public static function route_subject_id( $request ) {
        if ( ! is_a( $request, 'WP_REST_Request' ) ) {
            return 0;
        }

        $url_params = $request->get_url_params();

        return isset( $url_params['id'] ) ? absint( $url_params['id'] ) : 0;
    }

    /**
     * May the current user READ this post?
     *
     * Reading is wider than managing: a published event is public, so anyone
     * may read it. Anything else — draft, pending, private, future — is only
     * visible to its author and to someone who may edit other people's posts
     * (Editor and above on a stock install).
     *
     * `etn_manage_event` is NOT an acceptable test here. It is granted to
     * Contributor and Author by default, so gating on it leaks every
     * administrator's unpublished event to the lowest role that can reach the
     * events screen.
     *
     * `post_status` on its own is NOT an answer either. A password-protected
     * post keeps `post_status = publish` — WordPress puts the secret in the
     * separate `post_password` column — so testing the status alone waved every
     * protected event through to an anonymous caller, and the handlers behind
     * this then returned the raw post row: the plaintext password and the
     * protected body. See can_read_protected_content() for the extra test.
     *
     * @param int         $post_id   Post id to test.
     * @param string|null $post_type Optional expected post type.
     *
     * @return bool
     */
    public static function can_read_post( $post_id, $post_type = null ) {
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

        $readable = false;

        if ( self::is_unscoped() ) {
            $readable = true;
        } elseif ( 'publish' === $post->post_status ) {
            $readable = self::can_read_protected_content( $post );
        } else {
            $user_id = get_current_user_id();

            if ( $user_id ) {
                $readable = ( (int) $post->post_author === (int) $user_id )
                    || current_user_can( 'edit_others_posts' );
            }
        }

        /**
         * Filter the per-post read decision.
         *
         * Multi-organizer setups (WCFM, Dokan) can widen this so a store
         * manager may preview their vendors' unpublished events.
         *
         * @param bool     $readable Whether the current user may read the post.
         * @param \WP_Post $post     The post being tested.
         */
        return (bool) apply_filters( 'eventin_can_read_post', $readable, $post );
    }

    /**
     * May the current user see the content of this post?
     *
     * This is the post-password test, kept separate from status because the two
     * are independent: a post can be published AND protected at the same time.
     *
     * The rule is WordPress's own, the one core's REST controller applies in
     * WP_REST_Posts_Controller::can_access_password_content():
     *
     *   1. No password on the post — everyone may read it.
     *   2. The caller may edit the post — they see it on the edit screen
     *      anyway, so hiding it from an API they are entitled to would only
     *      break the event editor.
     *   3. The visitor typed the password on the front end, so WordPress set
     *      the `wp-postpass_` cookie. post_password_required() is core's own
     *      check of that cookie, hash and all.
     *
     * Anyone else is refused. Deliberately absent: core REST also accepts the
     * password as a `?password=` request parameter. Eventin's own front end
     * never needs it — the browser already carries the cookie — and leaving it
     * out means these routes cannot be used to guess a password.
     *
     * @param int|\WP_Post $post Post id or object.
     *
     * @return bool
     */
    public static function can_read_protected_content( $post ) {
        $post = get_post( $post );

        if ( ! $post ) {
            return false;
        }

        if ( '' === (string) $post->post_password ) {
            return true;
        }

        if ( current_user_can( 'edit_post', $post->ID ) ) {
            return true;
        }

        return ! post_password_required( $post );
    }

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
     * May the current user READ this speaker/organizer user record?
     *
     * Reading is narrower than managing, and deliberately does NOT require
     * `edit_user`. can_manage_user() demands it because writing to or deleting
     * an account is an edit of that account — but a Contributor holds no
     * `edit_users`, so requiring it for a read would lock them out of the very
     * speakers they created, which they can still see in the list.
     *
     * So the read test is simply: administrators, your own account, or a record
     * you created (the `author` user meta SpeakerController writes on create).
     * That matches exactly what SpeakerController::get_items() already shows —
     * the per-id read now returns nothing the list would have hidden.
     *
     * @param int $target_user_id User id to test.
     *
     * @return bool
     */
    public static function can_read_user( $target_user_id ) {
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

        // Own account is always readable.
        if ( (int) $target_user_id === (int) $user_id ) {
            return true;
        }

        $author = absint( get_user_meta( $target_user_id, 'author', true ) );
        $owns   = ( $author && $author === (int) $user_id );

        /**
         * Filter the per-user read decision.
         *
         * Multi-organizer setups (WCFM, Dokan) can widen this so a store
         * manager may view their vendors' speakers.
         *
         * @param bool $owns           Whether the current user may read the record.
         * @param int  $target_user_id User being tested.
         * @param int  $user_id        Current user id.
         */
        return (bool) apply_filters( 'eventin_can_read_user', $owns, $target_user_id, $user_id );
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

    /**
     * Can the current user act on this attendee?
     *
     * An attendee belongs to the organizer who owns the event it was booked
     * against. Unlike an order there is no customer branch: every attendee
     * route is staff-facing and gated on `etn_manage_attendee`, which no
     * customer holds. Buyers reach their own record through the token-scoped
     * `/attendees/{id}/edit-info` routes instead.
     *
     * Attendee rows carry the buyer's name, email and phone — the same PII the
     * customers table holds — so gating them on the collection capability alone
     * let anyone with attendee access read every organizer's buyers.
     *
     * @param int $attendee_id Attendee post id.
     *
     * @return bool
     */
    public static function can_manage_attendee( $attendee_id ) {
        $attendee_id = absint( $attendee_id );

        if ( ! $attendee_id ) {
            return false;
        }

        $attendee = get_post( $attendee_id );

        if ( ! $attendee || 'etn-attendee' !== $attendee->post_type ) {
            return false;
        }

        if ( self::is_unscoped() ) {
            return true;
        }

        $user_id = get_current_user_id();

        if ( ! $user_id ) {
            return false;
        }

        // The organizer who owns the event the attendee was booked against.
        $event_id = absint( get_post_meta( $attendee_id, 'etn_event_id', true ) );
        $owns     = false;

        if ( $event_id ) {
            $event = get_post( $event_id );
            $owns  = ( $event && (int) $event->post_author === (int) $user_id );
        }

        /**
         * Filter the per-attendee ownership decision.
         *
         * @param bool $owns        Whether the current user may act on the attendee.
         * @param int  $attendee_id Attendee being tested.
         * @param int  $user_id     Current user id.
         */
        return (bool) apply_filters( 'eventin_can_manage_attendee', $owns, $attendee_id, $user_id );
    }

    /**
     * Can the current user act on EVERY one of these attendees?
     *
     * All-or-nothing, matching can_manage_posts(): a bulk request that mixes
     * owned and foreign ids is refused outright rather than silently trimmed,
     * so a caller can never be told a batch succeeded when part of it was
     * dropped.
     *
     * @param array|int $attendee_ids Attendee ids.
     *
     * @return bool
     */
    public static function can_manage_attendees( $attendee_ids ) {
        $attendee_ids = array_filter( array_map( 'absint', (array) $attendee_ids ) );

        if ( ! $attendee_ids ) {
            return false;
        }

        foreach ( $attendee_ids as $attendee_id ) {
            if ( ! self::can_manage_attendee( $attendee_id ) ) {
                return false;
            }
        }

        return true;
    }
}
