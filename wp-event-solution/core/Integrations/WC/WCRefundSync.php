<?php

namespace Eventin\Integrations\WC;

defined( 'ABSPATH' ) || exit;

use Eventin\Refund\RefundModel;

/**
 * Propagates an Eventin refund to the linked WooCommerce order as a
 * record-only refund (no gateway charge-back).
 *
 * Hooked on eventin/refund/created. Only acts on orders whose Eventin
 * payment_method is 'wc'; every other gateway is ignored here.
 *
 * @package Eventin
 */
class WCRefundSync {

    /**
     * Create a record-only WooCommerce refund matching an Eventin refund row.
     *
     * On any failure (WC inactive, order not linked, wc_create_refund error)
     * the Eventin refund is left intact and the problem is logged; Eventin
     * remains the source of truth.
     *
     * @param   array  $refund_row  The refund record from RefundModel::create().
     * @param   int    $order_id    Eventin order id.
     *
     * @return  void
     */
    public function handle( $refund_row, $order_id ) {
        if ( ! is_array( $refund_row ) || 'wc' !== ( isset( $refund_row['gateway'] ) ? $refund_row['gateway'] : '' ) ) {
            return;
        }

        if ( ! function_exists( 'wc_create_refund' ) || ! function_exists( 'wc_get_orders' ) ) {
            return;
        }

        $order_id = (int) $order_id;

        try {
            $wc_order_ids = wc_get_orders( [
                'limit'      => 1,
                'status'     => 'any',
                'return'     => 'ids',
                'meta_key'   => 'eventin_order_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                'meta_value' => $order_id,          // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
            ] );

            if ( empty( $wc_order_ids ) ) {
                error_log( sprintf( '[eventin] refund: no WooCommerce order linked to Eventin order %d', $order_id ) );
                return;
            }

            $wc_order_id = (int) $wc_order_ids[0];
            $wc_order    = wc_get_order( $wc_order_id );

            if ( ! $wc_order ) {
                error_log( sprintf( '[eventin] refund: WooCommerce order %d could not be loaded', $wc_order_id ) );
                return;
            }

            $amount    = round( (float) ( isset( $refund_row['amount'] ) ? $refund_row['amount'] : 0 ), 2 );
            $remaining = round( (float) $wc_order->get_remaining_refund_amount(), 2 );

            if ( $amount > $remaining ) {
                error_log( sprintf(
                    '[eventin] refund: requested %01.2f exceeds WooCommerce remaining refundable %01.2f on order %d; clamping',
                    $amount,
                    $remaining,
                    $wc_order_id
                ) );
                $amount = $remaining;
            }

            if ( $amount <= 0 ) {
                return;
            }

            $refund = wc_create_refund( [
                'order_id'       => $wc_order_id,
                'amount'         => $amount,
                'reason'         => (string) ( isset( $refund_row['reason'] ) ? $refund_row['reason'] : '' ),
                'refund_payment' => false,
                'restock_items'  => false,
            ] );

            if ( is_wp_error( $refund ) ) {
                error_log( sprintf(
                    '[eventin] refund: wc_create_refund failed on order %d: %s',
                    $wc_order_id,
                    $refund->get_error_message()
                ) );
                return;
            }

            $linked = ( new RefundModel() )->set_gateway_refund_id(
                $order_id,
                (int) ( isset( $refund_row['id'] ) ? $refund_row['id'] : 0 ),
                (string) $refund->get_id()
            );

            if ( false === $linked ) {
                error_log( sprintf(
                    '[eventin] refund: WooCommerce refund %s created but could not be linked (gateway_refund_id) to Eventin order %d',
                    $refund->get_id(),
                    $order_id
                ) );
            }
        } catch ( \Throwable $e ) {
            error_log( '[eventin] refund: WooCommerce sync threw on Eventin order ' . $order_id . ': ' . $e->getMessage() );
        }
    }
}
