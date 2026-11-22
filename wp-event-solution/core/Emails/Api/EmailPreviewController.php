<?php
/**
 * Automation email preview REST controller
 *
 * @package Eventin
 */

namespace Eventin\Emails\Api;

use Eventin\Emails\EmailDesign;
use Eventin\Emails\EmailPreviewSample;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

/**
 * POST eventin/v2/automation/email-preview
 *
 * Returns the email an Automation step would send, built from sample data.
 */
class EmailPreviewController extends WP_REST_Controller {

    /**
     * Constructor
     */
    public function __construct() {
        $this->namespace = 'eventin/v2';
        $this->rest_base = 'automation/email-preview';
    }

    /**
     * Register routes
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route( $this->namespace, $this->rest_base, [
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [ $this, 'preview' ],
                'permission_callback' => [ $this, 'preview_permissions_check' ],
            ],
        ] );
    }

    /**
     * Same capability as the Automation menu.
     *
     * @return bool|WP_Error
     */
    public function preview_permissions_check() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return new WP_Error( 'eventin_rest_forbidden', __( 'Sorry, you are not allowed to do that.', 'wp-event-solution' ), [ 'status' => rest_authorization_required_code() ] );
        }

        return true;
    }

    /**
     * Render the preview.
     *
     * @param \WP_REST_Request $request Request.
     *
     * @return \WP_REST_Response|WP_Error
     */
    public function preview( $request ) {
        $data    = $request->get_json_params();
        $data    = is_array( $data ) ? $data : [];
        $trigger = sanitize_key( $data['trigger'] ?? '' );

        if ( ! EmailDesign::is_known( $trigger ) ) {
            return new WP_Error( 'eventin_preview_trigger', __( 'This email has no preview.', 'wp-event-solution' ), [ 'status' => 422 ] );
        }

        $mode = EmailDesign::mode( [ EmailDesign::MODE_KEY => $data['mode'] ?? '' ] );

        $result = ( new EmailPreviewSample() )->render(
            $trigger,
            sanitize_key( $data['receiver'] ?? '' ),
            (string) ( $data['subject'] ?? '' ),
            wp_kses_post( (string) ( $data['body'] ?? '' ) ),
            $mode
        );

        return is_wp_error( $result ) ? $result : rest_ensure_response( $result );
    }
}
