<?php
namespace Eventin\PreviewPlaceholder;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;

/**
 * Hides the preview-placeholder event and its linked records from the front-end
 * (archives, search, feeds, taxonomy pages, direct single-page access, and the
 * secondary WP_Query/get_posts loops run by shortcodes and Elementor widgets).
 *
 * The placeholder's speaker/organizer users are hidden from the WP admin Users
 * list by the existing hide_user filter (core/speaker/hooks.php). This class adds
 * the front-end coverage that filter never had: the pre_get_users handler below
 * strips those users from the speaker/organizer shortcodes, Elementor widgets and
 * blocks (all of which fetch users via get_users()/WP_User_Query).
 *
 * Template previews are unaffected: block rendering reads the event by ID
 * (new Event_Model( $id ) / get_post()) and its speakers/organizers by explicit
 * `include` — and WP_User_Query ignores `exclude` whenever `include` is set — so
 * the placeholder's own people still render inside its preview.
 */
class PreviewPlaceholderVisibility implements HookableInterface {

    /**
     * Register all hooks for the class.
     */
    public function register_hooks(): void {
        add_action( 'pre_get_posts', [ $this, 'hide_from_public_queries' ] );
        add_action( 'pre_get_posts', [ $this, 'hide_from_secondary_queries' ] );
        add_action( 'pre_get_users', [ $this, 'hide_from_user_queries' ] );
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
     * Exclude the placeholder event + schedules from the secondary WP_Query/get_posts
     * loops that shortcodes and Elementor widgets run on the front-end.
     *
     * The main-query case is handled by hide_from_public_queries(); this targets
     * non-main queries only. Admin and REST are skipped so the admin dashboard can
     * still list/manage the placeholder and REST collection responses stay intact.
     * Queries that explicitly pin a placeholder post by ID (e.g. a direct fetch used
     * by some preview paths) are left alone.
     *
     * @param \WP_Query $query
     */
    public function hide_from_secondary_queries( $query ): void {
        if ( is_admin() || $query->is_main_query() ) {
            return;
        }
        if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
            return;
        }
        $post_type = (array) $query->get( 'post_type' );
        if ( ! array_intersect( [ 'etn', 'etn-schedule' ], $post_type ) ) {
            return;
        }
        $ids = PreviewPlaceholder::excluded_post_ids();
        if ( ! $ids ) {
            return;
        }
        // Don't exclude when the query explicitly targets a placeholder post by ID.
        $p = (int) $query->get( 'p' );
        if ( $p && in_array( $p, $ids, true ) ) {
            return;
        }
        $existing = (array) $query->get( 'post__not_in' );
        $query->set( 'post__not_in', array_values( array_unique( array_merge( $existing, $ids ) ) ) );
    }

    /**
     * Exclude the placeholder speaker/organizer users from the front-end user
     * listings run by the speaker/organizer shortcodes, Elementor widgets and
     * blocks (all of which fetch users through get_users()/WP_User_Query, so this
     * pre_get_users action fires for every one of them).
     *
     * Admin and REST are skipped: the admin dashboard manages these users through
     * REST controllers that do their own exclusion (SpeakerController), and the WP
     * admin Users table is handled by the hide_user filter in core/speaker/hooks.php.
     *
     * Queries that explicitly target the placeholder users by `include` (e.g. the
     * Template Builder preview rendering the placeholder event's own speaker /
     * organizer blocks) are left alone. This is also enforced by WP_User_Query
     * itself, which ignores `exclude` whenever `include` is present.
     *
     * @param \WP_User_Query $query
     */
    public function hide_from_user_queries( $query ): void {
        if ( is_admin() ) {
            return;
        }
        if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
            return;
        }
        $ids = PreviewPlaceholder::user_ids();
        if ( ! $ids || ! PreviewPlaceholder::event_exists() ) {
            return;
        }
        // Don't strip the placeholder users when the query pins them by ID.
        $include = wp_parse_id_list( (array) $query->get( 'include' ) );
        if ( $include && array_intersect( $include, $ids ) ) {
            return;
        }
        $existing = wp_parse_id_list( (array) $query->get( 'exclude' ) );
        $query->set( 'exclude', array_values( array_unique( array_merge( $existing, $ids ) ) ) );
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
