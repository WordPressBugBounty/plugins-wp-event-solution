<?php

defined( 'ABSPATH' ) || exit; 
  //echo do_shortcode("[etn_pro_ticket_form id='" . $event_id . "' show_title='no']"); 
?>
<div class="etn-single-event-ticket-wrap" data-preview="<?php echo esc_attr( is_preview() ); ?>" >
    <?php if ( isset( $show_title ) && $show_title === "yes" ) : ?>
    <h3 class="etn-event-form-widget-title" ?>>
        <?php echo esc_html( get_the_title( $event_id ) ); ?>
    </h3>
    <?php endif; ?>

    <?php
    // A recurring PARENT sells nothing itself - each occurrence carries its own
    // tickets, listed by the recurring-event block. Matches the guard in
    // etn_after_single_event_meta_ticket_form() that the legacy templates use.
    //
    // Parent-ness is decided by post_parent, not by recurring_enabled alone: an
    // occurrence is always a child post, while recurring_enabled is only
    // *supposed* to be absent on children. Events created before that meta was
    // stripped from children (and clones/imports that copy every parent meta
    // key) can carry recurring_enabled = 'yes' on an occurrence, and keying the
    // guard off the meta alone hides the ticket form on a page that must sell.
    $is_recurring_parent = 'yes' === get_post_meta( $event_id, 'recurring_enabled', true )
        && 0 === (int) wp_get_post_parent_id( $event_id );

    if ( ! $is_recurring_parent ) {
        \Etn\Utils\Helper::eventin_ticket_widget( $event_id, $styles, null, $style_variant );
    }
    ?>
</div>
