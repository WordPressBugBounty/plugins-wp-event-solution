<?php

namespace Eventin\Template;

defined( 'ABSPATH' ) || exit;

use Etn\Core\Event\Event_Model;

/**
 * Normalizes an event into the shape the React preview blocks consume.
 * Single source of truth for preview data shape (mirrors what the
 * frontend blocks read from Event_Model + related models). Every value is
 * pre-resolved server-side (term links, avatars, image URLs) so the React
 * views can render identical markup without any WP function on the client.
 */
class PreviewEventFormatter {

    /**
     * Format an event id into the normalized preview array.
     *
     * @param int  $event_id  Explicit event id. 0 → resolve the template's preview event.
     * @param int  $post_id   Template post id, used to resolve the preview event
     *                        the SAME way the frontend/SSR blocks do.
     * @return array
     */
    public static function format( $event_id = 0, $post_id = 0 ) {
        $event_id = intval( $event_id );
        $post_id  = intval( $post_id );
        $is_demo  = false;

        if ( $event_id === 0 ) {
            $template = new TemplateModel( $post_id ? $post_id : get_the_ID() );
            $event_id = intval( $template->get_preview_event_id() );
            $is_demo  = true;
        }

        $event    = new Event_Model( $event_id );
        $location = get_post_meta( $event_id, 'etn_event_location', true );
        $location = is_array( $location ) ? $location : [];
        $type     = get_post_meta( $event_id, 'event_type', true );

        // Use the plugin's configured date/time formats so the React blocks
        // display dates/times exactly like the frontend (datetime, venue, etc.).
        $date_format = function_exists( 'etn_date_format' ) ? etn_date_format() : 'F j, Y';
        $time_format = function_exists( 'etn_time_format' ) ? etn_time_format() : 'H:i';

        return [
            'id'          => $event_id,
            'title'       => (string) $event->get_title(),
            'description' => self::clean_html( (string) $event->get_description() ),
            'address'     => (string) $event->get_address(),
            'latitude'    => isset( $location['latitude'] ) ? (string) $location['latitude'] : '',
            'longitude'   => isset( $location['longitude'] ) ? (string) $location['longitude'] : '',
            'isVirtual'   => ( $type === 'online' ),
            'timezone'    => (string) $event->get_timezone(),
            'startDate'   => (string) $event->get_start_date(),
            'startTime'   => (string) $event->get_start_time( $time_format ),
            'endDate'     => (string) $event->get_end_date(),
            'endTime'     => (string) $event->get_end_time( $time_format ),
            'startDateFormatted' => (string) $event->get_start_date( $date_format ),
            'endDateFormatted'   => (string) $event->get_end_date( $date_format ),
            'logo'        => (string) get_post_meta( $event_id, 'etn_event_logo', true ),
            'banner'      => (string) get_post_meta( $event_id, 'event_banner', true ),
            'socials'     => self::socials( $event->get_social() ),
            'tags'        => self::terms( $event->get_tags(), 'etn_tags' ),
            'categories'  => self::terms( $event->get_categories(), 'etn_category' ),
            'speakers'    => self::people( $event->get_speakers() ),
            'organizers'  => self::organizers( $event->get_organizers() ),
            'faqs'        => self::faqs( get_post_meta( $event_id, 'etn_event_faq', true ) ),
            'schedules'   => self::schedules( get_post_meta( $event_id, 'etn_event_schedule', true ) ),
            'scheduleType' => (string) get_post_meta( $event_id, 'etn_select_speaker_schedule_type', true ),
            'attendees'   => self::attendees( $event ),
            'attendeePageLink' => (string) get_post_meta( $event_id, 'attende_page_link', true ),
            'relatedEvents' => self::related_events( $event ),
            'isDemo'      => $is_demo,
        ];
    }

    /**
     * Strip <style>/<script> blocks then run through wp_kses_post, matching
     * the event-description frontend render.
     */
    private static function clean_html( $html ) {
        $html = preg_replace( '#<style[^>]*>.*?</style>#is', '', $html );
        $html = preg_replace( '#<script[^>]*>.*?</script>#is', '', $html );
        return wp_kses_post( (string) $html );
    }

    /**
     * Fallback avatar URL used by speaker/organizer/schedule blocks.
     */
    private static function avatar() {
        if ( class_exists( '\Wpeventin' ) && method_exists( '\Wpeventin', 'assets_url' ) ) {
            return \Wpeventin::assets_url() . 'images/avatar.jpg';
        }
        return '';
    }

    /**
     * Normalize social list to [{ icon, title, url }].
     */
    private static function socials( $socials ) {
        if ( ! is_array( $socials ) ) {
            return [];
        }
        $out = [];
        foreach ( $socials as $s ) {
            if ( ! is_array( $s ) ) {
                continue;
            }
            $out[] = [
                'icon'  => isset( $s['icon'] ) ? $s['icon'] : '',
                'title' => isset( $s['etn_social_title'] ) ? $s['etn_social_title'] : '',
                'url'   => isset( $s['etn_social_url'] ) ? $s['etn_social_url'] : '',
            ];
        }
        return $out;
    }

    /**
     * Reduce a WP_Term list to [{ name, url }] with resolved term links.
     */
    private static function terms( $terms, $taxonomy ) {
        if ( ! is_array( $terms ) ) {
            return [];
        }
        $out = [];
        foreach ( $terms as $t ) {
            if ( ! is_object( $t ) || ! isset( $t->name ) ) {
                continue;
            }
            $link = get_term_link( $t, $taxonomy );
            $out[] = [
                'name' => $t->name,
                'url'  => is_wp_error( $link ) ? '#' : $link,
            ];
        }
        return $out;
    }

    /**
     * Normalize a speaker/organizer User_Model list to the fields the blocks read.
     */
    private static function people( $people ) {
        if ( ! is_array( $people ) ) {
            return [];
        }
        $avatar = self::avatar();
        $out    = [];
        foreach ( $people as $p ) {
            if ( ! is_object( $p ) ) {
                continue;
            }
            $image = method_exists( $p, 'get_image' ) ? $p->get_image() : '';
            $out[] = [
                'authorUrl'   => method_exists( $p, 'get_author_url' ) ? $p->get_author_url() : '#',
                'name'        => method_exists( $p, 'get_speaker_title' ) ? $p->get_speaker_title() : '',
                'designation' => method_exists( $p, 'get_speaker_designation' ) ? $p->get_speaker_designation() : '',
                'image'       => $image ? $image : $avatar,
                'socials'     => method_exists( $p, 'get_speaker_socials' ) ? self::socials( $p->get_speaker_socials() ) : [],
            ];
        }
        return $out;
    }

    /**
     * Organizers: same as people() plus email + company logo.
     */
    private static function organizers( $people ) {
        if ( ! is_array( $people ) ) {
            return [];
        }
        $avatar = self::avatar();
        $out    = [];
        foreach ( $people as $p ) {
            if ( ! is_object( $p ) ) {
                continue;
            }
            $logo  = method_exists( $p, 'get_speaker_company_logo' ) ? $p->get_speaker_company_logo() : '';
            $image = method_exists( $p, 'get_image' ) ? $p->get_image() : '';
            $out[] = [
                'authorUrl'   => method_exists( $p, 'get_author_url' ) ? $p->get_author_url() : '#',
                'name'        => method_exists( $p, 'get_speaker_title' ) ? $p->get_speaker_title() : '',
                'email'       => method_exists( $p, 'get_speaker_email' ) ? $p->get_speaker_email() : '',
                'designation' => method_exists( $p, 'get_speaker_designation' ) ? $p->get_speaker_designation() : '',
                'image'       => $logo ? $logo : ( $image ? $image : $avatar ),
                'socials'     => method_exists( $p, 'get_speaker_socials' ) ? self::socials( $p->get_speaker_socials() ) : [],
            ];
        }
        return $out;
    }

    /**
     * FAQ list → [{ title, content }].
     */
    private static function faqs( $faqs ) {
        if ( ! is_array( $faqs ) ) {
            return [];
        }
        $out = [];
        foreach ( $faqs as $f ) {
            if ( ! is_array( $f ) ) {
                continue;
            }
            $content = isset( $f['etn_faq_content'] ) ? $f['etn_faq_content'] : '';
            $out[] = [
                'title'   => isset( $f['etn_faq_title'] ) ? $f['etn_faq_title'] : '',
                'content' => has_blocks( $content ) ? do_blocks( $content ) : $content,
            ];
        }
        return $out;
    }

    /**
     * Schedule day posts → [{ dayTitle, date, topics:[{ topic, startTime, endTime, objective, speakers[] }] }].
     */
    private static function schedules( $schedule_ids ) {
        if ( ! is_array( $schedule_ids ) || empty( $schedule_ids ) ) {
            return [];
        }
        $posts = get_posts( [
            'post_type'      => 'etn-schedule',
            'post__in'       => $schedule_ids,
            'orderby'        => 'post_date',
            'order'          => 'ASC',
            'posts_per_page' => -1,
        ] );

        $avatar = self::avatar();
        $out    = [];
        foreach ( $posts as $post ) {
            $date   = get_post_meta( $post->ID, 'etn_schedule_date', true );
            $topics = get_post_meta( $post->ID, 'etn_schedule_topics', true );
            if ( is_string( $topics ) ) {
                $topics = function_exists( 'etn_safe_decode' ) ? etn_safe_decode( $topics ) : json_decode( $topics, true );
            }
            $topics = is_array( $topics ) ? $topics : [];

            $topic_out = [];
            foreach ( $topics as $t ) {
                if ( ! is_array( $t ) ) {
                    continue;
                }
                $speaker_ids = isset( $t['speakers'] ) && is_array( $t['speakers'] ) ? $t['speakers'] : [];
                $speakers    = [];
                foreach ( $speaker_ids as $sid ) {
                    $img = get_user_meta( $sid, 'image', true );
                    $speakers[] = [
                        'authorUrl'   => get_author_posts_url( $sid ),
                        'name'        => get_the_author_meta( 'display_name', $sid ),
                        'designation' => get_user_meta( $sid, 'etn_speaker_designation', true ),
                        'image'       => $img ? $img : $avatar,
                    ];
                }
                $topic_out[] = [
                    'topic'     => isset( $t['etn_schedule_topic'] ) ? $t['etn_schedule_topic'] : '',
                    'startTime' => isset( $t['etn_shedule_start_time'] ) ? $t['etn_shedule_start_time'] : '',
                    'endTime'   => isset( $t['etn_shedule_end_time'] ) ? $t['etn_shedule_end_time'] : '',
                    'room'      => isset( $t['etn_shedule_room'] ) ? $t['etn_shedule_room'] : '',
                    'objective' => isset( $t['etn_shedule_objective'] ) ? wp_kses_post( $t['etn_shedule_objective'] ) : '',
                    'speakers'  => $speakers,
                ];
            }

            $out[] = [
                'dayTitle' => $post->post_title,
                'date'     => $date ? date_i18n( 'd M', strtotime( $date ) ) : '',
                'topics'   => $topic_out,
            ];
        }
        return $out;
    }

    /**
     * Attendees (publish only) → { list:[{ id, name, avatar }], total }.
     */
    private static function attendees( $event ) {
        $attendees = $event->get_attendees( [ 'publish' ], 200 );
        $avatar    = self::avatar();
        $list      = [];
        if ( is_array( $attendees ) ) {
            foreach ( $attendees as $a ) {
                $data  = is_object( $a ) && method_exists( $a, 'get_data' ) ? $a->get_data() : ( is_array( $a ) ? $a : [] );
                $email = isset( $data['etn_email'] ) ? $data['etn_email'] : '';
                $list[] = [
                    'id'     => isset( $data['id'] ) ? $data['id'] : 0,
                    'name'   => isset( $data['etn_name'] ) ? $data['etn_name'] : '',
                    'avatar' => $email ? get_avatar_url( $email, [ 'size' => 150 ] ) : $avatar,
                ];
            }
        }
        return [ 'list' => $list, 'total' => count( $list ) ];
    }

    /**
     * Related events → [{ permalink, title, thumbnail, startDate, endDate, address }].
     */
    private static function related_events( $event ) {
        $related = $event->get_related_events();
        if ( ! is_array( $related ) ) {
            return [];
        }
        $placeholder = ( class_exists( '\Wpeventin' ) && method_exists( '\Wpeventin', 'assets_url' ) )
            ? \Wpeventin::assets_url() . 'images/event-placeholder.jpg'
            : '';
        $out = [];
        foreach ( $related as $item ) {
            $thumb_id = get_post_thumbnail_id( $item->id );
            $thumb    = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium' ) : $placeholder;
            $out[] = [
                'permalink' => get_the_permalink( $item->id ),
                'title'     => $item->get_title(),
                'thumbnail' => $thumb ? $thumb : $placeholder,
                'startDate' => $item->get_start_date( 'D, d M Y' ),
                'address'   => $item->get_address(),
            ];
        }
        return $out;
    }
}
