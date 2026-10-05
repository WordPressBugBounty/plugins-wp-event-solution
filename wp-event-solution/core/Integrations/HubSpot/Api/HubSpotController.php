<?php
/**
 * HubSpot REST controller
 *
 * @package Eventin
 */
namespace Eventin\Integrations\HubSpot\Api;

defined( 'ABSPATH' ) || exit;

use Eventin\Integrations\HubSpot\EventDefinition;
use Eventin\Integrations\HubSpot\HubSpotClient;
use Eventin\Integrations\HubSpot\PropertySchema;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Connection management for the HubSpot integration.
 *
 * The Service Key / private app token is a CRM-write credential, so it is never echoed back:
 * every response carries a masked hint and a boolean, never the stored value.
 * Writes require `etn_manage_addons` (the capability the extensions screen
 * already gates on); the read-only status route also allows `etn_manage_event`
 * because the per-event integration form renders from it.
 */
class HubSpotController extends WP_REST_Controller {
    /**
     * Constructor
     */
    public function __construct() {
        $this->namespace = 'eventin/v2';
        $this->rest_base = 'hubspot';
    }

    /**
     * Register routes
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/status',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_status' ],
                    'permission_callback' => [ $this, 'read_permissions_check' ],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/connect',
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'connect' ],
                    'permission_callback' => [ $this, 'manage_permissions_check' ],
                    'args'                => [
                        'token' => [
                            'type'              => 'string',
                            'required'          => true,
                            'sanitize_callback' => 'sanitize_text_field',
                        ],
                    ],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/disconnect',
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'disconnect' ],
                    'permission_callback' => [ $this, 'manage_permissions_check' ],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/provision-properties',
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'provision_properties' ],
                    'permission_callback' => [ $this, 'provision_permissions_check' ],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/custom-events/provision',
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'provision_custom_event' ],
                    'permission_callback' => [ $this, 'provision_permissions_check' ],
                ],
            ]
        );
    }

    /**
     * Only extension managers may change the connection.
     *
     * @param mixed $request Request.
     *
     * @return bool
     */
    public function manage_permissions_check( $request ) {
        return current_user_can( 'etn_manage_addons' );
    }

    /**
     * Provisioning also requires the card to be switched on.
     *
     * `is_extension_enabled()` existed but nothing consulted it, so turning the card
     * off still left these two routes making outbound calls with the stored token.
     * The guard sits here rather than in HubSpotClient::request() because connect()
     * legitimately verifies a token before the card has been turned on.
     *
     * @param mixed $request Request.
     *
     * @return bool
     */
    public function provision_permissions_check( $request ) {
        return $this->manage_permissions_check( $request ) && HubSpotClient::is_extension_enabled();
    }

    /**
     * Extension managers and event managers may read connection status.
     *
     * @param mixed $request Request.
     *
     * @return bool
     */
    public function read_permissions_check( $request ) {
        return current_user_can( 'etn_manage_addons' ) || current_user_can( 'etn_manage_event' );
    }

    /**
     * Connection status.
     *
     * @param mixed $request Request.
     *
     * @return WP_REST_Response
     */
    public function get_status( $request ) {
        return new WP_REST_Response( $this->build_status(), 200 );
    }

    /**
     * Validate and store a Service Key (or legacy private app token).
     *
     * Properties and the custom event definition are provisioned right away so
     * a successful connect leaves nothing else for the admin to click, but a
     * failure in either step does not undo the connection — the modal exposes
     * both as re-runnable actions.
     *
     * @param mixed $request Request.
     *
     * @return WP_REST_Response|WP_Error
     */
    public function connect( $request ) {
        $token = trim( (string) $request->get_param( 'token' ) );

        if ( '' === $token ) {
            return new WP_Error(
                'eventin_hubspot_missing_token',
                __( 'Enter your HubSpot Service Key.', 'eventin' ),
                [ 'status' => 400 ]
            );
        }

        $client   = new HubSpotClient( $token );
        $verified = $client->verify_token();

        if ( is_wp_error( $verified ) ) {
            return $this->rest_error( $verified );
        }

        etn_update_option( HubSpotClient::TOKEN_OPTION, $token );

        $warnings = [];
        $started  = microtime( true );

        $properties = ( new PropertySchema( $client ) )->provision();

        if ( is_wp_error( $properties ) ) {
            $warnings[] = $properties->get_error_message();
        }

        /**
         * Seconds to spend provisioning before deferring the optional steps.
         *
         * Connect makes up to ten sequential HubSpot calls, each with a 20-second
         * client timeout, inside one REST request — worst case runs past
         * max_execution_time or a gateway limit and the user sees a dead modal.
         * Properties are required, so they always run; timeline events are optional
         * and are retried on the next connect, so they are skipped once the budget
         * is gone.
         *
         * @param float $budget Seconds.
         */
        $budget = (float) apply_filters( 'eventin_hubspot_connect_budget', 15.0 );

        if ( ( microtime( true ) - $started ) >= $budget ) {
            $status             = $this->build_status();
            $status['warnings'] = $warnings;

            return new WP_REST_Response( $status, 200 );
        }

        // Timeline events are optional: a missing scope or an unsupported
        // subscription just leaves them off, silently — contacts still sync.
        ( new EventDefinition( $client ) )->provision();

        $status             = $this->build_status();
        $status['warnings'] = $warnings;

        return new WP_REST_Response( $status, 200 );
    }

    /**
     * Forget the token and everything derived from it.
     *
     * @param mixed $request Request.
     *
     * @return WP_REST_Response
     */
    public function disconnect( $request ) {
        etn_update_option( HubSpotClient::TOKEN_OPTION, '' );
        etn_update_option( PropertySchema::PROVISIONED_OPTION, 0 );
        etn_update_option( EventDefinition::NAME_OPTION, '' );
        etn_update_option( EventDefinition::SUPPORT_OPTION, '' );
        etn_update_option( EventDefinition::REASON_OPTION, '' );

        return new WP_REST_Response( $this->build_status(), 200 );
    }

    /**
     * Re-run property provisioning.
     *
     * @param mixed $request Request.
     *
     * @return WP_REST_Response|WP_Error
     */
    public function provision_properties( $request ) {
        if ( ! HubSpotClient::has_token() ) {
            return $this->not_connected();
        }

        $result = ( new PropertySchema() )->provision();

        if ( is_wp_error( $result ) ) {
            return $this->rest_error( $result );
        }

        $status           = $this->build_status();
        $status['result'] = $result;

        return new WP_REST_Response( $status, 200 );
    }

    /**
     * Create (or adopt) the custom event definition.
     *
     * @param mixed $request Request.
     *
     * @return WP_REST_Response|WP_Error
     */
    public function provision_custom_event( $request ) {
        if ( ! HubSpotClient::has_token() ) {
            return $this->not_connected();
        }

        $result = ( new EventDefinition() )->provision();

        if ( is_wp_error( $result ) ) {
            return $this->rest_error( $result );
        }

        $status           = $this->build_status();
        $status['result'] = $result;

        return new WP_REST_Response( $status, 200 );
    }

    /**
     * Status payload shared by every route.
     *
     * @return array
     */
    private function build_status() {
        $token = HubSpotClient::get_token();

        return [
            'connected'              => '' !== $token,
            'token_hint'             => HubSpotClient::mask_token( $token ),
            'extension_enabled'      => HubSpotClient::is_extension_enabled(),
            'properties_provisioned' => PropertySchema::is_provisioned(),
            'custom_events_support'  => EventDefinition::get_support_flag(),
            'custom_events_reason'   => EventDefinition::get_reason(),
            'custom_events_scopes'   => EventDefinition::REQUIRED_SCOPES,
            'custom_events_enabled'  => EventDefinition::is_supported() && '' !== EventDefinition::get_event_name(),
            'event_name'             => EventDefinition::get_event_name(),
        ];
    }

    /**
     * Not-connected error.
     *
     * @return WP_Error
     */
    private function not_connected() {
        return new WP_Error(
            'eventin_hubspot_not_connected',
            __( 'Connect HubSpot before running this action.', 'eventin' ),
            [ 'status' => 400 ]
        );
    }

    /**
     * Re-wrap an API WP_Error so the REST layer answers with a sane HTTP status
     * instead of relaying HubSpot's 401/403 as the WordPress response code.
     *
     * @param WP_Error $error Error.
     *
     * @return WP_Error
     */
    private function rest_error( $error ) {
        $data   = $error->get_error_data();
        $data   = is_array( $data ) ? $data : [];
        $status = isset( $data['status'] ) ? (int) $data['status'] : 500;

        // Flattening everything to 400 told the client "your request was wrong" even
        // when HubSpot was down, so the modal showed an outage and a bad token as the
        // same red toast. Keep the categories distinguishable.
        if ( in_array( $status, [ 401, 403 ], true ) ) {
            $message = __( 'HubSpot rejected the key. Check that it is valid and has the required scopes.', 'eventin' );
            $out     = 400;
        } elseif ( 429 === $status ) {
            $message = __( 'HubSpot is rate limiting this account. Try again in a moment.', 'eventin' );
            $out     = 429;
        } elseif ( $status >= 500 ) {
            $message = __( 'HubSpot is not responding right now. Try again in a few minutes.', 'eventin' );
            $out     = 502;
        } else {
            $message = $error->get_error_message();
            $out     = 400;
        }

        // Only the status is rewritten; `retry_after`, `category` and `errors` from
        // HubSpotClient::request() ride along untouched.
        $data['status'] = $out;

        return new WP_Error(
            $error->get_error_code(),
            $message,
            $data
        );
    }
}
