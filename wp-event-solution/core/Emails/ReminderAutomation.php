<?php
/**
 * On-demand "Send Reminder to All Attendees" automation.
 *
 * Eventin's scheduled reminder (`event_reminder_email`, registered by
 * Eventin\Admin\EventReminder when an event is created) is not reusable for an
 * on-demand send. Its flow normally carries a "N days before event start" delay
 * node, and FlowManager either re-schedules the dispatch — so the organizer's
 * click sends nothing today — or, once that window has passed, fires at once and
 * duplicates the reminder the attendee already received.
 *
 * So the on-demand send gets its own trigger with its own delay-free flow. One
 * click means one send, and editing either flow never disturbs the other.
 *
 * This class is the single source of truth for that trigger: its slug, whether
 * it can actually deliver right now, and the payload it dispatches. The REST
 * route that exposes it lives in eventin-pro; everything it needs is here so Pro
 * duplicates none of the automation plumbing.
 *
 * @package Eventin
 */

namespace Eventin\Emails;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Event\Event_Model;
use Eventin\Admin\EventReminder;

/**
 * ReminderAutomation class
 */
class ReminderAutomation {

    /**
     * Trigger slug dispatched through `global_notification_hook`.
     *
     * Deliberately distinct from `event_reminder_email` — see the class docblock.
     */
    const TRIGGER = 'send_reminder_email_to_all_attendees';

    /**
     * Prefix the notification SDK is registered under (see eventin.php `general_prefix`).
     */
    const PREFIX = 'eve';

    /**
     * Is the Automation module switched on?
     *
     * When it is off the SDK is never loaded (see Wpeventin::load_composer_packages),
     * so `global_notification_hook` has no listener and the dispatch silently
     * reaches nobody.
     *
     * @return bool
     */
    public static function is_module_on() {
        $options = get_option( 'etn_addons_options' );

        if ( ! is_array( $options ) ) {
            return false;
        }

        return 'on' === ( $options['automation'] ?? 'off' );
    }

    /**
     * Is there a published flow listening for this trigger?
     *
     * The SDK only runs flows in `publish`. A draft flow accepts the dispatch and
     * mails nobody, which would show the organizer a success message for an email
     * that never left — the one outcome this feature must not produce.
     *
     * @return bool
     */
    public static function is_flow_published() {
        $flows = get_posts( [
            'post_type'      => self::PREFIX . '-flow',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_key'       => '_' . self::PREFIX . '_notification_flow_trigger', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
            'meta_value'     => self::TRIGGER,                                     // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
        ] );

        return ! empty( $flows );
    }

    /**
     * Both readiness answers, for the admin screen to render the right warning.
     *
     * Kept as two separate booleans rather than one flag because the two failures
     * need different instructions: "turn on the Automation module" versus
     * "publish the reminder flow".
     *
     * @return array{module_on: bool, flow_published: bool}
     */
    public static function status() {
        $module_on = self::is_module_on();

        return [
            'module_on' => $module_on,
            // Skip the query when the module is off: the flow CPT is registered by
            // the SDK, which is not loaded in that state.
            'flow_published' => $module_on ? self::is_flow_published() : false,
        ];
    }

    /**
     * Can a dispatch actually deliver right now?
     *
     * @return bool
     */
    public static function is_ready() {
        $status = self::status();

        return $status['module_on'] && $status['flow_published'];
    }

    /**
     * Does this event have anyone to remind?
     *
     * Mirrors EnsHooks::get_to_attendee_ids() — same post type, same two meta
     * conditions — so the answer can never disagree with the recipient list the
     * SDK builds a moment later. Without this the route would report success for
     * an event nobody has booked.
     *
     * @param int $event_id
     *
     * @return bool
     */
    public static function has_recipients( $event_id ) {
        $attendees = get_posts( [
            'post_type'      => 'etn-attendee',
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                [
                    'key'     => 'etn_event_id',
                    'value'   => absint( $event_id ),
                    'compare' => '=',
                ],
                [
                    'key'     => 'etn_status',
                    'value'   => 'success',
                    'compare' => '=',
                ],
            ],
        ] );

        return ! empty( $attendees );
    }

    /**
     * Dispatch the on-demand reminder for one event.
     *
     * Recipients are not listed here: EnsHooks resolves them at send time from
     * `event_id`, so an attendee who books between the click and the send is
     * still included, and refunded attendees are not.
     *
     * @param Event_Model $event Event whose attendees to remind.
     *
     * @return void
     */
    public static function dispatch( $event ) {
        do_action( 'global_notification_hook', self::TRIGGER, self::payload( $event ) );
    }

    /**
     * Build the trigger payload.
     *
     * Mirrors EventReminder::reminder_payload() so a flow moved between the two
     * reminder triggers finds every variable it expects. Both start and end
     * timestamps are always present: a delay node keyed off the missing one
     * resolves to "now" and mails everyone immediately.
     *
     * @param Event_Model $event
     *
     * @return array
     */
    public static function payload( $event ) {
        $date_format = get_option( 'date_format' );
        $reminder    = new EventReminder();

        $start = $reminder->get_event_date_timestamp( $event->get_start_date(), $event->get_start_time( 'H:i' ) );
        $end   = $reminder->get_event_date_timestamp( $event->get_end_date(), $event->get_end_time( 'H:i' ) );

        return [
            'site_name'                  => get_bloginfo( 'name' ),
            'site_link'                  => get_site_url(),
            'event_title'                => $event->get_title(),
            'event_date'                 => $event->get_start_date( $date_format ),
            'event_time'                 => $event->get_start_time( etn_time_format() ),
            'event_date_timestamp'       => $start,
            'event_start_date_timestamp' => $start,
            'event_end_date_timestamp'   => $end,
            'event_location'             => $event->get_address(),
            // Left empty on purpose — EnsHooks resolves recipients from event_id.
            'attendee_id'                => [],
            'attendee_email'             => [],
            'event_id'                   => $event->id,
            'post_id'                    => $event->id,
            'session_id'                 => uniqid(),
        ];
    }
}
