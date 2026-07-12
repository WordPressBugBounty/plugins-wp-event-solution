<?php

namespace Eventin\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Inline image handler for outgoing emails.
 *
 * Images inserted in the Email Automation body editor are embedded as base64
 * `data:` URIs (e.g. `src="data:image/png;base64,..."`). Two things break them
 * before they ever reach the inbox:
 *
 *   1. The email templates render the body through `wp_kses_post()`, which strips
 *      the `data:` prefix (it is not an allowed protocol) and leaves a broken
 *      `src="image/png;base64,..."`.
 *   2. Even if it survived, most email clients (Gmail, Outlook.com, Yahoo) refuse
 *      to render `data:` URI images for security reasons.
 *
 * This handler runs on the RAW body, writes each embedded base64 image to a
 * publicly hosted file under the uploads directory, and rewrites the source to
 * that URL so the image renders in every email client.
 *
 * IMPORTANT: it hooks `notification_sdk_email_message` at priority 5 — BEFORE
 * `Eventin\Emails\EnsHooks` (priority 10), which renders the template and applies
 * `wp_kses_post()`. Running first means the `data:` URI is replaced with a real
 * `http(s)` URL while it is still intact; `wp_kses_post()` then preserves that
 * URL because `http(s)` is an allowed protocol. Hooking the later
 * `notification_sdk_email_body` filter (as a previous version did) is too late:
 * by then `wp_kses_post()` has already mangled the `data:` prefix and the regex
 * has nothing to match.
 *
 * @package eventin
 */
class InlineImageHandler {

    /**
     * Sub directory, relative to the uploads base dir, where inline email
     * images are stored.
     */
    const SUBDIR = 'eventin-email-images';

    /**
     * Supported image mime subtypes mapped to file extensions.
     *
     * @var array
     */
    private $extensions = [
        'png'     => 'png',
        'jpeg'    => 'jpg',
        'jpg'     => 'jpg',
        'gif'     => 'gif',
        'webp'    => 'webp',
        'bmp'     => 'bmp',
        'svg+xml' => 'svg',
    ];

    /**
     * Constructor.
     */
    public function __construct() {
        add_filter( 'notification_sdk_email_message', [ $this, 'convert_data_uri_images' ], 5 );
        add_filter( 'rest_pre_dispatch', [ $this, 'allow_data_uri_on_flow_save' ], 10, 3 );
    }

    /**
     * Preserve base64 `data:` image URIs when an Email Automation flow is saved.
     *
     * The bundled email SDK sanitizes the whole flow config through the global
     * `ens_sanitize_recursive()`, which runs each string through
     * `wp_kses( $html, 'post', wp_allowed_protocols() )`. `data` is not a core
     * allowed protocol, so kses rewrites `src="data:image/png;base64,…"` down to
     * a broken `src="image/png;base64,…"` — the `data:` prefix is destroyed in
     * storage, before the send-time {@see self::convert_data_uri_images()} ever
     * sees it. (The problem is amplified on sites running several ThemeWinter
     * plugins: `ens_sanitize_recursive()` is a `function_exists`-guarded global,
     * so an older copy from whichever sibling plugin loads first can win.)
     *
     * `wp_kses()` builds its protocol list from `wp_allowed_protocols()`, which
     * fires the `kses_allowed_protocols` filter — so adding `data` there lets the
     * URI survive the save intact. The allowance is scoped to the flow-save REST
     * request only (not registered globally) to keep the extra `data:` surface
     * off every other kses call on the site.
     *
     * @param mixed            $result  Response to short-circuit with, or null.
     * @param \WP_REST_Server  $server  Server instance.
     * @param \WP_REST_Request $request Current request.
     *
     * @return mixed Untouched $result.
     */
    public function allow_data_uri_on_flow_save( $result, $server, $request ) {
        if ( ! $request instanceof \WP_REST_Request ) {
            return $result;
        }

        $is_write = in_array( $request->get_method(), [ 'POST', 'PUT', 'PATCH' ], true );

        if ( $is_write && false !== strpos( (string) $request->get_route(), '/notification-flow' ) ) {
            add_filter( 'kses_allowed_protocols', [ $this, 'allow_data_protocol' ] );
        }

        return $result;
    }

    /**
     * Add `data` to the kses allowed-protocol list.
     *
     * @param array $protocols Allowed protocols.
     *
     * @return array
     */
    public function allow_data_protocol( $protocols ) {
        $protocols[] = 'data';

        return $protocols;
    }

    /**
     * Replace every base64 `data:` image URI in the body with a hosted URL.
     *
     * @param string $message Email body HTML.
     *
     * @return string
     */
    public function convert_data_uri_images( $message ) {
        if ( ! is_string( $message ) || false === strpos( $message, 'data:image/' ) ) {
            return $message;
        }

        // Matches data:image/<subtype>;base64,<base64 payload>. The base64
        // alphabet contains no quote/whitespace characters, so the match stops
        // cleanly at the closing attribute quote.
        $pattern = '#data:image/([a-z0-9.+-]+);base64,([A-Za-z0-9+/=]+)#i';

        return preg_replace_callback(
            $pattern,
            function ( $matches ) {
                $url = $this->store_image( strtolower( $matches[1] ), $matches[2] );

                // On any failure keep the original data URI untouched.
                return $url ? $url : $matches[0];
            },
            $message
        );
    }

    /**
     * Decode a base64 image and store it as a hosted file, returning its URL.
     *
     * Files are named by the md5 hash of their contents so identical images are
     * written once and reused across every send.
     *
     * @param string $subtype Image mime subtype (e.g. png, jpeg, svg+xml).
     * @param string $base64  Base64 encoded image payload.
     *
     * @return string|false Public file URL, or false on failure.
     */
    private function store_image( $subtype, $base64 ) {
        if ( ! isset( $this->extensions[ $subtype ] ) ) {
            return false;
        }

        $binary = base64_decode( $base64, true );

        if ( false === $binary || '' === $binary ) {
            return false;
        }

        $extension = $this->extensions[ $subtype ];
        $filename  = md5( $binary ) . '.' . $extension;

        $upload_dir = wp_upload_dir();

        if ( ! empty( $upload_dir['error'] ) ) {
            return false;
        }

        $target_dir  = trailingslashit( $upload_dir['basedir'] ) . self::SUBDIR;
        $target_file = trailingslashit( $target_dir ) . $filename;
        $target_url  = trailingslashit( $upload_dir['baseurl'] ) . self::SUBDIR . '/' . $filename;

        if ( ! file_exists( $target_file ) ) {
            if ( ! wp_mkdir_p( $target_dir ) ) {
                return false;
            }

            if ( false === file_put_contents( $target_file, $binary ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
                return false;
            }
        }

        return $target_url;
    }
}
