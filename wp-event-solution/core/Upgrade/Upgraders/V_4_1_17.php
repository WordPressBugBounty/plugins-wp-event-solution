<?php
/**
 * Updater for version 4.1.17
 *
 * @package Eventin\Upgrade
 */

namespace Eventin\Upgrade\Upgraders;

use Eventin\Upgrade\Schema;

/**
 * Updater class for v4.1.17
 *
 * Creates the coupon storage tables:
 *  - etn_coupons             coupon definitions (unique code, atomic usage_count)
 *  - etn_coupon_redemptions  one row per redeemed order (per-user + drill-in queries)
 *
 * The table definitions themselves live in `Eventin\Upgrade\Schema`, which runs
 * independently of the version chain. Version-gating table creation is what left
 * fresh 4.1.19 installs with no coupon storage at all (see Schema's docblock); this
 * upgrader stays so an upgrading site still installs them at the point in the chain
 * where they were introduced.
 *
 * Idempotent: Schema guards every step with SHOW TABLES.
 *
 * @since 4.1.17
 */
class V_4_1_17 implements UpdateInterface {
    /**
     * Run the updater
     *
     * @return void
     */
    public function run() {
        // Earlier builds wrapped this in a transaction and DROPped both tables when
        // either create failed. MySQL commits implicitly on DDL, so the rollback
        // bought nothing — and on a site whose coupons table was already populated
        // but whose redemptions table was missing, that DROP destroyed live coupons.
        // Schema's per-table guards make the recovery unnecessary.
        Schema::install();
    }
}
