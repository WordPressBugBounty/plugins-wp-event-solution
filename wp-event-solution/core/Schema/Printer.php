<?php
/**
 * Decides when the event schema is written into the page.
 *
 * @package Eventin\Schema
 */

namespace Eventin\Schema;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;

/**
 * Prints the Event JSON-LD in the document head of a single event page.
 *
 * The head is where search engines and every other WordPress SEO plugin put
 * structured data; the previous implementation printed it in the body, after
 * the content, which is valid but out of step with everything else on the page.
 */
class Printer implements HookableInterface {

    /**
     * Event schema builder.
     *
     * @var EventSchema
     */
    protected $schema;

    /**
     * Constructor.
     *
     * @param EventSchema|null $schema Event schema builder.
     */
    public function __construct( EventSchema $schema = null ) {
        $this->schema = $schema ? $schema : new EventSchema();
    }

    /**
     * Register hooks.
     *
     * @return void
     */
    public function register_hooks(): void {
        add_action( 'wp_head', [ $this, 'print_single_event' ], 20 );
    }

    /**
     * Print the schema for the event currently being viewed.
     *
     * @return void
     */
    public function print_single_event(): void {
        if ( ! $this->should_print() ) {
            return;
        }

        $post = get_queried_object();

        if ( ! $post instanceof \WP_Post ) {
            return;
        }

        echo $this->schema->get_markup( $post ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-LD built and encoded by EventSchema::get_markup().
    }

    /**
     * Whether structured data should be emitted for this request.
     *
     * @return bool
     */
    public function should_print(): bool {
        $should = is_singular( 'etn' ) && self::is_enabled();

        /**
         * Filters whether Eventin prints its own event structured data.
         *
         * Return false to stand down — for example when an SEO plugin is already
         * describing the event and two competing descriptions would confuse
         * search engines.
         *
         * @since 4.1.20
         *
         * @param bool $should Whether to print.
         */
        return (bool) apply_filters( 'eventin_schema_should_print', $should );
    }

    /**
     * Whether the site owner has switched schema mark-up on.
     *
     * The stored key reads like an "off" switch for historical reasons but has
     * always behaved as an "on" switch — the Schema Mark-up toggle in Events →
     * Settings writes a truthy value when enabled.
     *
     * @return bool
     */
    public static function is_enabled(): bool {
        $options = get_option( 'etn_event_options' );

        return ! empty( $options['disable_rich_snippets_for_event'] );
    }
}
