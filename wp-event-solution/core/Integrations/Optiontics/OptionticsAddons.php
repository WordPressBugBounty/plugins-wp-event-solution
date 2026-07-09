<?php
/**
 * Single trust boundary between Eventin and the Optiontics option engine.
 *
 * Every reference to an `Optiontics\*` symbol lives in this class. If Optiontics
 * refactors, only this file changes; the rest of Eventin talks to plain arrays.
 *
 * @package Eventin\Integrations\Optiontics
 */

namespace Eventin\Integrations\Optiontics;

defined( 'ABSPATH' ) || exit;

/**
 * Adapter for reading Optiontics Option Blocks and re-pricing selections.
 */
class OptionticsAddons {

    /**
     * Optional test seam: fn(int $block_id): array<field>.
     *
     * @var callable|null
     */
    private $fields_provider = null;

    /**
     * True only when Optiontics is active AND speaks the contract we built against.
     *
     * @return bool
     */
    public function is_available(): bool {
        return defined( 'OPTIONTICS_INTEGRATION_API_VERSION' )
            && 2 === (int) constant( 'OPTIONTICS_INTEGRATION_API_VERSION' )
            && class_exists( \Optiontics\Models\Block_Model::class )
            && class_exists( \Optiontics\Core\FieldUnits\Pricing\Pricing_Engine::class )
            && class_exists( \Optiontics\Core\FieldUnits\DTO\Field_Definition::class );
    }

    /**
     * Whether the site owner has enabled the Optiontics integration on the
     * Extensions page. This is the global kill-switch, independent of the
     * per-event master switch ({@see addons_enabled()}) and plugin availability.
     *
     * Mirrors the admin extensions store rule (`status !== 'off'`): an explicit
     * choice wins; when the user has never toggled, default to enabled if the
     * plugin is active — consistent with how other integrations auto-enable on
     * active dependencies.
     *
     * @return bool
     */
    public function integration_enabled(): bool {
        $settings = get_option( 'etn_addons_options', [] );

        if ( isset( $settings['optiontics'] ) ) {
            return 'on' === $settings['optiontics'];
        }

        // Never toggled: follow the auto-enable default — on when the plugin is active.
        return $this->is_available();
    }

    /**
     * All Optiontics blocks whose product_type targets Eventin (regardless of
     * specific event assignment). Used in the admin ticket modal so only
     * Eventin-relevant blocks appear in the add-ons dropdown.
     *
     * @return array<int, array{id: int, title: string}>
     */
    public function get_eventin_blocks(): array {
        if ( ! $this->is_available() ) {
            return [];
        }

        $blocks = get_posts(
            [
                'post_type'      => 'optiontics-block',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'no_found_rows'  => true,
            ]
        );

        $eventin_blocks = [];

        foreach ( $blocks as $block_id ) {
            try {
                $block = new \Optiontics\Models\Block_Model( (int) $block_id );
            } catch ( \Throwable $e ) {
                continue;
            }

            $type = (string) $block->product_type;

            if ( in_array( $type, [ 'all_eventin_events', 'specific_eventin_events' ], true ) ) {
                $eventin_blocks[] = [
                    'id'    => (int) $block_id,
                    'title' => get_the_title( $block_id ),
                ];
            }
        }

        return $eventin_blocks;
    }

    /**
     * Eventin blocks actually assigned to a given event, with titles, for the
     * Advanced-tab per-ticket selector.
     *
     * Unlike {@see get_eventin_blocks()} this honours each block's include/exclude
     * targeting: only blocks that resolve to this event are returned. When
     * $event_id is 0 (a not-yet-saved event) only blanket `all_eventin_events`
     * blocks can apply, so those are returned.
     *
     * @param int $event_id Eventin event post ID (0 for an unsaved event).
     * @return array<int, array{id: int, title: string}>
     */
    public function blocks_for_event( int $event_id = 0 ): array {
        if ( ! $this->is_available() ) {
            return [];
        }

        $ids = $event_id > 0 ? $this->event_block_ids( $event_id ) : $this->all_event_block_ids();

        $out = [];

        foreach ( $ids as $id ) {
            $out[] = [
                'id'    => (int) $id,
                'title' => get_the_title( (int) $id ),
            ];
        }

        return $out;
    }

    /**
     * IDs of every published block targeting `all_eventin_events`. These apply to
     * any event, including one that has not been saved yet (no exclude list can
     * reference it), so they seed the Advanced-tab selector on the create screen.
     *
     * @return int[]
     */
    private function all_event_block_ids(): array {
        $blocks = get_posts(
            [
                'post_type'      => 'optiontics-block',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'no_found_rows'  => true,
            ]
        );

        $ids = [];

        foreach ( $blocks as $block_id ) {
            try {
                $block = new \Optiontics\Models\Block_Model( (int) $block_id );
            } catch ( \Throwable $e ) {
                continue;
            }

            if ( 'all_eventin_events' === (string) $block->product_type ) {
                $ids[] = (int) $block_id;
            }
        }

        return $ids;
    }

    /**
     * Inject a fake fields provider so unit tests need no live Optiontics install.
     *
     * @param callable $provider fn(int $block_id): array<field>.
     * @return void
     */
    public function set_fields_provider( callable $provider ): void {
        $this->fields_provider = $provider;
    }

    /**
     * Whether the Optiontics add-ons master switch is enabled for this event.
     *
     * Mirrors the Advanced-tab toggle, stored as the `optiontics_addons` event meta.
     * When off (the default), the purchase page shows no option fields for the event,
     * even if its tickets have blocks assigned. Defaults off for existing events.
     *
     * @param int $event_id Eventin event post ID.
     * @return bool
     */
    public function addons_enabled( int $event_id ): bool {
        return $event_id > 0 && (bool) get_post_meta( $event_id, 'optiontics_addons', true );
    }

    /**
     * Optiontics Option Block IDs that target the given Eventin event.
     *
     * Resolves event-level assignments authored on the Optiontics side via a block's
     * `product_type` + include/exclude meta:
     *  - 'all_eventin_events'      → applies unless the event is in exclude_products.
     *  - 'specific_eventin_events' → applies when the event is in include_products.
     *
     * @param int $event_id Eventin event post ID.
     * @return int[]
     */
    public function event_block_ids( int $event_id ): array {
        if ( ! $event_id || ! $this->is_available() ) {
            return [];
        }

        $blocks = get_posts(
            [
                'post_type'      => 'optiontics-block',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'no_found_rows'  => true,
            ]
        );

        $ids = [];

        foreach ( $blocks as $block_id ) {
            try {
                $block = new \Optiontics\Models\Block_Model( (int) $block_id );
            } catch ( \Throwable $e ) {
                continue;
            }

            $type = (string) $block->product_type;

            if ( 'all_eventin_events' === $type ) {
                $exclude = array_map( 'absint', (array) $block->exclude_products );
                if ( ! in_array( $event_id, $exclude, true ) ) {
                    $ids[] = (int) $block_id;
                }
            } elseif ( 'specific_eventin_events' === $type ) {
                $include = array_map( 'absint', (array) $block->include_products );
                if ( in_array( $event_id, $include, true ) ) {
                    $ids[] = (int) $block_id;
                }
            }
        }

        return $ids;
    }

    /**
     * Return normalized field definitions for a block (for rendering in React).
     *
     * Each field: { node_id, title, field_type, is_required, choices: [ { value, price, price_type } ] }.
     *
     * @param int $block_id Optiontics Option Block post ID.
     * @return array<int, array<string, mixed>>
     */
    public function get_block_fields( int $block_id ): array {
        if ( $this->fields_provider ) {
            return ( $this->fields_provider )( $block_id );
        }

        if ( ! $this->is_available() ) {
            return [];
        }

        try {
            $block = new \Optiontics\Models\Block_Model( $block_id );
            $raw   = $block->fields;
        } catch ( \Throwable $e ) {
            return [];
        }

        $raw = is_string( $raw ) ? json_decode( $raw, true ) : $raw;

        if ( ! is_array( $raw ) ) {
            return [];
        }

        $out = [];

        foreach ( $raw as $field ) {
            try {
                $def = \Optiontics\Core\FieldUnits\DTO\Field_Definition::from_array( $field );
            } catch ( \Throwable $e ) {
                continue;
            }

            $out[] = [
                'node_id'     => $def->node_id,
                'title'       => $def->title,
                'field_type'  => $def->field_type,
                'is_required' => $def->is_required,
                'choices'     => array_map(
                    function ( $choice ) {
                        $sale = isset( $choice['sale'] ) ? (float) $choice['sale'] : 0.0;

                        return [
                            'value'      => $choice['value'] ?? '',
                            'price'      => $sale > 0 ? $sale : (float) ( $choice['regular'] ?? 0 ),
                            'price_type' => $choice['type'] ?? 'no_cost',
                        ];
                    },
                    $def->choices
                ),
            ];
        }

        return $out;
    }

    /**
     * Authoritatively reprice client-submitted selections for ONE ticket type.
     *
     * The client-sent price is ignored entirely; the price is re-resolved from the
     * stored block definition. Selections whose block / field / choice cannot be
     * matched against the stored definition are dropped.
     *
     * @param array  $selections  Each: { block_id, node_id, choice_value, qty }.
     * @param float  $base        Ticket price (base for percentage-priced choices).
     * @param string $ticket_slug Ticket type slug the selections belong to.
     * @return array<int, array<string, mixed>> Stored rows.
     */
    public function reprice( array $selections, float $base, string $ticket_slug ): array {
        $engine = null;

        if ( ! $this->fields_provider && $this->is_available() ) {
            $engine = new \Optiontics\Core\FieldUnits\Pricing\Pricing_Engine();
        }

        $rows = [];

        foreach ( $selections as $selection ) {
            $block_id = (int) ( $selection['block_id'] ?? 0 );
            $node_id  = (string) ( $selection['node_id'] ?? '' );
            $value    = (string) ( $selection['choice_value'] ?? '' );
            $qty      = max( 1, (int) ( $selection['qty'] ?? 1 ) );

            if ( ! $block_id || '' === $node_id || '' === $value ) {
                continue;
            }

            $field = null;
            foreach ( $this->get_block_fields( $block_id ) as $candidate ) {
                if ( $candidate['node_id'] === $node_id ) {
                    $field = $candidate;
                    break;
                }
            }

            if ( ! $field ) {
                continue;
            }

            $choice = null;
            foreach ( $field['choices'] as $candidate ) {
                if ( $candidate['value'] === $value ) {
                    $choice = $candidate;
                    break;
                }
            }

            if ( null === $choice ) {
                // Tampered / unknown choice → drop.
                continue;
            }

            $unit = $this->resolve_unit_price( $choice, $base, $qty, $engine );

            $rows[] = [
                'ticket_slug'  => $ticket_slug,
                'block_id'     => $block_id,
                'node_id'      => $node_id,
                'field_label'  => $field['title'],
                'choice_value' => $value,
                'unit_price'   => $unit,
                'qty'          => $qty,
                'line_total'   => round( $unit * $qty, 2 ),
            ];
        }

        return $rows;
    }

    /**
     * Sum of line totals.
     *
     * @param array $rows Repriced rows from reprice().
     * @return float
     */
    public function options_total( array $rows ): float {
        return round( array_sum( array_column( $rows, 'line_total' ) ), 2 );
    }

    /**
     * Resolve the authoritative unit price for a normalized choice.
     *
     * Uses Optiontics' Pricing_Engine when available; otherwise (test path /
     * Optiontics absent) computes from the normalized stored price directly.
     *
     * @param array      $choice Normalized choice { value, price, price_type }.
     * @param float      $base   Base price for percentage pricing.
     * @param int        $qty    Quantity.
     * @param mixed|null $engine Optiontics Pricing_Engine instance or null.
     * @return float
     */
    private function resolve_unit_price( array $choice, float $base, int $qty, $engine ): float {
        if ( $engine ) {
            $result = $engine->resolve_for_choice(
                [
                    'type'    => $choice['price_type'],
                    'regular' => $choice['price'],
                    'sale'    => 0.0,
                    'value'   => $choice['value'],
                ],
                $base,
                $qty
            );

            return max( 0.0, (float) $result->effective_price );
        }

        if ( 'percentage' === $choice['price_type'] ) {
            return round( $base * ( (float) $choice['price'] / 100 ), 2 );
        }

        return (float) $choice['price'];
    }
}
