<?php
/**
 * Eventin Template Hooks
 *
 * Action/filter hooks used for Eventin functions/templates.
 *
 * @package Eventin\Templates
 * @version 2.3.2
 */

defined( 'ABSPATH' ) || exit;

$post_id = get_the_ID();


if ( ! $post_id ) {
   return;
}
  
/**
 * @see etn_before_single_event_content()
 */
add_action( "etn_event_content_before", "etn_before_single_event_content", 10 );

/**
 * @see etn_after_single_event_content()
 */
add_action( "etn_event_content_after", "etn_after_single_event_content", 10 );

/**
 * @see etn_single_event_template_select()
 */
add_action( "etn_single_event_template", "etn_single_event_template_select", 10 );

/**
 * @see eventin_rich_result_support()
 */
add_action( "etn_event_content_after", "eventin_rich_result_support", 10 );

/**
 * @see etn_after_event_archive_content_show_footer()
 */
add_action( 'etn_after_event_archive_content', 'etn_after_event_archive_content_show_footer', 10 );

/**
 * @see etn_before_event_archive_content_show_thumbnail()
 */
add_action( 'etn_before_event_archive_content', 'etn_before_event_archive_content_show_thumbnail', 10 );

/**
 * @see etn_after_event_archive_title_show_excerpt()
 */
add_action( 'etn_after_event_archive_title', 'etn_after_event_archive_title_show_excerpt', 10 );

/**
 * @see etn_before_event_archive_title_show_location()
 */
add_action( 'etn_before_event_archive_title', 'etn_before_event_archive_title_show_location', 10 );

/**
 * @see etn_event_archive_pagination_post_per_page()
 */
add_action( 'etn_event_archive_pagination', 'etn_event_archive_pagination_links', 10 );

/**
 * @see etn_before_recurring_event_form_content()
 */
add_action( "etn_before_recurring_event_form_content", "etn_before_recurring_event_form_content", 10, 1 );

/**
 * @see etn_after_recurring_event_form_content()
 */
add_action( "etn_after_recurring_event_form_content", "etn_after_recurring_event_form_content", 10, 1 );

/**
 * @see etn_after_single_event_meta_ticket_form()
 */
add_action( "etn_after_single_event_meta", "etn_after_single_event_meta_ticket_form", 10 );

/**
 * @see etn_after_single_event_meta_recurring_event_ticket_form()
 */
add_action( "etn_after_single_event_content_wrap", "etn_after_single_event_meta_recurring_event_ticket_form", 9 );

/**
 * @see etn_after_single_event_meta_add_calendar()
 */
add_action( "etn_after_single_event_meta", "etn_after_single_event_meta_add_to_calendar", 11, 1 );

$post_template  = get_post_meta( $post_id, 'event_layout', true );
//get global template settings
$settings_template  = etn_get_option( 'event_template', 'event-one' );
$current_template   = ! empty( $post_template ) && ! is_numeric( $post_template ) ? esc_attr( $post_template ) : ( $settings_template ? esc_attr( $settings_template ) : 'event-one' )  ;

if ( ( ETN_DEMO_SITE == true ) || ( ETN_DEMO_SITE === false && "event-one" === $current_template ) ) {

    /**
     * @see etn_before_single_event_details()
     */
    add_action( 'etn_before_single_event_details', 'etn_before_single_event_details' );

    /**
     * @see etn_before_single_event_container()
     */
    add_action( 'etn_before_single_event_container', 'etn_before_single_event_container' );

    /**
     * @see etn_before_single_event_content_wrap()
     */
    add_action( 'etn_before_single_event_content_wrap', 'etn_before_single_event_content_wrap' );

    /**
     * @see etn_after_single_event_content_schedule()
     */
    add_action( "etn_after_single_event_content_wrap", "etn_after_single_event_content_schedule", 10 );

    /**
     * @see etn_after_single_event_content_faq()
     */
    add_action( "etn_after_single_event_content_wrap", "etn_after_single_event_content_faq", 11 );

    /**
     * @see etn_before_single_event_content_title_show_meta()
     */
    add_action( "etn_before_single_event_content_title", "etn_before_single_event_content_title_show_meta", 10 );

    /**
     * @see etn_after_single_event_content_title()
     */
    add_action( "etn_after_single_event_content_title", "etn_after_single_event_content_title" );

    /**
     * @see etn_before_single_event_content_body()
     */
    add_action( "etn_before_single_event_content_body", "etn_before_single_event_content_body", 10 );

    /**
     * @see etn_after_single_event_content_body_show_meta()
     */
    add_action( "etn_after_single_event_content_body", "etn_after_single_event_content_body_show_meta", 10 );

    /**
     * @see etn_pro_after_single_event_content_body_show_locations()
     * @see etn_pro_single_event_locations_map()
     * @see etn_after_single_event_meta_speaker()
     * @see etn_pro_single_event_meta_external_link()
     *
     * Layout 1 keeps these Pro-only additions gated on Pro being active, so
     * activating layouts two & three in the free plugin does not change what
     * Layout 1 renders on either free-only or Pro sites.
     */
    if ( class_exists( 'Wpeventin_Pro' ) ) {
        add_action("etn_after_single_event_content_body", "etn_pro_after_single_event_content_body_show_locations", 11);
        add_action("etn_single_event_meta", "etn_pro_single_event_locations_map", 11);
        add_action("etn_after_single_event_content_wrap", "etn_after_single_event_meta_speaker", 10);
        add_action("etn_single_event_meta", "etn_pro_single_event_meta_external_link", 11);
    }
    /**
     * @see etn_before_single_event_meta()
     */
    add_action( "etn_before_single_event_meta", "etn_before_single_event_meta" );

    /**
     * @see etn_single_event_meta_details()
     */
    add_action( "etn_single_event_meta", "etn_single_event_meta_details", 10 );

    /**
     * @see etn_after_single_event_meta_attendee_list()
     */
    add_action( "etn_after_single_event_meta", "etn_after_single_event_meta_attendee_list", 11 );

    /**
     * @see etn_after_single_event_meta_organizers()
     */
    add_action( "etn_after_single_event_meta", "etn_after_single_event_meta_organizers", 12 );

    /**
     * @see etn_after_single_event_container_related_events()
     */
    add_action( "etn_after_single_event_container", "etn_after_single_event_container_related_events", 10 );

    /**
     * @see etn_after_single_event_details()
     */
    add_action( "etn_after_single_event_details", "etn_after_single_event_details" );

}

/**
 * Event layout two — ported from Eventin Pro so it renders with the free plugin.
 */
if ( ( ETN_DEMO_SITE == true ) || ( ETN_DEMO_SITE === false && "event-two" === $current_template ) ) {

    add_action( "etn_after_single_event_content_wrap", "etn_pro_after_single_event_two_content_organizer", 10 );
    add_action( "etn_after_single_event_content_wrap", "etn_after_single_event_meta_speaker", 10 );
    add_action( "etn_after_single_event_content_wrap", "etn_after_single_event_content_faq", 11 );
    add_action( "etn_before_single_event_content_title", "etn_pro_before_single_event_content_title_show_categories", 10 );
    add_action( "etn_before_single_event_details", "etn_pro_before_single_event_two_details_show_banner_module", 10 );
    add_action( "etn_before_single_event_details", "etn_pro_before_single_event_two_details_show_location_and_counter", 11 );
    add_action( "etn_after_single_event_content_body", "etn_pro_after_single_event_content_body_show_tags", 10 );
    add_action( "etn_after_single_event_content_body", "etn_pro_after_single_event_content_body_show_locations", 11 );
    add_action( "etn_after_single_event_content_body", "etn_pro_after_single_event_content_body_show_schedules", 12 );
    add_action( "etn_single_event_meta", "etn_pro_single_event_meta_details", 10 );
    add_action( "etn_single_event_meta", "etn_pro_single_event_meta_external_link", 10 );
    add_action( "etn_after_single_event_meta", "etn_after_single_event_meta_attendee_list", 11 );
    add_action( "etn_after_single_event_meta", "etn_pro_after_single_event_meta_related_events", 12 );

}

/**
 * Event layout three — ported from Eventin Pro so it renders with the free plugin.
 */
if ( ( ETN_DEMO_SITE == true ) || ( ETN_DEMO_SITE === false && "event-three" === $current_template ) ) {

    add_action( "etn_before_single_event_details", "etn_pro_before_single_event_three_details_show_banner_module", 10 );
    add_action( "etn_after_single_event_content_wrap", "etn_pro_after_single_event_three_content_organizer", 11 );
    add_action( "etn_after_single_event_content_wrap", "etn_after_single_event_meta_speaker", 10 );
    add_action( "etn_after_single_event_content_wrap", "etn_after_single_event_content_faq", 10 );
    add_action( "etn_after_single_event_content_body", "etn_pro_after_single_event_content_body_show_tags", 10 );
    add_action( "etn_after_single_event_content_body", "etn_pro_after_single_event_content_body_show_locations", 11 );
    add_action( "etn_after_single_event_content_body", "etn_pro_after_single_event_content_body_show_schedules", 12 );
    add_action( "etn_after_single_event_meta", "etn_pro_after_single_event_meta_related_events", 12 );
    add_action( "etn_after_single_event_meta", "etn_after_single_event_meta_attendee_list", 11 );
    add_action( "etn_single_event_meta", "etn_pro_single_event_meta_details", 10 );
    add_action( "etn_single_event_meta", "etn_pro_single_event_meta_external_link", 10 );
    add_action( "etn_after_single_event_content_title", "etn_pro_after_single_event_three_content_title_show_counter", 11 );
    add_action( "etn_before_single_event_content_title", "etn_pro_before_single_event_content_title_show_categories", 10 );
    add_action( "etn_after_single_event_content_title", "etn_pro_after_single_event_three_content_title_show_meta", 10 );

}
