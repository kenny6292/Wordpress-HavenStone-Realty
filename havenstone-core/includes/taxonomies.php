<?php
if (!defined('ABSPATH')) exit;

function havenstone_register_taxonomies(): void {
    register_taxonomy('property_type', ['property'], [
        'label' => __('Property Types', 'havenstone'),
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'property-type'],
    ]);

    register_taxonomy('property_location', ['property'], [
        'label' => __('Locations', 'havenstone'),
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'location'],
    ]);

    register_taxonomy('property_status', ['property'], [
        'label' => __('Property Status', 'havenstone'),
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => false,
        'rewrite' => ['slug' => 'status'],
    ]);
}
add_action('init', 'havenstone_register_taxonomies');
