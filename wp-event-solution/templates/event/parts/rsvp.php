<?php
/**
 * RSVP block markup: the mount point for Eventin Pro's React RSVP form.
 *
 * The legacy two-step PHP form was retired (it posted to a handler Pro has
 * removed); only the `.etn-rsvp-form-root` mount is rendered, as in Pro's view.
 */

defined( 'ABSPATH' ) || exit;

$single_event_id = isset( $single_event_id ) ? absint( $single_event_id ) : 0;
if ( ! $single_event_id ) {
	// Try to fall back to the current queried/post ID.
	$single_event_id = absint( get_the_ID() ?: get_queried_object_id() );
}

// RSVP is a Pro module; without it (or with it toggled off) nothing mounts the form.
if (
	! $single_event_id
	|| ! class_exists( '\Etn_Pro\Core\Modules\Rsvp\Helper' )
	|| ! \Etn\Core\Addons\Helper::instance()->check_active_module( 'rsvp' )
) {
	return;
}

$rsv_settings = get_post_meta( $single_event_id, 'rsvp_settings', true );

if ( ! is_array( $rsv_settings ) || empty( $rsv_settings['enable_rsvp_form'] ) ) {
	return;
}
?>
<div class="etn-rsvp-form-root" data-post_id="<?php echo esc_attr( $single_event_id ); ?>">
	<div class="etn-rsvp-placeholder etn-lazy-trigger" style="min-height:150px;" aria-busy="true" aria-label="<?php echo esc_attr__( 'Loading RSVP form', 'wp-event-solution' ); ?>">
		<h3 class="etn-rsvp-placeholder-title"><?php echo esc_html__( 'RSVP', 'wp-event-solution' ); ?></h3>
		<div class="etn-skel etn-skel-row"></div>
		<div class="etn-skel etn-skel-row" style="width:70%;"></div>
		<div class="etn-skel etn-skel-btn"></div>
	</div>
</div>
