<?php

/**
 * Integration provider;
 */
namespace Eventin\Integrations;

defined( 'ABSPATH' ) || exit;

use Eventin\Integrations\Zoom\Zoom;
use Eventin\Integrations\Zoom\ZoomToken;
use Eventin\Interfaces\HookableInterface;

/**
 * Integration service
 *
 * @package Eventin
 */
class Integration implements HookableInterface {
    /**
     * Register service
     *
     * @return  void
     */
    public function register_hooks(): void {
        add_action( 'template_redirect', [$this, 'authenticate'] );

        add_filter( 'eventin_settings', [$this, 'added_zoom_connection'] );
    }

    /**
     * Authenticate integration
     *
     * @return  void
     */
    public function authenticate() {
        $query_var = get_query_var( 'eventin-integration', false );

        if ( ! $query_var ) {
            return;
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- OAuth2 callback; CSRF is mitigated by the explicit state check below.
        $code  = isset( $_GET['code'] ) ? sanitize_text_field( wp_unslash( $_GET['code'] ) ) : '';
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- OAuth2 state is the CSRF primitive; validated below.
        $state = isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( $_GET['state'] ) ) : '';

        $user_id = get_current_user_id();

        if ( ! $user_id || ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have permission to authorize integrations.', 'eventin' ), '', [ 'response' => 403 ] );
        }

        $stored_state = get_transient( 'eventin_oauth_state_' . $user_id );

        if ( ! $state || ! $stored_state || ! hash_equals( (string) $stored_state, $state ) ) {
            wp_die( esc_html__( 'Invalid OAuth state — authorization rejected.', 'eventin' ), '', [ 'response' => 403 ] );
        }

        delete_transient( 'eventin_oauth_state_' . $user_id );

        switch ( $query_var ) {
        case 'zoom-auth':
            $this->zoom_auth( $code );
            break;
        }

        do_action( 'eventin_integration_auth', $query_var, $code );

        $redirect_url = admin_url( 'admin.php?page=eventin#/extensions' );

        wp_redirect( $redirect_url );
        exit;
    }

    /**
     * Authentication for zoom
     *
     * @param   string  $code
     *
     * @return void
     */
    public function zoom_auth( $code = '' ) {
        ZoomToken::get_remote( $code );
    }

    /**
     * Added zoom connection settings
     *
     * @param   array  $settings  Setting
     *
     * @return  array $settings
     */
    public function added_zoom_connection( $settings ) {
        $settings['zoom_connected'] = Zoom::is_connected();

        return $settings;
    }
}
