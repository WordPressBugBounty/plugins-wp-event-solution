<?php
/**
 * Event location helpers
 *
 * @package Eventin\Event
 */

namespace Eventin\Event;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;

/**
 * Reads and repairs the `etn_event_location` value.
 *
 * The CSV exporter writes a location array as `key:value` pairs joined by
 * commas, for example `address:Dhaka,integration:custom_url,custom_url:https://…`.
 * Before 4.1.26 the importer split that string only at the first colon, so the
 * whole tail landed in one field: `integration` for an online event, `address`
 * for a hybrid one. The Custom URL join link then sat inside a field that is
 * public, and code that hides `custom_url` could not see it.
 *
 * @since 4.1.26
 */
class EventLocation implements HookableInterface {
    /**
     * Keys the location array can hold, as the CSV exporter writes them.
     *
     * @var string[]
     */
    const KEYS = [ 'address', 'place_id', 'latitude', 'longitude', 'integration', 'custom_url' ];

    /**
     * Meta keys that hold an event location.
     *
     * @var string[]
     */
    const META_KEYS = [ 'etn_event_location', 'location' ];

    /**
     * Register hooks
     *
     * @return void
     */
    public function register_hooks(): void {
        add_filter( 'get_post_metadata', [ $this, 'repair_on_read' ], 10, 4 );
    }

    /**
     * Hand every reader a repaired location.
     *
     * About 30 templates, widgets and shortcodes print `address` straight from
     * get_post_meta(). The 4.1.26 upgrader repairs the stored value, but until
     * it has run (or if the data is damaged again some other way) this keeps the
     * join link out of the public address.
     *
     * @param mixed  $value     Value from an earlier filter, or null.
     * @param int    $object_id Post id.
     * @param string $meta_key  Meta key.
     * @param bool   $single    Whether one value is asked for.
     *
     * @return mixed
     */
    public function repair_on_read( $value, $object_id, $meta_key, $single ) {
        if ( null !== $value || ! in_array( $meta_key, self::META_KEYS, true ) ) {
            return $value;
        }

        // Read the cache directly: calling get_post_meta() here would loop.
        $cache = wp_cache_get( $object_id, 'post_meta' );

        if ( false === $cache ) {
            $cache = update_meta_cache( 'post', [ $object_id ] );
            $cache = isset( $cache[ $object_id ] ) ? $cache[ $object_id ] : [];
        }

        if ( empty( $cache[ $meta_key ] ) || 'etn' !== get_post_type( $object_id ) ) {
            return $value;
        }

        $values = array_map( 'maybe_unserialize', $cache[ $meta_key ] );

        // Leave healthy values to WordPress, so nothing changes for them.
        $repaired = array_map( [ self::class, 'repair' ], $values );

        if ( $repaired === $values ) {
            return $value;
        }

        // get_metadata() returns $repaired[0] when $single is true.
        return $repaired;
    }

    /**
     * Parse a CSV location cell into a location array.
     *
     * Splits only where a known key starts, so commas inside an address and
     * colons inside a URL are kept.
     *
     * @param mixed $value CSV cell.
     *
     * @return array|string Location array, the plain string when it holds no
     *                      `key:value` pairs, or '' when empty.
     */
    public static function from_csv( $value ) {
        if ( empty( $value ) || ! is_string( $value ) ) {
            return '';
        }

        $value = trim( $value );

        preg_match_all( self::key_pattern(), $value, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER );

        // Not our `key:value` format. Keep the old single-pair reading for an
        // unknown key, else the cell is a plain address string.
        if ( empty( $matches ) || 0 !== $matches[0][0][1] ) {
            if ( preg_match( '/^[a-z_]+:.+/i', $value ) ) {
                $parts = explode( ':', $value, 2 );

                return [ $parts[0] => $parts[1] ];
            }

            return $value;
        }

        $location = [];
        $count    = count( $matches );

        foreach ( $matches as $index => $match ) {
            $start = $match[0][1] + strlen( $match[0][0] );
            $end   = $index + 1 < $count ? $matches[ $index + 1 ][0][1] : strlen( $value );

            $location[ strtolower( $match[1][0] ) ] = trim( substr( $value, $start, $end - $start ) );
        }

        return $location;
    }

    /**
     * Undo the old importer damage in a stored location.
     *
     * A field such as `integration` => `custom_url,custom_url:https://…` is
     * split back into `integration` and `custom_url`. A location without that
     * damage comes back unchanged.
     *
     * Never unserializes: the importer hands it data from an uploaded file.
     *
     * @param mixed $location Location array or string.
     *
     * @return mixed
     */
    public static function repair( $location ) {
        if ( is_string( $location ) ) {
            return preg_match( self::key_pattern(), $location, $match, PREG_OFFSET_CAPTURE ) && 0 === $match[0][1]
                ? self::from_csv( $location )
                : $location;
        }

        if ( ! is_array( $location ) ) {
            return $location;
        }

        foreach ( self::KEYS as $key ) {
            if ( ! isset( $location[ $key ] ) || ! is_string( $location[ $key ] ) ) {
                continue;
            }

            // Only a field that swallowed later pairs, e.g. `…,custom_url:…`.
            if ( ! preg_match( '/,\s*(?:' . implode( '|', self::KEYS ) . ')\s*:/i', $location[ $key ] ) ) {
                continue;
            }

            $parsed = self::from_csv( $key . ':' . $location[ $key ] );

            if ( ! is_array( $parsed ) ) {
                continue;
            }

            foreach ( $parsed as $parsed_key => $parsed_value ) {
                if ( $parsed_key === $key || empty( $location[ $parsed_key ] ) ) {
                    $location[ $parsed_key ] = $parsed_value;
                }
            }
        }

        foreach ( [ 'online', 'offline' ] as $part ) {
            if ( isset( $location[ $part ] ) && is_array( $location[ $part ] ) ) {
                $location[ $part ] = self::repair( $location[ $part ] );
            }
        }

        return $location;
    }

    /**
     * Repair the location of every event an old CSV import damaged.
     *
     * The public event page and its JSON-LD print `address` straight from the
     * stored meta, so hiding the link in API responses is not enough: the
     * stored value itself must be fixed.
     *
     * @return int Number of meta rows changed.
     */
    public static function repair_stored_events() {
        global $wpdb;

        $likes = [];

        foreach ( self::KEYS as $key ) {
            $likes[] = $wpdb->prepare( 'pm.meta_value LIKE %s', '%' . $wpdb->esc_like( ',' . $key . ':' ) . '%' );
        }

        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery -- every LIKE above is prepared; one-time upgrade query.
        $rows = $wpdb->get_results(
            "SELECT pm.meta_id, pm.meta_value FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id AND p.post_type = 'etn'
             WHERE pm.meta_key IN ( 'etn_event_location', 'location' ) AND ( " . implode( ' OR ', $likes ) . ' )'
        );

        $changed = 0;

        foreach ( (array) $rows as $row ) {
            // The raw row, not get_post_meta(): repair_on_read() would hand back
            // the repaired value and nothing would be saved.
            $stored   = maybe_unserialize( $row->meta_value );
            $repaired = self::repair( $stored );

            if ( $repaired !== $stored ) {
                update_metadata_by_mid( 'post', (int) $row->meta_id, $repaired );
                $changed++;
            }
        }

        return $changed;
    }

    /**
     * Regex that finds where a known key starts: at the start or after a comma.
     *
     * @return string
     */
    private static function key_pattern() {
        return '/(?:^|,)\s*(' . implode( '|', self::KEYS ) . ')\s*:/i';
    }
}
