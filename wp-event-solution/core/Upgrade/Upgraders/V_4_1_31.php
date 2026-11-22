<?php
/**
 * Updater for version 4.1.31
 *
 * @package Eventin\Upgrade
 */

namespace Eventin\Upgrade\Upgraders;

defined( 'ABSPATH' ) || exit;

use Eventin\Extensions\ImportAutomation;

/**
 * Updates the default "Purchase Email Automation For Customer" mail wording.
 *
 * Sites that turned Automation on earlier already saved the old subject and body
 * as a flow. New installs get the new text from the seeder, so this only touches
 * flows that still have the old shipped text. Edited flows are left alone.
 * Running it twice changes nothing, because the old text is gone after the first run.
 *
 * @since 4.1.31
 */
class V_4_1_31 implements UpdateInterface {
    /**
     * Run the updater
     *
     * @return void
     */
    public function run() {
        ImportAutomation::refresh_customer_purchase_flows();
    }
}
