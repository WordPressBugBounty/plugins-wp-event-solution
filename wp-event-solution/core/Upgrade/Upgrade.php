<?php

namespace Eventin\Upgrade;

defined( 'ABSPATH' ) || exit;

use Eventin\Eventin;
use Eventin\PreviewPlaceholder\PreviewPlaceholderEventSeeder;
use Eventin\Upgrade\Schema;
use Eventin\Upgrade\Upgraders\UpdateInterface;
use Eventin\Upgrade\Upgraders\V_4_0_0;
use Eventin\Upgrade\Upgraders\V_4_0_2;
use Eventin\Upgrade\Upgraders\V_4_0_4;
use Eventin\Upgrade\Upgraders\V_4_0_6;
use Eventin\Upgrade\Upgraders\V_4_0_7;
use Eventin\Upgrade\Upgraders\V_4_0_8;
use Eventin\Upgrade\Upgraders\V_4_0_9;
use Eventin\Upgrade\Upgraders\V_4_0_29;
use Eventin\Upgrade\Upgraders\V_4_0_33;
use Eventin\Upgrade\Upgraders\V_4_0_50;
use Eventin\Upgrade\Upgraders\V_4_1_2;
use Eventin\Upgrade\Upgraders\V_4_1_13;
use Eventin\Upgrade\Upgraders\V_4_1_14;
use Eventin\Upgrade\Upgraders\V_4_1_15;
use Eventin\Upgrade\Upgraders\V_4_1_16;
use Eventin\Upgrade\Upgraders\V_4_1_17;
use Eventin\Upgrade\Upgraders\V_4_1_18;
use Eventin\Upgrade\Upgraders\V_4_1_19;
use Wpeventin;

/**
 * Upgrade class
 *
 * @since 4.0.0
 */
class Upgrade {
    /**
     * Store all upgraders
     *
     * @var array
     */
    protected static $upgraders = [
        '4.0.0' => V_4_0_0::class,
        '4.0.2' => V_4_0_2::class,
        '4.0.4' => V_4_0_4::class,
        '4.0.6' => V_4_0_6::class,
        '4.0.7' => V_4_0_7::class,
        '4.0.8' => V_4_0_8::class,
        '4.0.9' => V_4_0_9::class,
        '4.0.33' => V_4_0_33::class,
        '4.0.50' => V_4_0_50::class,
        '4.1.2'  => V_4_1_2::class,
        '4.1.13' => V_4_1_13::class,
        '4.1.14' => V_4_1_14::class,
        '4.1.15' => V_4_1_15::class,
        '4.1.16' => V_4_1_16::class,
        '4.1.17' => V_4_1_17::class,
        '4.1.18' => V_4_1_18::class,
        '4.1.19' => V_4_1_19::class,
    ];

    /**
     * Checks if upgrade is required or not
     *
     * @since 4.0.0
     *
     * @param bool $is_required
     *
     * @return bool
     */
    public static function is_upgrade_required( $is_required = false ) {
        $installed_version = get_option( 'etn_version' );
        $upgrade_versions  = array_keys( self::$upgraders );

        if ( $installed_version && version_compare( $installed_version, end( $upgrade_versions ), '<' ) ) {
            return true;
        }

        return $is_required;
    }

    /**
     * Registers services with the container.
     *
     * @return void
     * @throws \ReflectionException
     */
    public static function register() {
        $current_version = Wpeventin::version();
        $migrated_from   = get_option( 'etn_db_migration' );

        // Tables first, and unconditionally: the loop below runs only the current
        // release's upgrader on a site with no marker, so schema introduced by an
        // earlier release would never be installed on a fresh install. Data
        // migrations stay version-gated; a fresh install has nothing to migrate.
        Schema::install();

        // Same reasoning, same reason it can't live in an upgrader: the Template
        // Builder preview placeholder was seeded from V_4_1_16 alone, so a fresh
        // 4.1.19 install — which runs only V_4_1_19 — never got it, and a fresh
        // 4.1.20 install never would either. It is shipped content the running code
        // needs, not a migration from a previous version.
        //
        // Self-guarding and mutexed (see PreviewPlaceholderEventSeeder): it no-ops
        // once the event exists, so calling it on every activation and upgrade
        // cannot produce the duplicate copies the old admin_init call site did.
        ( new PreviewPlaceholderEventSeeder() )->seed();

        foreach ( self::$upgraders as $version => $upgrader ) {
            if ( ! class_exists( $upgrader ) ) {
                continue;
            }

            // Never run an upgrader that targets a release newer than the code we're running.
            if ( version_compare( $version, $current_version, '>' ) ) {
                continue;
            }

            if ( $migrated_from ) {
                // Existing site: run every upgrader newer than the last one applied, so a
                // customer who skips a release (e.g. 4.1.15 -> 4.1.17) still runs the
                // intermediate migration(s) such as 4.1.16.
                if ( version_compare( $version, $migrated_from, '<=' ) ) {
                    continue;
                }
            } elseif ( version_compare( $version, $current_version, '<' ) ) {
                // No migration marker yet (fresh install): preserve prior behavior and run
                // only the current release's upgrader.
                continue;
            }

            if ( is_subclass_of( $upgrader, UpdateInterface::class ) ) {
                $upgrader_object = new $upgrader();
                $upgrader_object->run();
            }
        }

        // if run the migration it will update the etn_is_migrated option.
        update_option( 'etn_is_migrated', true );
    }
}
