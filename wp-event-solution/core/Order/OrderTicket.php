<?php

namespace Eventin\Order;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Event\Event_Model;
use Eventin\Emails\AttendeeOrderEmail;
use Eventin\Interfaces\HookableInterface;
use Eventin\Mails\Mail;
use Wpeventin;
use Eventin\Order\OrderModel;
use Etn\Core\Attendee\Attendee_Model;
use Etn\Utils\Helper;

class OrderTicket implements HookableInterface {
    /**
     * Register service
     *
     * @return  void
     */
    public function register_hooks(): void {
        add_action( 'eventin_order_completed', [$this, 'order_status_completed'] );
        add_action( 'eventin_order_status_completed', [$this, 'order_status_completed'] );
        add_action( 'eventin_order_status_failed', [$this, 'order_status_failed'] );
        // WooCommerce (eventin_order_update) and the SureCart add-on announce a failed or
        // cancelled order under this other name. Without it a cancelled WooCommerce order
        // kept its tickets in the stored sold count. Listening here, instead of firing
        // both names there, keeps add-ons that already hear both (LearnDash, Tutor LMS)
        // from running twice. order_status_failed() recounts, so a second call is harmless.
        add_action( 'eventin_order_failed', [$this, 'order_status_failed'] );

        add_action( 'eventin_attendee_created', [ $this, 'send_attendee_ticket' ] );

        add_action( 'eventin_attendee_created', [ $this, 'decrease_ticket_after_attendee_create' ] );

        add_action( 'eventin_order_refund', [ $this, 'decrese_event_sold_ticket_after_refund' ] );
        
        add_action( 'eventin_order_before_delete', [ $this, 'decrese_event_sold_ticket_after_order_delete' ] );

        add_action( 'eventin_attendee_before_delete', [ $this, 'decrese_event_sold_ticket_after_attendee_delete' ] );

        add_action( 'eventin_release_held_tickets', [ $this, 'release_held_tickets' ] ); // from cron
        add_action( 'eventin_release_held_seats_and_tickets', [ $this, 'release_held_seats_and_tickets' ], 10, 3 );
        add_action( 'eventin_release_pending_seats_and_tickets', [ $this, 'eventin_release_pending_seats_and_tickets' ] ); // from cron

        /**
         * Add custom cron schedule
         *
         * @param array $schedules Schedules.
         *
         * @return array
         */
        add_filter( 'cron_schedules', function ( $schedules ) {
            $schedules['every_sixty_minutes'] = [
                'interval' => 60 * 60,
                'display'  => 'Every 60 Minutes'
            ];

            return $schedules;
        });

        /**
         * Schedule event to release pending seats and tickets
         *
         * @return void
         */
        add_action( 'init', function () {
            if ( ! wp_next_scheduled( 'eventin_release_pending_seats_and_tickets' ) ) {
                wp_schedule_event( time(), 'every_sixty_minutes', 'eventin_release_pending_seats_and_tickets' );
            }
        });
    }

    /**
     * Hourly safety net: bring every event's `pending` counters back in line
     * with the holds that are actually still live.
     *
     * Before 4.1.23 this walked failed orders and guessed. It had a hole: a stuck
     * hold on ticket A was skipped whenever the event had *any* failed order for
     * ticket B, because the reset sat in an `elseif` that only ran when the event
     * had no failed orders at all. Sites hit that hole permanently and their
     * tickets stayed "sold out" with stock left.
     *
     * The ledger removes the guesswork: every live hold is a row with an expiry,
     * so syncing an event is enough. It also repairs counters left behind by
     * older versions, which have no rows and therefore sync down to zero.
     *
     * @return void
     */
    public function eventin_release_pending_seats_and_tickets() {
        // Every event that holds something, or carries a counter written before
        // the ledger existed. No limit: the old version walked *every* published
        // event and did work on each, so this is strictly less. Ids only, and
        // TicketHold::sync() returns without a write when nothing is out of step.
        $events = get_posts( [
            'post_type'        => 'etn',
            'post_status'      => 'any',
            'posts_per_page'   => -1,
            'fields'           => 'ids',
            'no_found_rows'    => true,
            'suppress_filters' => false,
            'meta_query'       => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                'relation' => 'OR',
                // A live hold.
                [
                    'key'     => TicketHold::META_KEY,
                    'compare' => 'EXISTS',
                ],
                // Seats marked as held, with or without a ledger behind them.
                [
                    'key'     => 'pending_seats',
                    'compare' => 'EXISTS',
                ],
                // Any variation carrying a `pending` key at all. This matches
                // zero counters too — narrowing it further would mean matching
                // on serialized text, which breaks as soon as the value type
                // changes (i:17 vs s:2:"17"). sync() no-ops on those.
                [
                    'key'     => 'etn_ticket_variations',
                    'value'   => '"pending"',
                    'compare' => 'LIKE',
                ],
            ],
        ] );

        foreach ( $events as $event_id ) {
            TicketHold::sync( $event_id );
        }
    }

    /**
     * After booking update event ticket status
     *
     * @param   OrderModel  $order  The order need to update
     *
     * @return  void
     */
    public function order_status_completed( $order ) {
        if ( 'completed' !== $order->status ) {
            return;
        }

        $event = new Event_Model( $order->event_id );

        $event_tickets = $event->etn_ticket_variations;
        $is_waiting_list_order = (bool) get_post_meta( $order->id, 'is_from_waiting_list', true );

        $updated_tickets = [];

        if ( $event_tickets ) {
            foreach( $event_tickets as $ticket ) {
                $updated_ticket = $this->prepare_event_ticket( $order, $ticket, $is_waiting_list_order );

                $updated_tickets[] = $updated_ticket;
            }
        }

        $event_update = [ 'etn_ticket_variations' => $updated_tickets ];

        if ( $is_waiting_list_order ) {
            $enable_global_stock = get_post_meta( $order->event_id, 'etn_enable_global_stock', true );
            $total_ordered      = array_sum( array_column( (array) $order->tickets, 'ticket_quantity' ) );

            if ( $enable_global_stock ) {
                // A waiting-list conversion is admitted on top of the sold-out
                // capacity, so the seat has to be granted here.
                $event_update['etn_global_stock'] = (int) get_post_meta( $order->event_id, 'etn_global_stock', true ) + $total_ordered;
            }

            // The waiting-list limits (etn_global_waiting_list and the per-ticket
            // etn_ticket_waiting_list_limit) are configuration, not counters, and
            // are deliberately left untouched here. Occupancy is derived from the
            // signup orders themselves - see etn_get_waiting_list_counts_by_slug().
            // Decrementing the stored limit as well subtracted every converted
            // signup twice, so the public "spots available" figure drained at
            // double speed and never recovered.
        }

        $event->update( $event_update );

        $this->update_booked_seat($event, $order);
        $this->update_pending_seat($event, $order);

        $booked_seats   = etn_safe_decode(get_post_meta($order->id, 'seat_ids', true));
        $booked_tickets = $order->tickets;
        $formatted_booked_tickets = [];
        if (!empty($booked_tickets)) {
            foreach ($booked_tickets as $ticket) {
                $formatted_booked_tickets[] = [
                    'ticket_slug' => $ticket['ticket_slug'],
                    'ticket_quantity' => $ticket['ticket_quantity']
                ];
            }
        }

        \Etn\Utils\Helper::increase_count_by_ticket_slug($formatted_booked_tickets,$event->id);

        // These tickets are sold now, so the hold behind them has to go — otherwise
        // they would be counted twice (once sold, once held) until the timer ran out.
        //
        // We do not know which hold row this buyer created, and we do not need to:
        // rows for the same tickets hold the same thing, so releasing any matching
        // row keeps the total right. `sync()` then rewrites the counters, which is
        // also what corrects the numbers written by the pre-4.1.23 code path above.
        if ( ! TicketHold::remove_matching( $event->id, $formatted_booked_tickets, is_array( $booked_seats ) ? $booked_seats : [] ) ) {
            TicketHold::sync( $event->id );
        }
    }

    /**
     * Prepare updated event ticket
     *
     * @param   OrderModel  $order  [$order description]
     * @param   string  $slug   [$slug description]
     *
     * @return  array          [return description]
     */
    private function prepare_event_ticket( $order, $event_ticket, $is_waiting_list = false ) {
        $order_tickets = $order->tickets;
        $event_id = $order->event_id ?? null;
        $sold_tickets = $event_id ? (array)Helper::etn_get_sold_tickets_by_event( $event_id ) : [];

        foreach( $order_tickets as $ticket ) {
            if ( $ticket['ticket_slug'] === $event_ticket['etn_ticket_slug'] ) {
                $event_ticket['etn_sold_tickets'] = $sold_tickets[$ticket['ticket_slug']] ?? 0;

                if ( $is_waiting_list ) {
                    // Expand capacity to absorb the waiting-list conversion so the ticket
                    // never appears oversold after this order completes.
                    $event_ticket['etn_avaiilable_tickets'] = (int) ( $event_ticket['etn_avaiilable_tickets'] ?? 0 ) + (int) $ticket['ticket_quantity'];
                } else {
                    $event_ticket['pending'] = isset( $event_ticket['pending'] ) ? $event_ticket['pending'] - $ticket['ticket_quantity'] : 0;
                    if ( $event_ticket['pending'] < 0 ) {
                        $event_ticket['pending'] = 0;
                    }
                }
                break;
            }
        }

        return $event_ticket;
    }

    /**
     * Update booked event booked seats
     *
     * @param   Event_Model  $event  [$event description]
     * @param   Order_Model  $order  [$order description]
     *
     * @return  void
     */
    private function update_booked_seat( $event, $order ) {
        $event_seats = get_post_meta( $event->id, '_etn_seat_unique_id', true );

        $order_seats = $order->seat_ids;

        if ( ! $order_seats ) {
            return;
        }

        $event_seats = explode(',', $event_seats );

        $event_seats = array_merge( $event_seats, $order_seats );
        $event_seats = implode( ',', array_unique( $event_seats ) );

        update_post_meta( $event->id, '_etn_seat_unique_id', $event_seats );
    }

    /**
     * Update pending seats after booking
     *
     * @param   Event_Model  $event  [$event description]
     * @param   Order_Model  $order  [$order description]
     *
     * @return  void
     */
    public function update_pending_seat( $event, $order ) {
        $order_seats = $order->seat_ids;

        if ( empty( $order_seats ) ) {
            return;
        }

        $pending_seats = etn_safe_decode(get_post_meta($event->id, 'pending_seats', true));
        if (! is_array($pending_seats)) {
            $pending_seats = [];
        }

        $pending = array_diff($pending_seats, $order_seats);
        
        update_post_meta($event->id, 'pending_seats', $pending);
    }

    /**
     * Send attendee ticket after creating a attendee
     *
     * @param   Attendee_Model  $attendee  [$attendee description]
     *
     * @return  void             [return description]
     */
    public function send_attendee_ticket( $attendee ) {
        $purchase_email = etn_get_email_settings( 'purchase_email' );
        if(is_array($purchase_email) && array_key_exists( 'send_email_to_attendees', $purchase_email ) ){
            // If the setting exists, use it
           $send_email_to_attendees = $purchase_email['send_email_to_attendees'];
        }
        else{
            $send_email_to_attendees = true;
        }

        if ( !$send_email_to_attendees ) {
            return;
        }

        if ( $attendee->etn_email ) {
            $from  = etn_get_email_settings( 'purchase_email' )['from'];
            $event = new Event_Model( $attendee->etn_event_id );
            Mail::to($attendee->etn_email)->from( $from )->send(new AttendeeOrderEmail($event, $attendee));
        }
    }

    /**
     * Update event ticket quantity after attendee create
     *
     * @return  void
     */
    public function decrease_ticket_after_attendee_create( $attendee ) {
        $event = new Event_Model( $attendee->etn_event_id );

        $event_tickets = $event->etn_ticket_variations;

        $event_id = $attendee->etn_event_id ?? null;
        $sold_tickets = !empty($event_id) ? (array)Helper::etn_get_sold_tickets_by_event($event_id) : [];

        if ( $event_tickets ) {
            foreach( $event_tickets as &$ticket ) {
                if ( $ticket['etn_ticket_name'] === $attendee->ticket_name ) {
                    $ticket['etn_sold_tickets'] = $sold_tickets[$ticket['etn_ticket_slug']] ?? 0;
                }
            }
        }

        $event->update([
            'etn_ticket_variations' => $event_tickets,
            'etn_total_sold_tickets' => (int) $event->etn_total_sold_tickets + 1
        ]);
    }

    /**
     * Decrese event ticket variation amount after refunded
     *
     * @param   OrderModel  $order  The order need to refund
     *
     * @return  void
     */
    public function decrese_event_sold_ticket_after_refund( OrderModel $order ) {
        if ( 'refunded' != $order->status ) {
            return;
        }

        $event = new Event_Model( $order->event_id );

        $booked_tickets = $order->tickets;
        $formatted_booked_tickets = [];
        if ( !empty($booked_tickets) ) {
            foreach ($booked_tickets as $ticket) {
                $formatted_booked_tickets[] = [
                    'ticket_slug' => $ticket['ticket_slug'],
                    'ticket_quantity' => $ticket['ticket_quantity']
                ];
            }
        }

        \Etn\Utils\Helper::decrease_count_by_ticket_slug($formatted_booked_tickets,$event->id);



        $event_tickets = $event->etn_ticket_variations;

        $event_id = $order->event_id ?? null;
        $sold_tickets = !empty($event_id) ? (array)Helper::etn_get_sold_tickets_by_event($event_id) : [];

        if ( $event_tickets ) {
            foreach( $event_tickets as &$ticket ) {
                $ticket_amount = $order->get_total_ticket_by_ticket( $ticket['etn_ticket_slug'] );
                if ( $ticket_amount > 0 ) {
                    $ticket['etn_sold_tickets'] = $sold_tickets[$ticket['etn_ticket_slug']] ?? 0;
                }
            }
        }

        $event->update([
            'etn_ticket_variations' => $event_tickets,
        ]);

        // Update seat on refunded.
        $event_seats = get_post_meta( $event->id, '_etn_seat_unique_id', true );
        $order_seats = $order->seat_ids;

        if ( $order_seats ) {
            $event_seats = explode(',', $event_seats );

            $event_seats = array_diff( $event_seats, $order_seats );
            $event_seats = implode( ',', array_unique( $event_seats ) );

            update_post_meta( $event->id, '_etn_seat_unique_id', $event_seats );
        }
    }

    /**
     * Decrese event ticket variation amount after order status failed
     *
     * @param   OrderModel  $order  The order need to update
     *
     * @return  void
     */
    public function order_status_failed( $order ) {
        if ( 'failed' != $order->status ) {
            return;
        }

        $event = new Event_Model( $order->event_id );

        $event_tickets = $event->etn_ticket_variations;


        $event_id = $order->event_id ?? null;
        $sold_tickets = !empty($event_id) ? (array)Helper::etn_get_sold_tickets_by_event($event_id) : [];

        if ( $event_tickets ) {
            foreach( $event_tickets as &$ticket ) {
                $ticket_amount = $order->get_total_ticket_by_ticket( $ticket['etn_ticket_slug'] );
                if ( $ticket_amount > 0 ) {
                    $ticket['etn_sold_tickets'] = $sold_tickets[$ticket['etn_ticket_slug']] ?? 0;
                }
            }
        }

        $event->update([
            'etn_ticket_variations' => $event_tickets,
        ]);

        // Update seat on refunded.
        $event_seats = get_post_meta( $event->id, '_etn_seat_unique_id', true );
        $order_seats = etn_safe_decode(get_post_meta( $order->id, 'seat_ids', true ));

        if ( $order_seats ) {
            $event_seats = explode(',', $event_seats );

            $event_seats = array_diff( $event_seats, $order_seats );
            $event_seats = implode( ',', array_unique( $event_seats ) );

            update_post_meta( $event->id, '_etn_seat_unique_id', $event_seats );
        }
    }

    /**
     * Decrese event ticket variation amount after order deleted
     *
     * @param   OrderModel  $order  The order need to delete
     *
     * @return  void
     */
    public function decrese_event_sold_ticket_after_order_delete( OrderModel $order ) {
        if ( $order->status !== 'completed' ) {
            return;
        }
        $event = new Event_Model( $order->event_id );

        $event_tickets = $event->etn_ticket_variations;

        $event_id = $order->event_id ?? null;
        $sold_tickets = !empty($event_id) ? (array)Helper::etn_get_sold_tickets_by_event($event_id) : [];

        if ( $event_tickets ) {
            foreach( $event_tickets as &$ticket ) {
                $ticket_amount = $order->get_total_ticket_by_ticket( $ticket['etn_ticket_slug'] );
                if ( $ticket_amount > 0 ) {
                    $ticket['etn_sold_tickets'] = $sold_tickets[$ticket['etn_ticket_slug']] ?? 0;
                }
            }
        }

        $event->update([
            'etn_ticket_variations' => $event_tickets,
        ]);

        // Update seat on refunded.
        $event_seats = get_post_meta( $event->id, '_etn_seat_unique_id', true );
        $order_seats = $order->seat_ids;

        if ( $order_seats ) {
            $event_seats = explode(',', $event_seats );

            $event_seats = array_diff( $event_seats, $order_seats );
            $event_seats = implode( ',', array_unique( $event_seats ) );

            update_post_meta( $event->id, '_etn_seat_unique_id', $event_seats );
        }
    }

    /**
     * Decrese event sold ticket after attendee delete
     *
     * @param   Attendee_Model  $attendee
     *
     * @return  void
     */
    public function decrese_event_sold_ticket_after_attendee_delete( $attendee ) {
        if ( $attendee->etn_status != 'success' ) {
            return;
        }

        $event = new Event_Model( $attendee->etn_event_id );
        $order = new OrderModel( $attendee->eventin_order_id );

        // Decrease sold ticket quantity from event
        $event_tickets = $event->etn_ticket_variations;


        $event_id = $attendee->etn_event_id ?? null;
        $sold_tickets = !empty($event_id) ? (array)Helper::etn_get_sold_tickets_by_event($event_id) : [];

        if ( $event_tickets ) {
            foreach( $event_tickets as &$ticket ) {
                if ( $ticket['etn_ticket_name'] == $attendee->ticket_name ) {
                    $ticket['etn_sold_tickets'] = $sold_tickets[$ticket['etn_ticket_slug']] ?? 0;
                }
            }
        }

        $event->update([
            'etn_ticket_variations' => $event_tickets,
        ]);

        // Decrease sold ticket quantity from order
        $order_tickets = $order->tickets;
        $updated_tickets = [];
        if ( $order_tickets ) {
            foreach( $order_tickets as $ticket ) {
                
                $ticket_slug = $event->get_ticket_slug_by_name( $attendee->ticket_name );
                if ( $ticket['ticket_slug'] === $ticket_slug ) {
                    $ticket['ticket_quantity'] = $ticket['ticket_quantity'] - 1;
                }

                if ( $ticket['ticket_quantity'] > 0 ) {
                    $updated_tickets[] = $ticket;
                }
            }
        }
        
        // Decrease ticket quantity from order
        $order->update([
            'tickets'     => $updated_tickets,
            'total_price' => floatval($order->total_price) - floatval($attendee->etn_ticket_price),
        ]);
    }

    /**
     * Release held tickets after order status changed to pending
     *
     * @param   integer  $order_id  The order ID
     * 
     * @return  void
     */
    public function release_held_tickets( $order_id ) {
        $order = new OrderModel( $order_id );

        if ( 'pending' !== $order->status ) {
            return;
        }

        $order->update([
            'status' => 'failed'
        ]);

        // Update order attendees status
        $attendees = $order->get_attendees();
        if ( $attendees ) {
            foreach( $attendees as $attendee ) {
                $attendee = new Attendee_Model( $attendee['id'] );
                $attendee->update([
                    'etn_status' => 'failed'
                ]);
            }
        }
    }

    /**
     * Release held seats and tickets
     *
     * @param   integer  $event_id  The event ID
     *
     * @param   array    $seat_ids  The seat IDs to release
     *
     * @param   array    $booked_tickets  The booked tickets to release
     *
     * @return  void
     */
    public function release_held_seats_and_tickets( $event_id, $hold_or_seats = [], $booked_tickets = [] ) {
        // Since 4.1.23 the job carries the hold id, so it releases exactly the one
        // hold it was scheduled for and can safely run twice.
        if ( is_string( $hold_or_seats ) && '' !== $hold_or_seats ) {
            TicketHold::remove( $event_id, $hold_or_seats );

            return;
        }

        // Jobs queued by 4.1.22 and older still carry [ $event_id, $seat_ids,
        // $booked_tickets ]. Release the matching hold if one was recorded, then
        // sync — which alone clears the counters those versions left behind.
        if ( ! TicketHold::remove_matching( $event_id, $booked_tickets, $hold_or_seats ) ) {
            TicketHold::sync( $event_id );
        }
    }

    /**
     * Clear hold tickets cron
     *
     * @param   OrderModel  $order  The order need to clear hold tickets
     *
     * @return  void
     */
    // public function clear_hold_tickets_cron( $order ) {
    //     wp_clear_scheduled_hook( 'eventin_release_held_tickets', [ $order->id ] );
    // }

    /**
     * @deprecated 4.1.23 Holds are released through TicketHold, which is keyed by
     *             hold id. Clearing by argument list deleted other visitors' jobs
     *             too, because their arguments were identical.
     *
     * @param array $data Legacy [ $event_id, $seat_ids, $booked_tickets ] arguments.
     *
     * @return void
     */
    public function clear_hold_seats_and_tickets_cron( $data ) {
        wp_clear_scheduled_hook( 'eventin_release_held_seats_and_tickets', $data );
    }
}