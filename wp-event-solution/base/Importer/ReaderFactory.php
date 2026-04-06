<?php
/**
 * File Reader Factory
 * 
 * @package Eventin
 */
namespace Eventin\Importer;

class ReaderFactory {
    /**
     * Get reader depends on file type
     *
     * @return Reader_Interface
     */
    public static function get_reader( $file ) {
        $file_name = ! empty( $file['tmp_name'] ) ? $file['tmp_name'] : '';

        $finfo     = new \finfo( FILEINFO_MIME_TYPE );
        $real_mime = $finfo->file( $file_name );
        $allowed   = [ 'text/plain', 'text/csv', 'application/json' ];

        if ( ! in_array( $real_mime, $allowed, true ) ) {
            return new \WP_Error( 'invalid_file_type', esc_html__( 'Please upload a csv or json file', 'eventin' ), [ 'status' => 422 ] );
        }

        switch( $real_mime ) {
            case 'application/json':
                return new JSONReader( $file_name );
            default:
                return new CSVReader( $file_name );
        }
    }
}
