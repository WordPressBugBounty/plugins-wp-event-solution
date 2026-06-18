<div class="eventin-block-container">
    <div class="event-banner">
        <?php if ($event_banner): ?>
        <?php
        // LCP image: emit a responsive, high-priority <img> when the attachment id is known.
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