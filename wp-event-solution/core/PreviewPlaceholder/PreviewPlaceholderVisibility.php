<?php
namespace Eventin\PreviewPlaceholder;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;

/**
 * Hides the preview-placeholder event and its linked records from the front-end
 * (archives, search, feeds, taxonomy pages, and direct single-page access).
 *
 * The WP Users list is already handled by the existing hide_user filter
 * (core/speaker/hooks.php); this class covers the remaining public surfaces.
 * Template previews are unaffected because block rendering reads the event by ID,
 * not through the queries filtered here.
 */
class PreviewPlaceholderVisibility implements HookableInterface {

    /**
     * Register all hooks for the class.
     */
    public function register_hooks(): void {
        add_action( 'pre_get_posts', [ $this, 'hide_from_public_queries' ] );
        add_action( 'template_redirect', [ $this, 'block_direct_access' ] );
    }

    /**
     * Exclude the placeholder event + schedules from public archive/search/feed queries.
     *
     * @param \WP_Query $query
     */
    public function hide_from_public_queries( $query ): void {
        if ( is_admin() || ! $query->is_main_query() ) {
            return;
        }
        if ( ! ( $query->is_post_type_archive( 'etn' ) || $query->is_search() || $query->is_feed() || $query->is_tax() ) ) {
            return;
        }
        $ids = PreviewPlaceholder::excluded_post_ids();
        if ( ! $ids ) {
            return;
        }
        $existing = (array) $query->get( 'post__not_in' );
        $query->set( 'post__not_in', array_merge( $existing, $ids ) );
    }

    /**
     * 404 direct single-page access to the placeholder event, its schedules, or speakers.
     */
    public function block_direct_access(): void {
        if ( is_admin() ) {
            return;
        }
        $blocked = false;

        if ( is_singular( [ 'etn', 'etn-schedule' ] ) ) {
            $blocked = in_array( (int) get_queried_object_id(), PreviewPlaceholder::excluded_post_ids(), true );
        } elseif ( is_author() ) {
            $blocked = in_array( (int) get_queried_object_id(), PreviewPlaceholder::user_ids(), true );
        }

        if ( $blocked ) {
            global $wp_query;
            $wp_query->set_404();
            status_header( 404 );
            nocache_headers();
        }
    }
}
