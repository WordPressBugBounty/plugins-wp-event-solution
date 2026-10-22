<?php
/**
 * Phone numbers for WhatsApp steps in Automation flows.
 *
 * The notification SDK sends a WhatsApp step to the number it finds in the
 * hook payload under the step's receiver key: `customer_number`,
 * `admin_number` or `attendee_number`. This class is the one place that
 * builds those values, so every `global_notification_hook` payload carries
 * the same keys.
 *
 * Event-level triggers (reminder, certificate, send to all attendees) do not
 * list attendees in the payload, because the flow may wait days before it
 * sends. Their `attendee_number` is an empty list, and resolve_numbers() fills
 * it at send time — the same way EnsHooks::get_to_attendee_emails() does for
 * emails, with the same query, so both lists stay index-aligned.
 *
 * Sending WhatsApp is a Pro feature. Free only carries the numbers; the
 * channel stays off until Pro turns on `eve_ens_whatsapp_enabled`.
 *
 * @package Eventin
 */

namespace Eventin\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * WhatsApp recipients
 */
class WhatsappRecipients {

    /**
     * Triggers whose attendee numbers are found at send time.
     *
     * @var string[]
     */
    const EVENT_LEVEL_TRIGGERS = [
        'event_reminder_email',
        'send_email_to_all_attendees',
        'send_certificate',
        ReminderAutomation::TRIGGER,
    ];

    /**
     * Register hooks
     *
     * @return  void
     */
    public function __construct() {
        // Off by default. Eventin Pro turns it on when the WhatsApp module is on.
        add_filter( 'eve_ens_whatsapp_enabled', '__return_false', 5 );
        add_filter( 'eve_notification_sdk_to_whatsapp_numbers', [ $this, 'resolve_numbers' ], 10, 3 );
        // After eventin.php builds the trigger list (priority 10).
        add_filter( 'ens_eve_available_actions', [ $this, 'add_whatsapp_receivers' ], 20 );
    }

    /**
     * Give each trigger a WhatsApp receiver list for the flow builder.
     *
     * Built from the trigger's email receivers, so a WhatsApp step can only
     * target someone the trigger can already email: customer_email becomes
     * customer_number, and so on.
     *
     * @param   array  $actions  Trigger configs.
     *
     * @return  array
     */
    public function add_whatsapp_receivers( $actions ) {
        $map = [
            'attendee_email' => [ 'attendee_number', __( 'Attendee Number', 'wp-event-solution' ) ],
            'customer_email' => [ 'customer_number', __( 'Customer Number', 'wp-event-solution' ) ],
            'admin_email'    => [ 'admin_number', __( 'Admin Number', 'wp-event-solution' ) ],
        ];

        foreach ( (array) $actions as $index => $action ) {
            if ( ! is_array( $action ) || isset( $action['whatsapp_receivers'] ) || empty( $action['email_receivers'] ) ) {
                continue;
            }

            $receivers = [];
            foreach ( (array) $action['email_receivers'] as $receiver ) {
                $value = is_array( $receiver ) ? ( $receiver['value'] ?? '' ) : '';
                if ( isset( $map[ $value ] ) ) {
                    $receivers[] = [
                        'label' => $map[ $value ][1],
                        'value' => $map[ $value ][0],
                    ];
                }
            }

            $actions[ $index ]['whatsapp_receivers'] = $receivers;
        }

        return $actions;
    }

    /**
     * Admin WhatsApp number from settings.
     *
     * @return  string
     */
    public static function admin_number() {
        return (string) etn_get_option( 'whatsapp_admin_number', '' );
    }

    /**
     * Attendee phones, in the same order as the attendees given.
     *
     * An attendee without a phone keeps an empty string, so the list stays
     * index-aligned with `attendee_id` / `attendee_email`. The SDK skips
     * empty numbers.
     *
     * @param   array  $attendees  Attendee arrays or objects with `etn_phone`.
     *
     * @return  string[]
     */
    public static function attendee_numbers( $attendees ) {
        $numbers = [];

        foreach ( (array) $attendees as $attendee ) {
            $phone     = is_array( $attendee ) ? ( $attendee['etn_phone'] ?? '' ) : ( $attendee->etn_phone ?? '' );
            $numbers[] = is_scalar( $phone ) ? (string) $phone : '';
        }

        return $numbers;
    }

    /**
     * Fill attendee numbers at send time for event-level triggers.
     *
     * Only an empty list is filled. A list given in the payload (for example
     * the single-attendee certificate send) is used as it is.
     *
     * @param   mixed   $numbers      Value of the step's receiver key.
     * @param   array   $action_data  Hook payload.
     * @param   string  $action_name  Trigger name.
     *
     * @return  mixed
     */
    public function resolve_numbers( $numbers, $action_data, $action_name ) {
        if ( ! is_array( $numbers ) || ! empty( $numbers ) ) {
            return $numbers;
        }

        if ( ! in_array( $action_name, self::EVENT_LEVEL_TRIGGERS, true ) || empty( $action_data['event_id'] ) ) {
            return $numbers;
        }

        // Same query as EnsHooks::get_to_attendee_emails().
        $attendees = get_posts( [
            'post_type'      => 'etn-attendee',
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                [
                    'key'     => 'etn_event_id',
                    'value'   => $action_data['event_id'],
                    'compare' => '=',
                ],
                [
                    'key'     => 'etn_status',
                    'value'   => 'success',
                    'compare' => '=',
                ],
            ],
        ] );

        return self::attendee_numbers( $attendees );
    }
}
