<?php

namespace Etn\Core\Schedule;

defined( 'ABSPATH' ) || exit;

class Cpt extends \Etn\Base\Cpt {

    /**
     * Undocumented function
     *
     * @return void
     */
    public function create_cpt(){

        if( !is_admin() ){
            return true;
         }
 
         if( current_user_can( 'manage_etn_schedule' )){
             return true;
         }
         
         return false;
    }

    // set custom post type name
    public function get_name() {
        return 'etn-schedule';
    }

    // set custom post type options data
    public function post_type() {
        $options = $this->user_modifiable_option();
        $labels  = [
            'name'                  => esc_html_x( 'Schedule', 'Post Type General Name', 'wp-event-solution' ),
            'singular_name'         => $options['etn_schedule_singular_name'],
            'menu_name'             => esc_html__( 'Schedule', 'wp-event-solution' ),
            'name_admin_bar'        => esc_html__( 'Schedule', 'wp-event-solution' ),
            'attributes'            => esc_html__( 'Schedule Attributes', 'wp-event-solution' ),
            'parent_item_colon'     => esc_html__( 'Parent Item:', 'wp-event-solution' ),
            'all_items'             => $options['etn_schedule_all'],
            'add_new_item'          => esc_html__( 'Add New Schedule', 'wp-event-solution' ),
            'add_new'               => esc_html__( 'Add New', 'wp-event-solution' ),
            'new_item'              => esc_html__( 'New Schedule', 'wp-event-solution' ),
            'edit_item'             => esc_html__( 'Edit Schedule', 'wp-event-solution' ),
            'update_item'           => esc_html__( 'Update Schedule', 'wp-event-solution' ),
            'view_item'             => esc_html__( 'View Schedule', 'wp-event-solution' ),
            'view_items'            => esc_html__( 'View Schedules', 'wp-event-solution' ),
            'search_items'          => esc_html__( 'Search Schedules', 'wp-event-solution' ),
            'not_found'             => esc_html__( 'Not found', 'wp-event-solution' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'wp-event-solution' ),
            'featured_image'        => esc_html__( 'Featured Image', 'wp-event-solution' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'wp-event-solution' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'wp-event-solution' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'wp-event-solution' ),
            'insert_into_item'      => esc_html__( 'Insert into Schedule', 'wp-event-solution' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this Schedule', 'wp-event-solution' ),
            'items_list'            => esc_html__( 'Schedules list', 'wp-event-solution' ),
            'items_list_navigation' => esc_html__( 'Schedules list navigation', 'wp-event-solution' ),
            'filter_items_list'     => esc_html__( 'Filter froms list', 'wp-event-solution' ),
        ];
        $rewrite = [
            'slug'       => apply_filters( 'etn_schedule_slug', $options['etn_schedule_slug'] ),
            'with_front' => true,
            'pages'      => true,
            'feeds'      => false,
        ];
        $args = [
            'label'               => esc_html__( 'Schedules', 'wp-event-solution' ),
            'description'         => esc_html__( 'Schedule', 'wp-event-solution' ),
            'labels'              => $labels,
            'supports'            => false,
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => current_user_can( 'manage_etn_schedule' ),
            'menu_icon'           => 'dashicons-text-page',
            'menu_position'       => 1,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'query_var'           => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'rest_base'           => $this->get_name(),
        ];

        if( current_user_can( 'manage_etn_schedule' ) ){
            $args['show_in_menu']        = 'etn-events-manager';
        }

        return $args;
    }

    // Operation custom post type
    public function flush_rewrites() {
        $name = $this->get_name();
        $args = $this->post_type();
        register_post_type( $name, $args );
    }

    private function user_modifiable_option() {
        $settings_options = get_option( 'etn_schedule_options' );

        $options = [
            'etn_schedule_singular_name'       => esc_html__('Schedule', 'wp-event-solution'),
            'etn_schedule_all'                 => esc_html__('Schedules', 'wp-event-solution'),
            'etn_schedule_slug'                => 'etn-schedule',
            'etn_schedule_exclude_from_search' => true,
        ];

        if ( isset( $settings_options['etn_schedule_singular_name'] ) && $settings_options['etn_schedule_singular_name'] != '' ) {
            $options['etn_schedule_singular_name'] = $settings_options['etn_schedule_singular_name'];
        }

        if ( isset( $settings_options['etn_schedule_all'] ) && $settings_options['etn_schedule_all'] != '' ) {
            $options['etn_schedule_all'] = $settings_options['etn_schedule_all'];
        }

        if ( isset( $settings_options['etn_schedule_slug'] ) && $settings_options['etn_schedule_slug'] != '' ) {
            $options['etn_schedule_slug'] = $settings_options['etn_schedule_slug'];
        }

        if ( isset( $settings_options['etn_schedule_exclude_from_search'] ) && $settings_options['etn_schedule_exclude_from_search'] != '' ) {
            $options['etn_schedule_exclude_from_search'] = (bool) $settings_options['etn_schedule_exclude_from_search'];
        }

        return $options;
    }

}
