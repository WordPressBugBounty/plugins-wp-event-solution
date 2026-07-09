<?php

namespace Eventin\Reports;

defined( 'ABSPATH' ) || exit;

use Eventin\Input;
use Eventin\Order\OrderModel;
use Etn\Core\Event\Event_Model;

/**
 * Revenue  Report class
 * 
 * @package Eventin
 */
class RevenueReport extends AbstractReport {
    /**
     * Get total revenue
     *
     * @param   array  $dates  Start and end date
     *
     * @return  number
     */
    public static function get_total_revenue( $dates = [], $event_id = null ) {
        global $wpdb;

        // Add tax only for orders whose own tax_display_mode is not 'incl'.
        // Native orders are stored 'incl' (tax already inside total_price), so
        // they are not double-counted; WooCommerce orders keep their own mode.
        $revenue_expr = "COALESCE(price.meta_value + 0, 0) - COALESCE(discount.meta_value + 0, 0)"
            . " + CASE WHEN COALESCE(mode.meta_value, 'excl') = 'incl' THEN 0 ELSE COALESCE(tax.meta_value + 0, 0) END";

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
            $gross = (float) $row->gross;
            if ( 'partially_refunded' === $row->status && ! empty( $row->refunds_raw ) ) {
                $refunds = is_array( $row->refunds_raw ) ? $row->refunds_raw : json_decode( $row->refunds_raw, true );
                if ( is_array( $refunds ) ) {
                    foreach ( $refunds as $r ) {
                        $gross -= (float) ( $r['amount'] ?? 0 );
                    }
                }
            }
            $net += max( 0, $gross );
        }
        return $net;
    }

    /**
     * Get revenue for multiple events in a single query.
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

        // Per-order tax mode: native orders are 'incl' (tax already in total_price),
        // so tax is added only for non-'incl' (e.g. WooCommerce excl) orders.
        $revenue_expr = "COALESCE(price.meta_value + 0, 0) - COALESCE(discount.meta_value + 0, 0)"
            . " + CASE WHEN COALESCE(mode.meta_value, 'excl') = 'incl' THEN 0 ELSE COALESCE(tax.meta_value + 0, 0) END";

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

        return $map;
    }

    /**
     * Get total refunded amount
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

        foreach ( $order_ids as $order_id ) {
            $total_price    = floatval( get_post_meta( $order_id, 'total_price', true ) );
            $float_discount = floatval( get_post_meta( $order_id, 'discount_total', true ) );
            $float_tax      = floatval( get_post_meta( $order_id, 'tax_total', true ) );
            $mode           = get_post_meta( $order_id, 'tax_display_mode', true );

            if ( $mode === 'incl' ) {
                $total += $total_price - $float_discount;
            } else {
                $total += $total_price - $float_discount + $float_tax;
            }
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
