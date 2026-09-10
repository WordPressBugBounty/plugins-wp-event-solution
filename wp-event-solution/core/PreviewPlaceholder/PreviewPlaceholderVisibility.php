<?php
namespace Eventin\PreviewPlaceholder;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;

/**
 * Hides the preview-placeholder event and its linked records from the front-end
 * (archives, search, feeds, taxonomy pages, direct single-page access, and the
 * secondary WP_Query/get_posts loops run by shortcodes and Elementor widgets),
 * and from the core `wp/v2/etn` REST collection that backs the Bricks builder's
 * "Select Preview Event" dropdown.
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
        add_filter( 'rest_etn_query', [ $this, 'hide_from_rest_collection' ], 10, 2 );
        add_filter( 'rest_etn-schedule_query', [ $this, 'hide_from_rest_collection' ], 10, 2 );
        add_filter( 'rest_attachment_query', [ $this, 'hide_from_rest_collection' ], 10, 2 );
        add_filter( 'ajax_query_attachments_args', [ $this, 'hide_from_media_modal' ] );
        add_action( 'pre_get_posts', [ $this, 'hide_from_media_library' ] );
        add_filter( 'wp_count_attachments', [ $this, 'exclude_from_attachment_counts' ], 10, 2 );
        add_action( 'pre_get_posts', [ $this, 'hide_from_list_table' ] );
        add_filter( 'wp_count_posts', [ $this, 'exclude_from_post_counts' ], 10, 2 );
        add_action( 'pre_get_users', [ $this, 'hide_from_user_queries' ] );
        add_action( 'template_redirect', [ $this, 'block_direct_access' ] );
    }

    /**
     * Exclude the placeholder event + schedules from public archive/search/feed queries.
     *
     * @param \WP_Query $query
     */
    public function hide_from_public_queries( $query ): void {
        if ( $this->is_admin_context() || ! $query->is_main_query() ) {
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
        if ( $this->is_admin_context() || $query->is_main_query() ) {
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
     * Exclude the placeholder from the core wp/v2 event, schedule and media
     * collections. Fires in get_items() only, so single-item reads still work.
     * Requests pinning a placeholder by include=/p are left alone.
     *
     * @param array            $args    WP_Query args.
     * @param \WP_REST_Request $request REST request.
     *
     * @return array
     */
    public function hide_from_rest_collection( $args, $request ) {
        if ( ! is_array( $args ) ) {
            return $args;
        }

        $ids = array_values( array_unique( array_merge(
            PreviewPlaceholder::excluded_post_ids(),
            PreviewPlaceholder::attachment_ids()
        ) ) );
        if ( ! $ids ) {
            return $args;
        }

        // Don't exclude when the request explicitly targets a placeholder post.
        $pinned = wp_parse_id_list( (array) ( $args['post__in'] ?? [] ) );
        if ( ! empty( $args['p'] ) ) {
            $pinned[] = (int) $args['p'];
        }
        if ( array_intersect( $pinned, $ids ) ) {
            return $args;
        }

        $existing = wp_parse_id_list( (array) ( $args['post__not_in'] ?? [] ) );

        $args['post__not_in'] = array_values( array_unique( array_merge( $existing, $ids ) ) );

        return $args;
    }

    /**
     * Exclude the placeholder banner from the media modal grid.
     *
     * @param   array  $args  WP_Query args.
     *
     * @return  array
     */
    public function hide_from_media_modal( $args ) {
        $ids = PreviewPlaceholder::attachment_ids();

        if ( ! $ids || ! is_array( $args ) ) {
            return $args;
        }

        $existing = wp_parse_id_list( (array) ( $args['post__not_in'] ?? [] ) );

        $args['post__not_in'] = array_values( array_unique( array_merge( $existing, $ids ) ) );

        return $args;
    }

    /**
     * Exclude the placeholder banner from the upload.php list table. The modal and
     * REST have their own filters; queries pinning the banner by ID are left alone.
     *
     * @param \WP_Query $query
     */
    public function hide_from_media_library( $query ): void {
        if ( ! is_admin() || 'upload.php' !== ( $GLOBALS['pagenow'] ?? '' ) ) {
            return;
        }

        if ( ! in_array( 'attachment', (array) $query->get( 'post_type' ), true ) ) {
            return;
        }

        $ids = PreviewPlaceholder::attachment_ids();

        if ( ! $ids ) {
            return;
        }

        $pinned = wp_parse_id_list( (array) $query->get( 'post__in' ) );
        $p      = (int) $query->get( 'p' );

        if ( $p ) {
            $pinned[] = $p;
        }

        if ( array_intersect( $pinned, $ids ) ) {
            return;
        }

        $existing = wp_parse_id_list( (array) $query->get( 'post__not_in' ) );

        $query->set( 'post__not_in', array_values( array_unique( array_merge( $existing, $ids ) ) ) );
    }

    /**
     * Keep the upload.php counts in step with the rows the handler above filters.
     * Scoped to that screen because wp_count_attachments() is global.
     *
     * @param   object  $counts  Per-mime counts.
     * @param   string  $mime    Mime filter.
     *
     * @return  object
     */
    public function exclude_from_attachment_counts( $counts, $mime ) {
        if ( ! is_admin() || 'upload.php' !== ( $GLOBALS['pagenow'] ?? '' ) ) {
            return $counts;
        }

        foreach ( PreviewPlaceholder::attachment_ids() as $attachment_id ) {
            $post = get_post( $attachment_id );

            if ( ! $post ) {
                continue;
            }

            $count_key = 'trash' === $post->post_status ? 'trash' : $post->post_mime_type;

            if ( empty( $counts->{$count_key} ) ) {
                continue;
            }

            $counts->{$count_key}--;
        }

        return $counts;
    }

    /**
     * Exclude the placeholder event + schedules from the classic list tables at
     * wp-admin/edit.php.
     *
     * is_admin_context() deliberately lets real admin screens through so the
     * dashboard can still manage the placeholder — but the Eventin dashboard is a
     * React app reading the `eventin/v2` REST lists, which exclude it in their own
     * controllers. edit.php is the one admin screen left running a plain WP_Query,
     * so the demo event was listed there as an ordinary row.
     *
     * Queries pinning a placeholder post by ID are left alone, so editing it
     * directly (post.php, or a filtered list built from explicit IDs) still works.
     *
     * @param \WP_Query $query
     */
    public function hide_from_list_table( $query ): void {
        if ( ! is_admin() || 'edit.php' !== ( $GLOBALS['pagenow'] ?? '' ) ) {
            return;
        }

        if ( ! array_intersect( [ 'etn', 'etn-schedule' ], (array) $query->get( 'post_type' ) ) ) {
            return;
        }

        $ids = PreviewPlaceholder::excluded_post_ids();

        if ( ! $ids ) {
            return;
        }

        $pinned = wp_parse_id_list( (array) $query->get( 'post__in' ) );
        $p      = (int) $query->get( 'p' );

        if ( $p ) {
            $pinned[] = $p;
        }

        if ( array_intersect( $pinned, $ids ) ) {
            return;
        }

        $existing = wp_parse_id_list( (array) $query->get( 'post__not_in' ) );

        $query->set( 'post__not_in', array_values( array_unique( array_merge( $existing, $ids ) ) ) );
    }

    /**
     * Keep the edit.php status links ("All (N)", "Published (N)", "Trash (N)") in
     * step with the rows the handler above filters out. Scoped to that screen and
     * to our own post types, because wp_count_posts() is global.
     *
     * @param   object  $counts  Per-status counts.
     * @param   string  $type    Post type being counted.
     *
     * @return  object
     */
    public function exclude_from_post_counts( $counts, $type ) {
        if ( ! is_admin() || 'edit.php' !== ( $GLOBALS['pagenow'] ?? '' ) ) {
            return $counts;
        }

        if ( ! in_array( $type, [ 'etn', 'etn-schedule' ], true ) ) {
            return $counts;
        }

        foreach ( PreviewPlaceholder::excluded_post_ids() as $post_id ) {
            $post = get_post( $post_id );

            if ( ! $post || $type !== $post->post_type ) {
                continue;
            }

            if ( empty( $counts->{$post->post_status} ) ) {
                continue;
            }

            $counts->{$post->post_status}--;
        }

        return $counts;
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
        if ( $this->is_admin_context() ) {
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
     * Whether this request is a genuine WP admin screen, where the placeholder must
     * stay visible so the dashboard can list and manage it.
     *
     * `is_admin()` alone is not enough. `wp-admin/admin-ajax.php` defines `WP_ADMIN`,
     * so `is_admin()` is true for *every* AJAX request — including the
     * `wp_ajax_nopriv_*` handlers that render front-end markup for logged-out
     * visitors. Treating those as admin left the demo event listed in full by any
     * front-end AJAX lister (Pro's "Event Locations" map widget and its BuddyBoss
     * event list both run their own `WP_Query( [ 'post_type' => 'etn' ] )`).
     *
     * So AJAX is never an admin context here: the dashboard reads its lists over the
     * `eventin/v2` REST API, which both `pre_get_posts` handlers skip separately, and
     * no wp-admin screen depends on the placeholder surviving an admin-ajax query.
     * This also covers Elementor's editor round-trips, which are plain admin-ajax.
     *
     * @return bool True on real admin screens, false whenever front-end content is
     *              being rendered (AJAX handlers, Elementor's canvas/preview).
     */
    protected function is_admin_context(): bool {
        if ( ! is_admin() ) {
            return false;
        }

        if ( wp_doing_ajax() ) {
            return false;
        }

        return ! $this->is_elementor_render_request();
    }

    /**
     * Whether Elementor is currently rendering front-end content on an admin screen —
     * the editor canvas or the preview iframe.
     *
     * Elementor's editor AJAX round-trips (widget re-render, template insert, "load
     * more") need no special case here: is_admin_context() already treats every AJAX
     * request as front-end.
     *
     * @return bool
     */
    protected function is_elementor_render_request(): bool {
        if ( ! class_exists( '\Elementor\Plugin' ) || ! isset( \Elementor\Plugin::$instance ) ) {
            return false;
        }

        $elementor = \Elementor\Plugin::$instance;

        if ( isset( $elementor->editor ) && $elementor->editor->is_edit_mode() ) {
            return true;
        }

        if ( isset( $elementor->preview ) && $elementor->preview->is_preview_mode() ) {
            return true;
        }

        return false;
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
