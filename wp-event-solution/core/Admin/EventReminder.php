<?php

namespace Eventin\Admin;

defined( 'ABSPATH' ) || exit;

use DateTime;
use Etn\Core\Attendee\Attendee_Model;
use Etn\Core\Event\Event_Model;
use Eventin\Emails\AttendeeEventReminderEmail;
use Eventin\Interfaces\HookableInterface;
use Eventin\Mails\Mail;

class EventReminder implements HookableInterface {

    /**
     * Number of events re-synced per batch when the Automation module is toggled.
     */
    const RESYNC_BATCH_SIZE = 50;

    /**
     * Prefix the notification SDK is registered under (see eventin.php `general_prefix`).
     */
    const AUTOMATION_PREFIX = 'eve';

    /**
     * Extra head-room, in seconds, on top of a flow's own delay before an event
     * is considered safe to back-fill.
     */
    const RESYNC_SAFETY_MARGIN = 300;

    /**
     * Lead time assumed when no published reminder flow can be inspected.
     */
    const RESYNC_FALLBACK_LEAD = 30 * DAY_IN_SECONDS;

    /**
     * Register service
     *
     * @return  void
     */
    public function register_hooks(): void {
        add_action( 'eventin_event_created', [$this, 'register_schedule'] );
        add_action( 'eventin_reset_reminder_email', [$this, 'reset_reminder_email_schedule'], 10, 2 );

        // Toggling the Automation module has to re-decide the reminder route for
        // events that already exist; the decision used to be frozen at creation.
        add_action( 'eventin_automation_module_toggled', [$this, 'resync_all'] );
        add_action( 'eventin_resync_reminder_schedules', [$this, 'resync_batch'] );

        add_action( 'run_event_scheduler', [$this, 'run_event_schedule'] );

        add_action( 'send_reminder_email', [$this, 'send_reminder_email'] );
    }

    /**
     * Whether the Automation module currently owns reminder delivery
     *
     * @return  string  'on' or 'off'
     */
    private function is_automation_on() {
        $options = get_option( 'etn_addons_options' );

        if ( ! is_array( $options ) ) {
            return 'off';
        }

        return $options['automation'] ?? 'off';
    }

    /**
     * Register schedule for event reminder
     *
     * @param   Event_Model $event
     *
     * @return  void
     */
    public function register_schedule( $event ) {
        // Drop any default cron left over from a previous run or a previous
        // module state, so switching routes never leaves both active.
        wp_clear_scheduled_hook( 'send_reminder_email', [$event->id] );

        // check if automation module is on
        if ( 'on' === $this->is_automation_on() ) {
            $this->register_automated_schedule( $event );
        } else {
            $this->register_default_schedule( $event );
        }

    }

    /**
     * Queue a re-sync of ongoing and upcoming events after the Automation module is toggled
     *
     * Runs out of band: a site with thousands of events would otherwise time out
     * the REST request that flipped the module.
     *
     * @return  void
     */
    public function resync_all() {
        if ( ! wp_next_scheduled( 'eventin_resync_reminder_schedules', [0] ) ) {
            wp_schedule_single_event( time() + 5, 'eventin_resync_reminder_schedules', [0] );
        }
    }

    /**
     * Back-fill reminder schedules for one batch of ongoing/upcoming events
     *
     * Only meaningful while Automation owns reminders: the default route needs no
     * back-fill, since its cron is (re)armed from the event itself.
     *
     * @param   integer  $offset
     *
     * @return  void
     */
    public function resync_batch( $offset = 0 ) {
        if ( 'on' !== $this->is_automation_on() ) {
            return;
        }

        $offset = (int) $offset;

        // An event is only safe to back-fill if BOTH reminder windows are still
        // ahead of us:
        //  - the flow's own delay, because FlowManager sends immediately once the
        //    delay window has passed rather than scheduling;
        //  - the default `remainder_time` lead, because an event past that point
        //    has already had its default reminder delivered, and registering it
        //    now would send a second one.
        $threshold = time()
            + max( $this->get_reminder_lead_time(), $this->get_default_reminder_lead() )
            + self::RESYNC_SAFETY_MARGIN;

        // Ongoing (started, not yet ended) and upcoming events — keyed off the end
        // date so an in-progress event isn't excluded.
        $event_ids = get_posts( [
            'post_type'      => 'etn',
            'post_status'    => 'publish',
            'posts_per_page' => self::RESYNC_BATCH_SIZE,
            'offset'         => $offset,
            'fields'         => 'ids',
            'orderby'        => 'ID',
            'order'          => 'ASC',
            'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                [
                    'key'     => 'etn_end_date',
                    'value'   => current_time( 'Y-m-d' ),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ],
            ],
        ] );

        if ( ! $event_ids ) {
            return;
        }

        foreach ( $event_ids as $event_id ) {
            $this->backfill_reminder_schedule( new Event_Model( $event_id ), $threshold );
        }

        // Only chain another batch if this one filled up.
        if ( count( $event_ids ) === self::RESYNC_BATCH_SIZE ) {
            wp_schedule_single_event(
                time() + 30,
                'eventin_resync_reminder_schedules',
                [$offset + self::RESYNC_BATCH_SIZE]
            );
        }
    }

    /**
     * Register cron job for schedule a reminder email
     *
     * @param   integer  $event_id
     *
     * @return  void
     */
    public function register_default_schedule( $event ) {

        $date = $event->etn_start_date;
        $time = $event->etn_start_time;

        $event_timestamp = strtotime( $date . ' ' . $time );

        $reminder_time = etn_get_option( 'remainder_time' );

        if ( !$reminder_time ) {
            return;
        }

        foreach ( $reminder_time as $time ) {
            $timestamp = 0;
            $duration = intval( $time['duration-time'] );

            // if `duration-time` value not properly set, skip setting remainder
            if ( !isset( $duration ) || !is_numeric( $duration ) ) {
                continue;
            }

            switch ( $time['custom_duration_type'] ) {
            case 'min':
                $timestamp = $duration * 60;
                break;
            case 'hour':
                $timestamp = $duration * 60 * 60;
                break;
            case 'day':
                $timestamp = ( $duration * 24 ) * 60 * 60;
                break;
            }

            $timestamp = $event_timestamp - $timestamp;

            // A reminder whose window has already passed would be run by the very
            // next cron tick, mailing every attendee immediately. Skip it.
            if ( $timestamp <= time() ) {
                continue;
            }

            wp_schedule_single_event( $timestamp, 'send_reminder_email', [$event->id] );

//            if ( ! wp_next_scheduled( 'event_remainder' ) ) {
//            }
        }

    }

    /**
     * event schedule
     *
     * @return  void
     */
    public function run_event_schedule( $event = null ) {

        if ( !$event ) {
            return;
        }

        add_action( 'send_reminder_email', [$this, 'send_reminder_email'] );
        // Run cron action.
    }

    /**
     * Send email to attendees
     *
     * @param   integer  $event_id  Event id
     *
     * @return  void
     */
    public function send_reminder_email( $event_id ) {

        // Defence in depth: a cron scheduled before the module was switched on
        // must not fall through to the default mailer once Automation owns
        // reminder delivery.
        if ( 'on' === $this->is_automation_on() ) {
            return;
        }

        $args = [
            'post_type'      => 'etn-attendee',
            'post_status'    => 'any',
            'posts_per_page' => -1,

            'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                [
                    'key'     => 'etn_event_id',
                    'value'   => $event_id,
                    'compare' => '=',
                ],
            ],
        ];

        $attendees = get_posts( $args );
        $event = new Event_Model( $event_id );

        if ( $attendees ) {

            foreach ( $attendees as $attendee ) {
                $attendee = new Attendee_Model( $attendee->ID );

                Mail::to( $attendee->etn_email )->send( new AttendeeEventReminderEmail( $event, $attendee ) );
            }

        }

    }

    /**
     * Register cron job for schedule a reminder email
     *
     * @param   integer  $event_id
     *
     * @return  void
     */
    public function register_automated_schedule( $event ) {
        $date_format = get_option( 'date_format' );

        do_action( 'global_notification_hook', 'event_reminder_email', $this->reminder_payload( $event ) );

        // Send certificate automation: fire once at event creation; the SDK
        // schedules the actual send relative to `event_end_date_timestamp`
        // (delay dependency `after_event_end_date`). Recipients resolve at
        // send time via EnsHooks::get_to_attendee_emails().
        do_action( 'global_notification_hook', 'send_certificate', [
            'site_name'                => get_bloginfo( 'name' ),
            'site_link'                => get_site_url(),
            'event_title'              => $event->get_title(),
            'event_date'               => $event->get_end_date( $date_format ),
            'event_time'               => $event->get_end_time( etn_time_format() ),
            // Both timestamps so the flow's delay node can key off event start OR end.
            'event_start_date_timestamp' => $this->get_event_date_timestamp( $event->get_start_date(), $event->get_start_time( 'H:i' ) ),
            'event_end_date_timestamp' => $this->get_event_date_timestamp( $event->get_end_date(), $event->get_end_time( 'H:i' ) ),
            'event_location'           => $event->get_address(),
            'attendee_id'              => [],
            'attendee_email'           => [],
            'event_id'                 => $event->id,
            'post_id'                  => $event->id,
            'session_id'               => uniqid(),
        ] );
    }

    /**
     * Build the payload for an `event_reminder_email` dispatch
     *
     * @param   Event_Model  $event
     *
     * @return  array
     */
    private function reminder_payload( $event ) {
        $date_format = get_option( 'date_format' );
        $start       = $this->get_event_date_timestamp( $event->get_start_date(), $event->get_start_time() );

        return [
            'site_name'            => get_bloginfo( 'name' ),
            'site_link'            => get_site_url(),
            'event_title'          => $event->get_title(),
            'event_date'           => $event->get_start_date( $date_format ),
            'event_time'           => $event->get_start_time(),
            'event_date_timestamp' => $start,
            // Both timestamps so the flow's delay node can key off event start OR end.
            'event_start_date_timestamp' => $start,
            'event_end_date_timestamp'   => $this->get_event_date_timestamp( $event->get_end_date(), $event->get_end_time() ),
            'previous_event_date'  => $start,
            'event_location'       => $event->get_address(),
            'attendee_id'          => [],
            'attendee_email'       => [],
            'event_id'             => $event->id,
            'post_id'              => $event->id,
            'session_id'           => uniqid(),
        ];
    }

    /**
     * Register a single pre-existing event with the Automation flow
     *
     * Deliberately narrower than `register_automated_schedule()`:
     *
     *  - Only `event_reminder_email` is dispatched. `send_certificate` keys off the
     *    event *end* date, which for a back-log of events has usually passed —
     *    re-triggering it mails every attendee immediately.
     *  - Events whose delay window has already passed are skipped, for the same
     *    reason (see FlowManager: `if ( time() >= $resume_time )` sends at once).
     *  - Events the SDK has already registered are skipped, so repeated toggles
     *    don't create duplicate flows.
     *
     * @param   Event_Model  $event
     * @param   integer      $threshold  Earliest start time considered safe.
     *
     * @return  bool  Whether the event was registered.
     */
    private function backfill_reminder_schedule( $event, $threshold ) {
        if ( get_post_meta( $event->id, 'ens_flow_id', true ) ) {
            return false;
        }

        $start = $this->get_event_date_timestamp( $event->get_start_date(), $event->get_start_time() );

        // A zero timestamp means an unparseable/missing date; treating it as "now"
        // is what makes the flow fire instantly, so refuse it outright.
        if ( $start <= 0 || $start < $threshold ) {
            return false;
        }

        wp_clear_scheduled_hook( 'send_reminder_email', [$event->id] );

        do_action( 'global_notification_hook', 'event_reminder_email', $this->reminder_payload( $event ) );

        return true;
    }

    /**
     * Longest delay configured on any published reminder flow, in seconds
     *
     * @return  int
     */
    private function get_reminder_lead_time() {
        $prefix = self::AUTOMATION_PREFIX;

        $flows = get_posts( [
            'post_type'      => $prefix . '-flow',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_key'       => '_' . $prefix . '_notification_flow_trigger', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
            'meta_value'     => 'event_reminder_email',                       // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
        ] );

        $max = 0;

        foreach ( $flows as $flow ) {
            $config = get_post_meta( $flow->ID, '_' . $prefix . '_notification_flow_flow_config', true );

            if ( ! is_array( $config ) || empty( $config['nodes'] ) ) {
                continue;
            }

            foreach ( $config['nodes'] as $node ) {
                if ( 'add_delay' !== ( $node['data']['actionType'] ?? '' ) ) {
                    continue;
                }

                $delay = (int) ( $node['data']['delay'] ?? 0 );

                switch ( $node['data']['delayUnit'] ?? 'seconds' ) {
                case 'minutes':
                    $delay *= MINUTE_IN_SECONDS;
                    break;
                case 'hours':
                    $delay *= HOUR_IN_SECONDS;
                    break;
                case 'days':
                    $delay *= DAY_IN_SECONDS;
                    break;
                case 'weeks':
                    $delay *= WEEK_IN_SECONDS;
                    break;
                }

                $max = max( $max, $delay );
            }
        }

        // No inspectable flow — assume a generous lead time so we skip more events
        // rather than risk an immediate send.
        if ( ! $max ) {
            $max = self::RESYNC_FALLBACK_LEAD;
        }

        return (int) apply_filters( 'eventin_reminder_backfill_lead_time', $max );
    }

    /**
     * Longest lead time configured on the default reminder settings, in seconds
     *
     * An event closer to its start than this has already had its default reminder
     * sent, so back-filling it would deliver a duplicate.
     *
     * @return  int
     */
    private function get_default_reminder_lead() {
        $reminder_time = etn_get_option( 'remainder_time' );

        if ( ! is_array( $reminder_time ) ) {
            return 0;
        }

        $max = 0;

        foreach ( $reminder_time as $rule ) {
            $duration = intval( $rule['duration-time'] ?? 0 );

            switch ( $rule['custom_duration_type'] ?? '' ) {
            case 'min':
                $duration *= MINUTE_IN_SECONDS;
                break;
            case 'hour':
                $duration *= HOUR_IN_SECONDS;
                break;
            case 'day':
                $duration *= DAY_IN_SECONDS;
                break;
            default:
                $duration = 0;
            }

            $max = max( $max, $duration );
        }

        return $max;
    }

    /**
     * Reset reminder email schedule
     *
     * @param   Event_Model $event
     * @param   array       $previous_event_date
     */
    public function reset_reminder_email_schedule( $event, $previous_event_date ) {
        $date_format = get_option( 'date_format' );
        $previous_event_date = $this->get_event_date_timestamp(
            $previous_event_date['previous_event_start_date'],
            $previous_event_date['previous_event_start_time']
        );

        $current_event_date = $this->get_event_date_timestamp( $event->get_start_date(), $event->get_start_time( 'H:i' ) );

        if ( $previous_event_date != $current_event_date ) {
            do_action( 'global_notification_hook', 'event_reminder_email', [
                'site_name'                     => get_bloginfo( 'name' ),
                'site_link'                     => get_site_url(),
                'event_title'                   => $event->get_title(),
                'event_date'                    => $event->get_start_date($date_format),
                'event_time'                    => $event->get_start_time( etn_time_format() ),
                'event_date_timestamp'          => $current_event_date,
                // Both timestamps so the flow's delay node can key off event start OR end.
                'event_start_date_timestamp'    => $current_event_date,
                'event_end_date_timestamp'      => $this->get_event_date_timestamp( $event->get_end_date(), $event->get_end_time( 'H:i' ) ),
                'previous_event_date_timestamp' => $previous_event_date,
                'event_location'                => $event->get_address(),
                'attendee_id'                   => [],
                'attendee_email'                => [],
                'event_id'                      => $event->id,
                'post_id'                       => $event->id,
                'session_id'                    => uniqid(),
            ] );
        }

    }

    /**
     * Get event date timestamp
     *
     * @param   string $date
     * @param   string $time
     * @return  int
     */
    public function get_event_date_timestamp( $date, $time ) {
        $time = is_string( $time ) ? trim( $time ) : '';

        // Normalize any "digits + separator + digits" shape (e.g. "6h45", "6.45", "6 45") to "06:45".
        // Skip 12-hour strings ("8:00 pm"): rewriting them here would discard the
        // am/pm marker and silently schedule every afternoon event 12 hours early.
        if ( $time && ! preg_match( '/[ap]\.?m\.?$/i', $time ) && preg_match( '/^(\d{1,2})\D+(\d{1,2})/', $time, $m ) ) {
            $time = sprintf( '%02d:%02d', (int) $m[1], (int) $m[2] );
        }

        $formatted = '00:00:00';
        if ( $time ) {
            try {
                $formatted = ( new DateTime( $time ) )->format( 'H:i:s' );
            } catch ( \Exception $e ) {
                // Unparseable time → fall back to midnight rather than fataling.
            }
        }

        try {
            return ( new DateTime( $date . ' ' . $formatted, wp_timezone() ) )->getTimestamp();
        } catch ( \Exception $e ) {
            return 0;
        }
    }

}
