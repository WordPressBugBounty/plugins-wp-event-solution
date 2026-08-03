<?php

namespace Eventin\Reports;

defined( 'ABSPATH' ) || exit;

use Eventin\Input;
use Eventin\Order\OrderModel;
use Eventin\Refund\RefundService;
use Etn\Core\Event\Event_Model;

/**
 * Revenue  Report class
 * 
 * @package Eventin
 */
class RevenueReport extends AbstractReport {
    /**
     * SQL expression for what an order actually brought in.
     *
     * Two per-order rules, neither of which can be read off a global setting:
     *
     *  - **Tax** is added only for orders whose own `tax_display_mode` is not
     *    'incl'. Native orders are stored 'incl' (apply_native_tax() folded the
     *    tax into total_price already), so adding it again would double-count.
     *    WooCommerce orders keep whichever mode their prices were entered in.
     *  - **The coupon** is subtracted only for WooCommerce. Every other gateway
     *    has already taken it off total_price — apply_coupon() at order creation,
     *    apply_native_tax() at payment — so subtracting discount_total there
     *    counts the coupon twice and hides real income (a $200 order with a $20
     *    coupon, paid $180, would be reported as $160 of revenue).
     *
     * Same rule as {@see \Eventin\Refund\RefundService::final_amount_for_order()},
     * which is the authoritative statement of it; keep the two in step.
     *
     * The gateway is checked with a correlated EXISTS rather than a fifth
     * LEFT JOIN, and only for orders that actually carry a discount. Discounts
     * are rare (23 of 250,000 orders on the seeded sandbox), so the subquery
     * runs a handful of times instead of dragging another postmeta join across
     * every row. Measured on that sandbox: 13.53s median vs 13.13s before this
     * change, i.e. within noise; the plain join cost noticeably more.
     *
     * Callers must join `price`, `discount`, `tax` and `mode` against the order
     * id, and pass that id column as $order_id_col for the EXISTS correlation.
     *
     * @param   string  $order_id_col  SQL column holding the order id, e.g. 'p.ID'.
     *
     * @return  string
     */
    private static function revenue_expr( $order_id_col ) {
        global $wpdb;

        return "COALESCE(price.meta_value + 0, 0)"
            . " + CASE WHEN COALESCE(mode.meta_value, 'excl') = 'incl' THEN 0 ELSE COALESCE(tax.meta_value + 0, 0) END"
            . " - CASE WHEN COALESCE(discount.meta_value + 0, 0) <> 0"
            . " AND EXISTS ( SELECT 1 FROM {$wpdb->postmeta} gw"
            . " WHERE gw.post_id = {$order_id_col} AND gw.meta_key = 'payment_method' AND gw.meta_value = 'wc' )"
            . " THEN discount.meta_value + 0 ELSE 0 END";
    }

    /**
     * Get total revenue
     *
     * @param   array  $dates  Start and end date
     *
     * @return  number
     */
    public static function get_total_revenue( $dates = [], $event_id = null ) {
        global $wpdb;

        $revenue_expr = self::revenue_expr( 'p.ID' );

        // partially_refunded orders contribute (gross - sum of their refund amounts).
        // refunded orders are excluded entirely (net is 0).
        $sql = "
            SELECT COALESCE(SUM({$revenue_expr}), 0) AS gross, p.ID AS order_id, status_m.meta_value AS status, refunds.meta_value AS refunds_raw
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} status_m
                ON status_m.post_id = p.ID
                AND status_m.meta_key = 'status'
                AND status_m.meta_value IN ( 'completed', 'partially_refunded' )
            INNER JOIN {$wpdb->postmeta} price
                ON price.post_id = p.ID
                AND price.meta_key = 'total_price'
            LEFT JOIN {$wpdb->postmeta} discount
                ON discount.post_id = p.ID
                AND discount.meta_key = 'discount_total'
            LEFT JOIN {$wpdb->postmeta} tax
                ON tax.post_id = p.ID
                AND tax.meta_key = 'tax_total'
            LEFT JOIN {$wpdb->postmeta} mode
                ON mode.post_id = p.ID
                AND mode.meta_key = 'tax_display_mode'
            LEFT JOIN {$wpdb->postmeta} refunds
                ON refunds.post_id = p.ID
                AND refunds.meta_key = 'etn_refunds'
            WHERE p.post_type = 'etn-order'
            AND p.post_status != 'trash'
        ";

        $params = [];

        if ( ! empty( $dates['start_date'] ) && ! empty( $dates['end_date'] ) ) {
            $sql     .= " AND p.post_date >= %s AND p.post_date <= %s";
            $params[] = $dates['start_date'] . ' 00:00:00';
            $params[] = $dates['end_date'] . ' 23:59:59';
        }

        if ( ! empty( $event_id ) ) {
            $sql     .= " AND EXISTS (
                SELECT 1 FROM {$wpdb->postmeta} em
                WHERE em.post_id = p.ID AND em.meta_key = 'event_id' AND em.meta_value = %d
            )";
            $params[] = $event_id;
        } elseif ( ! current_user_can( 'manage_options' ) ) {
            $event     = new Event_Model();
            $event_ids = $event->get_ids_by_author( get_current_user_id() );

            if ( empty( $event_ids ) ) {
                return 0;
            }

            $placeholders = implode( ',', array_fill( 0, count( $event_ids ), '%d' ) );
            $sql         .= " AND EXISTS (
                SELECT 1 FROM {$wpdb->postmeta} em
                WHERE em.post_id = p.ID AND em.meta_key = 'event_id' AND em.meta_value IN ({$placeholders})
            )";
            $params = array_merge( $params, $event_ids );
        }

        // GROUP BY so we get one row per order with its gross + refunds_raw.
        $sql .= " GROUP BY p.ID, status_m.meta_value, refunds.meta_value";

        if ( ! empty( $params ) ) {
            // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            $sql = $wpdb->prepare( $sql, $params );
        }

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $rows = $wpdb->get_results( $sql );
        if ( empty( $rows ) ) {
            return 0.0;
        }

        $net = 0.0;
        foreach ( $rows as $row ) {
            $net += self::net_of_refunds( (float) $row->gross, $row->refunds_raw );
        }
        return $net;
    }

    /**
     * What an order kept, after subtracting everything refunded against it.
     *
     * Refunds live as a JSON blob in the `etn_refunds` meta, so they cannot be
     * summed in SQL — every caller has to post-process. Clamped at zero per
     * order so one over-refunded booking can never eat another's revenue.
     *
     * @param   float  $gross        The order's revenue before refunds.
     * @param   mixed  $refunds_raw  Raw `etn_refunds` meta (JSON string or array).
     *
     * @return  float
     */
    private static function net_of_refunds( $gross, $refunds_raw ) {
        if ( empty( $refunds_raw ) ) {
            return max( 0.0, (float) $gross );
        }

        $refunds = is_array( $refunds_raw ) ? $refunds_raw : json_decode( $refunds_raw, true );

        if ( is_array( $refunds ) ) {
            foreach ( $refunds as $refund ) {
                $gross -= (float) ( $refund['amount'] ?? 0 );
            }
        }

        return max( 0.0, (float) $gross );
    }

    /**
     * Get revenue for multiple events.
     *
     * Must agree with {@see get_total_revenue()} filtered to the same event —
     * the events list and the dashboard total are read side by side.
     *
     * Runs in two passes because refunds are a JSON blob and cannot be summed in
     * SQL. The first pass aggregates completed bookings with SUM/GROUP BY, which
     * is the hot path and stays exactly as it was. The second pass handles
     * part-refunded bookings one row at a time — they are rare, and doing them
     * per order is what lets each be clamped at zero individually, matching how
     * get_total_revenue() treats them.
     *
     * @param   int[]  $event_ids  List of event IDs
     *
     * @return  array  Map of event_id => revenue
     */
    public static function get_revenue_map_by_event_ids( array $event_ids ) {
        global $wpdb;

        if ( empty( $event_ids ) ) {
            return [];
        }

        $revenue_expr = self::revenue_expr( 'em.post_id' );

        $placeholders = implode( ',', array_fill( 0, count( $event_ids ), '%d' ) );

        // IMPORTANT: drive the join from `em` (event_id IN (...)) — the most selective
        // filter on this page — instead of letting MySQL start from status='completed',
        // which matches every completed order in the site. On large datasets the default
        // optimizer plan scans hundreds of thousands of `status` rows and the query takes
        // several seconds; STRAIGHT_JOIN with `em` first keeps it to the handful of orders
        // that actually belong to the requested events. See get_revenue_map perf notes.
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $sql = $wpdb->prepare(
            "SELECT STRAIGHT_JOIN em.meta_value AS event_id, COALESCE(SUM({$revenue_expr}), 0) AS revenue
            FROM {$wpdb->postmeta} em
            INNER JOIN {$wpdb->posts} p
                ON p.ID = em.post_id
                AND p.post_type = 'etn-order'
                AND p.post_status != 'trash'
            INNER JOIN {$wpdb->postmeta} status_m
                ON status_m.post_id = em.post_id
                AND status_m.meta_key = 'status'
                AND status_m.meta_value = 'completed'
            INNER JOIN {$wpdb->postmeta} price
                ON price.post_id = em.post_id
                AND price.meta_key = 'total_price'
            LEFT JOIN {$wpdb->postmeta} discount
                ON discount.post_id = em.post_id
                AND discount.meta_key = 'discount_total'
            LEFT JOIN {$wpdb->postmeta} tax
                ON tax.post_id = em.post_id
                AND tax.meta_key = 'tax_total'
            LEFT JOIN {$wpdb->postmeta} mode
                ON mode.post_id = em.post_id
                AND mode.meta_key = 'tax_display_mode'
            WHERE em.meta_key = 'event_id'
            AND em.meta_value IN ({$placeholders})
            GROUP BY em.meta_value", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
            $event_ids
        );

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $rows = $wpdb->get_results( $sql );

        $map = [];
        foreach ( $rows as $row ) {
            $map[ (int) $row->event_id ] = (float) $row->revenue;
        }

        return self::add_part_refunded_revenue( $map, $event_ids );
    }

    /**
     * Add what part-refunded bookings still contributed, event by event.
     *
     * These are excluded from the aggregate pass above because the amount they
     * kept can only be worked out in PHP: `etn_refunds` is a JSON blob. They are
     * a small minority of orders, so fetching them per row is cheap — and each
     * one is clamped at zero on its own, so an over-refunded booking cannot eat
     * another booking's revenue.
     *
     * @param   array  $map        Map of event_id => revenue so far.
     * @param   int[]  $event_ids  Events being reported on.
     *
     * @return  array  The map with part-refunded bookings folded in.
     */
    private static function add_part_refunded_revenue( array $map, array $event_ids ) {
        global $wpdb;

        $revenue_expr = self::revenue_expr( 'em.post_id' );
        $placeholders = implode( ',', array_fill( 0, count( $event_ids ), '%d' ) );

        // NOTE: deliberately NO STRAIGHT_JOIN here, unlike the aggregate query
        // above. There the driving filter is status='completed', which matches
        // most orders, so `em` first is the win. Here the driving filter is
        // status='partially_refunded', which is rare — a handful of rows on a
        // 250k-order site — so the optimizer must be free to start from it.
        // Forcing `em` first instead cost ~0.19s per call on that dataset.
        // The INNER JOIN on `refunds` also drops any order with no refund record.
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $sql = $wpdb->prepare(
            "SELECT em.meta_value AS event_id, {$revenue_expr} AS gross, refunds.meta_value AS refunds_raw
            FROM {$wpdb->postmeta} status_m
            INNER JOIN {$wpdb->postmeta} em
                ON em.post_id = status_m.post_id
                AND em.meta_key = 'event_id'
            INNER JOIN {$wpdb->posts} p
                ON p.ID = em.post_id
                AND p.post_type = 'etn-order'
                AND p.post_status != 'trash'
            INNER JOIN {$wpdb->postmeta} refunds
                ON refunds.post_id = em.post_id
                AND refunds.meta_key = 'etn_refunds'
            INNER JOIN {$wpdb->postmeta} price
                ON price.post_id = em.post_id
                AND price.meta_key = 'total_price'
            LEFT JOIN {$wpdb->postmeta} discount
                ON discount.post_id = em.post_id
                AND discount.meta_key = 'discount_total'
            LEFT JOIN {$wpdb->postmeta} tax
                ON tax.post_id = em.post_id
                AND tax.meta_key = 'tax_total'
            LEFT JOIN {$wpdb->postmeta} mode
                ON mode.post_id = em.post_id
                AND mode.meta_key = 'tax_display_mode'
            WHERE status_m.meta_key = 'status'
            AND status_m.meta_value = 'partially_refunded'
            AND em.meta_value IN ({$placeholders})", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
            $event_ids
        );

        // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
        $rows = $wpdb->get_results( $sql );

        foreach ( (array) $rows as $row ) {
            $event_id = (int) $row->event_id;

            $map[ $event_id ] = ( isset( $map[ $event_id ] ) ? (float) $map[ $event_id ] : 0.0 )
                + self::net_of_refunds( (float) $row->gross, $row->refunds_raw );
        }

        return $map;
    }

    /**
     * Get total refunded amount
     *
     * Sums what each fully-refunded order was worth, which for a fully-refunded
     * order is exactly what the customer got back. The per-order value comes from
     * {@see \Eventin\Refund\RefundService::final_amount_for_order()} rather than
     * being re-derived here — that method is the one place the tax-mode and
     * coupon rules are stated, and re-deriving them is what let this figure
     * under-report native coupon orders by the value of the coupon.
     *
     * NB: `OrderReport::get_refunded_orders()` matches status 'refunded' only, so
     * partial refunds are not represented in this total.
     *
     * @param   array  $dates  Start and end date
     *
     * @return  number
     */
    public static function get_total_refunded_amount( $dates = [], $event_id = null ) {
        $total = 0;
        $order_ids = OrderReport::get_refunded_orders( $dates, $event_id );

        if ( empty( $order_ids ) ) {
            return $total;
        }

        // Batch-load all order meta in a single query
        update_meta_cache( 'post', $order_ids );

        $refunds = new RefundService();

        foreach ( $order_ids as $order_id ) {
            $total += $refunds->final_amount_for_order( new OrderModel( $order_id ) );
        }

        return $total;
    }

    /**
     * Get revenue by event
     *
     * @param   array  $data  Date range and event id
     *
     * @return  array
     */
    public static function get_reports_by_event( $data = [] ) {
        $reports = [
            'total' => self::get_total_revenue_by_event( $data ),
        ];

        $ticket_reports = self::get_total_revenue_by_tickets( $data );

        return array_merge( $reports, $ticket_reports );
    }

    /**
     * Get total revenue by event
     *
     * @param   array  $data  [$data description]
     *
     * @return  integer
     */
    public static function get_total_revenue_by_event( $data = [] ) {
        $orders = OrderReport::get_orders_by_event( $data );
        $total = 0;

        if ( is_array( $orders ) ) {
            foreach( $orders as $order_id ) {
                $order = new OrderModel( $order_id );
                $total += $order->total_price;
            }
        }

        return $total;
    }

    /**
     * Get revenue for every ticket
     *
     * @param   array  $data  Event data
     *
     * @return  array
     */
    private static function get_total_revenue_by_tickets( $data ) {
        $tickets    = EventReport::get_ticket_reports_by_event( $data );
        $total      = 0;
        $event      = new Event_Model( $data['event_id'] );
        $variations = $event->etn_ticket_variations;
        $revenue    = [];

        if ( is_array( $tickets ) ) {
            foreach( $tickets as $ticket_name => $ticket ) {
                $price = $event->get_ticket_price_by_name( $ticket_name );
                $revenue[$ticket_name] = $tickets[$ticket_name]['sold'] * $price;
            }
        }
        
        return $revenue;
    }
}
