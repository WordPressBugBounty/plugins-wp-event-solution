<?php
namespace Eventin\Admin;

use Eventin\Interfaces\HookableInterface;
use Wpeventin;

class TemplateRender implements HookableInterface {
    /**
     * Register service
     *
     * @return  void
     */
    public function register_hooks(): void {
        add_filter('template_include', [$this, 'render_checkout_template'],99 );
        add_filter('body_class', [$this, 'add_checkout_page_class']);
    }

    /**
     * Render eventin checkout page template
     *
     * @return  void
     */
    public function render_checkout_template( $template ) {
        $query_var = get_query_var('eventin-purchase');
    
        if ( $query_var !== 'checkout' ) {
            return $template;
        }
    
        $checkout_template = \Wpeventin::templates_dir() . 'checkout-template.php';
    
        if ( file_exists( $checkout_template ) ) {
            return $checkout_template;
        }
    
        return $template;
    }

    public function add_checkout_page_class($classes) {
        if (get_query_var('eventin-purchase') === 'checkout') {
            $classes[] = 'eventin-checkout-page';
        }
        return $classes;
    }
}
