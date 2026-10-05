<?php

/**
 * Zapier integration
 *
 * @package Eventin
 */
namespace Eventin\Integrations\Webhook;

defined( 'ABSPATH' ) || exit;

use Eventin\Order\OrderModel;

/**
 * Zapier Webhook integration
 */
class Zapier implements WebhookIntegrationInterface {
    /**
     * Run action
     *
     * @return  void
     */
    /**
     * Cron hook the queued deliveries fire on.
     */
    const DISPATCH_HOOK = 'eventin_zapier_dispatch';

    public function run() {
        add_action( 'eventin_after_order_create', [ $this, 'send_data_to_zapier' ], 10, 2 );
        add_action( self::DISPATCH_HOOK, [ $this, 'deliver' ], 10, 3 );
    }

    /**
     * Send purchaser data to the Zapier Catch Hook
     *
     * @param   OrderModel  $order
     * @param   array       $attendees
     *
     * @return  void
     */
    public function send_data( $order, $attendees = [] ) {
        $event_id = $order->event_id;

        if ( ! $this->is_enabled( $event_id ) ) {
            return;
        }

        if ( ! in_array( 'purchaser', $this->get_send_to( $event_id ), true ) ) {
            return;
        }

        $body = [
            'type'       => 'purchaser',
            'event_id'   => $event_id,
            'email'      => $order->customer_email,
            'first_name' => $order->customer_fname,
            'last_name'  => $order->customer_lname,
        ];

        $body = apply_filters( 'eventin_zapier_purchaser_data', $body, $order, $attendees );

        $this->post_to_webhook( $this->get_webhook( $event_id ), $body, 'purchaser' );
    }

    /**
     * Send individual attendee data to the Zapier Catch Hook
     *
     * @param   OrderModel  $order
     * @param   array       $attendee
     *
     * @return  void
     */
    public function send_attendee_data( OrderModel $order, $attendee ): void {
        $event_id = $order->event_id;

        if ( ! $this->is_enabled( $event_id ) ) {
            return;
        }

        if ( ! in_array( 'attendee', $this->get_send_to( $event_id ), true ) ) {
            return;
        }

        $full_name  = isset( $attendee['etn_name'] ) ? trim( (string) $attendee['etn_name'] ) : '';
        $name_parts = '' !== $full_name ? preg_split( '/\s+/', $full_name, 2 ) : [];

        $body = [
            'type'       => 'attendee',
            'event_id'   => $event_id,
            'email'      => $attendee['etn_email'],
            'first_name' => $name_parts[0] ?? $attendee['etn_email'],
            'last_name'  => $name_parts[1] ?? '',
        ];

        $body = apply_filters( 'eventin_zapier_attendee_data', $body, $order, $attendee );

        $this->post_to_webhook( $this->get_webhook( $event_id ), $body, 'attendee' );
    }

    /**
     * Dispatch on order create
     *
     * @param   OrderModel  $order
     * @param   array       $attendees
     *
     * @return  void
     */
    public function send_data_to_zapier( $order, $attendees ) {
        $this->send_data( $order, $attendees );

        if ( 'on' !== etn_get_option( 'attendee_registration' ) ) {
            return;
        }

        try {
            foreach ( $attendees as $attendee ) {
                $this->send_attendee_data( $order, $attendee );
            }
        } catch ( \Exception $exception ) {
        }
    }

    /**
     * Check Zapier is enabled for the event and a webhook is present
     *
     * @param   int  $event_id
     *
     * @return  bool
     */
    private function is_enabled( $event_id ) {
        // The card is Pro-only, but this sender ships in the free plugin, so
        // without this check a configured webhook keeps firing after Pro is
        // deactivated.
        if ( ! class_exists( 'Wpeventin_Pro' ) ) {
            return false;
        }

        $extension_status = etn_get_option( 'zapier_api' );
        $extension_on     = $extension_status && 'off' !== $extension_status;

        // Strict: the meta is normalised to yes/no on write, and "no" is truthy.
        $event_enabled = 'yes' === get_post_meta( $event_id, 'zapier', true );

        return $extension_on && $event_enabled && ! empty( $this->get_webhook( $event_id ) );
    }

    /**
     * Get the Catch Hook URL for the event
     *
     * @param   int  $event_id
     *
     * @return  string
     */
    private function get_webhook( $event_id ) {
        return get_post_meta( $event_id, 'zapier_webhook', true );
    }

    /**
     * Get the recipients configured for the event
     *
     * An empty array means "send nothing", so only unset falls back to both.
     *
     * @param   int  $event_id
     *
     * @return  array
     */
    private function get_send_to( $event_id ) {
        if ( ! metadata_exists( 'post', $event_id, 'zapier_send_to' ) ) {
            return [ 'purchaser', 'attendee' ];
        }

        $send_to = get_post_meta( $event_id, 'zapier_send_to', true );

        return is_array( $send_to ) ? $send_to : [];
    }

    /**
     * Queue a delivery instead of posting inline
     *
     * A slow Catch Hook must not hold up checkout: one purchaser plus four
     * attendees on a stalled endpoint would otherwise add up to five sequential
     * timeouts before order creation returns. Hand each payload to cron and let
     * the order finish immediately.
     *
     * @param   string  $url
     * @param   array   $body
     * @param   string  $context  purchaser|attendee
     *
     * @return  void
     */
    private function post_to_webhook( $url, $body, $context ) {
        $url = $this->validate_webhook( $url );

        if ( ! $url ) {
            return;
        }

        // WordPress drops a single event that repeats an already-scheduled
        // hook+args pair within ten minutes. Two attendees can legitimately
        // share a payload, so give each dispatch its own id.
        wp_schedule_single_event(
            time(),
            self::DISPATCH_HOOK,
            [ $url, $body, $context . ':' . wp_generate_uuid4() ]
        );
    }

    /**
     * POST the payload to the Catch Hook as JSON
     *
     * Runs on cron, off the checkout request.
     *
     * @param   string  $url
     * @param   array   $body
     * @param   string  $context  purchaser|attendee, suffixed with a dispatch id
     *
     * @return  void
     */
    public function deliver( $url, $body, $context ) {
        // Re-validate: these arguments have been sitting in the cron option
        // since they were queued.
        $url = $this->validate_webhook( $url );

        if ( ! $url || ! is_array( $body ) ) {
            return;
        }

        $response = wp_safe_remote_post( $url, [
            // Zapier's Catch Hook reads either encoding, but the UI promises
            // JSON, so send JSON rather than the default form encoding.
            'body'    => wp_json_encode( $body ),
            'headers' => [ 'Content-Type' => 'application/json' ],
            'timeout' => 15,
        ] );

        if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
            return;
        }

        if ( is_wp_error( $response ) ) {
            error_log( sprintf( '[Eventin Zapier] %s POST error: %s', $context, $response->get_error_message() ) );
            return;
        }

        $code = wp_remote_retrieve_response_code( $response );

        if ( $code < 200 || $code >= 300 ) {
            error_log( sprintf(
                '[Eventin Zapier] %s POST non-2xx (%d): %s',
                $context,
                $code,
                wp_remote_retrieve_body( $response )
            ) );
        }
    }

    /**
     * Validate an outbound Catch Hook URL
     *
     * The URL is per-event post meta, so whoever can edit the event chooses
     * where this server-side request goes - the same primitive fixed for the
     * FluentCRM webhook in 4.1.20. Values stored by earlier versions were never
     * checked, so require https and let WordPress refuse private and loopback
     * targets.
     *
     * @param   mixed  $url
     *
     * @return  string  Empty string when the URL is unusable.
     */
    private function validate_webhook( $url ) {
        $url = esc_url_raw( (string) $url, [ 'https' ] );

        return ( $url && wp_http_validate_url( $url ) ) ? $url : '';
    }
}
