<?php
namespace Eventin\Vendor\Ens\Whatsapp;

use Eventin\Vendor\Ens\Utils\Helpers;

/**
 * Class WhatsappSender
 *
 * @package Ens\Whatsapp
 *
 * @since 1.0.0
 */
class WhatsappSender {

    protected $to;
    protected $message;
    protected $action_data;
    protected $action_name;
    protected $receiver_type;
    protected $count;
    protected $message_type;
    protected $template;
    protected $identifier;

    /**
     * WhatsappSender constructor.
     *
     * Expected $args keys:
     *   - identifier    string  Consumer plugin identifier (scopes the send hook).
     *   - action_name   string
     *   - receiver_type string
     *   - to            string
     *   - message       string  (used for `text` message_type)
     *   - message_type  string  (`text` or `template`, defaults to `text`)
     *   - template      array   (required when message_type=template):
     *       - name        string  Approved template name in Meta Business Manager.
     *       - language    string  Locale code (default `en_US`).
     *       - body_params array   Ordered parameter values; placeholders are resolved against action_data.
     *   - action_data   array
     *   - count         int
     *
     * @since 1.0.0
     *
     * @param array $args
     */
    public function __construct( array $args ) {
        $this->identifier    = $args['identifier']    ?? '';
        $this->action_name   = $args['action_name']   ?? '';
        $this->receiver_type = $args['receiver_type'] ?? '';
        $this->to            = $args['to']            ?? '';
        $this->action_data   = $args['action_data']   ?? [];
        $this->count         = $args['count']         ?? 0;
        $this->message_type  = isset( $args['message_type'] ) && 'template' === $args['message_type'] ? 'template' : 'text';
        $this->template      = isset( $args['template'] ) && is_array( $args['template'] ) ? $args['template'] : null;

        $message_content = $this->replace_placeholders( $args['message'] ?? '', $this->action_data );
        $this->message   = apply_filters(
            'notification_sdk_whatsapp_message',
            $message_content,
            $this->receiver_type,
            $this->action_name,
            $this->action_data,
            $this->count
        );

        if ( $this->template && ! empty( $this->template['body_params'] ) && is_array( $this->template['body_params'] ) ) {
            $action_data = $this->action_data;
            $this->template['body_params'] = array_map(
                function ( $param ) use ( $action_data ) {
                    return $this->replace_placeholders( (string) $param, $action_data );
                },
                $this->template['body_params']
            );
        }

        if ( $this->template ) {
            /**
             * Fill template variables per recipient.
             *
             * Scoped to the consumer plugin. `$count` is the recipient's position
             * in a list receiver (for example the 2nd attendee), so the plugin can
             * give each one their own values.
             */
            $params = apply_filters(
                Helpers::get_hook_name( $this->identifier, 'notification_sdk_whatsapp_template_params' ),
                isset( $this->template['body_params'] ) && is_array( $this->template['body_params'] ) ? $this->template['body_params'] : [],
                $this->receiver_type,
                $this->action_name,
                $this->action_data,
                $this->count
            );

            $this->template['body_params'] = is_array( $params ) ? array_values( $params ) : [];
        }
    }

    /**
     * Send the WhatsApp message by firing the dispatch action.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function send() {
        // Scoped per plugin: several plugins bundle this SDK, and a shared
        // hook name would make every plugin's provider send the same message.
        do_action(
            Helpers::get_hook_name( $this->identifier, 'notification_sdk_send_whatsapp' ),
            $this->to,
            $this->message,
            $this->action_data,
            $this->action_name,
            $this->receiver_type,
            $this->count,
            $this->message_type,
            $this->template
        );
    }

    /**
     * Replace placeholders in a template.
     *
     * @since 1.0.0
     *
     * @param string $template
     * @param array  $data
     *
     * @return string
     */
    public function replace_placeholders( $template, $data ) {
        if ( ! is_array( $data ) ) {
            return $template;
        }
        foreach ( $data as $key => $value ) {
            if ( ! is_array( $value ) ) {
                $template = str_replace( '{{' . $key . '}}', $value, $template );
                $template = str_replace( '{%' . $key . '%}', $value, $template );
            }
        }
        return $template;
    }
}
