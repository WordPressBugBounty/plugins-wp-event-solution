<?php

namespace Eventin\Emails;

defined( 'ABSPATH' ) || exit;

use Eventin\Mails\Content;
use Eventin\Mails\Mailable;
use Eventin\Order\OrderModel;

class RefundConfirmationMail extends Mailable {

    /** @var OrderModel */
    private $order;

    /** @var array */
    private $refund;

    public function __construct( OrderModel $order, array $refund ) {
        $this->order  = $order;
        $this->refund = $refund;
    }

    public function subject(): string {
        return sprintf(
            /* translators: %d is the order ID */
            __( 'Refund processed for order #%d', 'eventin' ),
            (int) $this->order->id
        );
    }

    public function content(): string {
        return Content::get( 'refund-confirmation', [
            'order'  => $this->order,
            'refund' => $this->refund,
        ] );
    }
}
