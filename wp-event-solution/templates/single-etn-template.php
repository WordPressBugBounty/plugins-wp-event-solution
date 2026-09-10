<?php

defined( 'ABSPATH' ) || exit;
/**
 * Single Template for etn-template post type
 *
 * @package Eventin
 * @since 4.0.43
 */

use Etn\Utils\Helper;
use Eventin\Template\TemplateModel;

if ( wp_is_block_theme() ) {
    block_header_area();
    wp_head();
} else {
    get_header();
}

// Load ticket layout style
wp_enqueue_style( 'etn-ticket-markup' );
wp_enqueue_style( 'etn-blocks-style' );

// Full-width the single-template wrapper (shared by all builder branches below).
wp_add_inline_style( 'etn-blocks-style', '.ast-container .etn-template-single-wrapper { flex-basis: 100%; }' );

// Include QR Code related scripts when pro plugin is activated
if ( class_exists( 'Wpeventin_Pro' ) ) {
    wp_enqueue_script( 'etn-qr-code' );
    wp_enqueue_script( 'etn-qr-code-scanner' );
    wp_enqueue_script( 'etn-qr-code-custom' );
}

$template_id = get_the_ID();

if ( ! $template_id ) {
    printf( '<p>%s</p>', esc_html__( 'No template found.', 'eventin' ) );
    return;
}

$template = new TemplateModel( $template_id );
$post     = get_post( $template_id );

if ( ! $post || 'etn-template' !== $post->post_type ) {
    printf( '<p>%s</p>', esc_html__( 'Invalid template.', 'eventin' ) );
    return;
}

// Check if this template is built with Elementor
$is_elementor_template = false;
if ( did_action( 'elementor/loaded' ) ) {
    $document = \Elementor\Plugin::$instance->documents->get( $template_id );
    $is_elementor_template = $document && $document->is_built_with_elementor();
}

// Check if this template is built with Bricks. Eventin owns the single template
// for etn-template (via template_include), which bypasses Bricks' own frontend
// render — so a Bricks template would output nothing. Render its saved content here.
$is_bricks_template = ( 'bricks' === $template->get_template_builder() )
    && class_exists( '\Bricks\Frontend' )
    && defined( 'BRICKS_DB_PAGE_CONTENT' );

// For Elementor templates, render using Elementor's content method
// For Bricks templates, render the saved Bricks elements
// For other templates, get demo content
if ( $is_bricks_template ) {
    // Run the loop so get_the_ID() is the template; the Eventin Bricks elements
    // resolve the template's preview event themselves (see etn_resolve_event_id).
    while ( have_posts() ) {
        the_post();
        $bricks_data = get_post_meta( $template_id, BRICKS_DB_PAGE_CONTENT, true );
        $bricks_html = ( is_array( $bricks_data ) && ! empty( $bricks_data ) )
            ? \Bricks\Frontend::render_data( $bricks_data )
            : '';

        // Demo templates imported via "Use this template" store their markup as
        // blocks in post_content while still tagged as Bricks (no Bricks content
        // yet). Fall back to the demo/block content so they aren't blank.
        if ( '' === trim( (string) $bricks_html ) ) {
            $bricks_html = $template->get_demo_content();
        }
        ?>
        <div class="etn-template-single-wrapper">
            <div class="etn-template-content">
                <?php
                // Builder markup is generated server side from template data that only
                // users who can edit this etn-template post can author, so it is output as
                // rendered -- the same trust model as the_content(). wp_kses_post() is not
                // usable here: it drops the venue map <iframe>, every <svg> icon, the
                // accordion/countdown <script>, the ticket and RSVP <form>/<input>/<select>,
                // and the per-block <style> emitted by AbstractBlock::render_frontend_css().
                // Helper::kses() does not fit either -- its allowlist has no svg, script or
                // style attribute. Widening an allowlist does not rescue it: kses entity-
                // normalizes script and style bodies (`a < 2 && b` collapses to `a`) and
                // safecss_filter_attr() still strips the transition property.
                echo Helper::render( $bricks_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Helper::render() is a passthrough; builder-rendered markup, see note above.
                ?>
            </div>
        </div>
        <?php
    }
} elseif ( $is_elementor_template ) {
    // Set up the WordPress loop for Elementor
    while ( have_posts() ) {
        the_post();
        ?>
        <div class="etn-template-single-wrapper">
            <div class="etn-template-content">
                <?php the_content(); ?>
            </div>
        </div>
        <?php
    }
} else {
    $template_html = $template->get_demo_content();
    ?>
    <div class="etn-template-single-wrapper">
        <div class="etn-template-content">
            <?php echo Helper::render( $template_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Helper::render() is a passthrough; block/Elementor-rendered markup. ?>
        </div>
    </div>
    <?php
}
?>

<?php
if ( wp_is_block_theme() ) {
    block_footer_area();
    wp_footer();
} else {
    get_footer();
}
