<?php
/**
 * Updater for version 4.1.15
 *
 * @package Eventin\Upgrade
 */

namespace Eventin\Upgrade\Upgraders;

defined( 'ABSPATH' ) || exit;

/**
 * Adds a composite (meta_key, meta_value) index to wp_postmeta.
 *
 * Eventin filters orders/attendees heavily by meta_value (event_id,
 * eventin_order_id, status, ticket status, …). WordPress core only indexes
 * meta_key, so on installs with many orders/attendees those lookups degrade to
 * large scans (e.g. the bookings list ran ~6s with 250k attendees because each
 * row triggered an unindexed eventin_order_id lookup). This composite index
 * turns those scans into seeks. Idempotent: skips if the index already exists.
 *
 * @since 4.1.15
 */
class V_4_1_15 implements UpdateInterface {
    /**
     * Index name.
     */
    const INDEX_NAME = 'etn_key_value';

    /**
     * Run the updater
     *
     * @return void
     */
    public function run() {
        global $wpdb;

        $table = $wpdb->postmeta;

        /*
         * Both statements below interpolate only two values:
         *   - $table            = $wpdb->postmeta, a WordPress core property.
         *   - self::INDEX_NAME  = the 'etn_key_value' class constant.
         * Neither is user input, and MySQL does not accept table or index names
         * as prepared placeholders, so they must be written into the SQL text.
         * The one value that can vary (the index name in the SHOW INDEX lookup)
         * is passed through $wpdb->prepare() with a %s placeholder.
         */
        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange

        // Bail if the index already exists (manual install or a previous run).
        $existing = $wpdb->get_results( $wpdb->prepare(
            "SHOW INDEX FROM `{$table}` WHERE Key_name = %s",
            self::INDEX_NAME
        ) );

        if ( ! empty( $existing ) ) {
            return;
        }

        // meta_value is LONGTEXT; index a 32-char prefix, which fully covers the
        // short numeric/string values Eventin filters on (ids, statuses, slugs).
        $wpdb->query(
            "ALTER TABLE `{$table}` ADD INDEX " . self::INDEX_NAME . " (meta_key(191), meta_value(32))"
        );

        // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
    }
}
