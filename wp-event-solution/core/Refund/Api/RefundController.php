<?php
namespace Eventin\Refund\Api;

defined( 'ABSPATH' ) || exit;

use Eventin\Order\OrderModel;
use Eventin\Refund\RefundModel;
use Eventin\Refund\RefundService;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class RefundController extends WP_REST_Controller {

    protected $namespace = 'eventin/v2';
    protected $rest_base = 'orders';

    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/refunds', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_items' ],
                'permission_callback' => [ $this, 'get_items_permissions_check' ],
            ],
            [
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => [ $this, 'create_item' ],
                'permission_callback' => [ $this, 'create_item_permissions_check' ],
                'args'                => [
                    'type'         => [ 'required' => true,  'type' => 'string', 'enum' => [ 'ticket', 'amount' ] ],
                    'attendee_ids' => [ 'required' => false, 'type' => 'array', 'items' => [ 'type' => 'integer' ] ],
                    'amount'       => [ 'required' => false, 'type' => 'number' ],
                    'reason'       => [ 'required' => false, 'type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field' ],
                ],
            ],
        ] );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/refundable-attendees', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [ $this, 'get_refundable_attendees' ],
            'permission_callback' => [ $this, 'get_items_permissions_check' ],
        ] );
    }

    public function get_items_permissions_check( $request ) {
        if ( ! current_user_can( 'etn_manage_order' ) ) {
            return new WP_Error( 'rest_forbidden', __( 'You do not have permission to manage orders.', 'eventin' ), [ 'status' => 403 ] );
        }
        return true;
    }

    public function create_item_permissions_check( $request ) {
        return $this->get_items_permissions_check( $request );
    }

    public function get_items( $request ) {
        $order_id     = (int) $request['id'];
        $model        = new RefundModel();
        $order        = new OrderModel( $order_id );
        $svc          = new RefundService();
        $rows         = $model->find_by_order( $order_id );
        $total        = $model->refunded_total( $order_id );
        $final_amount = $svc->final_amount_for_order( $order );

        return new WP_REST_Response( [
            'refunds'          => $rows,
            'refunded_total'   => $total,
            'refundable_total' => max( 0, round( $final_amount - $total, 2 ) ),
            'final_amount'     => $final_amount,
            'order_status'     => $order->status,
            // Currency captured on the order at purchase time (reflects the
            // gateway/currency actually used), falling back to the global setting.
            'currency'         => $order->currency ? $order->currency : etn_currency(),
            'currency_symbol'  => $order->currency_symbol
                ? html_entity_decode( $order->currency_symbol, ENT_QUOTES, 'UTF-8' )
                : html_entity_decode( etn_currency_symbol(), ENT_QUOTES, 'UTF-8' ),
        ], 200 );
    }

    public function create_item( $request ) {
        $svc     = new RefundService();
        $type    = (string) $request['type'];
        $payload = [
            'attendee_ids' => (array) ( isset( $request['attendee_ids'] ) ? $request['attendee_ids'] : [] ),
            'amount'       => isset( $request['amount'] ) ? (float) $request['amount'] : null,
        ];
        $result = $svc->create( (int) $request['id'], $type, $payload, isset( $request['reason'] ) ? $request['reason'] : null );
        if ( is_wp_error( $result ) ) {
            return $result;
        }
        return new WP_REST_Response( $result, 201 );
    }

    public function get_refundable_attendees( $request ) {
        $order_id = (int) $request['id'];
        $svc      = new RefundService();
        $model    = new RefundModel();
        $refunded = array_flip( $model->refunded_attendee_ids( $order_id ) );

        $out = [];
        foreach ( $svc->load_order_attendees( $order_id ) as $a ) {
            if ( isset( $refunded[ $a['id'] ] ) ) {
                continue;
            }
            $out[] = [
                'id'          => $a['id'],
                'name'        => $a['name'],
                'ticket_name' => $a['ticket_name'],
                'ticket_slug' => $a['ticket_slug'],
                'price'       => $a['price'],     // gross sticker price (per-ticket)
                'net_price'   => $a['net_price'], // post-discount, +tax if excl — what gets refunded
                'event_id'    => $a['event_id'],
            ];
        }
        return new WP_REST_Response( $out, 200 );
    }
}
