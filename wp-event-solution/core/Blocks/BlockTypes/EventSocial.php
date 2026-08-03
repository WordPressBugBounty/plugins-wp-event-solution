<?php

namespace Eventin\Blocks\BlockTypes;

defined( 'ABSPATH' ) || exit;
    use Etn\Core\Event\Event_Model;
    use Eventin\Blocks\BlockTypes\AbstractBlock;
    use Wpeventin;

    /**
     * Event Social Gutenberg block
     */
    class EventSocial extends AbstractBlock
    {
        /**
         * Block name.
         *
         * @var string
         */
        protected $block_name = 'event-social';

        /**
         * Include and render the block
         *
         * @param   array  $attributes  Block attributes. Default empty array
         * @param   string  $content     Block content. Default empty string
         * @param   WP_Block  $block       Block instance
         *
         * @return  string Rendered block type output
         */
        protected function render($attributes, $content, $block)
        {
            $container_class = ! empty($attributes['containerClassName']) ? $attributes['containerClassName'] : '';
            $styles          = ! empty($attributes['styles']) ? $attributes['styles'] : [];
            $style_variant   = ! empty($attributes['styleVariant']) ? sanitize_key($attributes['styleVariant']) : 'style-1';

            $allowed_variants = ['style-1'];
            if (! in_array($style_variant, $allowed_variants, true)) {
                $style_variant = 'style-1';
            }

            if ($this->is_editor()) {
                $event_id = ! empty($attributes['eventId']) ? intval($attributes['eventId']) : 0;

                if ($event_id == 0) {
                    $template = new \Eventin\Template\TemplateModel(get_the_ID());
                    $event_id = $template->get_preview_event_id();
                }
            } elseif ('etn-template' == get_post_type(get_the_ID())) {
                $template = new \Eventin\Template\TemplateModel(get_the_ID());
                $event_id = $template->get_preview_event_id();
            } else {
                $event_id = get_the_ID();
            }

            $event = new Event_Model($event_id);

            $event_socials = etn_get_valid_event_socials( $event->get_social() );

            // Nothing to share — render nothing on the frontend, and a hint in the
            // editor so the block stays selectable. Matches EventDescription's guard.
            if ( empty( $event_socials ) ) {
                if ( $this->is_editor() ) {
                    return '<div style="padding:16px;border:1px dashed #ccc;color:#888;">'
                        . esc_html__( 'Event Social: no social links set for this event.', 'eventin' )
                        . '</div>';
                }

                return '';
            }

            ob_start();
        ?>
        <?php echo $this->render_frontend_css( $styles, esc_attr( $container_class ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS rendered by render_frontend_css(); script and style tags stripped by generate_frontend_css(). ?>
        <?php
            $style_template = Wpeventin::templates_dir() . 'event/parts/styles/event-social/' . $style_variant . '.php';
                    require $style_template;
                ?>

        <?php
            return ob_get_clean();
                }
            }
