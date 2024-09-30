<?php
$data = [
    'ajax_url'                    => admin_url( 'admin-ajax.php' ),
    'site_url'                    => site_url(),
    'admin_url'                   => admin_url(),
    'assets_url'                  => \Wpeventin::plugin_url("assets"),
    'evnetin_pro_active'          => ( class_exists( 'Wpeventin_Pro' ) ) ? true : false,
    'locale_name'                 => strtolower( str_replace( '_', '-', get_locale() ) ),
    'start_of_week'               => get_option( 'start_of_week' ),
    'author_id'                   => get_current_user_id(),
    'ticket_scanner_link'         => admin_url( '/edit.php?post_type=etn-attendee' ),
    'post_id'                     => get_the_ID(),
    'zoom_connection_check_nonce' => wp_create_nonce( 'zoom_connection_check_nonce' ),
    'ticket_status_nonce'         => wp_create_nonce( 'ticket_status_nonce_value' ),
    'zoom_module'                 => etn_get_option( 'etn_zoom_api' ) ? 'no' : 'yes',
    'attendee_module'             => etn_get_option( 'attendee_registration' ) ? 'no' : 'yes',
    'currency_list'               => etn_get_currency(),
    'date_format'                 => get_option( 'date_format' ),
    'date_format_string'          => date_i18n( get_option( 'date_format' ) ),
    'time_format'                 => get_option( 'time_format' ),
    'time_format_string'          => date_i18n( get_option( 'time_format' ) ),
    'timezone_list'               => etn_get_timezone(),
    'version'                     => \Wpeventin::version(),
    'payment_option_woo'          => etn_get_option( 'sell_tickets' ),
    'payment_option_stripe'       => etn_get_option( 'etn_sells_engine_stripe' ),
    'payment_option_paypal'       => etn_get_option( 'paypal_status' ),
    'currency_symbol'             => \Etn\Core\Event\Helper::instance()->get_currency(),
    'nonce'                       => wp_create_nonce('wp_rest'),
    'timetics_pro_active'         => class_exists( 'TimeticsPro' ) ? true : false ,
    'etn_purchase_login_required' => etn_get_option( 'etn_purchase_login_required' ),
];

return apply_filters( 'etn_locale_vars', $data );