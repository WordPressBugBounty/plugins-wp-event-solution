<?php

/**
 * Manage access control
 * 
 * @package Eventin
 */
namespace Eventin\AccessControl;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;

/**
 * Permission manager class
 */
class PermissionManager implements HookableInterface {
    /**
     * Register all required hooks
     *
     * @return  void
     */
    public function register_hooks(): void {
        add_filter( 'map_meta_cap', [ $this, 'manage_permissions' ], 10, 4 );
        add_action( 'admin_init', [ $this, 'allow_qr_scan_for_vendor' ], 1 );
        add_action( 'admin_init', [ $this, 'fix_scanner_plugin_conflicts' ], 2 );
    }

    /**
     * Manage permissions
     *
     * @param   array  $caps     [$caps description]
     * @param   string  $cap      [$cap description]
     * @param   [type]  $user_id  [$user_id description]
     * @param   [type]  $args     [$args description]
     *
     * @return  [type]            [return description]
     */
    public function manage_permissions( $caps, $cap, $user_id, $args ) {
        $permissions = Permission::get_role_permissions();

        // Scope this filter to the plugin's own capabilities. The managed set is
        // the union of every capability the role-permission map assigns, so caps
        // added by third parties through the `eventin_role_permissions` filter
        // (e.g. Pro) keep working. Any capability the plugin does not manage —
        // every WordPress core cap such as manage_options, edit_plugins,
        // promote_users — is returned untouched so this filter can never grant a
        // core privilege to anyone. (CVE-2026-75983)
        $managed_caps = [];
        foreach ( $permissions as $role_caps ) {
            if ( is_array( $role_caps ) ) {
                $managed_caps = array_merge( $managed_caps, $role_caps );
            }
        }

        if ( ! in_array( $cap, $managed_caps, true ) ) {
            return $caps;
        }

        // Get the user’s roles
        $user = get_user_by('id', $user_id);
        if ( ! $user || empty( $user->roles ) ) {
            return $caps; // No roles assigned
        }

        // Grant a managed capability only when one of the user's real roles is
        // configured to hold it. User ID 1 gets no special treatment: capability
        // decisions come from the user's actual roles alone, so a user 1 that has
        // been demoted cannot regain privileges here. (CVE-2026-75983)
        foreach ( $user->roles as $role ) {
            // If the role has defined permissions in our options
            if ( isset( $permissions[$role] ) && in_array( $cap, $permissions[$role], true ) ) {
                // Grant the capability by mapping it to 'exist' or any other basic capability
                return ['exist'];
            }
        }

        return $caps;
    }

    /**
     * Prevent third-party frontend plugins from breaking the ticket scanner page.
     *
     * scanner.php (eventin-pro) calls wp_head() and wp_footer() in an admin context.
     * Plugins like wp-cafe register frontend hooks on those actions that call WooCommerce
     * cart methods (WC()->cart) which are null in admin — causing a fatal error and a
     * broken layout. This method removes those hooks and dequeues the conflicting styles
     * before they can execute.
     *
     * @return void
     */
    public function fix_scanner_plugin_conflicts(): void {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only check; etn_action compared to literal string only.
        if ( ! isset( $_GET['etn_action'] ) || 'ticket_scanner' !== sanitize_text_field( wp_unslash( $_GET['etn_action'] ) ) ) {
            return;
        }

        // Remove frontend plugin styles before wp_print_styles fires at priority 8.
        add_action(
            'wp_head',
            function () {
                global $wp_styles;
                if ( empty( $wp_styles->registered ) ) {
                    return;
                }
                foreach ( $wp_styles->registered as $handle => $style ) {
                    if ( ! empty( $style->src ) && (
                        strpos( $style->src, '/wp-cafe/' ) !== false ||
                        strpos( $style->src, '/wpcafe-pro/' ) !== false
                    ) ) {
                        wp_dequeue_style( $handle );
                    }
                }
            },
            7
        );

        // Remove frontend plugin hooks from wp_footer() before they fire.
        // wp-cafe's Mini_Cart::add_mini_cart() calls WC()->cart which is null in admin,
        // causing a fatal error that truncates scanner page output.
        add_action(
            'wp_footer',
            function () {
                global $wp_filter;
                if ( empty( $wp_filter['wp_footer'] ) ) {
                    return;
                }
                foreach ( $wp_filter['wp_footer']->callbacks as $priority => &$callbacks ) {
                    foreach ( $callbacks as $key => $callback ) {
                        if (
                            is_array( $callback['function'] )
                            && is_object( $callback['function'][0] )
                            && isset( $callback['function'][1] )
                            && 'add_mini_cart' === $callback['function'][1]
                        ) {
                            unset( $callbacks[ $key ] );
                        }
                    }
                }
            },
            -1
        );
    }

    /**
     * Allow users with QR scan permission to access the ticket scanner admin page
     * even when Dokan blocks vendor/seller roles from wp-admin.
     *
     * Runs at admin_init priority 1 — before Dokan's block_admin_access at priority 10.
     *
     * @return void
     */
    public function allow_qr_scan_for_vendor(): void {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only check; etn_action compared to literal string only.
        if ( ! isset( $_GET['etn_action'] ) || 'ticket_scanner' !== sanitize_text_field( wp_unslash( $_GET['etn_action'] ) ) ) {
            return;
        }

        if ( ! is_user_logged_in() || ! current_user_can( 'etn_manage_qr_scan' ) ) {
            return;
        }

        if ( ! function_exists( 'dokan' ) ) {
            return;
        }

        $container = dokan()->get_container();
        if ( $container->has( 'core' ) ) {
            remove_action( 'admin_init', [ $container->get( 'core' ), 'block_admin_access' ] );
        }
    }
}

