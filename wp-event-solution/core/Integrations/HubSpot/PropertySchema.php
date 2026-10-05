<?php
/**
 * HubSpot contact property schema
 *
 * @package Eventin
 */
namespace Eventin\Integrations\HubSpot;

defined( 'ABSPATH' ) || exit;

use WP_Error;

/**
 * Creates the Eventin property group and custom contact properties in HubSpot.
 *
 * Without these, an upserted contact carries no event context and the CRM side
 * is just a name and an email. Provisioning runs on connect and can be re-run
 * from the config modal; it is idempotent — properties that already exist come
 * back as a 409 conflict and are treated as success.
 */
class PropertySchema {
    /**
     * Object type these properties belong to.
     */
    const OBJECT_TYPE = 'contacts';

    /**
     * Property group all Eventin properties live in.
     */
    const GROUP_NAME = 'eventin';

    /**
     * Option flag recording that provisioning succeeded, and for which schema
     * revision. Bump SCHEMA_VERSION when properties are added so an existing
     * connection re-provisions instead of silently missing the new fields.
     */
    const PROVISIONED_OPTION = 'hubspot_properties_provisioned';

    /**
     * Schema revision.
     */
    const SCHEMA_VERSION = 1;

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
     * Property definitions sent to HubSpot.
     *
     * @return array
     */
    public static function definitions() {
        return [
            [
                'name'      => 'eventin_last_event_name',
                'label'     => __( 'Eventin: Last Event', 'eventin' ),
                'type'      => 'string',
                'fieldType' => 'text',
            ],
            [
                'name'      => 'eventin_last_event_id',
                'label'     => __( 'Eventin: Last Event ID', 'eventin' ),
                'type'      => 'number',
                'fieldType' => 'number',
            ],
            [
                'name'      => 'eventin_last_order_id',
                'label'     => __( 'Eventin: Last Order ID', 'eventin' ),
                'type'      => 'number',
                'fieldType' => 'number',
            ],
            [
                'name'      => 'eventin_last_order_total',
                'label'     => __( 'Eventin: Last Order Total', 'eventin' ),
                'type'      => 'number',
                'fieldType' => 'number',
            ],
            [
                'name'      => 'eventin_ticket_type',
                'label'     => __( 'Eventin: Ticket Type', 'eventin' ),
                'type'      => 'string',
                'fieldType' => 'text',
            ],
            [
                'name'      => 'eventin_registration_date',
                'label'     => __( 'Eventin: Registration Date', 'eventin' ),
                'type'      => 'datetime',
                'fieldType' => 'date',
            ],
            [
                'name'      => 'eventin_attendee_type',
                'label'     => __( 'Eventin: Contact Type', 'eventin' ),
                'type'      => 'enumeration',
                'fieldType' => 'select',
                'options'   => [
                    [
                        'label'        => __( 'Purchaser', 'eventin' ),
                        'value'        => 'purchaser',
                        'displayOrder' => 0,
                    ],
                    [
                        'label'        => __( 'Attendee', 'eventin' ),
                        'value'        => 'attendee',
                        'displayOrder' => 1,
                    ],
                ],
            ],
        ];
    }

    /**
     * Whether the current schema revision has been provisioned.
     *
     * @return bool
     */
    public static function is_provisioned() {
        return (int) etn_get_option( self::PROVISIONED_OPTION ) >= self::SCHEMA_VERSION;
    }

    /**
     * Create the group and every property.
     *
     * @return array|WP_Error {
     *     @type array $created Property names created this run.
     *     @type array $existing Property names already present.
     * }
     */
    public function provision() {
        $group = $this->ensure_group();

        if ( is_wp_error( $group ) ) {
            return $group;
        }

        $created  = [];
        $existing = [];

        foreach ( self::definitions() as $definition ) {
            $definition['groupName'] = self::GROUP_NAME;

            $response = $this->client->post(
                HubSpotClient::property_path( self::OBJECT_TYPE ),
                $definition
            );

            if ( ! is_wp_error( $response ) ) {
                $created[] = $definition['name'];
                continue;
            }

            if ( $this->is_conflict( $response ) ) {
                $existing[] = $definition['name'];
                continue;
            }

            return $response;
        }

        etn_update_option( self::PROVISIONED_OPTION, self::SCHEMA_VERSION );

        return [
            'created'  => $created,
            'existing' => $existing,
        ];
    }

    /**
     * Create the Eventin property group if it is missing.
     *
     * @return true|WP_Error
     */
    private function ensure_group() {
        $response = $this->client->post(
            HubSpotClient::property_path( self::OBJECT_TYPE, 'groups' ),
            [
                'name'         => self::GROUP_NAME,
                'label'        => __( 'Eventin', 'eventin' ),
                'displayOrder' => -1,
            ]
        );

        if ( ! is_wp_error( $response ) || $this->is_conflict( $response ) ) {
            return true;
        }

        return $response;
    }

    /**
     * A 409 means the property/group already exists — that is a success for an
     * idempotent provision, not a failure.
     *
     * @param WP_Error $error Error.
     *
     * @return bool
     */
    private function is_conflict( $error ) {
        $data = $error->get_error_data();

        return isset( $data['status'] ) && 409 === (int) $data['status'];
    }
}
