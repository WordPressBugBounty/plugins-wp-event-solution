<?php
/**
 * The Template for displaying single speaker
 *
 * @see         
 * @package     Eventin\Templates
 * @version     2.3.2
 */

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

    get_header();

    $author_id = get_queried_object_id();
		/**
		 * etn_before_single_speaker_content hook.
		 */
         // do_action( "etn_speaker_content_before" );
    ?>

    <?php
        
        //show woocommerce notice
        if ( class_exists( 'WooCommerce' ) ) {
            wc_print_notices();
        }

        /**
		 * etn_single_speaker_template_select hook.
		 */
        do_action( "etn_single_speaker_template",  $author_id ); ?>

    <?php 
    wp_footer();