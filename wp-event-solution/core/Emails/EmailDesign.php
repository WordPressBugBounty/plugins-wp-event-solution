<?php
/**
 * Default design or Custom body for Automation email steps.
 *
 * Each "Send Email" step can save `emailTemplateMode`:
 *  - missing   → what the email did before this setting existed (old flows),
 *  - `default` → the admin's text plus Eventin's built-in design block,
 *  - `custom`  → only the admin's text, inside the normal header and footer.
 *
 * @package Eventin
 */

namespace Eventin\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Email design
 */
class EmailDesign {

    /**
     * Node data key.
     */
    const MODE_KEY = 'emailTemplateMode';

    /**
     * Triggers whose email adds a built-in design block.
     *
     * @var string[]
     */
    const DESIGNED = [
        'event_ticket_purchase',
        'event_reminder_email',
        'send_reminder_email_to_all_attendees',
        'send_certificate',
        'waiting_list_request',
        'order_refund',
        'order_partial_refund',
        'waiting_list_payment_link',
    ];

    /**
     * Triggers whose email is the admin's text only, with no header or footer.
     *
     * @var string[]
     */
    const PLAIN = [ 'event_rsvp_email', 'send_email_to_all_attendees' ];

    /**
     * Whether the trigger's email has a built-in design block.
     *
     * @param   string  $trigger  Trigger slug.
     *
     * @return  bool
     */
    public static function has_design( $trigger ) {
        return in_array( $trigger, self::DESIGNED, true );
    }

    /**
     * Whether the trigger offers the Default / Custom choice at all.
     *
     * @param   string  $trigger  Trigger slug.
     *
     * @return  bool
     */
    public static function is_known( $trigger ) {
        return self::has_design( $trigger ) || in_array( $trigger, self::PLAIN, true );
    }

    /**
     * The step's saved choice: 'default', 'custom', or '' when not saved.
     *
     * @param   mixed  $node_data  Email step settings.
     *
     * @return  string
     */
    public static function mode( $node_data ) {
        $mode = is_array( $node_data ) ? ( $node_data[ self::MODE_KEY ] ?? '' ) : '';

        return in_array( $mode, [ 'default', 'custom' ], true ) ? $mode : '';
    }

    /**
     * Register hooks
     *
     * @return  void
     */
    public function __construct() {
        // After eventin.php builds the trigger list (10) and WhatsApp receivers (20).
        add_filter( 'ens_eve_available_actions', [ $this, 'add_to_triggers' ], 30 );
    }

    /**
     * Tell the builder which triggers have a design, and give a suggested format per receiver.
     *
     * @param   array  $actions  Trigger configs.
     *
     * @return  array
     */
    public function add_to_triggers( $actions ) {
        $formats = self::formats();

        foreach ( (array) $actions as $index => $action ) {
            if ( ! is_array( $action ) || empty( $action['trigger_value'] ) ) {
                continue;
            }

            $trigger = $action['trigger_value'];

            $actions[ $index ]['email_design']  = self::has_design( $trigger );
            $actions[ $index ]['email_formats'] = $formats[ $trigger ] ?? new \stdClass();
        }

        return $actions;
    }

    /**
     * Suggested subject and body per trigger and receiver.
     *
     * Used when the admin picks Custom: the design block is gone, so the text
     * carries the details the block used to show, as variables.
     *
     * @return  array  trigger => receiver => [ subject, body ]
     */
    public static function formats() {
        $p = function ( ...$lines ) {
            return '<p>' . implode( '</p><p>', $lines ) . '</p>';
        };

        $event_lines = [
            /* translators: keep the {%…%} variables as they are. */
            __( 'Date: {%event_date%} at {%event_time%} ({%event_timezone%})', 'wp-event-solution' ),
            __( 'Place: {%event_location%}', 'wp-event-solution' ),
            __( 'Online link: {%meeting_link%}', 'wp-event-solution' ),
        ];

        $order_lines = [
            __( 'Order: #{%order_id%} ({%order_date%})', 'wp-event-solution' ),
            __( 'Tickets: {%ticket_summary%}', 'wp-event-solution' ),
            __( 'Attendees: {%attendee_names%}', 'wp-event-solution' ),
            __( 'Subtotal: {%order_subtotal%}', 'wp-event-solution' ),
            __( 'Discount: {%order_discount%} {%coupon_code%}', 'wp-event-solution' ),
            __( 'Tax: {%order_tax%}', 'wp-event-solution' ),
            __( 'Total: {%order_total%}', 'wp-event-solution' ),
            __( 'Payment method: {%payment_method%}', 'wp-event-solution' ),
        ];

        $reminder = [
            'subject' => __( 'Reminder: {%event_title%} is coming up', 'wp-event-solution' ),
            'body'    => $p(
                __( 'Hi {%attendee_name%},', 'wp-event-solution' ),
                __( 'This is a reminder about {%event_title%}.', 'wp-event-solution' ),
                ...array_merge( $event_lines, [ __( 'See you there.', 'wp-event-solution' ) ] )
            ),
        ];

        return [
            'event_ticket_purchase'                => [
                'attendee_email' => [
                    'subject' => __( 'Your ticket for {%event_title%}', 'wp-event-solution' ),
                    'body'    => $p(
                        __( 'Hi {%attendee_name%},', 'wp-event-solution' ),
                        __( 'Your ticket for {%event_title%} is confirmed.', 'wp-event-solution' ),
                        __( 'Ticket: {%ticket_name%} ({%ticket_id%})', 'wp-event-solution' ),
                        ...array_merge( $event_lines, [
                            __( 'Download your ticket: {%ticket_link%}', 'wp-event-solution' ),
                            __( 'Change your details: {%edit_info_link%}', 'wp-event-solution' ),
                            __( 'See you there.', 'wp-event-solution' ),
                        ] )
                    ),
                ],
                'customer_email' => [
                    'subject' => __( 'Your order #{%order_id%} for {%event_title%} is confirmed', 'wp-event-solution' ),
                    'body'    => $p(
                        __( 'Hi {%customer_name%},', 'wp-event-solution' ),
                        __( 'Thank you for your order for {%event_title%}.', 'wp-event-solution' ),
                        ...array_merge( $order_lines, $event_lines )
                    ),
                ],
                'admin_email'    => [
                    'subject' => __( 'New booking #{%order_id%} for {%event_title%}', 'wp-event-solution' ),
                    'body'    => $p(
                        __( 'A new booking was made for {%event_title%}.', 'wp-event-solution' ),
                        __( 'Customer: {%customer_name%} ({%customer_email%}, {%customer_phone%})', 'wp-event-solution' ),
                        ...$order_lines
                    ),
                ],
            ],
            'event_rsvp_email'                     => [
                'attendee_email' => [
                    'subject' => __( 'Your RSVP for {%event_title%}', 'wp-event-solution' ),
                    'body'    => $p(
                        __( 'Hi {%attendee_name%},', 'wp-event-solution' ),
                        __( 'Thank you for your RSVP to {%event_title%}.', 'wp-event-solution' ),
                        ...array_merge( $event_lines, [ __( 'Host: {%host_name%}', 'wp-event-solution' ) ] )
                    ),
                ],
                'admin_email'    => [
                    'subject' => __( 'New RSVP for {%event_title%}', 'wp-event-solution' ),
                    'body'    => $p(
                        __( 'A new RSVP was received for {%event_title%}.', 'wp-event-solution' ),
                        __( 'Date: {%event_date%} at {%event_time%}', 'wp-event-solution' )
                    ),
                ],
            ],
            'event_reminder_email'                 => [ 'attendee_email' => $reminder ],
            'send_reminder_email_to_all_attendees' => [ 'attendee_email' => $reminder ],
            'send_email_to_all_attendees'          => [
                'attendee_email' => [
                    'subject' => __( 'An update about {%event_title%}', 'wp-event-solution' ),
                    'body'    => $p(
                        __( 'Hi {%attendee_name%},', 'wp-event-solution' ),
                        __( 'We have an update about {%event_title%} on {%event_date%}.', 'wp-event-solution' ),
                        __( 'Write your message here.', 'wp-event-solution' ),
                        __( 'Event page: {%event_link%}', 'wp-event-solution' )
                    ),
                ],
            ],
            'send_certificate'                     => [
                'attendee_email' => [
                    'subject' => __( 'Your certificate for {%event_title%}', 'wp-event-solution' ),
                    'body'    => $p(
                        __( 'Hi {%attendee_name%},', 'wp-event-solution' ),
                        __( 'Thank you for attending {%event_title%}.', 'wp-event-solution' ),
                        __( 'Download your certificate: {%certificate_link%}', 'wp-event-solution' )
                    ),
                ],
            ],
            'waiting_list_request'                 => array_fill_keys( [ 'customer_email', 'attendee_email' ], [
                'subject' => __( 'You are on the waiting list for {%event_title%}', 'wp-event-solution' ),
                'body'    => $p(
                    __( 'Hi {%recipient_name%},', 'wp-event-solution' ),
                    __( 'You are on the waiting list for {%event_title%}. Your request number is #{%order_id%}.', 'wp-event-solution' ),
                    __( 'Date: {%event_date%} at {%event_time%}', 'wp-event-solution' ),
                    __( 'Place: {%event_location%}', 'wp-event-solution' ),
                    __( 'We will contact you when a seat is free.', 'wp-event-solution' )
                ),
            ] ),
            'order_refund'                         => [
                'customer_email' => [
                    'subject' => __( 'Refund for your order #{%order_id%}', 'wp-event-solution' ),
                    'body'    => $p(
                        __( 'Hi {%customer_name%},', 'wp-event-solution' ),
                        __( 'We have refunded {%refund_amount%} for your order #{%order_id%} ({%event_title%}).', 'wp-event-solution' ),
                        __( 'Reason: {%refund_reason%}', 'wp-event-solution' )
                    ),
                ],
            ],
            'waiting_list_payment_link'            => [
                'customer_email' => [
                    'subject' => __( 'Your spot is ready: complete your payment for {%event_title%}', 'wp-event-solution' ),
                    'body'    => $p(
                        __( 'Hi {%customer_name%},', 'wp-event-solution' ),
                        __( 'Good news: a ticket for {%event_title%} is now available for you.', 'wp-event-solution' ),
                        __( 'Order: #{%order_id%}', 'wp-event-solution' ),
                        __( 'Tickets: {%ticket_summary%}', 'wp-event-solution' ),
                        __( 'Total: {%order_total%}', 'wp-event-solution' ),
                        __( 'Pay here before the spot is taken: {%payment_link%}', 'wp-event-solution' )
                    ),
                ],
            ],
            'order_partial_refund'                 => [
                'customer_email' => [
                    'subject' => __( 'Partial refund for your order #{%order_id%}', 'wp-event-solution' ),
                    'body'    => $p(
                        __( 'Hi {%customer_name%},', 'wp-event-solution' ),
                        __( 'We have refunded {%refund_amount%} of your order #{%order_id%} ({%event_title%}).', 'wp-event-solution' ),
                        __( 'Reason: {%refund_reason%}', 'wp-event-solution' )
                    ),
                ],
            ],
        ];
    }
}
