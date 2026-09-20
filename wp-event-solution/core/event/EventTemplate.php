<?php

namespace Eventin\Event;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;
use Etn\Core\Event\Event_Model;

/**
 * Manage event templates
 */
class EventTemplate implements HookableInterface {
    /**
     * Register all hooks
     *
     * @return  void 
     */
    public function register_hooks(): void {
        add_filter('template_include', [$this, 'event_single_page'], 99);
        add_filter('template_include', [$this, 'event_archive_template'], 99);
    }

    /**
     * Set archive events template
     *
     * @param   string  $template
     *
     * @return  string
     */
    // eventin archive page template redirection
    public function event_archive_template($template) {
        if ( ! is_post_type_archive('etn') ) {
            return $template;
        }

        // redirect to elementor pro archive page if any archive template is assigned
        if ($this->is_elementor_pro_archive_page('etn_archive')) {
            echo wp_kses_post( \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template) );
            return $template;
        }else{
            $enable_event_template_builder = etn_get_option( 'enable_event_template_builder' );
            
            if ( $enable_event_template_builder ) {
                \Wpeventin::templates_dir() . 'blocks/event/block-archive-template.php';
            } else {
                $template = \Wpeventin::templates_dir() . 'event/event-archive-page.php';
            }
        }


        return $template;
    }

    /**
     * Set event single template
     *
     * @param   string  $template
     *
     * @return  string
     */
    // Event single page template redirection
    public function event_single_page( $template ) {
        global $post;

        if ( ! $post ) {
            return $template;
        }

        if ( $post->post_type !== 'etn' || ! is_singular( 'etn' ) ) {
            return $template;
        }

        $current_post_id = get_the_ID();
        $is_elementor_editor = get_post_meta($current_post_id, '_elementor_edit_mode', true) === 'builder';

        if (class_exists('Elementor\Plugin') && $is_elementor_editor ) {

            $page_settings_manager = \Elementor\Plugin::$instance->documents->get($current_post_id);

            if ( $page_settings_manager ) {
                $page_settings_manager = $page_settings_manager->get_settings();
            }

            if (isset($page_settings_manager['template']) && ( 'elementor_canvas' == $page_settings_manager['template'] || 'elementor_header_footer' == $page_settings_manager['template']) ) {
                return $template;
            }else{
                $template = \Wpeventin::templates_dir() . 'event/event-single-page.php';
                return $template;
            }
        }

        $enable_event_template_builder = etn_get_option( 'enable_event_template_builder', true );

        $layout_id = self::resolve_layout_id( $post->ID );

        if ( 'etn-template' === get_post_type( $layout_id ) ) {
            $template = \Wpeventin::templates_dir() . 'template-parts/event/block-single-template.php';
        } else {
            $template = \Wpeventin::templates_dir() . 'event/event-single-page.php';
        }

        return $template;
    }

    /**
     * Work out which layout an event should render.
     *
     * `event_layout` holds one of two different kinds of value:
     *   - a POST ID  — an `etn-template` builder template;
     *   - a SLUG     — a PHP layout that ships as a file ('event-one',
     *                  'event-two', 'event-three', a Pro layout, or a theme
     *                  override).
     *
     * Only the first kind can go missing, so only the first kind may fall back
     * to the global default template. Treating a slug as a missing template
     * would silently override every event the site owner switched to a PHP
     * layout.
     *
     * @param   int  $event_id
     *
     * @return  string  A template post id, or a layout slug.
     */
    public static function resolve_layout_id( $event_id ) {
        $event     = new Event_Model( $event_id );
        $layout_id = (string) $event->event_layout;

        // A slug is the event's own choice — keep it.
        if ( '' !== $layout_id && ! is_numeric( $layout_id ) ) {
            return $layout_id;
        }

        // A live builder template — keep it.
        if ( '' !== $layout_id && 'etn-template' === get_post_type( $layout_id ) ) {
            return $layout_id;
        }

        // No choice at all, or a builder template that was deleted: use the
        // global default template when there is one.
        $default_layout = etn_get_option( 'event_layout' );

        if ( $default_layout && 'etn-template' === get_post_type( $default_layout ) ) {
            return (string) $default_layout;
        }

        return $layout_id;
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
