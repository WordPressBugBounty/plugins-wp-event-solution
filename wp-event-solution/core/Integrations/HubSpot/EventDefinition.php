<?php
/**
 * HubSpot custom event definition
 *
 * @package Eventin
 */
namespace Eventin\Integrations\HubSpot;

defined( 'ABSPATH' ) || exit;

use WP_Error;

/**
 * Manages the `eventin_ticket_purchased` custom event definition and the
 * sending of occurrences against it.
 *
 * Custom events are unavailable for two different reasons, both reported by
 * HubSpot as a 403: the portal's subscription does not include them (Free and
 * Starter), or the Service Key / private app token was never granted the behavioural-events
 * scopes. Only the response `category` tells them apart, and the fixes are
 * opposite, so `record_unavailable()` classifies before anything is stored and
 * the admin UI reports the matching remedy instead of a generic failure.
 */
class EventDefinition {
    /**
     * Internal event name. HubSpot returns it prefixed as `pe{HubID}_{name}`.
     */
    const EVENT_NAME = 'eventin_ticket_purchased';

    /**
     * Option holding the fully qualified event name returned by HubSpot.
     */
    const NAME_OPTION = 'hubspot_event_definition_name';

    /**
     * Option holding whether the connected portal supports custom events.
     * Values: 'yes' | 'no' | '' (unknown / not yet checked).
     */
    const SUPPORT_OPTION = 'hubspot_custom_events_supported';

    /**
     * Option holding *why* custom events are unavailable: 'tier' | 'scope' | ''.
     *
     * HubSpot answers 403 for both a subscription that lacks custom events and
     * a token that was never granted the behavioural-events scopes. Only the
     * response `category` separates them, and the fixes are opposite — buy an
     * upgrade vs. tick a scope on the key — so they must never be
     * reported with the same message.
     */
    const REASON_OPTION = 'hubspot_custom_events_reason';

    /**
     * Scopes the Service Key / private app needs for the custom event APIs.
     */
    const REQUIRED_SCOPES = 'behavioral_events.event_definitions.read_write, analytics.behavioral_events.send';

    /**
     * Client.
     *
     * @var HubSpotClient
     */
    private $client;

    /**
     * Constructor.
     *
     * @param HubSpotClient|null $client Client.
     */
    public function __construct( $client = null ) {
        $this->client = $client ? $client : new HubSpotClient();
    }

    /**
     * Fully qualified event name, empty when not provisioned.
     *
     * @return string
     */
    public static function get_event_name() {
        $name = etn_get_option( self::NAME_OPTION );

        return is_string( $name ) ? $name : '';
    }

    /**
     * Whether the portal supports custom events. Unknown counts as unsupported
     * for gating purposes but is reported separately to the UI.
     *
     * @return bool
     */
    public static function is_supported() {
        return 'yes' === etn_get_option( self::SUPPORT_OPTION );
    }

    /**
     * Raw support flag: 'yes' | 'no' | ''.
     *
     * @return string
     */
    public static function get_support_flag() {
        $flag = etn_get_option( self::SUPPORT_OPTION );

        return in_array( $flag, [ 'yes', 'no' ], true ) ? $flag : '';
    }

    /**
     * Create the event definition, or adopt the existing one.
     *
     * @return array|WP_Error Array with `event_name` on success.
     */
    public function provision() {
        $existing = $this->find_existing();

        if ( $existing ) {
            return $this->remember( $existing );
        }

        $response = $this->client->post(
            HubSpotClient::event_path( 'event-definitions' ),
            [
                'label'               => __( 'Eventin ticket purchased', 'eventin' ),
                'name'                => self::EVENT_NAME,
                'description'         => __( 'Fired when a ticket order completes in Eventin.', 'eventin' ),
                'primaryObject'       => 'CONTACT',
                'propertyDefinitions' => [
                    [
                        'label' => __( 'Event name', 'eventin' ),
                        'name'  => 'event_name',
                        'type'  => 'string',
                    ],
                    [
                        'label' => __( 'Event ID', 'eventin' ),
                        'name'  => 'event_id',
                        'type'  => 'number',
                    ],
                    [
                        'label' => __( 'Order ID', 'eventin' ),
                        'name'  => 'order_id',
                        'type'  => 'number',
                    ],
                    [
                        'label' => __( 'Ticket type', 'eventin' ),
                        'name'  => 'ticket_type',
                        'type'  => 'string',
                    ],
                    [
                        'label' => __( 'Order total', 'eventin' ),
                        'name'  => 'order_total',
                        'type'  => 'number',
                    ],
                    [
                        'label' => __( 'Contact type', 'eventin' ),
                        'name'  => 'contact_type',
                        'type'  => 'string',
                    ],
                ],
            ]
        );

        if ( is_wp_error( $response ) ) {
            $this->record_unavailable( $response );

            return $response;
        }

        $name = ! empty( $response['fullyQualifiedName'] ) ? $response['fullyQualifiedName'] : '';

        if ( ! $name ) {
            return new WP_Error(
                'eventin_hubspot_event_definition',
                __( 'HubSpot did not return a custom event name.', 'eventin' ),
                [ 'status' => 502 ]
            );
        }

        return $this->remember( $name );
    }

    /**
     * Send one occurrence for a contact.
     *
     * @param string $email      Contact email — the identifier HubSpot matches on.
     * @param array  $properties Occurrence properties.
     * @param int    $timestamp  Unix timestamp of the occurrence.
     *
     * @return true|WP_Error
     */
    public function send( $email, $properties, $timestamp = 0 ) {
        $event_name = self::get_event_name();

        if ( ! $event_name || ! $email ) {
            return new WP_Error(
                'eventin_hubspot_event_not_provisioned',
                __( 'The Eventin custom event has not been created in HubSpot yet.', 'eventin' ),
                [ 'status' => 400 ]
            );
        }

        $timestamp = $timestamp ? $timestamp : time();

        $response = $this->client->post(
            HubSpotClient::event_path( 'send' ),
            [
                'eventName'  => $event_name,
                'email'      => $email,
                // HubSpot expects ISO 8601 or epoch milliseconds.
                'occurredAt' => gmdate( 'c', $timestamp ),
                'properties' => $this->trim_properties( $properties ),
            ]
        );

        if ( is_wp_error( $response ) ) {
            $this->record_unavailable( $response );

            return $response;
        }

        return true;
    }

    /**
     * Look for an already-created definition so re-provisioning does not 409.
     *
     * @return string Fully qualified name, or empty string.
     */
    private function find_existing() {
        $response = $this->client->get( HubSpotClient::event_path( 'event-definitions' ) );

        if ( is_wp_error( $response ) ) {
            $this->record_unavailable( $response );

            return '';
        }

        $results = isset( $response['results'] ) && is_array( $response['results'] ) ? $response['results'] : [];

        foreach ( $results as $definition ) {
            if ( isset( $definition['name'] ) && self::EVENT_NAME === $definition['name'] ) {
                return isset( $definition['fullyQualifiedName'] ) ? $definition['fullyQualifiedName'] : '';
            }

            // Older portals only return the qualified name; match on the suffix.
            if ( isset( $definition['fullyQualifiedName'] )
                && substr( $definition['fullyQualifiedName'], -strlen( self::EVENT_NAME ) ) === self::EVENT_NAME ) {
                return $definition['fullyQualifiedName'];
            }
        }

        return '';
    }

    /**
     * Persist the resolved event name and mark the portal as supported.
     *
     * @param string $name Fully qualified event name.
     *
     * @return array
     */
    private function remember( $name ) {
        etn_update_option( self::NAME_OPTION, $name );
        etn_update_option( self::SUPPORT_OPTION, 'yes' );
        // Clear any earlier scope/tier verdict, or the modal renders "tracking
        // is active" and "needs more scopes" side by side after the admin fixes
        // the scopes and re-provisions.
        etn_update_option( self::REASON_OPTION, '' );

        return [ 'event_name' => $name ];
    }

    /**
     * Reason custom events are unavailable, recorded from a failed call.
     *
     * @return string 'tier' | 'scope' | ''
     */
    public static function get_reason() {
        $reason = etn_get_option( self::REASON_OPTION );

        return in_array( $reason, [ 'tier', 'scope' ], true ) ? $reason : '';
    }

    /**
     * Record why a call failed, when the failure is one of the two "custom
     * events are off for you" shapes.
     *
     * A missing scope is fixable on the HubSpot key and the token stays
     * valid for contacts, so support is left *unknown* rather than 'no' — the
     * next provision attempt after the scope is granted must be allowed to
     * succeed. A tier rejection is a hard no until the subscription changes.
     *
     * @param WP_Error $error Error.
     *
     * @return bool Whether the failure was classified (i.e. expected).
     */
    private function record_unavailable( $error ) {
        $data     = $error->get_error_data();
        $status   = isset( $data['status'] ) ? (int) $data['status'] : 0;
        $category = isset( $data['category'] ) ? $data['category'] : '';

        if ( ! in_array( $status, [ 402, 403 ], true ) ) {
            return false;
        }

        if ( 'MISSING_SCOPES' === $category ) {
            etn_update_option( self::SUPPORT_OPTION, '' );
            etn_update_option( self::REASON_OPTION, 'scope' );

            return true;
        }

        etn_update_option( self::SUPPORT_OPTION, 'no' );
        etn_update_option( self::REASON_OPTION, 'tier' );

        return true;
    }

    /**
     * HubSpot caps occurrence property values at 256 characters and 50 keys.
     *
     * @param array $properties Properties.
     *
     * @return array
     */
    private function trim_properties( $properties ) {
        $trimmed = [];

        foreach ( (array) $properties as $key => $value ) {
            if ( count( $trimmed ) >= 50 ) {
                break;
            }

            if ( is_array( $value ) || is_object( $value ) ) {
                continue;
            }

            // Numeric properties are declared as `number` in the definition —
            // keep them numeric rather than stringifying them through the
            // length cap, which only applies to text values.
            if ( is_int( $value ) || is_float( $value ) ) {
                $trimmed[ $key ] = $value;
                continue;
            }

            $trimmed[ $key ] = mb_substr( (string) $value, 0, 256 );
        }

        return $trimmed;
    }
}
