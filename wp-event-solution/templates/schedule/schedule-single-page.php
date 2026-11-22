<?php

defined( 'ABSPATH' ) || exit; 
    if( wp_is_block_theme() ){
        include \Wpeventin::templates_dir() . 'template-parts/block-theme-header.php';
    }else{
        get_header();
    }
?>

<?php
$options       = get_option( 'etn_event_general_options' );
$container_cls = isset( $options['single_post_container_width_cls'] )
? $options['single_post_container_width_cls']
: '';
?>
<div class="etn-es-events-page-container etn-container <?php echo esc_attr( $container_cls ); ?>">
	<?php
	while ( have_posts() ):
		the_post();
		require \Wpeventin::templates_dir() . 'schedule/schedule.php';
	endwhile;
	?>
</div>

<?php 
    if( wp_is_block_theme() ){
        include \Wpeventin::templates_dir() . 'template-parts/block-theme-footer.php';
    }else{
        get_footer();
    }
?>
