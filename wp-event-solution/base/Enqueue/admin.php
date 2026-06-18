<?php
namespace Eventin\Enqueue;

use Wpeventin;

/**
 * Admin class
 */
class Admin {
    /**
     * Initialize the class
     *
     * @return  void
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_scripts'] );
        add_action( 'admin_enqueue_scripts', [$this, 'dequeue_conflicting_google_maps'], 999 );
        add_action( 'admin_head', [$this, 'print_google_maps_bootstrap'], 1 );
        add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'elementor_js' ) );
    }

    /**
     * Is the current admin screen the Eventin events SPA (where the venue map lives)?
     *
     * @return bool
     */
    private function is_eventin_map_screen() {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- screen detection only; compared to a literal.
        return isset( $_GET['page'] ) && 'eventin' === sanitize_key( wp_unslash( $_GET['page'] ) );
    }

    /**
     * Print Google's keyed `importLibrary` bootstrap early in <head>.
     *
     * The Maps JS API uses the FIRST loader's API key for the whole page and
     * refuses to re-key once initialized. Some themes (e.g. Enfold/Avia) load
     * Maps without a key in the footer; if theirs wins, the Eventin venue map
     * fails with ApiProjectMapError. Printing Eventin's keyed bootstrap in
     * <head> makes `google.maps` initialize with Eventin's key first, so the
     * map renders regardless of the active theme.
     *
     * @return void
     */
    public function print_google_maps_bootstrap() {
        if ( ! $this->is_eventin_map_screen() ) {
            return;
        }

        $api_key = etn_get_option( 'google_api_key' );
        if ( empty( $api_key ) ) {
            return;
        }

        $config = wp_json_encode( array( 'key' => $api_key, 'v' => 'weekly' ) );
        ?>
        <script id="eventin-google-maps-bootstrap">
        (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})(<?php echo $config; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode output is safe inline JSON. ?>);
        </script>
        <?php
    }

    /**
     * Dequeue any theme/plugin Google Maps script on the Eventin events screen.
     *
     * Eventin loads Maps itself (keyed, in <head>). A second Maps load from the
     * active theme triggers the "included multiple times" conflict and, if it is
     * keyless, breaks the map. The theme has no need to load Maps on Eventin's
     * own admin SPA, so we strip any maps.googleapis.com script here.
     *
     * @return void
     */
    public function dequeue_conflicting_google_maps() {
        if ( ! $this->is_eventin_map_screen() ) {
            return;
        }

        $scripts = wp_scripts();
        foreach ( $scripts->registered as $handle => $script ) {
            if ( $handle === 'eventin-google-maps-bootstrap' ) {
                continue;
            }
            if ( ! empty( $script->src ) && false !== strpos( $script->src, 'maps.googleapis.com' ) ) {
                wp_dequeue_script( $handle );
            }
        }
    }

    public function i18n_loader() {
        $data = [
            'baseUrl'     => false,
            'locale'      => determine_locale(),
            'domainMap'   => [],
            'domainPaths' => [],
        ];
        
        $lang_dir    = WP_LANG_DIR;
        $content_dir = WP_CONTENT_DIR;
        $abspath     = ABSPATH;
        
        if ( strpos( $lang_dir, $content_dir ) === 0 ) {
            $data['baseUrl'] = content_url( substr( trailingslashit( $lang_dir ), strlen( trailingslashit( $content_dir ) ) ) );
        } elseif ( strpos( $lang_dir, $abspath ) === 0 ) {
            $data['baseUrl'] = site_url( substr( trailingslashit( $lang_dir ), strlen( untrailingslashit( $abspath ) ) ) );
        }
        
        wp_enqueue_script('eventin-i18n');
        
        $data['domainMap']   = (object) $data['domainMap']; // Ensure it becomes a json object.
        $data['domainPaths'] = (object) $data['domainPaths']; // Ensure it becomes a json object.
        wp_add_inline_script( 'eventin-i18n', 'if (typeof wp.eventinI18nLoader === "undefined") { wp.eventinI18nLoader = {}; } wp.eventinI18nLoader.state = ' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES ) . ';' );
    }

    /**
     * Enqueue scripts and styles
     *
     * @return  void
     */
    public function enqueue_scripts( $top ) {
        wp_enqueue_style( 'etn-event-manager-admin' ); 

        if($top == 'plugins.php'){
            wp_enqueue_style( 'etn-feedback-modal-styles' );
            wp_enqueue_script('etn-fedback-modal-js');
        }
        
        $screens = [
            'toplevel_page_eventin',
            'eventin_page_etn-event-shortcode',
            'eventin_page_etn_addons',
            'eventin_page_etn-license',
            'eventin_page_eventin_get_help',
            'admin_page_etn-wizard',
            'plugins.php',
        ];

        if ( ! in_array( $top, $screens ) ) {
            return;
        }

        wp_enqueue_style( 'etn-dashboard' );
        
        // Block editor styles and scripts 
        do_action('enqueue_block_assets');
        $settings = etn_editor_settings();
        wp_add_inline_script( 'etn-dashboard', 'window.eventinEditorSettings = ' . wp_json_encode( $settings ) . ';' );
        wp_enqueue_script('wp-edit-post');

        wp_enqueue_style( 'etn-public-css' );
        
        
        //experimental enqueue by Sajib
        wp_enqueue_script('etn-dashboard' , plugins_url('build/js/dashboard.js', __FILE__), array('wp-edit-post'), \Wpeventin::version(), true);
        
        /**
         * @method wp_set_script_translations
         * It helps to load the translation file for the script
         */ 
        wp_set_script_translations( 'etn-dashboard', 'eventin' );

        wp_localize_script('etn-dashboard' , 'eventinData', array(
        'publicPath' => plugins_url('../../build/', __FILE__),
        ));

        $this->i18n_loader();

        $screen    = get_current_screen();
        $screen_id = $screen->id;
        
        if ( 'toplevel_page_eventin' === $screen_id && class_exists( 'EventinAI' ) ) {
            wp_enqueue_style( 'etn-ai' );
            wp_enqueue_script( 'etn-ai' );
        }

        
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'media-upload' );
        wp_set_script_translations( 'etn-app-index', 'eventin' );
        wp_enqueue_script( 'etn-app-index' );
        
        // Enqueue the WordPress editor scripts
        wp_enqueue_editor();
        //setting pro translations for pro components via hooks
        wp_set_script_translations( 'etn-script-pro', 'eventin-pro' );
        
       


        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }

        if ( ! wp_style_is( 'wp-color-picker', 'enqueued' ) ) {
            wp_enqueue_style( 'wp-color-picker' );
        }
        
        wp_enqueue_style( 'etn-app-index' ); 
        
        wp_enqueue_style( 'etn-onboard-index' );
        wp_enqueue_script( 'etn-onboard-index' );
        wp_set_script_translations( 'etn-onboard-index', 'eventin' );
        $localize_data = etn_get_locale_data();
        wp_localize_script( 'etn-onboard-index', 'localized_data_obj', $localize_data );
        wp_enqueue_style( 'etn-icon' );
        // Enque block editor style in events create and edit pages only
        if ( isset( $_GET['page'] ) && $_GET['page'] === 'eventin' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- admin script enqueue condition; page param compared to a literal string only.
            wp_enqueue_style( 'wp-block-editor' );
        }
    }

    /**
     * Enqueue Elementor Assets
     *
     * @return void
     */
    public function elementor_js() {
        wp_enqueue_script( 'etn-elementor-inputs', \Wpeventin::assets_url() . 'js/elementor.js', array( 'elementor-frontend' ), \Wpeventin::version(), true );
        wp_enqueue_script( 'etn-app-index' );
    }
}