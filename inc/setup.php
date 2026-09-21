<?php
if (!defined('ABSPATH')) exit;

function havenstone_setup(): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    register_nav_menus([
        'primary' => __('Primary Menu', 'havenstone'),
        'footer' => __('Footer Menu', 'havenstone'),
    ]);
}
add_action('after_setup_theme', 'havenstone_setup');
