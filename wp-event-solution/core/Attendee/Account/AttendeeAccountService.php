<?php
/**
 * Attendee account service.
 *
 * When "Create User Account for Attendees" is on, every attendee of a
 * completed order gets a WordPress user with the event_attendee role.
 * New users get the "set your password" email once.
 */
namespace Eventin\Attendee\Account;

defined( 'ABSPATH' ) || exit;

use Eventin\Admin\Role\EventAttendeeRole;
use Eventin\Interfaces\HookableInterface;
use Eventin\Order\OrderModel;

/**
 * Attendee account service class
 */
class AttendeeAccountService implements HookableInterface {
    /**
     * Role given to every attendee user.
     */
    const ROLE = 'event_attendee';

    /**
     * User meta: Eventin made this user.
     */
    const CREATED_META = 'etn_created_by_eventin';

    /**
     * User meta: time the password email was sent.
     */
    const EMAIL_SENT_META = 'etn_password_email_sent';

    /**
     * Cron hook: send the password email to one batch of users.
     */
    const EMAIL_BATCH_HOOK = 'eventin_attendee_account_email_batch';

    /**
     * Users emailed in one cron run.
     */
    const EMAIL_BATCH_SIZE = 50;

    /**
     * Cron hook: add the role to one batch of old attendees.
     */
    const BACKFILL_HOOK = 'eventin_attendee_role_backfill';

    /**
     * Users that get the role in one cron run.
     */
    const BACKFILL_BATCH_SIZE = 200;

    /**
     * Register hooks
     *
     * @return void
     */
    public function register_hooks(): void {
        // Payment and WooCommerce completion.
        add_action( 'eventin_order_completed', [ $this, 'handle_order' ] );
        // Admin changes the booking status to completed.
        add_action( 'eventin_order_status_completed', [ $this, 'handle_order' ] );
        // Staff creates an order that is completed from the start.
        add_action( 'eventin_after_order_create', [ $this, 'handle_created_order' ], 10, 1 );
        // Admin adds a single attendee.
        add_action( 'eventin_attendee_created', [ $this, 'handle_created_attendee' ], 10, 1 );
        // Admin imports attendees from a file.
        add_action( 'eventin_attendees_imported', [ $this, 'handle_imported_attendees' ], 10, 2 );
        // Cron: send the password emails of an import in batches.
        add_action( self::EMAIL_BATCH_HOOK, [ $this, 'send_email_batch' ], 10, 1 );
        // Admin turns the setting on: old attendees get the role.
        add_action( 'eventin_attendee_create_user_enabled', [ $this, 'schedule_role_backfill' ] );
        // Cron: add the role to old attendees in batches.
        add_action( self::BACKFILL_HOOK, [ $this, 'backfill_roles_batch' ], 10, 1 );
    }

    /**
     * Queue the role backfill for old attendees
     *
     * @return void
     */
    public function schedule_role_backfill(): void {
        if ( ! wp_next_scheduled( self::BACKFILL_HOOK, [ 0 ] ) ) {
            wp_schedule_single_event( time() + 5, self::BACKFILL_HOOK, [ 0 ] );
        }
    }

    /**
     * Add the event_attendee role to one batch of users that match old attendees
     *
     * One query returns the user ids. It starts from postmeta (meta_key is
     * indexed) and joins users on user_email (indexed). Columns are not
     * wrapped in LOWER() or TRIM() so the indexes stay in use. The email
     * column has a case-insensitive collation, so letter case already
     * matches. An email with stray spaces in the attendee meta will not
     * match. This is acceptable.
     *
     * Only users that already exist get the role. No user is made, no email is
     * sent, and no other role is changed.
     *
     * @param int $last_user_id Highest user id handled by the earlier run.
     *
     * @return void
     */
    public function backfill_roles_batch( $last_user_id = 0 ): void {
        global $wpdb;

        if ( ! AttendeeAccountSettings::is_enabled() ) {
            return;
        }

        $last_user_id = max( 0, (int) $last_user_id );

        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
        $user_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT u.ID FROM {$wpdb->postmeta} m
                INNER JOIN {$wpdb->posts} p ON p.ID = m.post_id AND p.post_type = 'etn-attendee' AND p.post_status = 'publish'
                INNER JOIN {$wpdb->users} u ON u.user_email = m.meta_value
                WHERE m.meta_key = 'etn_email' AND u.ID > %d
                ORDER BY u.ID ASC LIMIT %d",
                $last_user_id,
                self::BACKFILL_BATCH_SIZE
            )
        );
        // phpcs:enable

        if ( ! $user_ids ) {
            return;
        }

        EventAttendeeRole::ensure();

        foreach ( $user_ids as $user_id ) {
            $user = get_userdata( (int) $user_id );

            if ( $user ) {
                $user->add_role( self::ROLE );
            }
        }

        // Only chain another batch if this one filled up.
        if ( count( $user_ids ) === self::BACKFILL_BATCH_SIZE ) {
            $next = [ (int) end( $user_ids ) ];

            if ( ! wp_next_scheduled( self::BACKFILL_HOOK, $next ) ) {
                wp_schedule_single_event( time() + 5, self::BACKFILL_HOOK, $next );
            }
        }
    }

    /**
     * Check if the service should make accounts
     *
     * @return bool
     */
    public static function should_run(): bool {
        return AttendeeAccountSettings::is_enabled()
            || ( 'on' !== etn_get_option( 'attendee_registration' )
                && apply_filters( 'eventin_create_buyer_attendee', false, null, null ) );
    }

    /**
     * Make accounts for an order created with status completed
     *
     * @param OrderModel $order Order.
     *
     * @return void
     */
    public function handle_created_order( $order ): void {
        if ( ! $order instanceof OrderModel || 'completed' !== $order->status ) {
            return;
        }

        $this->handle_order( $order );
    }

    /**
     * Make an account for an attendee added by an admin
     *
     * @param \Etn\Core\Attendee\Attendee_Model $attendee Attendee.
     *
     * @return void
     */
    public function handle_created_attendee( $attendee ): void {
        if ( ! AttendeeAccountSettings::is_enabled() || ! is_object( $attendee ) || empty( $attendee->id ) ) {
            return;
        }

        $id = (int) $attendee->id;

        if ( 'success' !== get_post_meta( $id, 'etn_status', true ) || 'publish' !== get_post_status( $id ) ) {
            return;
        }

        $this->ensure_account(
            (string) get_post_meta( $id, 'etn_email', true ),
            (string) get_post_meta( $id, 'etn_name', true ),
            true
        );
    }

    /**
     * Make accounts for imported attendees and queue the password emails
     *
     * Accounts are made at once, without email. The email is sent later in
     * batches, and only when the admin ticked the checkbox.
     *
     * @param int[] $attendee_ids Imported attendee post ids.
     * @param bool  $send_email   Admin asked to send the account email.
     *
     * @return void
     */
    public function handle_imported_attendees( $attendee_ids, $send_email = false ): void {
        if ( ! AttendeeAccountSettings::is_enabled() ) {
            return;
        }

        $seen     = [];
        $user_ids = [];

        foreach ( (array) $attendee_ids as $attendee_id ) {
            $email = strtolower( trim( (string) get_post_meta( (int) $attendee_id, 'etn_email', true ) ) );

            if ( '' === $email || isset( $seen[ $email ] ) ) {
                continue;
            }

            $seen[ $email ] = true;

            $user_id = $this->ensure_account( $email, (string) get_post_meta( (int) $attendee_id, 'etn_name', true ), false );

            if ( $user_id && $this->can_get_password_email( $user_id ) ) {
                $user_ids[] = $user_id;
            }
        }

        if ( $send_email && $user_ids ) {
            $this->schedule_email_batch( $user_ids, 5 );
        }
    }

    /**
     * Send the password email to one batch of users and queue the rest
     *
     * @param int[] $user_ids User ids.
     *
     * @return void
     */
    public function send_email_batch( $user_ids ): void {
        $user_ids = array_values( array_map( 'intval', (array) $user_ids ) );
        $batch    = array_slice( $user_ids, 0, self::EMAIL_BATCH_SIZE );
        $rest     = array_slice( $user_ids, self::EMAIL_BATCH_SIZE );

        foreach ( $batch as $user_id ) {
            if ( $this->can_get_password_email( $user_id ) ) {
                $this->send_password_email( $user_id );
            }
        }

        if ( $rest ) {
            $this->schedule_email_batch( $rest, 30 );
        }
    }

    /**
     * Queue a batch of password emails
     *
     * @param int[] $user_ids User ids.
     * @param int   $delay    Seconds to wait.
     *
     * @return void
     */
    private function schedule_email_batch( array $user_ids, int $delay ): void {
        $args = [ $user_ids ];

        if ( ! wp_next_scheduled( self::EMAIL_BATCH_HOOK, $args ) ) {
            wp_schedule_single_event( time() + $delay, self::EMAIL_BATCH_HOOK, $args );
        }
    }

    /**
     * Check if the user may get the password email
     *
     * Only users Eventin made, and only once.
     *
     * @param int $user_id User id.
     *
     * @return bool
     */
    private function can_get_password_email( int $user_id ): bool {
        return (bool) get_user_meta( $user_id, self::CREATED_META, true )
            && ! get_user_meta( $user_id, self::EMAIL_SENT_META, true );
    }

    /**
     * Make accounts for every attendee of a completed order
     *
     * @param OrderModel $order Order.
     *
     * @return void
     */
    public function handle_order( $order ): void {
        if ( ! self::should_run() || ! $order instanceof OrderModel ) {
            return;
        }

        $seen = [];

        foreach ( (array) $order->get_attendees() as $attendee ) {
            $id     = (int) ( $attendee['id'] ?? 0 );
            $status = $attendee['attendee_post_status'] ?? get_post_status( $id );

            if ( 'publish' !== $status ) {
                continue;
            }

            $email = strtolower( trim( (string) ( $attendee['etn_email'] ?? '' ) ) );

            if ( '' === $email || isset( $seen[ $email ] ) ) {
                continue;
            }

            $seen[ $email ] = true;

            $this->ensure_account( $email, (string) ( $attendee['etn_name'] ?? '' ), true );
        }
    }

    /**
     * Find or make the user for an email and add the event_attendee role
     *
     * @param string $email      Attendee email.
     * @param string $name       Attendee full name.
     * @param bool   $send_email Send the password email when allowed.
     *
     * @return int|null User id, or null on failure.
     */
    public function ensure_account( string $email, string $name, bool $send_email ): ?int {
        $email = strtolower( trim( $email ) );

        if ( ! is_email( $email ) ) {
            return null;
        }

        EventAttendeeRole::ensure();

        $user = get_user_by( 'email', $email );

        if ( $user ) {
            $user->add_role( self::ROLE );

            if ( $send_email && $this->can_get_password_email( (int) $user->ID ) ) {
                $this->send_password_email( $user->ID );
            }

            return (int) $user->ID;
        }

        $parts = preg_split( '/\s+/', trim( $name ), 2 );

        $user_id = wp_insert_user( [
            'user_login' => $this->unique_login( $email ),
            'user_email' => $email,
            'user_pass'  => wp_generate_password( 24, true, true ),
            'first_name' => $parts[0] ?? '',
            'last_name'  => $parts[1] ?? '',
            'role'       => 'subscriber',
        ] );

        if ( is_wp_error( $user_id ) ) {
            $this->log( 'could not create user for ' . $email . ': ' . $user_id->get_error_message() );
            return null;
        }

        $user = new \WP_User( $user_id );
        $user->add_role( self::ROLE );
        update_user_meta( $user_id, self::CREATED_META, 1 );

        if ( $send_email ) {
            $this->send_password_email( $user_id );
        }

        return (int) $user_id;
    }

    /**
     * Send the "set your password" email and remember it was sent
     *
     * @param int $user_id User id.
     *
     * @return void
     */
    private function send_password_email( int $user_id ): void {
        wp_new_user_notification( $user_id, null, 'user' );
        update_user_meta( $user_id, self::EMAIL_SENT_META, current_time( 'mysql' ) );
    }

    /**
     * Make a free user login from the email local part
     *
     * @param string $email Email.
     *
     * @return string
     */
    private function unique_login( string $email ): string {
        $base = sanitize_user( substr( $email, 0, (int) strpos( $email, '@' ) ), true );
        $base = substr( $base, 0, 50 );

        if ( '' === $base ) {
            $base = 'attendee';
        }

        $login  = $base;
        $suffix = 2;

        while ( username_exists( $login ) ) {
            $login = $base . '-' . $suffix;
            $suffix++;
        }

        return $login;
    }

    /**
     * Write a log line
     *
     * @param string $message Message.
     *
     * @return void
     */
    private function log( string $message ): void {
        error_log( '[eventin] attendee account: ' . $message ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
    }
}
