<?php

/**
 * Attendee Exporter Class
 *
 * @package Eventin
 */
namespace Eventin\Attendee;

defined( 'ABSPATH' ) || exit;

use Eventin\Exporter\ExporterFactory;
use Eventin\Exporter\PostExporterInterface;
/**
 * Class Attendee Exporter
 *
 * Export Attendee Data
 */
class AttendeeExporter implements PostExporterInterface {
    /**
     * Store file name
     *
     * @var string
     */
    private $file_name = 'attedee-data';

    /**
     * Store attendee extra fields columns
     *
     * @var array
     */
    private $extra_fields = [];

    /**
     * Store attendee data
     *
     * @var array
     */
    private $data;

    /**
     * Export attendee data
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
            $attendee = [
                'id'             => $id,
                'name'           => get_post_meta( $id, 'etn_name', true ),
                'event_id'       => get_post_meta( $id, 'etn_event_id', true ),
                'event_name'     => get_the_title( get_post_meta( $id, 'etn_event_id', true ) ),
                'ticket_id'      => get_post_meta( $id, 'etn_unique_ticket_id', true ),
                'ticket_name'    => get_post_meta( $id, 'ticket_name', true ),
                'ticket_status'  => get_post_meta( $id, 'etn_attendeee_ticket_status', true ),
                'ticket_price'   => get_post_meta( $id, 'etn_ticket_price', true ),
                'payment_status' => get_post_meta( $id, 'etn_status', true ),
            ];

            
            $attendee['email'] = get_post_meta( $id, 'etn_email', true );

            $attendee['phone'] = get_post_meta( $id, 'etn_phone', true );

            $attendee = array_merge( $attendee, $this->get_extra_field_data( $id ) );

            $filtered_attendee = apply_filters( 'etn_prepare_attendee_data', $attendee, $id );

            array_push( $exported_data, $filtered_attendee );
        }

        return $exported_data;
    }

    /**
     * Convert a field label to a slug matching the frontend JS key convention.
     *
     * JS in extra-form-fields.jsx uses:
     *   .replace(/[^\w\s]/g, '').replace(/\s+/g, '_').replace(/_+/g, '_').replace(/^_|_$/g, '')
     *
     * JS \w is ASCII-only ([a-zA-Z0-9_]), so non-ASCII letters (e.g. Polish ę, ó) are
     * treated as special chars and stripped — not kept. This must be replicated exactly.
     *
     * @param   string  $label
     * @return  string
     */
    private function label_to_slug( $label ) {
        $slug = mb_strtolower( trim( $label ) );
        // Normalize all Unicode whitespace (NBSP \u00A0, etc.) to a plain space.
        // JS \s matches \u00A0 and turns it into a word separator (_),
        // but PHP \s without the u flag treats those bytes as non-whitespace and strips them,
        // causing adjacent words to merge (e.g. "si_pod" vs "sipod").
        $slug = preg_replace( '/\p{Z}+/u', ' ', $slug );
        $slug = preg_replace( '/[^a-z0-9 _]/', '', $slug );  // strip non-ASCII-alnum
        $slug = preg_replace( '/[ _]+/', '_', $slug );        // spaces/_ → single _
        return trim( $slug, '_' );
    }

    /**
     * Prepare extra field data
     *
     * @param   integer  $attendee_id
     *
     * @return  array
     */
    private function get_extra_field_data( $attendee_id ) {
        $event_id     = get_post_meta( $attendee_id, 'etn_event_id', true );
        $extra_fields = get_post_meta( $event_id, 'attendee_extra_fields', true );
        $settings     = etn_get_option();
        $data         = [];
        if ( ! $extra_fields ) {
            $extra_fields = ! empty( $settings['extra_fields'] ) ? $settings['extra_fields'] : [];
        }

        if ( $extra_fields ) {
            foreach ( $extra_fields as $index=>$value ) {
                $field_id          = ! empty( $value['id'] ) ? $value['id'] : ( $index + 1 );
                $slug              = $this->label_to_slug( $value['label'] );
                $key               = substr( 'etn_attendee_extra_field_' . $slug . '_' . $field_id, 0, 255 );
                $this->extra_fields[$key] = $value['label'];
                $extra_field_value = get_post_meta( $attendee_id, $key, true );
                // Backward-compat: legacy entries stored without the _{id} suffix.
                if ( '' === $extra_field_value || false === $extra_field_value ) {
                    $legacy_key        = 'etn_attendee_extra_field_' . $slug;
                    $extra_field_value = get_post_meta( $attendee_id, $legacy_key, true );
                }
                switch($value['field_type']){
                    case 'radio':
                        $data[$key] = $extra_field_value;
                    break;

                    case 'checkbox': 
                        $data[$key] = $extra_field_value;
                    break;

                    case 'date':
                        $date_format = get_option( 'date_format' );
                        $date   = gmdate( $date_format, strtotime( $extra_field_value ) );

                        if ( ! $extra_field_value ) {
                            $date = '';
                        }
                        
                        $data[$key] = $date;
                    break;

                    default:
                        $data[$key] = $extra_field_value;
                }
            }
        }

        return $data;
    }

    /**
     * Get columns
     *
     * @return  array
     */
    private function get_columns() {
        $columns = [
            'id'             => __( 'Id', 'eventin' ),
            'name'           => __( 'Name', 'eventin' ),
            'email'          => __( 'Email', 'eventin' ),
            'phone'          => __( 'Phone', 'eventin' ),
            'event_id'       => __( 'Event ID', 'eventin' ),
            'event_name'     => __( 'Event Name', 'eventin' ),
            'ticket_price'   => __( 'Ticket Price', 'eventin' ),
            'payment_status' => __( 'Payment Status', 'eventin' ),
            'ticket_status'  => __( 'Ticket Status', 'eventin' ),
            'ticket_id'      => __( 'Ticket ID', 'eventin' ),
            'ticket_name'    => __( 'Ticket Name', 'eventin' ),
        ];

        $columns = apply_filters( 'etn_prepare_attendee_data_columns', $columns );

        return array_merge( $columns, $this->extra_fields );
    }
}
