<?php
defined( 'ABSPATH' ) || exit;

/**
 * @var \Eventin\Order\OrderModel $order
 * @var array                     $refund
 */

$is_ticket = isset( $refund['type'] ) && 'ticket' === $refund['type'];
$customer  = trim( (string) ( $order->customer_fname . ' ' . $order->customer_lname ) );
if ( '' === $customer ) {
    $customer = (string) $order->customer_email;
}
?>
<p><?php echo esc_html__( 'Hi', 'eventin' ); ?> <?php echo esc_html( $customer ); ?>,</p>

<p><?php esc_html_e( 'A refund has been processed for your order. Details below:', 'eventin' ); ?></p>

<table style="width:100%;border-collapse:collapse;margin:16px 0;">
    <tr>
        <td style="padding:8px;border:1px solid #eee;"><strong><?php esc_html_e( 'Order', 'eventin' ); ?></strong></td>
        <td style="padding:8px;border:1px solid #eee;">#<?php echo esc_html( (string) $order->id ); ?></td>
    </tr>
    <tr>
        <td style="padding:8px;border:1px solid #eee;"><strong><?php esc_html_e( 'Refund Amount', 'eventin' ); ?></strong></td>
        <td style="padding:8px;border:1px solid #eee;">
            <?php echo esc_html( $refund['currency'] . ' ' . number_format( (float) $refund['amount'], 2 ) ); ?>
        </td>
    </tr>
    <?php if ( $is_ticket && ! empty( $refund['attendee_ids'] ) ) : ?>
        <tr>
            <td style="padding:8px;border:1px solid #eee;"><strong><?php esc_html_e( 'Refunded Tickets', 'eventin' ); ?></strong></td>
            <td style="padding:8px;border:1px solid #eee;">
                <?php
                $lines = [];
                foreach ( (array) $refund['attendee_ids'] as $aid ) {
                    $name        = get_post_meta( $aid, 'etn_name', true );
                    $ticket_name = get_post_meta( $aid, 'ticket_name', true );
                    $price       = get_post_meta( $aid, 'etn_ticket_price', true );
                    $lines[]     = esc_html( $name . ' — ' . $ticket_name . ' — ' . $price );
                }
                echo implode( '<br>', $lines ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- each line escaped above
                ?>
            </td>
        </tr>
    <?php endif; ?>
</table>

<?php if ( ! empty( $refund['reason'] ) ) : ?>
    <p><strong><?php esc_html_e( 'Reason:', 'eventin' ); ?></strong> <?php echo esc_html( $refund['reason'] ); ?></p>
<?php endif; ?>

<p><?php esc_html_e( 'If you have any questions, just reply to this email.', 'eventin' ); ?></p>
