<?php
/**
 * Coupon Validator
 *
 * @package Eventin\Coupon
 */

namespace Eventin\Coupon;

defined( 'ABSPATH' ) || exit;

/**
 * The single validate engine, used by BOTH the /validate REST route and the
 * order-create path, so admin preview and real checkout can never diverge.
 *
 * Ports coupons-mock.js mockValidate verbatim (same check order, same error
 * codes). The canonical ticket identifier is the ticket SLUG (etn_ticket_slug):
 * the checkout cart sends `ticket_id = ticket_slug` and the coupon form stores
 * slugs in restricted_tickets.
 */
class CouponValidator {
    /**
     * Coupon repository.
     *
     * @var CouponModel
     */
    private $coupons;

    /**
     * Redemption repository.
     *
     * @var RedemptionModel
     */
    private $redemptions;

    /**
     * Constructor.
     *
     * @param CouponModel     $coupons     Coupon repository.
     * @param RedemptionModel $redemptions Redemption repository.
     */
    public function __construct( CouponModel $coupons, RedemptionModel $redemptions ) {
        $this->coupons     = $coupons;
        $this->redemptions = $redemptions;
    }

    /**
     * Validate a coupon against a checkout payload.
     *
     * @param array $p code, event_id, cart[{ticket_id,qty,price}], subtotal, buyer_email.
     * @return array Valid:   [ valid=>true, coupon=>[...], discount_amount, new_total ]
     *               Invalid: [ valid=>false, error_code, message ]
     */
    public function validate( $p ) {
        // Coupons are disabled entirely when WooCommerce is the selected ticket
        // payment method (the UI hides the field). The order route is guest-open,
        // so a code could still be POSTed — refuse it here rather than hand out an
        // impossible discount. Woo merely being installed/active is not enough.
        if ( CouponModel::woo_owns_checkout() ) {
            return $this->fail( 'invalid', __( 'Coupons are not available.', 'eventin' ) );
        }

        $code   = strtoupper( trim( (string) ( isset( $p['code'] ) ? $p['code'] : '' ) ) );
        $coupon = $this->coupons->find_by_code( $code );

        if ( ! $coupon ) {
            return $this->fail( 'invalid', __( 'This coupon code is invalid.', 'eventin' ) );
        }

        if ( 'expired' === $coupon['status'] ) {
            return $this->fail( 'expired', __( 'This coupon has expired.', 'eventin' ) );
        }

        if ( 'scheduled' === $coupon['status'] ) {
            return $this->fail( 'not_started', __( 'This coupon is not active yet.', 'eventin' ) );
        }

        if ( 'inactive' === $coupon['status'] ) {
            return $this->fail( 'invalid', __( 'This coupon is not available.', 'eventin' ) );
        }

        if ( null !== $coupon['usage_limit'] && $coupon['usage_count'] >= $coupon['usage_limit'] ) {
            return $this->fail( 'usage_exhausted', __( 'This coupon has reached its usage limit.', 'eventin' ) );
        }

        // per_user_limit — enforceable server-side (the mock could not).
        $buyer_email = ! empty( $p['buyer_email'] ) ? sanitize_email( $p['buyer_email'] ) : '';
        if ( null !== $coupon['per_user_limit'] && $buyer_email ) {
            $used = $this->redemptions->count_for_user( $coupon['id'], $buyer_email );
            if ( $used >= $coupon['per_user_limit'] ) {
                return $this->fail( 'per_user_exhausted', __( 'You have already used this coupon.', 'eventin' ) );
            }
        }

        $subtotal = isset( $p['subtotal'] ) ? floatval( $p['subtotal'] ) : 0;
        if ( null !== $coupon['min_purchase'] && $subtotal < $coupon['min_purchase'] ) {
            return $this->fail( 'min_purchase', __( 'Your order does not meet the minimum purchase for this coupon.', 'eventin' ) );
        }

        $cart = isset( $p['cart'] ) && is_array( $p['cart'] ) ? $p['cart'] : [];
        $qty  = 0;
        foreach ( $cart as $line ) {
            $qty += intval( isset( $line['qty'] ) ? $line['qty'] : 0 );
        }
        if ( null !== $coupon['min_qty'] && $qty < $coupon['min_qty'] ) {
            return $this->fail( 'min_qty', __( 'Your order does not meet the minimum ticket quantity for this coupon.', 'eventin' ) );
        }

        $event_id = intval( isset( $p['event_id'] ) ? $p['event_id'] : 0 );
        $excluded = array_map( 'intval', $coupon['excluded_events'] );
        if ( in_array( $event_id, $excluded, true ) ) {
            return $this->fail( 'event_excluded', __( 'This coupon cannot be used for this event.', 'eventin' ) );
        }

        $included = array_map( 'intval', $coupon['restricted_events'] );
        if ( $included && ! in_array( $event_id, $included, true ) ) {
            return $this->fail( 'event_not_eligible', __( 'This coupon does not apply to this event.', 'eventin' ) );
        }

        if ( $coupon['restricted_tickets'] ) {
            $ok = false;
            foreach ( $cart as $line ) {
                $ticket_id = isset( $line['ticket_id'] ) ? (string) $line['ticket_id'] : '';
                if ( in_array( $ticket_id, $coupon['restricted_tickets'], true ) ) {
                    $ok = true;
                    break;
                }
            }
            if ( ! $ok ) {
                return $this->fail( 'ticket_not_eligible', __( 'This coupon does not apply to the selected tickets.', 'eventin' ) );
            }
        }

        // Discount base — driven by discount_scope (the authoritative control).
        // restricted_tickets above is only an eligibility gate; here it just narrows
        // the ticket-scope set. Per-line prices are populated server-side by the
        // callers (validate route + order path); the client-sent price is never trusted.
        //  - discount_scope=total   -> the whole subtotal (tickets + add-ons), even
        //                              when the coupon is restricted to specific tickets.
        //  - discount_scope=tickets -> only ticket price: the eligible ticket lines
        //                              when restricted, otherwise all ticket lines.
        $scope = ( isset( $coupon['discount_scope'] ) && 'tickets' === $coupon['discount_scope'] ) ? 'tickets' : 'total';

        if ( 'tickets' === $scope ) {
            $discount_base = 0.0;
            foreach ( $cart as $line ) {
                $ticket_id = isset( $line['ticket_id'] ) ? (string) $line['ticket_id'] : '';
                // When restricted, only the eligible ticket lines count; otherwise all.
                if ( $coupon['restricted_tickets'] && ! in_array( $ticket_id, $coupon['restricted_tickets'], true ) ) {
                    continue;
                }
                $discount_base += floatval( isset( $line['price'] ) ? $line['price'] : 0 ) * intval( isset( $line['qty'] ) ? $line['qty'] : 0 );
            }
        } else {
            $discount_base = $subtotal;
        }

        $discount = ( 'percentage' === $coupon['discount_type'] )
            ? round( $discount_base * $coupon['discount_value'] / 100, 2 )
            : min( $coupon['discount_value'], $discount_base );

        // The scope and the eligible ticket set are returned alongside the amount so
        // the order can FREEZE them (OrderController::apply_coupon). Refunds need to
        // know how the discount was apportioned, and re-reading the coupon later is
        // not safe: update() overwrites the whole row and delete() leaves no
        // tombstone, so an edited or removed coupon would silently re-apportion the
        // refunds of an order that was paid for months earlier.
        return [
            'valid'           => true,
            'coupon'          => [
                'id'             => $coupon['id'],
                'code'           => $coupon['code'],
                'discount_type'  => $coupon['discount_type'],
                'discount_value' => $coupon['discount_value'],
                'discount_scope' => $scope,
            ],
            'discount_amount' => $discount,
            'new_total'       => max( 0, $subtotal - $discount ),
            // Ticket slugs the discount was computed against. Empty means "every
            // ticket line" — either the coupon was unrestricted, or the scope is
            // 'total' and the base was the whole subtotal.
            'discount_ticket_slugs' => ( 'tickets' === $scope && $coupon['restricted_tickets'] )
                ? array_values( array_map( 'strval', $coupon['restricted_tickets'] ) )
                : [],
        ];
    }

    /**
     * Build a failure result.
     *
     * @param string $error_code Error code (matches frontend coupon-errors.js).
     * @param string $message    Translated message.
     * @return array
     */
    private function fail( $error_code, $message ) {
        return [
            'valid'      => false,
            'error_code' => $error_code,
            'message'    => $message,
        ];
    }
}
