<?php

namespace Eventin\Emails;

defined( 'ABSPATH' ) || exit;

use Eventin\Abstracts\Provider;
use Eventin\Emails\EnsHooks;
use Eventin\Emails\InlineImageHandler;

/**
 * Speaker Provider Class
 */
class EmailHookProvider extends Provider {
    /**
     * Store all services
     *
     * @var array
     */
    protected $services = [
        EnsHooks::class,
        InlineImageHandler::class,
    ];
}
