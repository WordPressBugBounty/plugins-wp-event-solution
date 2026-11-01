<?php
/**
 * Register Custom Endpoint
 *
 * @package Eventin
 */
namespace Eventin;

/**
 * Custom Endpoint Class
 */
class CustomEndpoint {
    /**
     * Option that stores the endpoint list the saved rewrite rules were built with
     *
     * @var string
     */
    const FLUSHED_OPTION = 'etn_custom_endpoints_flushed';

    /**
     * Register all custom endpoints
     *
     * @return  void
     */
    public static function register() {
        $endpoints = self::get_endpoints();

        foreach ( $endpoints as $endpoint ) {
            add_rewrite_endpoint( $endpoint, EP_ALL );
        }

        // Rebuild the rewrite rules only when the endpoint list changed. Flushing on
        // every request rebuilt the whole rule set per page view, which slowed busy
        // sites and let overlapping requests save different rule sets (random 404s).
        if ( get_option( self::FLUSHED_OPTION ) !== self::get_signature() ) {
            add_action( 'wp_loaded', [ self::class, 'flush' ] );
        }
    }

    /**
     * Flush rewrite rules once and remember the endpoint list they were built with
     *
     * Runs on wp_loaded so every plugin has registered its rules first.
     *
     * @return  void
     */
    public static function flush() {
        flush_rewrite_rules( false );

        update_option( self::FLUSHED_OPTION, self::get_signature(), true );
    }

    /**
     * Get a signature of the current endpoint list
     *
     * @return  string
     */
    public static function get_signature() {
        return md5( implode( ',', self::get_endpoints() ) );
    }

    /**
     * Get all endpoints
     *
     * @return  array
     */
    public static function get_endpoints() {
        /**
         * All endpoints that have to be register
         */
        return [
            'eventin-integration',
            'eventin-purchase'
        ];
    }
};
