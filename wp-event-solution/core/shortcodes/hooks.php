<?php

namespace Etn\Core\Shortcodes;

use Etn\Core\Event\Event_Model;
use Etn\Utils\Helper as helper;

defined( 'ABSPATH' ) || exit;

class Hooks {

    use \Etn\Traits\Singleton;

    public function Init() { 
        //[events limit='1' event_cat_ids='1,2' event_tag_ids='1,2' /]
        add_shortcode( "events", [$this, "etn_events_widget"] );

        //[events_tab limit='1' event_cat_ids='1,2' event_tag_ids='1,2' /]
        add_shortcode( "events_tab", [$this, "etn_events_tab"] );

        //[events_calendar limit='1' event_cat_ids='1,2' event_tag_ids='1,2' /]
        add_shortcode( "events_calendar", [$this, "calendar_widget"] ); 

        //[speakers cat_id='19' limit='3'/]
        add_shortcode( "speakers", [$this, "etn_speakers_widget"] );

        //[schedules ids ='18,19'/]
        add_shortcode( "schedules", [$this, "etn_schedules_widget"] );

        //[event_search_filter/]
        add_shortcode( "event_search_filter", [$this, "etn_search_filter"] );

        add_shortcode( "schedules_list", [$this, "etn_schedules_list_widget"] );

        //[etn_zoom_api_link meeting_id ='123456789' link_only='no']
        add_shortcode( "etn_zoom_api_link", [$this, "etn_zoom_api_link"] );
        add_shortcode( "etn_event_meta_info", [$this, "etn_event_meta_info"] );

        //[etn_speaker_events id='5' event_cat_ids='1,2' event_tag_ids='1,2' event_ids='10,11' style='event-1' limit='10' /]
        add_shortcode( "etn_speaker_events", [$this, "etn_speaker_events_widget"] );

        //[etn_organizer_events id='8' event_cat_ids='1,2' event_tag_ids='1,2' event_ids='10,11' style='event-1' limit='10' /]
        add_shortcode( "etn_organizer_events", [$this, "etn_organizer_events_widget"] );
    }

    /**
     * Speaker events shortcode — list events linked to a given speaker user.
     */
    public function etn_speaker_events_widget( $attributes ) {
        return $this->etn_user_events_widget( $attributes, 'etn_event_speaker' );
    }

    /**
     * Organizer events shortcode — list events linked to a given organizer user.
     */
    public function etn_organizer_events_widget( $attributes ) {
        return $this->etn_user_events_widget( $attributes, 'etn_event_organizer' );
    }

    /**
     * Shared renderer for speaker / organizer event listings. Reuses the existing
     * event-1 / event-2 widget templates so the markup matches [events].
     */
    protected function etn_user_events_widget( $attributes, $meta_key ) {
        $user_id = isset( $attributes['id'] ) && is_numeric( $attributes['id'] ) ? intval( $attributes['id'] ) : 0;
        if ( ! $user_id ) {
            return '<p class="etn-not-found-post">' . esc_html__( 'No Post Found', 'eventin' ) . '</p>';
        }

        $allowed_styles = ['event-1', 'event-2'];
        $style = ! empty( $attributes['style'] ) && in_array( $attributes['style'], $allowed_styles, true )
            ? $attributes['style']
            : 'event-1';

        $event_cat = ! empty( $attributes['event_cat_ids'] ) && is_string( $attributes['event_cat_ids'] )
            ? array_filter( array_map( 'intval', explode( ',', $attributes['event_cat_ids'] ) ) )
            : null;
        $event_tag = ! empty( $attributes['event_tag_ids'] ) && is_string( $attributes['event_tag_ids'] )
            ? array_filter( array_map( 'intval', explode( ',', $attributes['event_tag_ids'] ) ) )
            : null;
        $selected_events = ! empty( $attributes['event_ids'] ) && is_string( $attributes['event_ids'] )
            ? array_filter( array_map( 'intval', explode( ',', $attributes['event_ids'] ) ) )
            : null;

        $posts_to_show = ! empty( $attributes['limit'] ) && is_numeric( $attributes['limit'] )
            ? intval( $attributes['limit'] )
            : -1;
        $order = ! empty( $attributes['order'] ) && in_array( strtoupper( $attributes['order'] ), ['ASC', 'DESC'], true )
            ? strtoupper( $attributes['order'] )
            : 'DESC';

        $post_attributes = ['title', 'ID', 'name', 'post_date'];
        $orderby = ! empty( $attributes['orderby'] ) && is_string( $attributes['orderby'] )
            ? sanitize_text_field( $attributes['orderby'] )
            : 'ID';
        $orderby_meta = ! in_array( $orderby, $post_attributes, true ) ? 'meta_value' : false;

        $etn_event_col = ! empty( $attributes['etn_event_col'] ) ? intval( $attributes['etn_event_col'] ) : 3;
        switch ( $etn_event_col ) {
            case 6: case 5: $etn_event_col = 2; break;
            case 4: $etn_event_col = 3; break;
            case 3: $etn_event_col = 4; break;
            case 2: $etn_event_col = 6; break;
            case 1: $etn_event_col = 12; break;
        }

        $etn_desc_limit         = ! empty( $attributes['desc_limit'] ) ? intval( $attributes['desc_limit'] ) : 20;
        $etn_desc_show          = isset( $attributes['etn_desc_show'] ) ? sanitize_text_field( $attributes['etn_desc_show'] ) : 'yes';
        $show_end_date          = isset( $attributes['show_end_date'] ) ? sanitize_text_field( $attributes['show_end_date'] ) : 'no';
        $show_event_location    = isset( $attributes['show_event_location'] ) ? sanitize_text_field( $attributes['show_event_location'] ) : 'yes';
        $show_remaining_tickets = isset( $attributes['show_remaining_tickets'] ) ? sanitize_text_field( $attributes['show_remaining_tickets'] ) : 'no';
        $filter_with_status     = isset( $attributes['filter_with_status'] ) ? sanitize_text_field( $attributes['filter_with_status'] ) : '';

        $args = [
            'post_type'      => 'etn',
            'post_status'    => 'publish',
            'posts_per_page' => $posts_to_show,
            'order'          => $order,
            'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
                'relation' => 'AND',
                [
                    'relation' => 'OR',
                    [ 'key' => $meta_key, 'value' => sprintf( '"%d"', $user_id ), 'compare' => 'LIKE' ],
                    [ 'key' => $meta_key, 'value' => sprintf( 'i:%d;', $user_id ),  'compare' => 'LIKE' ],
                ],
            ],
            'tax_query'      => [ 'relation' => 'AND' ], // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
        ];

        if ( $orderby_meta ) {
            $args['meta_key'] = $orderby; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
            $args['orderby'] = $orderby_meta;
        } else {
            $args['orderby'] = $orderby;
        }

        if ( ! empty( $event_cat ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'etn_category',
                'field'    => 'term_id',
                'terms'    => $event_cat,
                'operator' => 'IN',
            ];
        }
        if ( ! empty( $event_tag ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'etn_tags',
                'field'    => 'term_id',
                'terms'    => $event_tag,
                'operator' => 'IN',
            ];
        }
        if ( ! empty( $selected_events ) ) {
            $args['post__in'] = $selected_events;
        }

        if ( 'upcoming' === $filter_with_status ) {
            $args['meta_query'][] = [
                'key'     => 'etn_start_date',
                'value'   => gmdate( 'Y-m-d' ),
                'compare' => '>=',
                'type'    => 'DATE',
            ];
        } elseif ( 'expire' === $filter_with_status ) {
            $args['meta_query'][] = [
                'key'     => 'etn_end_date',
                'value'   => gmdate( 'Y-m-d' ),
                'compare' => '<',
                'type'    => 'DATE',
            ];
        }

        $data = get_posts( $args );
        $event_count = is_array( $data ) ? count( $data ) : 0;

        $user_type = ( 'etn_event_speaker' === $meta_key ) ? 'speaker' : 'organizer';

        if ( wp_style_is( 'etn-icon', 'registered' ) ) {
            wp_enqueue_style( 'etn-icon' );
        }

        ob_start();
        echo '<div class="etn-user-events-shortcode etn-user-events--' . esc_attr( $user_type ) . '">';
        echo $this->render_user_profile_header( $user_id, $user_type, $event_count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<div class="etn-user-events-list">';
        if ( file_exists( \Wpeventin::widgets_dir() . "events/style/{$style}.php" ) ) {
            include \Wpeventin::widgets_dir() . "events/style/{$style}.php";
        }
        echo '</div></div>';
        return ob_get_clean();
    }

    /**
     * Render the speaker / organizer profile hero header that sits above the
     * events grid. Pulls user data via User_Model and outputs a self-contained
     * HTML block plus its scoped CSS (printed once per request).
     */
    protected function render_user_profile_header( $user_id, $type, $event_count ) {
        $user = get_userdata( $user_id );
        if ( ! $user ) {
            return '';
        }

        $model = new \Etn\Core\Speaker\User_Model( $user_id );

        $name        = $model->get_speaker_title() ?: $user->display_name;
        $designation = $model->get_speaker_designation();
        $company     = $model->get_company_name();
        $company_url = $model->get_speaker_url();
        $company_logo = $model->get_speaker_company_logo();
        $bio = ( 'organizer' === $type ) ? $model->get_organizer_bio() : $model->get_speaker_summary();
        if ( empty( $bio ) ) {
            $bio = $model->get_speaker_summary();
        }
        if ( empty( $bio ) ) {
            $bio = get_user_meta( $user_id, 'organizer_bio', true );
        }
        if ( empty( $bio ) ) {
            $bio = get_user_meta( $user_id, 'description', true );
        }
        if ( empty( $bio ) && ! empty( $user->description ) ) {
            $bio = $user->description;
        }
        $email   = $model->get_speaker_email();
        $phone   = $model->get_phone();
        $socials = $model->get_speaker_socials();

        $image    = $model->get_image();
        $image_id = $model->get_image_id();
        if ( empty( $image ) && $image_id ) {
            $src   = wp_get_attachment_image_src( $image_id, 'medium' );
            $image = $src[0] ?? '';
        }
        if ( empty( $image ) ) {
            $image = get_avatar_url( $user_id, [ 'size' => 160 ] );
        }

        $role_label = ( 'organizer' === $type )
            ? esc_html__( 'Organizer', 'eventin' )
            : esc_html__( 'Speaker', 'eventin' );

        // translators: %s is the speaker / organizer name.
        $events_label_template = ( 'organizer' === $type )
            ? esc_html__( 'Events by %s', 'eventin' )
            : esc_html__( 'Events featuring %s', 'eventin' );

        ob_start();
        $this->print_user_profile_header_styles();
        ?>
        <div class="etn-uep-hero">
            <div class="etn-uep-hero__cover" aria-hidden="true"></div>
            <div class="etn-uep-hero__body">
                <div class="etn-uep-hero__avatar">
                    <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy" />
                </div>
                <div class="etn-uep-hero__main">
                    <h2 class="etn-uep-hero__name"><?php echo esc_html( $name ); ?></h2>
                    <div class="etn-uep-hero__meta">
                        <?php if ( $designation ) : ?>
                            <span class="etn-uep-hero__designation"><?php echo wp_kses_post( $designation ); ?></span>
                        <?php else : ?>
                            <span class="etn-uep-hero__designation"><?php echo esc_html( $role_label ); ?></span>
                        <?php endif; ?>
                        <?php if ( $company ) : ?>
                            <span class="etn-uep-hero__sep">&middot;</span>
                            <?php if ( $company_url ) : ?>
                                <a class="etn-uep-hero__company" href="<?php echo esc_url( $company_url ); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php if ( $company_logo ) : ?>
                                        <img class="etn-uep-hero__company-logo" src="<?php echo esc_url( $company_logo ); ?>" alt="<?php echo esc_attr( $company ); ?>" loading="lazy" />
                                    <?php endif; ?>
                                    <?php echo esc_html( $company ); ?>
                                </a>
                            <?php else : ?>
                                <span class="etn-uep-hero__company">
                                    <?php if ( $company_logo ) : ?>
                                        <img class="etn-uep-hero__company-logo" src="<?php echo esc_url( $company_logo ); ?>" alt="<?php echo esc_attr( $company ); ?>" loading="lazy" />
                                    <?php endif; ?>
                                    <?php echo esc_html( $company ); ?>
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ( $email || $phone || ! empty( $socials ) ) : ?>
                        <div class="etn-uep-hero__contact">
                            <?php if ( $email ) : ?>
                                <a class="etn-uep-hero__contact-link" href="mailto:<?php echo esc_attr( $email ); ?>" aria-label="<?php echo esc_attr__( 'Email', 'eventin' ); ?>">
                                    <span class="etn-uep-icon" aria-hidden="true">&#9993;</span>
                                    <span class="etn-uep-hero__contact-text"><?php echo esc_html( $email ); ?></span>
                                </a>
                            <?php endif; ?>
                            <?php if ( $phone ) : ?>
                                <a class="etn-uep-hero__contact-link" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" aria-label="<?php echo esc_attr__( 'Phone', 'eventin' ); ?>">
                                    <span class="etn-uep-icon" aria-hidden="true">&#9742;</span>
                                    <span class="etn-uep-hero__contact-text"><?php echo esc_html( $phone ); ?></span>
                                </a>
                            <?php endif; ?>
                            <?php if ( ! empty( $socials ) && is_array( $socials ) ) : ?>
                                <span class="etn-uep-hero__socials">
                                    <?php foreach ( $socials as $social ) :
                                        if ( empty( $social['etn_social_url'] ) ) { continue; }
                                        $raw_icon   = isset( $social['icon'] ) ? (string) $social['icon'] : '';
                                        $icon_parts = array_filter( array_map( 'sanitize_html_class', preg_split( '/\s+/', trim( $raw_icon ) ) ) );
                                        $icon_class = implode( ' ', $icon_parts );
                                        ?>
                                        <a class="etn-uep-hero__social"
                                           href="<?php echo esc_url( $social['etn_social_url'] ); ?>"
                                           target="_blank" rel="noopener noreferrer"
                                           aria-label="<?php echo esc_attr( $social['etn_social_url'] ); ?>">
                                            <i class="etn-icon <?php echo esc_attr( $icon_class ); ?>" aria-hidden="true"></i>
                                        </a>
                                    <?php endforeach; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $bio ) : ?>
                        <div class="etn-uep-hero__bio"><?php echo wp_kses_post( wpautop( $bio ) ); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="etn-uep-events-heading">
            <h3 class="etn-uep-events-heading__title">
                <?php echo esc_html( sprintf( $events_label_template, $name ) ); ?>
            </h3>
            <span class="etn-uep-events-heading__badge"><?php echo esc_html( number_format_i18n( $event_count ) ); ?></span>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Print scoped CSS for the speaker / organizer profile hero. Static guard
     * keeps it to one copy per request even if multiple shortcodes appear.
     */
    protected function print_user_profile_header_styles() {
        static $printed = false;
        if ( $printed ) {
            return;
        }
        $printed = true;
        ?>
        <style id="etn-user-events-profile-css">
        .etn-user-events-shortcode{margin:0 0 32px;font-family:inherit;color:#1f2937}
        .etn-uep-hero{position:relative;background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 6px 24px rgba(15,23,42,.08);border:1px solid #eef2f7;margin-bottom:28px}
        .etn-uep-hero__cover{height:140px;background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 50%,#ec4899 100%)}
        .etn-uep-hero__body{padding:0 28px 24px;display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap}
        .etn-uep-hero__avatar{margin-top:-60px;flex:0 0 auto}
        .etn-uep-hero__avatar img{width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid #fff;box-shadow:0 4px 12px rgba(15,23,42,.12);background:#f3f4f6;display:block}
        .etn-uep-hero__main{flex:1 1 320px;min-width:0;padding-top:14px}
        .etn-uep-hero__name{font-size:1.6rem;font-weight:700;margin:0 0 6px;line-height:1.25;color:#0f172a}
        .etn-uep-hero__meta{display:flex;flex-wrap:wrap;align-items:center;gap:8px;color:#475569;font-size:.95rem;margin-bottom:12px}
        .etn-uep-hero__designation{font-weight:500}
        .etn-uep-hero__sep{color:#cbd5e1}
        .etn-uep-hero__company{display:inline-flex;align-items:center;gap:6px;color:#475569;text-decoration:none}
        .etn-uep-hero__company:hover{color:#6366f1}
        .etn-uep-hero__company-logo{width:20px;height:20px;border-radius:4px;object-fit:contain;background:#f8fafc}
        .etn-uep-hero__contact{display:flex;flex-wrap:wrap;align-items:center;gap:14px 18px;margin-bottom:14px}
        .etn-uep-hero__contact-link{display:inline-flex;align-items:center;gap:6px;color:#475569;text-decoration:none;font-size:.9rem;transition:color .15s ease}
        .etn-uep-hero__contact-link:hover{color:#6366f1}
        .etn-uep-icon{display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:50%;background:#eef2ff;color:#6366f1;font-size:.78rem;line-height:1}
        .etn-uep-hero__socials{display:inline-flex;flex-wrap:wrap;align-items:center;gap:8px}
        .etn-uep-hero__social{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:50%;background:#f1f5f9;color:#475569;text-decoration:none;transition:background .15s ease,color .15s ease,transform .15s ease}
        .etn-uep-hero__social:hover{background:#6366f1;color:#fff;transform:translateY(-1px)}
        .etn-uep-hero__social i{font-size:.9rem;line-height:1}
        .etn-uep-hero__bio{color:#475569;font-size:.95rem;line-height:1.65;margin-top:4px}
        .etn-uep-hero__bio p:last-child{margin-bottom:0}
        .etn-uep-events-heading{display:flex;align-items:center;gap:10px;margin:0 0 18px;padding-bottom:12px;border-bottom:1px solid #e5e7eb}
        .etn-uep-events-heading__title{font-size:1.15rem;font-weight:600;margin:0;color:#0f172a}
        .etn-uep-events-heading__badge{display:inline-flex;align-items:center;justify-content:center;min-width:28px;height:24px;padding:0 8px;border-radius:999px;background:#eef2ff;color:#4338ca;font-size:.8rem;font-weight:600}
        @media (max-width:600px){
            .etn-uep-hero__cover{height:110px}
            .etn-uep-hero__body{padding:0 18px 20px;gap:16px}
            .etn-uep-hero__avatar{margin-top:-48px}
            .etn-uep-hero__avatar img{width:96px;height:96px}
            .etn-uep-hero__name{font-size:1.35rem}
            .etn-uep-hero__main{padding-top:8px;flex-basis:100%}
        }
        </style>
        <?php
    }
    
    /**
     * Events Calendar
     */
    public function calendar_widget($attributes){
        wp_enqueue_style('etn-app-index');
        wp_enqueue_script('etn-app-index');
        $event_cat = null;

        if ( isset( $attributes['event_cat_ids'] ) && $attributes['event_cat_ids'] !== '' ) {
            $event_cat = explode( ',', $attributes['event_cat_ids'] );
        }

        $event_count        = isset( $attributes["limit"] ) && is_numeric( $attributes["limit"] ) && is_numeric( $attributes["limit"] ) <= 3 ? intval( $attributes["limit"] ) : 15;
        $show_desc          = !empty( $attributes["show_desc"] ) ? $attributes["show_desc"] : 'no';
        $show_upcoming_event= !empty( $attributes["show_upcoming_event"] ) ? $attributes["show_upcoming_event"] : 'yes';
        $calendar_show      = !empty( $attributes["calendar_show"] ) ? $attributes["calendar_show"] : 'left';
        $style              = !empty( $attributes["style"] ) ? $attributes["style"] : 'style-1';

        $allowed_status     = ['', 'upcoming', 'ongoing', 'expire'];
        $filter_with_status = isset( $attributes["filter_with_status"] ) ? sanitize_text_field( $attributes["filter_with_status"] ) : '';
        $filter_with_status = in_array( $filter_with_status, $allowed_status, true ) ? $filter_with_status : '';

        if(is_array($event_cat)){
            $cats = $event_cat;
        }else{
    
            $args_cat = array(
                'taxonomy'     => 'etn_category',
                'number' => 50,
            );
            $cats = get_categories( $args_cat );
        }
        $cat_json = [];
        if(!empty($cats)){
            foreach($cats as $value){
                $term = get_term($value,'etn_category');
                
                $info = [
                    'category' => $term->name, 
                ];
                array_push($cat_json,  $info );
            }
        }
    
        $show_parent_event = isset( $attributes['show_parent_event'] ) ? $attributes['show_parent_event'] : '';
        $show_child_event  = isset( $attributes['show_child_event'] ) ? $attributes['show_child_event'] : 'yes';
        $post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );

        $extra_controls = [
            'limit' => $event_count, 
            'select_cat_text' => esc_html__('All Categories', 'eventin'),
            'show_desc' => $show_desc,
            'show_upcoming_event' => $show_upcoming_event,
            'calendar_show' =>$calendar_show,
            'style' =>$style,
            'filter_with_status' => $filter_with_status,
            'selected_date_text' => esc_html__('Showing events for', 'eventin'),
            'event_notice' => esc_html__('No event found on the selected date. Please select another date or month.', 'eventin'),
            'post_parent' => $post_parent
        ];
        $data_controls = json_encode( $extra_controls );
        $data_cat_list = json_encode( $cat_json );

        // Send only integer term IDs so the JS URL param is a clean comma-separated
        // string (e.g. "45") that the REST API can parse. Sending full WP_Term objects
        // causes JS to serialize them as "[object Object]" and the filter is silently dropped.
        if ( is_array( $event_cat ) && ! empty( $event_cat ) ) {
            $data_selected_cats = json_encode( array_map( 'intval', $event_cat ) );
        } else {
            $data_selected_cats = json_encode( [] );
        }
        

        ob_start();
        ?>
          <div class="eventin-shortcode-wrapper">
              <div class="events_calendar_classic" data-cat="<?php echo esc_attr( $data_cat_list ); ?>" data-controls="<?php echo esc_attr( $data_controls ); ?>" data-selectedCats="<?php echo esc_attr( $data_selected_cats ); ?>"></div>
          </div>
        <?php

        return ob_get_clean(); 
    }

  

    /**
     * Events shortcode
     */
    public function etn_events_widget( $attributes ) {
        $event_cat = null;
        $event_tag = null;

        // Sanitize and validate attributes
        $style = ! empty( $attributes['style'] ) && is_string( $attributes['style'] ) 
            ? sanitize_text_field( $attributes['style'] ) 
            : 'event-1';

        if ( ! empty( $attributes['event_cat_ids'] ) && is_string( $attributes['event_cat_ids'] ) ) {
            $event_cat = array_map( 'intval', explode( ',', $attributes['event_cat_ids'] ) );
        }

        if ( ! empty( $attributes['event_tag_ids'] ) && is_string( $attributes['event_tag_ids'] ) ) {
            $event_tag = array_map( 'intval', explode( ',', $attributes['event_tag_ids'] ) );
        }

        $posts_to_show = ! empty( $attributes['limit'] ) && is_numeric( intval($attributes['limit']) )
            ? intval( $attributes['limit'] ) 
            : -1;

        $order = ! empty( $attributes['order'] ) && in_array( strtoupper( $attributes['order'] ), ['ASC', 'DESC'], true ) 
            ? strtoupper( $attributes['order'] ) 
            : 'DESC';

        $etn_desc_limit = ! empty( $attributes['desc_limit'] ) ? intval( $attributes['desc_limit'] ) : 20;
        $etn_event_col  = ! empty( $attributes['etn_event_col'] ) ? intval( $attributes['etn_event_col'] ) : 20;

        $show_parent_event = ! empty( $attributes['show_parent_event'] ) 
            ? sanitize_text_field( $attributes['show_parent_event'] ) 
            : 'no';
        $show_child_event  = ! empty( $attributes['show_child_event'] ) 
            ? sanitize_text_field( $attributes['show_child_event'] ) 
            : 'yes';

        $post_parent = Helper::show_parent_child( $show_parent_event, $show_child_event );

        // Validate and adjust column settings
        switch ( $etn_event_col ) {
            case 6:
            case 5:
                $etn_event_col = 2;
                break;
            case 4:
                $etn_event_col = 3;
                break;
            case 3:
                $etn_event_col = 4;
                break;
            case 2:
                $etn_event_col = 6;
                break;
            case 1:
                $etn_event_col = 12;
                break;
        }

        // Sanitize orderby
        $post_attributes = ['title', 'ID', 'name', 'post_date'];
        $orderby = isset( $attributes['orderby'] ) && is_string( $attributes['orderby'] ) 
            ? sanitize_text_field( $attributes['orderby'] ) 
            : 'title';
        $orderby_meta = ! in_array( $orderby, $post_attributes, true ) ? 'meta_value' : false;

        $filter_with_status = isset( $attributes['filter_with_status'] ) 
            ? sanitize_text_field( $attributes['filter_with_status'] ) 
            : '';
        $show_end_date = isset( $attributes['show_end_date'] ) 
            ? sanitize_text_field( $attributes['show_end_date'] ) 
            : 'no';
        $show_event_location = isset( $attributes['show_event_location'] ) 
            ? sanitize_text_field( $attributes['show_event_location'] ) 
            : 'yes';

        $etn_desc_show = isset( $attributes['etn_desc_show'] ) 
            ? sanitize_text_field( $attributes['etn_desc_show'] ) 
            : 'yes';

        $show_remaining_tickets = isset( $attributes['show_remaining_tickets'] ) 
            ? sanitize_text_field( $attributes['show_remaining_tickets'] ) 
            : 'no';

        // Output buffering for rendering template
        ob_start();

        // Validate file inclusion
        $allowed_styles = ['event-1', 'event-2', 'event-3']; // Add allowed styles here
        if ( in_array( $style, $allowed_styles, true ) && file_exists( \Wpeventin::widgets_dir() . "events/style/{$style}.php" ) ) {
            include \Wpeventin::widgets_dir() . "events/style/{$style}.php";
        }

        return ob_get_clean();
    }

    /**
     * Events Tab shortcode
     */
    public function etn_events_tab( $attributes ) {

        $event_cats = null;
        $event_tag = null;
        $style            = !empty( $attributes["style"] ) ? $attributes["style"] : 'event-1';
        $allowed_styles = ['event-1', 'event-2']; // Add allowed styles here

        if ( isset( $attributes['event_cat_ids'] ) && $attributes['event_cat_ids'] !== '' ) {
            $event_cats = explode( ',', $attributes['event_cat_ids'] );
        }

        if ( isset( $attributes['event_tag_ids'] ) && $attributes['event_tag_ids'] !== '' ) {
            $event_tag = explode( ',', $attributes['event_tag_ids'] );
        }

        $posts_to_show    = isset( $attributes["limit"] ) && is_numeric( $attributes["limit"] ) && is_numeric( $attributes["limit"] ) <= 3 ? intval( $attributes["limit"] ) : 3;
        $order          = !empty( $attributes["order"] ) ? $attributes["order"] : 'DESC';
        $etn_desc_limit = !empty( $attributes["desc_limit"] ) ? $attributes["desc_limit"] : 20;        
        $etn_event_col  = !empty( $attributes["etn_event_col"] ) ? $attributes["etn_event_col"] : 20;

        if ( $etn_event_col == 6 ) {
            $etn_event_col = 2;
        } else if ( $etn_event_col == 5 ) {
            $etn_event_col = 2;
        } else if ( $etn_event_col == 4 ) {
            $etn_event_col = 3;
        } else if ( $etn_event_col == 3 ) {
            $etn_event_col = 4;
        } else if ( $etn_event_col == 2 ) {
            $etn_event_col = 6;
        } else if ( $etn_event_col == 1 ) {
            $etn_event_col = 12;
        }
        $widget_id = uniqid();
        $post_attributes    = ['title', 'ID', 'name', 'post_date'];
        $orderby            = !empty( $attributes["orderby"] ) ? $attributes["orderby"] : 'title';
        $orderby_meta       = in_array($orderby, $post_attributes) ? false : 'meta_value';
        $filter_with_status = !empty( $attributes["filter_with_status"] ) ? $attributes["filter_with_status"] : '';
        $show_end_date  = !empty( $attributes["show_end_date"] ) ? $attributes["show_end_date"] : 'no';


        $show_parent_event = ! empty( $attributes['show_parent_event'] ) ? $attributes['show_parent_event'] : 'no';
        $show_child_event  = ! empty( $attributes['show_child_event'] ) ? $attributes['show_child_event'] : 'yes';
        $post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );

        $show_event_location  = !empty( $attributes["show_event_location"] ) ? $attributes["show_event_location"] : 'yes';
        
        $etn_desc_show      = (isset($attributes["etn_desc_show"]) ? $attributes["etn_desc_show"] : 'yes');
        $show_remaining_tickets = !empty( $attributes["show_remaining_tickets"] ) ? $attributes["show_remaining_tickets"] : 'no';


        ob_start();
        if ( in_array( $style, $allowed_styles, true ) 
            && file_exists( \Wpeventin::widgets_dir() . "events/style/{$style}.php" ) ) {
            include \Wpeventin::widgets_dir() . "/events-tab/style/tab-1.php";

        }

        return ob_get_clean();
    }

    /**
     * Speakers shortcode
     */
    public function etn_speakers_widget( $attributes ) {

        $speakers_category  = isset( $attributes["cat_id"] ) && ( "" != $attributes["cat_id"] ) ? explode( ',', $attributes["cat_id"] ) : [];
        $etn_speaker_count  = isset( $attributes["limit"] ) && is_numeric( $attributes["limit"] ) ? $attributes["limit"] : 3;
        $etn_speaker_order  = !empty( $attributes["order"] ) ? $attributes["order"] : 'DESC';

        $etn_speaker_col  = !empty( $attributes["etn_speaker_col"] ) ? $attributes["etn_speaker_col"] : 3;

        if ( $etn_speaker_col == 6 ) {
            $etn_speaker_col = 2;
        } else if ( $etn_speaker_col == 5 ) {
            $etn_speaker_col = 2;
        } else if ( $etn_speaker_col == 4 ) {
            $etn_speaker_col = 3;
        } else if ( $etn_speaker_col == 3 ) {
            $etn_speaker_col = 4;
        } else if ( $etn_speaker_col == 2 ) {
            $etn_speaker_col = 6;
        } else if ( $etn_speaker_col == 1 ) {
            $etn_speaker_col = 12;
        }

        
        $post_attributes    = ['title', 'ID', 'name', 'post_date'];
        $orderby            = !empty( $attributes["orderby"] ) ? $attributes["orderby"] : 'title';
        $orderby_meta       = in_array($orderby, $post_attributes) ? false : 'meta_value';
        $style  = !empty( $attributes["style"] ) ? $attributes["style"] : 'speaker-1';
        $speakers_to_show   = $etn_speaker_count;


        ob_start();

        if ( file_exists( \Wpeventin::widgets_dir() . "speakers/style/speaker-2.php" ) && $style =='speaker-1' ) {
            include \Wpeventin::widgets_dir() . "speakers/style/speaker-2.php";
        }else if(file_exists( \Wpeventin::widgets_dir() . "speakers/style/speaker-3.php" ) && $style =='speaker-2'){
            include \Wpeventin::widgets_dir() . "speakers/style/speaker-3.php";
        }else{
            echo esc_html__('No style found', 'eventin');
        }

        return ob_get_clean();
    }

    /**
     * Schedule shortcode list
     */
    public function etn_schedules_list_widget( $attributes ) {
        $schedule_ids     = is_array( $attributes ) && isset( $attributes["ids"] ) ? $attributes["ids"] : "";
        $etn_schedule_id = explode( ",", $schedule_ids );
        $order            = isset( $attributes["order"] ) ? $attributes["order"] : 'asc';

        ob_start();

        if ( file_exists( \Wpeventin::widgets_dir() . "schedule-list/style/schedule-list-1.php" ) ) {
            include \Wpeventin::widgets_dir() . "schedule-list/style/schedule-list-1.php";
        }

        return ob_get_clean();
    }

    /**
     * Schedule shortcode tab
     */
    public function etn_schedules_widget( $attributes ) {
        $schedule_ids     = is_array( $attributes ) && isset( $attributes["ids"] ) ? $attributes["ids"] : "";
        $etn_schedule_ids = explode( ",", $schedule_ids );
        $order            = isset( $attributes["order"] ) ? $attributes["order"] : 'asc';

        ob_start();

        if ( file_exists( \Wpeventin::widgets_dir() . "schedule/style/schedule-1.php" ) ) {
            include \Wpeventin::widgets_dir() . "schedule/style/schedule-1.php";
        }

        return ob_get_clean();
    }

    /**
     * search filter widget
     */
    public function etn_search_filter(){
        ob_start();
        $widget_id = uniqid();
        ?>
            <div class="etn_search_<?php echo esc_attr( $widget_id ); ?> etn_search_wraper">

            <?php helper::get_event_search_form(); ?>
            <p class="etn_search_bottom_area_text"><?php
                $etn_search_count = count( helper::get_eventin_search_data() );
                // translators: %d is the number of upcoming and expired events.
                echo sprintf( esc_html( _n( 'Discover %d Upcoming and Expire Event', 'Discover %d Upcoming and Expire Events', $etn_search_count, 'eventin' ) ), absint( $etn_search_count ) );
            ?></p>
            </div>
        <?php
        return ob_get_clean();
    }
     
    /**
     * Zoom meeting info function
     */
    public function etn_zoom_api_link( $atts, $content ) {
        extract( shortcode_atts( ['meeting_id' => 'javascript:void(0);', 'link_only' => 'no'], $atts ) );

        ob_start();

        $event = new Event_Model( $meeting_id );
        $location = $event->etn_event_location;

        $integration = ! empty( $location['integration'] ) ? $location['integration'] : '';
        $meeting_link = $event->meeting_link;

        if ( ('online' == $event->event_type || 'hybrid' == $event->event_type) && 'zoom' == $integration ) {
            ?>
                <div class="meeting-wrapper">
                    <div class="meeting-row">
                        <div class="meeting-info">
                            <p><?php echo esc_html__( 'Join url', 'eventin' ); ?></p>
                        </div>
                        <div class="meeting-info info-right">
                            <a href="<?php echo esc_url( $meeting_link ) ?>"><?php echo esc_html__( 'Join url', 'eventin' ); ?></a>
                        </div>
                    </div><!-- row end -->
                </div><!-- mettin-wrapper end -->
            <?php
        } else {
            ?>
                <p><?php echo esc_html__( 'This is not a zoom event', 'eventin' ); ?></p>
            <?php
        }
        

        return ob_get_clean();
    }

    /**
     * fetch event meta info function
     */

    public function etn_event_meta_info( $attributes ) {
        $single_event_id = null;
        $single_event_id     = is_array( $attributes ) && isset( $attributes["event_id"] ) ? $attributes["event_id"] : "";
        
        ?>
        <div class="etn-sidebar">
            <?php
                \Etn\Templates\Event\Parts\EventDetailsParts::event_single_sidebar_meta( $single_event_id );
            ?>
        </div>
        <?php
    }

}
