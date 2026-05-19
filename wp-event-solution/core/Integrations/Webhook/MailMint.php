<?php

/**
 * Mail Mint integration
 *
 * @package Eventin
 */
namespace Eventin\Integrations\Webhook;

defined( 'ABSPATH' ) || exit;

use Eventin\Order\OrderModel;

/**
 * Mail Mint Webhook integration
 */
class MailMint implements WebhookIntegrationInterface {
    /**
     * Run action
     *
     * @return  void
     */
    public function run() {
        add_action( 'eventin_after_order_create', [ $this, 'send_data_to_mailmint' ], 10, 2 );
    }

    /**
     * Send purchaser data to Mail Mint webhook
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

        $send_to = $this->get_send_to( $event_id );

        if ( ! in_array( 'purchaser', $send_to, true ) ) {
            return;
        }

        $body = [
            'email'      => $order->customer_email,
            'first_name' => $order->customer_fname,
            'last_name'  => $order->customer_lname,
        ];

        $body = apply_filters( 'eventin_mailmint_purchaser_data', $body, $order, $attendees );

        $this->post_to_webhook( $this->get_webhook( $event_id ), $body, 'purchaser' );
    }

    /**
     * Send individual attendee data to Mail Mint webhook
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

        $send_to = $this->get_send_to( $event_id );

        if ( ! in_array( 'attendee', $send_to, true ) ) {
            return;
        }

        $full_name  = isset( $attendee['etn_name'] ) ? trim( (string) $attendee['etn_name'] ) : '';
        $name_parts = $full_name !== '' ? preg_split( '/\s+/', $full_name, 2 ) : [];
        $first_name = $name_parts[0] ?? '';
        $last_name  = $name_parts[1] ?? '';

        $body = [
            'email'      => $attendee['etn_email'],
            'first_name' => $first_name !== '' ? $first_name : $attendee['etn_email'],
            'last_name'  => $last_name,
        ];

        $body = apply_filters( 'eventin_mailmint_attendee_data', $body, $order, $attendee );

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
    public function send_data_to_mailmint( $order, $attendees ) {
        $this->send_data( $order, $attendees );

        $attendee_registration = 'on' === etn_get_option( 'attendee_registration' );

        if ( $attendee_registration ) {
            try {
                foreach ( $attendees as $attendee ) {
                    $this->send_attendee_data( $order, $attendee );
                }
            } catch ( \Exception $exception ) {
            }
        }
    }

    /**
     * Check Mail Mint enabled for event and webhook present
     *
     * @param   int  $event_id
     *
     * @return  bool
     */
    private function is_enabled( $event_id ) {
        $extension_status = etn_get_option( 'mail_mint_api' );
        $extension_on     = $extension_status && 'off' !== $extension_status;

        $event_enabled = 'yes' === get_post_meta( $event_id, 'mail_mint', true );
        $webhook       = get_post_meta( $event_id, 'mail_mint_webhook', true );

        return $extension_on && $event_enabled && ! empty( $webhook );
    }

    /**
     * Get webhook URL for event
     *
     * @param   int  $event_id
     *
     * @return  string
     */
    private function get_webhook( $event_id ) {
        return get_post_meta( $event_id, 'mail_mint_webhook', true );
    }

    /**
     * POST body to Mail Mint webhook with local-host SSL allowance and error logging
     *
     * @param   string  $url
     * @param   array   $body
     * @param   string  $context  purchaser|attendee
     *
     * @return  void
     */
    private function post_to_webhook( $url, $body, $context ) {
        $args = [
            'body'      => $body,
            'timeout'   => 15,
            'sslverify' => $this->should_verify_ssl( $url ),
        ];

        $response = wp_remote_post( $url, $args );

        if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
            return;
        }

        if ( is_wp_error( $response ) ) {
            error_log( sprintf( '[Eventin MailMint] %s POST error: %s', $context, $response->get_error_message() ) );
            return;
        }

        $code = wp_remote_retrieve_response_code( $response );

        if ( $code < 200 || $code >= 300 ) {
            error_log( sprintf(
                '[Eventin MailMint] %s POST non-2xx (%d): %s',
                $context,
                $code,
                wp_remote_retrieve_body( $response )
            ) );
        }
    }

    /**
     * Skip SSL verification for local development hosts (e.g. *.local, localhost)
     *
     * @param   string  $url
     *
     * @return  bool
     */
    private function should_verify_ssl( $url ) {
        $host = wp_parse_url( $url, PHP_URL_HOST );

        if ( ! $host ) {
            return true;
        }

        if ( 'localhost' === $host || '127.0.0.1' === $host ) {
            return false;
        }

        if ( substr( $host, -6 ) === '.local' || substr( $host, -5 ) === '.test' ) {
            return false;
        }

        return true;
    }

    /**
     * Get send_to recipients for event
     *
     * @param   int  $event_id
     *
     * @return  array
     */
    private function get_send_to( $event_id ) {
        $send_to = get_post_meta( $event_id, 'mail_mint_send_to', true );

        if ( empty( $send_to ) || ! is_array( $send_to ) ) {
            return [ 'purchaser', 'attendee' ];
        }

        return $send_to;
    }
}
