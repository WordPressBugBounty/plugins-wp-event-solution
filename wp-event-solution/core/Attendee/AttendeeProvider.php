<?php

namespace Eventin\Attendee;

defined( 'ABSPATH' ) || exit;

use Eventin\Abstracts\Provider;
use Eventin\Attendee\Account\AttendeeAccountService;
use Eventin\Attendee\Account\AttendeeAccountSettings;

/**
 * Attendee Provider
 */
class AttendeeProvider extends Provider {
    /**
     * Store attendee related services
     *
     * @var array
     */
    protected $services = [
        TicketTemplate::class,
        AttendeeAccountSettings::class,
        AttendeeAccountService::class,
    ];
}