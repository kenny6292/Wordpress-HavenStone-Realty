<?php
if (!defined('ABSPATH')) exit;

function havenstone_register_post_types(): void {
    register_post_type('property', [
        'labels' => [
            'name' => __('Properties', 'havenstone'),
            'singular_name' => __('Property', 'havenstone'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-building',
        'supports' => ['title','editor','thumbnail','excerpt','author'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'properties'],
    ]);

    register_post_type('agent', [
        'labels' => ['name'=>__('Agents','havenstone'),'singular_name'=>__('Agent','havenstone')],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-businessperson',
        'supports' => ['title','editor','thumbnail'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'agents'],
    ]);

    register_post_type('enquiry', [
        'labels' => ['name'=>__('Enquiries','havenstone'),'singular_name'=>__('Enquiry','havenstone')],
        'public' => false,
        'show_ui' => true,
        'show_in_rest' => false,
        'menu_icon' => 'dashicons-email-alt',
        'supports' => ['title','editor'],
    ]);

    register_post_type('viewing_request', [
        'labels' => ['name'=>__('Viewing Requests','havenstone'),'singular_name'=>__('Viewing Request','havenstone')],
        'public' => false,
        'show_ui' => true,
        'show_in_rest' => false,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title','editor'],
    ]);
}
add_action('init', 'havenstone_register_post_types');
