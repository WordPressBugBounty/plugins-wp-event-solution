<?php
/**
 * Static (starter) template config.
 *
 * SINGLE SOURCE OF TRUTH for the Template Builder starter templates, grouped by
 * type (landing/event, ticket, certificate, speaker). Each template carries an
 * `is_pro` flag:
 *   - is_pro = false  → free: importable with the free plugin.
 *   - is_pro = true   → pro:  shown but locked, and NOT importable when Eventin
 *                              Pro is inactive (enforced in TemplateController).
 *
 * To add/remove a starter template or change its free/pro status, edit the
 * `templates()` map below — nothing else needs to change.
 *
 * @package Eventin
 */

namespace Eventin\Template;

defined( 'ABSPATH' ) || exit;

use Wpeventin;

class StaticTemplateConfig {

    /**
     * The full starter-template config, keyed by template type.
     *
     * @return array<string, array<int, array>>
     */
    public static function templates() {
        $assets = Wpeventin::plugin_url() . 'assets/images/';

        return [
            /**
             * Landing page (event) templates.
             * event-two & event-three are free — their single-event page layouts
             * ship in the free plugin, so the starter templates match.
             */
            'event' => [
                self::entry( [
                    'id'           => 'event-one',
                    'name'         => __( 'Event Template One', 'eventin' ),
                    'type'         => 'event',
                    'orientation'  => 'portrait',
                    'thumbnail'    => $assets . 'landing_template_1.webp',
                    'is_pro'       => false,
                    'preview_link' => 'https://product.themewinter.com/eventin/event/event-details-1/',
                ] ),
                self::entry( [
                    'id'           => 'event-two',
                    'name'         => __( 'Event Template Two', 'eventin' ),
                    'type'         => 'event',
                    'orientation'  => 'portrait',
                    'thumbnail'    => $assets . 'landing_template_2.webp',
                    'is_pro'       => false,
                    'preview_link' => 'https://product.themewinter.com/eventin/event/event-details-2/',
                ] ),
                self::entry( [
                    'id'           => 'event-three',
                    'name'         => __( 'Event Template Three', 'eventin' ),
                    'type'         => 'event',
                    'orientation'  => 'portrait',
                    'thumbnail'    => $assets . 'landing_template_3.webp',
                    'is_pro'       => false,
                    'preview_link' => 'https://product.themewinter.com/eventin/event/event-details-3/',
                ] ),
            ],

            /**
             * Ticket templates.
             */
            'ticket' => [
                self::entry( [
                    'id'           => 'style-1',
                    'name'         => __( 'Template One', 'eventin' ),
                    'type'         => 'ticket',
                    'orientation'  => 'landscape',
                    'thumbnail'    => $assets . 'ticket_template_1.webp',
                    'is_pro'       => false,
                    'preview_link' => 'https://product.themewinter.com/eventin/ticket-template-one/',
                ] ),
                self::entry( [
                    'id'           => 'style-2',
                    'name'         => __( 'Template Two', 'eventin' ),
                    'type'         => 'ticket',
                    'orientation'  => 'landscape',
                    'thumbnail'    => $assets . 'ticket_template_2.webp',
                    'is_pro'       => false,
                    'preview_link' => 'https://product.themewinter.com/eventin/ticket-template-two/',
                ] ),
            ],

            /**
             * Certificate templates.
             */
            'certificate' => [],

            /**
             * Speaker templates.
             */
            'speaker' => [
                self::entry( [
                    'id'           => 'speaker-one',
                    'name'         => __( 'Template One', 'eventin' ),
                    'type'         => 'speaker',
                    'orientation'  => 'portrait',
                    'thumbnail'    => $assets . 'speaker_template_1.webp',
                    'is_pro'       => false,
                    'preview_link' => 'https://product.themewinter.com/eventin/admin/james/',
                ] ),
                self::entry( [
                    'id'           => 'speaker-two-lite',
                    'name'         => __( 'Template Two', 'eventin' ),
                    'type'         => 'speaker',
                    'orientation'  => 'portrait',
                    'thumbnail'    => $assets . 'speaker_template_2.webp',
                    'is_pro'       => false,
                    'preview_link' => 'https://product.themewinter.com/eventin/admin/henri/',
                ] ),
                self::entry( [
                    'id'           => 'speaker-two',
                    'name'         => __( 'Template Three', 'eventin' ),
                    'type'         => 'speaker',
                    'orientation'  => 'portrait',
                    'thumbnail'    => $assets . 'speaker_template_3.webp',
                    'is_pro'       => true,
                    'preview_link' => 'https://product.themewinter.com/eventin/admin/jim/',
                ] ),
                self::entry( [
                    'id'           => 'speaker-three',
                    'name'         => __( 'Template Four', 'eventin' ),
                    'type'         => 'speaker',
                    'orientation'  => 'portrait',
                    'thumbnail'    => $assets . 'speaker_template_4.webp',
                    'is_pro'       => true,
                    'preview_link' => 'https://product.themewinter.com/eventin/admin/laura-bryant/',
                ] ),
            ],
        ];
    }

    /**
     * Build a full static-template entry from the short form above, filling in
     * the shape the REST layer + StaticTemplate adapter expect.
     *
     * @param array $data
     *
     * @return array
     */
    private static function entry( array $data ) {
        return wp_parse_args( $data, [
            'id'                  => '',
            'name'                => '',
            'status'              => 'publish',
            'type'                => '',
            'orientation'         => 'portrait',
            'thumbnail'           => '',
            'content'             => '',
            'is_clone'            => false,
            'is_pro'              => false,
            'template_css'        => '',
            'edit_link'           => '',
            'preview_link'        => '',
            'preview_event_id'    => null,
            'template_builder'    => 'gutenberg',
            'edit_with_elementor' => false,
            'isStatic'            => true,
        ] );
    }

    /**
     * Get the starter templates for a single type (or all types when empty).
     *
     * @param string $type
     *
     * @return array
     */
    public static function by_type( $type = '' ) {
        $templates = self::templates();

        if ( '' === $type ) {
            return array_merge( ...array_values( $templates ) );
        }

        return $templates[ $type ] ?? [];
    }

    /**
     * Frontend display shape for the Template Builder's "Default" cards.
     *
     * The Template Builder page sources its Default (static) cards from HERE —
     * the local config, the single source of truth — instead of the remote
     * template server. The remote server does not ship every default (e.g. it
     * has no certificate static), so reading them remotely left those defaults
     * missing. Sourcing locally keeps the builder in sync with this config and
     * resilient to the remote server being unreachable.
     *
     * Only the fields the cards render are included (no heavy content/css).
     *
     * @return array<int, array>
     */
    public static function localized() {
        return array_map(
            function ( $template ) {
                return [
                    'id'               => $template['id'],
                    'name'             => $template['name'],
                    'status'           => $template['status'],
                    'type'             => $template['type'],
                    'orientation'      => $template['orientation'],
                    'thumbnail'        => $template['thumbnail'],
                    'is_pro'           => $template['is_pro'],
                    'edit_link'        => '',
                    'preview_link'     => $template['preview_link'],
                    'template_builder' => $template['template_builder'],
                    'isStatic'         => true,
                ];
            },
            self::by_type()
        );
    }

    /**
     * Find a single starter template by id (optionally scoped to a type).
     *
     * @param string $id
     * @param string $type
     *
     * @return array|null
     */
    public static function get( $id, $type = '' ) {
        foreach ( self::by_type( $type ) as $template ) {
            if ( $template['id'] === $id ) {
                return $template;
            }
        }

        return null;
    }

    /**
     * Remote (themewinter template server) template ids that are free to import
     * even without Eventin Pro.
     *
     * These are NON-static remote templates, so they are listed by their remote
     * id only — NOT as full entries in templates() — to avoid duplicating them in
     * the local static list (they already arrive from the remote fetch).
     *
     * @return array<int, string>
     */
    public static function free_remote_ids() {
        return [
            '254', // Event Template Four
            '166', // Event Template Five
            '168', // Event Template Six
        ];
    }

    /**
     * Ids of every free template — free static entries plus free remote ids — as
     * strings. Localised to the Template Builder so it decides lock state from
     * this config instead of the remote server's is_pro flag.
     *
     * @return array<int, string>
     */
    public static function free_ids() {
        $ids = [];

        foreach ( self::by_type() as $template ) {
            if ( empty( $template['is_pro'] ) ) {
                $ids[] = (string) $template['id'];
            }
        }

        return array_values( array_unique( array_merge( $ids, self::free_remote_ids() ) ) );
    }

    /**
     * Whether a template is free — its id is in the free list (static or remote).
     * This config is the single source of truth for free/pro; the remote server's
     * own is_pro value is intentionally ignored.
     *
     * @param string $id
     * @param string $type
     *
     * @return bool
     */
    public static function is_free( $id, $type = '' ) {
        return in_array( (string) $id, self::free_ids(), true );
    }

    /**
     * Whether a template requires Eventin Pro.
     *
     * Rule: anything NOT in the free list is pro. So an unknown id (e.g. a remote
     * template not whitelisted here) is treated as pro.
     *
     * @param string $id
     * @param string $type
     *
     * @return bool
     */
    public static function requires_pro( $id, $type = '' ) {
        return ! self::is_free( $id, $type );
    }
}
