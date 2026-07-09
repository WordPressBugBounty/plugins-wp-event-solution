<?php

namespace Eventin\Order;

defined( 'ABSPATH' ) || exit;

/**
 * Pure tax calculator — the PHP twin of src/module-purchase/utils/calculate-tax.js.
 *
 * Geo-matches a tax-rate row to the customer's address (WooCommerce-style '*'
 * wildcards, most-specific wins, ties -> first) and computes the tax amount.
 * Contains no WordPress calls so it can be unit-tested standalone.
 */
class TaxCalculator {

    /**
     * @param float  $subtotal          Pre-tax ticket subtotal.
     * @param array  $settings          [ enable_tax, tax_type, tax_rates ].
     * @param string $event_tax_status  'taxable' | 'none'.
     * @param array  $customer          [ country, state, postcode, city ].
     *
     * @return array [ 'taxAmount' => float, 'taxType' => string, 'label' => string ]
     */
    public static function calculate( $subtotal, array $settings, $event_tax_status = 'taxable', array $customer = [] ) {
        $tax_type = isset( $settings['tax_type'] ) ? $settings['tax_type'] : 'exclusive';
        $rates    = ( isset( $settings['tax_rates'] ) && is_array( $settings['tax_rates'] ) ) ? $settings['tax_rates'] : [];
        $enable   = self::is_enabled( isset( $settings['enable_tax'] ) ? $settings['enable_tax'] : '' );
        $amount   = floatval( $subtotal );
        $taxable  = ( 'none' !== $event_tax_status );

        $rate  = self::match_rate( $rates, $customer );
        $label = $rate ? trim( isset( $rate['name'] ) ? $rate['name'] : '' ) : '';

        if ( ! $enable || ! $taxable || ! $rate || $amount <= 0 ) {
            return [ 'taxAmount' => 0.0, 'taxType' => $tax_type, 'label' => $label ];
        }

        $value    = floatval( isset( $rate['rate'] ) ? $rate['rate'] : 0 );
        $is_fixed = ( isset( $rate['rate_type'] ) ? $rate['rate_type'] : 'percent' ) === 'fixed';

        if ( $is_fixed ) {
            $tax_amount = $value;
        } elseif ( 'inclusive' === $tax_type ) {
            $tax_amount = $amount - $amount / ( 1 + $value / 100 );
        } else {
            $tax_amount = $amount * $value / 100;
        }

        return [ 'taxAmount' => $tax_amount, 'taxType' => $tax_type, 'label' => $label ];
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private static function is_enabled( $value ) {
        return in_array( $value, [ '1', 'on', 1, true ], true );
    }

    /**
     * @param string $value
     * @return string
     */
    private static function normalize( $value ) {
        return strtolower( trim( (string) $value ) );
    }

    /**
     * Pick the best-matching rate row: every field must be '*' or equal the
     * customer's value; among matches the one with the fewest wildcards wins;
     * ties resolve to the first in array order. No match -> null.
     *
     * @param array $rates
     * @param array $customer
     * @return array|null
     */
    private static function match_rate( array $rates, array $customer ) {
        $cust = [
            'country_code' => self::normalize( isset( $customer['country'] ) ? $customer['country'] : '' ),
            'state_code'   => self::normalize( isset( $customer['state'] ) ? $customer['state'] : '' ),
            'postcode'     => self::normalize( isset( $customer['postcode'] ) ? $customer['postcode'] : '' ),
            'city'         => self::normalize( isset( $customer['city'] ) ? $customer['city'] : '' ),
        ];

        $best       = null;
        $best_score = -1;

        foreach ( $rates as $row ) {
            if ( ! is_array( $row ) ) {
                continue;
            }

            $score   = 0;
            $matches = true;

            foreach ( $cust as $key => $cust_val ) {
                $rule = self::normalize( isset( $row[ $key ] ) ? $row[ $key ] : '*' );

                if ( '*' === $rule || '' === $rule ) {
                    continue;
                }

                if ( $rule === $cust_val ) {
                    $score++;
                    continue;
                }

                $matches = false;
                break;
            }

            if ( $matches && $score > $best_score ) {
                $best       = $row;
                $best_score = $score;
            }
        }

        return $best;
    }
}
