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


/**
 * Create the standard HavenStone pages on a fresh WordPress installation.
 * Existing pages are never overwritten.
 */
function havenstone_create_required_pages(): void {
    if (get_option('havenstone_required_pages_v2')) return;

    $pages = [
        ['about-us', 'About Us', 'page-about-us.php'],
        ['services', 'Services', 'page-services.php'],
        ['locations', 'Locations', 'page-locations.php'],
        ['blog', 'Blog', 'page-blog.php'],
        ['contact', 'Contact', 'page-contact.php'],
        ['request-a-property', 'Request a Property', 'page-request-a-property.php'],
    ];

    foreach ($pages as [$slug, $title, $template]) {
        $existing = get_page_by_path($slug);
        if ($existing) continue;

        $page_id = wp_insert_post([
            'post_title' => $title,
            'post_name' => $slug,
            'post_status' => 'publish',
            'post_type' => 'page',
        ], true);

        if (!is_wp_error($page_id) && $page_id) {
            update_post_meta($page_id, '_wp_page_template', $template);
        }
    }

    flush_rewrite_rules(false);
    update_option('havenstone_required_pages_v2', 1, false);
}
add_action('admin_init', 'havenstone_create_required_pages');
