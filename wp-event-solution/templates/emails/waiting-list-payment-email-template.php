<?php
/**
 * Waiting-list payment email template.
 *
 * Included by email.php — do NOT add header/footer here.
 *
 * Variables in scope:
 *   $order       Eventin\Order\OrderModel
 *   $event       Etn\Core\Event\Event_Model
 *   $payment_url string  Signed preview page URL
 */
defined( 'ABSPATH' ) || exit;
?>

<h2 style="font-size:22px;font-weight:600;color:#020617;margin:0 0 12px;">
    <?php esc_html_e( 'A spot has opened up for you!', 'eventin' ); ?>
</h2>

<p style="font-size:14px;line-height:22px;color:#556880;margin:0 0 24px;">
    <?php
    printf(
        /* translators: 1: customer first name  2: event title */
        esc_html__( 'Hi %1$s, great news — a ticket for %2$s is now available. Click the button below to review your order and complete payment before the spot is taken.', 'eventin' ),
        esc_html( $order->customer_fname ),
        esc_html( $event->get_title() )
    );
    ?>
</p>

<!-- Order summary -->
<div style="background:#f1f5f9;padding:10px 20px;border-radius:5px;margin-bottom:16px;">
    <span style="font-weight:600;">
        <?php
        /* translators: %d: order ID */
        printf( esc_html__( 'Order #%d', 'eventin' ), esc_html( $order->id ) );
        ?>
    </span>
</div>

<?php if ( $order->get_tickets() ) : ?>
<table style="width:100%;border-collapse:collapse;margin-bottom:16px;">
    <tbody>
        <?php foreach ( $order->get_tickets() as $ticket ) : ?>
        <tr>
            <td style="padding:8px 0;font-size:14px;color:#334155;">
                <?php printf( '%s &times; %s', esc_html( $ticket['etn_ticket_name'] ), esc_html( $ticket['etn_ticket_qty'] ) ); ?>
            </td>
            <td style="padding:8px 0;text-align:right;font-size:14px;color:#334155;">
                <?php
                echo esc_html( \Etn\Core\Event\Helper::instance()->currency_with_position(
                    number_format( floatval( $ticket['etn_ticket_price'] ) * intval( $ticket['etn_ticket_qty'] ), 2 ),
                    $order
                ) );
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td style="padding:8px 0;font-size:14px;font-weight:700;color:#334155;border-top:1px solid #e2e8f0;">
                <?php esc_html_e( 'Total:', 'eventin' ); ?>
            </td>
            <td style="padding:8px 0;text-align:right;font-size:14px;font-weight:700;color:#334155;border-top:1px solid #e2e8f0;">
                <?php
                echo esc_html( \Etn\Core\Event\Helper::instance()->currency_with_position(
                    number_format( floatval( $order->total_price ), 2 ),
                    $order
                ) );
                ?>
            </td>
        </tr>
    </tbody>
</table>
<?php endif; ?>

<!-- CTA -->
<div style="text-align:center;margin:32px 0;">
    <a
        href="<?php echo esc_url( $payment_url ); ?>"
        style="
            display:inline-block;
            background-color:#6c2bd9;
            color:#ffffff;
            font-size:15px;
            font-weight:600;
            text-decoration:none;
            padding:14px 32px;
            border-radius:6px;
        "
    >
        <?php esc_html_e( 'View Order & Pay', 'eventin' ); ?>
    </a>
</div>

<p style="font-size:13px;color:#94a3b8;margin-top:16px;">
    <?php esc_html_e( 'Or copy this link into your browser:', 'eventin' ); ?>
    <br>
    <a href="<?php echo esc_url( $payment_url ); ?>" style="color:#6c2bd9;word-break:break-all;">
        <?php echo esc_url( $payment_url ); ?>
    </a>
</p>

<!-- Event info -->
<hr style="border:none;border-top:1px solid #e2e8f0;margin:28px 0;">

<h3 style="font-size:15px;font-weight:600;color:#020617;margin:0 0 8px;">
    <?php echo esc_html( $event->get_title() ); ?>
</h3>

<?php
$date_format = etn_date_format();
$time_format = etn_time_format();

$date_line = ( $event->etn_start_date === $event->etn_end_date )
    ? sprintf( '%s, %s — %s', $event->get_start_datetime( $date_format ), $event->get_start_datetime( $time_format ), $event->get_end_datetime( $time_format ) )
    : sprintf( '%s %s — %s %s', $event->get_start_datetime( $date_format ), $event->get_start_datetime( $time_format ), $event->get_end_datetime( $date_format ), $event->get_end_datetime( $time_format ) );
?>
<p style="font-size:14px;color:#556880;margin:4px 0;">
    <?php echo esc_html( $date_line ); ?>
    <?php if ( $event->get_timezone() ) : ?>
        <?php echo esc_html( $event->get_timezone() ); ?>
    <?php endif; ?>
</p>
<?php if ( $event->event_type !== 'online' && $event->get_address() ) : ?>
<p style="font-size:14px;color:#556880;margin:4px 0;">
    <?php echo esc_html( $event->get_address() ); ?>
</p>
<?php endif; ?>
