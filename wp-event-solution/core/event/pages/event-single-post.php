<?php

namespace Etn\Core\Event\Pages;

defined( 'ABSPATH' ) || exit;

class Event_single_post {

    use \Etn\Traits\Singleton;
    public function __construct() {
        add_filter('template_include', [$this, 'event_single_page'], 99);
        add_filter('template_include', [$this, 'event_archive_template'], 99);
    }

    public function event_archive_template($template) {
        if ( ! is_post_type_archive('etn') ) {
            return $template;
        }

        $enable_event_template_builder = etn_get_option( 'enable_event_template_builder' );
        
        if ( $enable_event_template_builder ) {
            $template = \Wpeventin::plugin_dir() . 'core/event/views/block-archive-template.php';
        } else {
            $template = \Wpeventin::plugin_dir() . 'core/event/views/event-archive-page.php';
        }

        return $template;
    }

    public function event_single_page( $template ) {
        global $post;

        if ( ! $post ) {
            return $template;
        }

        if ( $post->post_type !== 'etn' ) {
            return $template;
        }

        $enable_event_template_builder = etn_get_option( 'enable_event_template_builder' );

        if ( $enable_event_template_builder ) {
            $template = \Wpeventin::plugin_dir() . 'core/event/views/block-single-template.php';
        } else {
            $template = \Wpeventin::plugin_dir() . 'core/event/views/event-single-page.php';
        }

        return $template;
    }

}
