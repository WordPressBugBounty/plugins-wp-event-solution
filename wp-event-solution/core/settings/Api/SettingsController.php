<?php

/**
 * Settings Controller
 *
 * @package Eventin\Settings
 */
namespace Eventin\Settings\Api;

defined( 'ABSPATH' ) || exit;

use Eventin\Settings;
use WP_REST_Controller;
use WP_REST_Server;

/**
 * Settings Controller Class
 */
class SettingsController extends WP_REST_Controller {
    /**
     * Constructor for SettingsController
     *
     * @return void
     */
    public function __construct() {
        $this->namespace = 'eventin/v2';
        $this->rest_base = 'settings';
    }

    /**
     * Check if a given request has access to get items.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|boolean
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_item' ),
                    'args'                => array(),
                    'permission_callback' => array( $this, 'get_item_permissions_check' ),
                ),
                array(
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => array( $this, 'update_item' ),
                    'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
                    'permission_callback' => array( $this, 'update_item_permissions_check' ),
                ),
                'schema' => array( $this, 'get_public_item_schema' ),
            )
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/public',
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_public_item' ),
                    'args'                => array(),
                    'permission_callback' => array( $this, 'get_public_item_permissions_check' ),
                ),
            )
        );
    }

    /**
     * Check if a given request has access to get items.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|boolean
     */
    public function get_item_permissions_check( $request ) {
        return current_user_can( 'etn_manage_setting' )
                || current_user_can( 'etn_manage_event' );
    }

    /**
     * Check if a given request has access to update settings.
     *
     * Writes are restricted to users with the dedicated setting-management
     * capability (or site administrators); event-author roles must not be
     * able to overwrite payment credentials or other global options.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return bool
     */
    public function update_item_permissions_check( $request ) {
        return current_user_can( 'etn_manage_setting' ) || current_user_can( 'manage_options' );
    }

    /**
     * Get a collection of items.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_item( $request ) {
        $settings = Settings::get();

        $settings = apply_filters( 'eventin_settings', $settings );

        if ( ! current_user_can( 'etn_manage_setting' ) && ! current_user_can( 'manage_options' ) ) {
            $settings = $this->redact_secrets( $settings );
        }

        return rest_ensure_response( $settings );
    }

    /**
     * Strip secret-bearing fields from the settings payload.
     *
     * Event-author / vendor roles need parts of the settings payload to render
     * the dashboard (currency, extra_fields, attendee_registration, …) but must
     * never receive integration credentials. We strip a known list plus any
     * key whose suffix marks it as a credential.
     *
     * Integrations can extend the deny-list via `eventin_settings_secret_keys`
     * and `eventin_settings_secret_suffixes`.
     *
     * @param array $settings
     * @return array
     */
    protected function redact_secrets( $settings ) {
        if ( ! is_array( $settings ) ) {
            return $settings;
        }

        $secret_keys = apply_filters( 'eventin_settings_secret_keys', [
            // Eventin AI
            'eventin_ai_auth_key',
            // Google
            'google_api_key',
            'google_token',
            'google_meet_client_id',
            'google_meet_client_secret_key',
            // Zoom
            'zoom_client_id',
            'zoom_client_secret',
            'zoom_token',
            // Stripe
            'stripe_live_publishable_key',
            'stripe_live_secret_key',
            'etn_stripe_webhook_secret',
            // PayPal
            'paypal_client_id',
            'paypal_client_secret',
        ] );

        $secret_suffixes = apply_filters( 'eventin_settings_secret_suffixes', [
            '_secret',
            '_secret_key',
            '_publishable_key',
            '_token',
            '_password',
            '_auth_key',
            '_private_key',
            '_api_key',
        ] );

        foreach ( array_keys( $settings ) as $key ) {
            if ( in_array( $key, $secret_keys, true ) ) {
                unset( $settings[ $key ] );
                continue;
            }

            foreach ( $secret_suffixes as $suffix ) {
                if ( substr( $key, -strlen( $suffix ) ) === $suffix ) {
                    unset( $settings[ $key ] );
                    break;
                }
            }
        }

        return $settings;
    }

    /**
     * Update plugin settings
     *
     * @param   WP_Rest_Request  $request
     *
     * @return  WP_Rest_Response | WP_Error
     */
    public function update_item( $request ) {
        $params = $request->get_json_params();

        if ( empty( $params ) || ! is_array( $params ) ) {
            return new \WP_Error( 'invalid_data', __( 'Invalid settings data.', 'eventin' ), [ 'status' => 400 ] );
        }

        Settings::update( $params );

        return $this->get_item( $request );
    }

    /**
     * Get public settings
     *
     * @return  WP_Rest_Response | WP_Error
     */
    public function get_public_item( $request ) {
        $extra_fields = etn_get_option( 'extra_fields', [] ) ?: etn_get_option( 'attendee_extra_fields', [] );
        $extra_fields = array_values(array_filter($extra_fields, fn($v) => !is_null($v)));
        
        $items = [
            'extra_fields'             => $extra_fields,
            'striple_publishable_key'  => etn_get_option( 'stripe_live_publishable_key' ),
            'paypal_client_id'         => etn_get_option( 'paypal_client_id' ),
            'currency'                 => etn_currency(),
            'currency_symbol'          => etn_currency_symbol(),
            "paypal_status"            => etn_get_option( 'paypal_status' ),
            'surecart_status'          => etn_get_option( 'surecart_status' ),
            'local_payment_status'     => etn_get_option( 'local_payment_status' ),
            "attendee_registration"    => etn_get_option( 'attendee_registration' ),
            "reg_require_phone"        => etn_get_option( 'reg_require_phone' ),
            "reg_require_email"        => etn_get_option( 'reg_require_email' ),
            "enable_attendee_bulk"     => etn_get_option( 'enable_attendee_bulk' ),
            "add_to_cart_redirect"     => etn_get_option( 'add_to_cart_redirect','checkout' ),
            "order_thank_you_redirect" => etn_get_option( 'order_thank_you_redirect','eventin_thankyou' ),
            'etn_purchase_login_required' => etn_get_option( 'etn_purchase_login_required' ),
            'decimal_separator'        => etn_get_decimal_separator(),
            'thousand_separator'       => etn_get_thousand_separator(),
            'decimals'                 => etn_get_decimals(),
            'price_format'             => etn_get_price_format(),
            'currency_position'        => etn_get_currency_position(),
            'show_ticket_expiry_date'  => etn_get_option( 'show_ticket_expiry_date', false ),
            'default_extra_fields'     => etn_get_option( 'default_extra_fields' ),
            'show_phone_number'        => etn_get_option( 'show_phone_number', false ),
            'require_last_name'        => etn_get_option( 'require_last_name', false ),
            'require_phone_number'     => etn_get_option( 'require_phone_number', false ),
            'ticket_purchase_timer'    => etn_get_option( 'ticket_purchase_timer', 10 ),
            'ticket_purchase_timer_enable'   => etn_get_option( 'ticket_purchase_timer_enable', 'off' ),
        ];

        if ( function_exists( 'WC' ) ) {
            $items['wc_checkout_url'] = wc_get_checkout_url();
            $items['wc_cart_url']     = wc_get_cart_url();
        }

        return rest_ensure_response( $items );
    }

    /**
     * Check if a given request has access to get items.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|boolean
     */
    public function get_public_item_permissions_check( $request ) {
        $nonce = $request->get_header( 'X-Wp-Nonce' );
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return false;
        }
        return true;
    }
}