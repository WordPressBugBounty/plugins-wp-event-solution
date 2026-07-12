<div class="eventin-block-container
<?php echo esc_attr($container_class); ?>">
    <div class="event-banner">
        <?php if ($event_banner): ?>
        <?php
        // LCP image: prefer wp_get_attachment_image so the browser gets srcset/width/height
        // and we can flag it high-priority + eager (it's above the fold).
        $banner_html = '';

        if ( ! empty( $event_banner_id ) ) {
            $banner_html = wp_get_attachment_image(
                $event_banner_id,
                'full',
                false,
                [
                    'class'         => 'event-banner-image',
                    'alt'           => esc_attr__( 'Event banner', 'eventin' ),
                    'fetchpriority' => 'high',
                    'loading'       => 'eager',
                    'decoding'      => 'async',
                    'sizes'         => '100vw',
                ]
            );
        }

        // Fall back to the stored URL when there is no attachment id, or when the
        // attachment yields no markup (e.g. imported banners whose attachment has
        // no valid image metadata — wp_get_attachment_image() then returns '').
        if ( '' === $banner_html ) {
            $banner_html = sprintf(
                '<img class="event-banner-image" src="%s" alt="%s" fetchpriority="high" decoding="async">',
                esc_url( $event_banner ),
                esc_attr__( 'Event banner', 'eventin' )
            );
        }

        echo $banner_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from wp_get_attachment_image() or an <img> with esc_url()/esc_attr__() attributes above.
        ?>
        <?php else: ?>
        <p><?php esc_html_e('No event banner image found.', 'eventin'); ?></p>
        <?php endif; ?>
    </div>
</div>