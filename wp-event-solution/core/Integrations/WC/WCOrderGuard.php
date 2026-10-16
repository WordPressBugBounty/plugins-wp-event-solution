<?php

namespace Eventin\Integrations\WC;

defined( 'ABSPATH' ) || exit;

use Eventin\Order\OrderModel;

/**
 * Proof that a WooCommerce order really paid for an Eventin booking.
 *
 * The `eventin_order_id` meta on a WooCommerce order is only a pointer. Every place
 * that completes an Eventin booking because of a WooCommerce order asks this class
 * first, so a wrong pointer (a stale session, a $0 order for another product, a
 * ticket line of another booking) can never complete a paid booking
 * (Patchstack 36384).
 *
 * @package Eventin
 */
class WCOrderGuard {
    /**
     * Is a WooCommerce order line one of this booking's Eventin ticket lines?
     *
     * @param   mixed    $item              WooCommerce order item.
     * @param   integer  $event_id          Booking's event id.
     * @param   integer  $eventin_order_id  Booking id.
     *
     * @return  boolean
     */
    public static function line_is_event_ticket( $item, $event_id, $eventin_order_id = 0 ) {
        if ( ! is_object( $item ) || ! method_exists( $item, 'get_meta' ) ) {
            return false;
        }

        $is_ticket = ( 'yes' === $item->get_meta( '_etn' ) );

        if ( ! $is_ticket && method_exists( $item, 'get_product' ) ) {
            $product   = $item->get_product();
            $is_ticket = ( $product && method_exists( $product, 'get_type' ) && 'etn' === $product->get_type() );
        }

        if ( ! $is_ticket ) {
            return false;
        }

        // Strongest binding: the exact booking id stamped on the line at checkout.
        // When it is present it must match — the event id is not enough, or two
        // bookings for the same event could be confused.
        $line_order_id = absint( $item->get_meta( '_etn_order_id' ) );

        if ( $line_order_id ) {
            return $line_order_id === absint( $eventin_order_id );
        }

        // Older / in-flight carts without the stamp fall back to the event match.
        // Such a line can never pass can_complete() for free: its price must
        // still cover the booking.
        return absint( $item->get_meta( 'event_id' ) ) === absint( $event_id );
    }

    /**
     * May this WooCommerce order complete the Eventin booking it is linked to?
     *
     * The WooCommerce order must hold the booking's own ticket line, and the line's
     * price before coupons (+ its tax) must cover the booking's total_price. The
     * price is read before coupons on purpose: a 100% coupon is a legal way to
     * bring a real ticket order to $0, and it must still complete.
     *
     * @param   \WC_Order|mixed  $wc_order          WooCommerce order.
     * @param   integer          $eventin_order_id  Linked booking id.
     *
     * @return  boolean
     */
    public static function can_complete( $wc_order, $eventin_order_id ) {
        if ( ! $wc_order instanceof \WC_Order || ! $eventin_order_id ) {
            return false;
        }

        $event_order = new OrderModel( $eventin_order_id );
        $event_id    = absint( $event_order->event_id );
        $has_ticket  = false;
        $ticket_paid = 0.0;

        if ( $event_id ) {
            foreach ( $wc_order->get_items() as $item ) {
                if ( ! self::line_is_event_ticket( $item, $event_id, $eventin_order_id ) ) {
                    continue;
                }

                $has_ticket   = true;
                $ticket_paid += (float) $item->get_subtotal() + (float) $item->get_subtotal_tax();
            }
        }

        // One cent of room for rounding. Prices entered with tax included are
        // stored by WooCommerce as subtotal + subtotal tax, so both are added above.
        $can_complete = $has_ticket && ( $ticket_paid + 0.01 >= (float) $event_order->total_price );

        /**
         * Filters whether a WooCommerce order may complete its linked Eventin order.
         *
         * For sites that change the line price after checkout on purpose, for example
         * a multi-currency plugin that converts the order to another currency.
         *
         * @param boolean    $can_complete  Result of the ticket and price check.
         * @param \WC_Order  $wc_order      WooCommerce order.
         * @param OrderModel $event_order   Linked Eventin order.
         */
        $can_complete = (bool) apply_filters( 'eventin_wc_order_can_complete_eventin_order', $can_complete, $wc_order, $event_order );

        if ( ! $can_complete && method_exists( $wc_order, 'add_order_note' )
            && ! $wc_order->get_meta( '_etn_completion_blocked_note' ) ) {
            $wc_order->add_order_note( sprintf(
                /* translators: %d: Eventin booking id */
                __( 'Eventin booking #%d was not completed: this order does not hold its ticket at the full price.', 'eventin' ),
                $eventin_order_id
            ) );
            $wc_order->update_meta_data( '_etn_completion_blocked_note', 1 );
            $wc_order->save();
        }

        return $can_complete;
    }
}
