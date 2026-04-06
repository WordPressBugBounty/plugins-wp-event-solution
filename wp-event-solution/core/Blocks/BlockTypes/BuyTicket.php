<?php

namespace Eventin\Blocks\BlockTypes;

defined( 'ABSPATH' ) || exit;
use Etn\Core\Event\Event_Model;
use Eventin\Blocks\BlockTypes\AbstractBlock;
use Wpeventin;

/**
 * Buy Event Ticket Gutenberg block
 */
class BuyTicket extends AbstractBlock
{
    /**
     * Block name.
     *
     * @var string
     */
    protected $block_name = 'buy-ticket';

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
        $style_variant = ! empty($attributes['styleVariant']) ? $attributes['styleVariant'] : 'style-1';
        
        $container_class = ! empty($attributes['containerClassName']) ? $attributes['containerClassName'] : '';
        $styles = ! empty( $attributes['styles'] ) ? $attributes['styles'] : [];
        
        if ( 'etn-template' == get_post_type( get_the_ID() ) ) {
            $template = new \Eventin\Template\TemplateModel( get_the_ID() );
            $event_id = $template->get_preview_event_id();
        } else {
            $event_id = get_the_ID();
        }

        $event = new Event_Model($event_id);

        ob_start();


        ?>
        <?php echo $this->render_frontend_css( $styles, esc_attr( $container_class ) ); ?>
        <?php
        require_once Wpeventin::templates_dir() . 'event/parts/buy-ticket.php';
        ?>

        <?php

        return ob_get_clean();
    }
}