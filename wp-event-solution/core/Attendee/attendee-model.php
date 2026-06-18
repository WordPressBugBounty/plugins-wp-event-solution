<?php

/**
 * Attendee Model Class
 *
 * @package Eventin
 */
namespace Etn\Core\Attendee;

defined( 'ABSPATH' ) || exit;

use Etn\Base\Post_Model;
use function cli\err;

/**
 * Attendee Model
 */
class Attendee_Model extends Post_Model {
    protected $post_type = 'etn-attendee';

    /**
     * Store attendee data
     *
     * @var array
     */
    protected $data = [
        'etn_name'                    => '',
        'etn_email'                   => '',
        'etn_phone'                   => '',
        'etn_event_id'                => '',
        'eventin_order_id'            => '',
        'etn_unique_ticket_id'        => '',
        'ticket_name'                 => '',
        'ticket_slug'                 => '',
        'etn_attendeee_ticket_status' => 'unused',
        'etn_ticket_price'            => '',
        'etn_status'                  => 'failed',
        'etn_info_edit_token'         => '',
        'extra_fields'                => '',
        'attendee_seat'               => '',
    ];

    /**
     * Set extra field data
     *
     * @param   string  $name   Extra field name
     * @param   mixed  $value  Extra field value
     *
     * @return  void
     */
    public function __set( $name, $value ) {
        $this->data[$name] = $value;
    }

    /**
     * Set extra fields
     *
     * @param   array  $extra_fields  Attendee extra fields
     *
     * @return  void
     */
    public function set_fields( $extra_fields ) {
        foreach( $extra_fields as $key => $value ) {
            $this->data[$key] = $value;
        }
    }

    /**
     * Get all attendees by key. Example all attendess for an event or an order
     *
     * @param   string  $key    [$key description]
     * @param   mixed  $value  [$value description]
     *
     * @return  array
     */
    public function get_attendees_by( $key, $value, $post_status = ['publish', 'trash'], $limit = -1 ) {
        $args = [
            'post_type'      => 'etn-attendee',
            'post_status'    => $post_status,
            'posts_per_page' => $limit,
            'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                [
                    'key'     => $key,
                    'value'   => $value,
                    'compare' => '=',
                ]
            ]
        ];

        $attendees = get_posts( $args );

        $data = [];

        if ( $attendees ) {
            $attendee_ids = wp_list_pluck( $attendees, 'ID' );
            update_postmeta_cache( $attendee_ids );

            foreach( $attendees as $attendee ) {
                $attendee_object = new Attendee_Model( $attendee->ID );
                $attendee_data   = $attendee_object->get_data();
                $attendee_data['extra_fields'] = $attendee_object->get_extra_fields();
                $data[] = $attendee_data;
            }
        }

        return $data;
    }

    /**
     * Batch version of get_attendees_by() for a list of order ids.
     *
     * Fetches every attendee for the given orders in ONE query (instead of one
     * query per order, the booking-list N+1), primes their meta cache once, and
     * returns a map of order_id => array of attendee data — each element built
     * identically to get_attendees_by() so callers get the same shape.
     *
     * @param   int[]  $order_ids    Eventin order ids.
     * @param   array  $post_status  Attendee statuses to include.
     *
     * @return  array  [ order_id => [ attendee_data, ... ] ]
     */
    public function get_attendees_grouped_by_order( array $order_ids, $post_status = ['publish', 'trash'] ) {
        $order_ids = array_values( array_unique( array_filter( array_map( 'intval', $order_ids ) ) ) );

        if ( empty( $order_ids ) ) {
            return [];
        }

        $attendees = get_posts( [
            'post_type'      => 'etn-attendee',
            'post_status'    => $post_status,
            'posts_per_page' => -1,
            'meta_query'     => [
                [
                    'key'     => 'eventin_order_id',
                    'value'   => $order_ids,
                    'compare' => 'IN',
                ],
            ],
        ] );

        // Seed every requested order with an empty list so orders without
        // attendees still return [] (matching the per-order call).
        $grouped = array_fill_keys( $order_ids, [] );

        if ( $attendees ) {
            update_postmeta_cache( wp_list_pluck( $attendees, 'ID' ) );

            foreach ( $attendees as $attendee ) {
                $attendee_object               = new Attendee_Model( $attendee->ID );
                $attendee_data                 = $attendee_object->get_data();
                $attendee_data['extra_fields'] = $attendee_object->get_extra_fields();

                $order_id = (int) $attendee_data['eventin_order_id'];
                if ( isset( $grouped[ $order_id ] ) ) {
                    $grouped[ $order_id ][] = $attendee_data;
                }
            }
        }

        return $grouped;
    }

    /**
     * Get attendee extra fields data
     *
     * @return  array  Extra fields data
     */
    public function get_extra_fields() {
        $fields = get_post_meta( $this->id );
        $extra_fields = [];

        if ( ! is_array( $fields ) ) {
            return $extra_fields;
        }

        foreach( $fields as $key => $value ) {
            // Check extra fields exist or not.
            if ( strpos( $key, 'etn_attendee_extra_field_' ) !== false ) {
                $new_key = str_replace( 'etn_attendee_extra_field_', '', $key );
                // $value is an array from get_post_meta($id) with no key; use first element directly
                // to avoid a redundant DB round-trip (or cache hit) for get_post_meta($id, $key, true).
                $extra_fields[ $new_key ] = get_post_meta( $this->id, $key, true );
            }
        }

        return $extra_fields;
    }

    /**
     * Get the event-level extra-fields schema (config rows) for this attendee's event.
     * Falls back to global settings.
     *
     * @return array
     */
    public function get_extra_fields_schema() {
        $event_id = (int) get_post_meta( $this->id, 'etn_event_id', true );
        $schema   = $event_id ? get_post_meta( $event_id, 'attendee_extra_fields', true ) : '';

        if ( empty( $schema ) ) {
            $settings = etn_get_option();
            $schema   = ! empty( $settings['extra_fields'] ) ? $settings['extra_fields'] : ( ! empty( $settings['attendee_extra_fields'] ) ? $settings['attendee_extra_fields'] : [] );
        }

        return is_array( $schema ) ? $schema : [];
    }

    /**
     * Convert a field label to a slug matching the JS frontend convention.
     * Replicates AttendeeExporter::label_to_slug — keep in sync.
     *
     * @param string $label
     * @return string
     */
    private function label_to_slug( $label ) {
        $slug = mb_strtolower( trim( (string) $label ) );
        $slug = preg_replace( '/\p{Z}+/u', ' ', $slug );
        $slug = preg_replace( '/[^a-z0-9 _]/', '', $slug );
        $slug = preg_replace( '/[ _]+/', '_', $slug );
        return trim( $slug, '_' );
    }

    /**
     * For each schema entry of field_type "file", resolve attachment metadata
     * keyed by the attendee's stored extra-field key.
     *
     * @return array<string, array{id:int, url:string, mime:string, filename:string}>
     */
    public function get_extra_field_files() {
        $schema = $this->get_extra_fields_schema();
        if ( ! $schema ) {
            return [];
        }

        $extras = $this->get_extra_fields();
        $files  = [];

        foreach ( $schema as $idx => $row ) {
            if ( ! isset( $row['field_type'] ) || 'file' !== $row['field_type'] ) {
                continue;
            }

            $field_id  = ! empty( $row['id'] ) ? $row['id'] : ( $idx + 1 );
            $slug      = $this->label_to_slug( $row['label'] ?? '' );
            $field_key = $slug . '_' . $field_id;

            $att_id = isset( $extras[ $field_key ] ) ? $extras[ $field_key ] : '';
            // Backward-compat: legacy entries stored without the _{id} suffix.
            if ( '' === $att_id || null === $att_id ) {
                $att_id = isset( $extras[ $slug ] ) ? $extras[ $slug ] : '';
            }

            if ( '' === $att_id || ! is_numeric( $att_id ) ) {
                continue;
            }

            $att_id = (int) $att_id;
            $url    = wp_get_attachment_url( $att_id );
            if ( ! $url ) {
                continue;
            }

            $files[ $field_key ] = [
                'id'       => $att_id,
                'url'      => $url,
                'mime'     => (string) get_post_mime_type( $att_id ),
                'filename' => wp_basename( $url ),
            ];
        }

        return $files;
    }

    /**
     * Look up resolved file metadata for a single extra-field key.
     *
     * @param string $field_key  Key as returned by get_extra_fields() (no meta prefix).
     * @return array|null
     */
    public function get_extra_field_file_meta( $field_key ) {
        $files = $this->get_extra_field_files();
        return isset( $files[ $field_key ] ) ? $files[ $field_key ] : null;
    }

    /**
     * Get scanner update time
     *
     * @return  string
     */
    public function get_scanner_update_time() {
        $scanner_update_time = get_post_meta( $this->id, 'scanner_update_time', true );

        if ( empty( $scanner_update_time ) ) {
            return null;
        }
		
	    $date_format = get_option( 'date_format' );
	    $time_format = get_option( 'time_format' );
	    return date_i18n( $date_format . ' ' . $time_format, strtotime($scanner_update_time) );
    }

    /**
     * Build the rendered HTML for a single extra-field value.
     *
     * Handles file-type extras (image attachments rendered inline; other mime
     * types rendered as a download link) and falls back to escaped plain text.
     *
     * @since 4.1.13
     *
     * @param string $key   Extra-field key as stored in post meta (without prefix).
     * @param mixed  $field Stored extra-field value (scalar or array).
     * @param array  $files Resolved file map keyed by field_key.
     * @return string Escaped HTML safe for output.
     */
    protected function render_extra_field_value( $key, $field, $files ) {
        if ( isset( $files[ $key ] ) ) {
            $url      = esc_url( $files[ $key ]['url'] );
            $filename = esc_attr( $files[ $key ]['filename'] );
            $mime     = isset( $files[ $key ]['mime'] ) ? (string) $files[ $key ]['mime'] : '';

            if ( 0 === strpos( $mime, 'image/' ) ) {
                $image_style = apply_filters(
                    'eventin_attendee_extra_field_image_style',
                    'width:80px;height:80px;display:inline-block;vertical-align:middle;object-fit:cover;border-radius:4px;',
                    $key,
                    $files[ $key ]
                );

                $value = sprintf(
                    '<img src="%1$s" alt="%2$s" style="%3$s" />',
                    $url,
                    $filename,
                    esc_attr( $image_style )
                );
            } else {
                $value = sprintf(
                    '<a href="%1$s" target="_blank" rel="noopener">%2$s</a>',
                    $url,
                    $filename
                );
            }
        } else {
            $value = is_array( $field )
                ? esc_html( implode( ', ', $field ) )
                : esc_html( (string) $field );
        }

        /**
         * Filters the rendered HTML for a single attendee extra-field value.
         *
         * @since 4.1.13
         *
         * @param string         $value  Rendered HTML.
         * @param string         $key    Extra-field key.
         * @param mixed          $field  Stored value.
         * @param array          $files  Resolved file map.
         * @param Attendee_Model $model  Current attendee model instance.
         */
        return apply_filters( 'eventin_attendee_extra_field_value', $value, $key, $field, $files, $this );
    }

    /**
     * Get extra fields as html content.
     *
     * @return string
     */
    public function get_extra_fields_content() {
        $extra_fields = $this->get_extra_fields();
        $files        = $this->get_extra_field_files();

        $list_style = apply_filters(
            'eventin_attendee_extra_fields_list_style',
            'list-style: none; list-style-type: none; padding-left: 0; margin-left: 0;'
        );

        $fields = sprintf(
            '<ul class="etn-attendee-extra-data" style="%s">',
            esc_attr( $list_style )
        );

        if ( ! empty( $extra_fields ) ) {
            foreach ( $extra_fields as $key => $field ) {
                // Format label: replace underscores with spaces and capitalize each word.
                $label = ucwords( str_replace( '_', ' ', $key ) );

                $label = apply_filters( 'eventin_attendee_extra_field_label', $label, $key, $this );
                $value = $this->render_extra_field_value( $key, $field, $files );

                $fields .= sprintf(
                    '<li><label style="font-weight: bold;">%1$s:</label> <span>%2$s</span></li>',
                    esc_html( $label ),
                    $value
                );
            }
        }

        $fields .= '</ul>';

        /**
         * Filters the final HTML for all rendered attendee extra fields.
         *
         * @since 4.1.13
         *
         * @param string         $fields       Final HTML.
         * @param array          $extra_fields Raw extra-field key => value map.
         * @param array          $files        Resolved file map.
         * @param Attendee_Model $model        Current attendee model instance.
         */
        return apply_filters( 'eventin_attendee_extra_fields_content', $fields, $extra_fields, $files, $this );
    }
    /**
     * Get a single extra field value by key
     *
     * @param   string  $field_key  The field key (without prefix)
     *
     * @return  string
     */
    public function get_single_extra_field( $field_key ) {
        $extra_fields = $this->get_extra_fields();

        if ( ! isset( $extra_fields[ $field_key ] ) ) {
            return '';
        }

        $value = $extra_fields[ $field_key ];

        return is_array( $value ) ? implode( ', ', $value ) : $value;
    }

    /**
     * Get extra fields as a single comma-separated inline string
     *
     * @return  string
     */
    public function get_extra_fields_inline() {
        $extra_fields = $this->get_extra_fields();
        $files        = $this->get_extra_field_files();
        $parts        = [];

        foreach ( $extra_fields as $key => $field ) {
            $label = ucwords( str_replace( '_', ' ', $key ) );

            if ( isset( $files[ $key ] ) ) {
                $value = $files[ $key ]['url'];
            } else {
                $value = is_array( $field ) ? implode( ', ', $field ) : $field;
            }

            $parts[] = $label . ': ' . $value;
        }

        return implode( ', ', $parts );
    }

    /**
     * Get attendde data
     *
     * @return  array
     */
    public function get_data() {
        $event_id = get_post_meta( $this->id, 'etn_event_id', true );
        $event = get_post( intval($event_id) );
        $response_data = [
            'id'         => $this->id,
            'event_name' => $event ? $event->post_title : '',
            'attendee_post_status' => get_post_status( $this->id ),
        ];

        foreach ( $this->data as $key => $value ) {
            $meta_key = $this->meta_prefix . $key;

            $meta_value = get_post_meta( $this->id, $meta_key, true );
            
            $response_data[$key] = $meta_value;
        }

        return $response_data;
    }
	
	
	public function get_attendees_model_by_eventin_order_id( $eventin_order_id ) {
		$args = [
			'post_type'      => 'etn-attendee',
			'post_status'    => 'any',
			'posts_per_page' => -1,
		'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			[
					'key'     => "eventin_order_id",
					'value'   => $eventin_order_id,
					'compare' => '=',
				]
			]
		];
		
		return  get_posts( $args );
	}
	
	
	/**
	 * @return bool
	 */
	public function trash_post() {
		return !! wp_trash_post( $this->id );
	}
}
