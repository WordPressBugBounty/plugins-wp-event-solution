<?php
/**
 * HubSpot REST client
 *
 * @package Eventin
 */
namespace Eventin\Integrations\HubSpot;

defined( 'ABSPATH' ) || exit;

use WP_Error;

/**
 * Thin wrapper around the HubSpot REST API.
 *
 * Auth is a static Bearer credential stored as the `hubspot_token` Eventin
 * option: a HubSpot Service Key (Settings → Integrations → Service Keys) or a
 * legacy private app token. HubSpot stopped allowing new legacy private apps
 * in late 2026, so Service Keys are what new portals can create; both are sent
 * the same way. There is no OAuth dance, so nothing is registered in
 * `Eventin\Integrations\Integration::authenticate()`.
 *
 * HubSpot versions its surface by date, and the segment sits in a *different*
 * position per API family (`crm/objects/{version}/…` vs `events/{version}/…`).
 * Every path in this integration is therefore built through the helpers below
 * so a version bump is a one-line change to `API_VERSION`.
 */
class HubSpotClient {
    /**
     * API host.
     */
    const API_BASE = 'https://api.hubapi.com';

    /**
     * Dated API version segment shared by every family.
     */
    const API_VERSION = '2026-03';

    /**
     * Option holding the Service Key / legacy private app token.
     */
    const TOKEN_OPTION = 'hubspot_token';

    /**
     * Option holding the extension on/off status (written by ExtensionController).
     */
    const STATUS_OPTION = 'hubspot_api';

    /**
     * Access token used for this instance.
     *
     * @var string
     */
    private $token;

    /**
     * Constructor.
     *
     * @param string $token Optional explicit token — used by the connect flow to
     *                      validate a token before it is stored.
     */
    public function __construct( $token = '' ) {
        $this->token = $token ? $token : self::get_token();
    }

    /**
     * Stored Service Key / legacy private app token.
     *
     * @return string
     */
    public static function get_token() {
        $token = etn_get_option( self::TOKEN_OPTION );

        return is_string( $token ) ? trim( $token ) : '';
    }

    /**
     * Whether a token is stored.
     *
     * @return bool
     */
    public static function has_token() {
        return '' !== self::get_token();
    }

    /**
     * Whether the HubSpot extension card is toggled on.
     *
     * @return bool
     */
    public static function is_extension_enabled() {
        $status = etn_get_option( self::STATUS_OPTION );

        return $status && 'off' !== $status;
    }

    /**
     * Mask a token for display (never return the full token to the browser).
     *
     * @param string $token Token.
     *
     * @return string
     */
    public static function mask_token( $token ) {
        $token = (string) $token;

        if ( '' === $token ) {
            return '';
        }

        // A token too short to mask is still a stored token — usually a truncated or
        // mis-pasted one. Returning '' made the modal fall back to its generic
        // placeholder, hiding the very case worth showing.
        if ( strlen( $token ) < 8 ) {
            return str_repeat( '•', 8 );
        }

        return substr( $token, 0, 4 ) . str_repeat( '•', 8 ) . substr( $token, -4 );
    }

    /**
     * Path for a CRM object family, e.g. `/crm/objects/2026-03/contacts/batch/upsert`.
     *
     * @param string $object_type Object type slug (contacts, companies, …).
     * @param string $suffix      Optional trailing path, without a leading slash.
     *
     * @return string
     */
    public static function object_path( $object_type, $suffix = '' ) {
        $path = sprintf( '/crm/objects/%s/%s', self::API_VERSION, $object_type );

        return $suffix ? $path . '/' . ltrim( $suffix, '/' ) : $path;
    }

    /**
     * Path for the properties family, e.g. `/crm/properties/2026-03/contacts`.
     *
     * @param string $object_type Object type slug.
     * @param string $suffix      Optional trailing path, without a leading slash.
     *
     * @return string
     */
    public static function property_path( $object_type, $suffix = '' ) {
        $path = sprintf( '/crm/properties/%s/%s', self::API_VERSION, $object_type );

        return $suffix ? $path . '/' . ltrim( $suffix, '/' ) : $path;
    }

    /**
     * Path for the account-info family, e.g. `/account-info/2026-03/details`.
     *
     * @param string $suffix Trailing path, without a leading slash.
     *
     * @return string
     */
    public static function account_path( $suffix ) {
        return sprintf( '/account-info/%s/%s', self::API_VERSION, ltrim( $suffix, '/' ) );
    }

    /**
     * Path for the events family, e.g. `/events/2026-03/send`.
     *
     * @param string $suffix Trailing path, without a leading slash.
     *
     * @return string
     */
    public static function event_path( $suffix ) {
        return sprintf( '/events/%s/%s', self::API_VERSION, ltrim( $suffix, '/' ) );
    }

    /**
     * GET request.
     *
     * @param string $path Path built through one of the *_path() helpers.
     * @param array  $args Query args.
     *
     * @return array|WP_Error Decoded body, or WP_Error.
     */
    public function get( $path, $args = [] ) {
        if ( $args ) {
            $path = add_query_arg( $args, $path );
        }

        return $this->request( 'GET', $path );
    }

    /**
     * POST request.
     *
     * @param string $path Path.
     * @param array  $body Body, JSON-encoded on the way out.
     *
     * @return array|WP_Error
     */
    public function post( $path, $body = [] ) {
        return $this->request( 'POST', $path, $body );
    }

    /**
     * PATCH request.
     *
     * @param string $path Path.
     * @param array  $body Body.
     *
     * @return array|WP_Error
     */
    public function patch( $path, $body = [] ) {
        return $this->request( 'PATCH', $path, $body );
    }

    /**
     * Perform a request.
     *
     * Callers never get an exception — a failed call is a WP_Error, and the
     * dispatcher swallows those so a CRM outage can never fail an order.
     *
     * @param string     $method HTTP method.
     * @param string     $path   Path.
     * @param array|null $body   Optional body.
     *
     * @return array|WP_Error
     */
    public function request( $method, $path, $body = null ) {
        if ( ! $this->token ) {
            return new WP_Error(
                'eventin_hubspot_missing_token',
                __( 'HubSpot is not connected. Add a HubSpot Service Key first.', 'eventin' ),
                [ 'status' => 400 ]
            );
        }

        $args = [
            'method'  => $method,
            'timeout' => 20,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
        ];

        if ( null !== $body ) {
            $args['body'] = wp_json_encode( $body );
        }

        $response = wp_remote_request( self::API_BASE . $path, $args );

        if ( is_wp_error( $response ) ) {
            $this->log( sprintf( '%s %s transport error: %s', $method, $path, $response->get_error_message() ) );

            return $response;
        }

        $code = wp_remote_retrieve_response_code( $response );
        $raw  = wp_remote_retrieve_body( $response );
        $data = json_decode( $raw, true );
        $data = is_array( $data ) ? $data : [];

        if ( $code >= 200 && $code < 300 ) {
            return $data;
        }

        // 429 carries the throttle window; surface it so callers can report it
        // rather than silently dropping contacts on a burst.
        $retry_after = wp_remote_retrieve_header( $response, 'retry-after' );

        $message = ! empty( $data['message'] )
            ? $data['message']
            : sprintf(
                /* translators: %d: HTTP status code returned by HubSpot. */
                __( 'HubSpot returned an unexpected response (HTTP %d).', 'eventin' ),
                (int) $code
            );

        $this->log( sprintf( '%s %s failed (%d): %s', $method, $path, $code, $message ) );

        return new WP_Error(
            'eventin_hubspot_api_error',
            $message,
            [
                'status'      => (int) $code,
                'category'    => isset( $data['category'] ) ? $data['category'] : '',
                'retry_after' => $retry_after ? (int) $retry_after : 0,
                'errors'      => isset( $data['errors'] ) ? $data['errors'] : [],
            ]
        );
    }

    /**
     * Verify the token by reading a single contact page.
     *
     * Uses the contacts read scope the integration needs anyway, so a token
     * missing that scope fails here instead of silently at order time.
     *
     * @return true|WP_Error
     */
    public function verify_token() {
        $response = $this->get( self::object_path( 'contacts' ), [ 'limit' => 1 ] );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        return true;
    }

    /**
     * Portal (Hub) id for the connected account.
     *
     * Failure is non-fatal — the id is only used for display and for the
     * `pe{HubID}_` event-name prefix, which the event-definition response also
     * returns.
     *
     * @return int
     */
    public function get_portal_id() {
        $response = $this->get( self::account_path( 'details' ) );

        if ( is_wp_error( $response ) || empty( $response['portalId'] ) ) {
            return 0;
        }

        return (int) $response['portalId'];
    }

    /**
     * Log only under WP_DEBUG (matches MailPoet/MailMint).
     *
     * @param string $message Message.
     *
     * @return void
     */
    private function log( $message ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( sprintf( '[Eventin HubSpot] %s', $message ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
        }
    }
}
