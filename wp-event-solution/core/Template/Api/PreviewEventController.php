<?php

namespace Eventin\Template\Api;

defined( 'ABSPATH' ) || exit;

use WP_Error;
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

        // `etn_manage_template` says "may edit templates", not "may read any post
        // on the site". Without these checks an explicit event_id turned this
        // preview into a reader for any post id — drafts, private posts and
        // other post types included.
        if ( $event_id ) {
            $validation = $this->validate_post( $event_id, 'etn' );

            if ( is_wp_error( $validation ) ) {
                return $validation;
            }
        }

        if ( $post_id ) {
            $validation = $this->validate_post( $post_id, 'etn-template' );

            if ( is_wp_error( $validation ) ) {
                return $validation;
            }
        }

        $data = PreviewEventFormatter::format( $event_id, $post_id );

        return rest_ensure_response( $data );
    }

    /**
     * Confirm an id names an existing post of the expected type that this user may read.
     *
     * Unpublished posts fall back to `read_post`, the meta capability, which is the
     * only form that resolves per-post. The primitive `read` ignores the id it is
     * handed and passes for every logged-in user.
     *
     * @param int    $id        Post id.
     * @param string $post_type Expected post type.
     *
     * @return true|WP_Error
     */
    private function validate_post( $id, $post_type ) {
        $post = get_post( $id );

        if ( ! $post || $post_type !== $post->post_type ) {
            return new WP_Error(
                'eventin_preview_not_found',
                __( 'The requested preview content does not exist.', 'eventin' ),
                [ 'status' => 404 ]
            );
        }

        if ( 'publish' !== $post->post_status && ! current_user_can( 'read_post', $post->ID ) ) {
            return new WP_Error(
                'eventin_preview_forbidden',
                __( 'You are not allowed to preview this content.', 'eventin' ),
                [ 'status' => 403 ]
            );
        }

        return true;
    }
}
