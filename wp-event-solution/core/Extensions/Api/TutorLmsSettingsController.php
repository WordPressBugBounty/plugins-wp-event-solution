<?php
/**
 * Tutor LMS Addon Settings Controller
 *
 * @package Eventin
 */

namespace Eventin\Extensions\Api;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Server;

/**
 * Reads / writes the option blob owned by the eventin-addon-for-tutor-lms
 * plugin so admins can configure the addon from the Eventin Extensions page
 * without leaving the SPA.
 */
class TutorLmsSettingsController extends WP_REST_Controller {

    const OPTION_KEY = 'eventin_tutor_lms_options';

    protected $namespace = 'eventin/v2';

    protected $rest_base = 'tutor-lms-settings';

    public function register_routes() {
        register_rest_route( $this->namespace, $this->rest_base, [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_items' ],
                'permission_callback' => [ $this, 'permissions_check' ],
            ],
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [ $this, 'update_items' ],
                'permission_callback' => [ $this, 'permissions_check' ],
            ],
        ] );
    }

    public function permissions_check() {
        return current_user_can( 'etn_manage_addons' );
    }

    public function get_items( $request ) {
        return rest_ensure_response( $this->get_settings() );
    }

    public function update_items( $request ) {
        $input = json_decode( $request->get_body(), true );

        if ( ! is_array( $input ) ) {
            return new WP_Error(
                'invalid_payload',
                __( 'Invalid payload.', 'eventin' ),
                [ 'status' => 422 ]
            );
        }

        $clean = $this->sanitize( $input );

        update_option( self::OPTION_KEY, $clean );

        return rest_ensure_response( [
            'message' => __( 'Settings saved.', 'eventin' ),
            'data'    => $clean,
        ] );
    }

    protected function get_settings() {
        $stored = get_option( self::OPTION_KEY, [] );
        return wp_parse_args( is_array( $stored ) ? $stored : [], $this->defaults() );
    }

    protected function defaults() {
        return [
            'enroll_mode'        => 'all_attendees',
            'auto_create_users'  => 'on',
            'send_credentials'   => 'on',
            'unenroll_on_refund' => 'on',
        ];
    }

    protected function sanitize( $input ) {
        $defaults = $this->defaults();

        $mode = isset( $input['enroll_mode'] ) ? sanitize_key( $input['enroll_mode'] ) : $defaults['enroll_mode'];
        if ( ! in_array( $mode, [ 'purchaser', 'all_attendees' ], true ) ) {
            $mode = $defaults['enroll_mode'];
        }

        return [
            'enroll_mode'        => $mode,
            'auto_create_users'  => $this->bool_flag( $input, 'auto_create_users' ),
            'send_credentials'   => $this->bool_flag( $input, 'send_credentials' ),
            'unenroll_on_refund' => $this->bool_flag( $input, 'unenroll_on_refund' ),
        ];
    }

    protected function bool_flag( $input, $key ) {
        if ( ! array_key_exists( $key, $input ) ) {
            return 'off';
        }

        $value = $input[ $key ];

        if ( is_bool( $value ) ) {
            return $value ? 'on' : 'off';
        }

        return ! empty( $value ) && $value !== 'off' ? 'on' : 'off';
    }
}
