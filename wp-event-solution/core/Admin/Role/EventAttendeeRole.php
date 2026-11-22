<?php
/**
 * Event Attendee Role Class
 */
namespace Eventin\Admin\Role;

defined( 'ABSPATH' ) || exit;

/**
 * Event attendee role class
 */
class EventAttendeeRole implements RoleInterfacace {
    /**
     * Get role name
     *
     * @return  string
     */
    public function get_name() {
        return 'event_attendee';
    }

    /**
     * Get role display name
     *
     * @return  string
     */
    public function get_display_name() {
        return __( 'Event Attendee', 'wp-event-solution' );
    }

    /**
     * Get all capabilities a role
     *
     * @return  array Event attendee role capabilities
     */
    public function get_capabilities() {
        return [
            'read' => true,
        ];
    }

    /**
     * Add the role if it does not exist. Safe to call many times.
     *
     * @return  void
     */
    public static function ensure(): void {
        $role = new self();

        if ( null === get_role( $role->get_name() ) ) {
            add_role( $role->get_name(), $role->get_display_name(), $role->get_capabilities() );
        }
    }
}
