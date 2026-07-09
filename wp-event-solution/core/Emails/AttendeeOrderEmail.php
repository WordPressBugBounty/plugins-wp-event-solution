<?php

namespace Eventin\Emails;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Attendee\Attendee_Model;
use Etn\Core\Event\Event_Model;
use Eventin\Mails\Content;
use Eventin\Mails\Mailable;

/**
 * Attendee Order Email
 * 
 * @package eventin
 */
class AttendeeOrderEmail extends Mailable {
    /**
     * Email settings
     *
     * @var array
     */
    private $email_settings;

    /**
     * Store event object
     *
     * @var Event_Model
     */
    private $event;

    /**
     * Store attendees
     *
     * @var Attendee_Model
     */
    private $attendee;

    /**
     * Constructor for Admin Order Class
     *
     * @return  void
     */
    public function __construct( Event_Model $event, Attendee_Model $attendee ) {
        $this->email_settings = etn_get_email_settings( 'purchase_email' );
        $this->event          = $event;
        $this->attendee       = $attendee;
    }

    /**
     * Email subject
     *
     * @return  string
     */
    public function subject(): string {
        return $this->email_settings['subject'];
    }

    /**
     * Email content
     *
     * @return  string  email body
     */
    public function content(): string {
        $content = $this->prepare_content();

        return Content::get( 'attendee-order-email-template', [
            'content'       => $content,
            'attendee'      => $this->attendee,
            'event'         => $this->event
        ] );
    }

    /**
     * Prepare email content that need to send
     *
     * @return  string Email content
     */
    private function prepare_content() {
        $event      = $this->event;

        $post       = get_post( $event->id );
        $location   = get_post_meta( $event->id, 'etn_event_location', true );
        $address    = ! empty( $location['address'] ) ? $location['address'] : '';

        // Attendee details
        $attendee_name  = $this->attendee->etn_name;
        $attendee_email = $this->attendee->etn_email;

        // Format date and time according to WordPress settings
        $date_format     = get_option( 'date_format' );
        $time_format     = get_option( 'time_format' );
        $utc             = new \DateTimeZone( 'UTC' );
        $start_date      = wp_date( $date_format, strtotime( $event->etn_start_date ), $utc );
        $start_date_time = $event->etn_start_date . ' ' . $event->etn_start_time;
        $start_time      = wp_date( $time_format, strtotime( $start_date_time ), $utc );

        $placeholder = [
            '{%site_name%}' 	 => get_bloginfo( 'name' ),
            '{%site_link%}' 	 => site_url(),
            '{%site_logo%}' 	 => get_bloginfo('logo'),
            '{%event_title%}'    => $post->post_title,
            '{%event_date%}' 	 => $start_date,
            '{%event_time%}' 	 => $start_time,
            '{%event_location%}' => $address,
            '{%customer_name%}'  => $attendee_name,
            '{%customer_email%}' => $attendee_email,
            '{%add_ons%}'        => $this->format_add_ons(),
        ];

        $order_email_message = $this->email_settings['body'];

        $order_email_message = strtr( $order_email_message, $placeholder );

        return $order_email_message;
    }

    /**
     * Format this attendee's order add-ons for the {%add_ons%} placeholder.
     *
     * @return  string  Empty string when there are no add-ons.
     */
    private function format_add_ons() {
        // Each attendee's own selections (independent per attendee).
        $rows = $this->attendee->etn_option_selections;
        $rows = is_array( $rows ) ? $rows : [];

        if ( ! $rows ) {
            return '';
        }

        $lines = [];
        foreach ( $rows as $row ) {
            $lines[] = sprintf(
                '%s: %s &times; %s &mdash; %s',
                esc_html( $row['field_label'] ?? '' ),
                esc_html( $row['choice_value'] ?? '' ),
                (int) ( $row['qty'] ?? 1 ),
                esc_html( $row['line_total'] ?? '' )
            );
        }

        return implode( '<br />', $lines );
    }
}
