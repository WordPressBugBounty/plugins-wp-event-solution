<?php

namespace Etn\Core\Attendee;

use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Cpt Class.
 * Cpt class for custom post type of Speaker.
 * @extend Inherite class \Etn\Base\Cpt Abstract Class
 *
 * @since 1.0.0
 */

class Cpt extends \Etn\Base\Cpt {

    public function create_cpt(){

        if( !is_admin() ){
            return true;
        }

        if( current_user_can( 'manage_etn_attendee' )){
            return true;
        }
        
        return false;
    }

    /**
     * set custom post type name
     */ 
    public function get_name() {
        return "etn-attendee";
    }

     /**
     * set custom post type options data
     */
    public function post_type() {
        $options = $this->user_modifiable_option();

        $labels = [
            'name'                  => esc_html_x( 'Attendees', 'Post Type General Name', 'wp-event-solution' ),
            'singular_name'         => $options['etn_attendee_singular_name'],
            'menu_name'             => esc_html__( 'Attendee', 'wp-event-solution' ),
            'name_admin_bar'        => esc_html__( 'Attendee', 'wp-event-solution' ),
            'archives'              => $options['etn_attendee_archive'],
            'attributes'            => esc_html__( 'Attendee Attributes', 'wp-event-solution' ),
            'parent_item_colon'     => esc_html__( 'Parent Item:', 'wp-event-solution' ),
            'all_items'             => $options['etn_attendee_all'],
            'add_new_item'          => esc_html__( 'Add New Attendee', 'wp-event-solution' ),
            'add_new'               => esc_html__( 'Add New', 'wp-event-solution' ),
            'new_item'              => esc_html__( 'New Attendee', 'wp-event-solution' ),
            'edit_item'             => esc_html__( 'Edit Attendee', 'wp-event-solution' ),
            'update_item'           => esc_html__( 'Update Attendee', 'wp-event-solution' ),
            'view_item'             => esc_html__( 'View Attendee', 'wp-event-solution' ),
            'view_items'            => esc_html__( 'View Attendee', 'wp-event-solution' ),
            'search_items'          => esc_html__( 'Search Attendee', 'wp-event-solution' ),
            'not_found'             => esc_html__( 'Not found', 'wp-event-solution' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'wp-event-solution' ),
            'featured_image'        => esc_html__( 'Featured Image', 'wp-event-solution' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'wp-event-solution' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'wp-event-solution' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'wp-event-solution' ),
            'insert_into_item'      => esc_html__( 'Insert into Attendee', 'wp-event-solution' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this Attendee', 'wp-event-solution' ),
            'items_list'            => esc_html__( 'Attendee list', 'wp-event-solution' ),
            'items_list_navigation' => esc_html__( 'Attendee list navigation', 'wp-event-solution' ),
            'filter_items_list'     => esc_html__( 'Filter froms list', 'wp-event-solution' ),
        ];

        $rewrite = [
            'slug'       => apply_filters( 'attendee_slug', $options['attendee_slug'] ),
            'with_front' => true,
            'pages'      => true,
            'feeds'      => false,
        ];

        $args = [
            'label'               => esc_html__( 'Attendee', 'wp-event-solution' ),
            'description'         => esc_html__( 'Attendee', 'wp-event-solution' ),
            'labels'              => $labels,
            'supports'            => false,
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => current_user_can( 'manage_etn_attendee' ),
            'show_in_menu'        => false,
            'show_admin_column'   => false,
            'menu_icon'           => 'dashicons-text-page',
            'menu_position'       => 10,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'publicly_queryable'  => false,
            'rewrite'             => $rewrite,
            'query_var'           => true,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'rest_base'           => $this->get_name(),
            'map_meta_cap' => true, // Allow edit / delete
        ];

        // Can't create manual attendee
        if ( !class_exists('Wpeventin_Pro') ) {

            $args['capabilities'] = array(
                  'create_posts'    => 'do_not_allow', // Removes support for the "Add New" 
            );
			
        }

        // if( current_user_can( 'manage_etn_attendee' ) ){
        //     $args['show_in_menu']        = 'eventin';
        // }

        return $args;
    }

    /**
     * Operation custom post type
     */ 
    public function flush_rewrites() {
        $name = $this->get_name();
        $args = $this->post_type();

        register_post_type( $name, $args );
    }
    
    /**
     * Modifiable options is here
     */
    private function user_modifiable_option() {
        $etn_attendee_singular_name = Helper::get_option("etn_attendee_singular_name");
        $etn_attendee_archive       = Helper::get_option("etn_attendee_archive");
        $etn_attendee_all           = Helper::get_option("etn_attendee_all");
        $attendee_slug              = Helper::get_option("attendee_slug");

        $options = [
            'etn_attendee_singular_name'       => esc_html__( 'Attendee', 'wp-event-solution' ),
            'etn_attendee_archive'             => esc_html__( 'Attendee Archive', 'wp-event-solution' ),
            'etn_attendee_all'                 => esc_html__( 'Attendees', 'wp-event-solution' ),
            'attendee_slug'                    => 'etn-attendee',
            'etn_attendee_exclude_from_search' => true,
        ];

        if ( !empty( $etn_attendee_singular_name ) ) {
            $options['etn_attendee_singular_name'] = $etn_attendee_singular_name;
        }

        if ( !empty( $etn_attendee_archive ) ) {
            $options['etn_attendee_archive'] = $etn_attendee_archive;
        }

        if ( !empty($etn_attendee_all) ) {
            $options['etn_attendee_all'] = $etn_attendee_all;
        }

        if ( !empty( $attendee_slug ) ) {
            $options['attendee_slug'] = str_replace( ' ', '_', $attendee_slug );
        }

        return $options;
    }

}
