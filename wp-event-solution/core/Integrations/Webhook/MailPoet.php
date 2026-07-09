<?php
/**
 * MailPoet integration (local API, not a webhook).
 *
 * @package Eventin
 */
namespace Eventin\Integrations\Webhook;

defined( 'ABSPATH' ) || exit;

use Eventin\Order\OrderModel;

/**
 * Subscribes purchaser/attendees to MailPoet list(s) on order completion.
 *
 * Implements WebhookIntegrationInterface to fit the existing registry loop
 * (the interface only requires run()). The "Webhook" namespace name is
 * semantically off for a local integration but no new abstraction is warranted.
 */
class MailPoet implements WebhookIntegrationInterface {
    /**
     * Hook into order creation.
     *
     * @return void
     */
    public function run() {
        add_action( 'eventin_after_order_create', [ $this, 'subscribe_on_order_create' ], 10, 2 );
    }

    /**
     * Dispatch purchaser + attendee subscriptions.
     *
     * @param OrderModel $order
     * @param array      $attendees
     * @return void
     */
    public function subscribe_on_order_create( $order, $attendees ) {
        $event_id = $order->event_id;

        if ( ! $this->is_enabled( $event_id ) ) {
            return;
        }

        $list_ids    = $this->get_list_ids( $event_id );
        $send_to     = $this->get_send_to( $event_id );
        $event_title = get_the_title( $event_id );

        if ( in_array( 'purchaser', $send_to, true ) ) {
            $this->subscribe(
                $order->customer_email,
                $order->customer_fname,
                $order->customer_lname,
                $list_ids,
                $event_title
            );
        }

        $attendee_registration = 'on' === etn_get_option( 'attendee_registration' );

        if ( in_array( 'attendee', $send_to, true ) && $attendee_registration ) {
            foreach ( $attendees as $attendee ) {
                if ( empty( $attendee['etn_email'] ) ) {
                    continue;
                }

                $full_name  = trim( (string) ( $attendee['etn_name'] ?? '' ) );
                $name_parts = $full_name !== '' ? preg_split( '/\s+/', $full_name, 2 ) : [];
                $first_name = $name_parts[0] ?? '';
                $last_name  = $name_parts[1] ?? '';

                $this->subscribe(
                    $attendee['etn_email'],
                    $first_name,
                    $last_name,
                    $list_ids,
                    $event_title
                );
            }
        }
    }

    /**
     * Add subscriber, or subscribe an already-existing one to the list(s).
     * Fail-safe: never lets a MailPoet error bubble into order completion.
     *
     * @param string $email
     * @param string $first_name
     * @param string $last_name
     * @param array  $list_ids
     * @param string $event_title
     * @return void
     */
    private function subscribe( $email, $first_name, $last_name, $list_ids, $event_title ) {
        if ( empty( $email ) || empty( $list_ids ) || ! class_exists( \MailPoet\API\API::class ) ) {
            return;
        }

        $api  = \MailPoet\API\API::MP( 'v1' );
        $opts = [
            'send_confirmation_email' => true,  // respect MailPoet sign-up confirmation settings
            'schedule_welcome_email'  => true,
        ];

        $subscriber = [
            'email'      => $email,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'tags'       => $event_title ? [ $event_title ] : [],
        ];

        try {
            $api->addSubscriber( $subscriber, $list_ids, $opts );
        } catch ( \MailPoet\API\MP\v1\APIException $e ) {
            if ( 12 === $e->getCode() ) {
                // Subscriber already exists: subscribe by email to the list(s).
                // Note: subscribeToLists has no tag param, so existing subscribers are not re-tagged.
                try {
                    $api->subscribeToLists( $email, $list_ids, $opts );
                } catch ( \Exception $inner ) {
                    $this->log_debug( $inner->getMessage() );
                }
            } else {
                // code 5 = invalid/stale list id, etc. — swallow so the order still completes.
                $this->log_debug( $e->getMessage() );
            }
        } catch ( \Exception $e ) {
            $this->log_debug( $e->getMessage() );
        }
    }

    /**
     * Enabled when the MailPoet extension is on, MailPoet present, event opted
     * in, and at least one list chosen.
     *
     * @param int $event_id
     * @return bool
     */
    private function is_enabled( $event_id ) {
        if ( ! class_exists( \MailPoet\API\API::class ) ) {
            return false;
        }

        $extension_status = etn_get_option( 'mailpoet_api' );
        $extension_on     = $extension_status && 'off' !== $extension_status;

        $event_enabled = 'yes' === get_post_meta( $event_id, 'mailpoet', true );

        return $extension_on && $event_enabled && ! empty( $this->get_list_ids( $event_id ) );
    }

    /**
     * Get chosen list IDs for the event.
     *
     * @param int $event_id
     * @return array
     */
    private function get_list_ids( $event_id ) {
        $list_ids = get_post_meta( $event_id, 'mailpoet_list_ids', true );

        if ( empty( $list_ids ) || ! is_array( $list_ids ) ) {
            return [];
        }

        return array_values( array_filter( array_map( 'strval', $list_ids ) ) );
    }

    /**
     * Get send_to recipients; default to both.
     *
     * @param int $event_id
     * @return array
     */
    private function get_send_to( $event_id ) {
        $send_to = get_post_meta( $event_id, 'mailpoet_send_to', true );

        if ( empty( $send_to ) || ! is_array( $send_to ) ) {
            return [ 'purchaser', 'attendee' ];
        }

        return $send_to;
    }

    /**
     * Log only under WP_DEBUG (matches MailMint).
     *
     * @param string $message
     * @return void
     */
    private function log_debug( $message ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( sprintf( '[Eventin MailPoet] %s', $message ) );
        }
    }
}
