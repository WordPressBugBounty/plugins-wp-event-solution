<?php
/**
 * Custom-table schema installer.
 *
 * @package Eventin\Upgrade
 */

namespace Eventin\Upgrade;

defined( 'ABSPATH' ) || exit;

use Eventin\Support\DbLock;

/**
 * Creates and keeps current the plugin's own database tables.
 *
 * Schema deliberately does NOT live in the versioned upgrader chain. `Upgrade::register()`
 * runs an upgrader only when its version is newer than the stored `etn_db_migration`
 * marker, and a site with no marker at all — a fresh install — runs the current
 * release's upgrader alone. So a site installing 4.1.19 from scratch never reached
 * `V_4_1_17`, never got the coupon tables, and could not create a coupon: every write
 * failed with `coupon_create_error` (HTTP 500). It could not recover either, because
 * the marker was then written as 4.1.19 and `do_upgrade()` only fires when the running
 * version exceeds it.
 *
 * Table creation is not a migration — it has no "from" version. It is a statement of
 * what the running code needs, so it is expressed here, gated on its own
 * `etn_schema_version` option rather than on the release version, and is safe to run
 * on every request. Data migrations (backfills, rewrites) stay in the upgraders, where
 * "which version did we come from" is the right question; a fresh install skipping
 * those is correct, since there is no legacy data to convert.
 *
 * Every step is guarded by SHOW TABLES / SHOW COLUMNS, so running it repeatedly is a
 * no-op and never touches existing rows.
 *
 * Regression cover: tests/phpunit/tests/ReleaseCouponSchemaInstallTest.php.
 *
 * @since 4.1.19
 */
class Schema {
    /**
     * Bump when a table or column is added below, so installed sites re-check.
     *
     * @var string
     */
    const VERSION = '1';

    /**
     * Option holding the schema version last verified as installed.
     *
     * @var string
     */
    const OPTION = 'etn_schema_version';

    /**
     * Install the schema unless this site has already verified this version.
     *
     * The cheap path is a single autoloaded option read, which is what makes this
     * safe to call on every admin request. Runs under a lock because `admin_init`
     * is re-entered by every admin-ajax request, and the option is stamped only
     * once the tables verify as present — a failed install (no CREATE privilege,
     * disk full) must retry on the next request rather than be recorded as done.
     *
     * @return void
     */
    public static function install_if_needed() {
        if ( self::VERSION === get_option( self::OPTION ) ) {
            return;
        }

        if ( ! DbLock::acquire( 'etn_schema_lock' ) ) {
            return;
        }

        try {
            if ( self::install() ) {
                update_option( self::OPTION, self::VERSION, true );
            }
        } finally {
            DbLock::release( 'etn_schema_lock' );
        }
    }

    /**
     * Create every missing table and column. Idempotent.
     *
     * @return bool True when the schema is fully present afterwards.
     */
    public static function install() {
        $ok = self::create_coupons_table();
        $ok = self::create_coupon_redemptions_table() && $ok;

        // Only meaningful once the table exists; self-guarded either way.
        self::add_coupon_discount_scope_column();

        return $ok;
    }

    /**
     * Create the etn_coupons table if it doesn't exist.
     *
     * @return bool True when the table exists afterwards.
     */
    public static function create_coupons_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'etn_coupons';

        if ( self::table_exists( $table_name ) ) {
            return true;
        }

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table_name} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            code VARCHAR(100) NOT NULL,
            discount_type VARCHAR(20) NOT NULL DEFAULT 'percentage',
            discount_value DECIMAL(12,2) NOT NULL DEFAULT 0,
            start_date DATE NULL,
            start_time VARCHAR(20) NULL,
            end_date DATE NULL,
            end_time VARCHAR(20) NULL,
            usage_limit INT NULL,
            per_user_limit INT NULL,
            min_purchase DECIMAL(12,2) NULL,
            min_qty INT NULL,
            restricted_events LONGTEXT NULL,
            excluded_events LONGTEXT NULL,
            restricted_tickets LONGTEXT NULL,
            active TINYINT(1) NOT NULL DEFAULT 1,
            usage_count INT NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY code (code),
            KEY active (active)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        return self::table_exists( $table_name );
    }

    /**
     * Create the etn_coupon_redemptions table if it doesn't exist.
     *
     * @return bool True when the table exists afterwards.
     */
    public static function create_coupon_redemptions_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'etn_coupon_redemptions';

        if ( self::table_exists( $table_name ) ) {
            return true;
        }

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table_name} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            coupon_id BIGINT(20) UNSIGNED NOT NULL,
            coupon_code VARCHAR(100) NOT NULL,
            order_id BIGINT(20) UNSIGNED NOT NULL,
            buyer_email VARCHAR(190) NOT NULL,
            discount_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
            redeemed_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY coupon_id (coupon_id),
            KEY buyer_email (buyer_email),
            KEY order_id (order_id)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        return self::table_exists( $table_name );
    }

    /**
     * Add the `discount_scope` column to etn_coupons.
     *
     * Controls what a discount is computed on: 'total' (tickets + add-ons) or
     * 'tickets' (ticket price only). Existing rows default to 'total', so no
     * coupon changes behaviour.
     *
     * @return void
     */
    public static function add_coupon_discount_scope_column() {
        global $wpdb;

        $table = $wpdb->prefix . 'etn_coupons';

        if ( ! self::table_exists( $table ) ) {
            return;
        }

        $column = $wpdb->get_var( "SHOW COLUMNS FROM {$table} LIKE 'discount_scope'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- SHOW COLUMNS needs a literal; table built from $wpdb->prefix.

        if ( $column ) {
            return;
        }

        $wpdb->query( "ALTER TABLE {$table} ADD COLUMN discount_scope VARCHAR(20) NOT NULL DEFAULT 'total' AFTER restricted_tickets" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- DDL; table built from $wpdb->prefix; no user input.
    }

    /**
     * Whether a table exists.
     *
     * @param string $table Fully-prefixed table name.
     * @return bool
     */
    private static function table_exists( $table ) {
        global $wpdb;

        return $wpdb->get_var( "SHOW TABLES LIKE '{$table}'" ) === $table; // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- SHOW TABLES needs a literal; name built from $wpdb->prefix.
    }
}
