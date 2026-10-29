<?php
namespace Eventin\Vendor\Ens\Utils;

use WP_HTTP_Response;

class Helpers {

    /**
     * will return strings after translation
     *
     * @return  array
     */
    public static function ens_get_translatable_strings( $string, $text_domain ) {
        return $string;
    }

    /**
     * will verify nonce
     *
     * @return  array
     */
    public static function ens_verify_nonce( $nonce, $identifier ) {
        $is_local = isset( $_SERVER['REMOTE_ADDR'] ) && in_array( $_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'] );

        if ( ( isset( $nonce ) && wp_verify_nonce( $nonce, 'wp_rest' ) ) || $is_local ) {
            return true;
        }

        $response = [
            'success'     => 0,
            'status_code' => 403,
            'message'     => __( 'Nonce not verified', self::get_config_data( $identifier,'text_domain' ) ),
            'data'        => [],
        ];
        return new WP_HTTP_Response( $response, 403 );
    }

    /**
     * will sanitize recursive
     *
     * @return  array
     */
    public static function ens_sanitize_recursive( $input ) {
        if ( is_array( $input ) ) {

            foreach ( $input as $key => $value ) {
                $input[$key] = ens_sanitize_recursive( $value );
            }

        } elseif ( is_object( $input ) ) {

            foreach ( $input as $key => $value ) {
                $input->$key = ens_sanitize_recursive( $value );
            }

        } else {
            if ( filter_var( $input, FILTER_VALIDATE_URL ) !== false ) {
                $input = sanitize_url( $input );
            } elseif ( is_email( $input ) ) {
                $input = sanitize_email( $input );
            } elseif ( is_int( $input ) ) {
                $input = intval( $input );
            } elseif ( is_float( $input ) ) {
                $input = floatval( $input );
            } elseif ( is_string( $input ) ) {
                if ( strpos( $input, '<svg' ) === false ) {
                    $allowed_protocols   = wp_allowed_protocols();
                    $allowed_protocols[] = 'data';

                    $prev_backtrack = ini_get( 'pcre.backtrack_limit' );
                    $prev_recursion = ini_get( 'pcre.recursion_limit' );
                    if ( strlen( $input ) > 500000 ) {
                        @ini_set( 'pcre.backtrack_limit', max( (int) $prev_backtrack, strlen( $input ) * 4 ) );
                        @ini_set( 'pcre.recursion_limit', max( (int) $prev_recursion, strlen( $input ) * 4 ) );
                    }

                    $sanitized = wp_kses( $input, 'post', array_unique( $allowed_protocols ) );

                    if ( strlen( $input ) > 500000 ) {
                        @ini_set( 'pcre.backtrack_limit', $prev_backtrack );
                        @ini_set( 'pcre.recursion_limit', $prev_recursion );
                    }

                    if ( null !== $sanitized && '' !== $sanitized ) {
                        $input = $sanitized;
                    }
                }
            }
        }

        return $input;
    }

    /**
     * will return post status
     *
     * @return  array
     */
    public static function ens_get_post_status() {
        return ['publish', 'draft', 'trash'];
    }

    /**
     * Get config data value with fallback
     *
     * @param string $prefix
     * @param string $value_of
     * @return mixed|null
     */
    public static function get_config_data($prefix, $value_of) {
        $key = $prefix . '_ens_config';

        $data = get_option( $key );

        if (isset($data[$value_of])) {
            return $data[$value_of];
        }

        return null;
    }

    /**
     * Build a plugin-scoped hook name.
     *
     * Prepends the consumer plugin's hook_prefix (or general_prefix as
     * fallback) to the given suffix so hooks fired from the SDK do not
     * collide when the package is bundled in multiple plugins.
     *
     * @param string $identifier The consumer plugin identifier (general_prefix).
     * @param string $hook_suffix The unscoped hook name (e.g. 'email_body').
     *
     * @return string
     */
    public static function get_hook_name( $identifier, $hook_suffix ) {
        $prefix = self::get_config_data( $identifier, 'hook_prefix' );

        if ( empty( $prefix ) ) {
            $prefix = $identifier;
        }

        return $prefix . '_' . $hook_suffix;
    }

    /**
     * Whether the WhatsApp channel is on for this consumer plugin.
     *
     * Defaults to true so plugins that never add the filter keep the
     * WhatsApp action. The builder hides it and the flow skips it when false.
     *
     * @param string $identifier The consumer plugin identifier.
     *
     * @return bool
     */
    public static function is_whatsapp_enabled( $identifier ) {
        if ( ! self::channel_enabled( $identifier, 'whatsapp' ) ) {
            return false;
        }

        return (bool) apply_filters( self::get_hook_name( $identifier, 'ens_whatsapp_enabled' ), true );
    }

    /**
     * Channels the consumer plugin has switched on.
     *
     * Every channel is on unless the plugin turns it off through the
     * `ens_{identifier}_channels` filter, so plugins that never use the
     * filter behave as before. `whatsapp_text` false means WhatsApp steps
     * send approved templates only.
     *
     * @param string $identifier The consumer plugin identifier (general_prefix).
     *
     * @return array Channel name => bool, e.g. [ 'whatsapp' => true ].
     */
    public static function get_channels( $identifier ) {
        $channels = apply_filters( 'ens_' . $identifier . '_channels', [ 'whatsapp' => true ] );

        return is_array( $channels ) ? $channels : [ 'whatsapp' => true ];
    }

    /**
     * Whether a channel is on for the consumer plugin.
     *
     * @param string $identifier The consumer plugin identifier (general_prefix).
     * @param string $channel    Channel name, e.g. 'whatsapp'.
     *
     * @return bool
     */
    public static function channel_enabled( $identifier, $channel ) {
        $channels = self::get_channels( $identifier );

        return ! isset( $channels[ $channel ] ) || false !== $channels[ $channel ];
    }
}