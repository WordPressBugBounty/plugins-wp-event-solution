<?php
namespace Eventin\Refund;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Attendee\Attendee_Model;
use Eventin\Order\OrderModel;
use WP_Error;

class RefundService {

    /** @var RefundModel */
    protected $model;

    public function __construct( $model = null ) {
        $this->model = $model instanceof RefundModel ? $model : new RefundModel();
    }

    /**
     * Create a refund record + side effects.
     *
     * @param int     $order_id
     * @param string  $type     'ticket' or 'amount'
     * @param array   $payload  ticket: ['attendee_ids' => int[]]; amount: ['amount' => float]
     * @param string|null $reason
     * @return array|WP_Error
     */
    public function create( $order_id, $type, $payload, $reason = null ) {
        $order_id = (int) $order_id;
        $order    = new OrderModel( $order_id );

        if ( ! $order->id || 'etn-order' !== get_post_type( $order_id ) ) {
            return new WP_Error( 'eventin_refund_order_not_found', __( 'Order not found.', 'eventin' ), [ 'status' => 404 ] );
        }

        $current_status = $order->status;
        if ( in_array( $current_status, [ 'failed', 'pending', 'waiting', 'refunded' ], true ) ) {
            return new WP_Error( 'eventin_refund_invalid_state', __( 'This order cannot be refunded.', 'eventin' ), [ 'status' => 422 ] );
        }

        if ( ! in_array( $type, [ 'ticket', 'amount' ], true ) ) {
            return new WP_Error( 'eventin_refund_invalid_type', __( 'Invalid refund type.', 'eventin' ), [ 'status' => 400 ] );
        }

        $attendees            = $this->load_order_attendees( $order_id );
        $already_refunded_ids = $this->model->refunded_attendee_ids( $order_id );
        $attendee_ids_for_row = [];
        $amount               = 0.0;

        // Refund cap basis: customer's actual paid amount (post-discount, +tax if excl).
        // Ticket-type refunds are pro-rated against this so the math closes:
        // refunding all tickets returns exactly final_amount; refunding one returns
        // its proportional share including its slice of the discount and tax.
        $final_amount  = $this->final_amount_for_order( $order );
        $already_total = $this->model->refunded_total( $order_id );
        $remaining_cap = round( $final_amount - $already_total, 2 );

        if ( 'ticket' === $type ) {
            $attendee_ids = array_values( array_unique( array_map( 'intval', isset( $payload['attendee_ids'] ) ? (array) $payload['attendee_ids'] : [] ) ) );

            if ( empty( $attendee_ids ) ) {
                return new WP_Error( 'eventin_refund_no_attendees', __( 'Select at least one attendee.', 'eventin' ), [ 'status' => 400 ] );
            }
            $unknown = array_diff( $attendee_ids, array_keys( $attendees ) );
            if ( $unknown ) {
                return new WP_Error( 'eventin_refund_invalid_attendee', __( 'One or more attendees do not belong to this order.', 'eventin' ), [ 'status' => 400, 'invalid_ids' => array_values( $unknown ) ] );
            }
            $already = array_intersect( $attendee_ids, $already_refunded_ids );
            if ( $already ) {
                return new WP_Error( 'eventin_refund_already_refunded', __( 'One or more attendees are already refunded.', 'eventin' ), [ 'status' => 409, 'already_refunded' => array_values( $already ) ] );
            }

            $gross_all = 0.0;
            foreach ( $attendees as $a ) {
                $gross_all += (float) $a['price'];
            }
            $gross_selected = 0.0;
            foreach ( $attendee_ids as $aid ) {
                $gross_selected += (float) $attendees[ $aid ]['price'];
            }
            // Pro-rate: selected gross share × final paid amount.
            $amount = $gross_all > 0
                ? round( ( $gross_selected / $gross_all ) * $final_amount, 2 )
                : 0.0;

            if ( $amount > $remaining_cap + 0.001 ) {
                return new WP_Error(
                    'eventin_refund_exceeds_cap',
                    sprintf(
                        /* translators: %s is the remaining refundable amount formatted to 2 decimals */
                        __( 'Refund exceeds remaining refundable balance (%s). Deselect some tickets or process a smaller refund.', 'eventin' ),
                        number_format( $remaining_cap, 2 )
                    ),
                    [ 'status' => 422, 'remaining_cap' => $remaining_cap ]
                );
            }

            $attendee_ids_for_row = $attendee_ids;
        } else {
            if ( ! empty( $payload['attendee_ids'] ) ) {
                return new WP_Error( 'eventin_refund_amount_with_attendees', __( 'Amount-type refunds must not include attendee IDs.', 'eventin' ), [ 'status' => 400 ] );
            }
            $amount = isset( $payload['amount'] ) ? round( (float) $payload['amount'], 2 ) : 0.0;
            if ( $amount <= 0 ) {
                return new WP_Error( 'eventin_refund_invalid_amount', __( 'Amount must be greater than zero.', 'eventin' ), [ 'status' => 400 ] );
            }
            if ( $amount > $remaining_cap + 0.001 ) {
                return new WP_Error(
                    'eventin_refund_exceeds_cap',
                    sprintf(
                        /* translators: %s is the remaining refundable amount formatted to 2 decimals */
                        __( 'Amount exceeds remaining refundable balance (%s).', 'eventin' ),
                        number_format( $remaining_cap, 2 )
                    ),
                    [ 'status' => 422, 'remaining_cap' => $remaining_cap ]
                );
            }
        }

        // Compute per-slug counts for ticket-type refunds so the sold-count
        // aggregator can subtract them without us mutating the order's `tickets` meta
        // (which is the immutable original-purchase record).
        $slug_counts = [];
        if ( 'ticket' === $type ) {
            foreach ( $attendee_ids_for_row as $aid ) {
                $slug = isset( $attendees[ $aid ]['ticket_slug'] ) ? (string) $attendees[ $aid ]['ticket_slug'] : '';
                if ( $slug !== '' ) {
                    $slug_counts[ $slug ] = ( isset( $slug_counts[ $slug ] ) ? $slug_counts[ $slug ] : 0 ) + 1;
                }
            }
        }

        $refund_row = $this->model->create( $order_id, [
            'type'         => $type,
            'attendee_ids' => $attendee_ids_for_row,
            'slug_counts'  => $slug_counts,
            'amount'       => $amount,
            'currency'     => $order->currency ?: 'USD',
            'reason'       => $reason,
            'gateway'      => (string) $order->payment_method,
        ] );

        if ( null === $refund_row ) {
            return new WP_Error( 'eventin_refund_locked', __( 'Another refund is being processed on this order. Try again in a few seconds.', 'eventin' ), [ 'status' => 409 ] );
        }

        if ( 'ticket' === $type ) {
            foreach ( $attendee_ids_for_row as $aid ) {
                update_post_meta( $aid, 'etn_status', 'failed' );
            }
            // Note: we do NOT mutate the order's `tickets` post-meta. The sold-count
            // aggregator subtracts slug_counts from etn_refunds instead.
        }

        $new_status = $this->recompute_order_status( $order );

        try {
            \Eventin\Mails\Mail::to( (string) $order->customer_email )
                ->send( new \Eventin\Emails\RefundConfirmationMail( $order, $refund_row ) );
        } catch ( \Throwable $e ) {
            error_log( '[eventin] refund email failed: ' . $e->getMessage() );
        }

        do_action( 'eventin/refund/created', $refund_row, $order_id );

        return [
            'refund'                         => $refund_row,
            'order_status_after'             => $new_status,
            'remaining_refundable_attendees' => max( 0, count( $attendees ) - count( $this->model->refunded_attendee_ids( $order_id ) ) ),
        ];
    }

    /**
     * @param int $order_id
     * @return array<int,array{id:int,status:string,price:float,event_id:int,ticket_slug:string,ticket_name:string,name:string}>
     */
    public function load_order_attendees( $order_id ) {
        $attendee_model = new Attendee_Model();
        // Attendee_Model::get_attendees_by() returns assoc arrays (from get_data()),
        // NOT Attendee_Model instances — use array access, not ->property.
        $attendees = $attendee_model->get_attendees_by( 'eventin_order_id', (int) $order_id );
        $out       = [];

        foreach ( (array) $attendees as $att ) {
            $aid = (int) ( isset( $att['id'] ) ? $att['id'] : 0 );
            if ( ! $aid ) {
                continue;
            }
            $status = isset( $att['etn_status'] ) ? (string) $att['etn_status'] : '';
            $out[ $aid ] = [
                'id'          => $aid,
                'status'      => $status !== '' ? $status : 'failed',
                'price'       => isset( $att['etn_ticket_price'] ) ? (float) $att['etn_ticket_price'] : 0.0,
                'event_id'    => isset( $att['etn_event_id'] ) ? (int) $att['etn_event_id'] : 0,
                'ticket_slug' => isset( $att['ticket_slug'] ) ? (string) $att['ticket_slug'] : '',
                'ticket_name' => isset( $att['ticket_name'] ) ? (string) $att['ticket_name'] : '',
                'name'        => isset( $att['etn_name'] ) ? (string) $att['etn_name'] : '',
            ];
        }

        // Add per-attendee net_price: ticket_price's pro-rated share of the final
        // paid amount (post-discount, +tax if excl). Frontend uses this for the
        // refund preview so the live total matches what the backend will record.
        $order        = new OrderModel( (int) $order_id );
        $final_amount = $this->final_amount_for_order( $order );
        $gross_all    = 0.0;
        foreach ( $out as $a ) {
            $gross_all += (float) $a['price'];
        }
        foreach ( $out as $aid => $a ) {
            $out[ $aid ]['net_price'] = ( $gross_all > 0 )
                ? round( ( (float) $a['price'] / $gross_all ) * $final_amount, 2 )
                : 0.0;
        }

        return $out;
    }

    /**
     * Customer-paid amount for the order (post-discount, plus tax if tax-exclusive).
     * Refunds are pro-rated and capped against this value, not raw total_price.
     *
     * @param OrderModel $order
     * @return float
     */
    public function final_amount_for_order( $order ) {
        $total    = (float) $order->total_price;
        $discount = (float) $order->discount_total;
        $tax      = (float) $order->tax_total;
        $is_incl  = 'incl' === (string) $order->tax_display_mode;
        return max( 0.0, $total - $discount + ( $is_incl ? 0.0 : $tax ) );
    }

    /**
     * @param OrderModel $order
     * @return string
     */
    protected function recompute_order_status( $order ) {
        $refunded_total = $this->model->refunded_total( $order->id );
        // Compare against the customer's actual paid amount (post-discount, +tax if
        // tax-exclusive) — the same basis the refund cap uses in create(). Using raw
        // total_price here marks the order "refunded" while tax remained unrefunded,
        // e.g. paid 92 (80 + 12 tax), refunded 90, 2 left, yet flagged fully refunded.
        $final_amount   = $this->final_amount_for_order( $order );
        $new_status     = ( $refunded_total >= ( $final_amount - 0.01 ) ) ? 'refunded' : 'partially_refunded';
        $order->update( [ 'status' => $new_status ] );

        // Amount-type refunds never touch attendee status; once cumulative refunds
        // fully cover the order, mark every attendee failed so the order and its
        // tickets stay in sync.
        if ( 'refunded' === $new_status ) {
            foreach ( (array) $order->get_attendees() as $attendee_data ) {
                if ( empty( $attendee_data['id'] ) ) {
                    continue;
                }
                ( new Attendee_Model( (int) $attendee_data['id'] ) )->update( [ 'etn_status' => 'failed' ] );
            }
        }

        return $new_status;
    }

    /**
     * Reverse all recorded refunds on an order by wiping etn_refunds.
     * Because we don't mutate the order's `tickets` post-meta during refunds
     * (slug_counts in etn_refunds drive sold-count subtraction in the aggregator),
     * undoing just means deleting the history — quantities are already intact.
     *
     * The caller (OrderController status-update flow) flips attendee etn_status
     * back to 'success' separately.
     *
     * @param int $order_id
     * @return bool true if any refunds were undone, false if nothing to do.
     */
    public function undo_refunds_for_order( $order_id ) {
        $order_id = (int) $order_id;
        $refunds  = $this->model->find_by_order( $order_id );
        if ( empty( $refunds ) ) {
            return false;
        }
        delete_post_meta( $order_id, RefundModel::META_KEY );
        do_action( 'eventin/refund/undone', $order_id, $refunds );
        return true;
    }
}
