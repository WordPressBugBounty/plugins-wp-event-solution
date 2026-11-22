<?php
/**
 * Document opening for block themes, mirroring core's template-canvas.php.
 * Header renders first so its block styles enqueue before wp_head(); meta tags then land in <head>.
 */

defined( 'ABSPATH' ) || exit;

ob_start();
block_header_area();
$etn_block_header = ob_get_clean();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="wp-site-blocks">
<?php echo $etn_block_header; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rendered block template part markup. ?>
