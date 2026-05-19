<?php
/**
 * Updater for version 4.1.13
 *
 * @package Eventin\Upgrade
 */

namespace Eventin\Upgrade\Upgraders;

defined( 'ABSPATH' ) || exit;

/**
 * Updater class for v4.1.13
 *
 * Strips the etn_manage_order capability from the etn-customer role on
 * existing installs. The cap was previously granted by default
 * (Permission::default_role_permissions()) which let any customer hit
 * admin-only order endpoints such as /orders/export.
 *
 * @since 4.1.13
 */
class V_4_1_13 implements UpdateInterface {
    /**
     * Run the updater
     *
     * @return void
     */
    public function run() {
        $permissions = get_option( 'etn_permissions' );

        if ( ! is_array( $permissions ) || empty( $permissions['etn-customer'] ) ) {
            return;
        }

        $customer_perms = (array) $permissions['etn-customer'];
        $filtered       = array_values( array_filter( $customer_perms, function ( $cap ) {
            return 'etn_manage_order' !== $cap;
        } ) );

        if ( $filtered === $customer_perms ) {
            return;
        }

        $permissions['etn-customer'] = $filtered;

        update_option( 'etn_permissions', $permissions );
    }
}
