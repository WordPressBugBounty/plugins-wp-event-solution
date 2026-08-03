<?php
/**
 * Coupon Redemption Model
 *
 * @package Eventin\Coupon
 */

namespace Eventin\Coupon;

defined( 'ABSPATH' ) || exit;

/**
 * $wpdb repository over {$prefix}etn_coupon_redemptions.
 *
 * One row per redeemed order. Backs per-user limit enforcement and the admin
 * usage drill-in.
 */
class RedemptionModel {
    /**
     * Redemptions table name.
     *
     * @var string
     */
    private $table;

    /**
     * Constructor.
     */
    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'etn_coupon_redemptions';
    }

    /**
     * Insert a redemption row.
     *
     * @param array $row coupon_id, coupon_code, order_id, buyer_email, discount_amount.
     * @return bool
     */
    public function create( $row ) {
        global $wpdb;

        $data = [
            'coupon_id'       => intval( isset( $row['coupon_id'] ) ? $row['coupon_id'] : 0 ),
            'coupon_code'     => strtoupper( trim( (string) ( isset( $row['coupon_code'] ) ? $row['coupon_code'] : '' ) ) ),
            'order_id'        => intval( isset( $row['order_id'] ) ? $row['order_id'] : 0 ),
            'buyer_email'     => sanitize_email( isset( $row['buyer_email'] ) ? $row['buyer_email'] : '' ),
            'discount_amount' => floatval( isset( $row['discount_amount'] ) ? $row['discount_amount'] : 0 ),
            // Defaults to now; a backfill may pass the original order date instead.
            'redeemed_at'     => ! empty( $row['redeemed_at'] ) ? (string) $row['redeemed_at'] : current_time( 'mysql' ),
        ];

        return (bool) $wpdb->insert( $this->table, $data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- $wpdb->insert escapes values; table from $wpdb->prefix.
    }

    /**
     * Whether a redemption already exists for an order (idempotency guard for
     * retried webhooks / repeated completion events).
     *
     * @param int $order_id Order id.
     * @return bool
     */
    public function exists_for_order( $order_id ) {
        global $wpdb;

        $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$this->table} WHERE order_id = %d", intval( $order_id ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table from $wpdb->prefix; value prepared.

        return intval( $count ) > 0;
    }

    /**
     * Count redemptions of a coupon by a buyer email (per_user_limit enforcement).
     *
     * @param int    $coupon_id Coupon id.
     * @param string $email     Buyer email.
     * @return int
     */
    public function count_for_user( $coupon_id, $email ) {
        global $wpdb;

        $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$this->table} WHERE coupon_id = %d AND buyer_email = %s", intval( $coupon_id ), sanitize_email( $email ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table from $wpdb->prefix; values prepared.

        return intval( $count );
    }

    /**
     * Paginated redemptions for a coupon (admin drill-in).
     *
     * @param int $coupon_id Coupon id.
     * @param int $paged     Page (1-based).
     * @param int $per_page  Page size.
     * @return array { items: array, total_items: int }
     */
    public function paginate( $coupon_id, $paged = 1, $per_page = 10 ) {
        global $wpdb;

        $coupon_id = intval( $coupon_id );
        $paged     = max( 1, intval( $paged ) );
        $per_page  = max( 1, intval( $per_page ) );
        $offset    = ( $paged - 1 ) * $per_page;

        $total = intval( $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$this->table} WHERE coupon_id = %d", $coupon_id ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table from $wpdb->prefix; value prepared.

        $rows = $wpdb->get_results( $wpdb->prepare( "SELECT buyer_email, order_id, redeemed_at, discount_amount FROM {$this->table} WHERE coupon_id = %d ORDER BY id DESC LIMIT %d OFFSET %d", $coupon_id, $per_page, $offset ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table from $wpdb->prefix; values prepared.
        $rows = is_array( $rows ) ? $rows : [];

        $items = array_map(
            function ( $r ) {
                return [
                    'buyer_email'     => $r['buyer_email'],
                    'order_id'        => intval( $r['order_id'] ),
                    'date'            => $r['redeemed_at'],
                    'discount_amount' => floatval( $r['discount_amount'] ),
                ];
            },
            $rows
        );

        return [
            'items'       => $items,
            'total_items' => $total,
        ];
    }
}
