<?php
namespace Eventin\I18n;

defined( 'ABSPATH' ) || exit;

/**
 * Keeps translations made for the old "eventin" text domain working.
 *
 * In 4.1.28 the text domain changed from "eventin" to "wp-event-solution" so it
 * matches the WordPress.org plugin slug. Sites that translated the plugin
 * themselves (Loco Translate, Poedit) have files named after the old domain,
 * e.g. wp-content/languages/plugins/eventin-de_DE.mo. WordPress now only looks
 * for wp-event-solution-de_DE.mo, so those translations would disappear.
 *
 * This class loads the old files into the new domain. When a string exists in
 * more than one file, this order decides which one wins (highest first):
 *
 * 1. languages/loco/plugins/wp-event-solution-*  Customer edits made in Loco after the update.
 * 2. languages/loco/plugins/eventin-*            Customer's old Loco file.
 * 3. languages/plugins/eventin-*                 Customer's old file in the system folder.
 * 4. languages/plugins/wp-event-solution-*       WordPress.org language pack.
 *
 * So nothing automatic (like a language pack update) replaces the customer's
 * old wording. Only the customer's own new Loco edits do. The same order is
 * used for .mo files, script JSON files and lazy-loaded chunk JSON files.
 */
class LegacyTextDomain {
    const DOMAIN     = 'wp-event-solution';
    const OLD_DOMAIN = 'eventin';

    /**
     * Locales whose old files are already loaded in this request.
     *
     * @var array
     */
    private static $loaded = [];

    /**
     * Register hooks
     *
     * @return  void
     */
    public static function init() {
        // Runs right before WordPress loads any file for the new domain.
        add_action( 'load_textdomain', [ __CLASS__, 'before_load_textdomain' ], 10, 2 );

        // For languages that have no WordPress.org pack, nothing above fires.
        add_action( 'init', [ __CLASS__, 'load_without_language_pack' ], 1 );

        // switch_to_locale() unloads the domain, so the old files must load again.
        add_action( 'change_locale', [ __CLASS__, 'on_change_locale' ] );

        // Priority 12: after Loco Translate (11) has seen the original path, so
        // Loco can still merge the customer's new Loco file on top.
        add_filter( 'load_script_translation_file', [ __CLASS__, 'script_translation_file' ], 12, 3 );
        // Priority 10: before Loco (11), so Loco's new file stays the top layer.
        add_filter( 'load_script_translations', [ __CLASS__, 'script_translations' ], 10, 4 );

        // Older Eventin Pro and add-ons still call __( '...', 'eventin' ).
        add_filter( 'gettext_' . self::OLD_DOMAIN, [ __CLASS__, 'old_domain_gettext' ], 10, 2 );
        add_filter( 'gettext_with_context_' . self::OLD_DOMAIN, [ __CLASS__, 'old_domain_gettext_with_context' ], 10, 3 );
        add_filter( 'ngettext_' . self::OLD_DOMAIN, [ __CLASS__, 'old_domain_ngettext' ], 10, 4 );
        add_filter( 'ngettext_with_context_' . self::OLD_DOMAIN, [ __CLASS__, 'old_domain_ngettext_with_context' ], 10, 5 );

        WpmlTextDomainMigration::init();
    }

    /**
     * Translate an old-domain string with the new domain's files.
     *
     * Only used when the old domain has no translation of its own. Before
     * after_setup_theme it does nothing, because loading translations that
     * early makes WordPress show a "loaded too early" notice.
     *
     * @param   string  $translation  Translation from the old domain.
     * @param   string  $text         Original text.
     *
     * @return  string
     */
    public static function old_domain_gettext( $translation, $text ) {
        if ( $translation !== $text || ! did_action( 'after_setup_theme' ) ) {
            return $translation;
        }

        return get_translations_for_domain( self::DOMAIN )->translate( $text );
    }

    /**
     * Same as old_domain_gettext(), for _x()
     *
     * @param   string  $translation  Translation from the old domain.
     * @param   string  $text         Original text.
     * @param   string  $context      Context.
     *
     * @return  string
     */
    public static function old_domain_gettext_with_context( $translation, $text, $context ) {
        if ( $translation !== $text || ! did_action( 'after_setup_theme' ) ) {
            return $translation;
        }

        return get_translations_for_domain( self::DOMAIN )->translate( $text, $context );
    }

    /**
     * Same as old_domain_gettext(), for _n()
     *
     * @param   string  $translation  Translation from the old domain.
     * @param   string  $single       Singular text.
     * @param   string  $plural       Plural text.
     * @param   int     $number       Number.
     *
     * @return  string
     */
    public static function old_domain_ngettext( $translation, $single, $plural, $number ) {
        if ( $translation !== ( 1 === (int) $number ? $single : $plural ) || ! did_action( 'after_setup_theme' ) ) {
            return $translation;
        }

        return get_translations_for_domain( self::DOMAIN )->translate_plural( $single, $plural, $number );
    }

    /**
     * Same as old_domain_gettext(), for _nx()
     *
     * @param   string  $translation  Translation from the old domain.
     * @param   string  $single       Singular text.
     * @param   string  $plural       Plural text.
     * @param   int     $number       Number.
     * @param   string  $context      Context.
     *
     * @return  string
     */
    public static function old_domain_ngettext_with_context( $translation, $single, $plural, $number, $context ) {
        if ( $translation !== ( 1 === (int) $number ? $single : $plural ) || ! did_action( 'after_setup_theme' ) ) {
            return $translation;
        }

        return get_translations_for_domain( self::DOMAIN )->translate_plural( $single, $plural, $number, $context );
    }

    /**
     * Load the old files just before the first new file of a locale.
     *
     * @param   string  $domain  Text domain being loaded.
     * @param   string  $mofile  File being loaded.
     *
     * @return  void
     */
    public static function before_load_textdomain( $domain, $mofile ) {
        if ( self::DOMAIN !== $domain ) {
            return;
        }

        $locale = determine_locale();

        if ( preg_match( '/^' . preg_quote( self::DOMAIN, '/' ) . '-(.+?)\.(mo|l10n\.php)$/', basename( (string) $mofile ), $matches ) ) {
            $locale = $matches[1];
        }

        self::load_old_files( $locale );
    }

    /**
     * Load the old files when the locale has no new file at all.
     *
     * If old files were loaded here, the new files are loaded after them.
     * WordPress will not load them by itself any more, because the domain
     * already has translations. load_plugin_textdomain() cannot do it either:
     * since WordPress 6.7 it only saves the folder for just-in-time loading.
     *
     * @return  void
     */
    public static function load_without_language_pack() {
        $locale = determine_locale();

        if ( ! self::load_old_files( $locale ) ) {
            return;
        }

        $name = self::DOMAIN . '-' . $locale . '.mo';

        // A missing file is skipped by load_textdomain().
        load_textdomain( self::DOMAIN, WP_LANG_DIR . '/plugins/' . $name, $locale );
        load_textdomain( self::DOMAIN, \Wpeventin::plugin_dir() . 'languages/' . $name, $locale );
    }

    /**
     * Load the old files again after a locale switch
     *
     * @return  void
     */
    public static function on_change_locale() {
        self::$loaded = [];

        if ( did_action( 'init' ) ) {
            self::load_without_language_pack();
        }
    }

    /**
     * Load every old file that exists for a locale, once per request.
     *
     * @param   string  $locale  Locale, e.g. de_DE.
     *
     * @return  bool  True if at least one old file was loaded now.
     */
    private static function load_old_files( $locale ) {
        if ( isset( self::$loaded[ $locale ] ) ) {
            return false;
        }

        self::$loaded[ $locale ] = true;

        $old_files = self::old_mo_files( $locale );

        if ( ! $old_files ) {
            return false;
        }

        // The customer's new Loco file must beat the old files, so it loads first.
        $new_custom = self::loco_dir() . '/plugins/' . self::DOMAIN . '-' . $locale . '.mo';

        if ( self::mo_readable( $new_custom ) ) {
            load_textdomain( self::DOMAIN, $new_custom, $locale );
        }

        $loaded = false;

        foreach ( $old_files as $file ) {
            if ( load_textdomain( self::DOMAIN, $file, $locale ) ) {
                $loaded = true;
            }
        }

        return $loaded;
    }

    /**
     * Old .mo files that exist for a locale, highest priority first.
     *
     * load_textdomain() reads a matching .l10n.php file instead when one
     * exists, so a locale that has only the .l10n.php file is kept too.
     *
     * @param   string  $locale  Locale, e.g. de_DE.
     *
     * @return  array
     */
    private static function old_mo_files( $locale ) {
        $name  = self::OLD_DOMAIN . '-' . $locale . '.mo';
        $files = [
            self::loco_dir() . '/plugins/' . $name, // Loco Translate "custom" location.
            WP_LANG_DIR . '/plugins/' . $name,
            \Wpeventin::plugin_dir() . 'languages/' . $name,
        ];

        return array_values( array_filter( $files, [ __CLASS__, 'mo_readable' ] ) );
    }

    /**
     * Whether a .mo file, or its .l10n.php twin, can be read
     *
     * @param   string  $file  .mo file path.
     *
     * @return  bool
     */
    private static function mo_readable( $file ) {
        return is_readable( $file ) || is_readable( substr( $file, 0, -3 ) . '.l10n.php' );
    }

    /**
     * Loco Translate "custom" folder
     *
     * @return  string  Path without a trailing slash.
     */
    private static function loco_dir() {
        return untrailingslashit( defined( 'LOCO_LANG_DIR' ) ? LOCO_LANG_DIR : WP_LANG_DIR . '/loco' );
    }


    /**
     * Use an old JSON file when the new system file is missing.
     *
     * WordPress only calls load_script_translations() for a file it can read.
     * Without this, a site with only old JSON files gets no script strings.
     * The other old files are merged in script_translations().
     *
     * @param   string|false  $file    Translation file path.
     * @param   string        $handle  Script handle.
     * @param   string        $domain  Text domain.
     *
     * @return  string|false
     */
    public static function script_translation_file( $file, $handle, $domain ) {
        if ( self::DOMAIN !== $domain || ! is_string( $file ) || is_readable( $file ) ) {
            return $file;
        }

        $parts = self::json_parts( $file );

        if ( ! $parts || self::DOMAIN !== $parts['domain'] ) {
            return $file;
        }

        $old_files = self::old_json_files( $parts['locale'], $parts['hash'] );

        return $old_files ? $old_files[0] : $file;
    }

    /**
     * Put the old JSON strings on top of the file WordPress read.
     *
     * Loco Translate (priority 11) later puts the customer's new Loco file on
     * top of this result, which gives the order described on the class.
     *
     * @param   string|false  $translations  JSON content.
     * @param   string|false  $file          Translation file path.
     * @param   string        $handle        Script handle.
     * @param   string        $domain        Text domain.
     *
     * @return  string|false
     */
    public static function script_translations( $translations, $file, $handle, $domain ) {
        if ( self::DOMAIN !== $domain || ! $translations || ! is_string( $file ) ) {
            return $translations;
        }

        $parts = self::json_parts( $file );

        if ( ! $parts ) {
            return $translations;
        }

        $base = json_decode( $translations, true );

        if ( ! is_array( $base ) || empty( $base['locale_data'] ) || ! is_array( $base['locale_data'] ) ) {
            return $translations;
        }

        $read  = realpath( $file );
        $stack = self::json_stack( $parts['locale'], $parts['hash'] );
        $paths = array_map( 'realpath', $stack );

        // Nothing to merge, and WordPress can already read the domain key.
        if ( count( $stack ) < 2 && ! isset( $base['locale_data'][ self::OLD_DOMAIN ] ) ) {
            return $translations;
        }

        $base_messages = self::json_messages( $base );
        $messages      = [ '' => isset( $base_messages[''] ) ? $base_messages[''] : [] ];

        // A file from somewhere else (e.g. the plugin folder) is the lowest layer.
        if ( ! in_array( $read, $paths, true ) ) {
            $messages = array_merge( $messages, self::without_empty( $base_messages ) );
        }

        // Lowest priority first, so each later file wins.
        foreach ( $stack as $index => $layer ) {
            if ( $paths[ $index ] === $read ) {
                $data = $base;
            } else {
                $data = json_decode( (string) file_get_contents( $layer ), true );
            }

            if ( ! is_array( $data ) || empty( $data['locale_data'] ) || ! is_array( $data['locale_data'] ) ) {
                continue;
            }

            $messages = array_merge( $messages, self::without_empty( self::json_messages( $data ) ) );
        }

        // WordPress reads locale_data.<domain> first, then locale_data.messages.
        $base['locale_data'] = [ 'messages' => $messages ];

        return wp_json_encode( $base );
    }

    /**
     * Places the lazy-chunk loader (tools/i18n-loader.js) should read from.
     *
     * Each entry is a path under the languages folder, without the
     * "-<locale>-<md5>.json" end. Lowest priority comes first, so the loader
     * can merge them in this order. Folders with no file for this locale are
     * left out, so the browser does not ask for files that cannot exist.
     *
     * @param   string  $locale  Locale, e.g. de_DE.
     *
     * @return  array   Domain => list of paths, or empty to use the default place.
     */
    public static function chunk_sources( $locale ) {
        $lang_dir = trailingslashit( WP_LANG_DIR );
        $sources  = [];

        foreach ( self::chunk_places() as $place ) {
            if ( glob( $place . '-' . $locale . '-*.json' ) ) {
                $sources[] = substr( $place, strlen( $lang_dir ) );
            }
        }

        return $sources ? [ self::DOMAIN => $sources ] : [];
    }

    /**
     * Chunk JSON files found by chunk name, for the lazy-chunk loader.
     *
     * A chunk file name has a hash that changes on every build, e.g.
     * build/chunks/js/9140.4adb3329.js becomes build/chunks/js/9140.7a1c9e14.js.
     * The JSON file name is the md5 of that path, so after an update the
     * customer's old chunk JSON files are never found by name. Each JSON file
     * stores the script path in "source", so this map finds them by the part
     * that stays the same ("build/chunks/js/9140").
     *
     * Order for one chunk: the places in chunk_places() order, and inside one
     * place older builds first. So a later file wins, as in chunk_sources().
     *
     * @param   string  $locale  Locale, e.g. de_DE.
     *
     * @return  array   Domain => [ chunk name => list of paths ], or empty.
     */
    public static function chunk_files( $locale ) {
        if ( 'en_US' === $locale ) {
            return [];
        }

        $lang_dir = trailingslashit( WP_LANG_DIR );
        $files    = [];

        foreach ( self::chunk_places() as $place ) {
            $found = glob( $place . '-' . $locale . '-*.json' );
            $files = array_merge( $files, $found ? $found : [] );
        }

        if ( ! $files ) {
            return [];
        }

        // Reading every JSON file on each page is slow, so keep the map until a file changes.
        $signature = \Wpeventin::version();

        foreach ( $files as $file ) {
            $signature .= '|' . $file . ':' . filemtime( $file );
        }

        $signature = md5( $signature );
        $cache_key = 'etn_i18n_chunk_files_' . $locale;
        $cached    = get_transient( $cache_key );

        if ( is_array( $cached ) && isset( $cached['signature'], $cached['map'] ) && $cached['signature'] === $signature ) {
            return $cached['map'];
        }

        $plugin_dir = \Wpeventin::plugin_dir();
        $map        = [];

        foreach ( self::chunk_places() as $place ) {
            $found = glob( $place . '-' . $locale . '-*.json' );
            $rows  = [];

            foreach ( $found ? $found : [] as $file ) {
                $data   = json_decode( (string) file_get_contents( $file ), true );
                $source = is_array( $data ) && isset( $data['source'] ) ? (string) $data['source'] : '';

                if ( ! preg_match( '#^(build/chunks/js/[^/]+?)\.[a-f0-9]{6,}\.js$#', $source, $matches ) ) {
                    continue;
                }

                $rows[] = [
                    'chunk'   => $matches[1],
                    'path'    => substr( $file, strlen( $lang_dir ) ),
                    // A file made for the build that is installed now beats an older one.
                    'current' => is_readable( $plugin_dir . $source ) ? 1 : 0,
                    'time'    => filemtime( $file ),
                ];
            }

            usort( $rows, function ( $a, $b ) {
                return [ $a['current'], $a['time'] ] <=> [ $b['current'], $b['time'] ];
            } );

            foreach ( $rows as $row ) {
                $map[ $row['chunk'] ][] = $row['path'];
            }
        }

        $map = $map ? [ self::DOMAIN => $map ] : [];

        set_transient( $cache_key, [ 'signature' => $signature, 'map' => $map ], DAY_IN_SECONDS );

        return $map;
    }

    /**
     * Folders + file name start for chunk JSON files, lowest priority first.
     *
     * Only places inside the languages folder, because the browser downloads
     * these files by URL.
     *
     * @return  array  e.g. /.../languages/plugins/eventin
     */
    private static function chunk_places() {
        $lang_dir = trailingslashit( WP_LANG_DIR );
        $loco_dir = trailingslashit( self::loco_dir() );
        $places   = [
            $lang_dir . 'plugins/' . self::DOMAIN,
            $lang_dir . 'plugins/' . self::OLD_DOMAIN,
            $loco_dir . 'plugins/' . self::OLD_DOMAIN,
            $loco_dir . 'plugins/' . self::DOMAIN,
        ];

        return array_values( array_filter( $places, function ( $place ) use ( $lang_dir ) {
            return 0 === strpos( $place, $lang_dir );
        } ) );
    }

    /**
     * Read locale and hash from a script JSON file name.
     *
     * @param   string  $file  e.g. .../wp-event-solution-de_DE-<md5>.json
     *
     * @return  array|false  domain, locale and hash, or false.
     */
    private static function json_parts( $file ) {
        $pattern = '/^(' . preg_quote( self::DOMAIN, '/' ) . '|' . preg_quote( self::OLD_DOMAIN, '/' ) . ')-(.+)-([a-f0-9]{32})\.json$/';

        if ( ! preg_match( $pattern, basename( $file ), $matches ) ) {
            return false;
        }

        return [
            'domain' => $matches[1],
            'locale' => $matches[2],
            'hash'   => $matches[3],
        ];
    }

    /**
     * Old JSON files for one script, highest priority first
     *
     * @param   string  $locale  Locale, e.g. de_DE.
     * @param   string  $hash    md5 of the script path.
     *
     * @return  array
     */
    private static function old_json_files( $locale, $hash ) {
        $name  = self::OLD_DOMAIN . '-' . $locale . '-' . $hash . '.json';
        $files = [
            self::loco_dir() . '/plugins/' . $name,
            WP_LANG_DIR . '/plugins/' . $name,
        ];

        return array_values( array_filter( $files, 'is_readable' ) );
    }

    /**
     * Every JSON file for one script, lowest priority first
     *
     * @param   string  $locale  Locale, e.g. de_DE.
     * @param   string  $hash    md5 of the script path.
     *
     * @return  array
     */
    private static function json_stack( $locale, $hash ) {
        $end   = '-' . $locale . '-' . $hash . '.json';
        $files = array_merge(
            [ WP_LANG_DIR . '/plugins/' . self::DOMAIN . $end ],
            array_reverse( self::old_json_files( $locale, $hash ) ),
            [ self::loco_dir() . '/plugins/' . self::DOMAIN . $end ]
        );

        return array_values( array_filter( $files, 'is_readable' ) );
    }

    /**
     * The strings inside a JED JSON file.
     *
     * Tools save them under "messages", the domain, or the old domain.
     *
     * @param   array  $data  Decoded JSON.
     *
     * @return  array
     */
    private static function json_messages( $data ) {
        foreach ( [ self::DOMAIN, 'messages', self::OLD_DOMAIN ] as $key ) {
            if ( isset( $data['locale_data'][ $key ] ) && is_array( $data['locale_data'][ $key ] ) ) {
                return $data['locale_data'][ $key ];
            }
        }

        $first = reset( $data['locale_data'] );

        return is_array( $first ) ? $first : [];
    }

    /**
     * Drop the header and strings with no translation.
     *
     * An empty string in a higher file must not hide a real translation in a
     * lower file.
     *
     * @param   array  $messages  JED messages.
     *
     * @return  array
     */
    private static function without_empty( $messages ) {
        unset( $messages[''] );

        return array_filter( $messages, function ( $value ) {
            return is_array( $value ) && '' !== implode( '', array_map( 'strval', $value ) );
        } );
    }
}
