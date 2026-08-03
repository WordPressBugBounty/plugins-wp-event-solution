<?php
/**
 * Coupon Model
 *
 * @package Eventin\Coupon
 */

namespace Eventin\Coupon;

defined( 'ABSPATH' ) || exit;

use WP_Error;

/**
 * $wpdb repository over {$prefix}etn_coupons.
 *
 * Serializes the array fields (restricted_events/excluded_events/restricted_tickets)
 * to JSON on write, decodes + casts + derives status on read. `status` is never
 * stored — it is derived from active + validity window + usage_count, porting the
 * frontend mock's deriveStatus/combineDateTime so admin list and checkout agree.
 */
class CouponModel {
    /**
     * Coupons table name.
     *
     * @var string
     */
    private $table;

    /**
     * Array-typed columns stored as JSON.
     *
     * @var array
     */
    private $json_fields = [ 'restricted_events', 'excluded_events', 'restricted_tickets' ];

    /**
     * Constructor.
     */
    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'etn_coupons';
    }

    /**
     * Whether WooCommerce owns checkout — i.e. Woo is the selected ticket
     * payment method in Eventin settings, not merely the plugin being active.
     *
     * Native coupons are disabled only in this case (Woo has its own coupon
     * system). Having WooCommerce installed/active while Eventin sells tickets
     * natively must NOT disable native coupons.
     *
     * @return bool
     */
    public static function woo_owns_checkout() {
        return 'woocommerce' === etn_get_option( 'sell_tickets' );
    }

    /**
     * Find a coupon by id.
     *
     * @param int $id Coupon id.
     * @return array|null
     */
    public function find( $id ) {
        global $wpdb;

        $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->table} WHERE id = %d", intval( $id ) ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name built from $wpdb->prefix; value is prepared.

        return $row ? $this->to_object( $row ) : null;
    }

    /**
     * Find a coupon by its (normalized) code.
     *
     * @param string $code Coupon code.
     * @return array|null
     */
    public function find_by_code( $code ) {
        global $wpdb;

        $code = strtoupper( trim( (string) $code ) );

        $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->table} WHERE code = %s", $code ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name built from $wpdb->prefix; value is prepared.

        return $row ? $this->to_object( $row ) : null;
    }

    /**
     * Query coupons with search / status / date filters and pagination.
     *
     * search + date filters run as SQL WHERE. status is derived, so — mirroring the
     * mock's mockList — the full filtered set is fetched, status-filtered in PHP,
     * then sliced, so total_items and pagination stay correct.
     *
     * @param array $args search, status, date_from, date_to, paged, per_page.
     * @return array { items: array, total_items: int }
     */
    public function query( $args = [] ) {
        global $wpdb;

        $search    = isset( $args['search'] ) ? trim( (string) $args['search'] ) : '';
        $status    = isset( $args['status'] ) ? sanitize_text_field( $args['status'] ) : '';
        $date_from = isset( $args['date_from'] ) ? sanitize_text_field( $args['date_from'] ) : '';
        $date_to   = isset( $args['date_to'] ) ? sanitize_text_field( $args['date_to'] ) : '';
        $paged     = ! empty( $args['paged'] ) ? max( 1, intval( $args['paged'] ) ) : 1;
        $per_page  = ! empty( $args['per_page'] ) ? max( 1, intval( $args['per_page'] ) ) : 10;

        $where  = [ '1=1' ];
        $params = [];

        if ( '' !== $search ) {
            $where[]  = 'code LIKE %s';
            $params[] = '%' . $wpdb->esc_like( $search ) . '%';
        }

        if ( '' !== $date_from ) {
            $where[]  = '( end_date IS NULL OR end_date >= %s )';
            $params[] = $date_from;
        }

        if ( '' !== $date_to ) {
            $where[]  = '( start_date IS NULL OR start_date <= %s )';
            $params[] = $date_to;
        }

        $where_sql = implode( ' AND ', $where );
        $sql       = "SELECT * FROM {$this->table} WHERE {$where_sql} ORDER BY id DESC";

        if ( $params ) {
            $sql = $wpdb->prepare( $sql, $params ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Placeholders assembled above; values in $params.
        }

        $rows = $wpdb->get_results( $sql, ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared -- Table name from $wpdb->prefix; values prepared above.
        $rows = is_array( $rows ) ? $rows : [];

        $items = array_map( [ $this, 'to_object' ], $rows );

        if ( '' !== $status ) {
            $items = array_values(
                array_filter(
                    $items,
                    function ( $c ) use ( $status ) {
                        return $c['status'] === $status;
                    }
                )
            );
        }

        $total_items = count( $items );
        $offset      = ( $paged - 1 ) * $per_page;
        $items       = array_slice( $items, $offset, $per_page );

        return [
            'items'       => $items,
            'total_items' => $total_items,
        ];
    }

    /**
     * Create a coupon. Enforces unique code.
     *
     * @param array $data Coupon data.
     * @return array|WP_Error Created coupon, or duplicate WP_Error (409).
     */
    public function create( $data ) {
        global $wpdb;

        $row  = $this->to_row( $data );
        $code = $row['code'];

        if ( $this->find_by_code( $code ) ) {
            return new WP_Error( 'duplicate', __( 'Coupon code already exists.', 'eventin' ), [ 'status' => 409 ] );
        }

        $row['usage_count'] = 0;
        $row['created_at']  = current_time( 'mysql' );

        $inserted = $wpdb->insert( $this->table, $row ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- $wpdb->insert escapes values; table from $wpdb->prefix.

        if ( ! $inserted ) {
            return new WP_Error( 'coupon_create_error', __( 'Could not create coupon.', 'eventin' ), [ 'status' => 500 ] );
        }

        return $this->find( $wpdb->insert_id );
    }

    /**
     * Update a coupon. Enforces unique code (excluding self).
     *
     * @param int   $id   Coupon id.
     * @param array $data Coupon data.
     * @return array|WP_Error Updated coupon, or WP_Error.
     */
    public function update( $id, $data ) {
        global $wpdb;

        $id       = intval( $id );
        $existing = $this->find( $id );

        if ( ! $existing ) {
            return new WP_Error( 'not_found', __( 'Coupon not found.', 'eventin' ), [ 'status' => 404 ] );
        }

        $row  = $this->to_row( $data );
        $code = $row['code'];

        $by_code = $this->find_by_code( $code );
        if ( $by_code && intval( $by_code['id'] ) !== $id ) {
            return new WP_Error( 'duplicate', __( 'Coupon code already exists.', 'eventin' ), [ 'status' => 409 ] );
        }

        // usage_count and created_at are server-owned — never overwrite on update.
        unset( $row['usage_count'], $row['created_at'] );

        $wpdb->update( $this->table, $row, [ 'id' => $id ] ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- $wpdb->update escapes values; table from $wpdb->prefix.

        return $this->find( $id );
    }

    /**
     * Delete one coupon.
     *
     * @param int $id Coupon id.
     * @return bool
     */
    public function delete( $id ) {
        global $wpdb;

        return (bool) $wpdb->delete( $this->table, [ 'id' => intval( $id ) ] ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- $wpdb->delete escapes values; table from $wpdb->prefix.
    }

    /**
     * Delete many coupons.
     *
     * @param array $ids Coupon ids.
     * @return int Number of rows deleted.
     */
    public function delete_many( $ids ) {
        global $wpdb;

        $ids = array_filter( array_map( 'intval', (array) $ids ) );

        if ( ! $ids ) {
            return 0;
        }

        $placeholders = implode( ',', array_fill( 0, count( $ids ), '%d' ) );
        $sql          = $wpdb->prepare( "DELETE FROM {$this->table} WHERE id IN ({$placeholders})", $ids ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Placeholders built from int ids; values prepared.

        return (int) $wpdb->query( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared -- Prepared above.
    }

    /**
     * Whether at least one coupon is currently usable for a given event.
     *
     * "Usable" = active-flagged, inside its validity window (derived status
     * 'active'), the event is NOT excluded, and — if the coupon whitelists events
     * — the event is included. When $ticket_slugs is supplied (the cart's tickets),
     * a ticket-restricted coupon also has to cover at least one of those tickets;
     * otherwise it can never apply to this cart and should not show the field. Other
     * cart-dependent rules (min purchase/qty) stay as apply-time errors. Used to
     * gate the checkout coupon field.
     *
     * @param int        $event_id     Event id.
     * @param array|null $ticket_slugs Cart ticket slugs, or null to skip the ticket check.
     * @return bool
     */
    public function has_applicable_for_event( $event_id, $ticket_slugs = null ) {
        global $wpdb;

        $event_id      = intval( $event_id );
        $has_cart_info = is_array( $ticket_slugs );
        $ticket_slugs  = $has_cart_info ? array_map( 'strval', $ticket_slugs ) : [];

        $rows = $wpdb->get_results( "SELECT * FROM {$this->table} WHERE active = 1", ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table from $wpdb->prefix; no user input.

        foreach ( (array) $rows as $row ) {
            $c = $this->to_object( $row );

            if ( 'active' !== $c['status'] ) {
                continue;
            }

            if ( in_array( $event_id, array_map( 'intval', $c['excluded_events'] ), true ) ) {
                continue;
            }

            if ( $c['restricted_events'] && ! in_array( $event_id, array_map( 'intval', $c['restricted_events'] ), true ) ) {
                continue;
            }

            // Ticket-restricted coupon must cover a ticket actually in the cart.
            if ( $has_cart_info && $c['restricted_tickets'] && ! array_intersect( $c['restricted_tickets'], $ticket_slugs ) ) {
                continue;
            }

            return true;
        }

        return false;
    }

    /**
     * Atomically increment usage_count at redemption.
     *
     * @param int $id Coupon id.
     * @return void
     */
    public function increment_usage( $id ) {
        global $wpdb;

        $wpdb->query( $wpdb->prepare( "UPDATE {$this->table} SET usage_count = usage_count + 1 WHERE id = %d", intval( $id ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Atomic counter; table from $wpdb->prefix; value prepared.
    }

    /**
     * Normalize incoming data into a DB row (JSON-encode arrays, uppercase code).
     *
     * @param array $data Incoming coupon data.
     * @return array
     */
    private function to_row( $data ) {
        $row = [];

        $row['code']           = strtoupper( trim( (string) ( isset( $data['code'] ) ? $data['code'] : '' ) ) );
        $row['discount_type']  = ( isset( $data['discount_type'] ) && 'fixed' === $data['discount_type'] ) ? 'fixed' : 'percentage';
        $row['discount_value'] = isset( $data['discount_value'] ) ? floatval( $data['discount_value'] ) : 0;

        $row['start_date'] = $this->nullable_date( isset( $data['start_date'] ) ? $data['start_date'] : null );
        $row['end_date']   = $this->nullable_date( isset( $data['end_date'] ) ? $data['end_date'] : null );
        $row['start_time'] = ! empty( $data['start_time'] ) ? sanitize_text_field( $data['start_time'] ) : null;
        $row['end_time']   = ! empty( $data['end_time'] ) ? sanitize_text_field( $data['end_time'] ) : null;

        $row['usage_limit']    = $this->nullable_int( isset( $data['usage_limit'] ) ? $data['usage_limit'] : null );
        $row['per_user_limit'] = $this->nullable_int( isset( $data['per_user_limit'] ) ? $data['per_user_limit'] : null );
        $row['min_qty']        = $this->nullable_int( isset( $data['min_qty'] ) ? $data['min_qty'] : null );
        $row['min_purchase']   = ( isset( $data['min_purchase'] ) && '' !== $data['min_purchase'] && null !== $data['min_purchase'] )
            ? floatval( $data['min_purchase'] )
            : null;

        $row['restricted_events']  = wp_json_encode( array_values( array_map( 'intval', (array) ( isset( $data['restricted_events'] ) ? $data['restricted_events'] : [] ) ) ) );
        $row['excluded_events']    = wp_json_encode( array_values( array_map( 'intval', (array) ( isset( $data['excluded_events'] ) ? $data['excluded_events'] : [] ) ) ) );
        $row['restricted_tickets'] = wp_json_encode( array_values( array_map( 'sanitize_text_field', (array) ( isset( $data['restricted_tickets'] ) ? $data['restricted_tickets'] : [] ) ) ) );

        // Discount base: 'total' (tickets + add-ons) or 'tickets' (ticket price only).
        $row['discount_scope'] = ( isset( $data['discount_scope'] ) && 'tickets' === $data['discount_scope'] ) ? 'tickets' : 'total';

        $row['active'] = ( ! isset( $data['active'] ) || $data['active'] ) ? 1 : 0;

        return $row;
    }

    /**
     * Convert a DB row into the API coupon object (decode JSON, cast, derive status).
     *
     * @param array $row DB row.
     * @return array
     */
    private function to_object( $row ) {
        $c = (array) $row;

        $c['id']             = intval( $c['id'] );
        $c['discount_value'] = floatval( $c['discount_value'] );
        $c['usage_count']    = intval( $c['usage_count'] );
        $c['active']         = (bool) intval( $c['active'] );

        $c['usage_limit']    = ( null === $c['usage_limit'] ) ? null : intval( $c['usage_limit'] );
        $c['per_user_limit'] = ( null === $c['per_user_limit'] ) ? null : intval( $c['per_user_limit'] );
        $c['min_qty']        = ( null === $c['min_qty'] ) ? null : intval( $c['min_qty'] );
        $c['min_purchase']   = ( null === $c['min_purchase'] ) ? null : floatval( $c['min_purchase'] );

        // Older rows (pre-migration) have no discount_scope — default to 'total'.
        $c['discount_scope'] = ( isset( $c['discount_scope'] ) && 'tickets' === $c['discount_scope'] ) ? 'tickets' : 'total';

        foreach ( $this->json_fields as $k ) {
            $decoded = ! empty( $c[ $k ] ) ? json_decode( $c[ $k ], true ) : [];
            $c[ $k ] = is_array( $decoded ) ? $decoded : [];
        }

        $c['status'] = $this->derive_status( $c );

        return $c;
    }

    /**
     * Derive active|inactive|scheduled|expired from validity window + active flag.
     *
     * Ports coupons-mock.js deriveStatus/combineDateTime. Times are compared in the
     * site timezone (wp_timezone) against "now".
     *
     * @param array $c Coupon (decoded).
     * @return string
     */
    private function derive_status( $c ) {
        if ( empty( $c['active'] ) ) {
            return 'inactive';
        }

        $now   = new \DateTime( 'now', wp_timezone() );
        $start = $this->combine_date_time( $c['start_date'], isset( $c['start_time'] ) ? $c['start_time'] : null, false );
        $end   = $this->combine_date_time( $c['end_date'], isset( $c['end_time'] ) ? $c['end_time'] : null, true );

        if ( $end && $end < $now ) {
            return 'expired';
        }

        if ( $start && $start > $now ) {
            return 'scheduled';
        }

        return 'active';
    }

    /**
     * Combine a "Y-m-d" date with an "hh:mm A" time into a site-timezone DateTime.
     * $end_of_day picks 23:59 vs 00:00 when no time is stored. Null date → null.
     *
     * @param string|null $date       Date string.
     * @param string|null $time       Time string.
     * @param bool        $end_of_day Default to end-of-day when time missing.
     * @return \DateTime|null
     */
    private function combine_date_time( $date, $time, $end_of_day ) {
        if ( empty( $date ) ) {
            return null;
        }

        try {
            $dt = new \DateTime( $date, wp_timezone() );
        } catch ( \Exception $e ) {
            return null;
        }

        $hh = $end_of_day ? 23 : 0;
        $mm = $end_of_day ? 59 : 0;

        if ( $time && preg_match( '/(\d{1,2}):(\d{2})\s*(AM|PM)/i', $time, $m ) ) {
            $hh = intval( $m[1] ) % 12;
            if ( 0 === strcasecmp( $m[3], 'PM' ) ) {
                $hh += 12;
            }
            $mm = intval( $m[2] );
        }

        $dt->setTime( $hh, $mm, 0 );

        return $dt;
    }

    /**
     * Validate a Y-m-d date string, or null.
     *
     * @param mixed $value Value.
     * @return string|null
     */
    private function nullable_date( $value ) {
        if ( empty( $value ) ) {
            return null;
        }

        $value = sanitize_text_field( $value );
        $d     = \DateTime::createFromFormat( 'Y-m-d', $value );

        return ( $d && $d->format( 'Y-m-d' ) === $value ) ? $value : null;
    }

    /**
     * Cast to a positive int, or null for blank/zero.
     *
     * @param mixed $value Value.
     * @return int|null
     */
    private function nullable_int( $value ) {
        if ( null === $value || '' === $value ) {
            return null;
        }

        $int = intval( $value );

        return $int > 0 ? $int : null;
    }
}
