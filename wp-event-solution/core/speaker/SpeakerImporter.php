<?php

/**
 * Speaker Importer Class
 *
 * @package Eventin
 */
namespace Eventin\Speaker;

defined( 'ABSPATH' ) || exit;

use Eventin\Importer\PostImporterInterface;
use Eventin\Importer\ReaderFactory;
use Etn\Core\Speaker\User_Model;

/**
 * Class Speaker Importer
 */
class SpeakerImporter implements PostImporterInterface {
    /**
     * Store File
     *
     * @var string
     */
    private $file;

    /**
     * Store data
     *
     * @var array
     */
    private $data;
    
    /**
     * Schedule import
     *
     * @param   array        $file         File params from request
     * @param   string|null  $forced_role  Force all imported rows to this role (etn-speaker or etn-organizer)
     * @return  void
     */
    public function import( $file, $forced_role = null ) {
        $this->file  = $file;
        $file_reader = ReaderFactory::get_reader( $file );

        if ( is_wp_error( $file_reader ) ) {
            return $file_reader;
        }

        $this->data = $file_reader->read_file();

        $this->create_speaker( $forced_role );
    }

    /**
     * Create schedule
     *
     * @param   string|null  $forced_role  Force all imported rows to this role
     * @return  void
     */
    private function create_speaker( $forced_role = null ) {
        $file_type  = ! empty( $this->file['type'] ) ? $this->file['type'] : '';
        $rows       = $this->data;

        foreach( $rows as $row ) {
            $speaker = new User_Model();
            $social = ! empty( $row['social'] ) ? $row['social'] : '';
            $group  = ! empty( $row['speaker_group'] ) ? $row['speaker_group'] : '';

            if ( 'text/csv' == $file_type ) {
                $social = json_decode( $social, true );
                $group  = json_decode( $group, true );
            }

	        // Force role if specified, otherwise validate role from data
	        if ( $forced_role && in_array( $forced_role, ['etn-speaker', 'etn-organizer'] ) ) {
		        $row['role'] = $forced_role;
	        } elseif ( ! in_array( $row['role'], ['etn-speaker', 'etn-organizer'] ) ) {
		        $row['role'] = 'etn-speaker';
	        }

            // `etn_speaker_group` and `etn_speaker_category` are different things
            // that were being fed the same value. The group is the speaker-category
            // TAXONOMY assignment (term ids, the CSV's `speaker_group` column); the
            // category is the record TYPE — `speaker` or `organizer` — which is what
            // User_Model::get_data() reports and the edit form binds to. Copying the
            // group into the category left every imported record with term ids where
            // the type belongs.
            // SpeakerExporter writes a `speaker_category` column, so honour it when
            // the file came from an export — but never over a forced role, which is
            // how the Organizers tab imports (the caller has already said what these
            // rows are). Otherwise derive the type from the role normalised above.
            $category = ( ! $forced_role && ! empty( $row['speaker_category'] ) ) ? $row['speaker_category'] : '';

            if ( $category && 'text/csv' == $file_type ) {
                $category = json_decode( $category, true );
            }

            $category = array_values(
                array_filter(
                    (array) $category,
                    function ( $value ) {
                        return is_string( $value )
                            && in_array( strtolower( $value ), [ 'speaker', 'organizer' ], true );
                    }
                )
            );

            $type = $category ? $category : [ str_replace( 'etn-', '', $row['role'] ) ];

            $args = [
                'first_name'                => ! empty( $row['name'] ) ? $row['name'] : '',
                'etn_speaker_website_email' => ! empty( $row['email'] ) ? $row['email'] : '',
                'image'                     => ! empty( $row['image'] ) ? $row['image'] : '',
                'etn_speaker_designation'   => ! empty( $row['designation'] ) ? $row['designation'] : '',
                'etn_speaker_summery'       => ! empty( $row['summary'] ) ? $row['summary'] : '',
                'etn_speaker_social'        => $social,
                'etn_speaker_company_logo'  => ! empty( $row['company_logo'] ) ? $row['company_logo'] : '',
                'etn_speaker_url'           => ! empty( $row['company_url'] ) ? $row['company_url'] : '',
                'etn_speaker_group'         => $group,
                'etn_speaker_category'      => $type,
                'etn_company_name'          => ! empty( $row['company_name'] ) ? $row['company_name'] : '',
                'author_url'                => ! empty( $row['author_url'] ) ? $row['author_url'] : '',
                'role'                      => ! empty( $row['role'] ) ? $row['role'] : '',
            ];

            $args['user_login'] = $row['email'];
    
            $speaker->create( $args );
        }
    }
}
