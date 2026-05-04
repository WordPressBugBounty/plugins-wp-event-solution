<?php
namespace Eventin;

/**
 * Settings class
 */
class Settings {
    /**
     * Store option name
     *
     * @var string
     */
    protected static $option_name = 'etn_event_options';

    /**
     * Get settings
     *
     * @param   string  $key
     *
     * @return  mixed
     */
    public static function get( $key = '' ) {
        $settings = get_option( self::$option_name, [] );

        if ( ! $key ) {
            return $settings;
        }

        $value = '';

        if ( ! empty( $settings[$key] ) ) {
            $value = $settings[$key];
        }

        return $value;
    }

    /**
     * Update settings
     *
     * @param   array  $options
     *
     * @return  void
     */
    public static function update( $options = [] ) {
        $settings = self::get();

        $options = self::sanitize_settings( $options );

        foreach ( $options as $name => $value ) {
            $settings[$name] = $value;
        }

        return update_option( self::$option_name, $settings );
    }

    /**
     * Recursively sanitize settings, preserving safe HTML for email body fields.
     *
     * Email body fields (keyed `body`) are edited via a rich-text editor and
     * must retain formatting tags. All other values are hardened with
     * sanitize_text_field to strip unsafe markup.
     */
    protected static function sanitize_settings( $data ) {
        if ( ! is_array( $data ) ) {
            return sanitize_text_field( $data );
        }

        $sanitized = [];

        foreach ( $data as $key => $value ) {
            if ( is_array( $value ) ) {
                $sanitized[$key] = self::sanitize_settings( $value );
            } elseif ( 'body' === $key ) {
                $sanitized[$key] = wp_kses_post( $value );
            } else {
                $sanitized[$key] = sanitize_text_field( $value );
            }
        }

        return $sanitized;
    }
}
