<?php

namespace Eventin\Admin;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;

/**
 * Per-user light / dark / system colour mode for the Eventin admin screens.
 *
 * The mode is printed as a body class server side so the first paint is already
 * in the right theme; the SPA reads it back from the body and saves changes
 * through the core `/wp/v2/users/me` endpoint.
 *
 * @package Eventin/Admin
 */
class ColorMode implements HookableInterface {
    /**
     * User meta key holding the chosen mode
     */
    const META_KEY = 'etn_admin_color_mode';

    /**
     * Allowed modes, the first one is the default
     */
    const MODES = [ 'light', 'dark', 'system' ];

    /**
     * Register service
     *
     * @return  void
     */
    public function register_hooks(): void {
        $this->register_meta();

        add_filter( 'admin_body_class', [ $this, 'add_body_class' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
    }

    /**
     * Expose the mode on the users REST endpoint so the SPA can save it
     *
     * @return  void
     */
    public function register_meta() {
        register_meta(
            'user',
            self::META_KEY,
            [
                'type'              => 'string',
                'single'            => true,
                'default'           => self::MODES[0],
                'sanitize_callback' => [ $this, 'sanitize_mode' ],
                'auth_callback'     => function ( $allowed, $meta_key, $user_id ) {
                    return current_user_can( 'edit_user', $user_id );
                },
                'show_in_rest'      => [
                    'schema' => [
                        'type' => 'string',
                        'enum' => self::MODES,
                    ],
                ],
            ]
        );
    }

    /**
     * Fall back to light for anything outside the allowed modes
     *
     * @param   mixed  $mode  Raw value
     *
     * @return  string
     */
    public function sanitize_mode( $mode ) {
        return in_array( $mode, self::MODES, true ) ? $mode : self::MODES[0];
    }

    /**
     * Current user's mode
     *
     * @return  string
     */
    public function get_mode() {
        return $this->sanitize_mode( get_user_meta( get_current_user_id(), self::META_KEY, true ) );
    }

    /**
     * Add `etn-theme-{mode}` to the body on Eventin screens only
     *
     * @param   string  $classes  Space separated admin body classes
     *
     * @return  string
     */
    public function add_body_class( $classes ) {
        if ( ! $this->is_eventin_screen() ) {
            return $classes;
        }

        return $classes . ' etn-theme-' . $this->get_mode();
    }

    /**
     * Load the dark palette on Eventin screens only
     *
     * @return  void
     */
    public function enqueue_styles() {
        if ( $this->is_eventin_screen() ) {
            wp_enqueue_style( 'etn-admin-color-mode' );
        }
    }

    /**
     * Whether this is the Eventin admin SPA (the only screen with tokenised
     * colours; PHP-rendered Eventin pages and the onboarding wizard stay light)
     *
     * @return  bool
     */
    private function is_eventin_screen() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only screen check.
        $page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

        return 'eventin' === $page;
    }
}
