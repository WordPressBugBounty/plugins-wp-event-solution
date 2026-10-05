<?php
/**
 * Updater for version 4.1.26
 *
 * @package Eventin\Upgrade
 */

namespace Eventin\Upgrade\Upgraders;

defined( 'ABSPATH' ) || exit;

use Eventin\Admin\EventReminder;
use Eventin\Emails\ReminderAutomation;
use Eventin\Event\EventLocation;
use Eventin\Extensions\ImportAutomation;

/**
 * Repairs CSV-imported event locations, back-fills the "Send Reminder To All Attendees"
 * automation flow, and re-arms event reminders whose automation checkpoint was lost.
 *
 * The on-demand reminder button refuses to send unless a published flow is
 * listening for its trigger. New customers get that flow from
 * ImportAutomation::create_automation_flows() the first time Automation is
 * enabled. Customers who enabled Automation before this feature shipped already
 * ran that seeder, so without this back-fill their button would warn "publish
 * the reminder flow" forever.
 *
 * Guards, both deliberate:
 * - etn_reminder_all_attendees_automation_migrated → this back-fill already ran
 *   (or the full seeder already included the flow). Never create twice, or the
 *   admin gets two flows and every attendee gets two emails per click.
 * - etn_email_automation_migrated → the customer has actually enabled Automation
 *   and has the other default flows. If it is not set, do nothing and leave our
 *   guard unset, so the toggle-on seeder creates the flow at that point instead.
 *
 * Lost reminders: before 4.1.26 the notification SDK stored each delayed flow's
 * checkpoint in a transient capped at 30 days. A reminder due more than ~30 days
 * after its event was saved found no checkpoint when its cron ran, so no attendee
 * got the email. The SDK now keeps checkpoints in options (and moves surviving
 * legacy transients itself), which fixes events saved from now on; the
 * checkpoints already lost need the repair queued here. It only re-registers
 * upcoming events whose last job is dead and whose reminder is still ahead, and
 * only when every reminder flow waits for the event date — so nothing is sent
 * early, nothing is sent twice, and events that never had a reminder stay as
 * they are (see EventReminder::backfill_reminder_schedule()). It does nothing
 * while Automation is off; switching Automation on later runs the full re-sync,
 * which also repairs.
 *
 * @since 4.1.26
 */
class V_4_1_26 implements UpdateInterface {
    /**
     * Run the updater
     *
     * @return void
     */
    public function run() {
        // Events imported from CSV before 4.1.26 can show the private Custom
        // URL join link as their public address. Fix the stored value once.
        if ( ! get_option( 'etn_csv_location_repaired' ) ) {
            EventLocation::repair_stored_events();
            update_option( 'etn_csv_location_repaired', true );
        }

        $this->backfill_reminder_flow();

        // Batched in cron and self-guarding, so safe to queue on every run.
        ( new EventReminder() )->queue_lost_reminder_repair();
    }

    /**
     * Back-fill the "Send Reminder To All Attendees" flow.
     *
     * @return void
     */
    private function backfill_reminder_flow() {
        // Already back-filled, or the full seeder already included this flow.
        if ( get_option( 'etn_reminder_all_attendees_automation_migrated' ) ) {
            return;
        }

        // Customer has never enabled Automation — leave it to the toggle-on seeder.
        if ( ! get_option( 'etn_email_automation_migrated' ) ) {
            return;
        }

        // The trigger is offered in the Automation UI, so a customer may already
        // have built their own reminder flow. Seeding the shipped one on top of
        // it would leave two published flows on the same trigger, and every click
        // would send each attendee two emails. Record the back-fill as done and
        // leave their flow alone.
        if ( ReminderAutomation::is_flow_published() ) {
            update_option( 'etn_reminder_all_attendees_automation_migrated', true );

            return;
        }

        ImportAutomation::create_reminder_all_attendees_flow();

        update_option( 'etn_reminder_all_attendees_automation_migrated', true );
    }
}
