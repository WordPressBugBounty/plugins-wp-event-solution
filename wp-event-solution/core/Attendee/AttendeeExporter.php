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
     * Store Optiontics add-on field columns discovered while preparing rows.
     *
     * Keyed exactly like $extra_fields: column key => human label. One entry per
     * distinct option field (e.g. "T-Shirt Size", "Extras") across the exported
     * attendees, so a multi-choice field gets its own column instead of only
     * living inside the combined `addons` text.
     *
     * @var array
     */
    private $addon_fields = [];

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

            $attendee = array_merge( $attendee, $this->get_addons_data( $id ) );

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
     * Prepare per-attendee Optiontics add-on data (etn_option_selections)
     * as one readable text column plus a numeric total column.
     *
     * @param   integer  $attendee_id
     *
     * @return  array
     */
    private function get_addons_data( $attendee_id ) {
        $selections = get_post_meta( $attendee_id, 'etn_option_selections', true );

        if ( is_string( $selections ) ) {
            $decoded    = json_decode( $selections, true );
            $selections = is_array( $decoded ) ? $decoded : maybe_unserialize( $selections );
        }

        if ( ! is_array( $selections ) ) {
            $selections = [];
        }

        $lines      = [];
        $total      = 0.0;
        $per_field  = [];

        foreach ( $selections as $row ) {
            $qty        = isset( $row['qty'] ) ? (int) $row['qty'] : 1;
            $line_total = isset( $row['line_total'] ) ? (float) $row['line_total'] : 0.0;
            $label      = $row['field_label'] ?? '';
            $value      = $row['choice_value'] ?? '';
            $total     += $line_total;

            $lines[] = sprintf(
                '%s: %s x%d - %s',
                $label,
                $value,
                $qty,
                $line_total
            );

            // One column per option field. A multi-choice field (checkbox) sends one
            // row per selected value, so the values are collected and joined instead
            // of the last one overwriting the first.
            $slug = $this->label_to_slug( $label );

            if ( '' === $slug ) {
                $slug = $this->label_to_slug( (string) ( $row['node_id'] ?? '' ) );
            }

            if ( '' === $slug || '' === $value ) {
                continue;
            }

            $key    = substr( 'etn_addon_field_' . $slug, 0, 255 );
            $header = '' !== $label ? $label : $slug;

            // label_to_slug() drops punctuation, so two different fields ("Add-ons"
            // and "Add ons", or same-named fields in two blocks) can slug to the
            // same key. Keep them apart by qualifying with the node id rather than
            // merging their values into one cell.
            if ( isset( $this->addon_fields[ $key ] ) && $this->addon_fields[ $key ] !== $header ) {
                $key = substr( $key . '_' . $this->label_to_slug( (string) ( $row['node_id'] ?? '' ) ), 0, 255 );
            }

            $this->addon_fields[ $key ] = $header;

            $per_field[ $key ][] = $qty > 1 ? sprintf( '%s x%d', $value, $qty ) : $value;
        }

        $data = [
            'addons'       => implode( '; ', $lines ),
            'addons_total' => number_format( $total, 2, '.', '' ),
        ];

        foreach ( $per_field as $key => $values ) {
            $data[ $key ] = implode( ', ', $values );
        }

        return $data;
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
                // Front-end (extra-form-fields.jsx) saves the meta-key suffix as `item.id || index`,
                // where index is the 0-based array position. Mirror that exactly: use $index (NOT
                // $index + 1) for fields with no id, otherwise the lookup key is off by one and the
                // value exports blank.
                $field_id          = ! empty( $value['id'] ) ? $value['id'] : $index;
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

                    case 'file':
                        $url = ( $extra_field_value && is_numeric( $extra_field_value ) )
                            ? wp_get_attachment_url( (int) $extra_field_value )
                            : '';
                        $data[$key] = $url ? $url : '';
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
            'addons'         => __( 'Add-ons', 'eventin' ),
            'addons_total'   => __( 'Add-ons Total', 'eventin' ),
            'payment_status' => __( 'Payment Status', 'eventin' ),
            'ticket_status'  => __( 'Ticket Status', 'eventin' ),
            'ticket_id'      => __( 'Ticket ID', 'eventin' ),
            'ticket_name'    => __( 'Ticket Name', 'eventin' ),
        ];

        $columns = apply_filters( 'etn_prepare_attendee_data_columns', $columns );

        return array_merge( $columns, $this->addon_fields, $this->extra_fields );
    }
}
