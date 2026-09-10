<?php
/**
 * Describes where an event physically happens.
 *
 * @package Eventin\Schema
 */

namespace Eventin\Schema;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Event\Event_Model;
use Eventin\Schema\Abstracts\Schema;

/**
 * Builds the schema.org Place for an event's venue.
 *
 * Eventin stores the venue as free text plus optional coordinates
 * (`etn_event_location` => address / latitude / longitude), or as a term of the
 * `etn_location` taxonomy when the event uses a saved location. Google asks for
 * a PostalAddress rather than a bare string, so the free-text address is mapped
 * onto `streetAddress` — that is the most faithful thing to do without inventing
 * a city/region split the plugin never captured.
 */
class PlaceSchema extends Schema {

    /**
     * The schema.org type this class produces.
     *
     * @var string
     */
    protected $type = 'Place';

    /**
     * Build the Place object for an event.
     *
     * Unlike the other schema classes this is keyed off the event, not off a
     * venue post — Eventin has no venue post type.
     *
     * @param int|\WP_Post $post Event post ID or object.
     * @param array        $args Arguments. Defaults to context => false since a
     *                           Place is always nested inside an Event.
     *
     * @return array Map of event ID => Place object, or an empty array.
     */
    public function get_data( $post, array $args = [ 'context' => false ] ): array {
        $post = get_post( $post );

        if ( ! $post instanceof \WP_Post || ! $this->is_viewable( $post ) ) {
            return [];
        }

        $event   = new Event_Model( $post->ID );
        $address = $this->get_address( $event );
        $name    = $this->get_place_name( $event, $address );

        // A Place with neither a name nor an address says nothing.
        if ( ! $address && ! $name ) {
            return [];
        }

        $data = (object) [
            '@type' => $this->type,
        ];

        if ( ! isset( $args['context'] ) || false !== $args['context'] ) {
            $data->{'@context'} = 'https://schema.org';
        }

        $data->name = $name ? $name : $address;

        if ( $address ) {
            $data->address = (object) [
                '@type'         => 'PostalAddress',
                'streetAddress' => $address,
            ];
        }

        $geo = $this->get_geo( $event );
        if ( $geo ) {
            $data->geo = $geo;
        }

        return [ $post->ID => $this->apply_object_filter( $data, $args, $post ) ];
    }

    /**
     * The venue's street address.
     *
     * @param Event_Model $event Event model.
     *
     * @return string
     */
    protected function get_address( Event_Model $event ): string {
        $location = $event->etn_event_location;

        if ( is_array( $location ) && ! empty( $location['address'] ) ) {
            return wp_strip_all_tags( (string) $location['address'] );
        }

        if ( is_string( $location ) && '' !== trim( $location ) ) {
            return wp_strip_all_tags( $location );
        }

        return '';
    }

    /**
     * The venue's name.
     *
     * Events using a saved location carry it as an `etn_location` term; the term
     * name is the closest thing Eventin has to a venue name.
     *
     * @param Event_Model $event   Event model.
     * @param string      $address Street address, used as the fallback name.
     *
     * @return string
     */
    protected function get_place_name( Event_Model $event, string $address ): string {
        $terms = get_the_terms( $event->id, 'etn_location' );

        if ( is_array( $terms ) && ! empty( $terms ) ) {
            $term = reset( $terms );

            return wp_strip_all_tags( $term->name );
        }

        return $address;
    }

    /**
     * Coordinates for the venue, when the event stored them.
     *
     * @param Event_Model $event Event model.
     *
     * @return object|null
     */
    protected function get_geo( Event_Model $event ) {
        $location = $event->etn_event_location;

        if ( ! is_array( $location ) ) {
            return null;
        }

        $latitude  = isset( $location['latitude'] ) ? trim( (string) $location['latitude'] ) : '';
        $longitude = isset( $location['longitude'] ) ? trim( (string) $location['longitude'] ) : '';

        if ( '' === $latitude || '' === $longitude || ! is_numeric( $latitude ) || ! is_numeric( $longitude ) ) {
            return null;
        }

        return (object) [
            '@type'     => 'GeoCoordinates',
            'latitude'  => (float) $latitude,
            'longitude' => (float) $longitude,
        ];
    }
}
