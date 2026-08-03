<?php
/**
 * Updater for version 4.1.19
 *
 * @package Eventin\Upgrade
 */

namespace Eventin\Upgrade\Upgraders;

defined( 'ABSPATH' ) || exit;

use Eventin\PreviewPlaceholder\PreviewPlaceholderCleanup;
use Eventin\Upgrade\Schema;

/**
 * Carries two unrelated 4.1.19 migrations.
 *
 * 1. Clears the duplicate preview-placeholder events left by concurrent seeding on
 *    4.1.16–4.1.18. The seeder was guarded only by an option written after several
 *    seconds of work, and it runs on `admin_init`, which admin-ajax.php fires too —
 *    so on busy admin screens more than one request could seed at once and leave two
 *    or three copies of the demo event behind. 4.1.19 prevents that (DbLock in the
 *    seeder) and hides every copy rather than only the last (marker-based
 *    PreviewPlaceholder), but sites that already carry the surplus rows need them
 *    cleared. The cleanup refuses to delete anything a customer has touched — see
 *    PreviewPlaceholderCleanup.
 *
 * 2. Adds `discount_scope` to etn_coupons, which controls what a discount is computed
 *    on: 'total' (tickets + add-ons, the existing behavior) or 'tickets' (ticket price
 *    only). Existing rows default to 'total', so no coupon changes behavior.
 *
 * The two arrived from different branches and are deliberately kept independent. The
 * coupon migration's guards must never gate the placeholder cleanup: a site that
 * already has the column — anyone who ran an earlier 4.1.19 build — would otherwise
 * skip the cleanup silently and never retry, because `do_upgrade()` only fires when
 * the running version is greater than `etn_db_migration`, which is already 4.1.19
 * there. Regression cover: tests/phpunit/tests/ReleaseV4119MigrationTest.php.
 *
 * Both steps are individually idempotent.
 *
 * @since 4.1.19
 */
class V_4_1_19 implements UpdateInterface {
    /**
     * Run the updater.
     *
     * @return void
     */
    public function run() {
        // Independent migrations: neither may short-circuit the other.
        $this->clean_duplicate_preview_placeholders();
        $this->add_coupon_discount_scope_column();
    }

    /**
     * Remove the surplus demo-event copies left behind by concurrent seeding.
     *
     * Self-guarding: no-ops when the site has no placeholder records, and skips any
     * copy a customer already has bookings against.
     *
     * @return void
     */
    private function clean_duplicate_preview_placeholders() {
        ( new PreviewPlaceholderCleanup() )->run();
    }

    /**
     * Add the `discount_scope` column to etn_coupons.
     *
     * The column definition lives in Schema, which also installs it on sites that
     * never run this upgrader. Idempotent: guarded there by SHOW TABLES + SHOW COLUMNS.
     *
     * @return void
     */
    private function add_coupon_discount_scope_column() {
        Schema::add_coupon_discount_scope_column();
    }
}
