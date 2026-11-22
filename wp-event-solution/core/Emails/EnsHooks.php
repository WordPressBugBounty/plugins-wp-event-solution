<?php

namespace Eventin\Emails;

defined( 'ABSPATH' ) || exit;

use Eventin\Mails\Content;

/**
 * Attendee Order Email
 * 
 * @package eventin
 */
class EnsHooks {

    private $attendee;
    private $event;
    private $order;

    /**
     * Attendee name for the email being rendered, reused by the subject filter.
     *
     * @var string
     */
    private $attendee_name = '';

    /**
     * Recipient name for the waiting-list email (attendee or customer), reused by the subject filter.
     *
     * @var string
     */
    private $recipient_name = '';

    /**
     * Trigger, receiver, payload and position of the email being rendered.
     *
     * @var array
     */
    private $context = [];

    /**
     * Plain-text variable values of the email being rendered, reused by the subject filter.
     *
     * @var array
     */
    private $subject_values = [];
    /**
     * Store email body
     *
     * @var string
     */
     public $email_body;

    /**
     * Constructor for Admin Order Class
     *
     * @return  void
     */
    public function __construct() {
        add_filter( 'notification_sdk_email_message', array($this, 'get_latest_email_content'), 10, 6 );
        add_filter( 'notification_sdk_to_emails', array($this, 'get_to_attendee_emails'), 10, 3 );
        add_filter( 'wp_mail', array($this, 'replace_attendee_placeholder_in_subject') );
        add_filter( 'eventin_email_headers', array($this, 'default_from_header') );
    }

    /**
     * Get latest email content
     *
     * @param string $action_name
     * @param string $message
     * @param array  $action_data
     *
     * @return string|false
     */
    public function get_latest_email_content( $message, $receiver_type, $action_name, $action_data, $count, $node_data = [] ) {
        // Reset per-call state so a missing/invalid id never reuses the previous email's object.
        $this->attendee      = null;
        $this->event         = null;
        $this->order          = null;
        $this->attendee_name  = '';
        $this->recipient_name = '';
        $this->context        = compact( 'receiver_type', 'action_name', 'action_data', 'count' );

        // Step saved as Custom: the admin's text only, inside header and footer.
        // A step with no saved choice (every flow made before this setting)
        // falls through and sends exactly what it sent before.
        if ( 'custom' === EmailDesign::mode( $node_data ) && EmailDesign::is_known( $action_name ) ) {
            $attendee_id = AutomationPlaceholders::recipient_attendee_id( $action_name, $receiver_type, $action_data, $count );
            if ( $attendee_id ) {
                $this->attendee = new \Etn\Core\Attendee\Attendee_Model( $attendee_id );
            }

            return $this->replace_attendee_placeholders( Content::get( 'custom-body-email-template', [ 'content' => $message ] ) );
        }

        if($action_name == 'event_ticket_purchase' && $receiver_type == 'attendee_email') {
            $this->email_body = $message;
            if(isset($action_data['attendee_id']) && !empty($action_data['attendee_id'][$count])) {
                $this->attendee = new \Etn\Core\Attendee\Attendee_Model( $action_data['attendee_id'][$count] );
            }
            
            if(isset($action_data['event_id']) && !empty($action_data['event_id'])) {
                $this->event = new \Etn\Core\Event\Event_Model( $action_data['event_id'] );
            }

            return $this->replace_attendee_placeholders( $this->content() );
        }
        elseif($action_name == 'event_ticket_purchase' && ($receiver_type == 'customer_email' || $receiver_type == 'admin_email')) {
            $this->email_body = $message;
            if(isset($action_data['order_id']) && !empty($action_data['order_id'])) {
                $this->order = new \Eventin\Order\OrderModel( $action_data['order_id'] );
            }
            
            if(isset($action_data['event_id']) && !empty($action_data['event_id'])) {
                $this->event = new \Etn\Core\Event\Event_Model( $action_data['event_id'] );
            }

            return $this->replace_attendee_placeholders( $this->email_content_for_customer_and_admin() );
        }
        elseif($action_name == 'event_rsvp_email') {
            $this->email_body = $message;

            return $this->replace_attendee_placeholders( $this->email_body );
        }
        elseif($action_name == 'event_reminder_email' && $receiver_type == 'attendee_email') {
            $this->email_body = $message;

            if(empty($action_data['attendee_id'])) {
                $action_data['attendee_id'] = $this->get_to_attendee_ids($action_data);
            }

            if(isset($action_data['attendee_id']) && !empty($action_data['attendee_id']) && !empty($action_data['attendee_id'][$count])) {
                $this->attendee = new \Etn\Core\Attendee\Attendee_Model( $action_data['attendee_id'][$count] );
            }
            
            if(isset($action_data['event_id']) && !empty($action_data['event_id'])) {
                $this->event = new \Etn\Core\Event\Event_Model( $action_data['event_id'] );
            }

            return $this->replace_attendee_placeholders( $this->get_content_for_attendee_reminder_email() );
        }
        elseif($action_name == 'send_certificate' && $receiver_type == 'attendee_email') {
            $this->email_body = $message;

            if(empty($action_data['attendee_id'])) {
                $action_data['attendee_id'] = $this->get_to_attendee_ids($action_data);
            }

            if(isset($action_data['attendee_id']) && !empty($action_data['attendee_id']) && !empty($action_data['attendee_id'][$count])) {
                $this->attendee = new \Etn\Core\Attendee\Attendee_Model( $action_data['attendee_id'][$count] );
            }

            if(isset($action_data['event_id']) && !empty($action_data['event_id'])) {
                $this->event = new \Etn\Core\Event\Event_Model( $action_data['event_id'] );
            }

            return $this->replace_attendee_placeholders( $this->get_certificate_email_content() );
        }
        elseif($action_name == ReminderAutomation::TRIGGER && $receiver_type == 'attendee_email') {
            // On-demand reminder. Same rendering as the scheduled reminder above —
            // it is the same email, only the moment it is sent differs.
            $this->email_body = $message;

            if(empty($action_data['attendee_id'])) {
                $action_data['attendee_id'] = $this->get_to_attendee_ids($action_data);
            }

            if(isset($action_data['attendee_id']) && !empty($action_data['attendee_id']) && !empty($action_data['attendee_id'][$count])) {
                $this->attendee = new \Etn\Core\Attendee\Attendee_Model( $action_data['attendee_id'][$count] );
            }

            if(isset($action_data['event_id']) && !empty($action_data['event_id'])) {
                $this->event = new \Etn\Core\Event\Event_Model( $action_data['event_id'] );
            }

            return $this->replace_attendee_placeholders( $this->get_content_for_attendee_reminder_email() );
        }
        elseif($action_name == 'send_email_to_all_attendees' && $receiver_type == 'attendee_email') {
            $this->email_body = $message;

            // Fires with empty attendee arrays, so rebuild the ids the same way
            // get_to_attendee_emails() does to stay index-aligned by $count.
            if(empty($action_data['attendee_id'])) {
                $action_data['attendee_id'] = $this->get_to_attendee_ids($action_data);
            }

            if(!empty($action_data['attendee_id'][$count])) {
                $this->attendee = new \Etn\Core\Attendee\Attendee_Model( $action_data['attendee_id'][$count] );
            }

            return $this->replace_attendee_placeholders( $this->email_body );
        }
        elseif($action_name == OrderNoticeAutomation::WAITING_LIST_TRIGGER && ($receiver_type == 'customer_email' || $receiver_type == 'attendee_email')) {
            if($receiver_type == 'attendee_email' && !empty($action_data['attendee_id'][$count])) {
                $this->attendee = new \Etn\Core\Attendee\Attendee_Model( $action_data['attendee_id'][$count] );
            }

            // Local first: Post_Model has __get() without __isset().
            $recipient_name = $this->attendee ? (string) $this->attendee->etn_name : (string) ( $action_data['customer_name'] ?? '' );
            $this->recipient_name = $recipient_name;

            if(empty($action_data['event_id'])) {
                return $this->replace_attendee_placeholders( $message );
            }

            $content = Content::get( 'waiting-list-confirmation-email-template', [
                'event'          => new \Etn\Core\Event\Event_Model( $action_data['event_id'] ),
                'recipient_name' => $recipient_name,
                'content'        => strtr( $message, [ '{%recipient_name%}' => esc_html( $recipient_name ) ] ),
            ] );

            return $this->replace_attendee_placeholders( $content );
        }
        elseif(($action_name == OrderNoticeAutomation::REFUND_TRIGGER || $action_name == OrderNoticeAutomation::PARTIAL_REFUND_TRIGGER) && $receiver_type == 'customer_email') {
            $refund = null;

            if(!empty($action_data['order_id']) && isset($action_data['refund_id'])) {
                $refund = ( new \Eventin\Refund\RefundModel() )->find( $action_data['order_id'], $action_data['refund_id'] );
            }

            if(!$refund) {
                return $this->replace_attendee_placeholders( $message );
            }

            $content = Content::get( 'refund-confirmation', [
                'order'   => new \Eventin\Order\OrderModel( $action_data['order_id'] ),
                'refund'  => $refund,
                'content' => $message,
            ] );

            return $this->replace_attendee_placeholders( $content );
        }

        elseif($action_name == OrderNoticeAutomation::PAYMENT_LINK_TRIGGER && $receiver_type == 'customer_email') {
            if(empty($action_data['order_id']) || 'etn-order' !== get_post_type($action_data['order_id'])) {
                return $this->replace_attendee_placeholders( $message );
            }

            $order = new \Eventin\Order\OrderModel( $action_data['order_id'] );

            return $this->replace_attendee_placeholders( Content::get( 'waiting-list-payment-email-template', [
                'order'       => $order,
                'event'       => new \Etn\Core\Event\Event_Model( $order->event_id ),
                'payment_url' => (string) ( $action_data['payment_link'] ?? '' ),
                'content'     => $message,
            ] ) );
        }

        return $this->replace_attendee_placeholders( $message );
    }

    /**
     * Replace attendee placeholders in an automation email body.
     *
     * The SDK only substitutes flat payload scalars, so per-attendee values are
     * resolved here. Called on every return path so receivers without an
     * attendee (customer / admin) empty the token instead of shipping it.
     *
     * @param   string  $content
     *
     * @return  string
     */
    private function replace_attendee_placeholders( $content ) {
        // Local first: Post_Model has __get() without __isset().
        $attendee_name = $this->attendee ? (string) $this->attendee->etn_name : '';

        $this->attendee_name = $attendee_name;

        $replace = [
            '{%attendee_name%}' => esc_html( $attendee_name ),
            '{{attendee_name}}' => esc_html( $attendee_name ),
        ];

        // Every other variable of this trigger (order, ticket, links …). A value
        // this receiver has no data for becomes empty instead of a raw token.
        $this->subject_values = [];
        if ( $this->context ) {
            $values = AutomationPlaceholders::values(
                $this->context['action_name'],
                $this->context['receiver_type'],
                $this->context['action_data'],
                $this->context['count']
            );

            // All known keys, not only this trigger's: a body copied from another
            // flow must not mail a raw token.
            foreach ( array_keys( AutomationPlaceholders::labels() ) as $key ) {
                if ( 'attendee_name' === $key ) {
                    continue;
                }

                $value = $values[ $key ] ?? '';
                $html  = $this->email_value( $key, $value );

                $replace[ '{%' . $key . '%}' ] = $html;
                $replace[ '{{' . $key . '}}' ] = $html;

                $this->subject_values[ $key ] = $value;
            }
        }

        return strtr( $content, $replace );
    }

    /**
     * HTML for one variable in an email body.
     *
     * Links are clickable. Ticket, certificate and edit links keep the short
     * link text the certificate email already used.
     *
     * @param   string  $key    Variable key.
     * @param   string  $value  Plain value.
     *
     * @return  string
     */
    private function email_value( $key, $value ) {
        if ( '' === $value || ! in_array( $key, AutomationPlaceholders::LINK_KEYS, true ) ) {
            return esc_html( $value );
        }

        $texts = [
            'ticket_link'      => __( 'Download Ticket', 'wp-event-solution' ),
            'certificate_link' => __( 'Download Certificate', 'wp-event-solution' ),
            'edit_info_link'   => __( 'Edit Information', 'wp-event-solution' ),
            'payment_link'     => __( 'View Order & Pay', 'wp-event-solution' ),
        ];

        return sprintf( '<a href="%s">%s</a>', esc_url( $value ), esc_html( $texts[ $key ] ?? $value ) );
    }

    /**
     * Give an automation email with no From Address the site's admin email.
     *
     * With no From header WordPress uses wordpress@<site domain>, which mail
     * servers reject on a local or IP-only site ("Invalid address (From)").
     * Only automation emails being sent right now, and only when the flow has
     * no From of its own.
     *
     * @param   array  $headers  Email headers.
     *
     * @return  array
     */
    public function default_from_header( $headers ) {
        if ( empty( $this->context ) || ! is_array( $headers ) || preg_grep( '/^\s*From:/i', $headers ) ) {
            return $headers;
        }

        $email = sanitize_email( get_option( 'admin_email' ) );
        if ( ! is_email( $email ) ) {
            return $headers;
        }

        $name      = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
        $headers[] = sprintf( 'From: %s <%s>', $name, $email );

        return $headers;
    }

    /**
     * Replace the attendee name placeholder in an automation email subject.
     *
     * The SDK has no subject filter, so the token reaches wp_mail() intact. The
     * message filter runs first and has already resolved the attendee. Gated on
     * the token and cleared after, so other site mail is untouched.
     *
     * @param   array  $args  wp_mail() arguments.
     *
     * @return  array
     */
    public function replace_attendee_placeholder_in_subject( $args ) {
        // An automation email is being sent right now (its body was just built).
        $is_automation = ! empty( $this->context );
        $this->context = [];

        if ( empty( $args['subject'] ) || ( ! $is_automation && false === strpos( $args['subject'], '{%' ) && false === strpos( $args['subject'], '{{' ) ) ) {
            return $args;
        }

        $attendee_name  = wp_strip_all_tags( $this->attendee_name );
        $recipient_name = wp_strip_all_tags( $this->recipient_name );

        $replace = [
            '{%attendee_name%}'  => $attendee_name,
            '{{attendee_name}}'  => $attendee_name,
            '{%recipient_name%}' => $recipient_name,
            '{{recipient_name}}' => $recipient_name,
        ];
        foreach ( $this->subject_values as $key => $value ) {
            $replace[ '{%' . $key . '%}' ] = wp_strip_all_tags( $value );
            $replace[ '{{' . $key . '}}' ] = wp_strip_all_tags( $value );
        }

        $args['subject'] = strtr( $args['subject'], $replace );

        // A subject is plain text: event titles come HTML-escaped ("AI &amp; ML").
        if ( $is_automation ) {
            $args['subject'] = html_entity_decode( $args['subject'], ENT_QUOTES, 'UTF-8' );
        }

        $this->attendee_name  = '';
        $this->recipient_name = '';
        $this->subject_values = [];

        return $args;
    }

    public function get_to_attendee_emails( $to_emails, $action_data, $action_name ) {
        if($action_name == 'event_reminder_email' || $action_name == 'send_email_to_all_attendees' || $action_name == 'send_certificate' || $action_name == ReminderAutomation::TRIGGER) {
            // Honor an explicitly targeted recipient list. The per-attendee
            // "Send Certificate" action passes a single attendee_email, so send
            // to only that attendee instead of re-querying every success
            // attendee of the event. Stays index-aligned with the matching
            // attendee_id list carried in the same action_data.
            if ( $action_name == 'send_certificate' && ! empty( $action_data['attendee_email'] ) && is_array( $action_data['attendee_email'] ) ) {
                return array_values( array_filter( $action_data['attendee_email'] ) );
            }

            $event_id = $action_data['event_id'];

            $args = array(
                'post_type'         => 'etn-attendee',
                'post_status'       => 'any',
                'posts_per_page'    => -1,
                
                'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                    array(
                        'key'     => 'etn_event_id',
                        'value'   => $event_id,
                        'compare' => '=',
                    ),
                    array(
                        'key'     => 'etn_status',
                        'value'   => 'success',
                        'compare' => '=',
                    ),
                ),
            );

            $attendees = get_posts( $args );
            $attendee_emails = [];
            
            if ( $attendees ) {
                foreach( $attendees as $attendee ) {
                    $attendee_emails[] = $attendee->etn_email;
                }
            }

            return $attendee_emails;
        }

        return $to_emails;
    }


    public function get_to_attendee_ids( $action_data ) {
            $event_id = $action_data['event_id'];

            $args = array(
                'post_type'         => 'etn-attendee',
                'post_status'       => 'any',
                'posts_per_page'    => -1,
                
                // Mirror get_to_attendee_emails() exactly (same status filter and
                // ordering) so this list stays index-aligned with the recipient
                // list the SDK iterates by $count — otherwise per-attendee data
                // (e.g. certificate/ticket links) resolves to the wrong attendee.
                'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                    array(
                        'key'     => 'etn_event_id',
                        'value'   => $event_id,
                        'compare' => '=',
                    ),
                    array(
                        'key'     => 'etn_status',
                        'value'   => 'success',
                        'compare' => '=',
                    ),
                ),
            );

            $attendees = get_posts( $args );
            $attendee_ids = [];
            
            if ( $attendees ) {
                foreach( $attendees as $attendee ) {
                    $attendee_ids[] = $attendee->ID;
                }
            }

            return $attendee_ids;
        }

    /**
     * Email content
     *
     * @return  string  email body
     */
    public function content(): string {
        $content = $this->email_body;

        return Content::get( 'attendee-order-email-template', [
            'content'       => $content,
            'attendee'      => $this->attendee,
            'event'         => $this->event
        ] );
    }

    /**
     * Get content for attendee reminder email
     *
     * @return  string  email body
     */
    public function get_content_for_attendee_reminder_email(): string {
        $content = $this->email_body;

        return Content::get( 'attendee-event-reminder-email-template', [
            'content'       => $content,
            'attendee'      => $this->attendee,
            'event'         => $this->event
        ] );
    }

    /**
     * Get content for attendee certificate email
     *
     * @return  string  email body
     */
    public function get_certificate_email_content(): string {
        $content = $this->email_body;

        // Replace per-attendee link placeholders. These are attendee-specific
        // (carry the attendee id + edit token), so they can't be resolved by the
        // SDK's event-level placeholder pass — they are filled here, where the
        // attendee/event for this recipient ($count) is already loaded.
        // Render each placeholder as a clickable anchor (labeled link) rather than
        // a raw URL string, so recipients get "Download Certificate" / "Download
        // Ticket" hyperlinks instead of long bare URLs.
        $certificate_link = '';
        $ticket_link      = '';

        if ( $this->attendee && $this->event ) {
            $certificate_url = site_url( "etn-attendee?etn_action=download_certificate&attendee_id={$this->attendee->id}&event_id={$this->event->id}&etn_info_edit_token={$this->attendee->etn_info_edit_token}" );
            $ticket_url      = site_url( "etn-attendee?etn_action=download_ticket&attendee_id={$this->attendee->id}&etn_info_edit_token={$this->attendee->etn_info_edit_token}" );

            $certificate_link = sprintf( '<a href="%s">%s</a>', esc_url( $certificate_url ), esc_html__( 'Download Certificate', 'wp-event-solution' ) );
            $ticket_link      = sprintf( '<a href="%s">%s</a>', esc_url( $ticket_url ), esc_html__( 'Download Ticket', 'wp-event-solution' ) );
        }

        $content = strtr( $content, [
            '{%certificate_link%}' => $certificate_link,
            '{%ticket_link%}'      => $ticket_link,
        ] );

        return Content::get( 'attendee-certificate-email-template', [
            'content'       => $content,
            'attendee'      => $this->attendee,
            'event'         => $this->event
        ] );
    }

    /**
     * Email content for customer
     *
     * @return  string  email body
     */
    public function email_content_for_customer_and_admin(): string {
        $content = $this->email_body;

        return Content::get( 'admin-order-email-template', [
            'content'       => $content,
            'order'         => $this->order,
            'event'         => $this->event,
        ] );
    }
}
