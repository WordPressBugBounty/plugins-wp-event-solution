<?php

    // Add meta tag for responsive design in the head
    function etn_viewport_meta() {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
    }
    add_action('wp_head', 'etn_viewport_meta', '1');

    
    wp_head();
    
    $ticket_file_name = sanitize_title_with_dashes($attendee_name);
    $payment_status =  get_post_meta( $attendee_id, 'etn_status', true);

    $all_payment_status = [
        'success' => esc_html__('Success', 'eventin'),
        'failed'  => esc_html__('Failed', 'eventin')
    ];

    // Load ticket layout style
    wp_enqueue_style( 'etn-ticket-markup' );
    wp_enqueue_script( 'etn-pdf-gen' );
    wp_enqueue_script( 'etn-html-2-canvas' );
    wp_enqueue_script( 'etn-dom-purify-pdf' );
    wp_enqueue_script( 'html-to-image' );

    // Include QR Code related scripts when pro plugin is activated
    if(class_exists('Wpeventin_Pro')) {
        wp_enqueue_script('etn-qr-code');
        wp_enqueue_script('etn-qr-code-scanner');
        wp_enqueue_script('etn-qr-code-custom');
    }

    // Add-on selections this attendee chose. Each attendee selects independently, so
    // read the per-attendee meta rather than the order-level aggregate — otherwise two
    // attendees sharing a ticket variant would each show both attendees' selections.
    $etn_addon_rows  = [];
    $etn_addon_total = 0;

    if ( class_exists( '\Etn\Core\Attendee\Attendee_Model' ) ) {
        $etn_attendee   = new \Etn\Core\Attendee\Attendee_Model( $attendee_id );
        $etn_selections = is_array( $etn_attendee->etn_option_selections ) ? $etn_attendee->etn_option_selections : [];

        foreach ( $etn_selections as $etn_selection ) {
            $etn_addon_rows[] = $etn_selection;
            $etn_addon_total += (float) ( $etn_selection['line_total'] ?? 0 );
        }
    }
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<div class="etn-ticket-download-wrapper">
    <div class="etn-ticket-wrap" id="etn_attendee_details_to_print" >
      <div class="etn-ticket-wrapper">
            <div class="etn-ticket-main-wrapper">
                <div class="etn-ticket">
                    <?php  if(has_custom_logo()){ ?>
                          <div class="etn-ticket-logo-wrapper">
                             <?php 
                                $custom_logo_id = get_theme_mod( 'custom_logo' );
                                $image = wp_get_attachment_image_src( $custom_logo_id, 'Full' );
                            ?>
                             <img style="max-height: 70px; object-fit: cover"  src="<?php echo esc_url($image[0]); ?>" />

                            <div class="logo-shape">
                                <span class="logo-bar bar-one" ></span>
                                <span class="logo-bar bar-two" ></span>
                                <span class="logo-bar bar-three" ></span>
                            </div>
                        </div>
                    <?php    
                        }elseif( class_exists( 'ET_Builder_Element' ) ){ 
                    ?>
                        <div class="etn-ticket-logo-wrapper">
                             <?php 
                                $image = et_get_option( 'divi_logo');
                            ?>
                             <img style="max-height: 70px; object-fit: cover"  src="<?php echo esc_url($image); ?>" />

                            <div class="logo-shape">
                                <span class="logo-bar bar-one" ></span>
                                <span class="logo-bar bar-two" ></span>
                                <span class="logo-bar bar-three" ></span>
                            </div>
                        </div>
                    <?php             
                        } 
                    ?>
                  
                    <div class="etn-ticket-head">
                        <h3 class="etn-ticket-head-title"><?php echo esc_html( $event_name ) ?></h3>
                        <p class="etn-ticket-head-time"><?php echo esc_html( $date.' @ '. $time ) ?> </p>
                    </div>
                    <div class="etn-ticket-body">
                        <div class="etn-ticket-body-top<?php echo ! empty( $etn_addon_rows ) ? ' etn-ticket-body-top--with-aside' : ''; ?>">
                            <div class="etn-ticket-body-top-ul-wrapper">
                                <ul class="etn-ticket-body-top-ul">
                                    <?php 
                                        if ( class_exists( 'Wpeventin_Pro' ) ) {
                                            do_action( 'etn_pro_ticket_id', $attendee_id, $event_id );
                                        } else {
                                            $unique_id = get_post_meta( $attendee_id, 'etn_unique_ticket_id', true );

                                            if ( ! empty( $unique_id ) ) {
                                                ?>
                                                <li class="etn-ticket-body-top-li">
                                                    <span><?php echo esc_html__( "ID:", "eventin" ); ?></span>
                                                    <p class="etn-ticket-id"><?php echo esc_html( $unique_id ); ?></p>
                                                </li>
                                                <?php
                                            }
                                        }
                                    ?>
                                    <?php if ( $ticket_price !== "" && empty( $etn_addon_rows ) ) { ?>
                                        <li class="etn-ticket-body-top-li">
                                            <?php echo esc_html__( "PRICE :", "eventin" ); ?>
                                            <p>
                                                <?php
                                                    printf( '%s %s', esc_html( etn_currency_symbol() ), esc_html( $ticket_price ) );
                                                ?>
                                            </p>
                                        </li>
                                    <?php }?>
                                    <?php if ( $ticket_name !== "") { ?>
                                        <li class="etn-ticket-body-top-li flex-100">
                                            <?php echo esc_html__( "TYPE :", "eventin" ); ?> 
                                            <p><?php echo esc_html( $ticket_name ) ?></p>
                                        </li>
                                    <?php }?>
                                    <?php 
                                        if ( !empty($attendee_seat) ) {
                                        ?>
                                        <li class="etn-ticket-body-top-li flex-100">
                                            <?php echo esc_html__( "Seat :", "eventin" ); ?> 
                                            <p><?php echo esc_html( $attendee_seat ) ?></p>
                                        </li>
                                    <?php }  ?>
                                    <?php if ( $event_location !== "" && $event_location_type === 'existing_location') { ?>
                                        <li class="etn-ticket-body-top-li flex-100">
                                            <?php echo esc_html__( "VENUE :", "eventin" ); ?> 
                                            <p><?php echo esc_html( $event_location ) ?></p>
                                        </li>
                                    <?php }?>
                                    <?php if($event_location_type === 'new_location'): ?>
                                        <li class="etn-ticket-body-top-li flex-100 etn-event-location etn-taxonomy-location-meta">
                                            <?php echo esc_html__( "VENUE :", "eventin" ); ?> 
                                            <?php foreach($event_terms as $term) : ?>
                                                <span><?php echo esc_html($term->name); ?></span>
                                            <?php endforeach; ?>
                                        </li>
                                    <?php endif; ?>
                                    
	                                <?php if ( ( ! isset( $event_location_type ) || $event_location_type === "" ) && isset( $event_location ) && is_array( $event_location ) && isset( $event_location["address"] ) && strlen($event_location["address"]) ) { ?>
                                        <li class="etn-ticket-body-top-li flex-100">
			                                <?php echo esc_html__( "VENUE :", "eventin" ); ?>
                                            <p><?php echo esc_html( $event_location["address"] ) ?></p>
                                        </li>
	                                <?php }?>
                                 
	                                <?php if ( (  isset( $event_location ) && isset( $event_location["integration"] ) ) ) { ?>
                                        <li class="etn-ticket-body-top-li flex-100">
			                                <?php echo esc_html__( "VENUE :", "eventin" ); ?>
                                            <p>ONLINE</p>
                                        </li>
	                                <?php }?>
                                    
                                    
                                    <?php if ( $attendee_name !== "") { ?>
                                        <li class="etn-ticket-body-top-li">
                                            <?php echo esc_html__( "ATTENDEE :", "eventin" ); ?> 
                                            <p><?php echo esc_html( $attendee_name ) ?></p>
                                        </li>
                                    <?php }?>
                                    
                                    <?php if ( $include_phone  && $attendee_phone !== "") { ?>
                                        <li class="etn-ticket-body-top-li">
                                            <?php echo esc_html__( "PHONE :", "eventin" ); ?> 
                                            <p><?php echo esc_html( $attendee_phone ) ?></p>
                                        </li>
                                    <?php }?>
                                    <?php if ( $include_email && $attendee_email !== "" ) { ?>
                                        <li class="etn-ticket-body-top-li">
                                            <?php echo esc_html__( "EMAIL :", "eventin" ); ?> 
                                            <p><?php echo esc_html( $attendee_email ) ?></p>
                                        </li>
                                    <?php }?>

                                    <?php if($payment_status){ ?>
                                        <li class="etn-ticket-body-top-li">
                                            <?php echo esc_html__( "Payment Status :", "eventin" ); ?> 
                                            <p><?php echo esc_html( $all_payment_status[$payment_status] ); ?></p>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php if ( ! empty( $etn_addon_rows ) ) { ?>
                                <div class="etn-ticket-price-aside">
                                    <ul class="etn-ticket-body-top-ul">
                                        <?php if ( $ticket_price !== "" ) { ?>
                                            <li class="etn-ticket-body-top-li flex-100">
                                                <?php echo esc_html__( "PRICE :", "eventin" ); ?>
                                                <p><?php printf( '%s %s', esc_html( etn_currency_symbol() ), esc_html( $ticket_price ) ); ?></p>
                                            </li>
                                        <?php } ?>
                                        <?php foreach ( $etn_addon_rows as $etn_addon ) { ?>
                                            <li class="etn-ticket-body-top-li flex-100">
                                                <?php printf( '%s: %s &times; %s', esc_html( $etn_addon['field_label'] ?? '' ), esc_html( $etn_addon['choice_value'] ?? '' ), esc_html( $etn_addon['qty'] ?? 1 ) ); ?>
                                                <p><?php printf( '%s %s', esc_html( etn_currency_symbol() ), esc_html( number_format( (float) ( $etn_addon['line_total'] ?? 0 ), 2 ) ) ); ?></p>
                                            </li>
                                        <?php } ?>
                                        <li class="etn-ticket-body-top-li flex-100">
                                            <?php echo esc_html__( "TOTAL :", "eventin" ); ?>
                                            <p><?php printf( '%s %s', esc_html( etn_currency_symbol() ), esc_html( number_format( (float) $ticket_price + $etn_addon_total, 2 ) ) ); ?></p>
                                        </li>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                        <!-- <div class="etn-ticket-body-bottom"></div> -->
                    </div>
                  
                    <div class="etn-ticket-qr-code">
                        <?php
                            if( $payment_status ==='success'){
                                do_action('etn_pro_ticket_qr', $attendee_id, $event_id);
                            }
                        ?>
                    </div>
                </div>
                <!-- <div class="etn-ticket-action"></div> -->
            </div>
      </div>
    </div>
</div>
<div class="etn-download-ticket">
    <button class="etn-btn button etn-print-ticket-btn" id="etn_ticket_print_btn" data-ticketname="<?php echo esc_html( $ticket_file_name )?>" ><?php echo esc_html__( "Print", "eventin" ); ?></button>
    
    <button class="etn-btn button etn-download-ticket-btn" id="etn_ticket_download_btn" data-ticketname="<?php echo esc_html( $ticket_file_name )?>" ><?php echo esc_html__( "Download", "eventin" ); ?></button>
</div>

<?php wp_footer(); ?>

