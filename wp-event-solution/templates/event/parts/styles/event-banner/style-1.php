<div class="eventin-block-container
<?php echo esc_attr($container_class); ?>">
    <div class="event-banner">
        <?php if ($event_banner): ?>
        <?php
        // LCP image: prefer wp_get_attachment_image so the browser gets srcset/width/height
        // and we can flag it high-priority + eager (it's above the fold). Fall back to the
        // stored URL when only a URL (no attachment id) is available.
        if ( ! empty( $event_banner_id ) ) {
            echo wp_get_attachment_image(
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
        } else {
            ?>
            <img class="event-banner-image" src="<?php echo esc_url($event_banner) ?>" alt="<?php esc_attr_e('Event banner', 'eventin'); ?>" fetchpriority="high" decoding="async">
            <?php
        }
        ?>
        <?php else: ?>
        <p><?php esc_html_e('No event banner image found.', 'eventin'); ?></p>
        <?php endif; ?>
    </div>
</div>