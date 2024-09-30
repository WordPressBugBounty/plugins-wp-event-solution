<?php

use Etn\Core\Event\Event_Model;
use Etn\Utils\Helper;

?>
<?php do_action( 'etn_before_add_to_cart_widget_block', $single_event_id ); ?>

<div class="etn-purchase-ticket-root" data-post_id="<?php echo esc_attr($single_event_id); ?>"></div>

<?php do_action( 'etn_after_add_to_cart_widget_block', $single_event_id ); ?>