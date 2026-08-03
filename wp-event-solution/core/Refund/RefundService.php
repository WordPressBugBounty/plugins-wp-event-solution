<?php
namespace Eventin\Refund;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Attendee\Attendee_Model;
use Eventin\Coupon\CouponModel;
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

            // Sum the shares load_order_attendees() already allocated. Recomputing
            // the ratio here would be a second copy of the same rule, free to drift
            // from the preview the admin just approved in the refund modal.
            $amount = 0.0;
            foreach ( $attendee_ids as $aid ) {
                $amount += (float) $attendees[ $aid ]['net_price'];
            }
            $amount = round( $amount, 2 );

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
                'id'           => $aid,
                'status'       => $status !== '' ? $status : 'failed',
                'price'        => isset( $att['etn_ticket_price'] ) ? (float) $att['etn_ticket_price'] : 0.0,
                'addons_total' => $this->addons_total( isset( $att['etn_option_selections'] ) ? $att['etn_option_selections'] : [] ),
                'event_id'     => isset( $att['etn_event_id'] ) ? (int) $att['etn_event_id'] : 0,
                'ticket_slug'  => isset( $att['ticket_slug'] ) ? (string) $att['ticket_slug'] : '',
                'ticket_name'  => isset( $att['ticket_name'] ) ? (string) $att['ticket_name'] : '',
                'name'         => isset( $att['etn_name'] ) ? (string) $att['etn_name'] : '',
            ];
        }

        // Add per-attendee net_price: this attendee's share of the final paid
        // amount (post-discount, +tax if excl). The share is weighted by what the
        // attendee is actually worth — their ticket price PLUS the add-ons they
        // personally bought, LESS the part of the coupon their own spend earned.
        // Weighting on ticket price alone hands one buyer's add-on money to another,
        // and weighting on gross worth hands one buyer's discount to another; either
        // way refunding a single ticket returns the wrong figure (both only came out
        // right when every ticket was refunded at once).
        // Frontend uses this for the refund preview, and create() sums these same
        // values, so the preview and the recorded amount cannot disagree.
        $order        = new OrderModel( (int) $order_id );
        $final_amount = $this->final_amount_for_order( $order );

        foreach ( $this->allocate( $this->refund_weights( $order, $out ), $final_amount ) as $aid => $share ) {
            $out[ $aid ]['net_price'] = $share;
        }

        return $out;
    }

    /**
     * What each attendee is worth for the purpose of splitting the amount paid.
     *
     * Gross worth (ticket + their own add-ons) minus the slice of the coupon that
     * their own spend earned. Both terms matter, and for opposite reasons:
     *
     *  - drop the add-ons and one buyer's add-on money is refunded to another;
     *  - drop the discount and one buyer's discount is charged to another.
     *
     * Only the RATIO between weights is used ({@see allocate()} normalises against
     * the amount actually paid), which is why a 'total'-scope coupon comes out
     * identical with or without the discount term: it scales every weight by the
     * same factor. The ratio only moves when the discount was NOT proportional to
     * gross worth — i.e. a ticket-scope coupon, which skips add-ons, and a
     * ticket-restricted one, which skips ineligible tickets too.
     *
     * @param   OrderModel  $order      Order being refunded.
     * @param   array       $attendees  Attendee rows from load_order_attendees().
     *
     * @return  array<int,float>  Attendee id => weight.
     */
    protected function refund_weights( $order, array $attendees ) {
        $gross = [];

        foreach ( $attendees as $aid => $attendee ) {
            $gross[ $aid ] = (float) $attendee['price'] + (float) $attendee['addons_total'];
        }

        $discount = (float) $order->discount_total;

        if ( $discount <= 0 ) {
            return $gross;
        }

        $worth = $this->discountable_worth( $order, $attendees );
        $base  = array_sum( $worth );

        // A zero base means nothing on this order was discountable, which cannot be
        // squared with a non-zero discount — fall back to gross worth rather than
        // dividing by zero or inventing an apportionment.
        if ( $base <= 0 ) {
            return $gross;
        }

        $weights = [];

        foreach ( $gross as $aid => $value ) {
            $weights[ $aid ] = max( 0.0, $value - ( $worth[ $aid ] / $base ) * $discount );
        }

        return $weights;
    }

    /**
     * The part of each attendee's spend that the coupon was actually computed against.
     *
     * Mirrors the discount base in {@see \Eventin\Coupon\CouponValidator::validate()}:
     * 'total' scope discounts the whole subtotal, tickets and add-ons alike; 'tickets'
     * scope discounts ticket price only, and only for the eligible slugs when the
     * coupon is ticket-restricted.
     *
     * The terms are read from the ORDER, where apply_coupon() froze them, never from
     * the coupon row — that row is a whole-row overwrite away from changing and a
     * delete away from vanishing, and either would silently re-apportion the refunds
     * of an order paid for long ago.
     *
     * Two fallbacks, in order, for orders placed before those fields existed:
     * the coupon row itself (right whenever it has not been edited since), then
     * 'total' (which reproduces the historical behaviour exactly, so nothing that
     * works today starts moving). WooCommerce orders land on 'total' too — their
     * coupon never went through Eventin's engine, so there is no scope to honour.
     *
     * @param   OrderModel  $order      Order being refunded.
     * @param   array       $attendees  Attendee rows from load_order_attendees().
     *
     * @return  array<int,float>  Attendee id => discountable worth.
     */
    protected function discountable_worth( $order, array $attendees ) {
        $scope = (string) $order->discount_scope;
        $slugs = array_map( 'strval', (array) $order->discount_ticket_slugs );

        if ( '' === $scope ) {
            list( $scope, $slugs ) = $this->legacy_discount_terms( $order );
        }

        $worth = [];

        foreach ( $attendees as $aid => $attendee ) {
            if ( 'tickets' !== $scope ) {
                $worth[ $aid ] = (float) $attendee['price'] + (float) $attendee['addons_total'];
                continue;
            }

            // No slugs recorded means the coupon was unrestricted: every ticket line
            // counted towards the base.
            $eligible = ! $slugs || in_array( (string) $attendee['ticket_slug'], $slugs, true );

            $worth[ $aid ] = $eligible ? (float) $attendee['price'] : 0.0;
        }

        return $worth;
    }

    /**
     * Best-effort discount terms for an order placed before they were frozen onto it.
     *
     * Reads the coupon the order still names. Right whenever that coupon has not been
     * edited or deleted since the purchase; when it has, this degrades to 'total',
     * which is exactly what these orders already did. So it can improve a legacy
     * order's split and cannot make one worse.
     *
     * @param   OrderModel  $order  Order being refunded.
     *
     * @return  array{0:string,1:string[]}  [ scope, eligible ticket slugs ]
     */
    protected function legacy_discount_terms( $order ) {
        $coupon_id = (int) $order->coupon_id;

        if ( $coupon_id <= 0 ) {
            return [ 'total', [] ];
        }

        $coupon = ( new CouponModel() )->find( $coupon_id );

        if ( ! $coupon || 'tickets' !== ( isset( $coupon['discount_scope'] ) ? $coupon['discount_scope'] : 'total' ) ) {
            return [ 'total', [] ];
        }

        $restricted = isset( $coupon['restricted_tickets'] ) ? (array) $coupon['restricted_tickets'] : [];

        return [ 'tickets', array_values( array_map( 'strval', $restricted ) ) ];
    }

    /**
     * Sum the add-on lines a single attendee personally bought.
     *
     * Reads the attendee's own `etn_option_selections`, written per attendee at
     * order creation ({@see \Eventin\Order\Api\OrderController::prepare_attendee_data()}).
     * Absent on bookings made before add-ons existed, and on any booking without
     * them — both yield 0.0, which leaves the share weighted on ticket price alone.
     *
     * @param   mixed  $selections  Meta value: array of rows, or a JSON/serialized string.
     *
     * @return  float
     */
    protected function addons_total( $selections ) {
        if ( is_string( $selections ) ) {
            $decoded    = json_decode( $selections, true );
            $selections = is_array( $decoded ) ? $decoded : maybe_unserialize( $selections );
        }

        if ( ! is_array( $selections ) ) {
            return 0.0;
        }

        $total = 0.0;

        foreach ( $selections as $row ) {
            if ( is_array( $row ) && isset( $row['line_total'] ) ) {
                $total += (float) $row['line_total'];
            }
        }

        return round( $total, 2 );
    }

    /**
     * Split an amount across weighted parts so the parts sum to it exactly.
     *
     * Rounding each share independently loses or gains a cent: two $50 tickets on
     * a $100.01 order each come to 50.005 and both round up to 50.01, putting the
     * refundable total a cent above what was charged — which then rejects the last
     * ticket for exceeding the remaining balance. Largest-remainder allocation
     * hands the leftover cents out one at a time instead, so the parts always add
     * back up to $amount and refunding every ticket closes the order to the cent.
     *
     * Works in integer cents; ties break on the larger weight then the lower id, so
     * the split is stable across calls rather than depending on iteration order.
     *
     * @param   array<int,float>  $weights  Part id => relative weight.
     * @param   float             $amount   Total to split.
     *
     * @return  array<int,float>  Part id => allocated amount.
     */
    protected function allocate( array $weights, $amount ) {
        $out = [];

        foreach ( $weights as $id => $weight ) {
            $out[ $id ] = 0.0;
        }

        $total_weight = array_sum( $weights );
        $total_cents  = (int) round( (float) $amount * 100 );

        if ( ! $out || $total_weight <= 0 || $total_cents <= 0 ) {
            return $out;
        }

        $remainders = [];
        $assigned   = 0;

        foreach ( $weights as $id => $weight ) {
            $exact             = ( (float) $weight / $total_weight ) * $total_cents;
            $whole             = (int) floor( $exact );
            $out[ $id ]        = $whole;
            $remainders[ $id ] = $exact - $whole;
            $assigned         += $whole;
        }

        $ids = array_keys( $weights );

        usort( $ids, function ( $a, $b ) use ( $remainders, $weights ) {
            $cmp = $remainders[ $b ] <=> $remainders[ $a ];
            if ( 0 !== $cmp ) {
                return $cmp;
            }
            $cmp = $weights[ $b ] <=> $weights[ $a ];
            if ( 0 !== $cmp ) {
                return $cmp;
            }
            return $a <=> $b;
        } );

        $left = $total_cents - $assigned;

        foreach ( $ids as $id ) {
            if ( $left <= 0 ) {
                break;
            }
            $out[ $id ]++;
            $left--;
        }

        foreach ( $out as $id => $cents ) {
            $out[ $id ] = round( $cents / 100, 2 );
        }

        return $out;
    }

    /**
     * Customer-paid amount for the order (post-discount, plus tax if tax-exclusive).
     * Refunds are pro-rated and capped against this value, not raw total_price.
     *
     * `total_price` does not mean the same thing on every order, which is what
     * makes this more than a subtraction:
     *
     *  - Every gateway EXCEPT WooCommerce has already taken the coupon off it.
     *    apply_coupon() subtracts the discount at order creation and
     *    apply_native_tax() stores the discounted total at payment. Subtracting
     *    discount_total again there double-counts the coupon and under-refunds by
     *    its value (a $40 order with a $5 coupon, paid $35, would cap at $30).
     *  - WooCommerce is the sole exception. sync_wc_order_totals() deliberately
     *    keeps total_price as the gross ticket subtotal and copies the WooCommerce
     *    coupon alongside it in discount_total, so there the discount is still
     *    outstanding and MUST come off. Leaving it on let an admin refund a
     *    WooCommerce customer more than they ever paid — WooCommerce then clamped
     *    the excess and the two systems' books stopped matching.
     *
     * Those two are also the only writers of discount_total, which is what makes
     * the gateway check exhaustive rather than a guess. This is the PHP twin of
     * chargedTotal() in src/helpers/utils/order-totals.js — keep them in step.
     *
     * @param   OrderModel  $order  Order to value.
     *
     * @return  float  What the customer was actually charged.
     */
    public function final_amount_for_order( $order ) {
        $total    = (float) $order->total_price;
        $tax      = (float) $order->tax_total;
        $is_incl  = 'incl' === (string) $order->tax_display_mode;
        $discount = $this->discount_still_outstanding( $order ) ? (float) $order->discount_total : 0.0;

        return max( 0.0, $total + ( $is_incl ? 0.0 : $tax ) - $discount );
    }

    /**
     * Whether the order's coupon has NOT yet been taken off its total_price.
     *
     * True for WooCommerce only. Mirrors discountAlreadyInTotal() in
     * src/helpers/utils/order-totals.js (inverted).
     *
     * @param   OrderModel  $order  Order to inspect.
     *
     * @return  bool
     */
    protected function discount_still_outstanding( $order ) {
        return 'wc' === (string) $order->payment_method;
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
