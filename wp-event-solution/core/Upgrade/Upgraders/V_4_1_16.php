<?php
/**
 * Updater for version 4.1.16
 *
 * @package Eventin\Upgrade
 */

namespace Eventin\Upgrade\Upgraders;

defined( 'ABSPATH' ) || exit;

use Eventin\Extensions\ImportAutomation;

/**
 * Back-fills the "Send Certificate" automation flow.
 *
 * The send_certificate trigger has always been registered as an available
 * action, but no default flow was ever seeded. New customers now get it via
 * ImportAutomation::create_automation_flows() the first time Automation is
 * enabled. Existing customers already ran that seeder before this flow existed,
 * so this upgrader creates the single missing flow for them.
 *
 * Guards:
 * - etn_send_certificate_automation_migrated → this back-fill already ran (or the
 *   full seeder already included Send Certificate). Never create twice.
 * - etn_email_automation_migrated → the customer has actually enabled Automation
 *   and has the other default flows. If it is not set, we do nothing and leave
 *   our guard unset, so the toggle-on seeder handles Send Certificate later.
 *
 * @since 4.1.16
 */
class V_4_1_16 implements UpdateInterface {
    /**
     * Run the updater
     *
     * @return void
     */
    public function run() {
        // Seed the hidden Template Builder preview placeholder event (run-once, self-guarded).
        ( new \Eventin\PreviewPlaceholder\PreviewPlaceholderEventSeeder() )->seed();

        // Already back-filled, or the full seeder already included Send Certificate.
        if ( get_option( 'etn_send_certificate_automation_migrated' ) ) {
            return;
        }

        // Customer has never enabled Automation — leave it to the toggle-on seeder,
        // which will create Send Certificate and set the guard at that point.
        if ( ! get_option( 'etn_email_automation_migrated' ) ) {
            return;
        }

        ImportAutomation::create_send_certificate_flow();

        update_option( 'etn_send_certificate_automation_migrated', true );
    }
}
