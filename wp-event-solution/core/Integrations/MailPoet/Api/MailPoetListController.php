<?php
/**
 * MailPoet list REST controller
 *
 * @package Eventin
 */
namespace Eventin\Integrations\MailPoet\Api;

defined( 'ABSPATH' ) || exit;

use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Response;

/**
 * Exposes MailPoet lists for the per-event integration form.
 */
class MailPoetListController extends WP_REST_Controller {
    /**
     * Constructor
     */
    public function __construct() {
        $this->namespace = 'eventin/v2';
        $this->rest_base = 'mailpoet/lists';
    }

    /**
     * Register routes
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items' ],
                    'permission_callback' => [ $this, 'get_items_permissions_check' ],
                ],
            ]
        );
    }

    /**
     * Only event managers may read the lists.
     *
     * @param mixed $request
     * @return bool
     */
    public function get_items_permissions_check( $request ) {
        return current_user_can( 'etn_manage_event' );
    }

    /**
     * Return MailPoet lists as [ { id, name } ].
     * Empty array when MailPoet is not active — doubles as the "active" signal for the UI.
     *
     * @param mixed $request
     * @return WP_REST_Response
     */
    public function get_items( $request ) {
        if ( ! class_exists( \MailPoet\API\API::class ) ) {
            return new WP_REST_Response( [], 200 );
        }

        try {
            $lists = \MailPoet\API\API::MP( 'v1' )->getLists();
        } catch ( \Exception $e ) {
            return new WP_REST_Response( [], 200 );
        }

        $data = array_map(
            function ( $list ) {
                return [
                    'id'   => isset( $list['id'] ) ? (string) $list['id'] : '',
                    'name' => isset( $list['name'] ) ? $list['name'] : '',
                ];
            },
            is_array( $lists ) ? $lists : []
        );

        return new WP_REST_Response( $data, 200 );
    }
}
