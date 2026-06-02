<?php

defined( 'ABSPATH' ) || exit;

/**
 * Eventin <-> WPML compatibility bridge.
 *
 * Design rules (decided product-side):
 *  - Orders & attendees ALWAYS reference the original (source-language) event ID.
 *  - Recurring children inherit from the translated parent — no per-child translations.
 *  - Emails are sent in the site default language (English), regardless of buyer language.
 *  - Event FAQs use `copy-once`: duplicated as a starting draft when a translation
 *    is created, then each language version owns its FAQs independently (edited via
 *    the normal post editor, no String Translation involvement).
 *  - Schedule posts (`etn-schedule`) are translatable per language. Their operational
 *    meta (`etn_schedule_date`, `hide_date_on_event_page`) is `copy` + runtime-synced
 *    via `etn_update_schedule_meta`. Display meta (`etn_schedule_day`,
 *    `etn_schedule_title`, `etn_schedule_topics`) is `copy-once` so each language
 *    edits its own. The same runtime hook keeps topic time sub-keys
 *    (`etn_shedule_start_time` / `etn_shedule_end_time`) in sync inside the
 *    otherwise-independent `etn_schedule_topics` array.
 *
 * Known limitations — deferred:
 *  - Ticket variation names (`etn_ticket_variations[*][etn_ticket_name]`) stay
 *    identical across translations. Root cause: the name doubles as an identifier
 *    in the order pipeline (form field names, seatmap matching, price/slug lookup
 *    by name in `Event_Model::get_ticket_price_by_name`). Translating the display
 *    value would break order processing. Requires decoupling identity from display
 *    (route everything through `etn_ticket_slug`) before translation can be wired.
 *  - Attendee extra-field labels (`attendee_extra_fields`) use copy-once: copied on
 *    initial translation creation but independently editable per language afterwards.
 *    Note: the label-to-meta-key derivation still means each language's attendees
 *    store answers under keys derived from that language's labels.
 */

/**
 * Thin helper around WPML APIs so callers don't have to null-check $sitepress everywhere.
 */
class Etn_Wpml {

    public static function is_active() {
        return defined( 'ICL_SITEPRESS_VERSION' ) && class_exists( 'SitePress' );
    }

    /**
     * Map a post ID to the original-translation post ID. Returns the input if WPML
     * is not active or the post is not in any translation group.
     */
    public static function original_id( $post_id, $type = 'post_etn' ) {
        $post_id = (int) $post_id;
        if ( ! $post_id || ! self::is_active() ) {
            return $post_id;
        }

        global $sitepress;
        if ( ! is_object( $sitepress ) || ! method_exists( $sitepress, 'get_original_element_id' ) ) {
            return $post_id;
        }

        $original = $sitepress->get_original_element_id( $post_id, $type );
        return $original ? (int) $original : $post_id;
    }

    /**
     * Current language code (e.g. 'en', 'es'), or empty when WPML is inactive.
     */
    public static function current_lang() {
        if ( ! self::is_active() ) {
            return '';
        }
        return (string) apply_filters( 'wpml_current_language', null );
    }

    /**
     * Site default language code, or empty when WPML is inactive.
     */
    public static function default_lang() {
        if ( ! self::is_active() ) {
            return '';
        }
        return (string) apply_filters( 'wpml_default_language', null );
    }

    /**
     * Language code stored on a given post.
     */
    public static function post_lang( $post_id ) {
        if ( ! self::is_active() || ! $post_id ) {
            return '';
        }
        $info = apply_filters( 'wpml_post_language_details', null, (int) $post_id );
        return is_array( $info ) && ! empty( $info['language_code'] ) ? (string) $info['language_code'] : '';
    }

    /**
     * Switch active language; pair with restore_lang() after the work is done.
     * Safe no-op when WPML is inactive.
     */
    public static function switch_lang( $lang ) {
        if ( ! self::is_active() || ! $lang ) {
            return;
        }
        do_action( 'wpml_switch_language', $lang );
    }

    public static function restore_lang() {
        if ( ! self::is_active() ) {
            return;
        }
        do_action( 'wpml_switch_language', null );
    }
}

/**
 * Add etn_sold_tickets post meta on first publish (legacy bootstrap).
 */
function etn_add_sold_tickets_post_meta( $post_id, $post, $update ) {
    if ( $post->post_type === 'etn'
        && $post->post_status === 'publish'
        && empty( get_post_meta( $post_id, 'etn_sold_tickets' ) ) ) {
        add_post_meta( $post_id, 'etn_sold_tickets', 0, true );
    }
}
add_action( 'wp_insert_post', 'etn_add_sold_tickets_post_meta', 10, 3 );

/**
 * Keep stock/seat meta identical across all translations of an event.
 *
 * Custom-fields declared `action="copy"` in wpml-config.xml are only synced on
 * translation creation. Once a sibling exists, runtime updates (a sale, a refund,
 * a seat reservation) only touch the language version that was queried — leaving
 * other language siblings stale. We force a fresh copy on every change.
 */
add_action( 'added_post_meta',   'etn_update_event_meta', 10, 4 );
add_action( 'updated_post_meta', 'etn_update_event_meta', 10, 4 );
function etn_update_event_meta( $meta_id, $post_id, $meta_key, $meta_value ) {
    static $synced_keys = [
        // Stock / availability
        'etn_avaiilable_tickets',
        'etn_sold_tickets',
        'etn_total_avaiilable_tickets',
        'etn_total_sold_tickets',
        'etn_ticket_variations',
        'etn_max_attendee',
        'etn_global_stock',
        'etn_global_waiting_list',
        'etn_enable_global_stock',
        'enable_attendee_waiting_list',
        'pending_seats',
        '_etn_seat_unique_id',

        // Schedule / dates
        'etn_start_date',
        'etn_start_time',
        'etn_end_date',
        'etn_end_time',
        'etn_registration_deadline',

        // Recurring
        'recurring_enabled',
        'etn_event_recurrence',
        'etn_recurrence_timestamps',

        // Pricing / ticket structure
        'etn_ticket_price',
        'etn_ticket_availability',

        // Online meetings
        'etn_zoom_event',
        'etn_zoom_id',
        'etn_google_meet',
        'etn_google_meet_short_description',

        // Attendee / registration / seating
        'rsvp_settings',
        'enable_seatmap',
        'seat_plan',

        // Location / structural
        'event_timezone',
        'etn_event_location',
        'etn_event_location_type',
        'etn_event_location_list',
        'location',
        'event_type',
        'etn_event_organizer',
        'etn_event_speaker',
        'etn_event_schedule',
        'organizer_type',
        'organizer_group',
        'speaker_type',
        'speaker_group',
        'etn_select_speaker_schedule_type',

        // Layout / visual
        'event_layout',
        'event_banner',
        'event_banner_id',
        'event_logo_id',
        'etn_event_logo',
        'etn_event_calendar_bg',
        'etn_event_calendar_text_color',
        'alignment',
        'excerpt_enable',
        'external_link',
        'attende_page_link',
        'etn_event_socials',

        // Certificates / templates
        'certificate_template',
        'enable_legacy_certificate_template',
        'ticket_template',

        // (etn_event_faq intentionally NOT synced — copy-once per-language; see file header.)

        // Cross-plugin integrations
        'fluent_crm',
        'fluent_crm_webhook',
        'ens_flow_id',

        // Housekeeping
        'etn_last_update_date',
        'copied_media_ids',
        'referenced_media_ids',
    ];

    if ( ! in_array( $meta_key, $synced_keys, true ) ) {
        return;
    }
    if ( get_post_type( $post_id ) !== 'etn' ) {
        return;
    }
    if ( ! Etn_Wpml::is_active() ) {
        return;
    }

    // If this is a translation, write to the original event instead — that update
    // will then re-enter this hook and fall through to the sibling sync below.
    $original_id = Etn_Wpml::original_id( $post_id, 'post_etn' );
    if ( $original_id && (int) $original_id !== (int) $post_id ) {
        remove_action( 'updated_post_meta', 'etn_update_event_meta', 10 );
        update_post_meta( $original_id, $meta_key, $meta_value );
        add_action( 'updated_post_meta', 'etn_update_event_meta', 10, 4 );
        return;
    }

    // We are on the original event — push the new value to every translation
    // directly. We do NOT use wpml_sync_custom_field here because that action
    // only processes fields present in WPML's cached fields_to_sync list
    // (built from wpml-config.xml at activation time). New fields added to
    // wpml-config.xml after WPML was activated are silently skipped until
    // the WPML config cache is refreshed. Iterating translations explicitly
    // is reliable regardless of WPML's internal cache state.
    $trid         = apply_filters( 'wpml_element_trid', null, $post_id, 'post_etn' );
    $translations = $trid ? apply_filters( 'wpml_get_element_translations', [], $trid, 'post_etn' ) : [];

    remove_action( 'added_post_meta',   'etn_update_event_meta', 10 );
    remove_action( 'updated_post_meta', 'etn_update_event_meta', 10 );

    foreach ( $translations as $info ) {
        if ( empty( $info->element_id ) ) {
            continue;
        }
        $sibling_id = (int) $info->element_id;
        if ( $sibling_id === (int) $post_id ) {
            continue;
        }
        update_post_meta( $sibling_id, $meta_key, $meta_value );
    }

    add_action( 'added_post_meta',   'etn_update_event_meta', 10, 4 );
    add_action( 'updated_post_meta', 'etn_update_event_meta', 10, 4 );
}

/**
 * Normalize WC order line items to the original-language event ID.
 *
 * Buyers can purchase from any translated event page; reports, sold-ticket counts,
 * refunds and attendee provisioning all key off the original event. We rewrite
 * `event_id` on the order item to the original ID and stash the language-variant
 * ID in `_lang_event_id` so the cart/order UI can still link back to the page the
 * buyer actually saw.
 */
function etn_wpml_normalize_order_event_id( $order_id ) {
    if ( ! $order_id || ! Etn_Wpml::is_active() ) {
        return;
    }

    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }

    foreach ( $order->get_items() as $item_id => $item ) {
        $event_id = $item->get_meta( 'event_id', true );
        if ( empty( $event_id ) ) {
            continue;
        }

        $original = Etn_Wpml::original_id( $event_id, 'post_etn' );
        if ( ! $original || (int) $original === (int) $event_id ) {
            continue;
        }

        wc_update_order_item_meta( $item_id, 'event_id', $original );
        wc_update_order_item_meta( $item_id, '_lang_event_id', (int) $event_id );

        $original_title = get_the_title( $original );
        if ( $original_title ) {
            $item->set_name( $original_title );
            $item->save();
        }
    }

    $order->save();

    // Also normalize event_id on the etn-order post so stock updates target the original event.
    global $wpdb;
    $etn_order_ids = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT p.ID FROM {$wpdb->posts} p
             INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = 'wc_order_id' AND pm.meta_value = %s
             WHERE p.post_type = 'etn-order'",
            $order_id
        )
    );

    foreach ( $etn_order_ids as $etn_order_id ) {
        $etn_event_id = get_post_meta( $etn_order_id, 'event_id', true );
        if ( empty( $etn_event_id ) ) {
            continue;
        }
        $original = Etn_Wpml::original_id( $etn_event_id, 'post_etn' );
        if ( $original && (int) $original !== (int) $etn_event_id ) {
            update_post_meta( $etn_order_id, 'event_id', $original );
            update_post_meta( $etn_order_id, '_lang_event_id', (int) $etn_event_id );
        }
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'etn_wpml_normalize_order_event_id', 9, 1 );
add_action( 'woocommerce_store_api_checkout_update_order_meta', 'etn_wpml_normalize_order_event_id', 9, 1 );

/**
 * Normalize event_id on etn-order posts when the event_id meta is added/updated.
 * This ensures the etn-order always references the original event ID.
 */
add_action( 'added_post_meta',   'etn_wpml_normalize_etn_order_meta', 10, 4 );
add_action( 'updated_post_meta', 'etn_wpml_normalize_etn_order_meta', 10, 4 );
function etn_wpml_normalize_etn_order_meta( $meta_id, $post_id, $meta_key, $meta_value ) {
    if ( $meta_key !== 'event_id' || get_post_type( $post_id ) !== 'etn-order' ) {
        return;
    }

    if ( ! Etn_Wpml::is_active() ) {
        return;
    }

    $original = Etn_Wpml::original_id( $meta_value, 'post_etn' );
    if ( $original && (int) $original !== (int) $meta_value ) {
        // Temporarily remove this hook to prevent recursion
        remove_action( 'updated_post_meta', 'etn_wpml_normalize_etn_order_meta', 10 );
        update_post_meta( $post_id, 'event_id', $original );
        update_post_meta( $post_id, '_lang_event_id', (int) $meta_value );
        add_action( 'updated_post_meta', 'etn_wpml_normalize_etn_order_meta', 10, 4 );
    }
}

/**
 * Stamp the buyer's current language onto the cart item.
 *
 * Consumed by the cart-page renderer in core/woocommerce/hooks.php (~line 589) to
 * produce a `?lang=` permalink so users return to the language they purchased from.
 * The legacy `add_cart_item_data` handler reads `specific_lang` from POST; this
 * filter covers the AJAX / Store-API paths that don't post that field.
 */
add_filter( 'woocommerce_add_cart_item_data', 'etn_wpml_stamp_cart_item_language', 20, 2 );
function etn_wpml_stamp_cart_item_language( $cart_item_data, $product_id ) {
    if ( ! Etn_Wpml::is_active() ) {
        return $cart_item_data;
    }

    if ( get_post_type( $product_id ) !== 'etn' ) {
        return $cart_item_data;
    }

    if ( ! empty( $cart_item_data['specific_lang'] ) ) {
        return $cart_item_data;
    }

    $lang = Etn_Wpml::current_lang();
    if ( $lang ) {
        $cart_item_data['specific_lang'] = $lang;
    }

    return $cart_item_data;
}

/**
 * Localize the WC "added to cart" message for parent etn products (recurring children).
 */
add_filter( 'wc_add_to_cart_message_html', 'etn_wpml_override_woo_add_to_cart_msg', 10, 2 );
function etn_wpml_override_woo_add_to_cart_msg( $message, $products ) {
    if ( empty( $products ) || ! is_array( $products ) ) {
        return $message;
    }

    $product_id = (int) array_keys( $products )[0];

    if ( get_post_type( $product_id ) === 'etn' && ! empty( wp_get_post_parent_id( $product_id ) ) ) {
        // translators: %s is the product/event title.
        $message = sprintf( esc_html__( '"%s" has been added to your cart.', 'eventin' ), get_the_title( $product_id ) );
    }

    return $message;
}

/* -------------------------------------------------------------------------
 * REST API language boundary
 *
 * Eventin's React app calls /wp-json/eventin/v2/* (and a few /etn/v1/*).
 * Without this, two things break under WPML:
 *   1. Endpoints that take an `event_id` accept the translation child ID
 *      and write orders/attendees against it, breaking the "always store
 *      original" rule.
 *   2. Listing endpoints leak events from every language into one response.
 *
 * Strategy: a single `rest_pre_dispatch` filter that
 *   - normalizes `event_id` on every Eventin REST request to the original
 *     post ID (so downstream code never has to know);
 *   - switches WPML active language for the duration of the request when
 *     the caller passes an explicit `lang` param.
 * ------------------------------------------------------------------------- */
add_filter( 'rest_pre_dispatch', 'etn_wpml_rest_normalize', 10, 3 );
function etn_wpml_rest_normalize( $result, $server, $request ) {
    if ( ! Etn_Wpml::is_active() ) {
        return $result;
    }

    $route = (string) $request->get_route();
    if ( strpos( $route, '/eventin/' ) !== 0 && strpos( $route, '/etn/' ) !== 0 ) {
        return $result;
    }

    $event_id = $request->get_param( 'event_id' );
    if ( ! empty( $event_id ) && is_numeric( $event_id ) ) {
        $original = Etn_Wpml::original_id( (int) $event_id, 'post_etn' );
        if ( $original && (int) $original !== (int) $event_id ) {
            $request->set_param( 'event_id', $original );
        }
    }

    $lang = $request->get_param( 'lang' );
    if ( $lang ) {
        Etn_Wpml::switch_lang( sanitize_key( $lang ) );
    }

    return $result;
}

/* -------------------------------------------------------------------------
 * Force purchase emails into the site default language.
 *
 * A buyer on /es/event/... triggers OrderModel::send_email() while the
 * request still has Spanish active. Without switching, every __() inside
 * the email templates resolves to Spanish. Product decision: emails are
 * always English (= site default). We switch on `etn_before_send_email`
 * and restore on `etn_after_send_email` (hooks added in OrderEmailTrait).
 * ------------------------------------------------------------------------- */
add_action( 'etn_before_send_email', 'etn_wpml_force_default_lang_for_email' );
add_action( 'etn_after_send_email',  'etn_wpml_restore_lang_after_email' );

function etn_wpml_force_default_lang_for_email( $order ) {
    if ( ! Etn_Wpml::is_active() ) {
        return;
    }
    $default = Etn_Wpml::default_lang();
    if ( $default ) {
        Etn_Wpml::switch_lang( $default );
    }
}

function etn_wpml_restore_lang_after_email( $order ) {
    if ( ! Etn_Wpml::is_active() ) {
        return;
    }
    Etn_Wpml::restore_lang();
}

/* -------------------------------------------------------------------------
 * Sync etn_category and etn_tags assignments across all translations.
 *
 * WPML translate="1" taxonomies are independent per post translation by
 * default — assigning a term to a Spanish event leaves the English original
 * untouched. We mirror the meta sync pattern: map the assigned terms to
 * their original-language equivalents, write them to the original event,
 * then push the per-language translated terms to every other sibling.
 * ------------------------------------------------------------------------- */
add_action( 'set_object_terms', 'etn_wpml_sync_event_terms', 20, 6 );
function etn_wpml_sync_event_terms( $object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids ) {
    static $synced_taxonomies = [ 'etn_category', 'etn_tags' ];
    static $syncing = false;

    if ( $syncing ) {
        return;
    }
    if ( ! in_array( $taxonomy, $synced_taxonomies, true ) ) {
        return;
    }
    if ( get_post_type( $object_id ) !== 'etn' ) {
        return;
    }
    if ( ! Etn_Wpml::is_active() ) {
        return;
    }

    $original_id  = Etn_Wpml::original_id( $object_id, 'post_etn' );
    $default_lang = Etn_Wpml::default_lang();

    if ( ! $original_id || ! $default_lang ) {
        return;
    }

    // Read the full term set now on this post ($append may mean partial input).
    $current_ids = wp_get_post_terms( $object_id, $taxonomy, [ 'fields' => 'ids' ] );
    if ( is_wp_error( $current_ids ) ) {
        return;
    }
    $current_ids = array_map( 'intval', $current_ids );

    $is_original = ( (int) $object_id === (int) $original_id );

    if ( $is_original ) {
        // Already on the original — terms are already in the canonical language.
        // Skip mapping to avoid wpml_object_id returning null for terms that
        // exist but have no explicit WPML translation record for their own language.
        $original_term_ids = $current_ids;
    } else {
        // Translating from a sibling — map each term to its original-language counterpart.
        $original_term_ids = [];
        foreach ( $current_ids as $tid ) {
            // Use return_original_if_missing=true so terms without a WPML record
            // still get an ID to work with rather than silently dropping.
            $mapped = (int) apply_filters( 'wpml_object_id', $tid, $taxonomy, true, $default_lang );
            if ( $mapped ) {
                $original_term_ids[] = $mapped;
            }
        }
        $original_term_ids = array_unique( $original_term_ids );
    }

    // Guard: never wipe terms on a non-empty post due to a mapping failure.
    // If the post has terms but mapping produced nothing, bail rather than clear.
    if ( ! empty( $current_ids ) && empty( $original_term_ids ) ) {
        return;
    }

    $syncing = true;

    // Write canonical terms to the original event (only when called from a sibling).
    // Switch to default language so WPML doesn't normalize the terms to the
    // sibling's language before writing to the original post.
    if ( ! $is_original ) {
        Etn_Wpml::switch_lang( $default_lang );
        wp_set_post_terms( $original_id, $original_term_ids, $taxonomy, false );
        Etn_Wpml::restore_lang();
    }

    // Push the per-language translated terms to every other translation.
    $trid         = apply_filters( 'wpml_element_trid', null, $original_id, 'post_etn' );
    $translations = $trid ? apply_filters( 'wpml_get_element_translations', [], $trid, 'post_etn' ) : [];

    foreach ( $translations as $lang => $info ) {
        if ( empty( $info->element_id ) ) {
            continue;
        }
        $sibling_id = (int) $info->element_id;
        if ( $sibling_id === (int) $original_id ) {
            continue;
        }

        $sibling_terms = [];
        foreach ( $original_term_ids as $orig_tid ) {
            $xlated = (int) apply_filters( 'wpml_object_id', $orig_tid, $taxonomy, false, $lang );
            if ( $xlated ) {
                $sibling_terms[] = $xlated;
            }
        }

        // Switch to the sibling's language before assigning. WPML normalises
        // term IDs to the active language inside wp_set_object_terms, so
        // assigning Bengali term 62 from an English context silently converts
        // it back to English term 60. Switching language prevents that.
        Etn_Wpml::switch_lang( $lang );
        wp_set_post_terms( $sibling_id, $sibling_terms, $taxonomy, false );
        Etn_Wpml::restore_lang();
    }

    $syncing = false;
}

/* -------------------------------------------------------------------------
 * Keep `etn-schedule` post meta consistent across translations.
 *
 * The wpml-config.xml declares schedule meta with two regimes:
 *   - `copy`       (etn_schedule_date, hide_date_on_event_page) — stay identical
 *   - `copy-once`  (etn_schedule_day, etn_schedule_title, etn_schedule_topics)
 *                  — duplicated as a starting draft, then per-language autonomous
 *
 * WPML applies these actions at translation creation time. Once siblings exist,
 * runtime updates only touch the language version that was edited — leaving
 * `copy` siblings stale. We mirror the event-meta sync pattern here:
 *
 *   - For `copy` keys: push the new value to every translation sibling.
 *   - For `etn_schedule_topics` (declared `copy-once` so display text stays
 *     per-language): walk the topics array and propagate ONLY the operational
 *     time sub-keys to each sibling, preserving the sibling's translated topic
 *     titles, room labels, and objectives. Indexes that don't exist on the
 *     sibling are skipped — we never auto-add or auto-remove topics across
 *     languages, only sync times for topics the sibling already has.
 * ------------------------------------------------------------------------- */
add_action( 'added_post_meta',   'etn_update_schedule_meta', 10, 4 );
add_action( 'updated_post_meta', 'etn_update_schedule_meta', 10, 4 );
function etn_update_schedule_meta( $meta_id, $post_id, $meta_key, $meta_value ) {
    static $synced_keys = [
        'etn_schedule_date',
        'hide_date_on_event_page',
        'etn_schedule_topics', // sub-key sync only — see below
    ];

    if ( ! in_array( $meta_key, $synced_keys, true ) ) {
        return;
    }
    if ( get_post_type( $post_id ) !== 'etn-schedule' ) {
        return;
    }
    if ( ! Etn_Wpml::is_active() ) {
        return;
    }

    // If editing a translation sibling, route the write to the original schedule
    // post first; that update will re-enter this hook and fan out to all siblings.
    $original_id = Etn_Wpml::original_id( $post_id, 'post_etn-schedule' );
    if ( $original_id && (int) $original_id !== (int) $post_id ) {
        remove_action( 'updated_post_meta', 'etn_update_schedule_meta', 10 );
        update_post_meta( $original_id, $meta_key, $meta_value );
        add_action( 'updated_post_meta', 'etn_update_schedule_meta', 10, 4 );
        return;
    }

    $trid         = apply_filters( 'wpml_element_trid', null, $post_id, 'post_etn-schedule' );
    $translations = $trid ? apply_filters( 'wpml_get_element_translations', [], $trid, 'post_etn-schedule' ) : [];

    remove_action( 'added_post_meta',   'etn_update_schedule_meta', 10 );
    remove_action( 'updated_post_meta', 'etn_update_schedule_meta', 10 );

    foreach ( $translations as $info ) {
        if ( empty( $info->element_id ) ) {
            continue;
        }
        $sibling_id = (int) $info->element_id;
        if ( $sibling_id === (int) $post_id ) {
            continue;
        }

        if ( $meta_key === 'etn_schedule_topics' ) {
            $original_topics = is_array( $meta_value ) ? $meta_value : [];
            $sibling_raw     = get_post_meta( $sibling_id, 'etn_schedule_topics', true );
            $sibling_topics  = is_array( $sibling_raw ) ? $sibling_raw : [];

            foreach ( $original_topics as $idx => $topic ) {
                if ( ! is_array( $topic ) ) {
                    continue;
                }
                if ( ! isset( $sibling_topics[ $idx ] ) || ! is_array( $sibling_topics[ $idx ] ) ) {
                    continue;
                }
                if ( array_key_exists( 'etn_shedule_start_time', $topic ) ) {
                    $sibling_topics[ $idx ]['etn_shedule_start_time'] = $topic['etn_shedule_start_time'];
                }
                if ( array_key_exists( 'etn_shedule_end_time', $topic ) ) {
                    $sibling_topics[ $idx ]['etn_shedule_end_time'] = $topic['etn_shedule_end_time'];
                }
            }

            update_post_meta( $sibling_id, 'etn_schedule_topics', $sibling_topics );
        } else {
            update_post_meta( $sibling_id, $meta_key, $meta_value );
        }
    }

    add_action( 'added_post_meta',   'etn_update_schedule_meta', 10, 4 );
    add_action( 'updated_post_meta', 'etn_update_schedule_meta', 10, 4 );
}
