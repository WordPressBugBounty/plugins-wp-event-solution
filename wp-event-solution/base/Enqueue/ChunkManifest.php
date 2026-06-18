<?php
/**
 * Webpack chunk manifest reader.
 *
 * The build emits build/chunk-manifest.json mapping each webpack entry to
 * the ordered list of .js files it needs (shared vendor chunks first, then
 * the entry itself). This class reads that manifest and exposes the vendor
 * chunks as standalone WordPress script handles, so any entry can declare
 * `vendor_deps_for( $entry )` as part of its `deps` array and WordPress
 * will load the vendor chunks before the entry — required for splitChunks
 * to work without breaking the runtime.
 *
 * @package Eventin
 */

namespace Eventin\Enqueue;

/**
 * Reads build/chunk-manifest.json and registers vendor chunks as
 * dependencies for entry scripts.
 */
class ChunkManifest {

    /**
     * In-memory cache of the parsed manifest (entry name → list of file
     * paths relative to build/).
     *
     * @var array<string, string[]>|null
     */
    private static $manifest = null;

    /**
     * Load and cache the manifest. Returns an empty array if the file is
     * missing or malformed (e.g. older build or partial deploy) — callers
     * fall back to enqueuing only the entry script in that case, which
     * preserves pre-splitChunks behavior.
     */
    private static function load() {
        if ( self::$manifest !== null ) {
            return self::$manifest;
        }
        $path = \Wpeventin::plugin_dir() . 'build/chunk-manifest.json';
        if ( ! file_exists( $path ) ) {
            self::$manifest = [];
            return self::$manifest;
        }
        $raw = file_get_contents( $path );
        $decoded = json_decode( $raw, true );
        self::$manifest = is_array( $decoded ) ? $decoded : [];
        return self::$manifest;
    }

    /**
     * Returns the list of vendor script handles that the given entry
     * depends on. Each vendor chunk is registered with WordPress on first
     * encounter so it can be enqueued like any other handle.
     *
     * Pass the result into the `deps` array of your entry's
     * `wp_register_script` / `wp_enqueue_script` call:
     *
     *     'deps' => array_merge(
     *         [ 'wp-element', 'wp-i18n' ],
     *         ChunkManifest::vendor_deps_for( 'dashboard' )
     *     )
     *
     * @param string $entry Webpack entry name (e.g. 'dashboard').
     * @return string[] List of WP script handles, in load order.
     */
    public static function vendor_deps_for( $entry ) {
        $manifest = self::load();
        if ( empty( $manifest[ $entry ] ) ) {
            return [];
        }

        $deps     = [];
        $fallback = \Wpeventin::version();

        foreach ( $manifest[ $entry ] as $file ) {
            // Match files like "js/vendor-antd.js" → handle "etn-vendor-antd".
            if ( ! preg_match( '#^js/(vendor-[A-Za-z0-9_-]+)\.js$#', $file, $matches ) ) {
                continue;
            }
            $name   = $matches[1];
            $handle = 'etn-' . $name;

            // CACHE-BUST: version each vendor chunk by its file CONTENT, not the
            // static plugin version. Vendor chunk filenames are stable
            // (js/vendor-*.js, no content hash in the name), so a chunk whose
            // contents change between builds at the SAME plugin version would keep
            // the same ?ver= — and browsers/CDNs/page-caches then serve the stale
            // copy alongside freshly-built sibling chunks. That mixes two builds and
            // breaks webpack's cross-chunk module-id resolution (e.g.
            // "n.dn is not a function" thrown from vendor-antd when it requires a
            // stale vendor-rc). Hashing the file guarantees ?ver= changes whenever
            // the chunk does. crc32b is content-exact and fast (sub-ms per chunk).
            $file_path = \Wpeventin::plugin_dir() . 'build/' . $file;
            $file_ver  = file_exists( $file_path ) ? hash_file( 'crc32b', $file_path ) : $fallback;

            // Always (re-)register: WP can swap the active WP_Scripts instance
            // mid-request (e.g. _wp_get_iframed_editor_assets()), and a static
            // cache here would skip registration in the new instance, leaving
            // entries with unmet deps that WP silently drops at print time.
            if ( ! wp_script_is( $handle, 'registered' ) ) {
                wp_register_script(
                    $handle,
                    \Wpeventin::plugin_url( 'build/' . $file ),
                    [],
                    $file_ver,
                    false // Vendor chunks must load in <head> before the entry runs in footer.
                );
            }

            $deps[] = $handle;
        }

        return $deps;
    }
}
