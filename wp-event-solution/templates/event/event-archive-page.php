<?php
if (!defined('ABSPATH')) exit;
?>

<?php 
    if( wp_is_block_theme() ){
        include \Wpeventin::templates_dir() . 'template-parts/block-theme-header.php';
    }else{
        get_header();
    }
?>

<?php
//check if single page template is overriden from theme
//if overriden, then the overriden template has higher priority
if ( file_exists( get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'archive-event.php' ) ) {
    require_once get_stylesheet_directory() . \Wpeventin::theme_templates_dir() . 'archive-event.php';
} else if ( file_exists( get_template_directory() . \Wpeventin::theme_templates_dir() . 'archive-event.php' ) ) {
    require_once  get_template_directory() . \Wpeventin::theme_templates_dir() . 'archive-event.php';
} else if( file_exists( \Wpeventin::templates_dir() . 'archive-event.php' ) ){
    include_once \Wpeventin::templates_dir() . 'archive-event.php';
}
?>


<?php 
    if( wp_is_block_theme() ){
        include \Wpeventin::templates_dir() . 'template-parts/block-theme-footer.php';
    }else{
        get_footer();
    }
?>