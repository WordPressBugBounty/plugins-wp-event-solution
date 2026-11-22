<?php
/**
 * Automation for the waiting-list request and refund emails.
 *
 * These three emails were always sent straight through wp_mail(), even when the
 * Automation module was on, so an admin could not change their text in the
 * Automation screen. This class gives each one its own trigger:
 *
 *  - `waiting_list_request`  → "You're on the waiting list" (customer + attendees)
 *  - `order_refund`          → the order is now fully refunded (customer)
 *  - `order_partial_refund`  → part of the order was refunded (customer)
 *
 * Routing rule, kept in one place ( should_use() ):
 *  - Automation module ON and a flow exists for the trigger → automation sends it.
 *  - Otherwise → the caller sends the old direct email, exactly as before.
 *
 * "A flow exists" is checked on purpose. Existing sites get the default flows
 * on the next admin page load ( ImportAutomation::create_order_notice_flows() ).
 * A customer who joins a waiting list before that would otherwise get no email
 * at all. A flow the admin set to draft still counts as existing: that is how an
 * admin turns the email off, the same as for the purchase emails.
 *
 * @package Eventin
 */

namespace Eventin\Emails;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Attendee\Attendee_Model;
use Etn\Core\Event\Event_Model;
use Eventin\Order\OrderModel;

/**
 * OrderNoticeAutomation class
 */
class OrderNoticeAutomation {

    const WAITING_LIST_TRIGGER   = 'waiting_list_request';
    const REFUND_TRIGGER         = 'order_refund';
    const PARTIAL_REFUND_TRIGGER = 'order_partial_refund';
    const PAYMENT_LINK_TRIGGER   = 'waiting_list_payment_link';

    /**
     * Prefix the notification SDK is registered under (see eventin.php `general_prefix`).
     */
    const PREFIX = 'eve';

    /**
     * Should this email go through automation?
     *
     * @param string $trigger
     *
     * @return bool
     */
    public static function should_use( $trigger ) {
        return ReminderAutomation::is_module_on() && self::has_flow( $trigger );
    }

    /**
     * Is there a flow (any status except trash) with an email step for this trigger?
     *
     * A flow with only WhatsApp steps does not count: the WhatsApp draft made for
     * every trigger must not stop the old direct email on a site that deleted
     * its email flow.
     *
     * @param string $trigger
     *
     * @return bool
     */
    public static function has_flow( $trigger ) {
        $flows = get_posts( [
            'post_type'      => self::PREFIX . '-flow',
            'post_status'    => [ 'publish', 'draft', 'pending', 'private', 'future' ],
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'meta_key'       => '_' . self::PREFIX . '_notification_flow_trigger', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
            'meta_value'     => $trigger,                                          // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
        ] );

        foreach ( $flows as $flow_id ) {
            $config = get_post_meta( $flow_id, '_' . self::PREFIX . '_notification_flow_flow_config', true );
            $nodes  = is_array( $config ) && isset( $config['nodes'] ) && is_array( $config['nodes'] ) ? $config['nodes'] : [];

            foreach ( $nodes as $node ) {
                if ( is_array( $node ) && 'email' === ( $node['name'] ?? '' ) ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * The refund trigger for an order status after a refund.
     *
     * @param string $order_status `refunded` or `partially_refunded`.
     *
     * @return string
     */
    public static function refund_trigger( $order_status ) {
        return 'refunded' === $order_status ? self::REFUND_TRIGGER : self::PARTIAL_REFUND_TRIGGER;
    }

    /**
     * Send the waiting-list request email through automation.
     *
     * Attendees whose email is the same as the customer's are left out, so a
     * buyer booking for themselves gets one email — same as the direct path.
     *
     * @param OrderModel $order
     *
     * @return void
     */
    public static function dispatch_waiting_list( $order ) {
        $event = new Event_Model( $order->event_id );

        $customer_email  = (string) $order->customer_email;
        $sent            = $customer_email ? [ strtolower( $customer_email ) ] : [];
        $attendee_ids    = [];
        $attendee_emails = [];
        // WhatsApp "Attendee Number": same index as attendee_email. The SDK skips empty ones.
        $attendee_numbers = [];

        foreach ( (array) $order->get_attendees() as $attendee ) {
            $attendee = new Attendee_Model( $attendee['id'] );
            $email    = (string) $attendee->etn_email;

            if ( ! $email || in_array( strtolower( $email ), $sent, true ) ) {
                continue;
            }

            // Same index in both lists: EnsHooks loads attendee_id[$count]
            // for the email sent to attendee_email[$count].
            $attendee_ids[]     = (int) $attendee->id;
            $attendee_emails[]  = $email;
            $attendee_numbers[] = (string) $attendee->etn_phone;
            $sent[]             = strtolower( $email );
        }

        do_action( 'global_notification_hook', self::WAITING_LIST_TRIGGER, array_merge(
            self::event_payload( $event ),
            [
                'order_id'       => $order->id,
                'customer_name'  => self::customer_name( $order ),
                'customer_email' => $customer_email,
                'customer_number' => (string) $order->customer_phone,
                'attendee_id'    => $attendee_ids,
                'attendee_email' => $attendee_emails,
                'attendee_number' => $attendee_numbers,
                'session_id'     => uniqid(),
            ]
        ) );
    }

    /**
     * Send the refund email through automation.
     *
     * @param OrderModel $order
     * @param array      $refund       Refund row from RefundModel::create().
     * @param string     $order_status Order status after the refund.
     *
     * @return void
     */
    public static function dispatch_refund( $order, $refund, $order_status ) {
        $event = new Event_Model( $order->event_id );

        do_action( 'global_notification_hook', self::refund_trigger( $order_status ), array_merge(
            self::event_payload( $event ),
            [
                'order_id'       => $order->id,
                'refund_id'      => (int) $refund['id'],
                'refund_amount'  => self::refund_amount( $refund ),
                'refund_reason'  => (string) ( $refund['reason'] ?? '' ),
                'customer_name'  => self::customer_name( $order ),
                'customer_email' => (string) $order->customer_email,
                'customer_number' => (string) $order->customer_phone,
                'session_id'     => uniqid(),
            ]
        ) );
    }

    /**
     * Refund amount as the email shows it, e.g. "USD 25.00".
     *
     * @param array $refund
     *
     * @return string
     */
    /**
     * Send the waiting-list "Send Payment Link" email through Automation.
     *
     * @param OrderModel $order       Waiting-list order.
     * @param string     $payment_url Signed page where the customer pays.
     *
     * @return void
     */
    public static function dispatch_payment_link( $order, $payment_url ) {
        $event = new Event_Model( $order->event_id );

        do_action( 'global_notification_hook', self::PAYMENT_LINK_TRIGGER, array_merge(
            self::event_payload( $event ),
            [
                'order_id'        => $order->id,
                'customer_name'   => self::customer_name( $order ),
                'customer_email'  => (string) $order->customer_email,
                'customer_number' => (string) $order->customer_phone,
                'payment_link'    => esc_url_raw( $payment_url ),
                'session_id'      => uniqid(),
            ]
        ) );
    }

    public static function refund_amount( $refund ) {
        return $refund['currency'] . ' ' . number_format( (float) $refund['amount'], 2 );
    }

    /**
     * Customer full name, or the email when there is no name.
     *
     * @param OrderModel $order
     *
     * @return string
     */
    public static function customer_name( $order ) {
        $name = trim( (string) ( $order->customer_fname . ' ' . $order->customer_lname ) );

        return '' !== $name ? $name : (string) $order->customer_email;
    }

    /**
     * Site and event values shared by all three triggers.
     *
     * @param Event_Model $event
     *
     * @return array
     */
    private static function event_payload( $event ) {
        return [
            'site_name'      => get_bloginfo( 'name' ),
            'site_link'      => get_site_url(),
            'event_id'       => $event->id,
            'post_id'        => $event->id,
            'event_title'    => $event->get_title(),
            'event_date'     => $event->get_start_date( get_option( 'date_format' ) ),
            'event_time'     => $event->get_start_time( etn_time_format() ),
            'event_location' => $event->get_address(),
        ];
    }
}
