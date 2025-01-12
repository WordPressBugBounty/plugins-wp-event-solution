<?php

namespace Etn\Core\Event\Pages;

defined( 'ABSPATH' ) || exit;

class Event_single_post {

    use \Etn\Traits\Singleton;
    public function __construct() {
        add_filter('template_include', [$this, 'event_single_page'], 99);
        add_filter('template_include', [$this, 'event_archive_template'], 99);
    }

    // eventin archive page template redirection
    public function event_archive_template($template) {
        if ( ! is_post_type_archive('etn') ) {
            return $template;
        }

        // redirect to elementor pro archive page if any archive template is assigned
        if ($this->is_elementor_pro_archive_page('etn_archive')) {
            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template);
            return $template;
        }else{
            $enable_event_template_builder = etn_get_option( 'enable_event_template_builder' );
            
            if ( $enable_event_template_builder ) {
                $template = \Wpeventin::plugin_dir() . 'core/event/views/block-archive-template.php';
            } else {
                $template = \Wpeventin::plugin_dir() . 'core/event/views/event-archive-page.php';
            }
        }


        return $template;
    }

    // Event single page template redirection
    public function event_single_page( $template ) {
        global $post;

        if ( ! $post ) {
            return $template;
        }

        if ( $post->post_type !== 'etn' || ! is_singular( 'etn' ) ) {
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

    // check if the archive page is build with Elementor theme builder - archive template
    public function is_elementor_pro_archive_page($post_type) {
        if (class_exists('ElementorPro\Modules\ThemeBuilder\Module')) {
            $theme_builder = \ElementorPro\Modules\ThemeBuilder\Module::instance();
            $documents = $theme_builder->get_conditions_manager()->get_documents_for_location('archive');

            if (!empty($documents)) {
                foreach ($documents as $document) {
                    $template_id = $document->get_main_id();
                    $template_document = \ElementorPro\Plugin::elementor()->documents->get( $template_id );
                    $template_conditions = $theme_builder->get_conditions_manager()->get_document_conditions( $template_document );

                    if (!empty($template_conditions) && is_array($template_conditions)) {
                        foreach ($template_conditions as $rule) {
                            if ( isset($rule['sub_name']) && $rule['name'] === 'archive' && $rule['sub_name'] === $post_type ) {
                                return true; // Found an archive template specific to 'etn'
                            }
                        }
                    }
                }
            }
        }

        return false; // No matching archive template found for 'etn'
    }

}
