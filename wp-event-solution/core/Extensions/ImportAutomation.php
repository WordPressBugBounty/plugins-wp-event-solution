<?php

/**
 * Extentions class
 * 
 * @package Eventin
 */
namespace Eventin\Extensions;

defined( 'ABSPATH' ) || exit;

use Eventin\Vendor\Ens\Flow\Flow;
use Exception;

/**
 * Class extention
 */
class ImportAutomation {
    /**
     * Default subject and body of the "Purchase Email Automation For Customer" flow.
     *
     * The customer is the person who paid, so this one mail has to carry every
     * attendee's ticket link, even when none of the attendees uses the customer's
     * own email address. The ticket links are added below the body by the order
     * email template.
     */
    const CUSTOMER_PURCHASE_SUBJECT = 'Your tickets for {%event_title%}';
    const CUSTOMER_PURCHASE_BODY    = '<p>Hi {%customer_name%},</p><p>Thank you for your booking. You booked {%ticket_quantity%} ticket(s) for "{%event_title%}" on {%event_date%} at {%event_time%}.</p><p>Your booking number is #{%order_id%}. The ticket for each attendee is listed below. Use the Download Ticket link to save it, or forward the link to the attendee.</p>';

    /**
     * Subject and body this flow shipped with before the wording above.
     * Only used to tell an untouched flow from one the admin has edited.
     */
    const LEGACY_CUSTOMER_PURCHASE_SUBJECT = 'Ticket purchase successful';
    const LEGACY_CUSTOMER_PURCHASE_BODY    = 'You have purchased ticket(s). Attendee ticket details are as follows.';

    /**
     * Set once the waiting-list and refund flows were created on this site.
     */
    const ORDER_NOTICE_MIGRATED_OPTION = 'etn_order_notice_automation_migrated';
    const PAYMENT_LINK_MIGRATED_OPTION = 'etn_payment_link_automation_migrated';

    /**
     * Set once saved default flows had their wrong text repaired (v2 adds the RSVP subject).
     */
    const QUOTE_REPAIR_OPTION = 'etn_automation_default_text_repaired_v2';

    /**
     * The shipped RSVP subject was "Event Reminder", which is wrong for an RSVP.
     */
    const OLD_RSVP_SUBJECT = 'Event Reminder';
    const NEW_RSVP_SUBJECT = 'RSVP: {%event_title%}';

    /**
     * Repair wrong text in saved default flows, once.
     *
     * 1. RSVP flows still using the shipped subject "Event Reminder" get
     *    "RSVP: {%event_title%}".
     * 2. The apostrophe:
     * Older versions wrote "Here\u2019s" in a PHP string where \u2019 is not
     * an escape, so flows were saved with "Hereu2019s Whatu2019s" and every
     * email showed it. Only a "u2019" between letters is changed, so text an
     * admin wrote is left alone.
     *
     * @return void
     */
    public static function repair_broken_quotes() {
        if ( get_option( self::QUOTE_REPAIR_OPTION ) ) {
            return;
        }

        $ids = get_posts( [
            'post_type'      => 'eve-flow',
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ] );

        foreach ( $ids as $id ) {
            $config = get_post_meta( $id, '_eve_notification_flow_flow_config', true );
            if ( ! is_array( $config ) || empty( $config['nodes'] ) || ! is_array( $config['nodes'] ) ) {
                continue;
            }

            $changed = false;
            $is_rsvp = 'event_rsvp_email' === get_post_meta( $id, '_eve_notification_flow_trigger', true );
            foreach ( $config['nodes'] as &$node ) {
                // Only the untouched shipped subject; an admin's own subject stays.
                if ( $is_rsvp && 'email' === ( $node['name'] ?? '' ) && self::OLD_RSVP_SUBJECT === ( $node['data']['subject'] ?? null ) ) {
                    $node['data']['subject']  = self::NEW_RSVP_SUBJECT;
                    $node['data']['subtitle'] = 'Subject: ' . self::NEW_RSVP_SUBJECT;
                    $changed                  = true;
                }

                foreach ( [ 'subject', 'subtitle', 'body' ] as $field ) {
                    if ( ! isset( $node['data'][ $field ] ) || ! is_string( $node['data'][ $field ] ) ) {
                        continue;
                    }

                    $fixed = preg_replace( '/(?<=[A-Za-z])u2019(?=[A-Za-z.])/', '’', $node['data'][ $field ] );
                    if ( $fixed !== $node['data'][ $field ] ) {
                        $node['data'][ $field ] = $fixed;
                        $changed                = true;
                    }
                }
            }
            unset( $node );

            if ( $changed ) {
                update_post_meta( $id, '_eve_notification_flow_flow_config', $config );
            }
        }

        update_option( self::QUOTE_REPAIR_OPTION, 1, false );
    }

    public static function create_automation_flows() {
        $result = array(
            'flow_ids' => array(),
            'errors'   => array(),
        );

        $automation_flows = [
            [
                "name" => "Send Email To All Attendees",
                "trigger" => "send_email_to_all_attendees",
                "flow_config" => [
                    "nodes" => [
                        [
                            "id" => "node_1",
                            "type" => "trigger",
                            "name" => "trigger",
                            "data" => [
                                "label" => "trigger: send_email_to_all_attendees",
                                "subtitle" => "Send Email To All Attendees",
                                "triggerValue" => "send_email_to_all_attendees"
                            ],
                            "position" => [
                                "x" => 200,
                                "y" => 50
                            ]
                        ],
                        [
                            "id" => "node_2",
                            "type" => "end",
                            "name" => "end",
                            "data" => [
                                "label" => "end_flow",
                                "subtitle" => "End execution path"
                            ],
                            "position" => [
                                "x" => 221.46762850701441,
                                "y" => 410
                            ]
                        ],
                        [
                            "id" => "node_3",
                            "type" => "action",
                            "name" => "email",
                            "data" => [
                                "actionType" => "send_email",
                                "label" => "send_email",
                                "subtitle" => "Subject: Thanks for Joining Us -  Here’...",
                                "receiverType" => "attendee_email",
                                "from" => "admin@example.com",
                                "subject" => "Thanks for Joining Us -  Here’s What’s Next",
                                "body" => "<p>Hi Attendees ,<\/p><p>Thanks for attending \"{%event_title%}\". We appreciate your time and hope the session delivered real value.<\/p><p>&nbsp;<\/p><p>If you would like updates on future sessions or have feedback, reply directly to this email. we review every message.<\/p><p>&nbsp;<\/p><p>Thanks again,<\/p>",
                                "processed_session_ids" => []
                            ],
                            "position" => [
                                "x" => 210.73381425350721,
                                "y" => 230
                            ]
                        ]
                    ],
                    "edges" => [
                        [
                            "id" => "edge_node_1-node_3",
                            "type" => "smoothstep",
                            "markerEnd" => [
                                "type" => "arrowclosed"
                            ],
                            "source" => "node_1",
                            "target" => "node_3",
                            "data" => []
                        ],
                        [
                            "id" => "edge_node_3-node_2",
                            "type" => "smoothstep",
                            "markerEnd" => [
                                "type" => "arrowclosed"
                            ],
                            "source" => "node_3",
                            "target" => "node_2",
                            "data" => []
                        ]
                    ]
                ],
                "status" => "publish"
            ],
            [
                'name' => 'Purchase Email Automation For Attendee',
                'trigger' => 'event_ticket_purchase',
                'flow_config' => [
                    'nodes' => [
                        [
                            'id' => 'node_1',
                            'type' => 'trigger',
                            'name' => 'trigger',
                            'data' => [
                                'label' => 'trigger: event_ticket_purchase',
                                'subtitle' => 'Event Ticket Purchase',
                                'triggerValue' => 'event_ticket_purchase',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 50,
                            ],
                        ],
                        [
                            'id' => 'node_2',
                            'type' => 'end',
                            'name' => 'end',
                            'data' => [
                                'label' => 'end_flow',
                                'subtitle' => 'End execution path',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 410,
                            ],
                        ],
                        [
                            'id' => 'node_3',
                            'type' => 'action',
                            'name' => 'email',
                            'data' => [
                                'actionType' => 'send_email',
                                'label' => 'send_email',
                                'subtitle' => 'Subject: Attendee ticket purchase',
                                'receiverType' => 'attendee_email',
                                'from' => 'admin@gmail.com',
                                'subject' => 'You’ve Got Your Ticket!',
                                'body' => 'A ticket has been purchased for you. The details are as follows:',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 230,
                            ],
                        ],
                    ],
                    'edges' => [
                        [
                            'id' => 'edge_node_1-node_3',
                            'type' => 'smoothstep',
                            'markerEnd' => [
                                'type' => 'arrowclosed',
                            ],
                            'source' => 'node_1',
                            'target' => 'node_3',
                            'data' => [],
                        ],
                        [
                            'id' => 'edge_node_3-node_2',
                            'type' => 'smoothstep',
                            'markerEnd' => [
                                'type' => 'arrowclosed',
                            ],
                            'source' => 'node_3',
                            'target' => 'node_2',
                            'data' => [],
                        ],
                    ],
                ],
                'status' => 'publish',
            ],
            [
                'name' => 'Purchase Email Automation For Customer',
                'trigger' => 'event_ticket_purchase',
                'flow_config' => [
                    'nodes' => [
                        [
                            'id' => 'node_1',
                            'type' => 'trigger',
                            'name' => 'trigger',
                            'data' => [
                                'label' => 'trigger: event_ticket_purchase',
                                'subtitle' => 'Event Ticket Purchase',
                                'triggerValue' => 'event_ticket_purchase',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 50,
                            ],
                        ],
                        [
                            'id' => 'node_2',
                            'type' => 'end',
                            'name' => 'end',
                            'data' => [
                                'label' => 'end_flow',
                                'subtitle' => 'End execution path',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 410,
                            ],
                        ],
                        [
                            'id' => 'node_3',
                            'type' => 'action',
                            'name' => 'email',
                            'data' => [
                                'actionType' => 'send_email',
                                'label' => 'send_email',
                                'subtitle' => 'Subject: Customer ticket purchase',
                                'receiverType' => 'customer_email',
                                'from' => 'admin@gmail.com',
                                'subject' => self::CUSTOMER_PURCHASE_SUBJECT,
                                'body' => self::CUSTOMER_PURCHASE_BODY,
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 230,
                            ],
                        ],
                    ],
                    'edges' => [
                        [
                            'id' => 'edge_node_1-node_3',
                            'type' => 'smoothstep',
                            'markerEnd' => [
                                'type' => 'arrowclosed',
                            ],
                            'source' => 'node_1',
                            'target' => 'node_3',
                            'data' => [],
                        ],
                        [
                            'id' => 'edge_node_3-node_2',
                            'type' => 'smoothstep',
                            'markerEnd' => [
                                'type' => 'arrowclosed',
                            ],
                            'source' => 'node_3',
                            'target' => 'node_2',
                            'data' => [],
                        ],
                    ],
                ],
                'status' => 'publish',
            ],
            [
                'name' => 'Purchase Email Automation For Admin',
                'trigger' => 'event_ticket_purchase',
                'flow_config' => [
                    'nodes' => [
                        [
                            'id' => 'node_1',
                            'type' => 'trigger',
                            'name' => 'trigger',
                            'data' => [
                                'label' => 'trigger: event_ticket_purchase',
                                'subtitle' => 'Event Ticket Purchase',
                                'triggerValue' => 'event_ticket_purchase',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 50,
                            ],
                        ],
                        [
                            'id' => 'node_2',
                            'type' => 'end',
                            'name' => 'end',
                            'data' => [
                                'label' => 'end_flow',
                                'subtitle' => 'End execution path',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 410,
                            ],
                        ],
                        [
                            'id' => 'node_3',
                            'type' => 'action',
                            'name' => 'email',
                            'data' => [
                                'actionType' => 'send_email',
                                'label' => 'send_email',
                                'subtitle' => 'Subject: Admin purchase confirmetion',
                                'receiverType' => 'admin_email',
                                'from' => 'admin@gmail.com',
                                'subject' => 'New Event Booking Received',
                                'body' => 'A customer has successfully purchased a ticket. Details are provided below:',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 230,
                            ],
                        ],
                    ],
                    'edges' => [
                        [
                            'id' => 'edge_node_1-node_3',
                            'type' => 'smoothstep',
                            'markerEnd' => [
                                'type' => 'arrowclosed',
                            ],
                            'source' => 'node_1',
                            'target' => 'node_3',
                            'data' => [],
                        ],
                        [
                            'id' => 'edge_node_3-node_2',
                            'type' => 'smoothstep',
                            'markerEnd' => [
                                'type' => 'arrowclosed',
                            ],
                            'source' => 'node_3',
                            'target' => 'node_2',
                            'data' => [],
                        ],
                    ],
                ],
                'status' => 'publish',
            ],
            [
                'name' => 'RSVP Automation',
                'trigger' => 'event_rsvp_email',
                'flow_config' => [
                    'nodes' => [
                        [
                            'id' => 'node_1',
                            'type' => 'trigger',
                            'name' => 'trigger',
                            'data' => [
                                'label' => 'trigger: event_rsvp_email',
                                'subtitle' => 'RSVP Email',
                                'triggerValue' => 'event_rsvp_email',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 50,
                            ],
                        ],
                        [
                            'id' => 'node_2',
                            'type' => 'end',
                            'name' => 'end',
                            'data' => [
                                'label' => 'end_flow',
                                'subtitle' => 'End execution path',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 410,
                            ],
                        ],
                        [
                            'id' => 'node_3',
                            'type' => 'action',
                            'name' => 'email',
                            'data' => [
                                'actionType' => 'send_email',
                                'label' => 'send_email',
                                'subtitle' => 'Subject: RSVP: {%event_title%}',
                                'receiverType' => 'attendee_email',
                                'from' => 'admin@gmail.com',
                                'subject' => 'RSVP: {%event_title%}',
                                'body' => 'You have been invited to attend an event. The details are provided below:',
                            ],
                            'position' => [
                                'x' => 175.74853493583362,
                                'y' => 239.09429939906244,
                            ],
                        ],
                    ],
                    'edges' => [
                        [
                            'id' => 'edge_node_1-node_3',
                            'type' => 'smoothstep',
                            'markerEnd' => [
                                'type' => 'arrowclosed',
                            ],
                            'source' => 'node_1',
                            'target' => 'node_3',
                            'data' => [],
                        ],
                        [
                            'id' => 'edge_node_3-node_2',
                            'type' => 'smoothstep',
                            'markerEnd' => [
                                'type' => 'arrowclosed',
                            ],
                            'source' => 'node_3',
                            'target' => 'node_2',
                            'data' => [],
                        ],
                    ],
                ],
                'status' => 'publish',
            ],
            [
                'name' => 'Event Reminder',
                'trigger' => 'event_reminder_email',
                'flow_config' => [
                    'nodes' => [
                        [
                            'id' => 'node_1',
                            'type' => 'trigger',
                            'name' => 'trigger',
                            'data' => [
                                'label' => 'trigger: event_reminder_email',
                                'subtitle' => 'Event Reminder Email',
                                'triggerValue' => 'event_reminder_email',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 50,
                            ],
                        ],
                        [
                            'id' => 'node_2',
                            'type' => 'end',
                            'name' => 'end',
                            'data' => [
                                'label' => 'end_flow',
                                'subtitle' => 'End execution path',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 590,
                            ],
                        ],
                        [
                            'id' => 'node_3',
                            'type' => 'action',
                            'name' => 'delay',
                            'data' => [
                                'actionType' => 'add_delay',
                                'label' => 'add_delay',
                                'subtitle' => 'Wait for 10 minutes',
                                'delay' => 10,
                                'delayUnit' => 'minutes',
                                'delayCondition' => 'before_event_date',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 230,
                            ],
                        ],
                        [
                            'id' => 'node_4',
                            'type' => 'action',
                            'name' => 'email',
                            'data' => [
                                'actionType' => 'send_email',
                                'label' => 'send_email',
                                'subtitle' => 'Subject: Just a friendly reminder about your upcoming event',
                                'receiverType' => 'attendee_email',
                                'from' => 'eventin@admin.com',
                                'subject' => 'Just a friendly reminder about your upcoming event',
                                'body' => 'This is a gentle reminder that your upcoming event is just around the corner!',
                            ],
                            'position' => [
                                'x' => 200,
                                'y' => 410,
                            ],
                        ],
                    ],
                    'edges' => [
                        [
                            'id' => 'edge_node_1-node_3',
                            'type' => 'smoothstep',
                            'markerEnd' => [
                                'type' => 'arrowclosed',
                            ],
                            'source' => 'node_1',
                            'target' => 'node_3',
                            'data' => [],
                        ],
                        [
                            'id' => 'edge_node_3-node_4',
                            'type' => 'smoothstep',
                            'markerEnd' => [
                                'type' => 'arrowclosed',
                            ],
                            'source' => 'node_3',
                            'target' => 'node_4',
                            'data' => [],
                        ],
                        [
                            'id' => 'edge_node_4-node_2',
                            'type' => 'smoothstep',
                            'markerEnd' => [
                                'type' => 'arrowclosed',
                            ],
                            'source' => 'node_4',
                            'target' => 'node_2',
                            'data' => [],
                        ],
                    ],
                ],
                'status' => 'publish',
            ],
            self::send_certificate_flow_definition(),
            self::reminder_all_attendees_flow_definition(),
        ];
        $automation_flows = array_merge( $automation_flows, self::order_notice_flow_definitions() );
        try {
            foreach ( $automation_flows as $key => $automation_flow ) {
                $flow = new Flow( 'eve',0 );
                $flow->set_props( $automation_flow );
                $flow_id = $flow->save();
                if ( $flow_id ) {
                    $result['flow_ids'][] = $flow_id;
                }
            }

            update_option( 'etn_email_automation_migrated', true );
            // Send Certificate is part of this seed, so mark its dedicated guard too
            // to keep the update back-fill (V_4_1_16) a no-op for new customers.
            update_option( 'etn_send_certificate_automation_migrated', true );
            // Same for the on-demand reminder flow and its back-fill (V_4_1_26).
            update_option( 'etn_reminder_all_attendees_automation_migrated', true );
            // Same for the waiting-list and refund flows and their back-fill.
            update_option( self::ORDER_NOTICE_MIGRATED_OPTION, true );
            // The payment-link flow is part of the same default set.
            update_option( self::PAYMENT_LINK_MIGRATED_OPTION, true );
        } catch ( Exception $e ) {
            $result['errors'][] = 'Failed to create service: ' . $e->getMessage();
        }

        return $result;
    }

    /**
     * Default "Send Certificate" automation flow definition.
     *
     * Shared single source of truth used by both the toggle-on seeder
     * ( create_automation_flows() ) and the update back-fill
     * ( create_send_certificate_flow(), called from the V_4_1_16 upgrader ).
     *
     * Contains exactly the four keys the Flow model persists
     * ( name, trigger, flow_config, status ) — any extra key would be written
     * as junk post-meta by Flow::save_metadata().
     *
     * @return array
     */
    private static function send_certificate_flow_definition() {
        return [
            'name'        => 'Send Certificate',
            'trigger'     => 'send_certificate',
            'flow_config' => [
                'nodes' => [
                    [
                        'id'       => 'node_1',
                        'type'     => 'trigger',
                        'name'     => 'trigger',
                        'data'     => [
                            'label'        => 'trigger: send_certificate',
                            'subtitle'     => 'On "Send Certificate" event fires',
                            'triggerValue' => 'send_certificate',
                        ],
                        'position' => [
                            'x' => 300,
                            'y' => 100,
                        ],
                    ],
                    [
                        'id'       => 'end_1',
                        'type'     => 'end',
                        'name'     => 'end',
                        'data'     => [
                            'label'    => 'end_flow',
                            'subtitle' => 'Automation stops here',
                        ],
                        'position' => [
                            'x' => 318.38084617295419,
                            'y' => 460,
                        ],
                    ],
                    [
                        'id'       => 'node_3',
                        'type'     => 'action',
                        'name'     => 'email',
                        'data'     => [
                            'actionType'            => 'send_email',
                            'label'                 => 'send_email',
                            'subtitle'              => 'Send certificate email to attendee',
                            'operator'              => '=',
                            'value'                 => '',
                            'receiverType'          => 'attendee_email',
                            'from'                  => '',
                            'subject'               => 'Please collect your event certificate ',
                            'body'                  => '<p><br></p>',
                            'processed_session_ids' => [],
                        ],
                        'position' => [
                            'x' => 309.1904230864771,
                            'y' => 280,
                        ],
                    ],
                ],
                'edges' => [
                    [
                        'id'        => 'edge_node_1-node_3',
                        'type'      => 'smoothstep',
                        'markerEnd' => [
                            'type' => 'arrowclosed',
                        ],
                        'source'    => 'node_1',
                        'target'    => 'node_3',
                        'data'      => [
                            'animated' => false,
                        ],
                    ],
                    [
                        'id'        => 'edge_node_3-end_1',
                        'type'      => 'smoothstep',
                        'markerEnd' => [
                            'type' => 'arrowclosed',
                        ],
                        'source'    => 'node_3',
                        'target'    => 'end_1',
                        'data'      => [
                            'animated' => false,
                        ],
                    ],
                ],
            ],
            'status'      => 'publish',
        ];
    }

    /**
     * Default "Send Reminder To All Attendees" automation flow definition.
     *
     * Two things set this flow apart from every other seeded flow, and both are
     * deliberate:
     *
     *  - **No delay node.** The flow is driven by an explicit click in the event
     *    list, not by the event date. A delay would make the click schedule
     *    something instead of sending it, which is exactly the behaviour that
     *    ruled out reusing the scheduled `event_reminder_email` trigger.
     *  - **Status `publish`, not `draft`.** The other flows ship as drafts because
     *    the admin opts into a scheduled automation. Here a draft means the button
     *    warns "publish the reminder flow" on a fresh install and never works
     *    until someone hunts the flow down.
     *
     * Public because it is the single source of truth for the flow's shape; the
     * release test asserts on it directly.
     *
     * Contains exactly the four keys the Flow model persists
     * ( name, trigger, flow_config, status ) — any extra key would be written
     * as junk post-meta by Flow::save_metadata().
     *
     * @return array
     */
    public static function reminder_all_attendees_flow_definition() {
        $trigger = \Eventin\Emails\ReminderAutomation::TRIGGER;

        return [
            'name'        => 'Send Reminder To All Attendees',
            'trigger'     => $trigger,
            'flow_config' => [
                'nodes' => [
                    [
                        'id'       => 'node_1',
                        'type'     => 'trigger',
                        'name'     => 'trigger',
                        'data'     => [
                            'label'        => 'trigger: ' . $trigger,
                            'subtitle'     => 'On "Send Reminder To All Attendees" event fires',
                            'triggerValue' => $trigger,
                        ],
                        'position' => [
                            'x' => 300,
                            'y' => 100,
                        ],
                    ],
                    [
                        'id'       => 'end_1',
                        'type'     => 'end',
                        'name'     => 'end',
                        'data'     => [
                            'label'    => 'end_flow',
                            'subtitle' => 'Automation stops here',
                        ],
                        'position' => [
                            'x' => 318.38084617295419,
                            'y' => 460,
                        ],
                    ],
                    [
                        'id'       => 'node_3',
                        'type'     => 'action',
                        'name'     => 'email',
                        'data'     => [
                            'actionType'            => 'send_email',
                            'label'                 => 'send_email',
                            'subtitle'              => 'Send reminder email to attendee',
                            'operator'              => '=',
                            'value'                 => '',
                            'receiverType'          => 'attendee_email',
                            'from'                  => '',
                            'subject'               => 'Reminder: {%event_title%} is coming up',
                            'body'                  => '<p>Hi {%attendee_name%},</p><p>This is a reminder about "{%event_title%}".</p><p>Date: {%event_date%} at {%event_time%}<br>Location: {%event_location%}</p><p>See you there.</p>',
                            'processed_session_ids' => [],
                        ],
                        'position' => [
                            'x' => 309.1904230864771,
                            'y' => 280,
                        ],
                    ],
                ],
                'edges' => [
                    [
                        'id'        => 'edge_node_1-node_3',
                        'type'      => 'smoothstep',
                        'markerEnd' => [
                            'type' => 'arrowclosed',
                        ],
                        'source'    => 'node_1',
                        'target'    => 'node_3',
                        'data'      => [
                            'animated' => false,
                        ],
                    ],
                    [
                        'id'        => 'edge_node_3-end_1',
                        'type'      => 'smoothstep',
                        'markerEnd' => [
                            'type' => 'arrowclosed',
                        ],
                        'source'    => 'node_3',
                        'target'    => 'end_1',
                        'data'      => [
                            'animated' => false,
                        ],
                    ],
                ],
            ],
            'status'      => 'publish',
        ];
    }

    /**
     * Create only the "Send Reminder To All Attendees" flow.
     *
     * Used by the V_4_1_26 upgrader to back-fill existing customers who already
     * ran the full seeder ( create_automation_flows() ) before this flow existed.
     *
     * @return array { flow_ids: int[], errors: string[] }
     */
    public static function create_reminder_all_attendees_flow() {
        $result = array(
            'flow_ids' => array(),
            'errors'   => array(),
        );

        try {
            $flow = new Flow( 'eve', 0 );
            $flow->set_props( self::reminder_all_attendees_flow_definition() );
            $flow_id = $flow->save();

            if ( $flow_id ) {
                $result['flow_ids'][] = $flow_id;
            }
        } catch ( Exception $e ) {
            $result['errors'][] = 'Failed to create service: ' . $e->getMessage();
        }

        return $result;
    }

    /**
     * Default flows for the waiting-list request and refund emails.
     *
     * Published, because each one replaces an email that is sent today. A draft
     * would stop that email (see Eventin\Emails\OrderNoticeAutomation).
     *
     * The body text is shown inside the same email design as the direct email:
     * the event details (waiting list) and the refund table (refund) are added
     * below it by EnsHooks.
     *
     * @return array[]
     */
    public static function order_notice_flow_definitions() {
        $waiting_list_body = '<p>Hi {%recipient_name%},</p><p>You have been added to the waiting list for {%event_title%}. We will email you as soon as a spot opens up so you can complete your registration.</p>';

        return [
            self::order_notice_flow_definition(
                'Waiting List Request',
                \Eventin\Emails\OrderNoticeAutomation::WAITING_LIST_TRIGGER,
                [
                    [
                        'receiverType' => 'customer_email',
                        'subtitle'     => 'Send waiting list email to customer',
                        'subject'      => "You're on the waiting list for {%event_title%}",
                        'body'         => $waiting_list_body,
                    ],
                    [
                        'receiverType' => 'attendee_email',
                        'subtitle'     => 'Send waiting list email to attendee',
                        'subject'      => "You're on the waiting list for {%event_title%}",
                        'body'         => $waiting_list_body,
                    ],
                ]
            ),
            self::order_notice_flow_definition(
                'Booking Refund',
                \Eventin\Emails\OrderNoticeAutomation::REFUND_TRIGGER,
                [
                    [
                        'receiverType' => 'customer_email',
                        'subtitle'     => 'Send refund email to customer',
                        'subject'      => 'Refund processed for order #{%order_id%}',
                        'body'         => '<p>Hi {%customer_name%},</p><p>Your order #{%order_id%} for {%event_title%} has been fully refunded. Refund amount: {%refund_amount%}. Details below:</p>',
                    ],
                ]
            ),
            self::order_notice_flow_definition(
                'Booking Partial Refund',
                \Eventin\Emails\OrderNoticeAutomation::PARTIAL_REFUND_TRIGGER,
                [
                    [
                        'receiverType' => 'customer_email',
                        'subtitle'     => 'Send partial refund email to customer',
                        'subject'      => 'Partial refund processed for order #{%order_id%}',
                        'body'         => '<p>Hi {%customer_name%},</p><p>A partial refund of {%refund_amount%} has been processed for your order #{%order_id%} for {%event_title%}. Details below:</p>',
                    ],
                ]
            ),
            self::payment_link_flow_definition(),
        ];
    }

    /**
     * Default "Waiting List Payment Link" flow: same subject and intro as the old direct email.
     *
     * @return array
     */
    public static function payment_link_flow_definition() {
        return self::order_notice_flow_definition(
            'Waiting List Payment Link',
            \Eventin\Emails\OrderNoticeAutomation::PAYMENT_LINK_TRIGGER,
            [
                [
                    'receiverType' => 'customer_email',
                    'subtitle'     => 'Send payment link to customer',
                    'subject'      => 'Your spot is ready — complete your payment for {%event_title%}',
                    'body'         => '<p>Hi {%customer_name%}, great news — a ticket for {%event_title%} is now available. Click the button below to review your order and complete payment before the spot is taken.</p>',
                ],
            ]
        );
    }

    /**
     * Add the payment-link flow to a site that already has the other default flows, once.
     *
     * Its own option, so a site that deleted one of the other default flows
     * does not get that flow back.
     *
     * @return void
     */
    public static function create_payment_link_flow() {
        if ( get_option( self::PAYMENT_LINK_MIGRATED_OPTION ) ) {
            return;
        }

        if ( ! \Eventin\Emails\OrderNoticeAutomation::has_flow( \Eventin\Emails\OrderNoticeAutomation::PAYMENT_LINK_TRIGGER ) ) {
            $flow = new Flow( 'eve', 0 );
            $flow->set_props( self::payment_link_flow_definition() );
            $flow->save();
        }

        update_option( self::PAYMENT_LINK_MIGRATED_OPTION, true );
    }

    /**
     * Build one flow: trigger → email step(s) in order → end.
     *
     * Contains exactly the four keys the Flow model persists
     * ( name, trigger, flow_config, status ).
     *
     * @param string $name
     * @param string $trigger
     * @param array  $emails  Each: receiverType, subtitle, subject, body.
     *
     * @return array
     */
    private static function order_notice_flow_definition( $name, $trigger, $emails ) {
        $nodes = [
            [
                'id'       => 'node_1',
                'type'     => 'trigger',
                'name'     => 'trigger',
                'data'     => [
                    'label'        => 'trigger: ' . $trigger,
                    'subtitle'     => 'On "' . $name . '" event fires',
                    'triggerValue' => $trigger,
                ],
                'position' => [ 'x' => 300, 'y' => 100 ],
            ],
        ];
        $edges    = [];
        $previous = 'node_1';
        $y        = 280;

        foreach ( $emails as $index => $email ) {
            $id = 'node_' . ( $index + 2 );

            $nodes[] = [
                'id'       => $id,
                'type'     => 'action',
                'name'     => 'email',
                'data'     => [
                    'actionType'            => 'send_email',
                    'label'                 => 'send_email',
                    'subtitle'              => $email['subtitle'],
                    'operator'              => '=',
                    'value'                 => '',
                    'receiverType'          => $email['receiverType'],
                    'from'                  => '',
                    'subject'               => $email['subject'],
                    'body'                  => $email['body'],
                    'processed_session_ids' => [],
                ],
                'position' => [ 'x' => 300, 'y' => $y ],
            ];
            $edges[] = self::order_notice_edge( $previous, $id );

            $previous = $id;
            $y       += 180;
        }

        $nodes[] = [
            'id'       => 'end_1',
            'type'     => 'end',
            'name'     => 'end',
            'data'     => [
                'label'    => 'end_flow',
                'subtitle' => 'Automation stops here',
            ],
            'position' => [ 'x' => 300, 'y' => $y ],
        ];
        $edges[] = self::order_notice_edge( $previous, 'end_1' );

        return [
            'name'        => $name,
            'trigger'     => $trigger,
            'flow_config' => [
                'nodes' => $nodes,
                'edges' => $edges,
            ],
            'status'      => 'publish',
        ];
    }

    /**
     * One flow edge between two nodes.
     *
     * @param string $source
     * @param string $target
     *
     * @return array
     */
    private static function order_notice_edge( $source, $target ) {
        return [
            'id'        => 'edge_' . $source . '-' . $target,
            'type'      => 'smoothstep',
            'markerEnd' => [
                'type' => 'arrowclosed',
            ],
            'source'    => $source,
            'target'    => $target,
            'data'      => [
                'animated' => false,
            ],
        ];
    }

    /**
     * Create the waiting-list and refund flows on a site that already has the
     * other default flows (they were created before these flows existed).
     *
     * Skips a trigger that already has a flow — the admin may have built one
     * by hand. Two flows on one trigger would send every email twice.
     *
     * @return array { flow_ids: int[], errors: string[] }
     */
    public static function create_order_notice_flows() {
        $result = array(
            'flow_ids' => array(),
            'errors'   => array(),
        );

        try {
            foreach ( self::order_notice_flow_definitions() as $definition ) {
                if ( \Eventin\Emails\OrderNoticeAutomation::has_flow( $definition['trigger'] ) ) {
                    continue;
                }

                $flow = new Flow( 'eve', 0 );
                $flow->set_props( $definition );
                $flow_id = $flow->save();

                if ( $flow_id ) {
                    $result['flow_ids'][] = $flow_id;
                }
            }

            update_option( self::ORDER_NOTICE_MIGRATED_OPTION, true );
            // The payment-link flow is part of the same default set.
            update_option( self::PAYMENT_LINK_MIGRATED_OPTION, true );
        } catch ( Exception $e ) {
            $result['errors'][] = 'Failed to create service: ' . $e->getMessage();
        }

        return $result;
    }

    /**
     * Create only the "Send Certificate" flow.
     *
     * Used by the V_4_1_16 upgrader to back-fill existing customers who already
     * ran the full seeder ( create_automation_flows() ) before this flow existed.
     *
     * @return array { flow_ids: int[], errors: string[] }
     */
    public static function create_send_certificate_flow() {
        $result = array(
            'flow_ids' => array(),
            'errors'   => array(),
        );

        try {
            $flow = new Flow( 'eve', 0 );
            $flow->set_props( self::send_certificate_flow_definition() );
            $flow_id = $flow->save();

            if ( $flow_id ) {
                $result['flow_ids'][] = $flow_id;
            }
        } catch ( Exception $e ) {
            $result['errors'][] = 'Failed to create service: ' . $e->getMessage();
        }

        return $result;
    }

    /**
     * Give existing sites the new customer purchase mail wording.
     *
     * Only the customer node of a flow that still has the old shipped subject AND
     * body is changed. If the admin edited either one, the flow is left alone.
     *
     * @return int Number of flows updated.
     */
    public static function refresh_customer_purchase_flows() {
        if ( ! class_exists( Flow::class ) ) {
            return 0;
        }

        $flow_model = new Flow( 'eve', 0 );
        $found      = $flow_model->all( [
            'trigger'        => 'event_ticket_purchase',
            'posts_per_page' => -1,
            'post_status'    => 'any',
        ] );

        $updated = 0;

        foreach ( $found['items'] as $post ) {
            $flow   = new Flow( 'eve', $post->ID );
            $config = $flow->get_flow_config();

            if ( empty( $config['nodes'] ) || ! is_array( $config['nodes'] ) ) {
                continue;
            }

            $changed = false;

            foreach ( $config['nodes'] as $i => $node ) {
                $data = $node['data'] ?? [];

                if ( ( $data['receiverType'] ?? '' ) !== 'customer_email' ) {
                    continue;
                }

                // The editor may wrap the body in <p>, so compare the plain text.
                $same_subject = trim( (string) ( $data['subject'] ?? '' ) ) === self::LEGACY_CUSTOMER_PURCHASE_SUBJECT;
                $same_body    = trim( wp_strip_all_tags( (string) ( $data['body'] ?? '' ) ) ) === self::LEGACY_CUSTOMER_PURCHASE_BODY;

                if ( ! $same_subject || ! $same_body ) {
                    continue;
                }

                $config['nodes'][ $i ]['data']['subject'] = self::CUSTOMER_PURCHASE_SUBJECT;
                $config['nodes'][ $i ]['data']['body']    = self::CUSTOMER_PURCHASE_BODY;
                $changed = true;
            }

            if ( $changed ) {
                update_post_meta( $post->ID, $flow->prefix . 'flow_config', $config );
                $updated++;
            }
        }

        return $updated;
    }

    /**
     * update automation flow
     */
    public static function update_automation_flows() {
        $result = array(
            'flow_ids' => array(),
            'errors'   => array(),
        );

        $automation_flows = [
            [
                "name" => "Send Email To All Attendees",
                "trigger" => "send_email_to_all_attendees",
                "flow_config" => [
                    "nodes" => [
                        [
                            "id" => "node_1",
                            "type" => "trigger",
                            "name" => "trigger",
                            "data" => [
                                "label" => "trigger: send_email_to_all_attendees",
                                "subtitle" => "Send Email To All Attendees",
                                "triggerValue" => "send_email_to_all_attendees"
                            ],
                            "position" => [
                                "x" => 200,
                                "y" => 50
                            ]
                        ],
                        [
                            "id" => "node_2",
                            "type" => "end",
                            "name" => "end",
                            "data" => [
                                "label" => "end_flow",
                                "subtitle" => "End execution path"
                            ],
                            "position" => [
                                "x" => 221.46762850701441,
                                "y" => 410
                            ]
                        ],
                        [
                            "id" => "node_3",
                            "type" => "action",
                            "name" => "email",
                            "data" => [
                                "actionType" => "send_email",
                                "label" => "send_email",
                                "subtitle" => "Subject: Thanks for Joining Us -  Here’...",
                                "receiverType" => "attendee_email",
                                "from" => "admin@example.com",
                                "subject" => "Thanks for Joining Us -  Here’s What’s Next",
                                "body" => "<p>Hi Attendees ,<\/p><p>Thanks for attending \"{%event_title%}\". We appreciate your time and hope the session delivered real value.<\/p><p>&nbsp;<\/p><p>If you would like updates on future sessions or have feedback, reply directly to this email. we review every message.<\/p><p>&nbsp;<\/p><p>Thanks again,<\/p>",
                                "processed_session_ids" => []
                            ],
                            "position" => [
                                "x" => 210.73381425350721,
                                "y" => 230
                            ]
                        ]
                    ],
                    "edges" => [
                        [
                            "id" => "edge_node_1-node_3",
                            "type" => "smoothstep",
                            "markerEnd" => [
                                "type" => "arrowclosed"
                            ],
                            "source" => "node_1",
                            "target" => "node_3",
                            "data" => []
                        ],
                        [
                            "id" => "edge_node_3-node_2",
                            "type" => "smoothstep",
                            "markerEnd" => [
                                "type" => "arrowclosed"
                            ],
                            "source" => "node_3",
                            "target" => "node_2",
                            "data" => []
                        ]
                    ]
                ],
                "status" => "draft"
            ],
        ];
        try {
            foreach ( $automation_flows as $key => $automation_flow ) {                
                $flow = new Flow( 'eve',0 );
                $flow->set_props( $automation_flow );
                $flow_id = $flow->save();
                if ( $flow_id ) {
                    $result['flow_ids'][] = $flow_id;
                }
            }

        } catch ( Exception $e ) {
            $result['errors'][] = 'Failed to create service: ' . $e->getMessage();
        }

        return $result;
    }
}
