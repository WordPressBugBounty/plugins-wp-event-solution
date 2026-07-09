<?php

use \Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'etn_before_single_event_content_title_show_meta' ) ) {

	/**
	 * Show data before event title section
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	function etn_before_single_event_content_title_show_meta( $single_event_id ) {

		if ( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_TWO_ID != get_the_ID() && ETN_EVENT_TEMPLATE_THREE_ID != get_the_ID() ) ) {
			$single_event_id = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();

			if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/category-list.php' ) ) {
				include_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/category-list.php';
			} elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/category-list.php' ) ) {
				include_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/category-list.php';
			} else {
				include_once \Wpeventin::templates_dir() . 'event/category-list.php';
			}

		}

	}

}

if ( ! function_exists( 'etn_after_single_event_content_body_show_meta' ) ) {

	/**
	 * Show data after event content section
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	function etn_after_single_event_content_body_show_meta( $single_event_id ) {

		if ( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_TWO_ID != get_the_ID() && ETN_EVENT_TEMPLATE_THREE_ID != get_the_ID() ) ) {
			$single_event_id = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();

			if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/tag-list.php' ) ) {
				include_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/tag-list.php';
			} elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/tag-list.php' ) ) {
				include_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/tag-list.php';
			} else {
				include_once \Wpeventin::templates_dir() . 'event/tag-list.php';
			}

		}

	}

}

if ( ! function_exists( 'etn_single_event_meta_details' ) ) {
	/**
	 * Show data inside event meta section
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	function etn_single_event_meta_details( $single_event_id ) {

		if ( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_TWO_ID != get_the_ID() && ETN_EVENT_TEMPLATE_THREE_ID != get_the_ID() ) ) {
			$single_event_id = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();

			if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-sidebar-meta-free.php' ) ) {
				include_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-sidebar-meta-free.php';
			} elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-sidebar-meta-free.php' ) ) {
				include_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-sidebar-meta-free.php';
			} else {
				include_once \Wpeventin::templates_dir() . 'event/event-sidebar-meta-free.php';
			}

		}

	}

}

if ( ! function_exists( 'etn_after_single_event_container_related_events' ) ) {

	/**
	 * Show data after event container section
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	function etn_after_single_event_container_related_events( $single_event_id ) {

		if ( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_TWO_ID != get_the_ID() && ETN_EVENT_TEMPLATE_THREE_ID != get_the_ID() ) ) {
			$single_event_id = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();
			$event_options   = get_option( "etn_event_options" );

			if ( empty( $event_options["hide_related_event_from_details"] ) ) {

				// related events start
				Helper::related_events_widget( $single_event_id );
				// related events end
			}

		}

	}

}

if ( ! function_exists( 'etn_after_single_event_content_schedule' ) ) {

	/**
	 * Show data after event content section
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	function etn_after_single_event_content_schedule( $single_event_id ) {

		if ( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_TWO_ID != get_the_ID() && ETN_EVENT_TEMPLATE_THREE_ID != get_the_ID() ) ) {
			$single_event_id = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();
			$event_options   = get_option( "etn_event_options" );
			if ( empty( $event_options["etn_hide_schedule_from_details"] ) ) {
				if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/schedule-list.php' ) ) {
					include_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/schedule-list.php';
				} elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/schedule-list.php' ) ) {
					include_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/schedule-list.php';
				} else {
					include_once \Wpeventin::templates_dir() . 'event/schedule-list.php';
				}
			}
		}
	}
}

if ( ! function_exists( 'etn_after_single_event_content_faq' ) ) {

	/**
	 * Show data after event content section
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	function etn_after_single_event_content_faq( $single_event_id ) {

		if ( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_TWO_ID != get_the_ID() && ETN_EVENT_TEMPLATE_THREE_ID != get_the_ID() ) ) {
			$single_event_id  = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();
			$event_options    = get_option( "etn_event_options" );

			if ( ! empty( $event_options["hide_faq_from_details"] ) ) {
				return;
			}

			$default_faq_view = "";
			$faq_view         = 'event/event-faq.php';

			if ( is_file( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . $faq_view ) && file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . $faq_view ) ) {
				include get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . $faq_view;
			} elseif ( is_file( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . $faq_view ) && file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . $faq_view ) ) {
				include get_template_directory() . \Wpeventin::theme_templates_dir() . $faq_view;
			} elseif ( method_exists(\Wpeventin::class, 'templates_dir') && is_file( \Wpeventin::templates_dir() . $faq_view ) && file_exists( \Wpeventin::templates_dir() . $faq_view ) ) {
				include \Wpeventin::templates_dir() . $faq_view;
			}

		}

	}

}

if ( ! function_exists( 'etn_after_single_event_meta_organizers' ) ) {
	/**
	 * Show data after event meta section
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	function etn_after_single_event_meta_organizers( $single_event_id ) {

		if ( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_TWO_ID != get_the_ID() && ETN_EVENT_TEMPLATE_THREE_ID != get_the_ID() ) ) {

			$single_event_id      = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();
			$event_options        = get_option( "etn_event_options" );
			$etn_organizer_events = get_post_meta( $single_event_id, 'etn_event_organizer', true );

			// show event organizers
			if ( empty( $event_options["etn_hide_organizers_from_details"] ) ) {
				Helper::single_template_organizer_free( $etn_organizer_events );
				//  etn widget end
			}

		}

	}

}


if ( ! function_exists( 'etn_after_single_event_meta_ticket_form' ) ) {

	/**
	 * Show data after event meta section
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	function etn_after_single_event_meta_ticket_form( $single_event_id ) {
		$single_event_id = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();
		$disable_purchase_form = get_post_meta( $single_event_id, 'etn_disable_purchase_form', true );
		
		$rsv_settings = get_post_meta( get_the_ID(), 'rsvp_settings', true );
		$recurring_enabled = get_post_meta( get_the_ID(), 'recurring_enabled', true );
	
		// Override disable purchase form if set in RSVP settings
		$disable_purchase_form		= ! empty( $rsv_settings['etn_disable_purchase_form'] ) && $rsv_settings['etn_disable_purchase_form'] ? true : false;
		
		if ( $disable_purchase_form ) {
			return true;
		}
	
		// Whether to show ticket selector and sell tickets, are controlled in frontend now.
		?>
			<div class="etn-single-event-ticket-wrap">
				<?php if ($recurring_enabled !== 'yes') { 
							Helper::eventin_ticket_widget( $single_event_id, "", "", "style-1" );
						} ?>
			</div>
		 <?php
	}
} 

if ( ! function_exists( 'etn_after_single_event_meta_recurring_event_ticket_form' ) ) {

	/**
	 * Show data after event meta section
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	function etn_after_single_event_meta_recurring_event_ticket_form( $single_event_id ) {

		$single_event_id   = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();
		$recurring_enabled = get_post_meta( $single_event_id, 'recurring_enabled', true );

		if ( 'yes' !== $recurring_enabled ) {
			return;
		}

		$event_options    = get_option( "etn_event_options" );
		$has_child_events = Helper::get_child_events( $single_event_id );

		if ( $has_child_events ) {

			// if active woocmmerce and has ticket , show registation form
 				// for recurring events
			$child_event_ids = [];

			if ( is_array( $has_child_events ) && ! empty( $has_child_events ) ) {

				foreach ( $has_child_events as $single_child ) {
					$end_date        = date_i18n( "Y-m-d", strtotime( get_post_meta( $single_child->ID, 'etn_end_date', true ) ) );
					$current_date    = gmdate( "Y-m-d" );
					$settings        = etn_get_option();
					$hide_reccurance = ! empty( $settings['hide_past_recurring_event_from_details'] ) ? $settings['hide_past_recurring_event_from_details'] : '';

					if ( $hide_reccurance == 'on' ) {
						if ( $end_date >= $current_date ) {
							array_push( $child_event_ids, $single_child->ID );
						}
					} else {
						array_push( $child_event_ids, $single_child->ID );
					}

				}
				?>
<div class="etn-single-event-ticket-wrap">
    <?php Helper::woocommerce_recurring_events_ticket_widget( $single_event_id, $child_event_ids ); ?>
</div>
<?php
			}
			 
		}

	}

}

if ( ! function_exists( 'etn_after_single_event_meta_attendee_list' ) ) {
	/**
	 * Show data after event meta section
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	function etn_after_single_event_meta_attendee_list( $single_event_id ) {

		$single_event_id = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();

		if ( ! empty( \Etn\Utils\Helper::get_option( "attendee_registration" ) ) && ! empty( get_post_meta( $single_event_id, "attende_page_link", true ) ) ) {

			if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/attendee-list-button.php' ) ) {
				include_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/attendee-list-button.php';
			} elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/attendee-list-button.php' ) ) {
				include_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/attendee-list-button.php';
			} else {
				include_once \Wpeventin::templates_dir() . 'event/attendee-list-button.php';
			}

		}

	}

}

if ( ! function_exists( 'etn_before_recurring_event_form_content' ) ) {

	/**
	 * Show data after event meta section
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	function etn_before_recurring_event_form_content( $single_event_id ) {

		$freq = get_post_meta( $single_event_id, 'etn_event_recurrence', true );
		?>
<div class="etn-recurring-event-wrapper">
    <?php
		$freq_title = '';

		if ( $freq['recurrence_freq'] === 'day' ) {
			$freq_title .= esc_html__( 'Daily Event Schedule :', 'eventin' );
		} else if ( $freq['recurrence_freq'] === 'week' ) {
			$freq_title .= esc_html__( 'Weekly Event Schedule :', 'eventin' );
		} else if ( $freq['recurrence_freq'] === 'month' ) {
			$freq_title .= esc_html__( 'Monthly Event Schedule :', 'eventin' );
		} else if ( $freq['recurrence_freq'] === 'year' ) {
			$freq_title .= esc_html__( 'Yearly Event Schedule :', 'eventin' );
		}

		?>
    <h3 class="etn-widget-title"><?php echo esc_html( $freq_title ); ?></h3>
    <?php

	}

}

if ( ! function_exists( 'etn_after_recurring_event_form_content' ) ) {

	/**
	 * Show data after event meta section
	 *
	 * @param [type] $single_event_id
	 *
	 * @return void
	 */
	function etn_after_recurring_event_form_content( $single_event_id ) {
		?>
    <div class="etn-recurring-event-wrapper">
        <button id="seeMore" type="button">
            <?php echo esc_html__( 'Show More Event', 'eventin' ); ?>
            <i class="etn-icon etn-plus"></i>
        </button>
    </div>
</div>

<?php

	}

}

/**************************************************
 * Start - Default hooks required for all templates
 *************************************************/

if ( ! function_exists( 'etn_after_single_event_meta_add_to_calendar' ) ) {

	function etn_after_single_event_meta_add_to_calendar( $single_event_id ) {
		$event_options    = get_option( "etn_event_options" );
		$show_form_button = apply_filters( "etn_form_submit_visibility", true, $single_event_id );
		if ( isset( $event_options["checked_hide_calendar_from_details"] ) || Helper::get_child_events( $single_event_id ) || ! $show_form_button ) {
			return;
		}

		?>
<div class="etn-widget etn-add-calender-url">
    <?php

			do_action( 'etn_before_add_to_calendar_button' );

			( new \Etn\Core\Calendar\Add_Calendar\Add_Calendar() )->etn_add_to_google_calender_link( $single_event_id );

			do_action( 'etn_after_add_to_calendar_button' );
			?>
</div>
<?php
	}
}

if ( ! function_exists( 'etn_single_event_template_select' ) ) {

	/**
	 * Decide which template to show and the content that carries all the template hooks
	 *
	 * @return void
	 */
	function etn_single_event_template_select() {
		$post_id 			   = get_the_ID();
		$default_template_name = "event-one";
		$template_name 		   = etn_get_option('event_template') ?: $default_template_name;

		$event_template = '';

		$layouts = [
			'1' => 'event-one',
			'2' => 'event-two',
			'3' => 'event-three',
		];

		if ( ! empty( $layouts[$event_template] ) ) {
			$template_name = $layouts[$event_template];
		}

		$post_template  = get_post_meta( $post_id, 'event_layout', true );
		
		if ( ! empty( $post_template ) && ! is_numeric( $post_template ) ) {
			$template_name  = $post_template;
		}
		
		if ( ETN_DEMO_SITE === true ) {

			switch ( $post_id ) {
				case ETN_EVENT_TEMPLATE_ONE_ID:
					$single_template_path = \Wpeventin::templates_dir() . "event-one.php";
					break;
				case ETN_EVENT_TEMPLATE_TWO_ID:
					$single_template_path = \Wpeventin::templates_dir() . "event-two.php";
					break;
				case ETN_EVENT_TEMPLATE_THREE_ID:
					$single_template_path = \Wpeventin::templates_dir() . "event-three.php";
					break;
				default:
					$single_template_path = \Etn\Utils\Helper::prepare_event_template_path( $default_template_name, $template_name );
					break;
			}

			if ( file_exists( $single_template_path ) ) {
				include_once $single_template_path;
			}

		} else {

			//check if single page template is overriden from theme
			$post_template  = get_post_meta( $post_id, 'event_layout', true );
			
			if ( ! empty( $post_template ) && ! is_numeric( $post_template ) ) {
				$template_name  = $post_template;
			}


			//if overriden, then the overriden template has higher priority
			if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . $template_name . '.php' ) ) {
				include_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . $template_name . '.php';
			} elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . $template_name . '.php' ) ) {
				include_once get_template_directory() . \Wpeventin::theme_templates_dir() . $template_name . '.php';
			} else {

				// check if multi-template settings exists
				$single_template_path = \Etn\Utils\Helper::prepare_event_template_path( $default_template_name, $template_name );

				if ( file_exists( $single_template_path ) ) {
					include_once $single_template_path;
				}

			}

		}

	}

}

if ( ! function_exists( 'etn_before_single_event_content' ) ) {

	/**
	 * add parent markup before content
	 *
	 * @return void
	 */
	function etn_before_single_event_content() {

		$options       = get_option( 'etn_event_general_options' );
		$container_cls = isset( $options['single_post_container_width_cls'] ) ? $options['single_post_container_width_cls'] : '';
		$template_name = etn_get_option( 'event_template' );
		?>
<div class="etn-es-events-page-container <?php echo esc_attr( $container_cls . " " . $template_name ); ?>">
    <?php
	}

}

if ( ! function_exists( 'etn_after_single_event_content' ) ) {

	/**
	 * close parent markup before content
	 *
	 * @return void
	 */
	function etn_after_single_event_content() {
		?>
</div>
<?php
	}

}

if ( ! function_exists( 'etn_before_single_event_details' ) ) {

	function etn_before_single_event_details( $single_event_id ) {
		return;
	}

}

if ( ! function_exists( 'etn_before_single_event_container' ) ) {

	function etn_before_single_event_container( $single_event_id ) {
		return;
	}

}

if ( ! function_exists( 'etn_before_single_event_content_wrap' ) ) {

	function etn_before_single_event_content_wrap( $single_event_id ) {
		return;
	}

}

if ( ! function_exists( 'etn_after_single_event_content_title' ) ) {

	function etn_after_single_event_content_title( $single_event_id ) {
		return;
	}

}

if ( ! function_exists( 'etn_before_single_event_content_body' ) ) {

	function etn_before_single_event_content_body( $single_event_id ) {
		return;
	}

}

if ( ! function_exists( 'etn_before_single_event_meta' ) ) {

	function etn_before_single_event_meta( $single_event_id ) {
		return;
	}

}

/**
 * RSVP form after single event content
 */
if ( ! function_exists( 'etn_after_single_event_details_rsvp_form' ) ) {

	function etn_after_single_event_details_rsvp_form( $single_event_id ) {
		// include rsvp form
		return;
	}

}

/**
 *
 */
if ( ! function_exists( 'etn_after_single_event_details' ) ) {

	function etn_after_single_event_details( $single_event_id ) {
		return;
	}

}

/**
 * End - Default hooks required for all templates
 */

/**
 * Start - Event archive hooks
 */

if ( ! function_exists( 'etn_after_event_archive_content_show_footer' ) ) {

	function etn_after_event_archive_content_show_footer( $single_event_id ) {
		$single_event_id = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();

		if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/footer-content.php' ) ) {
			include get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/footer-content.php';
		} elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/footer-content.php' ) ) {
			include get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/footer-content.php';
		} else {
			include \Wpeventin::templates_dir() . 'event/archive/footer-content.php';
		}

	}

}

if ( ! function_exists( 'etn_before_event_archive_content_show_thumbnail' ) ) {

	function etn_before_event_archive_content_show_thumbnail( $single_event_id ) {
		$single_event_id = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();

		if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/thumbnail-content.php' ) ) {
			include get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/thumbnail-content.php';
		} elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/thumbnail-content.php' ) ) {
			include get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/thumbnail-content.php';
		} else {
			include \Wpeventin::templates_dir() . 'event/archive/thumbnail-content.php';
		}

	}

}

if ( ! function_exists( 'etn_after_event_archive_title_show_excerpt' ) ) {

	function etn_after_event_archive_title_show_excerpt( $single_event_id ) {
		$single_event_id = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();

		if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/excerpt-content.php' ) ) {
			include get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/excerpt-content.php';
		} elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/excerpt-content.php' ) ) {
			include get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/excerpt-content.php';
		} else {
			include \Wpeventin::templates_dir() . 'event/archive/excerpt-content.php';
		}

	}

}

if ( ! function_exists( 'etn_before_event_archive_title_show_location' ) ) {

	function etn_before_event_archive_title_show_location( $single_event_id ) {
		$single_event_id = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();

		if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/location-content.php' ) ) {
			include get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/location-content.php';
		} elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/location-content.php' ) ) {
			include get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/location-content.php';
		} else {
			include \Wpeventin::templates_dir() . 'event/archive/location-content.php';
		}

	}

}

/**
 * Events archive pagination template
 */

if ( ! function_exists( 'etn_event_archive_pagination_links' ) ) {

	function etn_event_archive_pagination_links( $query ) {

		if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/pagination-content.php' ) ) {
			include get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/pagination-content.php';
		} elseif ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/pagination-content.php' ) ) {
			include get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/archive/pagination-content.php';
		} else {
			include \Wpeventin::templates_dir() . 'event/archive/pagination-content.php';
		}

	}

}


/**
 * Add LD+JSON script with event data in single event page
 *
 * @param [type] $single_event_id
 *
 * @return string
 */

if ( ! function_exists( "eventin_rich_result_support" ) ) {
	function eventin_rich_result_support( $single_event_id ) {

		// Return if the setting is disabled
		$event_options = get_option( "etn_event_options" );
		if ( empty( $event_options["disable_rich_snippets_for_event"] ) ) {

			return;
		}

		$single_event_id   = ! empty( $single_event_id ) ? $single_event_id : get_the_ID();
		$single_event_data = Helper::single_template_options( $single_event_id );

		// Check location type for changing data set
		$event_terms    = ! empty( get_the_terms( $single_event_id, "etn_location" ) ) ? get_the_terms( $single_event_id, "etn_location" ) : [];
		$event_location = $single_event_data["etn_event_location"];

		if ( "new_location" === $single_event_data["etn_event_location_type"] ) {
			foreach ( $event_terms as $term ) {
				$event_location = $term->name;
			}
		}

		$event_start_date   = $single_event_data["event_start_date"];
		$event_end_date     = $single_event_data["event_end_date"];
		$event_start_time   = $single_event_data["event_start_time"];
		$event_end_time     = $single_event_data["event_end_time"];
		$event_reg_deadline = ! empty( $single_event_data["etn_deadline_value"] ) ? $single_event_data["etn_deadline_value"] : $event_end_date;
		$event_image        = ! empty( get_the_post_thumbnail_url( $single_event_id, "large" ) ) ? get_the_post_thumbnail_url( $single_event_id, "large" ) : "";
 

		// Generate ticket variation
		$ticket_variations = ! empty( get_post_meta( $single_event_id, "etn_ticket_variations", true ) ) ? get_post_meta( $single_event_id, "etn_ticket_variations", true ) : [];

		$ticket_variation = [];
		foreach ( $ticket_variations as $variation ) {

			$event_total_ticket = isset( $variation['etn_available_tickets'] ) ? absint( $variation['etn_available_tickets'] ) : 0;
			$event_sold_ticket  = isset( $variation["etn_sold_tickets"] ) ? absint( $variation["etn_sold_tickets"] ) : 0;
			$event_left_ticket  = $event_total_ticket - $event_sold_ticket;
			$stock_status       = $event_left_ticket <= 0 ? "SoldOut" : "InStock";
 			$new_variation = [
				"@type"         => "Offer",
				"name"          => $variation['etn_ticket_name'],
				"price"         => $variation['etn_ticket_price'],
				"priceCurrency" => \Etn\Core\Event\Helper::instance()->get_currency(),
				"validFrom"     => $event_reg_deadline,
				"url"           => get_the_permalink(),
				"availability"  => 'https://schema.org/' . $stock_status,
			];

			$ticket_variation[] = $new_variation;
		}

		// Generate event schema array
		$event_data = [
			"@context"            => "http://schema.org",
			"@type"               => "Event",
			"name"                => get_the_title(),
			"image"               => $event_image,
			"description"         => get_the_excerpt(),
			"startDate"           => $event_start_date . "T" . $event_start_time,
			"endDate"             => $event_end_date . "T" . $event_end_time,
			"eventStatus"         => "https://schema.org/EventScheduled",
			"eventAttendanceMode" => "https://schema.org/OfflineEventAttendanceMode",
			"location"            => [
				"@type"   => "Place",
				"address" => $event_location,
				"name"    => $event_location,
			],
			"offers"              => [
				$ticket_variation
			]
		];

		// Convert schema array into ld+json file and add into the DOM
		echo '<script type="application/ld+json">' . wp_json_encode( $event_data ) . '</script>';
	}
}

/**
 * ============================================================================
 * Event template two & three callbacks — ported from Eventin Pro.
 *
 * These render layouts two & three (banner, categories, tags, location, schedule,
 * organizers, speaker, meta, external link, related events, countdown) so the
 * layouts work with the free plugin alone. All are function_exists-guarded and
 * resolve Helper to \Etn\Utils\Helper via the file-level `use` above.
 * ============================================================================
 */
// Start - functions required for both event template-two and template-three

if ( !function_exists( 'etn_pro_before_single_event_content_title_show_categories' ) ) {

    /**
     * Show category list before single event content
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_before_single_event_content_title_show_categories( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ( ETN_EVENT_TEMPLATE_TWO_ID == get_the_ID(  ) || ETN_EVENT_TEMPLATE_THREE_ID == get_the_ID(  ) )) ){
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
    
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-category-list.php' ) ) {
                require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-category-list.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-category-list.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-category-list.php';
            } else {
                require_once \Wpeventin::templates_dir() . 'event/event-two-category-list.php';
            }
        }

    }

}
if ( !function_exists( 'etn_pro_after_single_event_content_body_show_schedules' ) ) {

    /**
     * Show schedule tabs after single event content
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_after_single_event_content_body_show_schedules( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ( ETN_EVENT_TEMPLATE_TWO_ID == get_the_ID(  ) || ETN_EVENT_TEMPLATE_THREE_ID == get_the_ID(  ) )) ){
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
    
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-schedule-tabs.php' ) ) {
                require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-schedule-tabs.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-schedule-tabs.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-schedule-tabs.php';
            } else {
                require_once \Wpeventin::templates_dir() . 'event/event-pro-schedule-tabs.php';
            }
        }

    }

}
if ( !function_exists( 'etn_pro_after_single_event_meta_related_events' ) ) {

    /**
     * Show related events on single event page
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_after_single_event_meta_related_events( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ( ETN_EVENT_TEMPLATE_TWO_ID == get_the_ID(  ) || ETN_EVENT_TEMPLATE_THREE_ID == get_the_ID(  ) )) ){
            $event_options = get_option( "etn_event_options" );
    
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
    
            // related post start
            if ( !isset( $event_options["hide_related_event_from_details"] ) ) {
    
                if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-related-events.php' ) ) {
                    require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-related-events.php';
                } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-related-events.php' ) ) {
                    require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-related-events.php';
                } else {
                    require_once \Wpeventin::templates_dir() . 'event/event-pro-related-events.php';
                }
    
            }
    
            // related events end
        }
    }

}
if ( !function_exists( 'etn_pro_single_event_meta_external_link' ) ) {

    /**
     * Show single event external link meta 
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_single_event_meta_external_link( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ( ETN_EVENT_TEMPLATE_TWO_ID == get_the_ID(  ) || ETN_EVENT_TEMPLATE_THREE_ID == get_the_ID(  ) )) ){
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
            $event_options   = get_option( "etn_event_options" );
            $data            = \Etn\Utils\Helper::single_template_options( $single_event_id );
    
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-meta-external-link.php' ) ) {
                require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-meta-external-link.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-meta-external-link.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-meta-external-link.php';
            } else {
                require_once \Wpeventin::templates_dir() . 'event/event-pro-meta-external-link.php';
            }
        }

    }

}
if ( !function_exists( 'etn_pro_single_event_meta_details' ) ) {

    /**
     * Show single event meta details
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_single_event_meta_details( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ( ETN_EVENT_TEMPLATE_TWO_ID == get_the_ID(  ) || ETN_EVENT_TEMPLATE_THREE_ID == get_the_ID(  ) )) ){
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
            $event_options   = get_option( "etn_event_options" );
            $data            = Helper::single_template_options( $single_event_id );
    
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-meta-details.php' ) ) {
                require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-meta-details.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-meta-details.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-meta-details.php';
            } else {
                require_once \Wpeventin::templates_dir() . 'event/event-pro-meta-details.php';
            }
        }

    }

}
if ( !function_exists( 'etn_pro_after_single_event_content_body_show_tags' ) ) {

    /**
     * Show tag list after single event content
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_after_single_event_content_body_show_tags( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ( ETN_EVENT_TEMPLATE_TWO_ID == get_the_ID(  ) || ETN_EVENT_TEMPLATE_THREE_ID == get_the_ID(  ) )) ){
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
    
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-tag-list.php' ) ) {
                require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-tag-list.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-tag-list.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-tag-list.php';
            } else {
                require_once \Wpeventin::templates_dir() . 'event/event-two-tag-list.php';
            }
        }

    }

}
// End - functions required for both event template-two and template-three

if ( !function_exists( 'etn_pro_after_single_event_content_body_show_locations' ) ) {

    /**
     * Show locations after single event content
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_after_single_event_content_body_show_locations( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ( ETN_EVENT_TEMPLATE_TWO_ID == get_the_ID(  ) || ETN_EVENT_TEMPLATE_THREE_ID == get_the_ID(  ) )) ){
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();

            $etn_googlemap_api = etn_get_option( 'etn_googlemap_api' ) ? 'checked' : '';

            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-location-map.php' ) ) {
                require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-location-map.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-location-map.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-location-map.php';
            } else {
                require_once \Wpeventin::templates_dir() . 'event/event-two-location-map.php';
            }
        }

    }

}
// End - functions required for both event template-two and template-three


// Start - functions required for event template-two
if ( !function_exists( 'etn_pro_before_single_event_two_details_show_banner_module' ) ) {


    /**
     * Show banner module before single event content
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_before_single_event_two_details_show_banner_module( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_TWO_ID == get_the_ID(  ) ) ){
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
    
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-banner-module.php' ) ) {
                require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-banner-module.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-banner-module.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-banner-module.php';
            }  else {
                require_once \Wpeventin::templates_dir() . 'event/event-two-banner-module.php';
            }
        }

    }

}
if ( !function_exists( 'etn_pro_before_single_event_two_details_show_location_and_counter' ) ) {

    /**
     * Show location and counter before single event template two
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_before_single_event_two_details_show_location_and_counter( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_TWO_ID == get_the_ID(  ) ) ){
    
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
            $event_options      = get_option( "etn_event_options" );
            $data               = Helper::single_template_options( $single_event_id );
            $event_start_date   = isset( $data['event_start_date'] ) ? $data['event_start_date'] : '';
            $event_end_date     = isset( $data['event_end_date'] ) ? $data['event_end_date'] : '';
            $event_start_time   = isset( $data['event_start_time'] ) ? $data['event_start_time'] : '';
            $etn_event_location = isset( $data['etn_event_location'] ) ? $data['etn_event_location'] : '';
            $etn_timezone = get_post_meta( $single_event_id, 'event_timezone', true );
            ?>
            <!-- counter area -->
            <div class="etn-event-header etn-event-single2">
                <div class="etn-container">
                    <div class="etn-row">
                        <div class="etn-col-lg-7 etn-align-self-center">
                            <?php

                                if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-location-details.php' ) ) {
                                    require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-location-details.php';
                                } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-location-details.php' ) ) {
                                    require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-two-location-details.php';
                                } else {
                                    require_once \Wpeventin::templates_dir() . 'event/event-two-location-details.php';
                                }

                                ?>
                        </div>
                        <div class="etn-col-lg-5">
                            <?php

                                if ( empty( $event_options["checked_hide_countdown_from_details"] ) ) {
                                    Helper::countdown_markup( get_post_meta( $single_event_id, 'etn_start_date', true ), $event_start_time, $etn_timezone );
                                }

                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

}
if ( !function_exists( 'etn_pro_after_single_event_two_content_organizer' ) ) {

    /**
     * Show organizer after single event content
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_after_single_event_two_content_organizer( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_TWO_ID == get_the_ID(  ) ) ){

            // etn-organizer start
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
            $event_options   = get_option( "etn_event_options" );
    
            if ( !isset( $event_options["etn_hide_organizers_from_details"] ) ) {
    
                $etn_organizer_events = get_post_meta( $single_event_id, 'etn_event_organizer', true );
                $etn_organizer_events = !empty( $etn_organizer_events ) ? $etn_organizer_events : '';
    
                if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-organizers.php' ) ) {
                    require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-organizers.php';
                } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-organizers.php' ) ) {
                    require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-organizers.php';
                } else {
                    echo Helper::single_template_organizer( $etn_organizer_events );
                }
    
            }
    
            // etn-organizer end
        }

    }

}
// End - functions required for event template-two


// Include template for speaker block in single event
if ( !function_exists( 'etn_after_single_event_meta_speaker' ) ) {
    /**
     * Show data after event meta section
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_after_single_event_meta_speaker( $single_event_id ) {
        $single_event_id      = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
        $event_options        = get_option( "etn_event_options" );
        $etn_speaker_events   = get_post_meta( $single_event_id, 'etn_event_speaker', true );
        if (  ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_TWO_ID != get_the_ID() && ETN_EVENT_TEMPLATE_THREE_ID != get_the_ID() ) ) {
            if ( empty( $event_options["etn_hide_speaker_from_details"] ) ) {
                if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-speaker.php' ) ) {
                    require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-speaker.php';
                } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-speaker.php' ) ) {
                    require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-speaker.php';
                } else {
                    echo Helper::single_template_speaker( $etn_speaker_events );
                }
            }
        }
    }
}

// Start - functions required for event template-three
if ( !function_exists( 'etn_pro_before_single_event_three_details_show_banner_module' ) ) {

    /**
     * Show banner module on single event template three
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_before_single_event_three_details_show_banner_module( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_THREE_ID == get_the_ID(  ) ) ){
    
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
    
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-three-banner-module.php' ) ) {
                require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-three-banner-module.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-three-banner-module.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-three-banner-module.php';
            } else {
                require_once \Wpeventin::templates_dir() . 'event/event-three-banner-module.php';
            }
        }

    }

}
if ( !function_exists( 'etn_pro_after_single_event_three_content_title_show_counter' ) ) {

    /**
     * Show counter after event title on single event template three
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_after_single_event_three_content_title_show_counter( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_THREE_ID == get_the_ID(  ) ) ){
    
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
            $event_options   = get_option( "etn_event_options" );
    
            $data             = Helper::single_template_options( $single_event_id );
            $event_start_time = isset( $data['event_start_time'] ) ? $data['event_start_time'] : '';
            $etn_timezone = get_post_meta( $single_event_id, 'event_timezone', true );
    
            if ( empty( $event_options["checked_hide_countdown_from_details"] ) ) {
                Helper::countdown_markup( get_post_meta( $single_event_id, 'etn_start_date', true ), $event_start_time, $etn_timezone );
            }
        }

    }

}
if ( !function_exists( 'etn_pro_after_single_event_three_content_title_show_meta' ) ) {

    /**
     * Show event location details after event title
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_after_single_event_three_content_title_show_meta( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_THREE_ID == get_the_ID(  ) ) ){
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
    
            if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-three-location-details.php' ) ) {
                require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-three-location-details.php';
            } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-three-location-details.php' ) ) {
                require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-three-location-details.php';
            } else {
                require_once \Wpeventin::templates_dir() . 'event/event-three-location-details.php';
            }
        }

    }

}
if ( !function_exists( 'etn_pro_after_single_event_three_content_organizer' ) ) {

    /**
     * Show organizer after single event content
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_after_single_event_three_content_organizer( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ETN_EVENT_TEMPLATE_THREE_ID == get_the_ID(  ) ) ){

            // etn-organizer start
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
            $event_options   = get_option( "etn_event_options" );
    
            if ( !isset( $event_options["etn_hide_organizers_from_details"] ) ) {
    
                $etn_organizer_events = get_post_meta( $single_event_id, 'etn_event_organizer', true );
                $etn_organizer_events = !empty( $etn_organizer_events ) ? $etn_organizer_events : '';
    
                if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-organizers.php' ) ) {
                    require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-organizers.php';
                } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-organizers.php' ) ) {
                    require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-organizers.php';
                } else {
                    echo Helper::single_template_organizer( $etn_organizer_events );
                }
    
            }
    
            // etn-organizer end
        }

    }

}
// End - functions required for event template-three

if ( !function_exists( 'etn_pro_single_event_locations_map' ) ) {

    /**
     * Show single event meta details
     *
     * @param [type] $single_event_id
     * @return void
     */
    function etn_pro_single_event_locations_map( $single_event_id ) {

        if( ( ETN_DEMO_SITE === false ) || ( ETN_DEMO_SITE == true && ( ETN_EVENT_TEMPLATE_ONE_ID == get_the_ID(  ) )) ){
            $single_event_id = !empty( $single_event_id ) ? $single_event_id : get_the_ID();
            $event_options   = get_option( "etn_event_options" );
            $data            = \Etn\Utils\Helper::single_template_options( $single_event_id );
            $settings = etn_get_option();
            // Honor the Google Map addon toggle by VALUE, not existence: turning the addon
            // off stores etn_googlemap_api = 'off' (the key is not deleted), so isset() was
            // always true and the map loaded even when disabled.
            $etn_googlemap_api = ( ! empty( $settings['etn_googlemap_api'] ) && 'on' === $settings['etn_googlemap_api'] ) ? 'checked' : '';

            if(get_post_meta($single_event_id, "etn_event_location_type", true) == 'new_location' && $etn_googlemap_api == 'checked'){
                if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-sidebar-location-map.php' ) ) {
                    require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-sidebar-location-map.php';
                } else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-sidebar-location-map.php' ) ) {
                    require_once get_template_directory() . \Wpeventin::theme_templates_dir() . 'event/event-pro-sidebar-location-map.php';
                } else {
                    require_once \Wpeventin::templates_dir() . 'event/event-pro-sidebar-location-map.php';
                }
            }
        }

    }

}
