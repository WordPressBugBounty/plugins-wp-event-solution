<?php defined( 'ABSPATH' ) || exit; ?>
<!-- Custom email body: the admin's own text only, no built-in details block. -->
<div
    style="
    font-size: 14px;
    line-height: 22px;
    color: #556880;
    margin-bottom: 24px;
    ">
    <?php echo wp_kses_post( $content ); ?>
</div>
