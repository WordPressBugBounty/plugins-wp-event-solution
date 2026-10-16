<?php

namespace Eventin\Integrations\WC;

defined( 'ABSPATH' ) || exit;

use WP_Error;

/**
 * Sends an Eventin refund to the linked WooCommerce order.
 *
 * Hooked on the eventin/refund/process_payment filter, which RefundService
 * runs BEFORE it records the refund. Only acts on orders whose Eventin
 * payment_method is 'wc'.
 *
 * - The order's gateway supports refunds (e.g. Stripe, PayPal): WooCommerce
 *   asks the gateway to send the money back. If the gateway refuses, the
 *   error goes back to the admin and Eventin records nothing.
 * - It does not (bank transfer, cash on delivery): WooCommerce only writes
 *   a refund record. The admin sends the money back by hand.
 *
 * @package Eventin
 */
class WCRefundSync {

    /**
     * True while Eventin itself is creating a WooCommerce refund.
     *
     * wc_create_refund() fires WooCommerce's refund and status hooks, which
     * would otherwise copy the same refund back into Eventin.
     *
     * @var bool
     */
    private static $running = false;

    /**
     * Whether Eventin is creating a WooCommerce refund right now
     *
     * @return bool
     */
    public static function is_running() {
        return self::$running;
    }

    /**
     * Whether a refund on this WooCommerce order will send the money back
     *
     * @param \WC_Order $wc_order WooCommerce order.
     *
     * @return bool
     */
    public static function sends_money_back( $wc_order ) {
        if ( ! $wc_order || ! function_exists( 'wc_get_payment_gateway_by_order' ) ) {
            return false;
        }

        $gateway = wc_get_payment_gateway_by_order( $wc_order );
        $result  = $gateway && $gateway->supports( 'refunds' );

        /**
         * Turn the automatic money refund through WooCommerce on or off.
         *
         * @param bool      $result   True when the order's gateway supports refunds.
         * @param \WC_Order $wc_order WooCommerce order.
         */
        return (bool) apply_filters( 'eventin_wc_refund_sends_money', $result, $wc_order );
    }

    /**
     * Find the WooCommerce order linked to an Eventin order
     *
     * @param int $order_id Eventin order id.
     *
     * @return \WC_Order|null
     */
    public static function get_wc_order( $order_id ) {
        if ( ! function_exists( 'wc_get_orders' ) ) {
            return null;
        }

        $wc_order_ids = wc_get_orders( [
            'limit'      => 1,
            'status'     => 'any',
            'return'     => 'ids',
            'meta_key'   => 'eventin_order_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
            'meta_value' => (int) $order_id,    // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
        ] );

        if ( empty( $wc_order_ids ) ) {
            return null;
        }

        $wc_order = wc_get_order( (int) $wc_order_ids[0] );

        return $wc_order ? $wc_order : null;
    }

    /**
     * Refund the linked WooCommerce order before Eventin records the refund
     *
     * @param null|string|WP_Error      $result Result from an earlier callback.
     * @param \Eventin\Order\OrderModel $order  Eventin order.
     * @param float                     $amount Amount to refund.
     * @param string|null               $reason Refund note.
     *
     * @return null|string|WP_Error WooCommerce refund id, null when there is nothing
     *                              to do, or an error that stops the Eventin refund.
     */
    public function process( $result, $order, $amount, $reason ) {
        if ( null !== $result || 'wc' !== (string) $order->payment_method ) {
            return $result;
        }

        if ( ! function_exists( 'wc_create_refund' ) ) {
            return null;
        }

        $wc_order = self::get_wc_order( $order->id );

        if ( ! $wc_order ) {
            error_log( sprintf( '[eventin] refund: no WooCommerce order linked to Eventin order %d', $order->id ) );
            return null;
        }

        $amount      = round( (float) $amount, 2 );
        $remaining   = round( (float) $wc_order->get_remaining_refund_amount(), 2 );
        $sends_money = self::sends_money_back( $wc_order );

        // Money refunds must match exactly. Clamping here would send the
        // customer less than Eventin records (e.g. the order was partly
        // refunded in WooCommerce before Eventin tracked Woo refunds).
        if ( $sends_money && $amount > $remaining + 0.001 ) {
            return new WP_Error(
                'eventin_wc_refund_exceeds_remaining',
                sprintf(
                    /* translators: 1: amount left to refund in WooCommerce, 2: WooCommerce order number */
                    __( 'WooCommerce can only refund %1$s more on order #%2$s. Part of it was already refunded in WooCommerce. Enter a smaller amount.', 'eventin' ),
                    number_format( $remaining, 2 ),
                    $wc_order->get_order_number()
                ),
                [ 'status' => 422 ]
            );
        }

        if ( $amount > $remaining ) {
            error_log( sprintf(
                '[eventin] refund: requested %01.2f exceeds WooCommerce remaining refundable %01.2f on order %d; clamping',
                $amount,
                $remaining,
                $wc_order->get_id()
            ) );
            $amount = $remaining;
        }

        if ( $amount <= 0 ) {
            return null;
        }

        self::$running = true;

        try {
            $refund = wc_create_refund( [
                'order_id'       => $wc_order->get_id(),
                'amount'         => $amount,
                'reason'         => (string) $reason,
                'refund_payment' => $sends_money,
                'restock_items'  => false,
            ] );
        } catch ( \Throwable $e ) {
            $refund = new WP_Error( 'eventin_wc_refund_failed', $e->getMessage() );
        } finally {
            self::$running = false;
        }

        if ( is_wp_error( $refund ) ) {
            error_log( sprintf(
                '[eventin] refund: wc_create_refund failed on order %d: %s',
                $wc_order->get_id(),
                $refund->get_error_message()
            ) );

            // The money could not be sent back. Stop, so Eventin does not
            // show a refund the customer never got.
            if ( $sends_money ) {
                return new WP_Error(
                    'eventin_wc_refund_failed',
                    sprintf(
                        /* translators: %s: error message from the payment gateway */
                        __( 'The payment gateway did not refund the money: %s', 'eventin' ),
                        $refund->get_error_message()
                    ),
                    [ 'status' => 422 ]
                );
            }

            // Record-only refund: keep the old behaviour and record it in Eventin anyway.
            return null;
        }

        return (string) $refund->get_id();
    }
}
