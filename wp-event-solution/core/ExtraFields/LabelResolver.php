<?php
/**
 * Extra-field label resolver.
 *
 * @package Eventin
 */

namespace Eventin\ExtraFields;

defined( 'ABSPATH' ) || exit;

/**
 * Maps stored extra-field meta keys back to their human-readable labels.
 *
 * Extra-field values are stored under `etn_attendee_extra_field_{slug}_{id}`,
 * where `{slug}` is built by the frontend (extra-form-fields.jsx) with an
 * ASCII-only regex (`/[^\w\s]/`, no `u` flag). Any label written in a
 * non-Latin script — Arabic, Bengali, Japanese — slugs to an empty string, so
 * the key degrades to `_2` and every display surface that reconstructed the
 * label from the key rendered "2:" or nothing at all.
 *
 * The label therefore cannot be recovered from the key. It has to come from
 * the field schema (event `attendee_extra_fields` meta, or the global
 * settings fallback), matched on the trailing id. This class builds that
 * key => label map so both stored and freshly-saved rows resolve.
 */
class LabelResolver {

    /**
     * Convert a field label to the slug the frontend uses in the meta key.
     *
     * Mirrors extra-form-fields.jsx: lowercase, drop everything outside
     * [A-Za-z0-9_ ], collapse whitespace to underscores, trim underscores.
     * Deliberately ASCII-only — it has to reproduce what was actually stored,
     * not what a correct slugger would produce.
     *
     * @param   string  $label  Field label.
     *
     * @return  string
     */
    public static function slug( $label ) {
        $slug = mb_strtolower( trim( (string) $label ) );
        $slug = preg_replace( '/\p{Z}+/u', ' ', $slug );
        $slug = preg_replace( '/[^a-z0-9 _]/', '', $slug );
        $slug = preg_replace( '/[ _]+/', '_', $slug );

        return trim( $slug, '_' );
    }

    /**
     * Build a meta-key => label map from an extra-fields schema.
     *
     * Registered in priority passes so an id-based key always wins over an
     * index-based one. That matters because every non-Latin label slugs to
     * '', leaving the id as the only thing telling `_1` and `_2` apart — a
     * single-pass build could hand row 2's value row 1's label.
     *
     * Key spellings covered, in order:
     *   1. `{slug}_{id}`      — current frontend convention.
     *   2. `{slug}_{index}`   — schema rows with no id (`item?.id || index`).
     *   3. `{slug}_{index+1}` — 1-based variant used by get_extra_field_files().
     *   4. `{slug}`           — legacy rows saved before the id suffix existed.
     *
     * @param   array  $schema  Extra-fields schema rows.
     *
     * @return  array<string, string>
     */
    public static function build_map( $schema ) {
        if ( ! is_array( $schema ) || ! $schema ) {
            return [];
        }

        $rows = [];

        foreach ( $schema as $index => $row ) {
            if ( ! is_array( $row ) ) {
                continue;
            }

            $label = isset( $row['label'] ) ? (string) $row['label'] : '';

            if ( '' === trim( $label ) ) {
                continue;
            }

            $rows[] = [
                'label' => $label,
                'slug'  => self::slug( $label ),
                'id'    => ! empty( $row['id'] ) ? $row['id'] : null,
                'index' => $index,
            ];
        }

        $map = [];

        $register = function ( $key, $label ) use ( &$map ) {
            if ( '' !== $key && ! isset( $map[ $key ] ) ) {
                $map[ $key ] = $label;
            }
        };

        foreach ( $rows as $row ) {
            if ( null !== $row['id'] ) {
                $register( $row['slug'] . '_' . $row['id'], $row['label'] );
            }
        }

        foreach ( $rows as $row ) {
            $register( $row['slug'] . '_' . $row['index'], $row['label'] );
        }

        foreach ( $rows as $row ) {
            $register( $row['slug'] . '_' . ( $row['index'] + 1 ), $row['label'] );
        }

        foreach ( $rows as $row ) {
            $register( $row['slug'], $row['label'] );
        }

        return $map;
    }

    /**
     * Humanize a meta key when the schema has no matching row.
     *
     * Drops the trailing `_{id}` so a deleted field still reads "Birth Day"
     * rather than "Birth Day 3", and falls back to the raw key when stripping
     * leaves nothing behind.
     *
     * @param   string  $key  Extra-field key (no meta prefix).
     *
     * @return  string
     */
    public static function humanize( $key ) {
        $key     = (string) $key;
        $trimmed = preg_replace( '/_\d+$/', '', $key );

        if ( '' === trim( $trimmed, '_ ' ) ) {
            $trimmed = $key;
        }

        return ucwords( trim( str_replace( '_', ' ', $trimmed ) ) );
    }

    /**
     * Resolve a single key to its label.
     *
     * @param   string  $key  Extra-field key (no meta prefix).
     * @param   array   $map  Map from build_map().
     *
     * @return  string
     */
    public static function label( $key, $map ) {
        if ( is_array( $map ) && isset( $map[ $key ] ) ) {
            return $map[ $key ];
        }

        return self::humanize( $key );
    }
}
