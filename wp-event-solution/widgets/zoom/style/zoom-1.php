<?php

defined( 'ABSPATH' ) || exit;
$meeting     			= $settings['meeting_cache'];
$zoom_meeting_data   	= empty( $meeting ) ? [] : json_decode( $meeting );

if ( is_object( $zoom_meeting_data ) && isset( $zoom_meeting_data->id ) ){
	$zoom_data 			= (object) \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->meeting_info( $zoom_meeting_data->id );
	
	if ( isset( $zoom_data->id ) ) {
		if ( empty( $zoom_data->type ) ) {
			$zoom_data 	= json_decode( \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->get_meeting_details( $zoom_meeting_data->id ) );
		}

	$type 				= \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->meeting_type_list();
	if ( isset( $zoom_data->start_time ) ) {
		$time_zone 			  = isset( $zoom_data->timezone ) ? $zoom_data->timezone : 'Asia/Dhaka' ;
		$converted_start_time = \Etn\Core\Zoom_Meeting\Api_Handlers::instance()->convert_meeting_date_time( $zoom_data->start_time );
	}
    ?>
	<div class="meeting-wrapper etn-zoom-details <?php echo esc_attr($zoom_style); ?>">
		<div class="meeting-row meeting-header">
			<div class="meeting-info">
				<h4 class="meeting-title">
					<?php echo esc_html( $zoom_types[ $zoom_data->type ] ) . esc_html__( ' info', "wp-event-solution" ); ?>
				</h4>
			</div>
		</div><!-- row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Type', "wp-event-solution" ) ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php echo esc_html( $type[ absint( $zoom_data->type ) ]) ?></p>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Meeting ID', 'wp-event-solution' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php echo esc_html( $zoom_data->id ); ?></p>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Topic', 'wp-event-solution' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php echo esc_html( $zoom_data->topic ); ?></p>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Start Time', 'wp-event-solution' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php echo esc_html( $converted_start_time ); ?></p>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Duration', 'wp-event-solution' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php echo esc_html( $zoom_data->duration ) . esc_html__( " minutes", "wp-event-solution" ); ; ?> </p>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Time Zone', 'wp-event-solution' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<p><?php echo esc_html( $zoom_data->timezone ); ?></p>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Start URL ( Host can start meeting using this url )', 'wp-event-solution' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<a class="etn-zoom-btn" href="<?php echo esc_url( $zoom_data->start_url );?>"><?php echo esc_html__( 'Start Url', 'wp-event-solution' ); ?></a>
			</div>
		</div><!-- meeting row end -->

		<div class="meeting-row">
			<div class="meeting-info">
				<p><?php echo esc_html__( 'Join URL ( Users can join meeting using this url )', 'wp-event-solution' ); ?></p>
			</div>
			<div class="meeting-info info-right">
				<a target="_blank" class="etn-zoom-btn" href="<?php echo esc_url( $zoom_data->join_url );?>"  rel="noopener"><?php echo esc_html__( 'Join Url', 'wp-event-solution' ); ?></a>
			</div>
		</div><!-- meeting row end -->
	</div>
<?php } } else { ?>
    <div class="meeting-wrapper">
        <div class="meeting-row">
            <div class="meeting-info">
                <p><?php echo esc_html__( 'Something is wrong.', 'wp-event-solution' ); ?></p>
            </div>
        </div>
    </div>
<?php
}

