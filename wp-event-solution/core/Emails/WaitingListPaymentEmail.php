<?php

namespace Eventin\Emails;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Event\Event_Model;
use Eventin\Mails\Content;
use Eventin\Mails\Mailable;
use Eventin\Order\OrderModel;

/**
 * Waiting List Payment Email
 *
 * Sent to a customer whose order is on the waiting list to invite them to
 * complete payment via a signed direct payment link.
 *
 * @package eventin
 */
class WaitingListPaymentEmail extends Mailable {
    /**
     * @var OrderModel
     */
    private $order;

    /**
     * @var Event_Model
     */
    private $event;

    /**
     * @var string Signed preview URL (order_id + order_token query params).
     */
    private $payment_url;

    /**
     * @param OrderModel $order
     * @param string     $payment_url
     */
    public function __construct( OrderModel $order, string $payment_url ) {
        $this->order       = $order;
        $this->event       = new Event_Model( $order->event_id );
        $this->payment_url = $payment_url;
    }

    /**
     * @return string
     */
    public function subject(): string {
        return sprintf(
            /* translators: %s: event title */
            __( 'Your spot is ready — complete your payment for %s', 'eventin' ),
            $this->event->get_title()
        );
    }

    /**
     * @return string
     */
    public function content(): string {
        return Content::get( 'waiting-list-payment-email-template', [
            'order'       => $this->order,
            'event'       => $this->event,
            'payment_url' => $this->payment_url,
        ] );
    }
}
