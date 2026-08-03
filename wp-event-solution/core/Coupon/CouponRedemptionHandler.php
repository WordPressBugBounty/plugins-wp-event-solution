<?php
/**
 * Coupon Redemption Handler
 *
 * @package Eventin\Coupon
 */

namespace Eventin\Coupon;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;

/**
 * Records a coupon redemption when an order is completed.
 *
 * Records the redemption when an order reaches "completed" — NOT at creation, so
 * abandoned/unpaid checkouts never inflate usage_count. Two completion paths exist
 * and both must be covered:
 *  - `eventin_order_completed`        — gateway completion via PaymentController
 *                                       (Stripe, PayPal, free, SureCart, FluentCart).
 *  - `eventin_order_status_completed` — admin/manual status change to completed
 *                                       (OrderController), which is how local_payment
 *                                       orders finalize (the payment endpoint leaves
 *                                       them "pending"). Without this hook, local-pay
 *                                       and admin-completed coupon orders never record.
 * Idempotent: an existing-row check makes double firing (both hooks, retried webhooks,
 * refund→re-complete) a no-op.
 */
class CouponRedemptionHandler implements HookableInterface {
    /**
     * Register hooks.
     *
     * @return void
     */
    public function register_hooks(): void {
        add_action( 'eventin_order_completed', [ $this, 'record_redemption' ], 10, 1 );
        add_action( 'eventin_order_status_completed', [ $this, 'record_redemption' ], 10, 1 );
    }

    /**
     * Record the redemption + increment usage for a completed coupon order.
     *
     * @param mixed $order OrderModel.
     * @return void
     */
    public function record_redemption( $order ) {
        if ( empty( $order ) || empty( $order->id ) ) {
            return;
        }

        $coupon_id = intval( $order->coupon_id );
        if ( ! $coupon_id ) {
            return;
        }

        $redemptions = new RedemptionModel();

        // Idempotency: webhooks retry and payment_complete can fire more than once.
        if ( $redemptions->exists_for_order( $order->id ) ) {
            return;
        }

        $redemptions->create(
            [
                'coupon_id'       => $coupon_id,
                'coupon_code'     => $order->coupon_code,
                'order_id'        => $order->id,
                'buyer_email'     => $order->customer_email,
                'discount_amount' => floatval( $order->discount_total ),
            ]
        );

        ( new CouponModel() )->increment_usage( $coupon_id );
    }
}
