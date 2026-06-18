<?php
/**
 * Waiting-list confirmation email template.
 *
 * Included by email.php — do NOT add header/footer here.
 *
 * Variables in scope:
 *   $event          Etn\Core\Event\Event_Model
 *   $recipient_name string  Display name for the greeting (may be empty)
 */
defined( 'ABSPATH' ) || exit;

$greeting_name = ! empty( $recipient_name ) ? $recipient_name : __( 'there', 'eventin' );
?>

<h2 style="font-size:22px;font-weight:600;color:#020617;margin:0 0 12px;">
    <?php esc_html_e( 'You\'re on the waiting list!', 'eventin' ); ?>
</h2>

<p style="font-size:14px;line-height:22px;color:#556880;margin:0 0 24px;">
    <?php
    printf(
        /* translators: 1: recipient name  2: event title */
        esc_html__( 'Hi %1$s, you have been successfully added to the waiting list for %2$s. We\'ll email you as soon as a spot opens up so you can complete your registration.', 'eventin' ),
        esc_html( $greeting_name ),
        esc_html( $event->get_title() )
    );
    ?>
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
