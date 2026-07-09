<?php

namespace Eventin\Emails;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Event\Event_Model;
use Eventin\Mails\Content;
use Eventin\Mails\Mailable;
use Eventin\Order\OrderModel;

/**
 * Admin Order Email
 * 
 * @package eventin
 */
class AdminOrderEmail extends Mailable {
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
     * @var array
     */
    private $attendees;

    /**
     * Store order 
     *
     * @var OrderModel
     */
    private $order;

    /**
     * Constructor for Admin Order Class
     *
     * @return  void
     */
    public function __construct( OrderModel $order ) {
        $this->email_settings = etn_get_email_settings( 'purchase_email' );
        $this->order = $order;
        $this->event = new Event_Model( $this->order->event_id );
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

        return Content::get( 'admin-order-email-template', [
            'content'       => $content,
            'order'         => $this->order,
            'event'         => $this->event,
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

        // customer details

        $customer_fname = $this->order->customer_fname;
        $customer_email = $this->order->customer_email;

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
			'{%customer_name%}'  => $customer_fname,
			'{%customer_email%}' => $customer_email,
			'{%add_ons%}'        => $this->format_add_ons( $this->order->option_selections ),
		];

        $order_email_message = $this->email_settings['body'];

        $order_email_message = strtr( $order_email_message, $placeholder );

        return $order_email_message;
    }

    /**
     * Format add-on selections as an HTML list for the {%add_ons%} placeholder.
     *
     * @param   mixed  $rows  option_selections meta.
     * @return  string  Empty string when there are no add-ons.
     */
    private function format_add_ons( $rows ) {
        if ( ! is_array( $rows ) || ! $rows ) {
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
