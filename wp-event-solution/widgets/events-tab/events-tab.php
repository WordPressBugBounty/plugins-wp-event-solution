<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

class Etn_Events_Tab extends Widget_Base {

    /**
     * Retrieve the widget name.
     * @return string Widget name.
     */
    public function get_name() {
        return 'etn-event-tab';
    }

    /**
     * Retrieve the widget title.
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Eventin Events Tab', 'wp-event-solution' );
    }

    /**
     * Retrieve the widget icon.
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-sort-amount-desc';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     * Used to determine where to display the widget in the editor.
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['etn-event'];
    }

    /**
     * Register the widget controls.
     * @access protected
     */
    protected function register_controls() {

        // Start of event section
        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__( 'Eventin Events Tab', 'wp-event-solution' ),
            ]
        );
        $this->add_control(
            'etn_event_style',
            [
                'label'   => esc_html__( 'Event Style', 'wp-event-solution' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'event-1',
                'options' => [
                    'event-1' => esc_html__( 'Event 1', 'wp-event-solution' ),
                    'event-2' => esc_html__( 'Event 2', 'wp-event-solution' ),
                ],
            ]
        );
        $this->add_control(
            'etn_event_cat',
            [
                'label'    => esc_html__( 'Event Category', 'wp-event-solution' ),
                'type'     => Controls_Manager::SELECT2,
                'options'  => $this->get_event_category(),
                'multiple' => true,
            ]
        );
        $this->add_control(
            'etn_event_tag',
            [
                'label'    => esc_html__( 'Event Tag', 'wp-event-solution' ),
                'type'     => Controls_Manager::SELECT2,
                'options'  => $this->get_event_tag(),
                'multiple' => true,
            ]
        );
        $this->add_control(
            'etn_event_count',
            [
                'label'   => esc_html__( 'Event count', 'wp-event-solution' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '6',
            ]
        );

        $this->add_control(
            'etn_desc_show',
            [
                'label'   => esc_html__( 'Show Description', 'wp-event-solution' ),
                'type'    => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wp-event-solution' ),
				'label_off' => esc_html__( 'No', 'wp-event-solution' ),
				'return_value' => 'yes',
				'default' => 'yes',
            ]
        );

        $this->add_control(
            'etn_desc_limit',
            [
                'label'   => esc_html__( 'Description Limit', 'wp-event-solution' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 20,
                'condition' => ['etn_desc_show' => 'yes'],
            ]
        );

        $this->add_control(
            'etn_event_col',
            [
                'label'   => esc_html__( 'Event column', 'wp-event-solution' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '3' => esc_html__( '4 Column ', 'wp-event-solution' ),
                    '4' => esc_html__( '3 Column', 'wp-event-solution' ),
                    '6' => esc_html__( '2 Column', 'wp-event-solution' ),

                ],
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'     => esc_html__( 'Order Event By', 'wp-event-solution' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'post_date',
                'options'   => [
                    'ID'        => esc_html__( 'Id', 'wp-event-solution' ),
                    'title'     => esc_html__( 'Title', 'wp-event-solution' ),
                    'post_date' => esc_html__( 'Post Date', 'wp-event-solution' ),
                    'etn_start_date' => esc_html__( 'Event Start Date', 'wp-event-solution' ),
                    'etn_end_date' => esc_html__( 'Event End Date', 'wp-event-solution' ),
                ],
                'condition' => ['etn_event_style' => ['event-1']],
            ]
        );
        $this->add_control(
            'filter_with_status',
            [
                'label'     => esc_html__( 'Event status filter By', 'wp-event-solution' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '',
                'options'   => [
                    ''        => esc_html__( 'All', 'wp-event-solution' ),
                    'upcoming' => esc_html__( 'upcoming Event', 'wp-event-solution' ),
                    'expire' => esc_html__( 'Expire Event', 'wp-event-solution' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__( 'Event Order', 'wp-event-solution' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC' => esc_html__( 'Ascending', 'wp-event-solution' ),
                    'DESC' => esc_html__( 'Descending', 'wp-event-solution' ),
                ],
            ]
        );

        $this->add_control(
					'show_parent_event',
					[
							'label'   => esc_html__( 'Show Recurring Parent Events', 'wp-event-solution' ),
							'type'    => Controls_Manager::SWITCHER,
							'label_on' => esc_html__( 'Yes', 'wp-event-solution' ),
					'label_off' => esc_html__( 'No', 'wp-event-solution' ),
					'return_value' => 'yes',
					'default' => 'no',
							]
					);

        $this->add_control(
            'show_child_event',
            [
                'label'   => esc_html__( 'Show Recurring Child Event', 'wp-event-solution' ),
                'type'    => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wp-event-solution' ),
					'label_off' => esc_html__( 'No', 'wp-event-solution' ),
					'return_value' => 'yes',
					'default' => 'yes',
							]
					);

        $this->add_control(
            'show_event_location',
            [
                'label'   => esc_html__( 'Show Event Location', 'wp-event-solution' ),
                'type'    => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wp-event-solution' ),
				'label_off' => esc_html__( 'No', 'wp-event-solution' ),
				'return_value' => 'yes',
				'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Start of nav section
        $this->start_controls_section(
            'nav_style',
            [
                'label' => esc_html__( 'Nav style', 'wp-event-solution' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'nav_align',
            [
                'label'     => esc_html__( 'Alignment', 'wp-event-solution' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [

                    'left'    => [
                        'title' => esc_html__( 'Left', 'wp-event-solution' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'  => [
                        'title' => esc_html__( 'Center', 'wp-event-solution' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'   => [
                        'title' => esc_html__( 'Right', 'wp-event-solution' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'wp-event-solution' ),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .event-tab-wrapper ul' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        //Responsive control end

        //control for nav typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_nav_typography',
                'label'    => esc_html__( 'Nav Title Typography', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-nav li a',
            ]
        );

    

        //start of nav color tabs (normal and hover)
        $this->start_controls_tabs(
            'etn_nav_tabs'
        );

        //start of nav normal color tab
        $this->start_controls_tab(
            'etn_nav_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'wp-event-solution' ),
            ]
        );

        $this->add_control(
            'etn_nav_color',
            [
                'label'     => esc_html__( 'Nav Title Color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-nav li a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'nav_background',
                'label'    => esc_html__( 'Background', 'wp-event-solution' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .etn-nav li a',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'nav_border',
                'label'    => esc_html__( 'Border', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-nav li a',
            ]
        );
        $this->add_responsive_control(
            'nav_padding',
            [
                'label'      => esc_html__( 'Padding', 'wp-event-solution' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}}  .etn-nav li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        //end of nav normal color tab

        //start of nav active color tab
        $this->start_controls_tab(
            'etn_nav_active_tab',
            [
                'label' => esc_html__( 'Active', 'wp-event-solution' ),
            ]
        );
        $this->add_control(
            'etn_nav_active_color',
            [
                'label'     => esc_html__( 'Nav active color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-nav li a.etn-active'                  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .schedule-tab-wrapper .etn-nav li a:after' => 'border-color: {{VALUE}} transparent transparent transparent;',
                ],
            ]
        );
    
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'nav_active_background',
                'label'    => esc_html__( 'Active Background', 'wp-event-solution' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .etn-nav li a.etn-active',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'nav_borderactive',
                'label'    => esc_html__( 'Border active', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-nav li a.etn-active',
            ]
        );
        $this->add_responsive_control(
            'nav_active_padding',
            [
                'label'      => esc_html__( 'Padding', 'wp-event-solution' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}}  .etn-nav li a.etn-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        //end of nav hover color tab

        $this->end_controls_tabs();
        //end of nav color tabs (normal and hover)

        $this->add_responsive_control(
            'nav_margin',
            [
                'label'      => esc_html__( 'Margin', 'wp-event-solution' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}}  .etn-nav li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nav_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wp-event-solution' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}}  .etn-nav li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        // End of nav section

        // Title style section
        $this->start_controls_section(
            'title_section',
            [
                'label' => esc_html__( 'Title Style', 'wp-event-solution' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'ent_title_typography',
                'label'    => esc_html__( 'Title Typography', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-content .etn-title',
            ]
        );

        // tab controls start
        $this->start_controls_tabs(
            'etn_title_tabs'
        );

        $this->start_controls_tab(
            'etn_title_normal_tab',
            [
                'label' =>esc_html__( 'Normal', 'wp-event-solution' ),
            ]
        );
        $this->add_control(
            'etn_title_color',
            [
                'label'     => esc_html__( 'Title color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-content .etn-title'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .etn-event-content .etn-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'etn_title_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'wp-event-solution' ),
            ]
        );
        $this->add_control(
            'etn_title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-item:hover .etn-event-content .etn-title:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .etn-event-item:hover .etn-event-content .etn-title a'     => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // tabs control end

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Title margin', 'wp-event-solution' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .etn-event-content .etn-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // designation style section
        $this->start_controls_section(
            'desginnation_section',
            [
                'label' => esc_html__( 'Designation Style', 'wp-event-solution' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_designation_typography',
                'label'    => esc_html__( 'Designation Typography', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-content p',
            ]
        );

        $this->add_control(
            'etn_desc_color',
            [
                'label'     => esc_html__( 'Designation color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'etn_desc_margin',
            [
                'label'      => esc_html__( 'Description margin', 'wp-event-solution' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .etn-event-content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // location style section
        $this->start_controls_section(
            'location_style',
            [
                'label' => esc_html__( 'Location Style', 'wp-event-solution' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_location_typography',
                'label'    => esc_html__( 'Location Typography', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-location',
            ]
        );

        $this->add_control(
            'etn_location_color',
            [
                'label'     => esc_html__( 'Location color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-location' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // location style section
        $this->start_controls_section(
            'category_style',
            [
                'label' => esc_html__( 'Category Style', 'wp-event-solution' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_category_typography',
                'label'    => esc_html__( 'Typography', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-item .etn-event-category span',
            ]
        );

        $this->add_control(
            'etn_cat_color',
            [
                'label'     => esc_html__( 'color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-item .etn-event-category span' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'etn_cat_bg_color',
            [
                'label'     => esc_html__( 'background color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-item .etn-event-category span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // location style section
        $this->start_controls_section(
            'thumb_style',
            [
                'label' => esc_html__( 'Thumb Style', 'wp-event-solution' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'thumb_border_radius',
            [
                'label'      => esc_html__( 'border radius', 'wp-event-solution' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .etn-event-item .etn-event-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

        // Date style section
        $this->start_controls_section(
            'date_style',
            [
                'label' => esc_html__( 'Date Style', 'wp-event-solution' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_date_typography',
                'label'    => esc_html__( 'Date Typography', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-date',
            ]
        );

        $this->add_control(
            'etn_date_color',
            [
                'label'     => esc_html__( 'Date color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button style section
        $this->start_controls_section(
            'etn_btn_style',
            [
                'label' => esc_html__( 'Button Style', 'wp-event-solution' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_btn_typography',
                'label'    => esc_html__( 'Button Typography', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-item .etn-btn',
            ]
        );
        // tab controls start
        $this->start_controls_tabs(
            'etn_btn_tabs'
        );

        $this->start_controls_tab(
            'etn_btn_normal_tab',
            [
                'label' =>esc_html__( 'Normal', 'wp-event-solution' ),
            ]
        );
        $this->add_control(
            'etn_btn_color',
            [
                'label'     => esc_html__( 'Button color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-item .etn-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'btn_background',
                'label'    => esc_html__( 'Background Color', 'wp-event-solution' ),
                'types'    => ['classic','gradient'],
                'selector' => '{{WRAPPER}} .etn-event-item .etn-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'etn_btn_border',
                'label'    => esc_html__( 'Border', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-item .etn-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'etn_btn_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'wp-event-solution' ),
            ]
        );
        $this->add_control(
            'etn_btn_hover_color',
            [
                'label'     => esc_html__( 'Button Hover color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-item .etn-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'btn_background_hover',
                'label'    => esc_html__( 'Background Hover Color', 'wp-event-solution' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .etn-event-item .etn-btn:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'etn_btn_hover_border',
                'label'    => esc_html__( 'Border Hover', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-item .etn-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // tabs control end

        $this->add_responsive_control(
            'etn_btn_padding',
            [
                'label'      => esc_html__( 'Button Padding', 'wp-event-solution' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .etn-event-item .etn-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // advance style section
        $this->start_controls_section(
            'advance_style',
            [
                'label' => esc_html__( 'Advance Style', 'wp-event-solution' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        // tab controls start
        $this->start_controls_tabs(
            'etn_content_box_tabs'
        );

        $this->start_controls_tab(
            'etn_content_box_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'wp-event-solution' ),
            ]
        );
        $this->add_control(
            'etn_content_box_color',
            [
                'label'     => esc_html__( 'Wrapper box BG color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'content_box_border',
                'label'    => esc_html__( 'Wrapper Border', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-item',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'conetnt_box_shadow',
                'label'    => esc_html__( 'Wrapper Box Shadow', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-item',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'etn_content_box_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'wp-event-solution' ),
            ]
        );
        $this->add_control(
            'etn_content_box_hover_color',
            [
                'label'     => esc_html__( 'Wrapper Box Hover BG color', 'wp-event-solution' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'content_box_hover_border',
                'label'    => esc_html__( 'Wrapper Border Hover', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-item:hover',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'conetnt_hover_box_shadow',
                'label'    =>esc_html__( 'Wrapper Box Hover Shadow', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-item:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // tabs control end

        $this->add_responsive_control(
            'etn_content_padding',
            [
                'label'      => esc_html__( 'Content Padding', 'wp-event-solution' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .etn-event-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'content_border',
                'label'    => esc_html__( 'Content Box Border', 'wp-event-solution' ),
                'selector' => '{{WRAPPER}} .etn-event-content',
            ]
        );

        $this->add_responsive_control(
            'etn_content_box_padding',
            [
                'label'      => esc_html__( 'Wrapper Box Padding', 'wp-event-solution' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .etn-event-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
                'etn_wrapper_margin',
                [
                    'label'      => esc_html__( 'Wrapper Box Margin', 'wp-event-solution' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%', 'em'],
                    'selectors'  => [
                        '{{WRAPPER}} .etn-event-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
  

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings();
        $style              = $settings["etn_event_style"];
        $sanitize_filename = sanitize_file_name($style);
        $style             = !empty($sanitize_filename) ? $sanitize_filename : 'event-1';
        $event_cats          = $settings["etn_event_cat"];
        $event_tag          = $settings["etn_event_tag"];
        $event_count        = $settings["etn_event_count"];
        $etn_event_col      = $settings["etn_event_col"];
        $etn_desc_show      = (isset($settings["etn_desc_show"]) ? $settings["etn_desc_show"] : 'yes');
        $etn_desc_limit     = $settings["etn_desc_limit"];
        $order              = (isset($settings["order"]) ? $settings["order"] : 'DESC');
        $orderby            = $settings["orderby"];
        $orderby_meta       = null;
        $filter_with_status       = Helper::resolve_widget_status( $settings['filter_with_status'] );
        $widget_id      = $this->get_id();
        $show_child_event   = $settings["show_child_event"];
        $show_event_location = (isset($settings["show_event_location"]) ? $settings["show_event_location"] : 'yes');
        $show_child_event     = $settings["show_child_event"];
        $show_parent_event    = $settings["show_parent_event"];

        if ( $orderby == "etn_start_date" || $orderby == "etn_end_date" ) {
            $orderby_meta       = "meta_value";
        } else {
            $orderby_meta       = null;
        }

		$post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );

       if ( file_exists( \Wpeventin::plugin_dir() . "widgets/events-tab/style/tab-1.php") ) {
            include \Wpeventin::plugin_dir() . "widgets/events-tab/style/tab-1.php";
       }
    }

    public function get_event_category() {
        return Helper::get_event_category();
    }

    public function get_event_tag() {
        return Helper::get_event_tag();
    }

}
