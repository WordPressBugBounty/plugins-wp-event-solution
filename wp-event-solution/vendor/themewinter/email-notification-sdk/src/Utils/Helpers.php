<?php
namespace Ens\Utils;

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
}