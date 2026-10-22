<?php

namespace Etn\Core\Event;

defined( 'ABSPATH' ) || exit;
/**
 * Cpt Class.
 * Cpt class for custom post type of Event.
 * @extend Inherite class \Etn\Base\Cpt Abstract Class
 *
 * @since 1.0.0
 */
class Cpt extends \Etn\Base\Cpt {

    // set custom post type name
    public function get_name() {
        return 'etn';
    }

    // set custom post type options data
    public function post_type() {
        $options = $this->user_modifiable_option();

        $labels  = [
            'name'                  => esc_html_x( 'Events', 'Post Type General Name', 'wp-event-solution' ),
            'singular_name'         => apply_filters( 'etn_event_singular_name', $options['wp_eventlty_singular_name'] ),
            'menu_name'             => esc_html__( 'Event', 'wp-event-solution' ),
            'name_admin_bar'        => esc_html__( 'Event', 'wp-event-solution' ),
            'archives'              => apply_filters( 'etn_event_archive', $options['etn_event_archive'] ),
            'attributes'            => esc_html__( 'Event Attributes', 'wp-event-solution' ),
            'parent_item_colon'     => esc_html__( 'Parent Item:', 'wp-event-solution' ),
            'all_items'             => apply_filters( 'etn_all_items', $options['etn_event_all'] ),
            'add_new_item'          => apply_filters( 'etn_add_new_item', 'Add New Event' ),
            'add_new'               => esc_html__( 'Add New', 'wp-event-solution' ),
            'new_item'              => esc_html__( 'New Event', 'wp-event-solution' ),
            'edit_item'             => esc_html__( 'Edit Event', 'wp-event-solution' ),
            'update_item'           => esc_html__( 'Update Event', 'wp-event-solution' ),
            'view_item'             => esc_html__( 'View Event', 'wp-event-solution' ),
            'view_items'            => esc_html__( 'View Events', 'wp-event-solution' ),
            'search_items'          => esc_html__( 'Search Events', 'wp-event-solution' ),
            'not_found'             => esc_html__( 'Not found', 'wp-event-solution' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'wp-event-solution' ),
            'featured_image'        => esc_html__( 'Featured Image', 'wp-event-solution' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'wp-event-solution' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'wp-event-solution' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'wp-event-solution' ),
            'insert_into_item'      => esc_html__( 'Insert into Event', 'wp-event-solution' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this Event', 'wp-event-solution' ),
            'items_list'            => esc_html__( 'Events list', 'wp-event-solution' ),
            'items_list_navigation' => esc_html__( 'Events list navigation', 'wp-event-solution' ),
            'filter_items_list'     => esc_html__( 'Filter froms list', 'wp-event-solution' ),
        ];
        $rewrite = [
            'slug'       => apply_filters( 'wp_eventlty_slug', $options['event_slug'] ),
            'with_front' => true,
            'pages'      => true,
            'feeds'      => false,
        ];

        $args = [
            'label'               => esc_html__( 'Events', 'wp-event-solution' ),
            'description'         => esc_html__( 'Event', 'wp-event-solution' ),
            'labels'              => $labels,
            'supports'            => [ 'title', 'editor', 'thumbnail','excerpt', 'author' ],
            'hierarchical'        => true,
            'public'              => true,
	        'show_ui'             => true,
	        'show_in_menu'        => current_user_can( 'etn_manage_event' ),
            'show_admin_column'   => false,
            'menu_icon'           => 'dashicons-text-page',
            'menu_position'       => 10,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'query_var'           => true,
            'exclude_from_search' => $options['etn_exclude_from_search'],
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'rest_base'           => $this->get_name(),

        ];

        if( current_user_can( 'etn_manage_event' ) ){
            $args['show_in_menu']        = '';
        }

        return $args;
    }

    private function user_modifiable_option() {
        $settings_options   = get_option( 'etn_event_options' );
        $options = [
            'wp_eventlty_singular_name' => esc_html__( 'Event', 'wp-event-solution' ),
            'etn_event_archive'         => esc_html__( 'Event Archive', 'wp-event-solution' ),
            'etn_event_all'             => esc_html__( 'Events', 'wp-event-solution' ),
            'event_slug'                => 'etn',
            'etn_exclude_from_search'   => false,
        ];

        if ( !empty( $settings_options['event_slug'] ) ) {
            $options['event_slug'] = str_replace( ' ', '_' , $settings_options['event_slug'] );
        }
        
        if ( !empty( $settings_options['etn_include_from_search'] ) && 'off' === $settings_options['etn_include_from_search'] ) {
            $options['etn_exclude_from_search'] = true;
        }

        return $options;
    }

}
