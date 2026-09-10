<?php
/**
 * Describes the people behind an event — organisers and speakers.
 *
 * @package Eventin\Schema
 */

namespace Eventin\Schema;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Speaker\User_Model;
use Eventin\Schema\Abstracts\Schema;

/**
 * Builds the schema.org Person for an Eventin speaker or organiser.
 *
 * Eventin stores both as WordPress users carrying `etn_speaker_*` meta, so this
 * class works off a user ID rather than a post — the post-based helpers on the
 * parent do not apply and get_data() is replaced outright.
 */
class PersonSchema extends Schema {

    /**
     * The schema.org type this class produces.
     *
     * @var string
     */
    protected $type = 'Person';

    /**
     * Build the Person object for a speaker or organiser.
     *
     * @param int|User_Model $user User ID or model.
     * @param array          $args Arguments. Defaults to context => false since a
     *                             Person is always nested inside an Event.
     *
     * @return array Map of user ID => Person object, or an empty array.
     */
    public function get_data( $user, array $args = [ 'context' => false ] ): array {
        $model = $user instanceof User_Model ? $user : new User_Model( $user );
        $id    = absint( $model->get_id() );

        if ( ! $id || ! get_userdata( $id ) ) {
            return [];
        }

        $name = $model->get_speaker_title();
        if ( ! $name ) {
            $name = $model->get_author_name();
        }

        // A Person with no name is noise Google will flag.
        if ( ! $name ) {
            return [];
        }

        $data = (object) [];

        if ( ! isset( $args['context'] ) || false !== $args['context'] ) {
            $data->{'@context'} = 'https://schema.org';
        }

        $data->{'@type'} = $this->type;
        $data->name      = wp_strip_all_tags( $name );

        $designation = $model->get_speaker_designation();
        if ( $designation ) {
            $data->jobTitle = wp_strip_all_tags( $designation );
        }

        $email = $model->get_speaker_email();
        if ( $email && is_email( $email ) ) {
            $data->email = $email;
        }

        $url = $this->get_person_url( $model );
        if ( $url ) {
            $data->url = esc_url_raw( $url );
        }

        $image = $this->get_person_image( $model );
        if ( $image ) {
            $data->image = esc_url_raw( $image );
        }

        $company = $model->get_company_name();
        if ( $company ) {
            $data->worksFor = (object) [
                '@type' => 'Organization',
                'name'  => wp_strip_all_tags( $company ),
            ];
        }

        /**
         * Filters a Person schema object before it is emitted.
         *
         * Note the third argument is a User_Model, not a WP_Post — speakers and
         * organisers are users in Eventin.
         *
         * @since 4.1.20
         *
         * @param object     $data  Schema object.
         * @param array      $args  Arguments passed to get_data().
         * @param User_Model $model The speaker / organiser model.
         */
        $data = apply_filters( 'eventin_schema_person_object', $data, $args, $model );

        return [ $id => $data ];
    }

    /**
     * The person's own website, falling back to their author archive.
     *
     * @param User_Model $model Speaker / organiser model.
     *
     * @return string
     */
    protected function get_person_url( User_Model $model ): string {
        $url = $model->get_speaker_url();

        if ( $url ) {
            return $url;
        }

        $website = $model->get_speaker_website_email();

        return $website && filter_var( $website, FILTER_VALIDATE_URL ) ? $website : '';
    }

    /**
     * The person's photo.
     *
     * User_Model::get_image() only returns a value when the stored meta is
     * already a URL, so resolve the attachment ID here as well.
     *
     * @param User_Model $model Speaker / organiser model.
     *
     * @return string
     */
    protected function get_person_image( User_Model $model ): string {
        $image = $model->get_image();

        if ( $image ) {
            return $image;
        }

        $image_id = $model->get_image_id();

        if ( $image_id && is_numeric( $image_id ) ) {
            $src = wp_get_attachment_image_url( absint( $image_id ), 'full' );

            if ( $src ) {
                return $src;
            }
        }

        return '';
    }
}
