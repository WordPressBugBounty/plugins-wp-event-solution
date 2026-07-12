<?php
/**
 * Read-only REST endpoint exposing per-ticket-type add-on field definitions.
 *
 * @package Eventin\Integrations\Optiontics
 */

namespace Eventin\Integrations\Optiontics;

use Etn\Core\Event\Event_Model;
use WP_REST_Controller;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

/**
 * GET eventin/v2/ticket-addons?event_id=
 *
 * Returns, per ticket-type slug, the add-on fields for the Optiontics blocks
 * assigned to that ticket type. Prices in the response are display-only; the
 * authoritative price is recomputed server-side at order creation.
 */
class TicketAddonsController extends WP_REST_Controller {

    /**
     * REST namespace.
     *
     * @var string
     */
    protected $namespace = 'eventin/v2';

    /**
     * REST base.
     *
     * @var string
     */
    protected $rest_base = 'ticket-addons';

    /**
     * Register routes.
     *
     * @return void
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            $this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items' ],
                    // Public read: ticket data on the purchase page is already public.
                    'permission_callback' => '__return_true',
                    'args'                => [
                        'event_id' => [
                            'required'          => true,
                            'sanitize_callback' => 'absint',
                        ],
                    ],
                ],
            ]
        );

        register_rest_route(
            $this->namespace,
            $this->rest_base . '/optiontics-blocks',
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_optiontics_blocks' ],
                    'permission_callback' => [ $this, 'get_optiontics_blocks_permissions_check' ],
                    'args'                => [
                        'event_id' => [
                            'required'          => false,
                            'default'           => 0,
                            'sanitize_callback' => 'absint',
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Permission check for the optiontics-blocks endpoint.
     *
     * @return bool
     */
    public function get_optiontics_blocks_permissions_check(): bool {
        return current_user_can( 'etn_manage_event' );
    }

    /**
     * Return the Optiontics blocks assigned to the requested event, for the
     * Advanced-tab per-ticket selector. Only blocks whose Optiontics targeting
     * resolves to this event are offered; the admin then picks, per ticket, which
     * of them appear at checkout.
     *
     * @param \WP_REST_Request $request Request.
     * @return \WP_REST_Response
     */
    public function get_optiontics_blocks( $request ) {
        $adapter = new OptionticsAddons();

        // Global Extensions-page toggle. Off → no blocks offered in the ticket modal.
        if ( ! $adapter->integration_enabled() ) {
            return rest_ensure_response( [] );
        }

        $event_id = absint( $request->get_param( 'event_id' ) );

        return rest_ensure_response( $adapter->blocks_for_event( $event_id ) );
    }

    /**
     * Build the per-ticket-type add-on map.
     *
     * @param \WP_REST_Request $request Request.
     * @return \WP_REST_Response { [ticket_slug]: [ { node_id, title, field_type, is_required, choices, block_id, base_price } ] }
     */
    public function get_items( $request ) {
        $event_id = absint( $request->get_param( 'event_id' ) );
        $adapter  = new OptionticsAddons();
        $out      = [];

        if ( ! $adapter->is_available() || ! $adapter->integration_enabled() || ! $event_id ) {
            return rest_ensure_response( $out );
        }

        // Master switch (Advanced tab toggle). Off/unset → no option fields anywhere
        // on the purchase page for this event, even if tickets have blocks assigned.
        if ( ! $adapter->addons_enabled( $event_id ) ) {
            return rest_ensure_response( $out );
        }

        $event      = new Event_Model( $event_id );
        $variations = $event->get_ticket();

        if ( ! is_array( $variations ) ) {
            return rest_ensure_response( $out );
        }

        // Only blocks the admin explicitly selected for a ticket appear at checkout.
        // We intersect with the blocks still assigned to this event on the Optiontics
        // side, so a block that has since been de-targeted can't linger from a stale
        // per-ticket selection.
        $event_block_ids = $adapter->event_block_ids( $event_id );

        foreach ( $variations as $variation ) {
            $slug      = $variation['etn_ticket_slug'] ?? '';
            $selected  = array_map( 'absint', (array) ( $variation['optiontics_block_ids'] ?? [] ) );
            $block_ids = array_values( array_intersect( $selected, $event_block_ids ) );

            if ( '' === $slug || ! $block_ids ) {
                continue;
            }

            // Ticket base price — needed client-side to display percentage-priced
            // choices as the amount that will actually be charged. Must match the
            // base used in OrderController::reprice ( $variation['etn_ticket_price'] ).
            $base_price = (float) ( $variation['etn_ticket_price'] ?? 0 );

            $fields = [];

            foreach ( $block_ids as $block_id ) {
                foreach ( $adapter->get_block_fields( $block_id ) as $field ) {
                    $field['block_id']   = $block_id;
                    $field['base_price'] = $base_price;
                    $fields[]            = $field;
                }
            }

            if ( $fields ) {
                $out[ $slug ] = $fields;
            }
        }

        return rest_ensure_response( $out );
    }
}
