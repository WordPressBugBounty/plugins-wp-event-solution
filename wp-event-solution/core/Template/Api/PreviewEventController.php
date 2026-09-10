<?php

namespace Eventin\Template\Api;

defined( 'ABSPATH' ) || exit;

use WP_REST_Controller;
use WP_REST_Server;
use Eventin\Template\PreviewEventFormatter;

/**
 * REST endpoint that returns normalized event data for the React
 * template-editor block previews. Read-only; editor-only use.
 */
class PreviewEventController extends WP_REST_Controller {

    protected $namespace = 'eventin/v2';
    protected $rest_base = 'templates/preview-event';

    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_item' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => [
                        'event_id' => [
                            'type'              => 'integer',
                            'required'          => false,
                            'default'           => 0,
                            'sanitize_callback' => 'absint',
                        ],
                        'post_id'  => [
                            'type'              => 'integer',
                            'required'          => false,
                            'default'           => 0,
                            'sanitize_callback' => 'absint',
                        ],
                    ],
                ],
            ]
        );
    }

    public function get_item_permissions_check( $request ) {
        return current_user_can( 'etn_manage_template' );
    }

    public function get_item( $request ) {
        $event_id = absint( $request->get_param( 'event_id' ) );
        $post_id  = absint( $request->get_param( 'post_id' ) );
        $data     = PreviewEventFormatter::format( $event_id, $post_id );

        return rest_ensure_response( $data );
    }
}
