<?php

/**
 * Extension Controller
 *
 * @package Eventin
 */

namespace Eventin\Extensions\Api;

defined( 'ABSPATH' ) || exit;
use Eventin\Extensions\Extension;
use Eventin\Extensions\ImportAutomation;
use Eventin\Extensions\PluginManager;
use Eventin\Input;
use Eventin\Settings;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Controller;

/**
 * Extension Controller
 *
 */
class ExtensionController extends WP_REST_Controller {

    /**
     * Store api namespace
     *
     * @since 4.0.13
     *
     * @var string $namespace
     */
    protected $namespace = 'eventin/v2';

    /**
     * Store rest base
     *
     * @since 4.0.13
     *
     * @var string $rest_base
     */
    protected $rest_base = 'extensions';

    /**
     * Register routes
     *
     * @return void
     */
    public function register_routes() {
        /*
         * Register route
         */
        register_rest_route( $this->namespace, $this->rest_base, [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'get_items'],
                'permission_callback' => function () {
                    return current_user_can( 'etn_manage_addons' );
                },
            ],
        ] );

        register_rest_route( $this->namespace, $this->rest_base, [
            [
                'methods'             => \WP_REST_Server::EDITABLE,
                'callback'            => [$this, 'update_item'],
                'permission_callback' => function () {
                    return current_user_can( 'etn_manage_addons' );
                },
            ],
        ] );

    }

    /**
     * Get all extensions
     *
     * @param   WP_Rest_Request  $request
     *
     * @return  WP_Rest_Response
     */
    public function get_items( $request ) {
        $type = ! empty( $request['type'] ) ? $request['type'] : 'all';

        $types = [
            'module' => Extension::modules(), 
            'addon'  => Extension::addons(),
            'plugin' => Extension::plugins(),
            'integration' => Extension::integrations(),
            'all_except_plugins' => Extension::all_except_plugins(),
            'all'    => Extension::get()
        ];

        return rest_ensure_response( $types[$type] );
    }

    /**
     * Enable or disable extension
     *
     * @param   WP_Rest_Request  $request  [$request description]
     *
     * @return  WP_Response | WP_Error
     */
    public function update_item( $request ) {
        $input_data = json_decode( $request->get_body(), true );

        $input  = new Input( $input_data );
        $name   = $input->get('name');
        $status = $input->get('status');

        $statuses = ['off', 'on', 'install', 'activate', 'deactivate'];

        if ( ! $name ) {
            return new WP_Error( 'extension_name_error', __( 'Please enter extension name', 'eventin' ), ['status' => 422] );
        }

        if (  ! $status ) {
            return new WP_Error( 'extension_status_error', __( 'Please enter status', 'eventin' ), ['status' => 422] );
        }

        if ( ! in_array( $status, $statuses ) ) {
            return new WP_Error( 'extension_status_error', __( 'Please enter status on/off', 'eventin' ), ['status' => 422] );
        }

        if ( ! Extension::find( $name ) ) {
            return new WP_Error( 'invalid_extension', __( 'Invalid extension.', 'eventin' ), ['status' => 422] );
        }

        // Gated on explicit user consent: the onboarding step and the Ask AI setup
        // dialog send `connect_account=true`. Without it, activating Aisentic must NOT
        // transmit the user's name/email/site to the provider.
        $connect_account = filter_var( $input->get( 'connect_account' ), FILTER_VALIDATE_BOOLEAN );
        $aisentic_connect = ( 'aisentic' === $name && 'activate' === $status && $connect_account );

        /*
         * This branch downloads and activates a plugin and then sends personal data
         * off-site, which is more than `etn_manage_addons` gates on its own. Checked
         * before anything runs, so a user without the capability installs nothing.
         * WordPress already denies install_plugins under DISALLOW_FILE_MODS, so
         * locked-down sites are covered too.
         */
        if ( $aisentic_connect && ! current_user_can( 'install_plugins' ) ) {
            return new WP_Error(
                'aisentic_setup_forbidden',
                __( 'You are not allowed to install plugins on this site.', 'eventin' ),
                ['status' => 403]
            );
        }

        if ( $name == 'zoom' ) {
            etn_update_option('etn_zoom_api',$status??null);
        }

        if ( $name == 'google_meet' ) {
            etn_update_option('etn_meet_api',$status??null);
        }

        if ( $name == 'google_map' ) {
            etn_update_option('etn_googlemap_api',$status??null);
        }

        if ( $name == 'eventin_ai' ) {
            etn_update_option('etn_ai_api',$status??null);
        }

        if ( $name == 'mail_mint' ) {
            etn_update_option( 'mail_mint_api', $status ?? null );
        }

        if ( $name == 'mailpoet' ) {
            etn_update_option( 'mailpoet_api', $status ?? null );
        }

        if ( $name == 'funnel_kit' ) {
            etn_update_option( 'funnel_kit_api', $status ?? null );
        }

        if ( $name == 'zoho_crm' ) {
            etn_update_option( 'zoho_crm_api', $status ?? null );
        }

        if ( $name == 'uncanny_automator' ) {
            etn_update_option( 'uncanny_automator_api', $status ?? null );
        }

        if ( $name == 'eventin-addon-for-surecart' ) {
            etn_update_option('etn_surecart_enabled',$status=='on'?true:false);
            if($status=='off'){
                etn_update_option('surecart_status',false);
            }
        }
        if ( $name == 'eventin-addon-for-fluentcart' ) {
            etn_update_option('etn_fluentcart_enabled',$status=='on'?true:false);
            if($status=='off'){
                etn_update_option('fluentcart_status',false);
            }
        }
        if ( $name == 'aisentic' ) {
            etn_update_option('etn_aisentic_enabled',$status=='on'?true:false);
            if($status=='off'){
                etn_update_option('aisentic_status',false);
            }
        }

        $clear_conflicts = (bool) $input->get( 'clear_conflicts' );

        if ( $name == 'stripe' ) {
            etn_update_option( 'etn_sells_engine_stripe', $status == 'on' ? 'stripe' : null );

            if ( $status == 'on' && $clear_conflicts ) {
                etn_update_option( 'sell_tickets', null );
                etn_update_option( 'payment_method', '' );
                etn_update_option( 'surecart_status', false );
                etn_update_option( 'fluentcart_status', false );
            }
        }

        if ( $name == 'paypal' ) {
            etn_update_option( 'paypal_status', $status == 'on' ? true : false );

            if ( $status == 'on' && $clear_conflicts ) {
                etn_update_option( 'sell_tickets', null );
                etn_update_option( 'payment_method', '' );
                etn_update_option( 'surecart_status', false );
                etn_update_option( 'fluentcart_status', false );
            }
        }


        $update = Extension::update( $name, $status );

        // Aisentic handshake: once Aisentic is activated, hand the organizer identity
        // the user typed (onboarding step five, or the Ask AI setup dialog) to the
        // Aisentic plugin via a WP action instead of calling its REST API from here.
        // Aisentic boots its providers on include, so activate_plugin() (run inside
        // Extension::update above) has already attached the listener in this same
        // request — the do_action below is heard.
        $aisentic_registered = null;

        if ( $aisentic_connect && PluginManager::is_activated( 'aisentic' ) ) {
            /**
             * Fires after Aisentic is activated and the user consented to connect.
             *
             * @param string $account_name Organizer/account name.
             * @param string $email        Organizer/account email.
             * @param string $site_url     Site URL to register with the provider.
             */
            /*
             * Never send an empty name: the provider refuses to create the account and
             * answers with a redirect rather than an error, so the caller only learns
             * "registered: false" with nothing to act on. The client fills this from
             * display_name, but an older bundle (or the onboarding organizer field left
             * blank) can still arrive empty.
             */
            $account_name = sanitize_text_field( $input->get( 'account_name' ) ?? '' );

            if ( '' === $account_name ) {
                $account_name = wp_get_current_user()->display_name;
            }

            do_action(
                'eventin/aisentic/register_site',
                $account_name,
                sanitize_email( $input->get( 'email' ) ?? '' ),
                esc_url_raw( $input->get( 'site_url' ) ?? site_url() )
            );

            /*
             * Read the key back rather than trusting the action. Aisentic's listener
             * swallows provider errors so a bad network never breaks activation, which
             * means a silent failure is possible and the caller has to be able to say
             * so instead of reloading into a chat that cannot reply.
             */
            $aisentic_registered = function_exists( 'aisentic_get_option' )
                && '' !== (string) aisentic_get_option( 'llmProviders.aisentic.apiKey', '' );
        }

        if($name == 'automation' && $update == 1 && $status == 'on') {
            $is_email_automation_migrated = get_option( 'etn_email_automation_migrated' );
            if ( ! $is_email_automation_migrated ) {
                ImportAutomation::create_automation_flows();
            }
        }
	    
	    // Dokan Event Publish Approval
	    // Automatically publish Dokan Vendor/Seller Event when an event is created.
	    if ( $status == "on" ) {
		    Settings::update(["dokan_event_auto_publish" => "on"]);
	    }
	    if ( $status == "off" ) {
		    Settings::update(["dokan_event_auto_publish" => ""]);
	    }
		
        
        if ( is_wp_error( $update ) ) {
            return new WP_Error( 'update_error', wp_strip_all_tags($update->get_error_message()), ['status' => 422] );
        }

        if ( ! $update ) {
            // translators: %s is the extension status action (e.g. activated, deactivated).
            return new WP_Error( 'update_error', sprintf( __( "Extension couldn't %s", 'eventin' ), $status ), ['status' => 422] );
        }

        $response = [
            'message' => __( 'Successfully updated', 'eventin' ),
        ];

        /*
         * Only present when a connect was attempted, so callers can tell "not asked
         * for" apart from "asked for and not completed". Reported, not treated as a
         * failure: Aisentic connects its Eventin integration either way, so the chat
         * loads and collects the account on the first prompt. Sending the user off to
         * finish setup by hand is what this request already did for them.
         */
        if ( null !== $aisentic_registered ) {
            $response['aisentic_registered'] = $aisentic_registered;
        }

        return rest_ensure_response( $response );
    }
}