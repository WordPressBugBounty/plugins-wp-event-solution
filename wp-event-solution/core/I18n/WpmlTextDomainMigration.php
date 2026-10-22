<?php
namespace Eventin\I18n;

defined( 'ABSPATH' ) || exit;

use Eventin\Support\DbLock;

/**
 * Moves WPML String Translation strings from the old "eventin" domain.
 *
 * WPML keeps string translations in its own tables (icl_strings and
 * icl_string_translations), grouped by text domain in the "context" column.
 * After the text domain changed to "wp-event-solution", WPML looks up the new
 * domain only, so every translation saved under "eventin" would stop showing.
 *
 * This runs once per site, the first time WPML String Translation is active
 * after the update. Each string is moved to the new domain the same way WPML
 * does it itself (WPML_String_Scanner::fix_string_context()): change the
 * context and recompute domain_name_context_md5.
 *
 * If WPML already registered the same string under the new domain, the old
 * row stays and its finished translations are copied to the new row.
 */
class WpmlTextDomainMigration {
    const DONE_OPTION = 'etn_wpml_text_domain_migrated';
    const LOCK        = 'etn_wpml_text_domain_lock';

    /**
     * WPML status for a finished translation (ICL_TM_COMPLETE).
     */
    const STATUS_COMPLETE = 10;

    /**
     * Register hooks
     *
     * @return  void
     */
    public static function init() {
        // Late on init, so WPML String Translation has finished loading.
        add_action( 'init', [ __CLASS__, 'maybe_migrate' ], 99 );
    }

    /**
     * Run the migration once, when WPML String Translation is active.
     *
     * @return  void
     */
    public static function maybe_migrate() {
        if ( ! defined( 'WPML_ST_VERSION' ) || get_option( self::DONE_OPTION ) ) {
            return;
        }

        if ( ! DbLock::acquire( self::LOCK, 300 ) ) {
            return;
        }

        try {
            $result = self::migrate();

            update_option( self::DONE_OPTION, array_merge( $result, [ 'time' => time() ] ), false );
        } finally {
            DbLock::release( self::LOCK );
        }
    }

    /**
     * Move the strings and their translations to the new domain.
     *
     * @return  array  Counts: moved strings, copied translations.
     */
    public static function migrate() {
        global $wpdb;

        $strings      = $wpdb->prefix . 'icl_strings';
        $translations = $wpdb->prefix . 'icl_string_translations';
        $result       = [ 'moved' => 0, 'copied' => 0 ];

        if ( $strings !== $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $strings ) ) ) {
            return $result;
        }

        $old_rows = $wpdb->get_results( $wpdb->prepare(
            "SELECT id, name, gettext_context FROM {$strings} WHERE context = %s",
            LegacyTextDomain::OLD_DOMAIN
        ) );

        foreach ( $old_rows as $row ) {
            $md5 = md5( LegacyTextDomain::DOMAIN . $row->name . $row->gettext_context );

            $new_id = $wpdb->get_var( $wpdb->prepare(
                "SELECT id FROM {$strings} WHERE domain_name_context_md5 = %s",
                $md5
            ) );

            if ( ! $new_id ) {
                $wpdb->update(
                    $strings,
                    [ 'context' => LegacyTextDomain::DOMAIN, 'domain_name_context_md5' => $md5 ],
                    [ 'id' => $row->id ],
                    [ '%s', '%s' ],
                    [ '%d' ]
                );
                $result['moved']++;
                continue;
            }

            $result['copied'] += self::copy_translations( (int) $row->id, (int) $new_id, $translations );
        }

        if ( $result['moved'] || $result['copied'] ) {
            // Same actions WPML fires itself, so it rebuilds its .mo files.
            do_action( 'wpml_st_string_updated' );
            do_action( 'wpml_st_refresh_domain', LegacyTextDomain::DOMAIN );
        }

        return $result;
    }

    /**
     * Copy finished translations from the old string to the new one.
     *
     * A language is copied only when the new string has no finished
     * translation for it, so nothing translated under the new domain is
     * overwritten.
     *
     * @param   int     $old_id        Old string id.
     * @param   int     $new_id        New string id.
     * @param   string  $translations  Translations table name.
     *
     * @return  int  Number of languages copied.
     */
    private static function copy_translations( $old_id, $new_id, $translations ) {
        global $wpdb;

        $old = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$translations} WHERE string_id = %d AND status = %d",
            $old_id,
            self::STATUS_COMPLETE
        ) );

        $copied = 0;

        foreach ( $old as $translation ) {
            $current = $wpdb->get_row( $wpdb->prepare(
                "SELECT id, status FROM {$translations} WHERE string_id = %d AND language = %s",
                $new_id,
                $translation->language
            ) );

            if ( $current && self::STATUS_COMPLETE === (int) $current->status ) {
                continue;
            }

            $data = [
                'string_id'           => $new_id,
                'language'            => $translation->language,
                'status'              => $translation->status,
                'value'               => $translation->value,
                'mo_string'           => $translation->mo_string,
                'translator_id'       => $translation->translator_id,
                'translation_service' => $translation->translation_service,
                'batch_id'            => $translation->batch_id,
                'translation_date'    => $translation->translation_date,
            ];

            if ( $current ) {
                $wpdb->update( $translations, $data, [ 'id' => $current->id ] );
            } else {
                $wpdb->insert( $translations, $data );
            }

            $copied++;
        }

        return $copied;
    }
}
