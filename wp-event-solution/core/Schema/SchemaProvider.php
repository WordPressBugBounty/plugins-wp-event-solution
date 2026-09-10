<?php
/**
 * Registers the structured-data subsystem.
 *
 * @package Eventin\Schema
 */

namespace Eventin\Schema;

defined( 'ABSPATH' ) || exit;

use Eventin\Abstracts\Provider;

/**
 * Boots the schema.org / JSON-LD output for events.
 */
class SchemaProvider extends Provider {

    /**
     * Services to instantiate.
     *
     * @var array
     */
    protected $services = [
        Printer::class,
    ];
}
