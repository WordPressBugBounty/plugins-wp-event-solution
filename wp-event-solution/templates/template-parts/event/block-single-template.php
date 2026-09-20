<?php

defined( 'ABSPATH' ) || exit;
use Eventin\Template\TemplateModel;

if ( wp_is_block_theme() ) {
    block_header_area();
    wp_head();
} else {
    get_header();
}

    $default_template_name = [
        'event-one'     => 'event-template-one',
        'event-two'     => 'event-template-two',
        'event-three'   => 'event-template-three',
    ];

    $event_id = get_the_ID();

    // Check if password is required
    if ( post_password_required( $event_id ) ) {
        echo get_the_password_form( $event_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_the_password_form() is a trusted WP core function.
    } else {
        // Same rule as the template_include callback: a layout slug is the
        // event's own choice, only a missing template post falls back.
        $template_id = \Eventin\Event\EventTemplate::resolve_layout_id( $event_id );

        if ( ! $template_id ) {
            $template_id = etn_get_option( 'event_template', 'event-one' );
        }

        $template = new TemplateModel( $template_id );

        if ( $template && get_post_type( $template_id ) == 'etn-template' ) {
            $template->render_content( '', $event_id );
        } else {
            $default_name = isset( $default_template_name[ $template_id ] )
                ? $default_template_name[ $template_id ]
                : $default_template_name['event-one'];

            $template->render_content( $default_name, $event_id );
        }
    }

    if ( wp_is_block_theme() ) {
        block_footer_area();
        wp_footer();
    } else {
        get_footer();
    }
?>
