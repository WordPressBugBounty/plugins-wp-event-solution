<?php

use Etn\Utils\Helper;

defined('ABSPATH') || exit;

if ( !class_exists( 'WC_Product_Data_Store_CPT' ) ) {
    return;
}

class Etn_Product_Data_Store_CPT extends WC_Product_Data_Store_CPT implements WC_Object_Data_Store_Interface, WC_Product_Data_Store_Interface {

    /**
     * Method to read a product from the database.
     * @param WC_Product
     */
    public function read( &$product ) {
        
        $product->set_defaults();

        if ( !$product->get_id() || !( $post_object = get_post( $product->get_id() ) ) || !in_array( $post_object->post_type, ['etn', 'product'] ) ) {
            throw new Exception( esc_html__( 'Invalid product.', 'eventin' ) );
        }

        // $id = $product->get_id();

        $product->set_id( $post_object->ID );
        
        $product->set_props( [
            'product_id'        => $post_object->ID,
            'name'              => $post_object->post_title,
            'slug'              => $post_object->post_name,
            'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
            'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
            'status'            => $post_object->post_status,
            'description'       => $post_object->post_content,
            'short_description' => $post_object->post_excerpt,
            'parent_id'         => $post_object->post_parent,
            'menu_order'        => $post_object->menu_order,
            'reviews_allowed'   => 'open' === $post_object->comment_status,
        ] );

        $this->read_attributes( $product );
        $this->read_downloads( $product );
        $this->read_visibility( $product );
        $this->read_product_data( $product );
        $this->read_extra_data( $product );
        $product->set_object_read( true );
    }

    /**
     * Read meta for a product, minus an event's private fields.
     *
     * This store makes wc_get_product( <event id> ) return the event, so any
     * plugin that answers a public request with $product->get_meta_data()
     * (seen: Optiontics GET /optiontics/v1/products/<id>, WP Cafe
     * GET /wpcafe/v2/products/<id>) handed out the event's CRM webhooks,
     * meeting links and revenue. Nothing reads these through the WC product
     * object, so they are left out of it; post meta itself is untouched.
     * Patchstack 36114.
     *
     * @param WC_Data $object Product being read.
     * @return array
     */
    public function read_meta( &$object ) {
        $meta_data = parent::read_meta( $object );

        if ( 'etn' !== get_post_type( $object->get_id() ) ) {
            return $meta_data;
        }

        $private = array_flip( \Eventin\Event\Api\EventController::management_only_meta_keys() );

        foreach ( $meta_data as $index => $meta ) {
            if ( isset( $private[ $meta->meta_key ] ) ) {
                unset( $meta_data[ $index ] );
                continue;
            }

            if ( in_array( $meta->meta_key, [ 'etn_event_location', 'location' ], true ) ) {
                $meta_data[ $index ]->meta_value = maybe_serialize(
                    \Eventin\Event\Api\EventController::strip_private_location( $meta->meta_value )
                );
            }
        }

        return $meta_data;
    }

    /**
     * Get the product type based on product ID.
     */
    public function get_product_type( $product_id ) {

        $post_type = get_post_type( $product_id );

        if ( 'product_variation' === $post_type ) {
            return 'variation';
        } elseif ( in_array( $post_type, ['etn', 'product'] ) ) {
            $terms = get_the_terms( $product_id, 'product_type' );
            return !empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'etn';
        } else {
            return false;
        }

    }

}

/**
 * overwrite woocommerce store and make our custom post as a product
 */
function etn_woocommerce_data_stores( $stores ) {
    $stores['product'] = 'Etn_Product_Data_Store_CPT';

    return $stores;
}

 //all hooks required to hook our event as woocommerce product
add_filter( 'woocommerce_data_stores', 'etn_woocommerce_data_stores' );


