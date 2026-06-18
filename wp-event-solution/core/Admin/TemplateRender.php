<?php

namespace Eventin\Admin;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;
use Wpeventin;

class TemplateRender implements HookableInterface {
    /**
     * Register service
     *
     * @return  void
     */
    public function register_hooks(): void {
        add_filter('template_include', [$this, 'render_checkout_template'], PHP_INT_MAX);
        add_filter('body_class', [$this, 'add_checkout_page_class']);
    }

    /**
     * Oxygen (CT) uses its own template pipeline. If we return a hard-coded PHP template via template_include
     */
    private function is_oxygen_active(): bool {
        return defined( 'CT_VERSION' )
            || defined( 'OXYGEN_VSB_VERSION' )
            || function_exists( 'ct_template_output' )
            || function_exists( 'oxygen_vsb_output' );
    }

    /**
     * Render eventin checkout page template
     *
     * @return  void
     */
    public function render_checkout_template( $template ) {
        $query_var = get_query_var('eventin-purchase');

        if ( $query_var === 'checkout' ) {
            // Let Oxygen/theme keep control of the full template (header/footer), while we render checkout only where Oxygen outputs content.
            if ( $this->is_oxygen_active() ) {
                add_action( 'wp_enqueue_scripts', function() {
                    wp_enqueue_script( 'etn-module-purchase' );
                }, 10 );

                add_filter( 'the_content', function( $content ) {
                    static $done = false;
                    if ( $done || get_query_var( 'eventin-purchase' ) !== 'checkout' ) {
                        return $content;
                    }
                    $done = true;
                    // Oxygen will output this only if the template includes "Inner Content".
                    return $this->render_checkout_mount();
                }, 999 );

                return $template;
            }

            $checkout_template = \Wpeventin::templates_dir() . 'checkout-template.php';
            if ( file_exists( $checkout_template ) ) {
                return $checkout_template;
            }
        }

        if ( $query_var === 'preview' ) {
            $preview_template = \Wpeventin::templates_dir() . 'order-preview-template.php';
            if ( file_exists( $preview_template ) ) {
                return $preview_template;
            }
        }

        return $template;
    }

    public function add_checkout_page_class($classes) {
        $query_var = get_query_var('eventin-purchase');
        if ( $query_var === 'checkout' ) {
            $classes[] = 'eventin-checkout-page';
        }
        if ( $query_var === 'preview' ) {
            $classes[] = 'eventin-order-preview-page';
        }
        return $classes;
    }

    private function render_checkout_mount(): string {
        return '<div id="eventin-checkout" style="width: 100%;"></div>';
    }
}
