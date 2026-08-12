<?php
namespace Eventin;

/**
 * Settings class
 */
class Settings {
    /**
     * Store option name
     *
     * @var string
     */
    protected static $option_name = 'etn_event_options';

    /**
     * Get settings
     *
     * @param   string  $key
     *
     * @return  mixed
     */
    public static function get( $key = '' ) {
        $settings = get_option( self::$option_name, [] );

        // Harden against a corrupted option value. If the row exists but holds a
        // non-array (e.g. an empty string written by an external process), the
        // default [] above is ignored by get_option(), and the offset assignment
        // in update() would fatal with "Cannot access offset of type string on
        // string", white-screening the entire site on every boot.
        if ( ! is_array( $settings ) ) {
            $settings = [];
        }

        if ( ! $key ) {
            return $settings;
        }

        $value = '';

        if ( ! empty( $settings[$key] ) ) {
            $value = $settings[$key];
        }

        return $value;
    }

    /**
     * Update settings
     *
     * @param   array  $options
     *
     * @return  void
     */
    public static function update( $options = [] ) {
        $settings = self::get();

        $options = self::sanitize_settings( $options );

        foreach ( $options as $name => $value ) {
            $settings[$name] = $value;
        }

        return update_option( self::$option_name, $settings );
    }

    /**
     * Color keys that must validate as a CSS hex color on write.
     *
     * Why: sanitize_text_field strips tags but accepts arbitrary content,
     * which allowed pre-4.1.x installs to store payloads like
     * `</style><script src=…>` in color fields. Whitelisting these keys to
     * sanitize_hex_color blocks the save-side vector reported externally.
     */
    protected static $hex_color_keys = [
        'etn_primary_color',
        'etn_secondary_color',
    ];

    /**
     * Setting keys whose value is used to build a template include path.
     *
     * Why: sanitize_text_field preserves `../`, and these values are
     * concatenated into include_once() when rendering a single speaker/event.
     * Storing a traversal sequence here, combined with any local file-write
     * primitive, is local file inclusion. Confine them to a bare slug on write;
     * the read side confines them again (see
     * Etn\Utils\Helper::sanitize_template_slug()).
     *
     * Every key here legitimately holds EITHER a static template slug
     * ('event-one', 'speaker-two-lite', 'style-1', and the Pro-only
     * 'speaker-two' / 'speaker-three' / 'event-two' / 'event-three') OR the
     * numeric post id of a custom `etn-template` post. The `[A-Za-z0-9_-]` rule
     * accepts both, so no legitimate value — free or Pro — is rejected.
     */
    protected static $template_slug_keys = [
        'speaker_template',
        'event_template',
        // Concatenated into the ticket markup include in
        // Etn\Core\Attendee\TicketTemplate::ticket_markup(). It is not
        // currently escapable there (the "ticket-markup-" prefix means a bare
        // `..` component cannot form), but it is the same class of value and
        // the same class of sink, so it is confined here too.
        'attendee_ticket_style',
        // The remaining two global-default targets written by
        // TemplateController::get_settings_key_for(). Neither reaches an
        // include path in the free plugin today; they are listed so every key
        // that holds a template identity is confined by the same rule.
        'event_layout',
        'certificate_template',
    ];

    /**
     * Recursively sanitize settings, preserving safe HTML for email body fields.
     *
     * Email body fields (keyed `body`) are edited via a rich-text editor and
     * must retain formatting tags. Color fields listed in $hex_color_keys are
     * validated against sanitize_hex_color and reset to empty on failure.
     * All other values are hardened with sanitize_text_field.
     */
    protected static function sanitize_settings( $data ) {
        if ( ! is_array( $data ) ) {
            return sanitize_text_field( $data );
        }

        $sanitized = [];

        foreach ( $data as $key => $value ) {
            if ( is_array( $value ) ) {
                $sanitized[$key] = self::sanitize_settings( $value );
            } elseif ( 'body' === $key ) {
                $sanitized[$key] = wp_kses_post( $value );
            } elseif ( in_array( $key, self::$hex_color_keys, true ) ) {
                $hex             = sanitize_hex_color( (string) $value );
                $sanitized[$key] = null === $hex ? '' : $hex;
            } elseif ( in_array( $key, self::$template_slug_keys, true ) ) {
                // Reject anything that is not a bare slug rather than silently
                // substituting a default: the value is stored as '' and the
                // read side falls back to the built-in default template.
                //
                // Storing '' (rather than skipping the key) is deliberate — the
                // settings UI sends '' as the "clear my selection" signal when
                // the user un-picks a template card, see
                // src/helpers/hooks/useTemplateSelection.jsx clearSelection().
                $slug            = \Etn\Utils\Helper::sanitize_template_slug( $value, '' );
                $sanitized[$key] = $slug;
            } else {
                $sanitized[$key] = sanitize_text_field( $value );
            }
        }

        return $sanitized;
    }
}
