<?php

/**
 * Template Model class
 * 
 * @package Eventin
 */
namespace Eventin\Template;

defined( 'ABSPATH' ) || exit;

use Etn\Base\Post_Model;
use Etn\Core\Attendee\Attendee_Model;
use Etn\Core\Event\Event_Model;

/**
 * Template Model
 */
class TemplateModel extends Post_Model {
    /**
     * Store post type
     *
     * @var string
     */
    protected $post_type = 'etn-template';

    /**
     * The actual event being rendered on the frontend single-event page.
     *
     * When a real event is rendered through a custom block template, do_blocks()
     * runs with the global $post set to the template post, so dynamic blocks can
     * no longer tell which event to render and fall back to the template's
     * design-time preview event. This static carries the real event id across
     * those freshly-constructed block-side TemplateModel instances so
     * get_preview_event_id() can return the event actually being viewed.
     *
     * @var int
     */
    protected static $rendering_event_id = 0;

    /**
     * Template properties
     *
     * @var array
     */
    protected $data = [
        'type'          => '',
        'orientation'   => '',
        'is_clone'      => '',
        'is_pro'        => '',
        'is_custom'     => '',
        'thumbnail'     => '',
        'template_css'  => '',
        'template_builder' => '',
        'preview_event_id' => '',
        'width'         => '',
        'height'        => '',
        'remote_template_id' => '',
    ];

    /**
     * Constructor for the TemplateModel class
     *
     * @param   mixed  $template
     *
     * @return  void
     */
    public function __construct( $template = 0) {
        parent::__construct( $template );
    }

    /**
     * Get template name
     *
     * @return  string  Return template title if post exist otherwise null
     */
    public function get_name() {
        $post = get_post( $this->id );

        if ( ! $post ) {
            return null;
        }

        return $post->post_title;
    }

    public function get_width() {
        return $this->width;
    }

    public function get_height() {
        return $this->height;
    }

    /**
     * Get template content
     *
     * @return  string  Return template content if post exist otherwise null
     */
    public function get_content() {
        $post = get_post( $this->id );

        if ( ! $post ) {
            return null;
        }

        return $post->post_content;
    }

    /**
     * Get template status
     *
     * @return  string  Return template content if post exist otherwise null
     */
    public function get_status() {
        $post = get_post( $this->id );

        if ( ! $post ) {
            return null;
        }

        return $post->post_status;
    }

    /**
     * Get template orientation
     *
     * @return  string  Return orientation Landscape or Protrait
     */
    public function get_orientation() {
        return $this->orientation;
    }

    /**
     * Get template type
     *
     * @return  string  Return template template type will be ticket, certificate, event
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * Get the template builder used to make this template
     * 
     * @return string The id of the template builder. Can be 'elementor', 'gutenberg'
     */
    public function get_template_builder() {
        return $this->template_builder;
    }

    /**
     * Get placeholders
     *
     * @return  array
     */
    /**
     * This attendee's own add-on selections (each attendee chooses independently).
     *
     * @param Attendee_Model $attendee Attendee.
     * @return array<int, array<string, mixed>>
     */
    protected function get_add_ons_rows( $attendee ) {
        $selections = $attendee->etn_option_selections;

        return is_array( $selections ) ? array_values( $selections ) : [];
    }

    /**
     * Render add-ons as an HTML list for the {{add_ons}} placeholder.
     *
     * @param Attendee_Model $attendee Attendee.
     * @return string
     */
    protected function get_add_ons_content( $attendee ) {
        $rows = $this->get_add_ons_rows( $attendee );

        if ( ! $rows ) {
            return '';
        }

        $items = '';
        foreach ( $rows as $row ) {
            $items .= sprintf(
                '<div class="wp-block-group ticket-row" style="display:flex;justify-content:space-between;width:100%%;">'
                    . '<p style="font-size:16px;font-weight:500;color:#2d3748;margin-bottom:0px;">%1$s: %2$s &times; %3$d</p>'
                    . '<p style="font-size:16px;font-weight:400;color:#4a5568;margin-bottom:0px;">%4$s</p>'
                    . '</div>',
                esc_html( $row['field_label'] ?? '' ),
                esc_html( $row['choice_value'] ?? '' ),
                (int) ( $row['qty'] ?? 1 ),
                esc_html( $row['line_total'] ?? '' )
            );
        }

        return '<div class="etn-ticket-add-ons">' . $items . '</div>';
    }

    /**
     * Render add-ons as an inline comma-separated string for {{add_ons_inline}}.
     *
     * @param Attendee_Model $attendee Attendee.
     * @return string
     */
    protected function get_add_ons_inline( $attendee ) {
        $rows = $this->get_add_ons_rows( $attendee );

        $parts = array_map(
            function ( $row ) {
                return sprintf(
                    '%s: %s (x%d)',
                    $row['field_label'] ?? '',
                    $row['choice_value'] ?? '',
                    (int) ( $row['qty'] ?? 1 )
                );
            },
            $rows
        );

        return implode( ', ', $parts );
    }

    public function get_place_holder( $attendee_id ) {
        $attendee = new Attendee_Model( $attendee_id );
        $event    = new Event_Model( $attendee->etn_event_id );

        $date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );

		$wp_timezone = wp_timezone();

		$start_dt = etn_parse_event_datetime( $event->etn_start_date, $event->etn_start_time, $wp_timezone );
		$end_dt   = etn_parse_event_datetime( $event->etn_end_date, $event->etn_end_time, $wp_timezone );

		$start_date = wp_date( $date_format, $start_dt->getTimestamp() );
		$end_date   = wp_date( $date_format, $end_dt->getTimestamp() );
		$start_time = wp_date( $time_format, $start_dt->getTimestamp() );
		$end_time   = wp_date( $time_format, $end_dt->getTimestamp() );

        $placeholders = [
            '{{event_title}}'         => esc_html( $event->get_title() ),
            '{{event_description}}'   => wp_kses_post( $event->get_description() ),
            '{{event_location}}'      => esc_html( $event->get_address() ),
            '{{event_start_date}}'    => esc_html( $start_date ),
            '{{event_end_date}}'      => esc_html( $end_date ),
            '{{event_start_time}}'    => esc_html( $start_time ),
            '{{event_end_time}}'      => esc_html( $end_time ),
            '{{event_timezone}}'      => esc_html( $event->event_timezone ),
            '{{ticket_price}}'        => esc_html( $attendee->etn_ticket_price ),
            '{{ticket_type}}'         => esc_html( $attendee->ticket_name ),
            '{{attendee_seat}}'       => esc_html( $attendee->attendee_seat ),
            '{{payment_status}}'      => esc_html( $attendee->etn_status ),
            '{{ticket_id}}'           => esc_html( $attendee->etn_unique_ticket_id ),
            '{{attendee_name}}'       => esc_html( $attendee->etn_name ),
            '{{attendee_email}}'      => esc_html( $attendee->etn_email ),
            '{{attendee_phone}}'      => esc_html( $attendee->etn_phone ),
            '{{extra_fields}}'        => wp_kses_post( $attendee->get_extra_fields_content() ),
            '{{extra_fields_inline}}' => esc_html( $attendee->get_extra_fields_inline() ),
            '{{add_ons}}'             => wp_kses_post( $this->get_add_ons_content( $attendee ) ),
            '{{add_ons_inline}}'      => esc_html( $this->get_add_ons_inline( $attendee ) ),
            // '{{qr_code}}',
        ];

        // Dynamic per-field tokens: {{extra_field_KEY}}.
        $extra_field_files = $attendee->get_extra_field_files();

        foreach ( $attendee->get_extra_fields() as $key => $value ) {
            $token = '{{extra_field_' . $key . '}}';

            if ( isset( $extra_field_files[ $key ] ) ) {
                $placeholders[ $token ] = $this->render_extra_field_token( $key, $extra_field_files[ $key ] );
                continue;
            }

            $plain = is_array( $value ) ? implode( ', ', $value ) : (string) $value;

            /**
             * Filters the rendered HTML for a non-file extra-field placeholder.
             *
             * @since 4.1.13
             *
             * @param string         $rendered Escaped plain-text value.
             * @param string         $key      Extra-field key.
             * @param mixed          $value    Raw stored value.
             * @param Attendee_Model $attendee Current attendee model.
             */
            $placeholders[ $token ] = apply_filters(
                'eventin_template_extra_field_token',
                esc_html( $plain ),
                $key,
                $value,
                $attendee
            );
        }

        return $placeholders;
    }

    /**
     * Render the HTML for a single file-type extra-field placeholder token.
     *
     * Image attachments render inline; other mime types render as a download
     * link. Output is escaped per token piece, not via wp_kses, because the
     * caller pushes the value through `strtr()` on a raw HTML template.
     *
     * @since 4.1.13
     *
     * @param string $key  Extra-field key.
     * @param array  $file Resolved file metadata (`id`, `url`, `mime`, `filename`).
     * @return string Escaped HTML safe for output.
     */
    protected function render_extra_field_token( $key, $file ) {
        $url      = esc_url( $file['url'] );
        $mime     = isset( $file['mime'] ) ? (string) $file['mime'] : '';
        $filename = isset( $file['filename'] ) ? $file['filename'] : wp_basename( $file['url'] );
        $name     = esc_html( $filename );

        if ( 0 === strpos( $mime, 'image/' ) ) {
            /**
             * Filters the inline `<img>` style used for image extra-field tokens.
             *
             * @since 4.1.13
             *
             * @param string $style Default inline CSS.
             * @param string $key   Extra-field key.
             * @param array  $file  Resolved file metadata.
             */
            $image_style = apply_filters(
                'eventin_template_extra_field_image_style',
                'width:80px;height:80px;display:inline-block;vertical-align:middle;object-fit:cover;border-radius:4px;',
                $key,
                $file
            );

            $rendered = sprintf(
                '<img src="%1$s" alt="%2$s" style="%3$s" />',
                $url,
                $name,
                esc_attr( $image_style )
            );
        } else {
            $rendered = sprintf(
                '<a href="%1$s" target="_blank" rel="noopener">%2$s</a>',
                $url,
                $name
            );
        }

        /**
         * Filters the rendered HTML for a file-type extra-field placeholder.
         *
         * @since 4.1.13
         *
         * @param string $rendered Default rendered HTML.
         * @param string $key      Extra-field key.
         * @param array  $file     Resolved file metadata.
         */
        return apply_filters( 'eventin_template_extra_field_file_token', $rendered, $key, $file );
    }

    /**
     * Get event placeholders for event pages (without attendee data)
     *
     * @since 1.0.0
     * @param   int  $event_id  Event post ID
     *
     * @return  array
     */
    public function get_event_placeholders( $event_id ) {
        if ( ! $event_id ) {
            return [];
        }

        $event = new Event_Model( $event_id );

        $date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );

		$wp_timezone = wp_timezone();

		$start_dt = etn_parse_event_datetime( $event->etn_start_date, $event->etn_start_time, $wp_timezone );
		$end_dt   = etn_parse_event_datetime( $event->etn_end_date, $event->etn_end_time, $wp_timezone );

		$start_date = wp_date( $date_format, $start_dt->getTimestamp() );
		$end_date   = wp_date( $date_format, $end_dt->getTimestamp() );
		$start_time = wp_date( $time_format, $start_dt->getTimestamp() );
		$end_time   = wp_date( $time_format, $end_dt->getTimestamp() );

        return [
            '{{event_title}}'         => esc_html( $event->get_title() ),
            '{{event_description}}'   => wp_kses_post( $event->get_description() ),
            '{{event_location}}'      => esc_html( $event->get_address() ),
            '{{event_start_date}}'    => esc_html( $start_date ),
            '{{event_end_date}}'      => esc_html( $end_date ),
            '{{event_start_time}}'    => esc_html( $start_time ),
            '{{event_end_time}}'      => esc_html( $end_time ),
            '{{event_timezone}}'      => esc_html( $event->event_timezone ),
        ];
    }

    /**
     * Get html content
     *
     * @return  string
     */
    public function get_html_content() {
        global $post;
        $original_post = $post;

        $template_post = get_post( $this->id );
        if ( $template_post ) {
            $post = $template_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- intentionally set so dynamic blocks detect etn-template context during do_blocks().
            setup_postdata( $post );
        }

        $content = $this->swap_cover_banner( $this->get_content() );
        $content = $this->add_proxy_image( $content );
        $content = do_blocks( $content ); // Process Gutenberg blocks
        $content = do_shortcode( $content ); // Process shortcodes
        $content = $this->prioritize_lcp_image( $content ); // Hint the cover image as LCP

        $post = $original_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
        if ( $original_post ) {
            setup_postdata( $original_post );
        } else {
            wp_reset_postdata();
        }

        return $content;
    }

    /**
     * Get fully rendered content, handling both Elementor and Gutenberg.
     *
     * @return string
     */
    private function get_rendered_content() {
        if ( did_action( 'elementor/loaded' ) ) {
            $document = \Elementor\Plugin::$instance->documents->get( $this->id );

            if ( $document && $document->is_built_with_elementor() ) {
                $content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $this->id, true );
                return $this->add_proxy_image( $content );
            }
        }

        return $this->get_html_content();
    }

    /**
     * Returns the event id used for previewing the template
     * 
     * @return int|string returns event id or emptry string
     */
    public function get_preview_event_id() {
        // On the frontend single-event page the block is rendering a real event
        // through this template; show that event rather than the template's
        // design-time preview event.
        if ( self::$rendering_event_id ) {
            $rendering_post = get_post( self::$rendering_event_id );

            if ( $rendering_post && 'etn' === $rendering_post->post_type ) {
                return $rendering_post->ID;
            }
        }

        $event_id = $this->preview_event_id;
        if ( empty( $event_id ) ) {
            $event_id = $this->get_available_event_id();
        }

        if ( empty( $event_id ) ) {
            return 0;
        }

        $post = get_post( $event_id );

        if ( $post && 'etn' === $post->post_type ) {
            return $post->ID;
        }

        return 0;
    }
    /**
     * Retrieves the fallback event id used when a template has no selected
     * preview event.
     *
     * Prefers the shipped "preview placeholder" event so every template previews
     * against consistent, fully-populated data; falls back to the first published
     * event (lowest id) if the placeholder is missing (e.g. deleted by the site owner).
     *
     * @return int $event_id
     */
    private function get_available_event_id() {
        // Prefer the shipped preview placeholder event when present.
        if ( \Eventin\PreviewPlaceholder\PreviewPlaceholder::event_exists() ) {
            return \Eventin\PreviewPlaceholder\PreviewPlaceholder::event_id();
        }

        $args = array(
            'post_type'      => 'etn',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'orderby'        => 'ID',
            'order'          => 'ASC',
        );

        $query = new \WP_Query( $args );

        if ( $query->have_posts() ) {
            return (int) $query->posts[0];
        }

        return 0;
    }

    /**
     * Render html with actual value
     *
     * @param   array  $data
     *
     * @return
     */
    public function get_rendable_content( $attendee_id ) {
        if ( ! $attendee_id ) {
            return null;
        }

        $placeholder = $this->get_place_holder( $attendee_id );

        $rendered = $this->get_rendered_content();
        $content  = strtr( $rendered, $placeholder );

        // Remove any extra_field tokens that had no matching attendee data.
        $content = preg_replace( '/\{\{extra_field_[^}]+\}\}/', '', $content );

        // Fallback for templates saved before the add-ons feature: they have no
        // {{add_ons}} token, which would silently drop an attendee's purchased
        // add-ons. When the token is absent but this attendee has selections,
        // append the add-ons block so paid add-ons are never lost. Templates that
        // already position {{add_ons}} are untouched (it was substituted above).
        if ( false === strpos( (string) $rendered, '{{add_ons}}' ) ) {
            $add_ons = $this->get_add_ons_content( new Attendee_Model( $attendee_id ) );

            if ( '' !== $add_ons ) {
                $content .= wp_kses_post( $add_ons );
            }
        }

        return $content;
    }

    /**
     * Render html with actual event values (without attendee data)
     *
     * @since 1.0.0
     * @param   int  $event_id  Event post ID
     *
     * @return  string|null
     */
    public function get_rendable_event_content( $event_id ) {
        if ( ! $event_id ) {
            return null;
        }

        $placeholder = $this->get_event_placeholders( $event_id );

        // Expose the real event to dynamic blocks so they resolve it instead of
        // the template's preview event while do_blocks() runs against the
        // template post. Save/restore keeps nested template renders correct.
        $previous_event_id        = self::$rendering_event_id;
        self::$rendering_event_id = (int) $event_id;

        try {
            return strtr( $this->get_rendered_content(), $placeholder );
        } finally {
            self::$rendering_event_id = $previous_event_id;
        }
    }

    /**
     * Render html with actual value
     *
     * @param   array  $data  
     *
     * @return  
     */
    public function get_default_rendable_content( $attendee_id, $template_name ) {
        if ( ! $attendee_id ) {
            return null;
        }


        $placeholder = $this->get_place_holder( $attendee_id );

        $template = DefaultTemplate::get_template( $template_name );

        $content = strtr( $template['content'], $placeholder );

        // Remove any extra_field tokens that had no matching attendee data.
        $content = preg_replace( '/\{\{extra_field_[^}]+\}\}/', '', $content );

        return $content;
    }

    /**
     * Render block content
     *
     * @since 1.0.0
     * @param   string  $template_name  Template name for default templates
     * @param   int     $event_id       Event ID for placeholder replacement
     *
     * @return  void
     */
    public function render_content( $template_name = '', $event_id = 0 ) {
        if ( ! empty( $template_name ) ) {
            $template = DefaultTemplate::get_template( $template_name );

            if ( ! empty( $template['content'] ) ) {
                $content = $template['content'];

                // Replace placeholders if event ID is provided
                if ( $event_id ) {
                    $placeholder = $this->get_event_placeholders( $event_id );
                    $content = strtr( $content, $placeholder );
                }

                echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Full HTML template; placeholders are escaped in get_place_holder()
            }

            return;
        }

        if ( $event_id ) {
            echo $this->get_rendable_event_content( $event_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Full HTML template; placeholders are escaped in get_event_placeholders()
        } else {
            echo $this->get_rendered_content(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Full HTML template rendered via Elementor or WP blocks
        }
    }


    /**
     * Render demo content
     *
     * @return  void
     */
    public function get_demo_content() {
        $placeholder = $this->get_demo_placeholder();
        $content     = strtr( $this->get_rendered_content(), $placeholder );

        // Dynamically replace any remaining {{extra_field_KEY}} tokens with a
        // human-readable demo label derived from the key (e.g. "gender_1" → "Gender").
        $content = preg_replace_callback(
            '/\{\{extra_field_([^}]+)\}\}/',
            function ( $matches ) {
                $label = ucwords( str_replace( '_', ' ', preg_replace( '/_\d+$/', '', $matches[1] ) ) );
                return $label;
            },
            $content
        );

        return $content;
    }

    /**
     * Get demo placeholder
     *
     * @return  array
     */
    public function get_demo_placeholder() {
        $date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );

        return [
            '{{event_title}}'         => 'Event Title',
            '{{event_description}}'   => 'This is a sample event description.',
            '{{event_location}}'      => 'Springfield, IL 62701, United States',
            '{{event_start_date}}'  => gmdate( $date_format ),
            '{{event_end_date}}'    => gmdate( $date_format ),
            '{{event_start_time}}'  => gmdate( $time_format ),
            '{{event_end_time}}'    => gmdate( $time_format ),
            '{{event_timezone}}'    => 'America/New_York',
            '{{ticket_price}}'      => 500,
            '{{ticket_type}}'       => 'VIP',
            '{{payment_status}}'    => 'success',
            '{{ticket_id}}'         => '#nr5s1v40d4',
            '{{attendee_name}}'     => 'John Doe',
            '{{attendee_seat}}'     => 'Vip-01',
            '{{attendee_email}}'    => 'john@gmail.com',
            '{{attendee_phone}}'    => '995571089087',
            '{{extra_fields}}'        => '<strong>Company:</strong> Acme Corp<br><strong>Job Title:</strong> Developer<br><strong>Phone:</strong> +1 555 1234',
            '{{extra_fields_inline}}' => 'Company: Acme Corp, Job Title: Developer',
            '{{extra_field_etn_phone}}' => '995571089087',
            '{{add_ons}}'             => '<div class="etn-ticket-add-ons"><div class="wp-block-group ticket-row" style="display:flex;justify-content:space-between;width:100%;"><p style="font-size:16px;font-weight:500;color:#2d3748;margin-bottom:0px;">Dinner: Veg &times; 2</p><p style="font-size:16px;font-weight:400;color:#4a5568;margin-bottom:0px;">40</p></div></div>',
            '{{add_ons_inline}}'      => 'Dinner: Veg (x2)',
            // '{{qr_code}}',
        ];
    }

    /**
     * Swap a pre-built template's hardcoded demo banner for the real event banner.
     *
     * Event templates 6-9 render their hero as a static wp-block-cover whose
     * background image is a baked-in demo asset (feature-image*.webp), not the
     * dynamic {@see \Eventin\Blocks\BlockTypes\EventBanner} block. When such a
     * template renders a real event on the front-end single page we replace the
     * first cover-background image with that event's own banner, so a live event
     * never shows the design-time demo image. Templates that use the dynamic
     * event-banner block carry no cover-background <img> and are left untouched.
     *
     * Only runs while a real event is being rendered ($rendering_event_id set),
     * so the editor/template preview keeps its demo design. Runs before
     * add_proxy_image() because the event banner is same-origin and must not be
     * proxied.
     *
     * @param   string  $content  Raw block markup (before do_blocks()).
     *
     * @return  string
     */
    private function swap_cover_banner( $content ) {
        if ( ! self::$rendering_event_id ) {
            return $content;
        }

        $event  = new Event_Model( self::$rendering_event_id );
        $banner = $event->event_banner;

        if ( empty( $banner ) ) {
            return $content;
        }

        // Locate the hero cover-background image and read its current (demo) src.
        if ( ! preg_match( '/<img[^>]*\bwp-block-cover__image-background\b[^>]*>/i', $content, $img ) ) {
            return $content;
        }

        if ( ! preg_match( '/\ssrc="([^"]+)"/i', $img[0], $src ) ) {
            return $content;
        }

        $demo_url = $src[1];

        if ( $demo_url === $banner ) {
            return $content;
        }

        // The demo URL appears in both the cover block's "url" attribute and the
        // inner <img> src; replacing the exact string swaps both so do_blocks()
        // outputs the real banner. Scoped to that URL, so other images are safe.
        return str_replace( $demo_url, esc_url( $banner ), $content );
    }

    /**
     * Replace image and backgroud url for remote server
     *
     * @param   string  $content
     *
     * @return  string
     */
    public function add_proxy_image( $content ) {
        $proxy_url_base = site_url() . '?action=proxy_image&url=';

        // Replace all image url.
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $content, $matches);

        if ( ! empty( $matches[1] ) ) {
            foreach ( $matches[1] as $image_url ) {
                if ( ! $this->is_same_origin( $image_url ) ) {
                    $proxied_image_url = $proxy_url_base . urlencode($image_url);
                    $content = str_replace( $image_url, $proxied_image_url, $content );
                }
            }
        }

        // Replace all background image url
        preg_match_all( '/background-image\s*:\s*url\(["\']?([^"\')]+)["\']?\)/i', $content, $background_matches );

        if ( ! empty( $background_matches[1] ) ) {
            foreach ( $background_matches[1] as $bg_image_url ) {
        
                if ( ! $this->is_same_origin( $bg_image_url ) ) {
                    $proxied_bg_image_url = $proxy_url_base . urlencode( $bg_image_url );
                    $content = str_replace( $bg_image_url, $proxied_bg_image_url, $content );
                }
            }
        }

        return $content;
    }

    /**
     * Mark the first image (the cover/banner) as the LCP element.
     *
     * Adds fetchpriority="high" so the browser discovers and loads the banner
     * before lower-priority assets, and strips loading="lazy" off it so it is
     * never deferred. Only the first <img> is touched; everything below the
     * fold keeps default lazy behaviour.
     *
     * @param   string  $content
     *
     * @return  string
     */
    public function prioritize_lcp_image( $content ) {
        return preg_replace_callback(
            '/<img\b(?![^>]*\bfetchpriority=)([^>]*)>/i',
            function ( $matches ) {
                $attrs = preg_replace( '/\s*loading\s*=\s*(["\'])lazy\1/i', '', $matches[1] );

                return '<img fetchpriority="high" decoding="async"' . $attrs . '>';
            },
            $content,
            1
        );
    }

    /**
     * Site url and requested url are same  or not
     *
     * @param   string  $url
     *
     * @return  bool
     */
    public function is_same_origin( $url ) {
        return strpos( $url, site_url() ) !== false;
    }
}
