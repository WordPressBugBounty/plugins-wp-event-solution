<?php
/**
 * Updater for version 4.1.14
 *
 * @package Eventin\Upgrade
 */

namespace Eventin\Upgrade\Upgraders;

defined( 'ABSPATH' ) || exit;

/**
 * Re-validates the color settings in wp_options.etn_event_options against
 * sanitize_hex_color, resetting any non-hex value (e.g. legacy XSS payloads
 * accepted by pre-4.1.x installs) to the plugin default.
 *
 * @since 4.1.14
 */
class V_4_1_14 implements UpdateInterface {
    /**
     * Run the updater
     *
     * @return void
     */
    public function run() {
        $option_name = 'etn_event_options';
        $settings    = get_option( $option_name, [] );

        if ( ! is_array( $settings ) ) {
            return;
        }

        $defaults = [
            'etn_primary_color'   => '#5D78FF',
            'etn_secondary_color' => '',
        ];

        $changed = false;

        foreach ( $defaults as $key => $default ) {
            if ( ! array_key_exists( $key, $settings ) ) {
                continue;
            }

            $hex = sanitize_hex_color( (string) $settings[ $key ] );

            if ( null === $hex ) {
                $settings[ $key ] = $default;
                $changed          = true;
            }
        }

        if ( $changed ) {
            update_option( $option_name, $settings );
        }
    }
}
