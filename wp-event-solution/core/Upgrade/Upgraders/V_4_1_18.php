<?php
/**
 * Updater for version 4.1.18
 *
 * @package Eventin\Upgrade
 */

namespace Eventin\Upgrade\Upgraders;

use Eventin\Coupon\CouponModel;
use Eventin\Coupon\RedemptionModel;

/**
 * Backfill coupon redemptions for orders that completed before the redemption
 * hooks were fully wired.
 *
 * Early builds only recorded a redemption on `eventin_order_completed`, so
 * local-payment and admin-completed coupon orders (which finalize via
 * `eventin_order_status_completed`) left no redemption row and never bumped
 * usage_count — the usage drill-in showed nothing. This scans every completed
 * order carrying a coupon and, for any without a redemption row, inserts one
 * (dated to the order) and recomputes the coupon's usage_count from the table.
 *
 * Idempotent: an existing-row check skips already-recorded orders, and
 * usage_count is recomputed from COUNT(*) rather than blindly incremented, so
 * re-running never double-counts.
 *
 * @since 4.1.18
 */
class V_4_1_18 implements UpdateInterface {
    /**
     * Run the updater.
     *
     * @return void
     */
    public function run() {
        global $wpdb;

        $coupons_table = $wpdb->prefix . 'etn_coupons';

        // Tables must exist (created by V_4_1_17) before backfilling.
        if ( $wpdb->get_var( "SHOW TABLES LIKE '{$coupons_table}'" ) !== $coupons_table ) { // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- SHOW TABLES needs a literal; name built from $wpdb->prefix.
            return;
        }

        $redemptions = new RedemptionModel();
        $coupons     = new CouponModel();

        $order_ids = get_posts( [
            'post_type'      => 'etn-order',
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- One-time migration.
                'relation' => 'AND',
                [ 'key' => 'coupon_id', 'value' => 0, 'compare' => '>', 'type' => 'NUMERIC' ],
                [ 'key' => 'status', 'value' => 'completed', 'compare' => '=' ],
            ],
        ] );

        if ( empty( $order_ids ) ) {
            return;
        }

        $touched_coupons = [];

        foreach ( $order_ids as $order_id ) {
            $coupon_id = (int) get_post_meta( $order_id, 'coupon_id', true );

            if ( ! $coupon_id || $redemptions->exists_for_order( $order_id ) ) {
                continue;
            }

            $order_date = get_post_field( 'post_date', $order_id );

            $redemptions->create( [
                'coupon_id'       => $coupon_id,
                'coupon_code'     => get_post_meta( $order_id, 'coupon_code', true ),
                'order_id'        => $order_id,
                'buyer_email'     => get_post_meta( $order_id, 'customer_email', true ),
                'discount_amount' => (float) get_post_meta( $order_id, 'discount_total', true ),
                'redeemed_at'     => $order_date ? $order_date : current_time( 'mysql' ),
            ] );

            $touched_coupons[ $coupon_id ] = true;
        }

        // Recompute usage_count from the redemption table for every affected coupon
        // (authoritative + safe to re-run; never double-counts).
        $redemptions_table = $wpdb->prefix . 'etn_coupon_redemptions';
        foreach ( array_keys( $touched_coupons ) as $coupon_id ) {
            $count = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$redemptions_table} WHERE coupon_id = %d", $coupon_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table from $wpdb->prefix; value prepared.
            $wpdb->update( $coupons_table, [ 'usage_count' => $count ], [ 'id' => $coupon_id ] ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Recompute; $wpdb->update escapes values.
        }
    }
}
