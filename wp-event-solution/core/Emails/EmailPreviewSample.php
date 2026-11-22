<?php
/**
 * Render an Automation email with sample data, for the builder preview.
 *
 * A sample order and attendees (and a refund, for refund emails) are created
 * on the preview event, the email is built with the same SDK class and
 * filters a real send uses, and then every row is rolled back. The preview
 * never touches a real customer.
 *
 * @package Eventin
 */

namespace Eventin\Emails;

use Etn\Core\Event\Event_Model;
use Eventin\PreviewPlaceholder\PreviewPlaceholder;
use Eventin\Refund\RefundModel;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Email preview sample
 */
class EmailPreviewSample {

    /**
     * Sample attendees: name, email.
     */
    const PEOPLE = [
        [ 'Rahim Uddin', 'rahim@example.com' ],
        [ 'Sara Khan', 'sara@example.com' ],
        [ 'Tanvir Hasan', 'tanvir@example.com' ],
    ];

    /**
     * Posts created during this preview.
     *
     * @var int[]
     */
    private $created = [];

    /**
     * Render the email.
     *
     * @param   string  $trigger   Trigger slug.
     * @param   string  $receiver  Receiver key, e.g. customer_email.
     * @param   string  $subject   Subject as typed.
     * @param   string  $body      Body as typed.
     * @param   string  $mode      '', 'default' or 'custom'.
     *
     * @return  array|WP_Error  { subject, html }
     */
    public function render( $trigger, $receiver, $subject, $body, $mode ) {
        $event_id = $this->event_id();
        if ( ! $event_id ) {
            return new WP_Error( 'eventin_preview_no_event', __( 'Create an event first to see a preview.', 'wp-event-solution' ), [ 'status' => 422 ] );
        }

        $sender_class = '\Eventin\Vendor\Ens\Email\EmailSender';
        if ( ! class_exists( $sender_class ) ) {
            return new WP_Error( 'eventin_preview_no_automation', __( 'Turn on the Automation module to see a preview.', 'wp-event-solution' ), [ 'status' => 422 ] );
        }

        $this->begin();

        try {
            $payload = $this->payload( $trigger, $receiver, $event_id );

            // The same class, placeholders and filters a real send uses.
            $sender = new $sender_class( $trigger, $receiver, 'preview@example.com', '', $subject, $body, $payload, 0, [ EmailDesign::MODE_KEY => $mode ] );

            $values  = AutomationPlaceholders::values( $trigger, $receiver, $payload, 0 );
            $replace = [];
            foreach ( $values as $key => $value ) {
                $replace[ '{%' . $key . '%}' ] = $value;
            }

            return [
                'subject' => wp_strip_all_tags( strtr( (string) $this->read( $sender, 'subject' ), $replace ) ),
                'html'    => (string) $this->read( $sender, 'message' ),
            ];
        } finally {
            $this->rollback();
        }
    }

    /**
     * Event to hang the sample order on: the hidden preview event, else the newest published event.
     *
     * Never a new event: saving an event posts to BuddyBoss / FluentCommunity feeds.
     *
     * @return  int
     */
    private function event_id() {
        $id = class_exists( PreviewPlaceholder::class ) ? PreviewPlaceholder::event_id() : 0;
        if ( $id ) {
            return $id;
        }

        $ids = get_posts( [
            'post_type'      => 'etn',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ] );

        return $ids ? (int) $ids[0] : 0;
    }

    /**
     * Payload shaped like the real dispatcher's for this trigger.
     *
     * Attendee ids are always given, so event-level triggers never look up the
     * event's real attendees.
     *
     * @param   string  $trigger   Trigger slug.
     * @param   string  $receiver  Receiver key.
     * @param   int     $event_id  Event id.
     *
     * @return  array
     */
    private function payload( $trigger, $receiver, $event_id ) {
        $event    = new Event_Model( $event_id );
        $order_id = $this->create_order( $event );
        $people   = $this->create_attendees( $event, $order_id );

        $payload = [
            'site_name'      => get_bloginfo( 'name' ),
            'site_link'      => get_site_url(),
            'site_logo'      => get_site_icon_url(),
            'event_title'    => $event->get_title(),
            'event_date'     => $event->get_start_date( get_option( 'date_format' ) ),
            'event_time'     => $event->get_start_time( etn_time_format() ),
            'event_location' => $event->get_location_label(),
            'event_id'       => $event_id,
            'post_id'        => $event_id,
            'order_id'       => $order_id,
            'session_id'     => 'preview',
        ];

        switch ( $receiver ) {
            case 'customer_email':
                $payload['customer_email'] = self::PEOPLE[0][1];
                $payload['customer_name']  = self::PEOPLE[0][0];
                break;
            case 'admin_email':
                $payload['admin_email'] = get_option( 'admin_email' );
                break;
            default:
                $payload['attendee_id']    = array_keys( $people );
                $payload['attendee_email'] = array_values( $people );
        }

        if ( 'event_rsvp_email' === $trigger ) {
            unset( $payload['order_id'] );
            $payload['attendee_email'] = self::PEOPLE[0][1];
            $payload['attendee_name']  = self::PEOPLE[0][0];
            $payload['host_name']      = wp_get_current_user()->display_name;
        }

        if ( in_array( $trigger, [ OrderNoticeAutomation::REFUND_TRIGGER, OrderNoticeAutomation::PARTIAL_REFUND_TRIGGER ], true ) ) {
            $refund = ( new RefundModel() )->create( $order_id, [
                'type'         => 'ticket',
                'attendee_ids' => [ array_keys( $people )[0] ],
                'amount'       => 10,
                'currency'     => etn_currency(),
                'reason'       => __( 'Cannot attend', 'wp-event-solution' ),
            ] );

            $payload['refund_id']     = (int) ( $refund['id'] ?? 0 );
            $payload['refund_amount'] = $refund ? OrderNoticeAutomation::refund_amount( $refund ) : '';
            $payload['refund_reason'] = (string) ( $refund['reason'] ?? '' );
            $payload['customer_name'] = self::PEOPLE[0][0];
        }

        if ( OrderNoticeAutomation::PAYMENT_LINK_TRIGGER === $trigger ) {
            $payload['payment_link'] = add_query_arg( [ 'order_id' => $order_id, 'order_token' => 'preview' ], site_url( 'eventin-purchase/preview/' ) );
        }

        if ( OrderNoticeAutomation::WAITING_LIST_TRIGGER === $trigger ) {
            $payload['customer_name'] = self::PEOPLE[0][0];
        }

        return $payload;
    }

    /**
     * Sample order: 2 x first ticket + 1 x second ticket, site currency.
     *
     * @param   Event_Model  $event  Event.
     *
     * @return  int
     */
    private function create_order( $event ) {
        $tickets = array_values( (array) $event->etn_ticket_variations );
        $lines   = [];
        $total   = 0;

        foreach ( array_slice( $tickets, 0, 2 ) as $i => $ticket ) {
            $qty     = 0 === $i ? 2 : 1;
            $lines[] = [ 'ticket_slug' => $ticket['etn_ticket_slug'] ?? '', 'ticket_quantity' => $qty ];
            $total  += (float) ( $ticket['etn_ticket_price'] ?? 0 ) * $qty;
        }

        $order_id = $this->insert( 'etn-order', 'Preview order' );

        foreach ( [
            'event_id'         => $event->id,
            'customer_fname'   => 'Rahim',
            'customer_lname'   => 'Uddin',
            'customer_email'   => self::PEOPLE[0][1],
            'customer_phone'   => '+8801711000000',
            'payment_method'   => 'local_payment',
            'status'           => 'completed',
            'tickets'          => $lines,
            'total_price'      => $total,
            'discount_total'   => 0,
            'tax_total'        => 0,
            'tax_display_mode' => 'incl',
            'options_total'    => 0,
            'currency'         => etn_currency(),
            'currency_symbol'  => \Etn\Core\Event\Helper::instance()->get_currency(),
        ] as $key => $value ) {
            update_post_meta( $order_id, $key, $value );
        }

        return $order_id;
    }

    /**
     * Sample attendees on the order.
     *
     * @param   Event_Model  $event     Event.
     * @param   int          $order_id  Order id.
     *
     * @return  array  attendee id => email
     */
    private function create_attendees( $event, $order_id ) {
        $tickets = array_values( (array) $event->etn_ticket_variations );
        $people  = [];

        foreach ( self::PEOPLE as $i => $person ) {
            $ticket = $tickets[ 2 === $i && isset( $tickets[1] ) ? 1 : 0 ] ?? [];
            $id     = $this->insert( 'etn-attendee', $person[0] );

            foreach ( [
                'etn_name'             => $person[0],
                'etn_email'            => $person[1],
                'etn_phone'            => '',
                'ticket_name'          => $ticket['etn_ticket_name'] ?? '',
                'ticket_slug'          => $ticket['etn_ticket_slug'] ?? '',
                'etn_ticket_price'     => $ticket['etn_ticket_price'] ?? 0,
                'etn_event_id'         => $event->id,
                'eventin_order_id'     => $order_id,
                'etn_status'           => 'success',
                'etn_unique_ticket_id' => 'PREVIEW-' . ( $i + 1 ),
                'etn_info_edit_token'  => 'preview',
            ] as $key => $value ) {
                update_post_meta( $id, $key, $value );
            }

            $people[ $id ] = $person[1];
        }

        return $people;
    }

    /**
     * Insert a sample post and remember it (publish: order emails list only published attendees).
     *
     * @param   string  $type   Post type.
     * @param   string  $title  Title.
     *
     * @return  int
     */
    private function insert( $type, $title ) {
        $id = (int) wp_insert_post( [ 'post_type' => $type, 'post_status' => 'publish', 'post_title' => $title ] );

        $this->created[] = $id;

        return $id;
    }

    /**
     * Start a transaction, or a savepoint when one is already open (autocommit off).
     *
     * @return  void
     */
    private function begin() {
        global $wpdb;

        $this->nested = '0' === (string) $wpdb->get_var( 'SELECT @@autocommit' );
        $wpdb->query( $this->nested ? 'SAVEPOINT etn_email_preview' : 'START TRANSACTION' );
    }

    /**
     * Whether begin() used a savepoint.
     *
     * @var bool
     */
    private $nested = false;

    /**
     * Undo everything, and delete anything a non-transactional table kept.
     *
     * @return  void
     */
    private function rollback() {
        global $wpdb;

        $wpdb->query( $this->nested ? 'ROLLBACK TO SAVEPOINT etn_email_preview' : 'ROLLBACK' );

        foreach ( $this->created as $id ) {
            clean_post_cache( $id );
            if ( $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE ID = %d", $id ) ) ) {
                wp_delete_post( $id, true );
            }
        }

        $this->created = [];
    }

    /**
     * Read a protected property of the SDK sender.
     *
     * @param   object  $sender  Sender.
     * @param   string  $prop    Property.
     *
     * @return  mixed
     */
    private function read( $sender, $prop ) {
        $property = new \ReflectionProperty( $sender, $prop );
        $property->setAccessible( true );

        return $property->getValue( $sender );
    }
}
