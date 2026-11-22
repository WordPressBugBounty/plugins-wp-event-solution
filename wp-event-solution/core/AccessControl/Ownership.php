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
            $user_id = self::current_user_owner_id();

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

        $owner_id = self::current_user_owner_id();

        if ( $owner_id && (int) $post->post_author === (int) $owner_id ) {
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
         * This is for genuinely site-wide administrators. Store managers must
         * remain scoped and map to their store through
         * eventin_current_user_owner_id; making them unscoped would grant access
         * to every vendor's objects.
         *
         * @param bool $unscoped Whether ownership checks are skipped.
         */
        return (bool) apply_filters( 'eventin_ownership_is_unscoped', current_user_can( 'manage_options' ) );
    }

    /**
     * Get the post author that represents the current management scope.
     *
     * Eventin normally treats the logged-in WordPress user as the owner of an
     * event. In Dokan, vendor staff act on behalf of the store owner and Dokan
     * stores vendor-owned posts under that owner's user id. Using the staff
     * account here makes the store look empty and makes object-level ownership
     * checks fail even though Dokan authorised the staff member for that store.
     *
     * Dokan's own resolver is deliberately used instead of request data: it
     * resolves vendor staff through the server-side `_vendor_id` relation and
     * returns the current user unchanged for ordinary users. Other multivendor
     * integrations may provide the same mapping through the filter below.
     *
     * This is not an administrator bypass. Administrators remain unscoped only
     * through is_unscoped(); this method merely identifies which post_author a
     * scoped user is allowed to manage.
     *
     * @return int Effective owner user id, or 0 for a logged-out caller.
     */
    public static function current_user_owner_id() {
        $user_id  = get_current_user_id();
        $owner_id = $user_id;

        // Never let an integration turn an anonymous request into an owned
        // scope. Besides being nonsensical, that would expose unpublished posts
        // if a third-party filter returned a non-zero id for user 0.
        if ( ! $user_id ) {
            return 0;
        }

        if ( function_exists( 'dokan_get_current_user_id' ) ) {
            $dokan_owner_id = absint( dokan_get_current_user_id() );

            if ( $dokan_owner_id ) {
                $owner_id = $dokan_owner_id;
            }
        }

        /**
         * Filter the post author that represents the current user's store scope.
         *
         * Returning 0 denies scoped access. Collection controllers explicitly
         * turn that into an empty query rather than letting WP_Query interpret
         * author=0 as an unscoped request.
         *
         * @param int $owner_id Effective post author id.
         * @param int $user_id  Logged-in WordPress user id.
         */
        return absint( apply_filters( 'eventin_current_user_owner_id', $owner_id, $user_id ) );
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

        $user_id = self::current_user_owner_id();

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

        // WordPress's own gate, or — for managers without `edit_users` (Editor) — a pure
        // speaker/organizer account holding nothing the caller lacks. Admin-promoted accounts stay locked.
        if ( ! current_user_can( 'edit_user', $target_user_id ) && ! self::is_subordinate_person( $target_user_id ) ) {
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
     * Does the current user already hold every capability in this list?
     * Managing an account that carries only these caps is then no escalation (CVE-2026-84905).
     *
     * @param array $caps Capability => grant map (role capabilities / WP_User::$allcaps).
     * @return bool
     */
    public static function holds_all_caps( array $caps ) {
        // Speaker-portal marker caps (unused by Eventin, declared by Booktics/Timetics roles).
        $exempt = (array) apply_filters( 'eventin_role_grant_exempt_caps', [ 'manage_eventin', 'read_booking', 'read_meeting', 'edit_profile' ] );

        foreach ( array_keys( array_filter( $caps ) ) as $cap ) {
            if ( ! in_array( $cap, $exempt, true ) && ! current_user_can( $cap ) ) {
                return false;
            }
        }

        return true;
    }

    /**
     * May the current user hand these roles to a new or existing account without escalating?
     *
     * @param string[] $roles Role names.
     * @return bool
     */
    public static function can_grant_roles( array $roles ) {
        foreach ( $roles as $role_name ) {
            $role = get_role( $role_name );

            if ( $role && ! self::holds_all_caps( $role->capabilities ) ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Is this account only a speaker/organizer whose every capability the current user holds?
     *
     * @param int $target_user_id User id.
     * @return bool
     */
    private static function is_subordinate_person( $target_user_id ) {
        $user = get_userdata( $target_user_id );

        if ( ! $user || ! $user->roles || array_diff( $user->roles, [ 'etn-speaker', 'etn-organizer' ] ) ) {
            return false;
        }

        // allcaps also lists the role names themselves; drop them, keep role + per-user caps.
        return self::holds_all_caps( array_diff_key( $user->allcaps, array_flip( $user->roles ) ) );
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

        $user_id  = get_current_user_id();
        $owner_id = self::current_user_owner_id();

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
            $owns  = ( $event && (int) $event->post_author === (int) $owner_id );
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

        $user_id  = get_current_user_id();
        $owner_id = self::current_user_owner_id();

        if ( ! $user_id ) {
            return false;
        }

        // The organizer who owns the event the attendee was booked against.
        $event_id = absint( get_post_meta( $attendee_id, 'etn_event_id', true ) );
        $owns     = false;

        if ( $event_id ) {
            $event = get_post( $event_id );
            $owns  = ( $event && (int) $event->post_author === (int) $owner_id );
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

    /**
     * Is this user a speaker or an organizer?
     *
     * Event organizer/speaker lists are printed with the user's email and
     * phone (event pages, JSON-LD, export, organizer widgets), so an ordinary
     * account (e.g. an administrator) must never be shown as one.
     *
     * The role alone is not enough to recognise a real speaker: on live sites
     * records made on the Speakers screen can lose the `etn-speaker` /
     * `etn-organizer` role later (sandbox users 41 and 67 are now plain
     * Subscribers; 367 has no role at all). So a user also counts when the
     * Speakers screen made them — SpeakerController writes the `author` meta on
     * create — or when they carry a speaker category. A caller cannot put
     * either on someone else's account without `edit_user` on it.
     *
     * @param int $user_id User id.
     *
     * @return bool
     */
    public static function is_speaker_or_organizer( $user_id ) {
        $user = get_userdata( absint( $user_id ) );

        if ( ! $user ) {
            return false;
        }

        $is_person = array_intersect( [ 'etn-speaker', 'etn-organizer' ], (array) $user->roles )
            || '' !== (string) get_user_meta( $user->ID, 'author', true )
            || ! empty( get_user_meta( $user->ID, 'etn_speaker_category', true ) );

        /**
         * Filter whether a user is shown as a speaker/organizer.
         *
         * @param bool     $is_person Whether the user counts as a speaker/organizer.
         * @param \WP_User $user      The user.
         */
        return (bool) apply_filters( 'eventin_is_speaker_or_organizer', (bool) $is_person, $user );
    }

    /**
     * Keep only the speaker/organizer ids the current user may attach to an event.
     *
     * Event create/update and event import used to store ANY user id here. A
     * Contributor could put an administrator's id in `organizer`, then export
     * the event or preview it and read that user's email and phone
     * (CVE-2026-109994 follow-up). An id is kept when it is a speaker or
     * organizer AND either the caller may read that record (can_read_user():
     * their own, or one they created) or it is already saved on this event —
     * so editing an event never drops people someone else attached.
     *
     * @param mixed  $ids      Submitted ids.
     * @param int    $event_id Existing event id, 0 on create.
     * @param string $meta_key `etn_event_organizer` or `etn_event_speaker`.
     *
     * @return array Allowed ids, in submitted order.
     */
    public static function filter_assignable_people( $ids, $event_id = 0, $meta_key = '' ) {
        if ( ! is_array( $ids ) ) {
            return [];
        }

        $existing = [];

        if ( $event_id && $meta_key ) {
            $existing = array_map( 'absint', (array) get_post_meta( absint( $event_id ), $meta_key, true ) );
        }

        $allowed = [];

        foreach ( $ids as $id ) {
            $user_id = absint( $id );

            if ( ! $user_id ) {
                continue;
            }

            // Already on this event: always kept, so saving an existing event
            // never drops someone (even a record that lost its role).
            if ( in_array( $user_id, $existing, true ) ) {
                $allowed[] = $id;
                continue;
            }

            if ( self::is_speaker_or_organizer( $user_id ) && self::can_read_user( $user_id ) ) {
                $allowed[] = $id;
            }
        }

        return array_values( $allowed );
    }

    /**
     * May the current viewer see this event's private join link (meeting_link)?
     *
     * The REST layer treats meeting_link as management-only. The
     * [etn_zoom_api_link] shortcode printed it for any event id written in the
     * shortcode, so a Contributor could preview a draft with
     * [etn_zoom_api_link meeting_id="<admin event>"] and read the Zoom URL and
     * its pwd token (Patchstack 866bfb05).
     *
     * Allowed when any of these is true:
     *  1. The viewer can manage or edit the event.
     *  2. The post that holds the shortcode was written by someone who can edit
     *     the event. That author chose to publish the link (e.g. an admin's
     *     public "Join our webinar" page). A Contributor's own post never passes
     *     this for someone else's event.
     *  3. The logged-in viewer holds a paid ticket for the event (an attendee
     *     with status success, or a completed order, under their email).
     *
     * Case 2 needs the event to be published (the author chose to share the
     * link, so the event password is not asked again). Case 3 needs the event
     * to be readable by the viewer (published, password entered if any).
     *
     * @since 4.1.31
     *
     * @param int               $event_id     Event id.
     * @param int|\WP_Post|null $context_post Post that holds the shortcode/block, if any.
     *
     * @return bool
     */
    public static function can_view_meeting_link( $event_id, $context_post = null ) {
        $event_id = absint( $event_id );
        $event    = $event_id ? get_post( $event_id ) : null;

        if ( ! $event || 'etn' !== $event->post_type ) {
            return false;
        }

        $allowed = false;

        if ( self::can_manage_post( $event_id, 'etn' ) || current_user_can( 'edit_post', $event_id ) ) {
            $allowed = true;
        } elseif ( 'publish' === $event->post_status ) {
            $context_post = $context_post ? get_post( $context_post ) : null;

            if ( $context_post && $context_post->post_author ) {
                $author_id = (int) $context_post->post_author;

                // The author must be allowed to edit the event. A Contributor's
                // post (draft, preview or published by an admin later) keeps
                // the Contributor as author, so it never passes for an event
                // they do not own.
                $allowed = (int) $event->post_author === $author_id
                    || user_can( $author_id, 'manage_options' )
                    || user_can( $author_id, 'edit_post', $event_id );
            }

            if ( ! $allowed && self::can_read_post( $event_id, 'etn' ) ) {
                $allowed = self::current_user_holds_ticket( $event_id );
            }
        }

        /**
         * Filter whether the current viewer may see an event's join link.
         *
         * @since 4.1.31
         *
         * @param bool          $allowed      Decision.
         * @param \WP_Post      $event        The event.
         * @param \WP_Post|null $context_post Post holding the shortcode, if any.
         */
        return (bool) apply_filters( 'eventin_can_view_meeting_link', $allowed, $event, $context_post ? get_post( $context_post ) : null );
    }

    /**
     * Does the logged-in user hold a paid ticket for this event?
     *
     * Matches by the user's email: a successful attendee record, or a
     * completed order, for this event (or its recurring parent's child).
     *
     * @since 4.1.31
     *
     * @param int $event_id Event id.
     *
     * @return bool
     */
    public static function current_user_holds_ticket( $event_id ) {
        $user = wp_get_current_user();

        if ( ! $user || ! $user->exists() || empty( $user->user_email ) ) {
            return false;
        }

        $email = $user->user_email;

        $attendees = get_posts(
            [
                'post_type'      => 'etn-attendee',
                'post_status'    => 'any',
                'posts_per_page' => 1,
                'fields'         => 'ids',
                'no_found_rows'  => true,
                'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                    [ 'key' => 'etn_event_id', 'value' => (string) $event_id ],
                    [ 'key' => 'etn_email', 'value' => $email ],
                    [ 'key' => 'etn_status', 'value' => 'success' ],
                ],
            ]
        );

        if ( $attendees ) {
            return true;
        }

        $orders = get_posts(
            [
                'post_type'      => 'etn-order',
                'post_status'    => 'any',
                'posts_per_page' => 1,
                'fields'         => 'ids',
                'no_found_rows'  => true,
                'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                    [ 'key' => 'event_id', 'value' => (string) $event_id ],
                    [ 'key' => 'customer_email', 'value' => $email ],
                    [ 'key' => 'status', 'value' => 'completed' ],
                ],
            ]
        );

        return ! empty( $orders );
    }
}
