<?php

namespace Eventin\Order;

defined( 'ABSPATH' ) || exit;

use Eventin\Coupon\CouponModel;

/**
 * A loyalty plugin's member discount for a native-checkout order.
 *
 * One place for the filter and its clamping, so the checkout preview and the
 * order that is actually charged can never disagree.
 *
 * @package Eventin
 */
class MemberDiscount {

    const FILTER = 'eventin_order_member_discount';

    /**
     * The discount on the order's total after the coupon.
     *
     * @param array $order_data Order data; total_price is after the coupon.
     * @param array $input_data Raw request body.
     * @return array{amount: float, label: string}
     */
    public static function resolve( array $order_data, array $input_data = [] ) {
        $none = [ 'amount' => 0.0, 'label' => '' ];

        // WooCommerce checkout applies the member discount in its own cart.
        if ( CouponModel::woo_owns_checkout() ) {
            return $none;
        }

        $subtotal = max( 0, floatval( isset( $order_data['total_price'] ) ? $order_data['total_price'] : 0 ) );

        /**
         * Filters the member discount for a native-checkout order.
         *
         * @param array $discount   { amount: float, label: string }.
         * @param array $order_data Order data; total_price is after the coupon.
         * @param array $input_data Raw request body.
         */
        $discount = apply_filters( self::FILTER, [ 'amount' => 0, 'label' => '' ], $order_data, $input_data );

        if ( ! is_array( $discount ) || ! isset( $discount['amount'] ) || ! is_numeric( $discount['amount'] ) ) {
            return $none;
        }

        $amount = min( round( (float) $discount['amount'], 2 ), $subtotal );

        if ( $amount <= 0 ) {
            return $none;
        }

        return [
            'amount' => $amount,
            'label'  => isset( $discount['label'] ) && is_scalar( $discount['label'] ) ? sanitize_text_field( (string) $discount['label'] ) : '',
        ];
    }
}
