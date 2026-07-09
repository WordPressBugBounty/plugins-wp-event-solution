<?php
namespace Eventin\Vendor\Ens\Core;

use Eventin\Vendor\Ens\Assets\Enqueue;
use Eventin\Vendor\Ens\Flow\FlowAPI;
use Eventin\Vendor\Ens\Flow\FlowCPT;
use Eventin\Vendor\Ens\Hook\ActionListener;

/**
 * Class SDK
 *
 * @package Ens
 *
 * @since 1.0.0
 */
class SDK {

    /**
     * @var SDK|null
     */
    protected static $instance = null;

    public $identifier;

    public static function get_instance() {
        self::$instance = new self();
        
        return self::$instance;
    }

    /**
     * Pass config data before init.
     *
     * @since 1.0.0
     *
     * @param array $config
     *
     * @return SDK
     */
    public function setup( $config ) {
        if(isset($config['general_prefix'])){
            $this->identifier = $config['general_prefix'];
            update_option( $this->identifier.'_ens_config', $config );
        }

        return $this;
    }

    /**
     * Initialize SDK.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init() {
        ( new FlowCPT() )->register($this->identifier);
        ( new FlowAPI() )->init($this->identifier);
        ( new ActionListener() )->register($this->identifier);
        ( new Enqueue() )->init($this->identifier);
    }
}