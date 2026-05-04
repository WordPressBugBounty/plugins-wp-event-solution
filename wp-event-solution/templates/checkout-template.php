<?php
/**
 * Eventin checkout page template.
 *
 * When a customer arrives via a waiting-list payment link the URL carries
 *   ?order_id=<int>&order_token=<hex>
 * The token is validated server-side, and a small inline <script> seeds
 * localStorage with the cart context so the React checkout app initialises
 * correctly without asking the customer to re-select tickets.
 */

// ---------------------------------------------------------------
// Waiting-list: pre-populate localStorage cart context.
// ---------------------------------------------------------------
// Default: clear any stale payment-link flag from a prior session.
$wl_cart_script = '<script>try{localStorage.removeItem("etn_is_payment_link");}catch(e){}</script>';

$raw_order_id    = isset( $_GET['order_id'] )    ? intval( $_GET['order_id'] )                                  : 0;  // phpcs:ignore WordPress.Security.NonceVerification
$raw_order_token = isset( $_GET['order_token'] ) ? sanitize_text_field( wp_unslash( $_GET['order_token'] ) )    : ''; // phpcs:ignore WordPress.Security.NonceVerification

if ( $raw_order_id && $raw_order_token ) {
    $stored_token = get_post_meta( $raw_order_id, '_order_access_token', true );

    if ( $stored_token && hash_equals( $stored_token, $raw_order_token ) ) {
        $wl_order = new \Eventin\Order\OrderModel( $raw_order_id );

        if ( 'waiting' === $wl_order->status ) {
            $event_id    = intval( $wl_order->event_id );
            $event_post  = get_post( $event_id );
            $event_model = new \Etn\Core\Event\Event_Model( $event_id );
            $ev_tickets  = etn_safe_decode( get_post_meta( $event_id, 'etn_ticket_variations', true ) );
            $location    = get_post_meta( $event_id, 'etn_event_location', true );

            // Build ticketCounts keyed by slug (mirrors the JS cart format).
            $ticket_counts = [];
            if ( is_array( $wl_order->tickets ) ) {
                foreach ( $wl_order->tickets as $t ) {
                    $slug  = $t['ticket_slug'] ?? '';
                    $qty   = intval( $t['ticket_quantity'] ?? 0 );
                    $price = 0;
                    $name  = '';

                    if ( is_array( $ev_tickets ) ) {
                        foreach ( $ev_tickets as $et ) {
                            if ( isset( $et['etn_ticket_slug'] ) && $et['etn_ticket_slug'] === $slug ) {
                                $price = floatval( $et['etn_ticket_price'] ?? 0 );
                                $name  = $et['etn_ticket_name'] ?? '';
                                break;
                            }
                        }
                    }

                    if ( $slug ) {
                        $ticket_counts[ $slug ] = [
                            'name'     => $name,
                            'quantity' => $qty,
                            'price'    => $price,
                        ];
                    }
                }
            }

            $cart_context = [
                'eventInformation' => [
                    'id'          => $event_id,
                    'title'       => $event_post ? $event_post->post_title : '',
                    'extra_fields' => [],
                    'location'    => is_array( $location ) ? $location : [],
                    'timezone'    => $event_model->event_timezone ?: get_option( 'timezone_string' ),
                    'start_date'  => $event_model->etn_start_date,
                    'start_time'  => $event_model->etn_start_time,
                    'link'        => get_permalink( $event_id ),
                ],
                'ticketCounts' => $ticket_counts,
                'totalPrice'   => floatval( $wl_order->total_price ),
            ];

            $event_data = [
                'title'      => $cart_context['eventInformation']['title'],
                'location'   => $cart_context['eventInformation']['location'],
                'start_date' => $cart_context['eventInformation']['start_date'],
                'start_time' => $cart_context['eventInformation']['start_time'],
                'link'       => $cart_context['eventInformation']['link'],
            ];

            // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
            $wl_cart_script = '<script>(function(){'
                . 'var c=' . wp_json_encode( $cart_context ) . ','
                . 'e=' . wp_json_encode( $event_data ) . ';'
                . 'try{'
                . 'localStorage.setItem("etn_cart_info",JSON.stringify(c));'
                . 'localStorage.setItem("etn_event_data",JSON.stringify(e));'
                . 'localStorage.setItem("etn_order_id",' . wp_json_encode( (string) $raw_order_id ) . ');'
                . 'localStorage.setItem("etn_order_token",' . wp_json_encode( $raw_order_token ) . ');'
                . 'localStorage.setItem("etn_is_payment_link","true");'
                . '}catch(err){}'
                . '})();</script>';
            // phpcs:enable
        }
    }
}
// ---------------------------------------------------------------

if ( wp_is_block_theme() ) {
    block_header_area();
    wp_head();
} else {
    get_header();
}

// purchase module script
wp_enqueue_script( 'etn-module-purchase' );
?>

<?php
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built entirely from wp_json_encode + server-controlled data, no user input echoed raw
echo $wl_cart_script;
?>

<div id="eventin-checkout" style="width: 100%;"></div>

<?php
if ( wp_is_block_theme() ) {
    block_footer_area();
    wp_footer();
} else {
    get_footer();
}
?>
