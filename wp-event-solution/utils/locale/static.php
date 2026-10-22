<?php

defined( 'ABSPATH' ) || exit;
$data = [
    'empty'              => esc_html__( 'Please fill the field', 'wp-event-solution' ),
    'invalid'            => esc_html__( 'Invalid input', 'wp-event-solution' ),
    'only_number'        => esc_html__( 'Only number allowed', 'wp-event-solution' ),
    'text'               => esc_html__( 'Please fill the field', 'wp-event-solution' ),
    'number'             => esc_html__( 'Please input a number', 'wp-event-solution' ),
    'date'               => esc_html__( 'Please fill the field', 'wp-event-solution' ),
    'radio'              => esc_html__( 'Please check the field', 'wp-event-solution' ),
    'expired'            => esc_html__( 'Expired', 'wp-event-solution' ),
    'scanner_common_msg' => esc_html__( 'Something went wrong! Please try again.', 'wp-event-solution' ),
    'scanner_common_msg' => esc_html__( 'Something went wrong! Please try again.', 'wp-event-solution' ),
];

return apply_filters( 'etn_locale_static',  $data );
