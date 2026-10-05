<?php

namespace Eventin\Template;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;

/**
 * Manage etn-template post type template loading
 *
 * @package Eventin
 * @since 4.0.43
 */
class TemplateLoader implements HookableInterface {
    /**
     * Register all hooks
     *
     * @return  void
     */
    public function register_hooks(): void {
        add_filter( 'template_include', [ $this, 'etn_template_single_page' ], 99 );
        add_filter( 'bricks/supported_post_types', [ $this, 'add_bricks_post_type_support' ] );
    }

    /**
     * Tell Bricks it may open `etn-template`, without writing to Bricks' options.
     *
     * The builder only opens post types it considers supported. This was previously
     * arranged by pushing 'etn-template' into the persisted `bricks_global_settings`
     * option while *building an edit link* — so listing templates, a read-only GET,
     * silently rewrote another plugin's site-wide configuration. Declaring support at
     * runtime achieves the same thing, changes nothing on disk, and disappears
     * cleanly when Eventin is deactivated.
     *
     * @param   array  $post_types  Post types Bricks supports.
     *
     * @return  array
     */
    public function add_bricks_post_type_support( $post_types ) {
        if ( ! is_array( $post_types ) ) {
            return $post_types;
        }

        if ( ! in_array( 'etn-template', $post_types, true ) ) {
            $post_types[] = 'etn-template';
        }

        return $post_types;
    }

    /**
     * Set etn-template single template
     *
     * @param   string  $template
     *
     * @return  string
     */
    public function etn_template_single_page( $template ) {
        global $post;

        if ( ! $post ) {
            return $template;
        }

        if ( $post->post_type !== 'etn-template' || ! is_singular( 'etn-template' ) ) {
            return $template;
        }

        // Use custom template for etn-template single view
        $custom_template = \Wpeventin::templates_dir() . 'single-etn-template.php';

        if ( file_exists( $custom_template ) ) {
            return $custom_template;
        }

        return $template;
    }
}
