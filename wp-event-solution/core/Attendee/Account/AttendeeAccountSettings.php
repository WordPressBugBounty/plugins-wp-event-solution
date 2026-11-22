<?php
/**
 * Attendee account setting.
 *
 * When "Create User Account for Attendees" is on, the attendee email field
 * must be shown and required. This class enforces that on the server.
 */
namespace Eventin\Attendee\Account;

defined( 'ABSPATH' ) || exit;

use Eventin\Interfaces\HookableInterface;

/**
 * Attendee account settings class
 */
class AttendeeAccountSettings implements HookableInterface {
    /**
     * Option that stores the email field before it was forced.
     */
    const BACKUP_OPTION = 'etn_attendee_email_field_backup';

    /**
     * Register hooks
     *
     * @return void
     */
    public function register_hooks(): void {
        add_filter( 'eventin_settings_update_params', [ $this, 'enforce_email_field' ], 10, 1 );
        add_action( 'update_option_etn_event_options', [ $this, 'maybe_fire_enabled_action' ], 10, 2 );
        add_action( 'add_option_etn_event_options', [ $this, 'maybe_fire_on_first_save' ], 10, 2 );
    }

    /**
     * Check if attendee accounts are enabled
     *
     * @return bool
     */
    public static function is_enabled(): bool {
        return 'on' === etn_get_option( 'attendee_create_user' );
    }

    /**
     * Lock the email field while the setting is on, restore it when turned off.
     *
     * @param array|\WP_Error $params Submitted settings.
     *
     * @return array|\WP_Error
     */
    public function enforce_email_field( $params ) {
        if ( ! is_array( $params ) ) {
            return $params;
        }

        $was_on = self::is_enabled();
        $is_on  = array_key_exists( 'attendee_create_user', $params )
            ? 'on' === $params['attendee_create_user']
            : $was_on;

        $has_fields = array_key_exists( 'default_extra_fields', $params ) && is_array( $params['default_extra_fields'] );
        $fields     = $has_fields ? $params['default_extra_fields'] : etn_get_option( 'default_extra_fields', [] );
        $fields     = is_array( $fields ) ? $fields : [];
        $index      = $this->find_email_index( $fields );

        if ( $is_on ) {
            if ( null !== $index ) {
                if ( ! $was_on || false === get_option( self::BACKUP_OPTION, false ) ) {
                    // Back up the STORED email item. The form may already send it forced on.
                    $stored       = etn_get_option( 'default_extra_fields', [] );
                    $stored       = is_array( $stored ) ? $stored : [];
                    $stored_index = $this->find_email_index( $stored );
                    $source       = null !== $stored_index ? $stored[ $stored_index ] : $fields[ $index ];

                    update_option( self::BACKUP_OPTION, $source, false );
                }

                $fields[ $index ]['show']     = '1';
                $fields[ $index ]['required'] = '1';

                $params['default_extra_fields'] = $fields;
            } elseif ( empty( $fields ) ) {
                // Legacy sites keep the email rule in a separate toggle.
                $params['reg_require_email'] = 'on';
            }

            return $params;
        }

        if ( $was_on ) {
            $backup = get_option( self::BACKUP_OPTION, false );

            if ( is_array( $backup ) && null !== $index ) {
                // Restore only show and required. Keep the label the admin sent.
                foreach ( [ 'show', 'required' ] as $key ) {
                    if ( array_key_exists( $key, $backup ) ) {
                        $fields[ $index ][ $key ] = $backup[ $key ];
                    }
                }

                $params['default_extra_fields'] = $fields;
            }

            delete_option( self::BACKUP_OPTION );
        }

        return $params;
    }

    /**
     * Fire an action when the setting changes from off to on.
     *
     * @param mixed $old_value Old settings.
     * @param mixed $new_value New settings.
     *
     * @return void
     */
    public function maybe_fire_enabled_action( $old_value, $new_value ): void {
        $old = is_array( $old_value ) && isset( $old_value['attendee_create_user'] ) ? $old_value['attendee_create_user'] : '';
        $new = is_array( $new_value ) && isset( $new_value['attendee_create_user'] ) ? $new_value['attendee_create_user'] : '';

        if ( 'on' !== $old && 'on' === $new ) {
            /**
             * Fires once when attendee user accounts are turned on.
             */
            do_action( 'eventin_attendee_create_user_enabled' );
        }
    }

    /**
     * Handle the first save, when the option did not exist yet.
     *
     * @param string $option Option name.
     * @param mixed  $value  New settings.
     *
     * @return void
     */
    public function maybe_fire_on_first_save( $option, $value ): void {
        $this->maybe_fire_enabled_action( [], $value );
    }

    /**
     * Find the email item in the default fields, by name.
     *
     * @param array $fields Default extra fields.
     *
     * @return int|string|null
     */
    private function find_email_index( array $fields ) {
        foreach ( $fields as $index => $field ) {
            if ( is_array( $field ) && isset( $field['name'] ) && 'etn_email' === $field['name'] ) {
                return $index;
            }
        }

        return null;
    }
}
