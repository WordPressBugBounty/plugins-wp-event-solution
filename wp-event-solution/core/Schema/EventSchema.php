<?php
/**
 * Describes an Eventin event to search engines.
 *
 * @package Eventin\Schema
 */

namespace Eventin\Schema;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Event\Event_Model;
use Eventin\Schema\Abstracts\Schema;

/**
 * Builds the schema.org Event object.
 *
 * This is the node Google reads to turn a plain search listing into a card
 * carrying the date, the venue and the ticket price. Everything else in this
 * namespace exists to fill one of its properties.
 */
class EventSchema extends Schema {

    /**
     * The schema.org type this class produces.
     *
     * @var string
     */
    protected $type = 'Event';

    /**
     * Attendance mode by Eventin event type.
     *
     * @var array<string, string>
     */
    const ATTENDANCE_MODES = [
        'offline' => 'https://schema.org/OfflineEventAttendanceMode',
        'online'  => 'https://schema.org/OnlineEventAttendanceMode',
        'hybrid'  => 'https://schema.org/MixedEventAttendanceMode',
    ];

    /**
     * Place schema builder.
     *
     * @var PlaceSchema
     */
    protected $place;

    /**
     * Person schema builder.
     *
     * @var PersonSchema
     */
    protected $person;

    /**
     * Offer schema builder.
     *
     * @var OfferSchema
     */
    protected $offer;

    /**
     * Constructor.
     *
     * @param PlaceSchema|null  $place  Place builder.
     * @param PersonSchema|null $person Person builder.
     * @param OfferSchema|null  $offer  Offer builder.
     */
    public function __construct( PlaceSchema $place = null, PersonSchema $person = null, OfferSchema $offer = null ) {
        $this->place  = $place ? $place : new PlaceSchema();
        $this->person = $person ? $person : new PersonSchema();
        $this->offer  = $offer ? $offer : new OfferSchema();
    }

    /**
     * Build the Event object for one or more events.
     *
     * @param int|\WP_Post|array $posts One event, or a list of events.
     * @param array              $args  Arguments passed through to the builders.
     *
     * @return array Map of event ID => Event object.
     */
    public function get_data( $posts, array $args = [] ): array {
        $posts  = is_array( $posts ) ? $posts : [ $posts ];
        $return = [];

        foreach ( $posts as $post ) {
            $post = get_post( $post );

            if ( ! $post instanceof \WP_Post || 'etn' !== $post->post_type ) {
                continue;
            }

            $data = $this->build( $post, $args );

            if ( $data ) {
                $return[ $post->ID ] = $data;
            }
        }

        return $return;
    }

    /**
     * Build a single Event object.
     *
     * @param \WP_Post $post Event post.
     * @param array    $args Arguments.
     *
     * @return object|null
     */
    protected function build( \WP_Post $post, array $args ) {
        $data = $this->base_data( $post, $args );

        if ( ! $data ) {
            return null;
        }

        $event = new Event_Model( $post->ID );

        $this->add_dates( $data, $event );
        $this->add_status( $data, $event );
        $this->add_location( $data, $event, $post );
        $this->add_people( $data, $event );
        $this->add_offers( $data, $event );

        return $this->apply_object_filter( $data, $args, $post );
    }

    /**
     * Add start and end dates as ISO 8601 strings in the event's own timezone.
     *
     * The single most important thing this class does. Google rejects an Event
     * whose startDate is not a valid ISO 8601 datetime, and a rejected Event
     * produces no rich result at all — which is exactly what the previous
     * implementation shipped, by reusing display-formatted strings.
     *
     * Event_Model::get_datetime() already parses in the event's timezone, so it
     * avoids the double-offset trap documented for TemplateModel.
     *
     * @param object      $data  Schema object.
     * @param Event_Model $event Event model.
     *
     * @return void
     */
    protected function add_dates( $data, Event_Model $event ): void {
        $start_date = $event->etn_start_date;

        // etn_parse_event_datetime() falls back to "now" on an unparseable
        // value, so an event with no stored start date would otherwise be
        // advertised as happening today. Better to emit no date at all.
        if ( ! $start_date ) {
            return;
        }

        try {
            $data->startDate = $event->get_start_datetime( 'c' );
            $data->endDate   = $event->get_end_datetime( 'c' );
        } catch ( \Exception $e ) {
            unset( $data->startDate, $data->endDate );
        }
    }

    /**
     * Add the event's scheduling status.
     *
     * Eventin has no cancelled or postponed state, so this is always Scheduled.
     * The filter is the seam for Pro or a site that adds one.
     *
     * @param object      $data  Schema object.
     * @param Event_Model $event Event model.
     *
     * @return void
     */
    protected function add_status( $data, Event_Model $event ): void {
        /**
         * Filters the event's schema.org eventStatus.
         *
         * @since 4.1.20
         *
         * @param string      $status A schema.org EventStatusType URL.
         * @param Event_Model $event  Event model.
         */
        $data->eventStatus = apply_filters(
            'eventin_schema_event_status',
            'https://schema.org/EventScheduled',
            $event
        );
    }

    /**
     * Add where the event happens, and how people attend it.
     *
     * An online event must carry a VirtualLocation with a URL or Google rejects
     * it; a hybrid event carries both the virtual and the physical location.
     *
     * @param object      $data  Schema object.
     * @param Event_Model $event Event model.
     * @param \WP_Post    $post  Event post.
     *
     * @return void
     */
    protected function add_location( $data, Event_Model $event, \WP_Post $post ): void {
        $type = $event->event_type;
        $type = is_string( $type ) && '' !== $type ? $type : 'offline';

        $data->eventAttendanceMode = self::ATTENDANCE_MODES[ $type ] ?? self::ATTENDANCE_MODES['offline'];

        $place   = $this->place->get_data( $post, [ 'context' => false ] );
        $place   = $place ? reset( $place ) : null;
        $virtual = $this->get_virtual_location( $event );

        if ( 'online' === $type ) {
            // Online-only: the virtual location replaces the venue entirely.
            if ( $virtual ) {
                $data->location = $virtual;
            }

            return;
        }

        if ( 'hybrid' === $type && $virtual && $place ) {
            $data->location = [ $virtual, $place ];

            return;
        }

        if ( $place ) {
            $data->location = $place;

            return;
        }

        if ( $virtual ) {
            // A physical event with no stored address but a join link — describe
            // what we actually have rather than emitting an empty Place.
            $data->location            = $virtual;
            $data->eventAttendanceMode = self::ATTENDANCE_MODES['online'];
        }
    }

    /**
     * Build the VirtualLocation for an online or hybrid event.
     *
     * schema.org requires a URL on VirtualLocation, so fall back through every
     * link Eventin might hold before giving up.
     *
     * @param Event_Model $event Event model.
     *
     * @return object|null
     */
    protected function get_virtual_location( Event_Model $event ) {
        $candidates = [
            $event->meeting_link,
            $event->etn_zoom_event,
            $event->etn_google_meet,
            $event->external_link,
        ];

        foreach ( $candidates as $candidate ) {
            if ( is_string( $candidate ) && filter_var( $candidate, FILTER_VALIDATE_URL ) ) {
                return (object) [
                    '@type' => 'VirtualLocation',
                    'url'   => esc_url_raw( $candidate ),
                ];
            }
        }

        $type = $event->event_type;

        // Online events must carry a location; the event page is where the
        // visitor goes to join, so it is a truthful last resort.
        if ( 'online' === $type || 'hybrid' === $type ) {
            return (object) [
                '@type' => 'VirtualLocation',
                'url'   => esc_url_raw( get_permalink( $event->id ) ),
            ];
        }

        return null;
    }

    /**
     * Add the organiser and the speakers.
     *
     * @param object      $data  Schema object.
     * @param Event_Model $event Event model.
     *
     * @return void
     */
    protected function add_people( $data, Event_Model $event ): void {
        $organizers = $this->build_people( $event->get_organizers() );

        if ( $organizers ) {
            $data->organizer = 1 === count( $organizers ) ? reset( $organizers ) : array_values( $organizers );
        }

        $speakers = $this->build_people( $event->get_speakers() );

        if ( $speakers ) {
            $data->performer = 1 === count( $speakers ) ? reset( $speakers ) : array_values( $speakers );
        } else {
            // Google Search Console warns on a missing performer. The Events
            // Calendar emits this same placeholder for the same reason.
            $data->performer = 'Organization';
        }
    }

    /**
     * Turn a list of speaker / organiser models into Person objects.
     *
     * @param array $models User models.
     *
     * @return array
     */
    protected function build_people( $models ): array {
        if ( ! is_array( $models ) ) {
            return [];
        }

        $people = [];

        foreach ( $models as $model ) {
            $person = $this->person->get_data( $model, [ 'context' => false ] );

            if ( $person ) {
                $people[] = reset( $person );
            }
        }

        return $people;
    }

    /**
     * Add one Offer per ticket.
     *
     * Emitted as a flat list — the previous implementation wrapped it in a
     * second array, which no validator accepts.
     *
     * @param object      $data  Schema object.
     * @param Event_Model $event Event model.
     *
     * @return void
     */
    protected function add_offers( $data, Event_Model $event ): void {
        $offers = $this->offer->get_offers( $event );

        if ( ! $offers ) {
            return;
        }

        $data->offers = 1 === count( $offers ) ? reset( $offers ) : $offers;
    }

    /**
     * Wrap the events in a script tag ready for output.
     *
     * @param int|\WP_Post|array $posts Events to describe.
     * @param array              $args  Arguments.
     *
     * @return string The script tag, or an empty string when there is nothing
     *                new to say.
     */
    public function get_markup( $posts, array $args = [] ): string {
        $data = $this->get_data( $posts, $args );

        // Drop anything already described on this page, then record the rest.
        foreach ( array_keys( $data ) as $post_id ) {
            if ( $this->already_emitted( $post_id ) ) {
                unset( $data[ $post_id ] );
                continue;
            }

            $this->mark_emitted( $post_id );
        }

        if ( ! $data ) {
            return '';
        }

        /**
         * Filters the full set of Event objects before they are encoded.
         *
         * @since 4.1.20
         *
         * @param array $data Map of event ID => Event object.
         * @param array $args Arguments passed to get_data().
         */
        $data = apply_filters( 'eventin_schema_event_data', $data, $args );

        $data = array_values( $data );
        $json = wp_json_encode( 1 === count( $data ) ? reset( $data ) : $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

        if ( ! $json ) {
            return '';
        }

        return '<script type="application/ld+json">' . $json . '</script>' . "\n";
    }
}
