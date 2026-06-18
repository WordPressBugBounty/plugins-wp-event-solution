<?php

namespace Eventin\Emails;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Event\Event_Model;
use Eventin\Mails\Content;
use Eventin\Mails\Mailable;

/**
 * Waiting List Confirmation Email
 *
 * Sent to the purchaser and each attendee right after an order is added to an
 * event's waiting list, confirming their spot in the queue. Contains the event
 * name and date.
 *
 * @package eventin
 */
class WaitingListConfirmationEmail extends Mailable {
    /**
     * @var Event_Model
     */
    private $event;

    /**
     * @var string Recipient display name used in the greeting.
     */
    private $recipient_name;

    /**
     * @param Event_Model $event
     * @param string      $recipient_name
     */
    public function __construct( Event_Model $event, string $recipient_name = '' ) {
        $this->event          = $event;
        $this->recipient_name = $recipient_name;
    }

    /**
     * @return string
     */
    public function subject(): string {
        return sprintf(
            /* translators: %s: event title */
            __( 'You\'re on the waiting list for %s', 'eventin' ),
            $this->event->get_title()
        );
    }

    /**
     * @return string
     */
    public function content(): string {
        return Content::get( 'waiting-list-confirmation-email-template', [
            'event'          => $this->event,
            'recipient_name' => $this->recipient_name,
        ] );
    }
}
