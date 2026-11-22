<?php
/**
 * Variables for Automation emails and WhatsApp templates.
 *
 * The flow builder shows one list of variables per trigger, for both the
 * email body and the WhatsApp template blanks. This class is the one place
 * that builds those values, at send time, from the ids in the hook payload:
 * `event_id`, `order_id` and `attendee_id[ $count ]`.
 *
 * Keys the payload already carries as plain text (site_name, event_title,
 * event_date …) are left to the SDK, so old emails keep the exact text they
 * had. This class fills only the keys the payload does not have as text.
 *
 * @package Eventin
 */

namespace Eventin\Emails;

use Etn\Core\Attendee\Attendee_Model;
use Etn\Core\Event\Event_Model;
use Eventin\Order\OrderModel;

defined( 'ABSPATH' ) || exit;

/**
 * Automation placeholders
 */
class AutomationPlaceholders {

    /**
     * Meta's limit for one template variable.
     */
    const WHATSAPP_MAX_LENGTH = 1024;

    /**
     * Triggers whose attendees are found at send time.
     *
     * @var string[]
     */
    const EVENT_LEVEL_TRIGGERS = WhatsappRecipients::EVENT_LEVEL_TRIGGERS;

    /**
     * Receiver keys that point at one attendee.
     *
     * @var string[]
     */
    const ATTENDEE_RECEIVERS = [ 'attendee_email', 'attendee_number' ];

    /**
     * Keys that hold a web address.
     *
     * @var string[]
     */
    const LINK_KEYS = [ 'event_link', 'meeting_link', 'ticket_link', 'edit_info_link', 'certificate_link', 'payment_link' ];

    /**
     * Variable labels, by key.
     *
     * @return  array
     */
    public static function labels() {
        return [
            'site_name'        => __( 'Site Name', 'wp-event-solution' ),
            'site_link'        => __( 'Site Link', 'wp-event-solution' ),
            'site_logo'        => __( 'Site Logo', 'wp-event-solution' ),
            'event_title'      => __( 'Event Title', 'wp-event-solution' ),
            'event_link'       => __( 'Event Link', 'wp-event-solution' ),
            'event_date'       => __( 'Event Date', 'wp-event-solution' ),
            'event_time'       => __( 'Event Time', 'wp-event-solution' ),
            'event_start_date' => __( 'Event Start Date', 'wp-event-solution' ),
            'event_start_time' => __( 'Event Start Time', 'wp-event-solution' ),
            'event_end_date'   => __( 'Event End Date', 'wp-event-solution' ),
            'event_end_time'   => __( 'Event End Time', 'wp-event-solution' ),
            'event_timezone'   => __( 'Event Timezone', 'wp-event-solution' ),
            'event_location'   => __( 'Event Location', 'wp-event-solution' ),
            'event_type'       => __( 'Event Type', 'wp-event-solution' ),
            'meeting_link'     => __( 'Meeting Link', 'wp-event-solution' ),
            'organizer_name'   => __( 'Organizer Name', 'wp-event-solution' ),
            'host_name'        => __( 'Host Name', 'wp-event-solution' ),
            'attendee_name'    => __( 'Attendee Name', 'wp-event-solution' ),
            'attendee_email'   => __( 'Attendee Email', 'wp-event-solution' ),
            'attendee_phone'   => __( 'Attendee Phone', 'wp-event-solution' ),
            'ticket_name'      => __( 'Ticket Name', 'wp-event-solution' ),
            'ticket_id'        => __( 'Ticket ID', 'wp-event-solution' ),
            'ticket_price'     => __( 'Ticket Price', 'wp-event-solution' ),
            'seat_number'      => __( 'Seat Number', 'wp-event-solution' ),
            'ticket_link'      => __( 'Ticket Link', 'wp-event-solution' ),
            'edit_info_link'   => __( 'Edit Information Link', 'wp-event-solution' ),
            'certificate_link' => __( 'Certificate Link', 'wp-event-solution' ),
            'order_id'         => __( 'Order ID', 'wp-event-solution' ),
            'order_date'       => __( 'Order Date', 'wp-event-solution' ),
            'order_status'     => __( 'Order Status', 'wp-event-solution' ),
            'payment_method'   => __( 'Payment Method', 'wp-event-solution' ),
            'customer_name'    => __( 'Customer Name', 'wp-event-solution' ),
            'customer_email'   => __( 'Customer Email', 'wp-event-solution' ),
            'customer_phone'   => __( 'Customer Phone', 'wp-event-solution' ),
            'ticket_summary'   => __( 'Ticket Summary', 'wp-event-solution' ),
            'ticket_quantity'  => __( 'Ticket Quantity', 'wp-event-solution' ),
            'attendee_names'   => __( 'Attendee Names', 'wp-event-solution' ),
            'order_subtotal'   => __( 'Order Subtotal', 'wp-event-solution' ),
            'order_discount'   => __( 'Order Discount', 'wp-event-solution' ),
            'coupon_code'      => __( 'Coupon Code', 'wp-event-solution' ),
            'order_tax'        => __( 'Order Tax', 'wp-event-solution' ),
            'order_total'      => __( 'Order Total', 'wp-event-solution' ),
            'recipient_name'   => __( 'Recipient Name', 'wp-event-solution' ),
            'refund_amount'    => __( 'Refund Amount', 'wp-event-solution' ),
            'refund_reason'    => __( 'Refund Reason', 'wp-event-solution' ),
            'payment_link'     => __( 'Payment Link', 'wp-event-solution' ),
        ];
    }

    /**
     * Variable keys each trigger can fill, in builder order.
     *
     * @param   string  $trigger  Trigger slug.
     *
     * @return  string[]
     */
    public static function keys_for_trigger( $trigger ) {
        $site     = [ 'site_name', 'site_link' ];
        $event    = [ 'event_title', 'event_link', 'event_date', 'event_time', 'event_start_date', 'event_start_time', 'event_end_date', 'event_end_time', 'event_timezone', 'event_location', 'event_type', 'meeting_link', 'organizer_name' ];
        $attendee = [ 'attendee_name', 'attendee_email', 'attendee_phone', 'ticket_name', 'ticket_id', 'ticket_price', 'seat_number', 'ticket_link', 'edit_info_link' ];
        $order    = [ 'order_id', 'order_date', 'order_status', 'payment_method', 'customer_name', 'customer_email', 'customer_phone', 'ticket_summary', 'ticket_quantity', 'attendee_names', 'order_subtotal', 'order_discount', 'coupon_code', 'order_tax', 'order_total' ];

        switch ( $trigger ) {
            case 'event_ticket_purchase':
                return array_merge( $site, [ 'site_logo' ], $event, $attendee, $order );

            case 'event_rsvp_email':
                return array_merge( $site, $event, [ 'attendee_name', 'attendee_email', 'attendee_phone', 'host_name' ] );

            case 'send_certificate':
                return array_merge( $site, $event, $attendee, [ 'certificate_link' ], $order );

            case 'event_reminder_email':
            case 'send_email_to_all_attendees':
            case ReminderAutomation::TRIGGER:
                return array_merge( $site, $event, $attendee, $order );

            case OrderNoticeAutomation::WAITING_LIST_TRIGGER:
                return array_merge( $site, $event, [ 'recipient_name' ], $attendee, $order );

            case OrderNoticeAutomation::PAYMENT_LINK_TRIGGER:
                return array_merge( $site, $event, $order, [ 'payment_link' ] );

            case OrderNoticeAutomation::REFUND_TRIGGER:
            case OrderNoticeAutomation::PARTIAL_REFUND_TRIGGER:
                return array_merge( $site, $event, $order, [ 'refund_amount', 'refund_reason' ] );
        }

        return [];
    }

    /**
     * Builder `trigger_data` list for a trigger.
     *
     * @param   string  $trigger  Trigger slug.
     *
     * @return  array
     */
    public static function trigger_data( $trigger ) {
        $labels = self::labels();
        $data   = [];

        foreach ( self::keys_for_trigger( $trigger ) as $key ) {
            $data[] = [
                'label' => $labels[ $key ] ?? $key,
                'value' => $key,
                'type'  => 'event_date' === $key ? 'date' : 'string',
            ];
        }

        return $data;
    }

    /**
     * Plain-text values for one recipient.
     *
     * Only keys the payload does not already carry as text. Empty values are
     * kept as '' so the caller decides how to show them.
     *
     * @param   string  $action_name    Trigger slug.
     * @param   string  $receiver_type  Receiver key, e.g. attendee_email.
     * @param   array   $action_data    Hook payload.
     * @param   int     $count          Recipient position in a list receiver.
     *
     * @return  array   key => string
     */
    public static function values( $action_name, $receiver_type, $action_data, $count = 0 ) {
        $action_data = is_array( $action_data ) ? $action_data : [];
        $allowed     = self::keys_for_trigger( $action_name );

        if ( ! $allowed ) {
            return [];
        }

        $event_id = absint( $action_data['event_id'] ?? 0 );
        $event    = $event_id && get_post( $event_id ) ? new Event_Model( $event_id ) : null;

        $attendee_id = 0;
        if ( in_array( $receiver_type, self::ATTENDEE_RECEIVERS, true ) ) {
            $attendee_id = self::attendee_id( $action_name, $action_data, (int) $count );
        }
        $attendee = $attendee_id ? new Attendee_Model( $attendee_id ) : null;

        $order_id = absint( $action_data['order_id'] ?? 0 );
        if ( ! $order_id && $attendee ) {
            $order_id = absint( $attendee->eventin_order_id );
        }
        $order = $order_id && 'etn-order' === get_post_type( $order_id ) ? new OrderModel( $order_id ) : null;

        $values = array_merge(
            self::event_values( $event ),
            self::attendee_values( $attendee, $event, $order ),
            self::order_values( $order )
        );

        $values['site_name'] = get_bloginfo( 'name' );
        $values['site_link'] = get_site_url();

        // Waiting list: the attendee's name, or the buyer's for the customer copy.
        $values['recipient_name'] = '' !== (string) ( $values['attendee_name'] ?? '' )
            ? $values['attendee_name']
            : (string) ( $values['customer_name'] ?? ( $action_data['customer_name'] ?? '' ) );

        $result = [];
        foreach ( $allowed as $key ) {
            // The payload's own text wins, so emails keep what they showed before.
            if ( isset( $action_data[ $key ] ) && is_scalar( $action_data[ $key ] ) && '' !== (string) $action_data[ $key ] ) {
                continue;
            }

            if ( array_key_exists( $key, $values ) ) {
                $result[ $key ] = (string) $values[ $key ];
            }
        }

        /**
         * Filter Automation variable values for one recipient.
         *
         * @param array  $result         key => plain text.
         * @param string $action_name    Trigger slug.
         * @param string $receiver_type  Receiver key.
         * @param array  $action_data    Hook payload.
         * @param int    $count          Recipient position.
         */
        return apply_filters( 'eventin_automation_placeholder_values', $result, $action_name, $receiver_type, $action_data, $count );
    }

    /**
     * Make a value safe for a WhatsApp template blank.
     *
     * Meta rejects an empty blank, new lines, tabs and more than 4 spaces in
     * a row, so the value becomes one plain line.
     *
     * @param   mixed  $value  Value.
     *
     * @return  string
     */
    public static function whatsapp_text( $value ) {
        $value = is_scalar( $value ) ? (string) $value : '';
        $value = html_entity_decode( wp_strip_all_tags( $value ), ENT_QUOTES, 'UTF-8' );
        $value = trim( preg_replace( '/\s+/u', ' ', $value ) );

        if ( '' === $value ) {
            return self::empty_text();
        }

        if ( function_exists( 'mb_substr' ) ) {
            return mb_substr( $value, 0, self::WHATSAPP_MAX_LENGTH );
        }

        return substr( $value, 0, self::WHATSAPP_MAX_LENGTH );
    }

    /**
     * Text shown for a value that is empty.
     *
     * @return  string
     */
    public static function empty_text() {
        return (string) apply_filters( 'eventin_automation_empty_value', __( 'N/A', 'wp-event-solution' ) );
    }

    /**
     * Attendee id for a recipient, or 0 when the receiver is not one attendee.
     *
     * @param   string  $action_name    Trigger slug.
     * @param   string  $receiver_type  Receiver key.
     * @param   array   $action_data    Hook payload.
     * @param   int     $count          Recipient position.
     *
     * @return  int
     */
    public static function recipient_attendee_id( $action_name, $receiver_type, $action_data, $count ) {
        if ( ! in_array( $receiver_type, self::ATTENDEE_RECEIVERS, true ) ) {
            return 0;
        }

        return self::attendee_id( $action_name, is_array( $action_data ) ? $action_data : [], (int) $count );
    }

    /**
     * Attendee id for this recipient.
     *
     * @param   string  $action_name  Trigger slug.
     * @param   array   $action_data  Hook payload.
     * @param   int     $count        Recipient position.
     *
     * @return  int
     */
    private static function attendee_id( $action_name, $action_data, $count ) {
        $ids = isset( $action_data['attendee_id'] ) ? (array) $action_data['attendee_id'] : [];

        // Event-level triggers find attendees at send time, with the same query
        // as the recipient list, so $count points at the same person.
        if ( ! $ids && in_array( $action_name, self::EVENT_LEVEL_TRIGGERS, true ) && ! empty( $action_data['event_id'] ) ) {
            $ids = self::event_attendee_ids( $action_data['event_id'] );
        }

        $ids = array_values( $ids );

        return absint( $ids[ $count ] ?? 0 );
    }

    /**
     * Successful attendees of an event, in recipient order.
     *
     * Same query as EnsHooks::get_to_attendee_ids() and
     * WhatsappRecipients::resolve_numbers().
     *
     * @param   int  $event_id  Event id.
     *
     * @return  int[]
     */
    public static function event_attendee_ids( $event_id ) {
        static $cache = [];

        // One flow run sends to every attendee; look the list up once.
        if ( isset( $cache[ $event_id ] ) ) {
            return $cache[ $event_id ];
        }

        return $cache[ $event_id ] = get_posts( [
            'post_type'      => 'etn-attendee',
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                [
                    'key'     => 'etn_event_id',
                    'value'   => $event_id,
                    'compare' => '=',
                ],
                [
                    'key'     => 'etn_status',
                    'value'   => 'success',
                    'compare' => '=',
                ],
            ],
        ] );
    }

    /**
     * Event values.
     *
     * @param   Event_Model|null  $event  Event.
     *
     * @return  array
     */
    private static function event_values( $event ) {
        if ( ! $event ) {
            return [];
        }

        $date_format = get_option( 'date_format' );
        $time_format = etn_time_format();

        $types = [
            'online'  => __( 'Online', 'wp-event-solution' ),
            'offline' => __( 'Offline', 'wp-event-solution' ),
            'hybrid'  => __( 'Hybrid', 'wp-event-solution' ),
        ];
        $type = (string) $event->event_type;

        $organizers = [];
        foreach ( $event->get_organizers() as $organizer ) {
            $user = get_userdata( (int) $organizer->get_id() );
            if ( $user ) {
                $organizers[] = $user->display_name;
            }
        }

        // Offline events can still hold an old meeting link; never send it.
        $meeting_link = in_array( $type, [ 'online', 'hybrid' ], true ) ? (string) $event->meeting_link : '';

        return [
            'event_title'      => $event->get_title(),
            'event_link'       => (string) get_permalink( $event->id ),
            'event_date'       => $event->get_start_date( $date_format ),
            'event_time'       => $event->get_start_time( $time_format ),
            'event_start_date' => $event->get_start_date( $date_format ),
            'event_start_time' => $event->get_start_time( $time_format ),
            'event_end_date'   => $event->get_end_date( $date_format ),
            'event_end_time'   => $event->get_end_time( $time_format ),
            'event_timezone'   => (string) $event->get_timezone(),
            'event_location'   => $event->get_location_label(),
            'event_type'       => $types[ $type ] ?? '',
            'meeting_link'     => $meeting_link,
            'organizer_name'   => implode( ', ', $organizers ),
        ];
    }

    /**
     * Attendee values.
     *
     * @param   Attendee_Model|null  $attendee  Attendee.
     * @param   Event_Model|null     $event     Event.
     * @param   OrderModel|null      $order     Order.
     *
     * @return  array
     */
    private static function attendee_values( $attendee, $event, $order ) {
        if ( ! $attendee ) {
            return [];
        }

        // Post_Model has no __isset, so read magic props into locals first.
        $id       = (int) $attendee->id;
        $token    = (string) $attendee->etn_info_edit_token;
        $event_id = $event ? (int) $event->id : (int) $attendee->etn_event_id;
        $price    = $attendee->etn_ticket_price;
        $seat     = $attendee->attendee_seat;

        $values = [
            'attendee_name'  => (string) $attendee->etn_name,
            'attendee_email' => (string) $attendee->etn_email,
            'attendee_phone' => (string) $attendee->etn_phone,
            'ticket_name'    => (string) $attendee->ticket_name,
            'ticket_id'      => (string) $attendee->etn_unique_ticket_id,
            'ticket_price'   => is_numeric( $price ) ? self::money( $price, $order ) : '',
            'seat_number'    => is_scalar( $seat ) ? (string) $seat : implode( ', ', array_filter( (array) $seat, 'is_scalar' ) ),
            'ticket_link'    => '',
            'edit_info_link' => '',
            'certificate_link' => '',
        ];

        if ( $token ) {
            $values['ticket_link']      = site_url( "etn-attendee?etn_action=download_ticket&attendee_id={$id}&etn_info_edit_token={$token}" );
            $values['edit_info_link']   = site_url( "etn-attendee?etn_action=edit_information&attendee_id={$id}&etn_info_edit_token={$token}" );
            $values['certificate_link'] = site_url( "etn-attendee?etn_action=download_certificate&attendee_id={$id}&event_id={$event_id}&etn_info_edit_token={$token}" );
        }

        return $values;
    }

    /**
     * Order values. Money follows templates/emails/admin-order-email-template.php.
     *
     * @param   OrderModel|null  $order  Order.
     *
     * @return  array
     */
    private static function order_values( $order ) {
        if ( ! $order ) {
            return [];
        }

        $payment_method = (string) $order->payment_method;
        $tax_total      = (float) $order->tax_total;
        $discount       = (float) $order->discount_total;

        $summary  = [];
        $quantity = 0;
        $subtotal = 0;
        foreach ( $order->get_tickets() as $ticket ) {
            $qty       = (int) $ticket['etn_ticket_qty'];
            $quantity += $qty;
            $subtotal += (float) $ticket['etn_ticket_price'] * $qty;
            $summary[] = sprintf( '%d x %s', $qty, $ticket['etn_ticket_name'] );
        }
        $subtotal += (float) $order->options_total;

        $names = [];
        foreach ( (array) $order->get_attendees() as $attendee ) {
            $name = is_array( $attendee ) ? ( $attendee['etn_name'] ?? '' ) : '';
            if ( '' !== (string) $name ) {
                $names[] = $name;
            }
        }

        // Tax already in total_price ('incl', every native order) is not added
        // again. The discount is only subtracted for WooCommerce, whose
        // total_price stays gross (see memory: total_price has two meanings).
        $discount_to_subtract = 'wc' === $payment_method ? $discount : 0;
        if ( 'incl' === self::tax_display_mode( $order ) && $tax_total > 0 ) {
            $total = (float) $order->total_price - $discount_to_subtract;
        } else {
            $total = (float) $order->total_price - $discount_to_subtract + $tax_total;
        }

        $methods = [
            'wc'            => __( 'WooCommerce', 'wp-event-solution' ),
            'local_payment' => __( 'Local Payment', 'wp-event-solution' ),
            'stripe'        => __( 'Stripe', 'wp-event-solution' ),
            'paypal'        => __( 'PayPal', 'wp-event-solution' ),
        ];

        $status = (string) $order->status;

        return [
            'order_id'        => (string) $order->id,
            'order_date'      => $order->get_datetime( get_option( 'date_format' ) ),
            'order_status'    => ucfirst( str_replace( [ '_', '-' ], ' ', $status ) ),
            'payment_method'  => $methods[ $payment_method ] ?? ucfirst( str_replace( [ '_', '-' ], ' ', $payment_method ) ),
            'customer_name'   => trim( $order->customer_fname . ' ' . $order->customer_lname ),
            'customer_email'  => (string) $order->customer_email,
            'customer_phone'  => (string) $order->customer_phone,
            'ticket_summary'  => implode( ', ', $summary ),
            'ticket_quantity' => (string) $quantity,
            'attendee_names'  => implode( ', ', $names ),
            'order_subtotal'  => self::money( $subtotal, $order ),
            'order_discount'  => $discount > 0 ? self::money( $discount, $order ) : '',
            'coupon_code'     => (string) $order->coupon_code,
            'order_tax'       => $tax_total > 0 ? self::money( $tax_total, $order ) : '',
            'order_total'     => self::money( $total, $order ),
        ];
    }

    /**
     * Whether tax is already in total_price. Same rule as the order email.
     *
     * @param   OrderModel  $order  Order.
     *
     * @return  string  'incl' or 'excl' (or the WooCommerce setting).
     */
    private static function tax_display_mode( $order ) {
        $mode = (string) $order->tax_display_mode;

        if ( 'fluentcart' === $order->payment_method && (float) $order->tax_total > 0 && class_exists( '\\FluentCart\\App\\Modules\\Tax\\TaxModule' ) ) {
            $settings = ( new \FluentCart\App\Modules\Tax\TaxModule() )->getSettings();
            $mode     = ( is_array( $settings ) && 'included' === ( $settings['tax_inclusion'] ?? '' ) ) ? 'incl' : 'excl';
        }

        if ( '' === $mode ) {
            $mode = (string) get_option( 'woocommerce_tax_display_shop' );
        }

        return $mode;
    }

    /**
     * Price with the order's currency symbol.
     *
     * @param   float            $amount  Amount.
     * @param   OrderModel|null  $order   Order.
     *
     * @return  string
     */
    private static function money( $amount, $order ) {
        $text = \Etn\Core\Event\Helper::instance()->currency_with_position( number_format( (float) $amount, 2 ), $order );

        return html_entity_decode( (string) $text, ENT_QUOTES, 'UTF-8' );
    }
}
