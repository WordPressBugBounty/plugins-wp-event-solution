<?php

namespace Etn\Core\Event;

use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Taxonomy Class.
 * Taxonomy class for taxonomy of Event.
 * @extend Inherite class \Etn\Base\taxonomy Abstract Class
 *
 * @since 1.0.0
 */
class Tags extends \Etn\Base\Taxonomy {

    // set custom post type name
    public function get_name() {
        return 'etn_tags';
    }

    public function get_cpt() {
        return 'etn';
    }

    // Operation custom post type
    public function flush_rewrites() {
    }

    function taxonomy() {
        $labels = [
            'name'              => esc_html__( 'Tags', 'wp-event-solution' ),
            'singular_name'     => esc_html__( 'Tags', 'wp-event-solution' ),
            'search_items'      => esc_html__( 'Search Tags', 'wp-event-solution' ),
            'all_items'         => esc_html__( 'All Tags', 'wp-event-solution' ),
            'parent_item'       => esc_html__( 'Parent Tags', 'wp-event-solution' ),
            'parent_item_colon' => esc_html__( 'Parent Tags:', 'wp-event-solution' ),
            'edit_item'         => esc_html__( 'Edit Tags', 'wp-event-solution' ),
            'update_item'       => esc_html__( 'Update Tags', 'wp-event-solution' ),
            'add_new_item'      => esc_html__( 'Add New Tags', 'wp-event-solution' ),
            'new_item_name'     => esc_html__( 'New Tags Name', 'wp-event-solution' ),
            'menu_name'         => esc_html__( 'Tags', 'wp-event-solution' ),
        ];

        $args = [
            'hierarchical'      => true,
            'has_archive'       => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'public'            => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_in_menu'      => true,
            'query_var'         => true,
            'show_in_rest'      => true,

            'rewrite'           => ['slug' => 'etn-tags'],

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
    public function create_page() {
        $page_id = Helper::create_page( 'etn-tags', '', null, "-" );

        return $page_id;

    } 

}
