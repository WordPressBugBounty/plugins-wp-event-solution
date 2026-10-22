<?php

namespace Eventin\Template;

defined( 'ABSPATH' ) || exit;

/**
 * Template custom post type
 */
class CPT {

    /**
     * Register post type
     *
     * @return  void
     */
    public function register_post_type() {
        $labels = [
            'name'                  => __( 'ETN Templates', 'wp-event-solution' ),
            'singular_name'         => __( 'ETN Template', 'wp-event-solution' ),
            'menu_name'             => __( 'ETN Templates', 'wp-event-solution' ),
            'name_admin_bar'        => __( 'ETN Template', 'wp-event-solution' ),
            'add_new'               => __( 'Add New', 'wp-event-solution' ),
            'add_new_item'          => __( 'Add New ETN Template', 'wp-event-solution' ),
            'edit_item'             => __( 'Edit ETN Template', 'wp-event-solution' ),
            'new_item'              => __( 'New ETN Template', 'wp-event-solution' ),
            'view_item'             => __( 'View ETN Template', 'wp-event-solution' ),
            'view_items'            => __( 'View ETN Templates', 'wp-event-solution' ),
            'search_items'          => __( 'Search ETN Templates', 'wp-event-solution' ),
            'not_found'             => __( 'No ETN Templates found', 'wp-event-solution' ),
            'not_found_in_trash'    => __( 'No ETN Templates found in Trash', 'wp-event-solution' ),
            'all_items'             => __( 'All ETN Templates', 'wp-event-solution' ),
            'archives'              => __( 'ETN Template Archives', 'wp-event-solution' ),
            'attributes'            => __( 'ETN Template Attributes', 'wp-event-solution' ),
            'insert_into_item'      => __( 'Insert into ETN Template', 'wp-event-solution' ),
            'uploaded_to_this_item' => __( 'Uploaded to this ETN Template', 'wp-event-solution' ),
            'featured_image'        => __( 'Featured Image', 'wp-event-solution' ),
            'set_featured_image'    => __( 'Set Featured Image', 'wp-event-solution' ),
            'remove_featured_image' => __( 'Remove Featured Image', 'wp-event-solution' ),
            'use_featured_image'    => __( 'Use as Featured Image', 'wp-event-solution' ),
            'menu_name'             => __( 'ETN Templates', 'wp-event-solution' ),
        ];
    
        $args = [
            'labels'              => $labels,
            'public'              => true, // Whether the post type is public.
            'has_archive'         => true, // Enables archive page for the post type.
            'rewrite'             => [ 'slug' => 'etn-template' ], // Custom slug for URLs.
            'supports'            => [ 'title', 'editor', 'thumbnail' ], // Supported features.
            'show_in_rest'        => true, // Enable Gutenberg editor and REST API.
            'menu_icon'           => 'dashicons-layout', // Icon for the post type.
            'capability_type'     => 'post', // Default capabilities (can be customized).
            'publicly_queryable'  => true,
            'show_ui'             => true,  // Show in Gutendberg editor in admin panel
            'show_in_menu'        => false,
            'menu_position'       => 20, // Position in admin menu.
        ];
    
        // Register the post type.
        register_post_type( 'etn-template', $args );

        $this->enable_elementor_support();

        $this->register_template_meta();
    }

    /**
     * Register template post meta so the block editor can persist starter-template
     * data (imported CSS + remote dedup id) via editPost({ meta }).
     *
     * These keys mirror the meta that TemplateModel reads back on render.
     *
     * @return void
     */
    private function register_template_meta() {
        register_post_meta( 'etn-template', 'template_css', [
            'show_in_rest'      => true,
            'single'            => true,
            'type'              => 'string',
            'sanitize_callback' => function ( $value ) {
                // Preserve CSS/markup — strip only invalid bytes, do not run
                // sanitize_text_field (which would corrupt the stylesheet).
                return is_string( $value ) ? wp_check_invalid_utf8( $value ) : '';
            },
            'auth_callback'     => function () {
                return current_user_can( 'etn_manage_template' );
            },
        ] );

        register_post_meta( 'etn-template', 'remote_template_id', [
            'show_in_rest'      => true,
            'single'            => true,
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function () {
                return current_user_can( 'etn_manage_template' );
            },
        ] );
    }

    /**
     * Ensure the etn-template post type is enabled for the Elementor editor.
     *
     * Elementor only opens `post.php?post=ID&action=elementor` for post types
     * listed in its `elementor_cpt_support` option; otherwise it bounces back
     * to the WordPress post list. Eventin previously registered the post type
     * only while installing Elementor itself, so installs where Elementor was
     * already active never got it. Run on every CPT registration, but write
     * the option only when the value is missing (idempotent).
     *
     * @return void
     */
    private function enable_elementor_support() {
        if ( ! class_exists( '\Elementor\Plugin' ) ) {
            return;
        }

        $cpt_support = get_option( 'elementor_cpt_support', [] );

        if ( ! is_array( $cpt_support ) ) {
            $cpt_support = [];
        }

        if ( ! in_array( 'etn-template', $cpt_support, true ) ) {
            $cpt_support[] = 'etn-template';
            update_option( 'elementor_cpt_support', $cpt_support );
        }
    }
}
