<?php
/**
 * Eventin order-preview page template.
 *
 * Reached via a waiting-list payment link:
 *   /eventin-purchase/preview/?order_id=<int>&order_token=<hex>
 *
 * Validates the signed token, then shows the order summary and a
 * "Complete Payment" button that hands the customer off to checkout.
 */
defined( 'ABSPATH' ) || exit;

// ----------------------------------------------------------------
// Validate query params.
// ----------------------------------------------------------------
$order_id    = isset( $_GET['order_id'] )    ? intval( $_GET['order_id'] )                                  : 0;  // phpcs:ignore WordPress.Security.NonceVerification
$order_token = isset( $_GET['order_token'] ) ? sanitize_text_field( wp_unslash( $_GET['order_token'] ) )    : ''; // phpcs:ignore WordPress.Security.NonceVerification

$valid        = false;
$order        = null;
$event        = null;
$error_msg    = '';

if ( $order_id && $order_token ) {
    $stored_token = get_post_meta( $order_id, '_order_access_token', true );

    if ( $stored_token && hash_equals( $stored_token, $order_token ) ) {
        $post = get_post( $order_id );
        if ( $post && 'etn-order' === $post->post_type ) {
            $order = new \Eventin\Order\OrderModel( $order_id );
            if ( 'waiting' === $order->status ) {
                $event = new \Etn\Core\Event\Event_Model( $order->event_id );
                $valid = true;
            } else {
                $error_msg = __( 'This payment link is no longer valid.', 'eventin' );
            }
        } else {
            $error_msg = __( 'Order not found.', 'eventin' );
        }
    } else {
        $error_msg = __( 'Invalid or expired payment link.', 'eventin' );
    }
} else {
    $error_msg = __( 'Missing order information.', 'eventin' );
}

// ----------------------------------------------------------------
// Build checkout URL (used by the CTA button).
// ----------------------------------------------------------------
$checkout_url = $valid ? add_query_arg(
    [
        'order_id'    => $order_id,
        'order_token' => $order_token,
    ],
    site_url( 'eventin-purchase/checkout/' )
) : '';

// ----------------------------------------------------------------
// Page shell.
// ----------------------------------------------------------------
if ( wp_is_block_theme() ) {
    block_header_area();
    wp_head();
} else {
    get_header();
}
?>

<div class="eventin-order-preview" style="max-width:680px;margin:48px auto;padding:0 16px;font-family:sans-serif;">

<?php if ( ! $valid ) : ?>

    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:24px;text-align:center;">
        <p style="margin:0;font-size:16px;color:#991b1b;">
            <?php echo esc_html( $error_msg ); ?>
        </p>
    </div>

<?php else :
    $date_format = etn_date_format();
    $time_format = etn_time_format();

    if ( $event->etn_start_date === $event->etn_end_date ) {
        $date_line = sprintf(
            '%s, %s — %s',
            $event->get_start_datetime( $date_format ),
            $event->get_start_datetime( $time_format ),
            $event->get_end_datetime( $time_format )
        );
    } else {
        $date_line = sprintf(
            '%s %s — %s %s',
            $event->get_start_datetime( $date_format ),
            $event->get_start_datetime( $time_format ),
            $event->get_end_datetime( $date_format ),
            $event->get_end_datetime( $time_format )
        );
    }
?>

    <!-- Header -->
    <div style="text-align:center;margin-bottom:32px;">
        <h1 style="font-size:26px;font-weight:700;color:#020617;margin:0 0 8px;">
            <?php esc_html_e( 'Complete Your Payment', 'eventin' ); ?>
        </h1>
        <p style="font-size:14px;color:#64748b;margin:0;">
            <?php esc_html_e( 'A spot is waiting for you. Review your order and proceed to pay.', 'eventin' ); ?>
        </p>
    </div>

    <!-- Order card -->
    <div style="border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;margin-bottom:24px;">

        <!-- Event info -->
        <div style="background:#f8fafc;padding:20px 24px;border-bottom:1px solid #e2e8f0;">
            <p style="margin:0 0 4px;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;">
                <?php esc_html_e( 'Event', 'eventin' ); ?>
            </p>
            <h2 style="margin:0 0 6px;font-size:18px;font-weight:600;color:#0f172a;">
                <?php echo esc_html( $event->get_title() ); ?>
            </h2>
            <p style="margin:0;font-size:13px;color:#64748b;">
                <?php echo esc_html( $date_line ); ?>
                <?php if ( $event->get_timezone() ) : ?>
                    &nbsp;<?php echo esc_html( $event->get_timezone() ); ?>
                <?php endif; ?>
            </p>
            <?php if ( $event->event_type !== 'online' && $event->get_address() ) : ?>
            <p style="margin:4px 0 0;font-size:13px;color:#64748b;">
                <?php echo esc_html( $event->get_address() ); ?>
            </p>
            <?php endif; ?>
        </div>

        <!-- Order meta -->
        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:13px;color:#64748b;">
                <?php
                /* translators: %d: order ID */
                printf( esc_html__( 'Order #%d', 'eventin' ), esc_html( $order->id ) );
                ?>
            </span>
            <span style="font-size:13px;color:#64748b;">
                <?php echo esc_html( $order->get_datetime( etn_date_format() . ' ' . etn_time_format() ) ); ?>
            </span>
        </div>

        <!-- Tickets -->
        <?php if ( $order->get_tickets() ) : ?>
        <div style="padding:0 24px;">
            <table style="width:100%;border-collapse:collapse;">
                <tbody>
                    <?php foreach ( $order->get_tickets() as $ticket ) : ?>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:14px 0;font-size:14px;color:#334155;">
                            <?php echo esc_html( $ticket['etn_ticket_name'] ); ?>
                            <span style="color:#94a3b8;">&nbsp;&times;&nbsp;<?php echo esc_html( $ticket['etn_ticket_qty'] ); ?></span>
                        </td>
                        <td style="padding:14px 0;text-align:right;font-size:14px;color:#334155;">
                            <?php
                            echo esc_html( \Etn\Core\Event\Helper::instance()->currency_with_position(
                                number_format( floatval( $ticket['etn_ticket_price'] ) * intval( $ticket['etn_ticket_qty'] ), 2 ),
                                $order
                            ) );
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <!-- Total -->
        <div style="padding:16px 24px;background:#f8fafc;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:15px;font-weight:700;color:#0f172a;">
                <?php esc_html_e( 'Total', 'eventin' ); ?>
            </span>
            <span style="font-size:15px;font-weight:700;color:#0f172a;">
                <?php
                echo esc_html( \Etn\Core\Event\Helper::instance()->currency_with_position(
                    number_format( floatval( $order->total_price ), 2 ),
                    $order
                ) );
                ?>
            </span>
        </div>

    </div><!-- /.order card -->

    <!-- CTA -->
    <div style="text-align:center;margin-top:32px;">
        <a
            href="<?php echo esc_url( $checkout_url ); ?>"
            style="
                display:inline-block;
                background:#6c2bd9;
                color:#fff;
                font-size:16px;
                font-weight:600;
                text-decoration:none;
                padding:16px 40px;
                border-radius:8px;
            "
        >
            <?php esc_html_e( 'Complete Payment', 'eventin' ); ?>
        </a>
    </div>

<?php endif; ?>

</div>

<?php
if ( wp_is_block_theme() ) {
    block_footer_area();
    wp_footer();
} else {
    get_footer();
}
?>
