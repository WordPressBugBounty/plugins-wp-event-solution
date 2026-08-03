<?php
namespace Eventin\Support;

defined( 'ABSPATH' ) || exit;

/**
 * A cross-request mutex backed by a single row in the options table.
 *
 * WordPress has no request-level locking, and the usual option guards are not safe
 * for it: add_option() and get_option() both consult the object cache before they
 * touch the database, so two requests that start within the same moment can each
 * read "not set" and both proceed.
 *
 * INSERT IGNORE goes straight to the database and relies on the UNIQUE index on
 * option_name, so exactly one caller comes away with a non-zero rows_affected. That
 * makes it the only option write able to arbitrate between concurrent requests.
 * WordPress core uses the same approach for its own updates (WP_Upgrader::create_lock).
 *
 * This matters because admin-ajax.php fires `admin_init`, so every background AJAX
 * call on a WP admin page re-enters anything hooked there. A slow routine guarded
 * only by an option written at the end will be entered several times over.
 *
 * Locks carry a timestamp and expire, so a request that dies mid-run (fatal, timeout)
 * cannot block the operation forever.
 */
class DbLock {

    /**
     * Seconds after which an unreleased lock is treated as abandoned.
     */
    const DEFAULT_TIMEOUT = 300;

    /**
     * Try to claim a lock. Only one concurrent caller succeeds.
     *
     * @param string $name    Lock name; used verbatim as the option name.
     * @param int    $timeout Seconds after which an unreleased lock is reclaimable.
     *
     * @return bool True if this caller now holds the lock.
     */
    public static function acquire( string $name, int $timeout = self::DEFAULT_TIMEOUT ): bool {
        global $wpdb;

        // Reclaim a lock left behind by a request that died before releasing.
        $held_since = $wpdb->get_var( $wpdb->prepare(
            "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s",
            $name
        ) );

        if ( null !== $held_since && ( time() - (int) $held_since ) > $timeout ) {
            self::release( $name );
        }

        // Atomic: the UNIQUE index on option_name means only one INSERT can succeed.
        $wpdb->query( $wpdb->prepare(
            "INSERT IGNORE INTO {$wpdb->options} ( option_name, option_value, autoload ) VALUES ( %s, %s, 'no' )",
            $name,
            (string) time()
        ) );

        return $wpdb->rows_affected > 0;
    }

    /**
     * Release a lock so the operation can run again later.
     *
     * @param string $name
     */
    public static function release( string $name ): void {
        global $wpdb;

        $wpdb->delete( $wpdb->options, [ 'option_name' => $name ], [ '%s' ] );

        // The row was written behind get_option()'s back, so clear its caches too.
        wp_cache_delete( $name, 'options' );
        wp_cache_delete( 'notoptions', 'options' );
        wp_cache_delete( 'alloptions', 'options' );
    }
}
