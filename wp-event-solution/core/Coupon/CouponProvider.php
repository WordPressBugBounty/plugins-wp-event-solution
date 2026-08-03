<?php
/**
 * Coupon Provider
 *
 * @package Eventin\Coupon
 */

namespace Eventin\Coupon;

defined( 'ABSPATH' ) || exit;

use Eventin\Abstracts\Provider;

/**
 * Coupon Provider Class
 */
class CouponProvider extends Provider {
    /**
     * Coupon services (registered + hooked).
     *
     * @var array
     */
    protected $services = [
        CouponRedemptionHandler::class,
    ];
}
