<?php

namespace Eventin\Order;

defined( 'ABSPATH' ) || exit;

use Eventin\Support\DbLock;

/**
 * The ledger of temporary ticket/seat holds for one event.
 *
 * When the purchase timer is on, a visitor who clicks "Buy ticket" holds their
 * tickets (and seats) until the timer runs out. The hold has to be given back
 * afterwards, or the event slowly looks sold out while nothing was sold.
 *
 * Why a ledger instead of a counter
 * ---------------------------------
 * The old code kept only a `pending` number on each ticket variation: every hold
 * added to it, and one wp-cron job per hold subtracted from it again. That broke
 * in two ways:
 *
 *  1. The cron job was scheduled as
 *     `wp_schedule_single_event( $t, $hook, [ $event_id, $seat_ids, $tickets ] )`.
 *     WordPress refuses to schedule a job when an identical hook + identical
 *     arguments already exists within 10 minutes (see wp-includes/cron.php,
 *     "Check for a duplicated event"). Two visitors buying 1 x the same ticket
 *     produce identical arguments, so the second visitor's job was silently
 *     dropped and their hold was never given back.
 *  2. `wp_clear_scheduled_hook( $hook, $args )` on a completed order deleted
 *     *every* job with those arguments, including other visitors' jobs.
 *
 * Here each hold is one row with its own id, so:
 *  - every hold schedules a job with unique arguments and can never be suppressed;
 *  - `pending` is always recomputed from the rows, never incremented in place, so
 *    a lost or duplicated job cannot make the number drift;
 *  - every row carries its own expiry, so a site with a dead wp-cron still
 *    releases holds on the next write (`sync()` drops expired rows first).
 *
 * Rows are interchangeable: two rows for "1 x general" hold the same thing, so
 * removing either one is equally correct. That is what lets a completed order
 * release a hold without having to know which visitor created it.
 *
 * @since 4.1.23
 */
class TicketHold {

    /**
     * Post meta key holding the ledger, keyed by hold id.
     *
     * @var string
     */
    const META_KEY = 'eventin_ticket_holds';

    /**
     * Seconds a hold survives when no timer length is given.
     *
     * @var int
     */
    const DEFAULT_TTL = 660; // 10 minute timer + 1 minute of grace.

    /**
     * Add one hold and return its id.
     *
     * @param int   $event_id       Event the hold belongs to.
     * @param array $seat_ids       Seat ids being held (empty for events without a seat plan).
     * @param array $booked_tickets Rows of [ 'ticket_slug' => string, 'ticket_quantity' => int ].
     * @param int   $ttl            Seconds the hold lives for.
     *
     * @return string The hold id, or '' when there was nothing to hold.
     */
    public static function add( $event_id, $seat_ids = [], $booked_tickets = [], $ttl = self::DEFAULT_TTL ) {
        $seats   = self::normalize_seats( $seat_ids );
        $tickets = self::normalize_tickets( $booked_tickets );

        if ( ! $seats && ! $tickets ) {
            return '';
        }

        $hold_id = self::generate_id();

        self::write( $event_id, function ( $holds ) use ( $hold_id, $seats, $tickets, $ttl ) {
            $holds[ $hold_id ] = [
                'seats'   => $seats,
                'tickets' => $tickets,
                'expires' => time() + max( 60, (int) $ttl ),
            ];

            return $holds;
        } );

        return $hold_id;
    }

    /**
     * Remove one hold by id. Safe to call twice — a hold that is already gone is
     * simply not there, which is what a released hold should look like.
     *
     * @param int    $event_id Event id.
     * @param string $hold_id  Hold id returned by add().
     *
     * @return bool True when a row was removed.
     */
    public static function remove( $event_id, $hold_id ) {
        $removed = false;

        self::write( $event_id, function ( $holds ) use ( $hold_id, &$removed ) {
            if ( isset( $holds[ $hold_id ] ) ) {
                unset( $holds[ $hold_id ] );
                $removed = true;
            }

            return $holds;
        } );

        return $removed;
    }

    /**
     * Remove one hold that covers the given tickets/seats.
     *
     * Used when an order completes and we do not know which hold row it came
     * from. Rows for the same tickets are interchangeable, so releasing any one
     * of them keeps the total correct.
     *
     * @param int   $event_id       Event id.
     * @param array $booked_tickets Rows of [ 'ticket_slug' => ..., 'ticket_quantity' => ... ].
     * @param array $seat_ids       Seat ids from the order.
     *
     * @return bool True when a row was removed.
     */
    public static function remove_matching( $event_id, $booked_tickets = [], $seat_ids = [] ) {
        $wanted_tickets = self::normalize_tickets( $booked_tickets );
        $wanted_seats   = self::normalize_seats( $seat_ids );

        if ( ! $wanted_tickets && ! $wanted_seats ) {
            return false;
        }

        $removed = false;

        self::write( $event_id, function ( $holds ) use ( $wanted_tickets, $wanted_seats, &$removed ) {
            // An exact match first: same tickets and same seats.
            foreach ( $holds as $id => $hold ) {
                if ( $hold['tickets'] === $wanted_tickets && $hold['seats'] === $wanted_seats ) {
                    unset( $holds[ $id ] );
                    $removed = true;

                    return $holds;
                }
            }

            // Same tickets but the seats were not recorded on one of the two
            // sides. Still the same purchase.
            foreach ( $holds as $id => $hold ) {
                if ( $wanted_tickets && $hold['tickets'] === $wanted_tickets ) {
                    unset( $holds[ $id ] );
                    $removed = true;

                    return $holds;
                }
            }

            // Nothing beyond this point. Matching on a merely overlapping row
            // would let one buyer's payment release a different visitor's hold,
            // and that visitor could then lose the last seat to someone else.
            // Leaving the row alone is the safe miss: it expires on its own
            // within one timer window, and until then the ticket is only counted
            // as unavailable, never oversold.
            return $holds;
        } );

        return $removed;
    }

    /**
     * Drop expired rows and write the resulting counts back onto the event.
     *
     * This is the only place `pending` and `pending_seats` are written, so the
     * stored numbers always match the rows that are actually held.
     *
     * @param int $event_id Event id.
     *
     * @return void
     */
    public static function sync( $event_id ) {
        // Cheap check first. Syncing an event that holds nothing is a no-op, and
        // this runs on every hold and on every event in the hourly sweep, so it
        // must not take the write lock just to discover there is nothing to do.
        if ( ! self::needs_sync( $event_id ) ) {
            return;
        }

        self::write( $event_id, function ( $holds ) {
            return $holds;
        } );
    }

    /**
     * Is anything on this event out of step with its holds?
     *
     * A false answer means a sync would write nothing, so it can be skipped. A
     * stale read can only cause a skipped no-op, which the next call corrects.
     *
     * @param int $event_id Event id.
     *
     * @return bool
     */
    private static function needs_sync( $event_id ) {
        $holds = self::read( $event_id );
        $now   = time();

        // A row past its time has to be dropped.
        foreach ( $holds as $hold ) {
            if ( $hold['expires'] <= $now ) {
                return true;
            }
        }

        $per_slug = [];
        $seats    = [];

        foreach ( $holds as $hold ) {
            foreach ( $hold['tickets'] as $slug => $qty ) {
                $per_slug[ $slug ] = ( $per_slug[ $slug ] ?? 0 ) + (int) $qty;
            }
            $seats = array_merge( $seats, $hold['seats'] );
        }

        $stored_seats = etn_safe_decode( get_post_meta( $event_id, 'pending_seats', true ) );
        $stored_seats = is_array( $stored_seats ) ? array_values( $stored_seats ) : [];

        sort( $stored_seats );
        $seats = array_values( array_unique( $seats ) );
        sort( $seats );

        if ( $stored_seats != $seats ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison -- comparing scalar id lists.
            return true;
        }

        $variations = etn_safe_decode( get_post_meta( $event_id, 'etn_ticket_variations', true ) );

        if ( ! is_array( $variations ) ) {
            return false;
        }

        foreach ( $variations as $variation ) {
            if ( ! is_array( $variation ) ) {
                continue;
            }

            $slug = $variation['etn_ticket_slug'] ?? '';

            if ( (int) ( $variation['pending'] ?? 0 ) !== (int) ( $per_slug[ $slug ] ?? 0 ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Held quantity per ticket slug, ignoring expired rows.
     *
     * @param int $event_id Event id.
     *
     * @return array<string,int>
     */
    public static function pending_by_slug( $event_id ) {
        $totals = [];

        foreach ( self::live_holds( $event_id ) as $hold ) {
            foreach ( $hold['tickets'] as $slug => $qty ) {
                $totals[ $slug ] = ( $totals[ $slug ] ?? 0 ) + (int) $qty;
            }
        }

        return $totals;
    }

    /**
     * Seat ids currently held, ignoring expired rows.
     *
     * @param int $event_id Event id.
     *
     * @return int[]
     */
    public static function pending_seats( $event_id ) {
        $seats = [];

        foreach ( self::live_holds( $event_id ) as $hold ) {
            $seats = array_merge( $seats, $hold['seats'] );
        }

        return array_values( array_unique( $seats ) );
    }

    /**
     * All rows that have not expired yet.
     *
     * @param int $event_id Event id.
     *
     * @return array
     */
    public static function live_holds( $event_id ) {
        $now = time();

        return array_filter(
            self::read( $event_id ),
            function ( $hold ) use ( $now ) {
                return (int) $hold['expires'] > $now;
            }
        );
    }

    /**
     * Read and clean the ledger.
     *
     * @param int $event_id Event id.
     *
     * @return array
     */
    private static function read( $event_id ) {
        $holds = etn_safe_decode( get_post_meta( $event_id, self::META_KEY, true ) );

        if ( ! is_array( $holds ) ) {
            return [];
        }

        $clean = [];

        foreach ( $holds as $id => $hold ) {
            if ( ! is_array( $hold ) || ! isset( $hold['expires'] ) ) {
                continue;
            }

            $clean[ (string) $id ] = [
                'seats'   => self::normalize_seats( $hold['seats'] ?? [] ),
                'tickets' => self::normalize_tickets( $hold['tickets'] ?? [] ),
                'expires' => (int) $hold['expires'],
            ];
        }

        return $clean;
    }

    /**
     * Run a change against the ledger under a lock, drop expired rows, store the
     * result and write the derived counts onto the event.
     *
     * The lock matters because two visitors can hold seats in the same instant;
     * without it one read-modify-write overwrites the other and a hold is lost.
     *
     * @param int      $event_id Event id.
     * @param callable $mutator  Receives the current rows, returns the new rows.
     *
     * @return void
     */
    private static function write( $event_id, callable $mutator ) {
        $event_id = (int) $event_id;

        if ( ! $event_id ) {
            return;
        }

        $lock = 'eventin_ticket_hold_' . $event_id;
        $held = self::acquire_lock( $lock );

        try {
            // The row may have been written by another request since this one
            // warmed its cache; go back to the database for it.
            wp_cache_delete( $event_id, 'post_meta' );

            $holds = call_user_func( $mutator, self::read( $event_id ) );

            if ( ! is_array( $holds ) ) {
                $holds = [];
            }

            $now = time();

            foreach ( $holds as $id => $hold ) {
                if ( (int) $hold['expires'] <= $now ) {
                    unset( $holds[ $id ] );
                }
            }

            if ( $holds ) {
                update_post_meta( $event_id, self::META_KEY, $holds );
            } else {
                delete_post_meta( $event_id, self::META_KEY );
            }

            self::write_counts( $event_id, $holds );
        } finally {
            if ( $held ) {
                DbLock::release( $lock );
            }
        }
    }

    /**
     * Write `pending` onto every ticket variation and `pending_seats` onto the event.
     *
     * @param int   $event_id Event id.
     * @param array $holds    Live rows.
     *
     * @return void
     */
    private static function write_counts( $event_id, array $holds ) {
        $per_slug = [];
        $seats    = [];

        foreach ( $holds as $hold ) {
            foreach ( $hold['tickets'] as $slug => $qty ) {
                $per_slug[ $slug ] = ( $per_slug[ $slug ] ?? 0 ) + (int) $qty;
            }
            $seats = array_merge( $seats, $hold['seats'] );
        }

        $seats = array_values( array_unique( $seats ) );

        $variations = etn_safe_decode( get_post_meta( $event_id, 'etn_ticket_variations', true ) );

        if ( is_array( $variations ) ) {
            $changed = false;

            foreach ( $variations as &$variation ) {
                if ( ! is_array( $variation ) ) {
                    continue;
                }

                $slug    = $variation['etn_ticket_slug'] ?? '';
                $pending = (int) ( $per_slug[ $slug ] ?? 0 );

                if ( (int) ( $variation['pending'] ?? 0 ) !== $pending ) {
                    $changed = true;
                }

                $variation['pending'] = $pending;
            }
            unset( $variation );

            if ( $changed ) {
                update_post_meta( $event_id, 'etn_ticket_variations', $variations );
            }
        }

        $stored_seats = etn_safe_decode( get_post_meta( $event_id, 'pending_seats', true ) );
        $stored_seats = is_array( $stored_seats ) ? array_values( $stored_seats ) : [];

        if ( $stored_seats != $seats ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison -- order-insensitive compare of scalar lists.
            update_post_meta( $event_id, 'pending_seats', $seats );
        }
    }

    /**
     * Take the per-event lock, waiting briefly for a competing request.
     *
     * Returning false means the lock was not taken; the caller still writes,
     * because a rare lost update is corrected by the next sync, whereas
     * refusing to record a hold would let the event oversell.
     *
     * @param string $lock Lock name.
     *
     * @return bool
     */
    private static function acquire_lock( $lock ) {
        for ( $attempt = 0; $attempt < 10; $attempt++ ) {
            if ( DbLock::acquire( $lock, 30 ) ) {
                return true;
            }

            usleep( 50000 ); // 50ms.
        }

        return false;
    }

    /**
     * @return string
     */
    private static function generate_id() {
        return function_exists( 'wp_generate_uuid4' ) ? wp_generate_uuid4() : uniqid( 'hold_', true );
    }

    /**
     * Seat ids as a sorted list of positive integers.
     *
     * @param mixed $seat_ids Raw seat ids.
     *
     * @return int[]
     */
    private static function normalize_seats( $seat_ids ) {
        if ( ! is_array( $seat_ids ) ) {
            return [];
        }

        $seats = array_values( array_unique( array_filter( array_map( 'intval', $seat_ids ) ) ) );
        sort( $seats );

        return $seats;
    }

    /**
     * Ticket rows as a slug => quantity map, sorted by slug.
     *
     * Accepts both the wire shape ([ [ 'ticket_slug' => ..., 'ticket_quantity' => ... ] ])
     * and the stored shape ([ slug => qty ]).
     *
     * @param mixed $tickets Raw tickets.
     *
     * @return array<string,int>
     */
    private static function normalize_tickets( $tickets ) {
        if ( ! is_array( $tickets ) ) {
            return [];
        }

        $map = [];

        foreach ( $tickets as $key => $ticket ) {
            if ( is_array( $ticket ) ) {
                $slug = isset( $ticket['ticket_slug'] ) ? sanitize_text_field( (string) $ticket['ticket_slug'] ) : '';
                $qty  = isset( $ticket['ticket_quantity'] ) ? (int) $ticket['ticket_quantity'] : 0;
            } else {
                $slug = sanitize_text_field( (string) $key );
                $qty  = (int) $ticket;
            }

            if ( '' === $slug || $qty <= 0 ) {
                continue;
            }

            $map[ $slug ] = ( $map[ $slug ] ?? 0 ) + $qty;
        }

        ksort( $map );

        return $map;
    }
}
