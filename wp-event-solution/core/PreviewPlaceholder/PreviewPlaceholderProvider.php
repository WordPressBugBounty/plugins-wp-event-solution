<?php
namespace Eventin\PreviewPlaceholder;

defined( 'ABSPATH' ) || exit;

use Eventin\Abstracts\Provider;

/**
 * Registers the preview-placeholder front-end visibility hooks.
 */
class PreviewPlaceholderProvider extends Provider {
    protected $services = [
        PreviewPlaceholderVisibility::class,
    ];
}
