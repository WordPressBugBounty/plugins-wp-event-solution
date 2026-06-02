<?php
/**
 * Admin Assets Class
 * 
 * @package Eventin
 */
namespace Eventin\Enqueue;

/**
 * Admin Scripts and Styles class
 */
class AdminAssets implements AssetsInterface {

    /**
     * Register scripts
     *
     * @return  array
     */
    public static function get_scripts() {
        $scripts = [
            //TODO: make deps load dynamically
            'etn-packages' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/packages.js' ),
                'deps'      => array_merge(
                    ['moment', 'react', 'react-dom', 'wp-api-fetch', 'wp-block-editor', 'wp-block-library', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-hooks', 'wp-html-entities', 'wp-i18n', 'wp-keyboard-shortcuts', 'wp-primitives', 'wp-url'],
                    ChunkManifest::vendor_deps_for( 'packages' )
                ),
                'in_footer' => false,
            ],
            'etn-app-index'     => [
                'src'       => \Wpeventin::plugin_url( 'build/js/index-calendar.js' ),
                'deps'      => array_merge(
                    [ 'jquery' ],
                    ChunkManifest::vendor_deps_for( 'index-calendar' )
                ),
                'in_footer' => true,
            ],
            'etn-onboard-index' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/index-onboard.js' ),
                'deps'      => array_merge(
                    [ 'jquery' ],
                    ChunkManifest::vendor_deps_for( 'index-onboard' )
                ),
                'in_footer' => true,
            ],
            'etn-ai' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/index-ai-script.js' ),
                'deps'      => array_merge(
                    [ 'jquery', 'wp-scripts', 'etn-packages' ],
                    ChunkManifest::vendor_deps_for( 'index-ai-script' )
                ),
                'in_footer' => true,
            ],
             'etn-html-2-canvas' => [
                'src'       => \Wpeventin::plugin_url( 'assets/lib/js/html2canvas.min.js' ),
                'deps'      => ['jquery'],
                'in_footer' => false,
            ],
            'etn-dashboard' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/dashboard.js' ),
                'deps'      => array_merge(
                    ['wp-format-library', 'etn-html-2-canvas', 'etn-packages'],
                    ChunkManifest::vendor_deps_for( 'dashboard' )
                ),
                'in_footer' => true,
            ],
            'etn-fedback-modal-js' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/feedback-modal.js' ),
                'deps'      => ['jquery'],
                'in_footer' => true,
            ],
        ];
        return apply_filters( 'etn_admin_register_scripts', $scripts );
    }




    /**
     * Get styles
     *
     * @return  array
     */
    public static function get_styles() {
        $styles = [
            'etn-onboard-index'    => [
                'src' => \Wpeventin::plugin_url( 'build/css/index-onboard.css' ),
            ],
            'etn-ai'    => [
                'src' => \Wpeventin::plugin_url( 'build/css/index-ai-style.css' ),
            ],
            'etn-event-manager-admin'    => [
                'src' => \Wpeventin::plugin_url( 'build/css/event-manager-admin.css' ),
            ],
            'etn-feedback-modal-styles'    => [
                'src' => \Wpeventin::plugin_url( 'build/css/feedback-modal-styles.css' ),
            ],
        ];

        return apply_filters( 'etn_admin_register_styles', $styles );
    }
}