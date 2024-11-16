<?php
/**
 * Extentions class
 * 
 * @package Eventin
 */
namespace Eventin\Extensions;

use Wpeventin;

class ExtensionIcon {
    /**
     * Get icon
     *
     * @param   string  $extension_name  [$extension_name description]
     *
     * @return  string
     */
    public static function get( $extension_name ) {
        return self::get_svg( $extension_name );
    }

    /**
     * Get svg icon
     *
     * @param   string  $file_name  [$file_name description]
     *
     * @return  string
     */
    public static function get_svg( $file_name ) {
        $file = Wpeventin::assets_dir() . 'images/addons/' . $file_name . '.svg';
        
        if ( file_exists( $file ) ) {
            return file_get_contents( $file );
        }

        return null;
    }
}