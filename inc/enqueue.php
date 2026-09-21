<?php
if (!defined('ABSPATH')) exit;

function havenstone_enqueue_assets(): void {
    $version = wp_get_theme()->get('Version');
    wp_enqueue_style('havenstone-style', get_stylesheet_uri(), [], $version);
    wp_enqueue_style('havenstone-main', get_template_directory_uri() . '/assets/css/main.css', [], $version);
    wp_enqueue_script('havenstone-main', get_template_directory_uri() . '/assets/js/main.js', [], $version, true);
}
add_action('wp_enqueue_scripts', 'havenstone_enqueue_assets');
