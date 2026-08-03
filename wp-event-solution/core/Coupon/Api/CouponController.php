<?php
/**
 * Coupon Api Class
 *
 * @package Eventin\Coupon
 */

namespace Eventin\Coupon\Api;

defined( 'ABSPATH' ) || exit;

use Eventin\Coupon\CouponModel;
use Eventin\Coupon\RedemptionModel;
use Eventin\Coupon\CouponValidator;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Server;

/**
 * Coupon Controller Class
 *
 * eventin/v2/coupons — admin CRUD + a guest-open /validate route for checkout.
 */
class CouponController extends WP_REST_Controller {
    /**
     * Coupon repository.
     *
     * @var CouponModel
     */
    private $model;

    /**
     * Redemption repository.
     *
     * @var RedemptionModel
     */
    private $redemptions;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->namespace   = 'eventin/v2';
        $this->rest_base   = 'coupons';
        $this->model       = new CouponModel();
        $this->redemptions = new RedemptionModel();
    }

    /**
     * Register routes.
     *
     * @return void
     */
    public function register_routes() {
        // Collection: GET list, POST create, DELETE bulk.
        register_rest_route( $this->namespace, '/' . $this->rest_base, [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_items' ],
                'permission_callback' => [ $this, 'get_items_permissions_check' ],
            ],
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [ $this, 'create_item' ],
                'permission_callback' => [ $this, 'create_item_permissions_check' ],
            ],
            [
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => [ $this, 'delete_items' ],
                'permission_callback' => [ $this, 'delete_item_permissions_check' ],
            ],
        ] );

        // Guest-open checkout validation. Registered before the /(?P<id>) route.
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/validate', [
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [ $this, 'validate_item' ],
                'permission_callback' => [ $this, 'validate_permissions_check' ],
            ],
        ] );

        // Guest-open: does any coupon apply to this event? Gates the checkout field.
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/available', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'available' ],
                'permission_callback' => [ $this, 'validate_permissions_check' ],
            ],
        ] );

        // Single: GET, PUT, DELETE.
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', [
            'args' => [
                'id' => [
                    'description' => __( 'Unique identifier for the coupon.', 'eventin' ),
                    'type'        => 'integer',
                ],
            ],
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_item' ],
                'permission_callback' => [ $this, 'get_item_permissions_check' ],
            ],
            [
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => [ $this, 'update_item' ],
                'permission_callback' => [ $this, 'update_item_permissions_check' ],
            ],
            [
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => [ $this, 'delete_item' ],
                'permission_callback' => [ $this, 'delete_item_permissions_check' ],
            ],
        ] );

        // Redemptions drill-in.
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/redemptions', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_redemptions' ],
                'permission_callback' => [ $this, 'get_item_permissions_check' ],
            ],
        ] );
    }

    /**
     * Get a collection of coupons.
     *
     * @param \WP_REST_Request $request Request.
     * @return \WP_REST_Response
     */
    public function get_items( $request ) {
        $args = [
            'search'    => ! empty( $request['search'] ) ? sanitize_text_field( $request['search'] ) : '',
            'status'    => ! empty( $request['status'] ) ? sanitize_text_field( $request['status'] ) : '',
            'date_from' => ! empty( $request['date_from'] ) ? sanitize_text_field( $request['date_from'] ) : '',
            'date_to'   => ! empty( $request['date_to'] ) ? sanitize_text_field( $request['date_to'] ) : '',
            'paged'     => ! empty( $request['paged'] ) ? intval( $request['paged'] ) : 1,
            'per_page'  => ! empty( $request['per_page'] ) ? intval( $request['per_page'] ) : 10,
        ];

        $result   = $this->model->query( $args );
        $response = rest_ensure_response( $result );
        $response->header( 'X-WP-Total', $result['total_items'] );

        return $response;
    }

    /**
     * Get a single coupon.
     *
     * @param \WP_REST_Request $request Request.
     * @return \WP_REST_Response|WP_Error
     */
    public function get_item( $request ) {
        $coupon = $this->model->find( intval( $request['id'] ) );

        if ( ! $coupon ) {
            return new WP_Error( 'not_found', __( 'Coupon not found.', 'eventin' ), [ 'status' => 404 ] );
        }

        return rest_ensure_response( $coupon );
    }

    /**
     * Create a coupon.
     *
     * @param \WP_REST_Request $request Request.
     * @return \WP_REST_Response|WP_Error
     */
    public function create_item( $request ) {
        $data   = $this->prepare_item_for_database( $request );
        $coupon = $this->model->create( $data );

        if ( is_wp_error( $coupon ) ) {
            return $coupon;
        }

        $response = rest_ensure_response( $coupon );
        $response->set_status( 201 );

        return $response;
    }

    /**
     * Update a coupon.
     *
     * @param \WP_REST_Request $request Request.
     * @return \WP_REST_Response|WP_Error
     */
    public function update_item( $request ) {
        $data   = $this->prepare_item_for_database( $request );
        $coupon = $this->model->update( intval( $request['id'] ), $data );

        if ( is_wp_error( $coupon ) ) {
            return $coupon;
        }

        return rest_ensure_response( $coupon );
    }

    /**
     * Delete a coupon.
     *
     * @param \WP_REST_Request $request Request.
     * @return \WP_REST_Response|WP_Error
     */
    public function delete_item( $request ) {
        $deleted = $this->model->delete( intval( $request['id'] ) );

        if ( ! $deleted ) {
            return new WP_Error( 'rest_cannot_delete', __( 'The coupon cannot be deleted.', 'eventin' ), [ 'status' => 500 ] );
        }

        return rest_ensure_response( [ 'deleted' => true ] );
    }

    /**
     * Bulk delete coupons (?ids=1,2,3).
     *
     * @param \WP_REST_Request $request Request.
     * @return \WP_REST_Response|WP_Error
     */
    public function delete_items( $request ) {
        $ids = ! empty( $request['ids'] ) ? $request['ids'] : '';

        if ( is_string( $ids ) ) {
            $ids = array_filter( array_map( 'trim', explode( ',', $ids ) ) );
        }

        $ids = array_map( 'intval', (array) $ids );

        if ( ! $ids ) {
            return new WP_Error( 'rest_cannot_delete', __( 'Coupon ids cannot be empty.', 'eventin' ), [ 'status' => 400 ] );
        }

        $this->model->delete_many( $ids );

        return rest_ensure_response( [ 'deleted' => true ] );
    }

    /**
     * Get paginated redemptions for a coupon.
     *
     * @param \WP_REST_Request $request Request.
     * @return \WP_REST_Response
     */
    public function get_redemptions( $request ) {
        $paged    = ! empty( $request['paged'] ) ? intval( $request['paged'] ) : 1;
        $per_page = ! empty( $request['per_page'] ) ? intval( $request['per_page'] ) : 10;

        $result   = $this->redemptions->paginate( intval( $request['id'] ), $paged, $per_page );
        $response = rest_ensure_response( $result );
        $response->header( 'X-WP-Total', $result['total_items'] );

        return $response;
    }

    /**
     * Validate a coupon for checkout (guest-open).
     *
     * @param \WP_REST_Request $request Request.
     * @return \WP_REST_Response
     */
    public function validate_item( $request ) {
        $input = json_decode( $request->get_body(), true );
        $input = is_array( $input ) ? $input : [];

        $payload = [
            'code'        => isset( $input['code'] ) ? sanitize_text_field( $input['code'] ) : '',
            'event_id'    => isset( $input['event_id'] ) ? intval( $input['event_id'] ) : 0,
            'subtotal'    => isset( $input['subtotal'] ) ? floatval( $input['subtotal'] ) : 0,
            'buyer_email' => isset( $input['buyer_email'] ) ? sanitize_email( $input['buyer_email'] ) : '',
            'cart'        => [],
        ];

        // Authoritative per-ticket prices from the event (never trust client prices) —
        // needed so a ticket-restricted coupon discounts only the eligible lines.
        $event = $payload['event_id'] ? new \Etn\Core\Event\Event_Model( $payload['event_id'] ) : null;

        if ( isset( $input['cart'] ) && is_array( $input['cart'] ) ) {
            foreach ( $input['cart'] as $line ) {
                $ticket_id = isset( $line['ticket_id'] ) ? sanitize_text_field( $line['ticket_id'] ) : '';
                $ticket    = $event && $ticket_id ? $event->get_ticket( $ticket_id ) : null;

                $payload['cart'][] = [
                    'ticket_id' => $ticket_id,
                    'qty'       => isset( $line['qty'] ) ? intval( $line['qty'] ) : 0,
                    'price'     => $ticket && isset( $ticket['etn_ticket_price'] ) ? floatval( $ticket['etn_ticket_price'] ) : 0,
                ];
            }
        }

        $validator = new CouponValidator( $this->model, $this->redemptions );
        $result    = $validator->validate( $payload );

        // Don't expose the internal coupon id, or the terms the discount was computed
        // under, over the guest-open endpoint. Those exist so the ORDER can freeze
        // them for refund apportionment (OrderController::apply_coupon), and the
        // server-side order path calls the validator directly and keeps them. A buyer
        // probing codes from checkout has no business enumerating a coupon's
        // configuration. The buyer only needs to know it applied and by how much.
        unset( $result['coupon']['id'], $result['coupon']['discount_scope'], $result['discount_ticket_slugs'] );

        return rest_ensure_response( $result );
    }

    /**
     * Whether any coupon applies to the given event (checkout field gate).
     * Guest-open. Returns false when WooCommerce owns coupons.
     *
     * @param \WP_REST_Request $request Request.
     * @return \WP_REST_Response
     */
    public function available( $request ) {
        if ( CouponModel::woo_owns_checkout() ) {
            return rest_ensure_response( [ 'available' => false ] );
        }

        $event_id = ! empty( $request['event_id'] ) ? intval( $request['event_id'] ) : 0;

        // Optional cart tickets (comma-separated slugs) → ticket-aware gate: a
        // coupon restricted to tickets not in the cart won't show the field.
        $ticket_slugs = null;
        if ( isset( $request['tickets'] ) && '' !== $request['tickets'] ) {
            $ticket_slugs = array_values( array_filter( array_map(
                'sanitize_text_field',
                explode( ',', (string) $request['tickets'] )
            ) ) );
        }

        return rest_ensure_response( [
            'available' => $event_id ? $this->model->has_applicable_for_event( $event_id, $ticket_slugs ) : false,
        ] );
    }

    /**
     * Sanitize + whitelist coupon fields from the request body.
     *
     * @param \WP_REST_Request $request Request.
     * @return array
     */
    protected function prepare_item_for_database( $request ) {
        $input = json_decode( $request->get_body(), true );
        $input = is_array( $input ) ? $input : [];

        return [
            'code'               => isset( $input['code'] ) ? sanitize_text_field( $input['code'] ) : '',
            'discount_type'      => isset( $input['discount_type'] ) ? sanitize_text_field( $input['discount_type'] ) : 'percentage',
            'discount_value'     => isset( $input['discount_value'] ) ? floatval( $input['discount_value'] ) : 0,
            'start_date'         => isset( $input['start_date'] ) ? sanitize_text_field( $input['start_date'] ) : null,
            'start_time'         => isset( $input['start_time'] ) ? sanitize_text_field( $input['start_time'] ) : null,
            'end_date'           => isset( $input['end_date'] ) ? sanitize_text_field( $input['end_date'] ) : null,
            'end_time'           => isset( $input['end_time'] ) ? sanitize_text_field( $input['end_time'] ) : null,
            'usage_limit'        => isset( $input['usage_limit'] ) ? $input['usage_limit'] : null,
            'per_user_limit'     => isset( $input['per_user_limit'] ) ? $input['per_user_limit'] : null,
            'min_purchase'       => isset( $input['min_purchase'] ) ? $input['min_purchase'] : null,
            'min_qty'            => isset( $input['min_qty'] ) ? $input['min_qty'] : null,
            'restricted_events'  => isset( $input['restricted_events'] ) ? (array) $input['restricted_events'] : [],
            'excluded_events'    => isset( $input['excluded_events'] ) ? (array) $input['excluded_events'] : [],
            'restricted_tickets' => isset( $input['restricted_tickets'] ) ? (array) $input['restricted_tickets'] : [],
            'discount_scope'     => isset( $input['discount_scope'] ) ? sanitize_text_field( $input['discount_scope'] ) : 'total',
            'active'             => isset( $input['active'] ) ? (bool) $input['active'] : true,
        ];
    }

    /**
     * List permission check.
     *
     * @param \WP_REST_Request $request Request.
     * @return bool
     */
    public function get_items_permissions_check( $request ) {
        return current_user_can( 'etn_manage_coupon' );
    }

    /**
     * Single permission check.
     *
     * @param \WP_REST_Request $request Request.
     * @return bool
     */
    public function get_item_permissions_check( $request ) {
        return current_user_can( 'etn_manage_coupon' );
    }

    /**
     * Create permission check.
     *
     * @param \WP_REST_Request $request Request.
     * @return bool
     */
    public function create_item_permissions_check( $request ) {
        return current_user_can( 'etn_manage_coupon' );
    }

    /**
     * Update permission check.
     *
     * @param \WP_REST_Request $request Request.
     * @return bool
     */
    public function update_item_permissions_check( $request ) {
        return current_user_can( 'etn_manage_coupon' );
    }

    /**
     * Delete permission check.
     *
     * @param \WP_REST_Request $request Request.
     * @return bool
     */
    public function delete_item_permissions_check( $request ) {
        return current_user_can( 'etn_manage_coupon' );
    }

    /**
     * Validate permission check — guest-open checkout (nonce only), mirroring
     * OrderController::create_item_permissions_check.
     *
     * @param \WP_REST_Request $request Request.
     * @return bool
     */
    public function validate_permissions_check( $request ) {
        return (bool) wp_verify_nonce( $request->get_header( 'X-Wp-Nonce' ), 'wp_rest' );
    }
}
