<?php
/**
 * Make the URL path the only source of a route's URL parameters.
 *
 * WP_REST_Request::get_param() looks for a key in this order: JSON body, POST
 * body, query string, and only then the URL path. So on
 *
 *     GET /eventin/v2/speakers/<own_id>?id=<victim_id>
 *
 * `$request->get_url_params()['id']` is the caller's own id, but
 * `$request['id']` is the victim's. Ownership::route_subject_id() authorises
 * the first; around 60 handlers act on the second. That split is
 * CVE-2026-109994 (any Contributor could read any user's email and phone
 * through /speakers/{id}), and the same split existed on attendees, refunds,
 * coupons, templates and customers.
 *
 * Fixing each handler leaves the next new handler open. This guard instead
 * runs before the permission callback of every Eventin route and makes every
 * copy of a URL parameter equal to the URL value:
 *
 *   - parsed JSON, POST and query params (what `$request['id']`,
 *     get_param(), get_json_params() and get_query_params() read);
 *   - the raw body, because about 30 handlers run
 *     `json_decode( $request->get_body() )` themselves — and that works even
 *     with a non-JSON Content-Type, where WordPress never parses the body.
 *
 * A legitimate client never sends a different `id` in the body than in the
 * path, so nothing that works today changes.
 *
 * @package Eventin
 */

namespace Eventin\AccessControl;

defined( 'ABSPATH' ) || exit;

/**
 * RouteParamGuard class
 */
class RouteParamGuard {

    /**
     * Routes this guard owns: `eventin/v1`, `eventin/v2`, Pro (`eventin-pro`),
     * the add-ons (`eventin-addon-for-*`) and the legacy `etn` namespace.
     *
     * Case-insensitive on purpose: WordPress matches routes with the `i`
     * flag (WP_REST_Server::match_request_to_handler), so `/EVENTIN/v2/...`
     * reaches the same handler as `/eventin/v2/...`.
     *
     * @var string
     */
    const ROUTE_PATTERN = '#^/(eventin(-[a-z0-9_-]+)?|etn)(/|$)#i';

    /**
     * Hook the guard.
     *
     * `rest_request_before_callbacks` fires after the route is matched and
     * the URL parameters are known, but BEFORE the permission callback — so
     * the permission check and the handler see the same value. It also fires
     * for each request inside a `/batch/v1` call.
     *
     * @return void
     */
    public static function register() {
        add_filter( 'rest_request_before_callbacks', [ self::class, 'pin_url_params' ], 0, 3 );
    }

    /**
     * Copy every URL parameter over the same key everywhere else.
     *
     * @param mixed            $response Result so far (WP_Error stops dispatch).
     * @param array            $handler  Matched route handler.
     * @param \WP_REST_Request $request  The request.
     *
     * @return mixed Unchanged $response.
     */
    public static function pin_url_params( $response, $handler, $request ) {
        if ( ! $request instanceof \WP_REST_Request || ! preg_match( self::ROUTE_PATTERN, (string) $request->get_route() ) ) {
            return $response;
        }

        $url_params = (array) $request->get_url_params();

        if ( ! $url_params ) {
            return $response;
        }

        // Raw body first: set_body() resets the parsed JSON params, which are
        // then re-read from the corrected body.
        self::pin_raw_body( $request, $url_params );

        foreach ( $url_params as $key => $value ) {
            // set_param() overwrites the key in EVERY location where it
            // already exists (JSON, POST, GET). Only call it when a copy
            // exists: with no copy it would ADD the key to the body, and
            // handlers that save get_json_params() would store it.
            if ( self::is_shadowed( $request, $key, $value ) ) {
                $request->set_param( $key, $value );
            }
        }

        return $response;
    }

    /**
     * Rewrite top-level URL keys in a JSON body, whatever the Content-Type.
     *
     * The body is only re-encoded when a key actually differs, so ordinary
     * requests keep their exact bytes.
     *
     * @param \WP_REST_Request $request    Request.
     * @param array            $url_params URL parameters.
     *
     * @return void
     */
    private static function pin_raw_body( $request, $url_params ) {
        $body = (string) $request->get_body();

        if ( '' === $body ) {
            return;
        }

        $decoded = json_decode( $body, true );

        if ( ! is_array( $decoded ) ) {
            return;
        }

        $changed = false;

        foreach ( $url_params as $key => $value ) {
            if ( array_key_exists( $key, $decoded )
                && ( ! is_scalar( $decoded[ $key ] ) || (string) $decoded[ $key ] !== (string) $value ) ) {
                $decoded[ $key ] = $value;
                $changed         = true;
            }
        }

        if ( $changed ) {
            $request->set_body( wp_json_encode( $decoded ) );
        }
    }

    /**
     * Does the body or query string hold a different value for this key?
     *
     * Every location is checked, not just get_param(): get_param() shows only
     * the first match, so a query-string value can hide behind a matching one
     * in the body, and handlers that read get_query_params() would still see it.
     *
     * @param \WP_REST_Request $request Request.
     * @param string           $key     Parameter name.
     * @param mixed            $value   URL value.
     *
     * @return bool
     */
    private static function is_shadowed( $request, $key, $value ) {
        foreach ( [ $request->get_json_params(), $request->get_body_params(), $request->get_query_params() ] as $params ) {
            if ( is_array( $params ) && array_key_exists( $key, $params ) && $params[ $key ] !== $value ) {
                return true;
            }
        }

        return false;
    }
}
