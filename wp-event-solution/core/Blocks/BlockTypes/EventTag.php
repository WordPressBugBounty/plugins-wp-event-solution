<?php

namespace Eventin\Blocks\BlockTypes;

defined( 'ABSPATH' ) || exit;
use Etn\Core\Event\Event_Model;
use Etn\Utils\Helper;
use Eventin\Blocks\BlockTypes\AbstractBlock;
use Wpeventin;

/**
 * Event Tag Gutenberg block
 */
class EventTag extends AbstractBlock {
    /**
     * Block name.
     *
     * @var string
     */
    protected $block_name = 'event-tag';

    /**
     * Include and render the block
     *
     * @param   array  $attributes  Block attributes. Default empty array
     * @param   string  $content     Block content. Default empty string
     * @param   WP_Block  $block       Block instance
     *
     * @return  string Rendered block type output
     */
    protected function render( $attributes, $content, $block ) {
        $container_class = ! empty( $attributes['containerClassName'] ) ? $attributes['containerClassName'] : '';
        $styles = ! empty( $attributes['styles'] ) ? $attributes['styles'] : [];

        if ( $this->is_editor() ) {
            $event_id = ! empty( $attributes['eventId'] ) ? intval( $attributes['eventId'] ) : 0;
        } else if ( 'etn-template' == get_post_type( get_the_ID() ) ) {
            // do_blocks() runs against the template post, so resolve the event
            // being rendered through it — same as the event-category block.
            $template = new \Eventin\Template\TemplateModel( get_the_ID() );
            $event_id = $template->get_preview_event_id();
        } else {
            $event_id = get_the_ID();
        }

        // "Vendor Event Category & Tags" setting: hide tags on vendor created events.
        if ( ! $this->is_editor() && Helper::should_hide_event_taxonomy( $event_id ) ) {
            return '';
        }

        $event = new Event_Model( $event_id );

        $event_tags = $event->get_tags();

        ob_start();
        ?>
        <?php echo $this->render_frontend_css( $styles, esc_attr( $container_class ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSS rendered by render_frontend_css(); script and style tags stripped by generate_frontend_css(). ?>
        <?php
        require_once Wpeventin::templates_dir() . 'event/parts/event-tag.php';
        ?>

        <?php
        return ob_get_clean();
    }
}

