<?php
/**
 * Eventin uninstall handler.
 *
 * Runs when the plugin is deleted from the WordPress Plugins screen.
 * Clears the onboarding flag so the setup wizard re-appears on reinstall.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'etn_wizard' );
