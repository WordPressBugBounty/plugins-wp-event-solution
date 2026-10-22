<?php

/**
 * Event Exporter Class
 *
 * @package Eventin
 */
namespace Eventin\Event;

defined( 'ABSPATH' ) || exit;

use Eventin\Exporter\ExporterFactory;
use Eventin\Exporter\PostExporterInterface;

/**
 * Class Event Exporter
 *
 * Export Event Data
 */
class EventExporter implements PostExporterInterface {
    /**
     * Store file name
     *
     * @var string
     */
    private $file_name = 'event-data';

    /**
     * Store event data
     *
     * @var array
     */
    private $data;

    /**
     * Export event data
     *
     * @return void
     */
    public function export( $data, $format ) {
        $this->data = $data;

        $rows      = $this->prepare_data();
        $columns   = $this->get_columns();
        $file_name = $this->file_name;

        $exporter = ExporterFactory::get_exporter( $format );

        $exporter->export( $rows, $columns, $file_name );
    }

    /**
     * Prepare data to export
     *
     * @return  array
     */
    private function prepare_data() {
        $ids           = $this->data;
        $exported_data = [];

        foreach ( $ids as $id ) {
            $categories = get_the_terms( $id, 'etn_category' );
            $categories = $categories ? array_column( $categories, 'name' ) : [];
            $tags       = get_the_terms( $id, 'etn_tags' );
            $tags       = $tags ? array_column( $tags, 'name' ) : [];
            $post       = get_post( $id );

            $schedule_data = [
                'id'                      => $id,
                'title'                   => $post->post_title,
                'status'                  => $post->post_status,
                'description'             => $post->post_content,
                'start_date'              => get_post_meta( $id, 'etn_start_date', true ),
                'end_date'                => get_post_meta( $id, 'etn_end_date', true ),
                'start_time'              => get_post_meta( $id, 'etn_start_time', true ),
                'end_time'                => get_post_meta( $id, 'etn_end_time', true ),
                'timezone'                => get_post_meta( $id, 'event_timezone', true ),
                'event_type'              => get_post_meta( $id, 'event_type', true ),
                'location'                => get_post_meta( $id, 'etn_event_location', true ),
                'meeting_link'            => get_post_meta( $id, 'meeting_link', true ),
                'categories'              => $categories,
                'tags'                    => $tags,
                'speaker'                 => $this->map_user_ids_to_emails( get_post_meta( $id, 'etn_event_speaker', true ) ),
                'speaker_type'            => get_post_meta( $id, 'speaker_type', true ),
                'speaker_groups'          => get_post_meta( $id, 'speaker_group', true ),
                'organizer'               => $this->map_user_ids_to_emails( get_post_meta( $id, 'etn_event_organizer', true ) ),
                'organizer_type'          => get_post_meta( $id, 'organizer_type', true ),
                'organizer_groups'        => get_post_meta( $id, 'organizer_group', true ),
                'schedules'               => get_post_meta( $id, 'etn_event_schedule', true ),
                'ticket_availability'     => get_post_meta( $id, 'etn_ticket_availability', true ),
                'event_logo'              => wp_get_attachment_url( get_post_meta( $id, 'etn_event_logo', true ) ),
                'event_logo_id'           => get_post_meta( $id, 'event_logo_id', true ),
                'event_banner'            => get_post_meta( $id, 'event_banner', true ),
                'event_banner_id'         => get_post_meta( $id, 'event_banner_id', true ),
                'event_layout'            => get_post_meta( $id, 'event_layout', true ),
                'ticket_template'         => get_post_meta( $id, 'ticket_template', true ),
                'certificate_template'    => get_post_meta( $id, 'certificate_template', true ),
                'calendar_bg'             => get_post_meta( $id, 'etn_event_calendar_bg', true ),
                'calendar_text_color'     => get_post_meta( $id, 'etn_event_calendar_text_color', true ),
                'registration_deadline'   => get_post_meta( $id, 'etn_registration_deadline', true ),
                'attende_page_link'       => get_post_meta( $id, 'attende_page_link', true ),
                'total_ticket'            => get_post_meta( $id, 'etn_total_avaiilable_tickets', true ),
                'sold_tickets'            => get_post_meta( $id, 'etn_total_sold_tickets', true ),
                'ticket_variations'       => get_post_meta( $id, 'etn_ticket_variations', true ),
                'event_socials'           => get_post_meta( $id, 'etn_event_socials', true ),
                'fluent_crm'              => get_post_meta( $id, 'fluent_crm', true ),
                'fluent_crm_webhook'      => get_post_meta( $id, 'fluent_crm_webhook', true ),
                'faq'                     => get_post_meta( $id, 'etn_event_faq', true ),
                'extra_fields'            => get_post_meta( $id, 'attendee_extra_fields', true ),
                'rsvp'                    => get_post_meta( $id, 'rsvp_settings', true ),
            ];

            $location_type = get_post_meta( $id, 'etn_event_location_type', true );
            $location      = get_post_meta( $id, 'etn_event_location', true );

            if ( 'new_location' == $location_type ) {
                $location = get_post_meta( $id, 'etn_event_location_list', true );
            }

            $schedule_data['location_type'] = $location_type;
            $schedule_data['location']      = $location;

            array_push( $exported_data, $schedule_data );
        }

        return $exported_data;
    }

    /**
     * Map user IDs to email addresses
     *
     * @param   mixed  $user_ids
     *
     * @return  array
     */
    private function map_user_ids_to_emails( $user_ids ) {
        if ( empty( $user_ids ) || ! is_array( $user_ids ) ) {
            return [];
        }

        $emails = [];

        foreach ( $user_ids as $user_id ) {
            $user = get_userdata( (int) $user_id );

            // Only real speakers/organizers. An id that is not one (e.g. an
            // admin id stored by a crafted save) must not turn into an email.
            if ( $user && \Eventin\AccessControl\Ownership::is_speaker_or_organizer( $user->ID ) ) {
                $emails[] = $user->user_email;
            }
        }

        return $emails;
    }

    /**
     * Get columns
     *
     * @return  array
     */
    private function get_columns() {
        return [
            'id'                      => __( 'ID', 'wp-event-solution' ),
            'title'                   => __( 'Title', 'wp-event-solution' ),
            'status'                  => __( 'Status', 'wp-event-solution' ),
            'description'             => __( 'Description', 'wp-event-solution' ),
            'start_date'              => __( 'Start Date', 'wp-event-solution' ),
            'end_date'                => __( 'End Date', 'wp-event-solution' ),
            'start_time'              => __( 'Start Time', 'wp-event-solution' ),
            'end_time'                => __( 'End Time', 'wp-event-solution' ),
            'timezone'                => __( 'Timezone', 'wp-event-solution' ),
            'event_type'              => __( 'Event Type', 'wp-event-solution' ),
            'location'                => __( 'Location', 'wp-event-solution' ),
            'meeting_link'            => __( 'Meeting Link', 'wp-event-solution' ),
            'categories'              => __( 'Categories', 'wp-event-solution' ),
            'tags'                    => __( 'Tags', 'wp-event-solution' ),   
            'speaker_type'            => __( 'Speaker Type', 'wp-event-solution' ),
            'speaker'                 => __( 'Speaker', 'wp-event-solution' ),
            'speaker_groups'          => __( 'Speaker Groups', 'wp-event-solution' ),
            'organizer_type'          => __( 'Organizer Type', 'wp-event-solution' ),
            'organizer'               => __( 'Organizer', 'wp-event-solution' ),
            'organizer_groups'        => __( 'Organizer Groups', 'wp-event-solution' ),
            'schedules'               => __( 'Schedules', 'wp-event-solution' ),
            'ticket_variations'       => __( 'Ticket Variations', 'wp-event-solution' ),
            'ticket_availability'     => __( 'Ticket Availability', 'wp-event-solution' ),
            'event_logo'              => __( 'Logo', 'wp-event-solution' ),
            'event_logo_id'           => __( 'Logo Id', 'wp-event-solution' ),
            'event_banner'            => __( 'Banner', 'wp-event-solution' ),
            'event_banner_id'         => __( 'Banner Id', 'wp-event-solution' ),
            'event_layout'            => __( 'Layout', 'wp-event-solution' ),
            'ticket_template'         => __( 'Ticket Template', 'wp-event-solution' ),
            'certificate_template'    => __( 'Certificate Template', 'wp-event-solution' ),
            'calendar_bg'             => __( 'Calendar Background', 'wp-event-solution' ),
            'calendar_text_color'     => __( 'Calendar Text Color', 'wp-event-solution' ),
            'event_socials'           => __( 'Event Socials', 'wp-event-solution' ),
            'faq'                     => __( 'FAQ', 'wp-event-solution' ),
            'extra_fields'            => __( 'Extra Fields', 'wp-event-solution' ),
            'rsvp'                    => __( 'RSVP', 'wp-event-solution' ),
            'attende_page_link'       => __( 'Attendee Page Link', 'wp-event-solution' ),
            'total_ticket'            => __( 'Total Ticket', 'wp-event-solution' ),
            'sold_tickets'            => __( 'Sold Ticket', 'wp-event-solution' ),
            'fluent_crm'              => __( 'Fluent CRM', 'wp-event-solution' ),
            'fluent_crm_webhook'      => __( 'Fluent CRM Webhook', 'wp-event-solution' ),
        ];
    }
}
