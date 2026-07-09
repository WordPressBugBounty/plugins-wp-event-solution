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

        // Harden against a corrupted option value. If the row exists but holds a
        // non-array (e.g. an empty string written by an external process), the
        // default [] above is ignored by get_option(), and the offset assignment
        // in update() would fatal with "Cannot access offset of type string on
        // string", white-screening the entire site on every boot.
        if ( ! is_array( $settings ) ) {
            $settings = [];
        }

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
     * Color keys that must validate as a CSS hex color on write.
     *
     * Why: sanitize_text_field strips tags but accepts arbitrary content,
     * which allowed pre-4.1.x installs to store payloads like
     * `</style><script src=…>` in color fields. Whitelisting these keys to
     * sanitize_hex_color blocks the save-side vector reported externally.
     */
    protected static $hex_color_keys = [
        'etn_primary_color',
        'etn_secondary_color',
    ];

    /**
     * Recursively sanitize settings, preserving safe HTML for email body fields.
     *
     * Email body fields (keyed `body`) are edited via a rich-text editor and
     * must retain formatting tags. Color fields listed in $hex_color_keys are
     * validated against sanitize_hex_color and reset to empty on failure.
     * All other values are hardened with sanitize_text_field.
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
            } elseif ( in_array( $key, self::$hex_color_keys, true ) ) {
                $hex             = sanitize_hex_color( (string) $value );
                $sanitized[$key] = null === $hex ? '' : $hex;
            } else {
                $sanitized[$key] = sanitize_text_field( $value );
            }
        }

        return $sanitized;
    }
}
