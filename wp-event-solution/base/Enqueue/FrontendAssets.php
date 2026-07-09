<?php
/**
 * Frontend Assets Class
 * 
 * @package Eventin
 */
namespace Eventin\Enqueue;

/**
 * Admin Scripts and Styles class
 */
class FrontendAssets implements AssetsInterface {

    /**
     * Register scripts
     *
     * @return  array
     */
    public static function get_scripts() {
        $scripts = [
            // PERF: PDF/canvas libs only fire on user action (ticket download), never
            // during initial paint. 'defer' takes them off the render-blocking critical
            // path while preserving load order + jQuery dependency.
            'etn-pdf-gen' => [
                'src'       => \Wpeventin::plugin_url( 'assets/lib/js/jspdf.min.js'),
                'deps'      => ['jquery'],
                'in_footer' => false,
                'strategy'  => 'defer',
            ],
            'etn-html-2-canvas' => [
                'src'       => \Wpeventin::plugin_url( 'assets/lib/js/html2canvas.min.js' ),
                'deps'      => ['jquery'],
                'in_footer' => false,
                'strategy'  => 'defer',
            ],
            'etn-dom-purify-pdf' => [
                'src'       => \Wpeventin::plugin_url( 'assets/lib/js/purify.min.js' ),
                'deps'      => ['jquery'],
                'in_footer' => false,
                'strategy'  => 'defer',
            ],
            'html-to-image' => [
                'src'       => \Wpeventin::plugin_url( 'assets/lib/js/html-to-image.js' ),
                'deps'      => ['jquery'],
                'in_footer' => false,
                'strategy'  => 'defer',
            ],
            // jQuery countdown plugin — powers the single-event countdown on event
            // layouts two & three (ported from Pro so those layouts work without Pro).
            'etn-jquery-countdown' => [
                'src'       => \Wpeventin::plugin_url( 'assets/js/jquery.countdown.min.js' ),
                'deps'      => ['jquery'],
                'in_footer' => true,
                'strategy'  => 'defer',
            ],
            'etn-public' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/event-manager-public.js' ),
                // etn-jquery-countdown must load before the public bundle initialises
                // the layout two/three countdown via jQuery's .countdown().
                'deps'      => array_merge(
                    ['jquery', 'etn-jquery-countdown'],
                    ChunkManifest::vendor_deps_for( 'event-manager-public' )
                )
            ],
            'etn-app-index'     => [
                'src'       => \Wpeventin::plugin_url( 'build/js/index-calendar.js' ),
                'deps'      => array_merge(
                    [ 'jquery' ],
                    ChunkManifest::vendor_deps_for( 'index-calendar' )
                ),
                'in_footer' => true,
            ],
            // PERF: block frontend script enhances already-rendered block markup; it
            // does not need to run before paint. 'defer' removes it from the
            // render-blocking critical path on every event page using blocks.
            'eventin-block-js' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/gutenberg-blocks.js' ),
                'deps'      => array_merge(
                    [ 'jquery' ],
                    ChunkManifest::vendor_deps_for( 'gutenberg-blocks' )
                ),
                'in_footer' => false,
                'strategy'  => 'defer',
            ],
            // PERF: slim frontend federation host (build/js/packages-frontend.js). Exposes
            // only the runtime shares + light form components frontend bundles need
            // (window.eventin.{antd,antdIcons,icons,styled,theme,utils,components}), with
            // NO admin SPA and NO Gutenberg editor stack. Frontend bundles (Pro module-rsvp)
            // depend on this instead of 'etn-packages' so block-editor.min.js no longer loads.
            // Head (in_footer:false) so window.eventin is set before footer consumers run.
            'etn-packages-frontend' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/packages-frontend.js' ),
                'deps'      => ChunkManifest::vendor_deps_for( 'packages-frontend' ),
                'in_footer' => false,
            ],
            'etn-module-purchase' => [
                'src'       => \Wpeventin::plugin_url( 'build/js/module-purchase.js' ),
                // PERF: 'etn-packages' (the legacy packages.js shared bundle) was a dep
                // here, but it carries the whole Gutenberg editor stack (block-editor
                // ~1MB, registers core blocks at load) — needed by the admin dashboard,
                // never by the public checkout. module-purchase.js self-declares its own
                // wp-* deps via its .asset.php and references none of packages.js, so the
                // dep only dragged ~MBs of editor JS onto every event page. Removed.
                'deps'      => array_merge(
                    ['underscore', 'wp-i18n'],
                    ChunkManifest::vendor_deps_for( 'module-purchase' )
                ),
                'in_footer' => true,
            ],
        ];

        return apply_filters( 'etn_frontend_register_scripts', $scripts );
    }

    /**
     * Get styles
     *
     * @return  array
     */
    public static function get_styles() {
        $styles = [
            'etn-ticket-markup' => [
                'src' => \Wpeventin::plugin_url( 'assets/css/ticket-markup.css' ),
            ],
            'etn-public-css' => [
                'src' => \Wpeventin::plugin_url( 'build/css/event-manager-public-styles.css' )
            ],
            'etn-icon' => [
                'src' => \Wpeventin::plugin_url( 'assets/css/etn-icon.css' )
            ],
            // Countdown plugin styles for event layouts two & three (ported from Pro).
            'etn-jquery-countdown' => [
                'src' => \Wpeventin::plugin_url( 'assets/css/jquery.countdown.css' ),
            ],
        ];

        return apply_filters( 'etn_frontend_register_styles', $styles );
    }
}