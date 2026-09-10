<?php
/**
 * Tour Controller
 *
 * Stores each admin's guided-tour progress against their own user meta so one
 * person finishing the tour never hides it from anybody else.
 *
 * @package Eventin\Tour
 */

namespace Eventin\Tour\Api;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Tour Controller Class
 */
class TourController extends WP_REST_Controller {
    /**
     * User meta key holding every tour's state for one user.
     *
     * @var string
     */
    const META_KEY = 'eventin_tour_state';

    /**
     * Allowed tour states.
     *
     * @var array
     */
    const STATUSES = [ 'in_progress', 'completed', 'skipped' ];

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() {
        $this->namespace = 'eventin/v2';
        $this->rest_base = 'tours';
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
                    'args'                => [],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            // Matches what sanitize_key() accepts, so an id registered through
            // the `eventin.tours` filter cannot 404 at routing while still
            // being a valid key in the callback.
            '/' . $this->rest_base . '/(?P<id>[a-zA-Z0-9_\-]+)/state',
            [
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => [ $this, 'update_item' ],
                    'permission_callback' => [ $this, 'update_item_permissions_check' ],
                    'args'                => $this->get_state_args(),
                ],
            ]
        );
    }

    /**
     * Anyone who can reach the Eventin dashboard may read their own tour state.
     *
     * Deliberately not gated behind manage_options: organizer and event-manager
     * roles use the same SPA and need the tour more than administrators do.
     *
     * @return bool
     */
    public function get_items_permissions_check( $request ) {
        return $this->can_use_tour();
    }

    /**
     * Writing progress only ever touches the current user's own meta, so the
     * same check applies. There is no user_id parameter by design.
     *
     * @param WP_REST_Request $request Request object.
     * @return bool
     */
    public function update_item_permissions_check( $request ) {
        return $this->can_use_tour();
    }

    /**
     * Whether the logged-in user has any Eventin admin capability.
     *
     * @return bool
     */
    protected function can_use_tour() {
        if ( ! is_user_logged_in() ) {
            return false;
        }

        $caps = [
            'etn_manage_dashboard',
            'etn_manage_event',
            'etn_manage_attendee',
            'etn_manage_order',
            'etn_manage_setting',
            'manage_options',
        ];

        foreach ( $caps as $cap ) {
            if ( current_user_can( $cap ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the current user's tour state plus the context that decides whether a
     * tour should auto-start.
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response
     */
    public function get_items( $request ) {
        return rest_ensure_response(
            [
                'tours'   => $this->get_state(),
                'context' => [
                    // Written once, by the upgrader, on a site with no Eventin
                    // history — a new install or one whose options a reset
                    // wiped. An upgraded site never has it, which is what keeps
                    // the walkthrough away from people already running events.
                    'fresh_install'    => 'yes' === get_option( 'etn_tour_eligible' ),
                    'wizard_completed' => 'active' === get_option( 'etn_wizard' ),
                    'event_count'      => (int) wp_count_posts( 'etn' )->publish,
                ],
            ]
        );
    }

    /**
     * Save progress for a single tour.
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response|WP_Error
     */
    public function update_item( $request ) {
        $tour_id = sanitize_key( $request->get_param( 'id' ) );

        if ( ! $tour_id ) {
            return new WP_Error(
                'eventin_tour_invalid_id',
                __( 'Invalid tour id.', 'eventin' ),
                [ 'status' => 400 ]
            );
        }

        $status = $request->get_param( 'status' );

        if ( ! in_array( $status, self::STATUSES, true ) ) {
            return new WP_Error(
                'eventin_tour_invalid_status',
                __( 'Invalid tour status.', 'eventin' ),
                [ 'status' => 400 ]
            );
        }

        $state             = $this->get_state();
        $state[ $tour_id ] = [
            'status'     => $status,
            'step'       => max( 0, (int) $request->get_param( 'step' ) ),
            'version'    => max( 1, (int) $request->get_param( 'version' ) ),
            'updated_at' => current_time( 'mysql' ),
        ];

        update_user_meta( get_current_user_id(), self::META_KEY, $state );

        return rest_ensure_response(
            [
                'tours' => $state,
            ]
        );
    }

    /**
     * Read the current user's stored state, normalised to an array.
     *
     * @return array
     */
    protected function get_state() {
        $state = get_user_meta( get_current_user_id(), self::META_KEY, true );

        return is_array( $state ) ? $state : [];
    }

    /**
     * Argument schema for the state endpoint.
     *
     * @return array
     */
    protected function get_state_args() {
        return [
            'status'  => [
                'description' => __( 'Tour status.', 'eventin' ),
                'type'        => 'string',
                'required'    => true,
                'enum'        => self::STATUSES,
            ],
            'step'    => [
                'description'       => __( 'Zero based index of the step the user reached.', 'eventin' ),
                'type'              => 'integer',
                'default'           => 0,
                'sanitize_callback' => 'absint',
            ],
            'version' => [
                'description'       => __( 'Version of the tour definition that was shown.', 'eventin' ),
                'type'              => 'integer',
                'default'           => 1,
                'sanitize_callback' => 'absint',
            ],
        ];
    }
}
