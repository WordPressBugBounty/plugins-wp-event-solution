<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Etn\Utils\Helper;

defined( 'ABSPATH' ) || exit;

class Etn_Upcoming_Event_Tab extends Widget_Base {

    /**
     * Retrieve the widget name.
     * @return string Widget name.
     */
    public function get_name() {
        return 'etn-upcoming-expire-tab';
    }

    /**
     * Retrieve the widget title.
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'Upcoming/expire event Tab', 'eventin' );
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
                'label' => esc_html__( 'Upcoming Events Tab', 'eventin' ),
            ]
        );
        $this->add_control(
            'etn_event_style',
            [
                'label'   => esc_html__( 'Event Style', 'eventin' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'event-1',
                'options' => [
                    'event-1' => esc_html__( 'Event 1', 'eventin' ),
                    'event-2' => esc_html__( 'Event 2', 'eventin' )
                ],
            ]
        );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
			'tab_title',
			[
				'label' => esc_html__( 'Tab Title', 'eventin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);
        $repeater->add_control(
            'etn_event_cat',
            [
                'label'    => esc_html__( 'Event Category', 'eventin' ),
                'type'     => Controls_Manager::SELECT2,
                'options'  => $this->get_event_category(),
                'multiple' => true,
            ]
        );
        $repeater->add_control(
            'etn_event_tag',
            [
                'label'    => esc_html__( 'Event Tag', 'eventin' ),
                'type'     => Controls_Manager::SELECT2,
                'options'  => $this->get_event_tag(),
                'multiple' => true,
            ]
        );

	
        $repeater->add_control(
            'orderby',
            [
                'label'     => esc_html__( 'Order Event By', 'eventin' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'post_date',
                'options'   => [
                    'ID'        => esc_html__( 'Id', 'eventin' ),
                    'title'     => esc_html__( 'Title', 'eventin' ),
                    'post_date' => esc_html__( 'Post Date', 'eventin' ),
                    'etn_start_date' => esc_html__( 'Event Start Date', 'eventin' ),
                    'etn_end_date' => esc_html__( 'Event End Date', 'eventin' ),
                ],
            ]
        );
        $repeater->add_control(
            'filter_with_status',
            [
                'label'     => esc_html__( 'Event status filter By', 'eventin' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '',
                'options'   => [
                    ''        => esc_html__( 'All', 'eventin' ),
                    'upcoming' => esc_html__( 'upcoming Event', 'eventin' ),
                    'expire' => esc_html__( 'Expire Event', 'eventin' ),
                ],
            ]
        );

        $repeater->add_control(
            'order',
            [
                'label'   => esc_html__( 'Event Order', 'eventin' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC' => esc_html__( 'Ascending', 'eventin' ),
                    'DESC' => esc_html__( 'Descending', 'eventin' ),
                ],
            ]
        );

		$this->add_control(
			'tab_list',
			[
				'label' => esc_html__( 'Repeater List', 'eventin' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => esc_html__( 'Title #1', 'eventin' ),
					],
					
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);


        $this->add_control(
            'etn_event_count',
            [
                'label'   => esc_html__( 'Event count', 'eventin' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => '6',
            ]
        );

        $this->add_control(
            'etn_desc_show',
            [
                'label'   => esc_html__( 'Show Description', 'eventin' ),
                'type'    => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'eventin' ),
				'label_off' => esc_html__( 'No', 'eventin' ),
				'return_value' => 'yes',
				'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'etn_desc_limit',
            [
                'label'   => esc_html__( 'Description Limit', 'eventin' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 20,
                'condition' => ['etn_desc_show' => 'yes'],
            ]
        );

        $this->add_control(
            'etn_event_col',
            [
                'label'   => esc_html__( 'Event column', 'eventin' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '3' => esc_html__( '4 Column ', 'eventin' ),
                    '4' => esc_html__( '3 Column', 'eventin' ),
                    '6' => esc_html__( '2 Column', 'eventin' ),

                ],
            ]
        );

        $this->add_control(
            'show_parent_event',
            [
                    'label'   => esc_html__( 'Show Recurring Parent Events', 'eventin' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'eventin' ),
                    'label_off' => esc_html__( 'No', 'eventin' ),
                    'return_value' => 'yes',
                    'default' => 'no',
                ]
        );

        $this->add_control(
            'show_child_event',
            [
                'label'   => esc_html__( 'Show Recurring Child Event', 'eventin' ),
                'type'    => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'eventin' ),
				'label_off' => esc_html__( 'No', 'eventin' ),
				'return_value' => 'yes',
				'default' => 'yes',
            ]
        );
        $this->add_control(
            'show_end_date',
            [
                'label'   => esc_html__( 'Show End Date', 'eventin' ),
                'type'    => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'eventin' ),
				'label_off' => esc_html__( 'No', 'eventin' ),
				'return_value' => 'yes',
				'default' => 'no',
            ]
        );

        $this->add_control(
            'show_event_location',
            [
                'label'   => esc_html__( 'Show Event Location', 'eventin' ),
                'type'    => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'eventin' ),
				'label_off' => esc_html__( 'No', 'eventin' ),
				'return_value' => 'yes',
				'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Start of nav section
        $this->start_controls_section(
            'nav_style',
            [
                'label' => esc_html__( 'Nav style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'nav_align',
            [
                'label'     => esc_html__( 'Alignment', 'eventin' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [

                    'left'    => [
                        'title' => esc_html__( 'Left', 'eventin' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'  => [
                        'title' => esc_html__( 'Center', 'eventin' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'   => [
                        'title' => esc_html__( 'Right', 'eventin' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'eventin' ),
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
                'label'    => esc_html__( 'Nav Title Typography', 'eventin' ),
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
                'label' => esc_html__( 'Normal', 'eventin' ),
            ]
        );

        $this->add_control(
            'etn_nav_color',
            [
                'label'     => esc_html__( 'Nav Title Color', 'eventin' ),
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
                'label'    => esc_html__( 'Background', 'eventin' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .etn-nav li a',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'nav_border',
                'label'    => esc_html__( 'Border', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-nav li a',
            ]
        );
        $this->add_responsive_control(
            'nav_padding',
            [
                'label'      => esc_html__( 'Padding', 'eventin' ),
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
                'label' => esc_html__( 'Active', 'eventin' ),
            ]
        );
        $this->add_control(
            'etn_nav_active_color',
            [
                'label'     => esc_html__( 'Nav active color', 'eventin' ),
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
                'label'    => esc_html__( 'Active Background', 'eventin' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .etn-nav li a.etn-active',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'nav_borderactive',
                'label'    => esc_html__( 'Border active', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-nav li a.etn-active',
            ]
        );
        $this->add_responsive_control(
            'nav_active_padding',
            [
                'label'      => esc_html__( 'Padding', 'eventin' ),
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
                'label'      => esc_html__( 'Margin', 'eventin' ),
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
                'label'      => esc_html__( 'Border Radius', 'eventin' ),
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
                'label' => esc_html__( 'Title Style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'ent_title_typography',
                'label'    => esc_html__( 'Title Typography', 'eventin' ),
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
                'label' =>esc_html__( 'Normal', 'eventin' ),
            ]
        );
        $this->add_control(
            'etn_title_color',
            [
                'label'     => esc_html__( 'Title color', 'eventin' ),
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
                'label' => esc_html__( 'Hover', 'eventin' ),
            ]
        );
        $this->add_control(
            'etn_title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover color', 'eventin' ),
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
                'label'      => esc_html__( 'Title margin', 'eventin' ),
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
                'label' => esc_html__( 'Designation Style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_designation_typography',
                'label'    => esc_html__( 'Designation Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-content p',
            ]
        );

        $this->add_control(
            'etn_desc_color',
            [
                'label'     => esc_html__( 'Designation color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'etn_desc_margin',
            [
                'label'      => esc_html__( 'Description margin', 'eventin' ),
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
                'label' => esc_html__( 'Location Style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_location_typography',
                'label'    => esc_html__( 'Location Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-location',
            ]
        );

        $this->add_control(
            'etn_location_color',
            [
                'label'     => esc_html__( 'Location color', 'eventin' ),
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
                'label' => esc_html__( 'Category Style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_category_typography',
                'label'    => esc_html__( 'Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-item .etn-event-category span',
            ]
        );

        $this->add_control(
            'etn_cat_color',
            [
                'label'     => esc_html__( 'color', 'eventin' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .etn-event-item .etn-event-category span' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'etn_cat_bg_color',
            [
                'label'     => esc_html__( 'background color', 'eventin' ),
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
                'label' => esc_html__( 'Thumb Style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'thumb_border_radius',
            [
                'label'      => esc_html__( 'border radius', 'eventin' ),
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
                'label' => esc_html__( 'Date Style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_date_typography',
                'label'    => esc_html__( 'Date Typography', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-date',
            ]
        );

        $this->add_control(
            'etn_date_color',
            [
                'label'     => esc_html__( 'Date color', 'eventin' ),
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
                'label' => esc_html__( 'Button Style', 'eventin' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'etn_btn_typography',
                'label'    => esc_html__( 'Button Typography', 'eventin' ),
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
                'label' =>esc_html__( 'Normal', 'eventin' ),
            ]
        );
        $this->add_control(
            'etn_btn_color',
            [
                'label'     => esc_html__( 'Button color', 'eventin' ),
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
                'label'    => esc_html__( 'Background Color', 'eventin' ),
                'types'    => ['classic','gradient'],
                'selector' => '{{WRAPPER}} .etn-event-item .etn-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'etn_btn_border',
                'label'    => esc_html__( 'Border', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-item .etn-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'etn_btn_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'eventin' ),
            ]
        );
        $this->add_control(
            'etn_btn_hover_color',
            [
                'label'     => esc_html__( 'Button Hover color', 'eventin' ),
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
                'label'    => esc_html__( 'Background Hover Color', 'eventin' ),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .etn-event-item .etn-btn:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'etn_btn_hover_border',
                'label'    => esc_html__( 'Border Hover', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-item .etn-btn:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // tabs control end

        $this->add_responsive_control(
            'etn_btn_padding',
            [
                'label'      => esc_html__( 'Button Padding', 'eventin' ),
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
                'label' => esc_html__( 'Advance Style', 'eventin' ),
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
                'label' => esc_html__( 'Normal', 'eventin' ),
            ]
        );
        $this->add_control(
            'etn_content_box_color',
            [
                'label'     => esc_html__( 'Wrapper box BG color', 'eventin' ),
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
                'label'    => esc_html__( 'Wrapper Border', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-item',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'conetnt_box_shadow',
                'label'    => esc_html__( 'Wrapper Box Shadow', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-item',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'etn_content_box_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'eventin' ),
            ]
        );
        $this->add_control(
            'etn_content_box_hover_color',
            [
                'label'     => esc_html__( 'Wrapper Box Hover BG color', 'eventin' ),
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
                'label'    => esc_html__( 'Wrapper Border Hover', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-item:hover',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'conetnt_hover_box_shadow',
                'label'    =>esc_html__( 'Wrapper Box Hover Shadow', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-item:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        // tabs control end

        $this->add_responsive_control(
            'etn_content_padding',
            [
                'label'      => esc_html__( 'Content Padding', 'eventin' ),
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
                'label'    => esc_html__( 'Content Box Border', 'eventin' ),
                'selector' => '{{WRAPPER}} .etn-event-content',
            ]
        );

        $this->add_responsive_control(
            'etn_content_box_padding',
            [
                'label'      => esc_html__( 'Wrapper Box Padding', 'eventin' ),
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
                    'label'      => esc_html__( 'Wrapper Box Margin', 'eventin' ),
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
   
        $event_count        = $settings["etn_event_count"];
        $etn_event_col      = $settings["etn_event_col"];
        $etn_desc_limit     = $settings["etn_desc_limit"];
        $show_child_event   = $settings["show_child_event"];
        $show_parent_event  = $settings["show_parent_event"];
        $orderby_meta       = null;
        $widget_id      = $this->get_id();
        $tab_list      = $settings['tab_list'];
        $show_end_date = !empty($settings['show_end_date']) ? $settings['show_end_date'] : 'no';
        $show_event_location = (isset($settings["show_event_location"]) ? $settings["show_event_location"] : 'yes');
        $etn_desc_show      = (isset($settings["etn_desc_show"]) ? $settings["etn_desc_show"] : 'yes');     

		$post_parent = Helper::show_parent_child( $show_parent_event , $show_child_event  );

        include \Wpeventin::plugin_dir() . "widgets/upcoming-event-tab/style/tab-1.php";
    }

    public function get_event_category() {
        return Helper::get_event_category();
    }

    public function get_event_tag() {
        return Helper::get_event_tag();
    }

}
