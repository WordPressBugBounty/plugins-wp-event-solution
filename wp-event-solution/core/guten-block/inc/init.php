<?php

// Exit if accessed directly.

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

global $wp_version;

//register Eventin  block category
function etn_block_category( $categories, $post ) {
    return array_merge(
        $categories,
        [
            [
                'slug'  => 'eventin-blocks',
                'title' => __( 'Eventin ', 'eventin' ),
            ],
        ]
    );
}
//register block assets
function etn_block_assets() {

    // Register block styles for both frontend + backend.
    // wp_register_style(
    //     'eventin-block-style-css',
    //     plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ),
    //     is_admin() ? $wp_editor : null,
    //     null
    // );

    // // Register block editor styles for backend.
    // wp_register_style(
    //     'eventin-block-editor-style-css',
    //     plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ),
    //     [ 'wp-edit-blocks' ],
    //     null
    // );

    // Register block editor script for backend.

    wp_enqueue_script('eventin-block-js');
    wp_set_script_translations( 'eventin-block-js', 'eventin' );
    wp_enqueue_style('eventin-block-editor-style-css');
    wp_enqueue_style('eventin-calendar-block-editor-style');

    // WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
    wp_localize_script(
        'eventin-block-js',
        'tsGlobal',
        [
            'pluginDirPath' => plugin_dir_path( __DIR__ ),
            'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
			'etn_pro_active' => (( class_exists( 'Wpeventin_Pro' ) ) ? true : false),
        ]
    );
}


if( version_compare($wp_version, '5.8') >= 0){
	add_filter( 'block_categories_all', 'etn_block_category', 10, 2 );
} else{
	add_filter( 'block_categories', 'etn_block_category', 10, 2 );
}

// Hook: Block assets.
add_action( 'enqueue_block_editor_assets', 'etn_block_assets' );

//include event block
if ( file_exists( \Wpeventin::plugin_dir() . 'core/guten-block/inc/blocks/event-list.php' ) ) {
    include_once \Wpeventin::plugin_dir() . 'core/guten-block/inc/blocks/event-list.php';
}

//include speaker block
if ( file_exists( \Wpeventin::plugin_dir() . 'core/guten-block/inc/blocks/speaker-list.php' ) ) {
    include_once \Wpeventin::plugin_dir() . 'core/guten-block/inc/blocks/speaker-list.php';
}

//include zoom meeting
if ( file_exists( \Wpeventin::plugin_dir() . 'core/guten-block/inc/blocks/zoom-meeting.php' ) ) {
    include_once \Wpeventin::plugin_dir() . 'core/guten-block/inc/blocks/zoom-meeting.php';
}

//include zoom meeting
if ( file_exists( \Wpeventin::plugin_dir() . 'core/guten-block/inc/blocks/schedule-tab.php' ) ) {
    include_once \Wpeventin::plugin_dir() . 'core/guten-block/inc/blocks/schedule-tab.php';
}

//include event calendar
if ( file_exists( \Wpeventin::plugin_dir() . 'core/guten-block/inc/blocks/event-calendar.php' ) ) {
    include_once \Wpeventin::plugin_dir() . 'core/guten-block/inc/blocks/event-calendar.php';
}

//include event calendar
if ( file_exists( \Wpeventin::plugin_dir() . 'core/guten-block/inc/blocks/event-info.php' ) ) {
    include_once \Wpeventin::plugin_dir() . 'core/guten-block/inc/blocks/event-info.php';
}
