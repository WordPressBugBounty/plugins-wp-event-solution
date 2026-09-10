<?php
/**
 * Describes an event's tickets as purchasable offers.
 *
 * @package Eventin\Schema
 */

namespace Eventin\Schema;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Event\Event_Model;

/**
 * Builds one schema.org Offer per ticket variation.
 *
 * This is what turns a plain search listing into one showing a price and an
 * in-stock / sold-out state, so the numbers have to be right: prices are emitted
 * as plain decimal strings, the currency as an ISO 4217 code (not the display
 * symbol), and availability is derived from real remaining stock.
 *
 * Deliberately does not extend the Schema base class — an Offer is never a
 * standalone node keyed by post ID; it only ever exists nested inside an Event.
 */
class OfferSchema {

    /**
     * Ticket count at or below which an event is reported as running low.
     *
     * @var int
     */
    protected $low_stock = 5;

    /**
     * Build the list of Offers for an event.
     *
     * @param Event_Model $event Event model.
     *
     * @return array List of Offer objects. Empty when the event sells nothing.
     */
    public function get_offers( Event_Model $event ): array {
        $variations = $event->etn_ticket_variations;

        if ( ! is_array( $variations ) || empty( $variations ) ) {
            return [];
        }

        $currency    = $this->get_currency_code();
        $url         = get_permalink( $event->id );
        $valid_from  = $this->get_valid_from( $event );
        $valid_until = $this->get_valid_through( $event );
        $offers      = [];

        /**
         * Filters the remaining-ticket count at or below which an event is
         * reported to search engines as having limited availability.
         *
         * @since 4.1.20
         *
         * @param int         $low_stock Threshold.
         * @param Event_Model $event     Event model.
         */
        $low_stock = (int) apply_filters( 'eventin_schema_low_stock_threshold', $this->low_stock, $event );

        foreach ( $variations as $variation ) {
            if ( ! is_array( $variation ) ) {
                continue;
            }

            $offer = (object) [
                '@type'         => 'Offer',
                'name'          => isset( $variation['etn_ticket_name'] ) ? wp_strip_all_tags( (string) $variation['etn_ticket_name'] ) : '',
                'price'         => $this->normalize_price( $variation['etn_ticket_price'] ?? 0 ),
                'priceCurrency' => $currency,
                'availability'  => $this->get_availability( $event, $variation, $low_stock ),
                'url'           => esc_url_raw( $url ),
            ];

            if ( '' === $offer->name ) {
                unset( $offer->name );
            }

            if ( $valid_from ) {
                $offer->validFrom = $valid_from;
            }

            if ( $valid_until ) {
                $offer->validThrough = $valid_until;
            }

            /**
             * Filters a single ticket Offer before it is emitted.
             *
             * @since 4.1.20
             *
             * @param object      $offer     Offer object.
             * @param array       $variation The raw ticket variation.
             * @param Event_Model $event     Event model.
             */
            $offers[] = apply_filters( 'eventin_schema_offer_object', $offer, $variation, $event );
        }

        return $offers;
    }

    /**
     * Availability for a ticket variation.
     *
     * Reads `etn_avaiilable_tickets` — the misspelling is the real meta key used
     * throughout the plugin, so do not "fix" it here.
     *
     * @param Event_Model $event     Event model.
     * @param array       $variation Ticket variation.
     * @param int         $low_stock Low-stock threshold.
     *
     * @return string A schema.org ItemAvailability URL.
     */
    protected function get_availability( Event_Model $event, array $variation, int $low_stock ): string {
        $base = 'https://schema.org/';

        // Sales that have closed are sold out regardless of remaining stock.
        if ( $this->sales_closed( $event ) ) {
            return $base . 'SoldOut';
        }

        $unlimited = ! empty( $variation['etn_unlimited_tickets'] )
            || ( isset( $variation['etn_avaiilable_tickets'] ) && -1 === (int) $variation['etn_avaiilable_tickets'] );

        if ( $unlimited ) {
            return $base . 'InStock';
        }

        // With global stock on, the event-wide pool decides, not the per-ticket count.
        // Assign to locals first: Post_Model has __get but no __isset, so empty()
        // on a model property always reports "not set" — see the known gotcha.
        $global_stock_on = $event->etn_enable_global_stock;
        $global_stock    = $event->etn_global_stock;

        if ( $global_stock_on ) {
            $remaining = (int) $global_stock - $event->get_total_sold_ticket();
        } else {
            $available = isset( $variation['etn_avaiilable_tickets'] ) ? (int) $variation['etn_avaiilable_tickets'] : 0;
            $sold      = isset( $variation['etn_sold_tickets'] ) ? (int) $variation['etn_sold_tickets'] : 0;
            $remaining = $available - $sold;
        }

        if ( $remaining <= 0 ) {
            return $base . 'SoldOut';
        }

        if ( $remaining <= $low_stock ) {
            return $base . 'LimitedAvailability';
        }

        return $base . 'InStock';
    }

    /**
     * Whether ticket sales have already closed for this event.
     *
     * @param Event_Model $event Event model.
     *
     * @return bool
     */
    protected function sales_closed( Event_Model $event ): bool {
        $deadline = $event->etn_registration_deadline;

        if ( ! $deadline ) {
            return false;
        }

        try {
            $closes = new \DateTime( $this->normalize_datetime( $deadline ), new \DateTimeZone( $event->get_timezone() ) );
            $now    = new \DateTime( 'now', new \DateTimeZone( $event->get_timezone() ) );

            return $now > $closes;
        } catch ( \Exception $e ) {
            return false;
        }
    }

    /**
     * Normalize a stored date string for DateTime.
     *
     * Deadlines are stored with slashes in places ("2026/10/05 18:00"), which
     * PHP would otherwise read as an American m/d/Y date.
     *
     * @param mixed $value Raw stored value.
     *
     * @return string
     */
    protected function normalize_datetime( $value ): string {
        return trim( str_replace( '/', '-', (string) $value ) );
    }

    /**
     * When the offer becomes buyable.
     *
     * Eventin has no per-ticket sale-start date, so the event's publish date is
     * the honest answer: that is the moment the ticket became purchasable.
     *
     * @param Event_Model $event Event model.
     *
     * @return string ISO 8601 datetime, or an empty string.
     */
    protected function get_valid_from( Event_Model $event ): string {
        $published = get_post_datetime( $event->id, 'date', 'local' );

        return $published ? $published->format( 'c' ) : '';
    }

    /**
     * When the offer stops being buyable.
     *
     * The registration deadline is when buying *stops*, so it belongs here — not
     * on validFrom, where the previous implementation put it.
     *
     * @param Event_Model $event Event model.
     *
     * @return string ISO 8601 datetime, or an empty string.
     */
    protected function get_valid_through( Event_Model $event ): string {
        $deadline = $event->etn_registration_deadline;

        if ( ! $deadline ) {
            return '';
        }

        try {
            $closes = new \DateTime( $this->normalize_datetime( $deadline ), new \DateTimeZone( $event->get_timezone() ) );

            return $closes->format( 'c' );
        } catch ( \Exception $e ) {
            return '';
        }
    }

    /**
     * Price as a plain decimal string.
     *
     * schema.org forbids currency symbols and thousands separators in `price`.
     *
     * @param mixed $price Raw stored price.
     *
     * @return string
     */
    protected function normalize_price( $price ): string {
        if ( is_numeric( $price ) ) {
            return (string) round( (float) $price, 2 );
        }

        // Strip anything that is not a digit, dot or minus (symbols, separators,
        // and the word "Free", which becomes an empty string and then zero).
        $clean = preg_replace( '/[^0-9.\-]/', '', (string) $price );

        return '' === $clean ? '0' : (string) round( (float) $clean, 2 );
    }

    /**
     * The store's ISO 4217 currency code.
     *
     * `Etn\Core\Event\Helper::get_currency()` returns a display *symbol*, which
     * schema.org rejects — `etn_currency()` is the code.
     *
     * @return string
     */
    protected function get_currency_code(): string {
        $currency = function_exists( 'etn_currency' ) ? etn_currency() : 'USD';
        $currency = strtoupper( trim( (string) $currency ) );

        return preg_match( '/^[A-Z]{3}$/', $currency ) ? $currency : 'USD';
    }
}
