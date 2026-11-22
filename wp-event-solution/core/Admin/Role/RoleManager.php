<?php

namespace Eventin\Admin\Role;

defined( 'ABSPATH' ) || exit;
use Eventin\Interfaces\HookableInterface;

/**
 * Role Manager class
 * 
 * @package Eventin
 */

class RoleManager implements HookableInterface {
    /**
     * Store all roles
     *
     * @var array
     */
    private $roles = [
        CustomerRole::class,
        EventAttendeeRole::class,
    ];

    /**
     * Register all hooks
     *
     * @return  void 
     */
    public function register_hooks(): void {
        add_action( 'admin_init', [ $this, 'register_role' ] );
        add_action( 'profile_update', [ $this, 'restore_roles_after_profile_save' ], 10, 2 );
    }

    /**
     * Give back Eventin roles that the user edit screen dropped.
     * Core shows one role select and set_role() removes every other role.
     *
     * @param int     $user_id       User id.
     * @param WP_User $old_user_data User before the update.
     *
     * @return void
     */
    public function restore_roles_after_profile_save( $user_id, $old_user_data ) {
        if ( ! did_action( 'edit_user_profile_update' ) && ! did_action( 'personal_options_update' ) ) {
            return;
        }

        // Core already checked the nonce. Empty role = admin picked "No role".
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
        if ( empty( $_POST['role'] ) ) {
            return;
        }

        $user = get_userdata( $user_id );

        if ( ! $user || empty( $old_user_data->roles ) ) {
            return;
        }

        foreach ( $this->roles as $role ) {
            $role_name = ( new $role() )->get_name();

            if ( in_array( $role_name, $old_user_data->roles, true ) && ! in_array( $role_name, $user->roles, true ) ) {
                $user->add_role( $role_name );
            }
        }
    }

    /**
     * Register all roles
     *
     * @return  void
     */
    public function register_role() {
        foreach ( $this->roles as $role ) {

            $role_object = new $role();
            $role_name   = $role_object->get_name();
            if ( ! $this->is_role_exist( $role_name ) ) {
                add_role(
                    $role_object->get_name(),
                    $role_object->get_display_name(),
                    $role_object->get_capabilities(),
                );
            }
        }
    }

    /**
     * Check a role is exist or not
     *
     * @param   string  $role_name Role name, that is need to check
     *
     * @return  bool
     */
    private function is_role_exist( $role_name ) {
        $role = get_role( $role_name );

        if ( $role ) {
            return true;
        }

        return false;
    }
}
