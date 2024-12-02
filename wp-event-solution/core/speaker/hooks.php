<?php

namespace Etn\Core\Speaker;

use \Etn\Core\Speaker\Pages\Speaker_single_post;
use Etn\Core\Speaker\Views\Parts\TemplateHooks as PartsTemplateHooks;
use TemplateHooks;

defined( 'ABSPATH' ) || exit;

class Hooks {

    use \Etn\Traits\Singleton;

    public $cpt;
    public $action;
    public $base;
    public $speaker;
    public $category;
    public $settings;
    public $spaeker_action;

    public $actionPost_type = ['etn-speaker'];

    public function Init() {

        $this->cpt      = new Cpt();
        $this->category = new Category();
        $this->action   = new Action();
        $this->settings = new Settings( 'etn', '1.0' );

        // custom post meta

        $_metabox = new \Etn\Core\Metaboxs\Speaker_meta();

        add_action( 'add_meta_boxes', [$_metabox, 'register_meta_boxes'] );
        add_action( 'save_post', [$_metabox, 'save_meta_box_data'] );
 
        $this->add_single_page_template();
        add_action( 'init', [$this, 'add_default_speaker_categories'], 99999 );

        //Add column
        add_filter('manage_etn-speaker_posts_columns', [$this, 'speaker_column_headers']);
        add_action('manage_etn-speaker_posts_custom_column', [$this, 'speaker_column_data'], 10, 2);

        // Speaker single page template hooks
        $this->speaker_single_page_hooks();

        add_filter( 'wp_insert_post_data', [$this, 'etn_set_speaker_title'], 500, 2 );

        include_once \Wpeventin::core_dir() . 'speaker/api.php';
        include_once \Wpeventin::core_dir() . 'speaker/api-taxonomy.php';

        // Add bulk actions.
        add_filter( 'bulk_actions-edit-etn-speaker', [ $this, 'add_bulk_actions' ] );

        add_filter( 'handle_bulk_actions-edit-etn-speaker', [ $this, 'handle_export_bulk_action' ], 10, 3 );
        add_filter('author_link', [$this, 'custom_speaker_link'], 10, 3);
        add_action('init', [$this, 'speaker_rewrite_rules']);


        add_filter( 'user_row_actions', [$this, 'user_row_action_organizer'], 10, 2 );
        add_filter( 'user_row_actions', [$this, 'user_row_action_speaker'], 10, 2 );

        add_action( 'admin_init', [$this, 'make_speaker_organizer'] );

        add_filter( 'users_list_table_query_args', [ $this, 'hide_speakers_from_users' ] );

    }  

    /**
     * Override speaker title from speaker post meta
     *
     * @param [type] $data
     * @param [type] $postarr
     * @return void
     */
    public function etn_set_speaker_title( $data, $postarr ) {

        if ( 'etn-speaker' == $data['post_type'] ) {

            if ( isset( $postarr['etn_speaker_title'] ) ) {
                $speaker_title = sanitize_text_field( $postarr['etn_speaker_title'] );
            } else {
                $speaker_title = get_post_meta( $postarr['ID'], 'etn_speaker_title', true );
            }

            if ( isset( $postarr['etn_speaker_summery'] ) ) {
                $speaker_content = sanitize_text_field( $postarr['etn_speaker_summery'] );
            } else {
                $speaker_content = get_post_meta( $postarr['ID'], 'etn_speaker_summery', true );
            }

            $post_slug    = sanitize_title_with_dashes( $speaker_title, '', 'save' );
            $speaker_slug = sanitize_title( $post_slug );

            $data['post_title']     = $speaker_title;
            $data['post_name']      = $speaker_slug;
            $data['post_content']   = $speaker_content;
        }

        return $data;
    }

    /**
     * Speaker single page template hooks
     */
    public function speaker_single_page_hooks(){
        if ( file_exists(\Wpeventin::core_dir() ."speaker/views/template-hooks.php") ) {
             include_once \Wpeventin::core_dir() ."speaker/views/template-hooks.php";
        }
        if ( file_exists(\Wpeventin::core_dir() ."speaker/views/template-functions.php") ) {
            include_once \Wpeventin::core_dir() ."speaker/views/template-functions.php";
        }
    }
 

    /**
     * Insert two categories of speaker cpt by default
     *
     * @return void
     */
    public function add_default_speaker_categories() {

        $org_term = term_exists( 'Organizer', 'etn_speaker_category' );

        if ( $org_term === null ) {
            wp_insert_term(
                'Organizer',
                'etn_speaker_category',
                [
                    'description' => 'Organizer of event',
                    'slug'        => 'organizer',
                    'parent'      => 0,
                ]
            );
        }

        $speaker_term = term_exists( 'Speaker', 'etn_speaker_category' );

        if ( $speaker_term === null ) {
            wp_insert_term(
                'Speaker',
                'etn_speaker_category',
                [
                    'description' => 'Speaker of schedule',
                    'slug'        => 'speaker',
                    'parent'      => 0,
                ]
            );
        }

        // create a new page
        $this->category->create_page();

    }

    public function add_single_page_template() {
        $page = new Speaker_single_post();
    }
    
    /**
     * Column name
     */
    public function speaker_column_headers( $columns ) {
        $new_item["id"] = esc_html__("Id", "eventin");
        $new_array = array_slice($columns, 0, 1, true) + $new_item + array_slice($columns, 1, count($columns)-1, true);
        return $new_array;
    }

    /**
     * Return row
     */
    public function speaker_column_data( $column, $post_id ) {
        switch ( $column ) {
        case 'id':
            echo intval( $post_id );
            break;
        }

    }

    /**
     * Add bulk action on schedule post type
     *
     * @param   array  $bulk_actions
     *
     * @return  array
     */
    public function add_bulk_actions( $bulk_actions ) {
        $bulk_actions['export-csv']  = __( 'Export CSV', 'eventin' );
        $bulk_actions['export-json'] = __( 'Export JSON', 'eventin' );

        return $bulk_actions;
    }

    /**
     * Handle bulk action for export
     *
     * @param   string  $redirect_url
     * @param   string  $action
     * @param   array  $post_ids
     *
     * @return  string
     */
    public function handle_export_bulk_action( $redirect_url, $action, $post_ids ) {
        $actions = [
            'export-csv',
            'export-json'
        ];

        if ( ! in_array( $action, $actions ) ) {
            return $redirect_url;
        }

        $export_type = 'json';

        if ( 'export-csv' == $action ) {
            $export_type = 'csv';
        }

        $schedule_exporter = new Speaker_Exporter();
        $schedule_exporter->export( $post_ids, $export_type );
    }

    /**
     * Add a rewrite rule to enable pretty URLs for speakers.
     *
     * By default, WordPress uses `author` as the slug for the author archive. This
     * function adds a rewrite rule that allows the slug to be customized via the
     * `etn_event_options` option.
     *
     * @since 1.0.0
     * @access public
     */
    public function speaker_rewrite_rules() {
        $settings_options = get_option('etn_event_options'); 
        if (!empty($settings_options['speaker_slug'])) {
            $slug = sanitize_title($settings_options['speaker_slug']);
            add_rewrite_rule("^$slug/([^/]+)/?", 'index.php?author_name=$matches[1]', 'top');
        }
    }
    
    /**
     * Change the author slug to 'speakers'
     *
     * @param string $link The link to the author page
     * @param int $author_id The ID of the author
     * @param string $author_nicename The nicename of the author
     * @return string The modified link
     */
    public function custom_speaker_link($link, $author_id, $author_nicename) {  
        $settings_options = get_option('etn_event_options'); 
        if (!empty($settings_options['speaker_slug'])) {
            $slug = sanitize_title($settings_options['speaker_slug']);
            
            // Get the user object
            $user = get_userdata($author_id);
    
            // Check if the user has the role 'etn-speaker' or 'etn-organizer'
            if (in_array('etn-speaker', (array) $user->roles) || in_array('etn-organizer', (array) $user->roles)) {
                // Set base for speakers and organizers
                $link = home_url("/$slug/" . $author_nicename);
            }
        }
        return $link;
    }


    /**
     * Add or remove organizer role in user row actions
     *
     * @param array $actions
     * @param WP_User $user_object
     * @return array
     */
    public function user_row_action_organizer($actions, $user_object) {
        $is_organizer = in_array( 'etn-organizer', $user_object->roles );
        $button_text = $is_organizer ? esc_html__( 'Remove from Organizer', 'eventin' ) : esc_html__( 'Make Organizer', 'eventin' );
        $action = $is_organizer ? 'remove_organizer' : 'make_organizer';

        $actions['organizer'] = "<a class='etn-organizer' href='" . wp_nonce_url("users.php?action=$action&amp;users=$user_object->ID", 'bulk-users') . "'>" . $button_text . '</a>';

        return $actions;
    }

    /**
     * Add or remove speaker role in user row actions
     *
     * @param array $actions
     * @param WP_User $user_object
     * @return array
     */
    public function user_row_action_speaker($actions, $user_object) {
        
        $is_speaker = in_array( 'etn-speaker', $user_object->roles );
        $button_text = $is_speaker ? esc_html__( 'Remove from Speaker', 'eventin' ) : esc_html__( 'Make Speaker', 'eventin' );
        $action = $is_speaker ? 'remove_speaker' : 'make_speaker';

        $actions['speaker'] = "<a class='etn-speaker' href='" . wp_nonce_url("users.php?action=$action&amp;users=$user_object->ID", 'bulk-users') . "'>" . $button_text . '</a>';

        return $actions;
    }

    /**
     * Handle make or remove organizer and speaker actions
     *
     * @return void
     */
    public function make_speaker_organizer() { 

        $action  = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
        $user_id = isset( $_GET['users'] ) ? intval( $_GET['users'] ) : 0;
        $nonce   = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
        $user    = get_userdata( $user_id );
    
        if ( !wp_verify_nonce( $nonce, 'bulk-users' ) ) {
            return;
        }

        $capability = current_user_can( 'manage_options' );
        if( !$capability ) return;

        if ( 'make_speaker' === $action ) {
            $user->add_role( 'etn-speaker' );
        }

        if ( 'remove_speaker' === $action ) {
            $user->remove_role( 'etn-speaker' );
        }

        if ( 'make_organizer' === $action ) {
            $user->add_role( 'etn-organizer' );
        }

        if ( 'remove_organizer' === $action ) {
            $user->remove_role( 'etn-organizer' );
        }
    }
    
    /**
     * Hihde users from user list table
     *
     * @param   array  $query_args  [$query_args description]
     *
     * @return  array
     */
    public function hide_speakers_from_users( $query_args ) {
        $args = [
            'role__in'    => ['etn-speaker', 'etn-organizer'],
            'meta_query'  => [ 
                [
                    'key'     => 'hide_user',
                    'value'   => '1', // Check if hide_user is true
                    'compare' => '='
                ]
            ],
            'fields'      => 'ID', // Return only user IDs
            'number'      => -1,   // Retrieve all matching users
        ];

        $users = get_users( $args );
        $hidden_users = $users;

        $query_args['exclude'] = $hidden_users;

        return $query_args;
    }
}
