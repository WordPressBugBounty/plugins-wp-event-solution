<?php

use Etn\Core\Event\Event_Model;
use Etn\Utils\Helper;

$tickets_variations = get_post_meta($single_event_id,'etn_ticket_variations', true);
$style = isset($style_variant) ? $style_variant : 'style-1';

if(!empty($tickets_variations)){

    // PERF: server-rendered placeholder shown until the React purchase bundle is
    // lazy-loaded on interaction (see Helper::enable_lazy_ticket_loader). It gives
    // the page a meaningful, immediately-visible CTA + price and reserves vertical
    // space to limit layout shift. React replaces this markup on mount.
    $currency = '';
    if ( class_exists( 'woocommerce' ) ) {
        $currency = get_woocommerce_currency_symbol();
    } elseif ( function_exists( 'etn_currency_symbol' ) ) {
        $currency = etn_currency_symbol();
    }

    $prices = array();
    foreach ( (array) $tickets_variations as $variation ) {
        if ( isset( $variation['etn_ticket_price'] ) && is_numeric( $variation['etn_ticket_price'] ) ) {
            $prices[] = (float) $variation['etn_ticket_price'];
        }
    }
    $min_price = ! empty( $prices ) ? min( $prices ) : null;

    if ( null === $min_price ) {
        $price_label = '';
    } elseif ( 0.0 === $min_price ) {
        $price_label = esc_html__( 'Free', 'eventin' );
    } else {
        /* translators: %s: lowest ticket price with currency symbol. */
        $price_label = sprintf( esc_html__( 'From %s', 'eventin' ), $currency . rtrim( rtrim( number_format( $min_price, 2, '.', ',' ), '0' ), '.' ) );
    }
?>
<?php do_action( 'etn_before_add_to_cart_widget_block', $single_event_id ); ?>

<div class="etn-purchase-ticket-root" data-post_id="<?php echo esc_attr($single_event_id); ?>" data-style="<?php echo esc_attr($style); ?>" >
    <div class="etn-ticket-placeholder etn-lazy-trigger" style="min-height:170px;" aria-busy="true" aria-label="<?php echo esc_attr__( 'Loading tickets', 'eventin' ); ?>">
        <h3 class="etn-purchase-ticket-title"><?php echo esc_html__( 'Tickets', 'eventin' ); ?></h3>
        <?php if ( '' !== $price_label ) : ?>
            <div class="etn-ticket-price-hint"><?php echo esc_html( $price_label ); ?></div>
        <?php endif; ?>
        <div class="etn-skel etn-skel-row"></div>
        <div class="etn-skel etn-skel-row" style="width:75%;"></div>
        <div class="etn-skel etn-skel-btn"></div>
    </div>
</div>

<?php
}
do_action( 'etn_after_add_to_cart_widget_block', $single_event_id ); ?>
