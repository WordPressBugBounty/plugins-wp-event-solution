<?php

namespace Eventin\Order;

defined( 'ABSPATH' ) || exit;
use Etn\Base\Post_Model;
use Etn\Core\Attendee\Attendee_Model;
use Etn\Core\Event\Event_Model;
use Eventin\Customer\CustomerModel;

/**
 * Order Model
 * 
 * @package Eventin
 */

class OrderModel extends Post_Model {
    use OrderEmailTrait;

    /**
     * Store post type
     *
     * @var string
     */
    protected $post_type = 'etn-order';

    /**
     * Store order data
     *
     * @var array
     */
    protected $data = [
        'customer_fname'    => '',
        'customer_lname'    => '',
        'customer_email'    => '',
        'customer_phone'    => '',
        'date_time'         => '',
        'event_id'          => '',
        'payment_method'    => '',
        'status'            => '',
        'user_id'           => '',
        'tickets'           => '',
        'seat_ids'          => '',
        'total_price'       => '',
        'discount_total'    => '',
        'tax_total'         => 0,
        'tax_display_mode'  => 'excl',
        'payment_id'        => '',
        'attendee_seats'    => '',
        'customer_id'       => '',
        'remaining_time_to_pay' => 3,
        'extra_fields'        => [],
        'currency'			  => '',
        'currency_symbol'     => ''
    ];

    /**
     * Get total ticket for an order
     *
     * @return  integer
     */
    public function get_total_ticket() {
        $variations = $this->tickets;
        $total_ticket = 0;

        if ( $variations && is_array( $variations ) ) {
            foreach( $variations as $variation ) {
                $total_ticket += $variation['ticket_quantity'];
            }
        }

        return $total_ticket;
    }

    /**
     * Get total ticket by ticket slug
     *
     * @param   string  $ticket_slug 
     *
     * @return  integer
     */
    public function get_total_ticket_by_ticket( $ticket_slug ) {
        $variations = $this->tickets;

        if ( $variations && is_array( $variations ) ) {
            foreach( $variations as $variation ) {
                if ( $variation['ticket_slug'] === $ticket_slug ) {
                    return $variation['ticket_quantity'];
                }
            }
        }

        return 0;
    }

    /**
     * Get all attenddes for an order
     *
     * @return  array Attendee data
     */
    public function get_attendees() {
        $attendee_obect = new Attendee_Model();

        $attendees = $attendee_obect->get_attendees_by( 'eventin_order_id', $this->id );

        return $attendees;
    }

    /**
     * Get all tickets for an order
     *
     * @return  array  Tickets data
     */
    public function get_tickets() {
        $tickets = [];
        $event   = new Event_Model( $this->event_id );

        if ( $this->tickets ) {
            foreach( $this->tickets as $ticket ) {
                $ticket_item = $event->get_ticket( $ticket['ticket_slug'] );
                if ( ! $ticket_item ) {
                    continue;
                }
                
                $ticket_data = [
                    'etn_ticket_name'   => $ticket_item['etn_ticket_name'],
                    'etn_ticket_price'  => $ticket_item['etn_ticket_price'],
                    'etn_ticket_slug'   => $ticket_item['etn_ticket_slug'],
                    'etn_ticket_qty'    => $ticket['ticket_quantity'],
                ];

                if ( ! empty( $ticket['seats'] ) ) {
                    $ticket_data['seats'] = $ticket['seats'];
                }

                $tickets[] = $ticket_data;
            }
        }

        
        return apply_filters( 'etn/order-model/tickets',$tickets,$event,$this->event_id, $this->id );
    }

    /**
     * Get order date time
     *
     * @param   string  $format  
     *
     * @return  string
     */
    public function get_datetime( $format = 'Y-m-d h:i A') {
        $post = get_post( $this->id );

        $datetime = new \DateTime( $post->post_date );

        return $datetime->format($format);
    }

    /**
     * Get order customer
     *
     * @return  CustomerModel
     */
    public function get_customer() {
        return CustomerModel::find( $this->customer_id );
    }
	
	
	/**
	 * Get all the orders by a list of orderIds
	 *
	 * @return array
	 *
	 */
	public function getAllOrdersByIds() : array
	{
		return [];
	}

    /**
     * Validate event order tickets
     *
     * @return  bool | WP_Error
     */
    public function validate_ticket($is_for_update = false) {
       return etn_validate_event_tickets( $this->event_id, $this->tickets,$is_for_update );
    }

    /**
     * Get the order's extra-fields schema. Prefers event-level
     * attendee_extra_fields meta and falls back to global settings.
     *
     * @return array
     */
    public function get_extra_fields_schema() {
        $event_id = (int) $this->event_id;
        $schema   = $event_id ? get_post_meta( $event_id, 'attendee_extra_fields', true ) : '';

        if ( empty( $schema ) ) {
            $settings = etn_get_option();
            $schema   = ! empty( $settings['extra_fields'] )
                ? $settings['extra_fields']
                : ( ! empty( $settings['attendee_extra_fields'] ) ? $settings['attendee_extra_fields'] : [] );
        }

        return is_array( $schema ) ? $schema : [];
    }

    /**
     * Convert a field label into the slug shape the JS form uses.
     * Mirrors AttendeeModel::label_to_slug — keep in sync.
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
     * keyed by the order's stored extra-field key.
     *
     * @return array<string, array{id:int, url:string, mime:string, filename:string}>
     */
    public function get_extra_field_files() {
        $schema = $this->get_extra_fields_schema();
        if ( ! $schema ) {
            return [];
        }

        $extras = $this->extra_fields;
        if ( is_object( $extras ) ) {
            $extras = (array) $extras;
        }
        if ( ! is_array( $extras ) ) {
            return [];
        }

        $files = [];

        foreach ( $schema as $idx => $row ) {
            if ( ! isset( $row['field_type'] ) || 'file' !== $row['field_type'] ) {
                continue;
            }

            $field_id  = ! empty( $row['id'] ) ? $row['id'] : ( $idx + 1 );
            $slug      = $this->label_to_slug( $row['label'] ?? '' );
            $field_key = $slug . '_' . $field_id;

            $att_id = isset( $extras[ $field_key ] ) ? $extras[ $field_key ] : '';
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
	 * @description
	 * @return false|void
	 */
	public function findWCOrderByEventinOrder()
	{
		$post_type = etn_is_enable_wc_synchronize_order() ? 'shop_order' : 'shop_order_placehold';
		$args = [
			'post_type'   => $post_type,
			'post_status' => 'any',
			'posts_per_page' => 1,
			'fields'          => 'ids',
			'meta_query'    => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				[
					'key'   => 'eventin_order_id',
					'value' => $this->id,
					'compare' => '='
				]
			]
		];
		
		
		$orders_ids = get_posts( $args );
		
		if ( ! $orders_ids ) {
			return false;
		}
		
		$wc_order = wc_get_order( $orders_ids[0] );
		
		return $wc_order;
	}
}

