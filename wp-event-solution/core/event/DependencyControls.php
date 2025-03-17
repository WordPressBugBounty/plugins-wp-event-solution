<?php
namespace Etn\Core\Event;

/**
 * Manage eventin dependencies
 */
class DependencyControls {

    /**
     * Register all hooks
     *
     * @return  void 
     */
    public function __construct() {

        // archive search filter.
        add_filter( 'pre_get_posts', '\Etn\Utils\Helper::event_etn_search_filter', 999999 );
        add_action( 'wp_ajax_etn_event_ajax_get_data', '\Etn\Utils\Helper::etn_event_ajax_get_data' );
        add_action( 'wp_ajax_nopriv_etn_event_ajax_get_data', '\Etn\Utils\Helper::etn_event_ajax_get_data' );

        // archive pagination filter.
        add_filter( 'pre_get_posts', '\Etn\Utils\Helper::etn_event_archive_pagination_per_page' );

        // Bricks theme compatibility
        $theme = wp_get_theme(); // gets the current theme
        if ( ! empty( $theme ) && ( 'Bricks' == $theme->name || 'Bricks' == $theme->parent_theme ) ) {
            add_filter( 'language_attributes', [ $this, 'add_class_in_html_bricks' ], 10, 2 );

        }
    }

    // Bricks theme compatibility
    public function add_class_in_html_bricks( $output, $doctype ) {
        if ( 'html' !== $doctype ) {
            return $output;
        }
        $output .= ' class="no-js no-svg bricks_parent"';

        return $output;
    }

}
