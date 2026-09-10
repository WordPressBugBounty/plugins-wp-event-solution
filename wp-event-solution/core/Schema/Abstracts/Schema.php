<?php
/**
 * Base class for every schema.org type Eventin emits.
 *
 * @package Eventin\Schema
 */

namespace Eventin\Schema\Abstracts;

defined( 'ABSPATH' ) || exit;

/**
 * Carries the work every schema type needs: the shared @context/@type/name/
 * description/image/url fields, a visibility check so unpublished content never
 * leaks, a per-request registry that stops the same post being described twice
 * on one page, and the single named filter that lets add-ons contribute fields
 * without editing this plugin.
 *
 * Subclasses set $type and override get_data() to add their own properties,
 * calling base_data() first and apply_object_filter() last.
 */
abstract class Schema {

    /**
     * The schema.org type this class produces.
     *
     * @var string
     */
    protected $type = 'Thing';

    /**
     * Types already emitted this request, keyed by post ID.
     *
     * Static so every subclass shares one registry: an event page that renders
     * the same event twice (single view plus a related-events widget, an
     * Elementor loop, a shortcode inside the content) still emits one blob.
     *
     * @var array<int, string[]>
     */
    protected static $emitted = [];

    /**
     * Get the schema.org type.
     *
     * @return string
     */
    public function get_type(): string {
        return $this->type;
    }

    /**
     * Build the schema data for a post.
     *
     * @param int|\WP_Post $post Post ID or object.
     * @param array        $args Optional. Pass 'context' => false to omit the
     *                           @context key on nested objects.
     *
     * @return array Map of post ID => data object, or an empty array.
     */
    public function get_data( $post, array $args = [] ): array {
        $data = $this->base_data( $post, $args );

        if ( ! $data ) {
            return [];
        }

        $post = get_post( $post );

        return [ $post->ID => $this->apply_object_filter( $data, $args, $post ) ];
    }

    /**
     * Build the fields shared by every schema type.
     *
     * Returns null when the post does not exist or must not be described.
     *
     * @param int|\WP_Post $post Post ID or object.
     * @param array        $args Arguments passed to get_data().
     *
     * @return object|null
     */
    protected function base_data( $post, array $args ) {
        $post = get_post( $post );

        if ( ! $post instanceof \WP_Post || ! $this->is_viewable( $post ) ) {
            return null;
        }

        $data = (object) [];

        // Nested objects (a Place inside an Event) must not repeat @context.
        if ( ! isset( $args['context'] ) || false !== $args['context'] ) {
            $data->{'@context'} = 'https://schema.org';
        }

        $data->{'@type'} = $this->type;
        $data->name      = wp_strip_all_tags( get_the_title( $post ) );

        $description = $this->get_description( $post );
        if ( $description ) {
            $data->description = $description;
        }

        $image = $this->get_image( $post );
        if ( $image ) {
            $data->image = $image;
        }

        $url = $this->get_link( $post );
        if ( $url ) {
            $data->url = esc_url_raw( $url );
        }

        return $data;
    }

    /**
     * Whether this post may be described to a search engine.
     *
     * Mirrors what the visitor is allowed to see: anonymous visitors only get
     * published, unprotected posts; logged-in users get what their capabilities
     * allow. Without this, a draft event's title, date and price would sit in
     * the page source of any preview that happens to be public.
     *
     * @param \WP_Post $post Post object.
     *
     * @return bool
     */
    protected function is_viewable( \WP_Post $post ): bool {
        if ( post_password_required( $post ) ) {
            return false;
        }

        if ( 'publish' === $post->post_status ) {
            return true;
        }

        if ( ! is_user_logged_in() ) {
            return false;
        }

        $capability = 'private' === $post->post_status ? 'read_private_posts' : 'read';

        return current_user_can( $capability, $post->ID );
    }

    /**
     * Description text for the post.
     *
     * @param \WP_Post $post Post object.
     *
     * @return string
     */
    protected function get_description( \WP_Post $post ): string {
        $excerpt = has_excerpt( $post ) ? get_the_excerpt( $post ) : $post->post_content;
        $excerpt = wp_strip_all_tags( strip_shortcodes( $excerpt ) );
        $excerpt = trim( preg_replace( '/\s+/', ' ', $excerpt ) );

        return wp_html_excerpt( $excerpt, 500, '…' );
    }

    /**
     * Featured image URL for the post.
     *
     * @param \WP_Post $post Post object.
     *
     * @return string
     */
    protected function get_image( \WP_Post $post ): string {
        $url = get_the_post_thumbnail_url( $post, 'full' );

        return $url ? esc_url_raw( $url ) : '';
    }

    /**
     * Canonical link for the post. Subclasses override where the post type has
     * no public permalink of its own.
     *
     * @param \WP_Post $post Post object.
     *
     * @return string
     */
    protected function get_link( \WP_Post $post ): string {
        $link = get_permalink( $post );

        return $link ? $link : '';
    }

    /**
     * Let themes and add-ons change the object before it is emitted.
     *
     * This is the extension seam. Eventin Pro, the WooCommerce / FluentCart
     * integrations and site-specific snippets all hook here rather than editing
     * this plugin.
     *
     * @example eventin_schema_event_object
     * @example eventin_schema_place_object
     *
     * @param object   $data Schema object.
     * @param array    $args Arguments passed to get_data().
     * @param \WP_Post $post Post object.
     *
     * @return object
     */
    protected function apply_object_filter( $data, array $args, \WP_Post $post ) {
        $type = strtolower( $this->type );

        /**
         * Filters a single schema object before it is emitted.
         *
         * @since 4.1.20
         *
         * @param object   $data Schema object.
         * @param array    $args Arguments passed to get_data().
         * @param \WP_Post $post Post object.
         */
        return apply_filters( "eventin_schema_{$type}_object", $data, $args, $post );
    }

    /**
     * Whether this post has already been described as this type in this request.
     *
     * @param int $post_id Post ID.
     *
     * @return bool
     */
    public function already_emitted( $post_id ): bool {
        $post_id = absint( $post_id );

        return isset( self::$emitted[ $post_id ] ) && in_array( $this->type, self::$emitted[ $post_id ], true );
    }

    /**
     * Record that this post has been described as this type.
     *
     * @param int $post_id Post ID.
     *
     * @return void
     */
    public function mark_emitted( $post_id ): void {
        $post_id = absint( $post_id );

        if ( ! isset( self::$emitted[ $post_id ] ) ) {
            self::$emitted[ $post_id ] = [];
        }

        if ( ! in_array( $this->type, self::$emitted[ $post_id ], true ) ) {
            self::$emitted[ $post_id ][] = $this->type;
        }
    }

    /**
     * Clear the registry. Used by tests.
     *
     * @return void
     */
    public static function reset_emitted(): void {
        self::$emitted = [];
    }
}
