<?php

defined('ABSPATH') || die();

$etn_show_placeholder = (bool) apply_filters(
    'eventin_archive_thumbnail_show_placeholder',
    true,
    get_the_ID()
);

if ( ! has_post_thumbnail() && ! $etn_show_placeholder ) {
    return;
}

$etn_placeholder_url = apply_filters(
    'eventin_archive_thumbnail_placeholder',
    \Wpeventin::assets_url() . 'images/placeholder.jpg',
    get_the_ID()
);
?>
<!-- thumbnail -->
<div class="etn-event-thumb">

    <?php do_action( 'etn_before_event_archive_thumbnail' ); ?>

    <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail(); ?>
        <?php else : ?>
            <img
                src="<?php echo esc_url( $etn_placeholder_url ); ?>"
                alt="<?php echo esc_attr( get_the_title() ); ?>"
                class="etn-event-thumb-placeholder"
                loading="lazy"
            />
        <?php endif; ?>
    </a>

    <?php do_action( 'etn_after_event_archive_thumbnail' ); ?>

</div>
