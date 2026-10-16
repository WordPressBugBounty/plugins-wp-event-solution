<?php

namespace Eventin\Enqueue;

/**
 * Frontend class
 */
class Frontend
{
    /**
     * Initialize the class
     *
     * @return  void
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_head', [$this, 'print_timer_flag'], 1);
    }

    /**
     * Tell the seat map if the checkout timer is on, before any widget loads.
     *
     * The seat map (Timetics Pro) holds seats only when localStorage
     * `bookingTimerSettingsEnabled` is "true". The ticket widget wrote that
     * value only after it loaded, which is too late on a first visit, so
     * those seats were never held.
     *
     * @return  void
     */
    public function print_timer_flag()
    {
        $is_enabled = 'on' === etn_get_option('ticket_purchase_timer_enable', 'off') ? 'true' : 'false';

        wp_print_inline_script_tag(
            "try{localStorage.setItem('bookingTimerSettingsEnabled','" . $is_enabled . "');}catch(e){}"
        );
    }

    /**
     * Enqueue scripts and styles
     *
     * @return  void
     */
    public function enqueue_scripts($top)
    {
        wp_enqueue_script('eventin-i18n');
        wp_enqueue_script('etn-public');
        wp_enqueue_style('etn-icon');
        wp_enqueue_style('etn-public-css');
        //set translations

        wp_set_script_translations('etn-public', 'eventin');
        // wp_enqueue_script( 'html-to-image' ); // Don't need this. Without this file it's working fine

        //set frontend translation
        wp_set_script_translations(
            'etn-module-purchase',  // The script handle
            'eventin',              // Text domain
            plugin_dir_path(__FILE__) . 'languages' //path to language folder
        );
        wp_set_script_translations(
            'etn-app-index',  // The script handle
            'eventin',              // Text domain
            plugin_dir_path(__FILE__) . 'languages' //path to language folder
        );
        wp_set_script_translations(
            'etn-monthly-calendar', // Monthly Calendar shortcode bundle
            'eventin',
            plugin_dir_path(__FILE__) . 'languages'
        );
    }
}