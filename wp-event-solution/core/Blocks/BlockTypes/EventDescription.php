<?php

namespace Eventin\Blocks\BlockTypes;

defined( 'ABSPATH' ) || exit;
    use Etn\Core\Event\Event_Model;
    use Eventin\Blocks\BlockTypes\AbstractBlock;
    use Wpeventin;

    /**
     * Event Description Gutenberg block
     */
    class EventDescription extends AbstractBlock
    {
        /**
         * Namespace for the block
         *
         * @var string
         */
        protected $namespace = 'eventin-pro';

        /**
         * Block name.
         *
         * @var string
         */
        protected $block_name = 'event-description';

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

            if ($this->is_editor()) {
                $event_id = ! empty($attributes['eventId']) ? intval($attributes['eventId']) : 0;

                if ($event_id == 0 || get_post_type($event_id) === 'etn-template') {
                    $template = new \Eventin\Template\TemplateModel(get_the_ID());
                    $event_id = $template->get_preview_event_id();
                }
            } else if ('etn-template' == get_post_type(get_the_ID())) {
                // Non-editor rendering inside a ticket/certificate template — output a placeholder
                // so TemplateModel::get_rendable_content() can replace it with real attendee event data.
                return '{{event_description}}';
            } else {
                $event_id = get_the_ID();
            }

            // Guard: no valid event resolved — show placeholder in editor, nothing on frontend.
            if ( empty( $event_id ) || 'etn' !== get_post_type( $event_id ) ) {
                if ( $this->is_editor() ) {
                    return '<div style="padding:16px;border:1px dashed #ccc;color:#888;">'
                        . esc_html__( 'Event Description: no preview event found.', 'eventin' )
                        . '</div>';
                }
                return '';
            }

            $event = new Event_Model($event_id);

            ob_start();
        ?>
        <?php echo $this->render_frontend_css( $styles, esc_attr( $container_class ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS rendered by render_frontend_css(); script and style tags stripped by generate_frontend_css(). ?>
        <?php
            require Wpeventin::templates_dir() . 'event/parts/event-description.php';
                ?>

        <?php
            return ob_get_clean();
                }
            }
