<?php

/**
 * Integration provider;
 */
namespace Eventin\Integrations;

defined( 'ABSPATH' ) || exit;

use Eventin\Integrations\Zoom\Zoom;
use Eventin\Integrations\Zoom\ZoomCredential;
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

        $matched_key = '';

        if ( $state ) {
            // Accept the first key whose stored value actually matches. Stopping at the
            // first key that merely holds *a* value would let a stale scoped token
            // shadow a live legacy one during a pro up/downgrade mid-flow.
            foreach ( $this->get_state_transient_keys( $query_var, $user_id ) as $state_key ) {
                $stored_state = get_transient( $state_key );

                if ( $stored_state && hash_equals( (string) $stored_state, $state ) ) {
                    $matched_key = $state_key;
                    break;
                }
            }
        }

        if ( ! $matched_key ) {
            wp_die( esc_html__( 'Invalid OAuth state — authorization rejected.', 'eventin' ), '', [ 'response' => 403 ] );
        }

        delete_transient( $matched_key );

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
     * Resolve the transient keys that may hold the OAuth CSRF state for a callback.
     *
     * Every provider writes its state under a provider-scoped key so two connect
     * flows can't clobber each other (see ZoomCredential::STATE_TRANSIENT_KEY).
     * This callback is shared by all providers, so the key must be derived from
     * the endpoint being hit rather than hardcoded to Zoom's — hardcoding it made
     * the Google Meet callback reject every authorization, valid state included.
     *
     * Only Zoom is mapped here, because only Zoom lives in this plugin. Providers
     * shipped elsewhere register their scoped key through the
     * `eventin_oauth_state_transient_keys` filter, so this file never has to name a
     * key it does not own. The legacy unscoped key stays as the default for
     * unmapped providers: eventin-pro <= 4.1.x writes Google's state to
     * `eventin_oauth_state_{user_id}` and does not register the filter, and pro
     * updates lag the free plugin. Same token, same TTL — only the storage key
     * differs, so the fallback costs nothing in CSRF strength. Drop it once the pro
     * minimum version is bumped past the release that registers the filter.
     *
     * @param   string  $query_var  Endpoint being authenticated (e.g. `google-auth`).
     * @param   int     $user_id    Current user id.
     *
     * @return  string[]  Candidate transient keys, most specific first.
     */
    protected function get_state_transient_keys( $query_var, $user_id ) {
        $prefixes = [
            'zoom-auth' => [ ZoomCredential::STATE_TRANSIENT_KEY ],
        ];

        // Providers this plugin does not own (Google Meet lives in eventin-pro) fall
        // back to the legacy unscoped key and prepend their own scoped key via the
        // filter below. Keeping the legacy default here is what lets a newer free
        // plugin still authorize against an older pro that has not been updated.
        $keys = isset( $prefixes[ $query_var ] ) ? $prefixes[ $query_var ] : [ 'eventin_oauth_state_' ];

        /**
         * Filter the OAuth state transient key prefixes for an integration callback.
         *
         * @param   string[]  $keys       Key prefixes, most specific first.
         * @param   string    $query_var  Endpoint being authenticated.
         */
        $keys = apply_filters( 'eventin_oauth_state_transient_keys', $keys, $query_var );

        return array_map(
            function ( $prefix ) use ( $user_id ) {
                return $prefix . $user_id;
            },
            (array) $keys
        );
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
