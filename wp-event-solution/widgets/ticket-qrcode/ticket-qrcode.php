<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

class Etn_Ticket_Qrcode extends Widget_Base {

    public function get_name() {
        return 'etn-ticket-qrcode';
    }

    public function get_title() {
        return esc_html__( 'Ticket QR Code', 'eventin' );
    }

    public function get_icon() {
        return 'eicon-barcode';
    }

    public function get_screenshot() {
        return \Wpeventin::assets_url() . 'images/qr.webp';
    }

    public function get_categories() {
        return ['etn-event'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'QR Code', 'eventin' ),
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label'     => esc_html__( 'Alignment', 'eventin' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__( 'Left', 'eventin' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'eventin' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__( 'Right', 'eventin' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eventin-ticket-qrcode' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'QR Code', 'eventin' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label'      => esc_html__( 'Padding', 'eventin' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eventin-ticket-qrcode' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label'      => esc_html__( 'Margin', 'eventin' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default'    => [
                    'top'    => '10',
                    'right'  => '0',
                    'bottom' => '0',
                    'left'   => '0',
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eventin-ticket-qrcode' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            $qr_url = \Wpeventin::assets_url() . 'images/qr.webp';
            ?>
            <div class="eventin-ticket-qrcode">
                <img src="<?php echo esc_url( $qr_url ); ?>"
                     alt="<?php esc_attr_e( 'Ticket QR Code', 'eventin' ); ?>"
                     style="max-width: 150px; height: auto; display: inline-block;" />
            </div>
            <?php
            return;
        }

        if ( ! class_exists( 'Wpeventin_Pro' ) ) {
            echo '<p>' . esc_html__( 'Ticket QR Code requires Eventin Pro.', 'eventin' ) . '</p>';
            return;
        }

        if ( defined( 'ETN_PRO_ASSETS' ) ) {
            if ( ! wp_script_is( 'etn-qr-code-block', 'registered' ) ) {
                wp_register_script( 'etn-qr-code-block', ETN_PRO_ASSETS . 'js/qr-code.js', [ 'jquery' ], \Wpeventin::version(), false );
            }
            if ( ! wp_script_is( 'etn-qr-code-custom-block', 'registered' ) ) {
                wp_register_script( 'etn-qr-code-custom-block', ETN_PRO_ASSETS . 'js/qr-code-custom.js', [ 'jquery', 'etn-qr-code-block' ], \Wpeventin::version(), false );
            }
            wp_enqueue_script( 'etn-qr-code-custom-block' );
        }

        $attendee_id       = ! empty( $_GET['attendee_id'] ) ? intval( $_GET['attendee_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- attendee_id cast to int via intval().
        $unique_id         = get_post_meta( $attendee_id, 'etn_unique_ticket_id', true );
        $ticket_verify_url = admin_url( '/edit.php?post_type=etn-attendee&etn_action=ticket_scanner' );
        $ticket_verify_url .= "&attendee_id=$attendee_id&ticket_id=$unique_id";
        ?>
        <div class="eventin-ticket-qrcode">
            <p class="etn-ticket-id" id="ticketUnqId" data-ticketverifyurl="<?php echo esc_url( $ticket_verify_url ); ?>"></p>
            <img class="etn-qrImage" src="" alt="" id="qrImage" />
        </div>
        <?php
    }
}
