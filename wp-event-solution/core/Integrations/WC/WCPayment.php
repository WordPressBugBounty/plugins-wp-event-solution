<?php

namespace Eventin\Integrations\WC;

defined( 'ABSPATH' ) || exit;

use Eventin\Order\OrderModel;
use Eventin\Order\PaymentInterface;

/**
 * Pay using Woocommerce payment
 * 
 * @package Eventin
 */
class WCPayment implements PaymentInterface {
    /**
     * Create payment for woocommerce payment methods
     *
     * @return  void
     */
    public function create_payment( $order ) {
        WC()->cart->empty_cart();

        if ( WC()->session->get( 'event_order_id' ) ) {
            WC()->session->__unset( 'event_order_id' );
        }

        WC()->session->set( 'event_order_id', $order->id );

        $this->prefill_customer_from_order( $order );

        $cart_id  = WC()->cart->add_to_cart( $order->event_id );

        return [ 'id' => $cart_id ];
    }

    /**
     * Prefill the WC customer billing fields from the Eventin order so both
     * classic and block-based checkout render the data the user already
     * submitted on the Eventin order-create form.
     *
     * @param   OrderModel  $order
     *
     * @return  void
     */
    protected function prefill_customer_from_order( $order ) {
        if ( ! WC()->customer ) {
            return;
        }

        $first_name = (string) $order->customer_fname;
        $last_name  = (string) $order->customer_lname;
        $email      = (string) $order->customer_email;
        $phone      = (string) $order->customer_phone;

        if ( $first_name ) {
            WC()->customer->set_billing_first_name( $first_name );
            WC()->customer->set_shipping_first_name( $first_name );
        }

        if ( $last_name ) {
            WC()->customer->set_billing_last_name( $last_name );
            WC()->customer->set_shipping_last_name( $last_name );
        }

        if ( $email ) {
            WC()->customer->set_billing_email( $email );
        }

        if ( $phone ) {
            WC()->customer->set_billing_phone( $phone );
        }

        WC()->customer->save();
    }

    /**
     * Create refund for woocommere order
     *
     * @param   OrderModel  $order
     *
     * @return
     */
    public function refund( OrderModel $order ) {
        $post_type = etn_is_enable_wc_synchronize_order() ? 'shop_order' : 'shop_order_placehold'; 
        $args = [
            'post_type'   => $post_type,
            'post_status' => 'any',
            'posts_per_page' => -1,
            'fields'          => 'ids',        
            'meta_query'    => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                [
                    'key'   => 'eventin_order_id',
                    'value' => $order->id,
                    'compare' => '='
                ]
            ]
        ];


        $orders_ids = get_posts( $args );

        if ( ! $orders_ids ) {
            return false;
        }

        $order = wc_get_order( $orders_ids[0] );

        if ( $order ) {
            $order->update_status( 'refunded' );

            return true;
        }

        return false;
    }
}
