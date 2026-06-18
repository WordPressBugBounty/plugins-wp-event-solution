<?php

namespace Eventin\Reports;

defined( 'ABSPATH' ) || exit;

use Eventin\Abstracts\Provider;

/**
 * Registers report-related services.
 *
 * @package Eventin\Reports
 */
class ReportProvider extends Provider {
    /**
     * Services to boot. ReportCache implements HookableInterface, so the base
     * provider calls register_hooks() on it.
     *
     * @var array
     */
    protected $services = [
        ReportCache::class,
    ];
}
