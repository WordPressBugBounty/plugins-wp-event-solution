<?php
namespace Eventin\Refund;

defined( 'ABSPATH' ) || exit;

class RefundModel {

    const META_KEY    = 'etn_refunds';
    const LOCK_PREFIX = 'etn_refund_lock_';
    const LOCK_TTL    = 5;

    /**
     * Append a refund record under a short-lived lock.
     *
     * @param int   $order_id
     * @param array $data
     * @return array|null The new refund object, or null if the lock could not be acquired.
     */
    public function create( $order_id, $data ) {
        $order_id = (int) $order_id;

        if ( ! $this->acquire_lock( $order_id ) ) {
            return null;
        }

        try {
            $existing = $this->find_by_order( $order_id );
            $next_id  = $this->next_id( $existing );

            $type = sanitize_key( isset( $data['type'] ) ? $data['type'] : 'ticket' );
            if ( ! in_array( $type, [ 'ticket', 'amount' ], true ) ) {
                $type = 'ticket';
            }

            // slug_counts: map of ticket_slug => refunded count, captured at refund time.
            // Read by the sold-count aggregator (utils/helper.php) to subtract from
            // the order's contribution without mutating its `tickets` meta.
            $slug_counts = [];
            if ( ! empty( $data['slug_counts'] ) && is_array( $data['slug_counts'] ) ) {
                foreach ( $data['slug_counts'] as $slug => $count ) {
                    $slug = sanitize_text_field( (string) $slug );
                    if ( $slug !== '' ) {
                        $slug_counts[ $slug ] = max( 0, (int) $count );
                    }
                }
            }

            $refund = [
                'id'                => $next_id,
                'type'              => $type,
                'attendee_ids'      => array_values( array_map( 'intval', isset( $data['attendee_ids'] ) ? (array) $data['attendee_ids'] : [] ) ),
                'slug_counts'       => $slug_counts,
                'amount'            => round( (float) ( isset( $data['amount'] ) ? $data['amount'] : 0 ), 2 ),
                'currency'          => substr( (string) ( isset( $data['currency'] ) ? $data['currency'] : 'USD' ), 0, 3 ),
                'reason'            => isset( $data['reason'] ) ? sanitize_textarea_field( $data['reason'] ) : null,
                'gateway'           => sanitize_key( isset( $data['gateway'] ) ? $data['gateway'] : '' ),
                'gateway_refund_id' => isset( $data['gateway_refund_id'] ) ? sanitize_text_field( $data['gateway_refund_id'] ) : null,
                'created_by'        => get_current_user_id(),
                'created_at'        => current_time( 'mysql' ),
            ];

            $existing[] = $refund;
            update_post_meta( $order_id, self::META_KEY, wp_json_encode( $existing ) );

            return $refund;
        } finally {
            $this->release_lock( $order_id );
        }
    }

    /**
     * @param int $order_id
     * @return array<int,array> Refunds in insertion order.
     */
    public function find_by_order( $order_id ) {
        $raw = get_post_meta( (int) $order_id, self::META_KEY, true );
        if ( empty( $raw ) ) {
            return [];
        }
        $decoded = is_array( $raw ) ? $raw : json_decode( $raw, true );
        return is_array( $decoded ) ? $decoded : [];
    }

    /**
     * @param int $order_id
     * @param int $refund_id
     * @return array|null
     */
    public function find( $order_id, $refund_id ) {
        foreach ( $this->find_by_order( $order_id ) as $r ) {
            if ( (int) $r['id'] === (int) $refund_id ) {
                return $r;
            }
        }
        return null;
    }

    /**
     * @param int $order_id
     * @return float
     */
    public function refunded_total( $order_id ) {
        $sum = 0.0;
        foreach ( $this->find_by_order( $order_id ) as $r ) {
            $sum += (float) $r['amount'];
        }
        return $sum;
    }

    /**
     * @param int $order_id
     * @return int[] Unique attendee IDs across all refunds.
     */
    public function refunded_attendee_ids( $order_id ) {
        $ids = [];
        foreach ( $this->find_by_order( $order_id ) as $r ) {
            foreach ( (array) ( isset( $r['attendee_ids'] ) ? $r['attendee_ids'] : [] ) as $aid ) {
                $ids[ (int) $aid ] = true;
            }
        }
        return array_keys( $ids );
    }

    /**
     * @param array $existing
     * @return int
     */
    protected function next_id( $existing ) {
        $max = 0;
        foreach ( $existing as $r ) {
            $max = max( $max, (int) $r['id'] );
        }
        return $max + 1;
    }

    /**
     * @param int $order_id
     * @return string
     */
    protected function lock_key( $order_id ) {
        return self::LOCK_PREFIX . (int) $order_id;
    }

    /**
     * Atomic CAS via add_option(autoload=no). Stale lock recovery once.
     *
     * @param int $order_id
     * @return bool
     */
    protected function acquire_lock( $order_id ) {
        $key      = $this->lock_key( $order_id );
        $acquired = add_option( $key, time(), '', 'no' );
        if ( $acquired ) {
            return true;
        }
        $held_at = (int) get_option( $key );
        if ( $held_at && ( time() - $held_at ) > self::LOCK_TTL ) {
            delete_option( $key );
            return (bool) add_option( $key, time(), '', 'no' );
        }
        return false;
    }

    /**
     * @param int $order_id
     */
    protected function release_lock( $order_id ) {
        delete_option( $this->lock_key( $order_id ) );
    }
}
