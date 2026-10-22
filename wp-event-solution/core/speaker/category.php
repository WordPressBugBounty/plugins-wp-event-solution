<?php

namespace Etn\Core\Speaker;

use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Taxonomy Class.
 * Taxonomy class for taxonomy of Event.
 * @extend Inherite class \Etn\Base\taxonomy Abstract Class
 *
 * @since 1.0.0
 */
class Category extends \Etn\Base\Taxonomy {

    // set custom post type name
    public function get_name() {
        return 'etn_speaker_category';
    }

    public function get_cpt() {
        return 'etn-speaker';
    }

    // Operation custom post type
    public function flush_rewrites() {
    }

    function taxonomy() {

        $labels = [
            'name'              => esc_html__( 'Speaker Category', 'wp-event-solution' ),
            'singular_name'     => esc_html__( 'Speaker Category', 'wp-event-solution' ),
            'search_items'      => esc_html__( 'Search Category', 'wp-event-solution' ),
            'all_items'         => esc_html__( 'All Category', 'wp-event-solution' ),
            'parent_item'       => esc_html__( 'Parent Category', 'wp-event-solution' ),
            'parent_item_colon' => esc_html__( 'Parent Category:', 'wp-event-solution' ),
            'edit_item'         => esc_html__( 'Edit Category', 'wp-event-solution' ),
            'update_item'       => esc_html__( 'Update Category', 'wp-event-solution' ),
            'add_new_item'      => esc_html__( 'Add New Speaker Category', 'wp-event-solution' ),
            'new_item_name'     => esc_html__( 'New Category Name', 'wp-event-solution' ),
            'menu_name'         => esc_html__( 'Speaker Category', 'wp-event-solution' ),
        ];

        $args = [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_admin_column' => true,
            // 'show_in_menu'      => 'etn-events-manager',
            'public'            => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_in_menu'      => true,
            'query_var'         => true,
            'show_in_rest'        => true,
            'rewrite'           => [ 'slug' => 'etn-speaker-category' ],
        ];

        return $args;
    }

     /**
     * Create page
     *
     * @param string $title_of_the_page
     * @param string $content
     * @param [type] $parent_id
     * @return void
     */
    public function create_page(){
        $page_id = Helper::create_page( 'etn-speaker-category', '', null , "-"  );

        return $page_id;
        
    } 
}
