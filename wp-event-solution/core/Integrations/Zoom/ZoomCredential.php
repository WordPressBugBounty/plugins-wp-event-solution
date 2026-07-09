<?php

namespace Eventin\Integrations\Zoom;

defined( 'ABSPATH' ) || exit;

/**
 * Manage zoom credentials
 *
 * @package Eventin/Integrations/Zoom
 */

/**
 * Manage zoom credentials
 */
class ZoomCredential {
    /**
     * Provider-scoped transient key prefix for the OAuth CSRF state.
     * Must stay distinct from other providers (e.g. Google Meet) so their
     * state tokens never overwrite each other on a shared settings read.
     *
     * @var string
     */
    const STATE_TRANSIENT_KEY = 'eventin_oauth_state_zoom_';

    /**
     * Get client id
     *
     * @return  string
     */
    public static function get_client_id(): string {
        return etn_get_option( 'zoom_client_id' );
    }

    /**
     * Get client secret
     *
     * @return  string
     */
    public static function get_client_secret(): string {
        return etn_get_option( 'zoom_client_secret' );
    }

    /**
     * Get auth url
     *
     * @return  string
     */
    public static function get_auth_url(): string {
        $user_id = get_current_user_id();

        if ( ! $user_id || ! current_user_can( 'manage_options' ) ) {
            return '';
        }

        $client_id     = self::get_client_id();
        $redirect_uri  = self::get_redirect_uri();
        $zoom_auth_url = 'https://zoom.us/oauth/authorize';

        // Reuse an existing, unexpired state instead of regenerating on every call.
        // get_auth_url() runs via the `eventin_settings` filter on every settings
        // read/write, and the admin reads settings multiple times. Regenerating the
        // token each time overwrote the stored transient, so the value Zoom echoed
        // back (from the link the browser actually followed) no longer matched storage
        // at callback time -> "Invalid OAuth state — authorization rejected."
        //
        // The key is also provider-scoped (`..._zoom_`). Previously Zoom and Google Meet
        // (eventin-pro) shared a single `eventin_oauth_state_{user_id}` transient, so
        // Google's get_auth_url() — which also runs on every settings read — clobbered
        // Zoom's token on the same request. Scoping per provider stops that collision.
        //
        // The transient is deleted on a successful callback, so the next connect rotates it.
        $state = get_transient( self::STATE_TRANSIENT_KEY . $user_id );

        if ( ! $state ) {
            $state = wp_generate_password( 32, false );
        }

        set_transient( self::STATE_TRANSIENT_KEY . $user_id, $state, 10 * MINUTE_IN_SECONDS );

        $args = [
            'response_type' => 'code',
            'redirect_uri'  => $redirect_uri,
            'client_id'     => $client_id,
            'state'         => $state,
        ];

        return add_query_arg( $args, $zoom_auth_url );
    }

    /**
     * Get auth redirect uri
     *
     * @return  string
     */
    public static function get_redirect_uri(): string {
        // Must byte-match the URL shown to the user in the Zoom configure modal
        // (core/Extensions/Extension.php uses home_url()). The OAuth callback is a
        // front-end rewrite endpoint, so home_url() (Site Address) is the correct base.
        // Using site_url() here caused Zoom error 4700 "Invalid redirect url" on installs
        // where WordPress Address (site_url) != Site Address (home_url).
        return home_url( '/eventin-integration/zoom-auth' );
    }
}
