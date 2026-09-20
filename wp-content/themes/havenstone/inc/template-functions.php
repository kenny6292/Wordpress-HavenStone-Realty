<?php
if (!defined('ABSPATH')) exit;

/**
 * Safe fallback navigation for a fresh WordPress installation.
 * Once a Primary menu is assigned in Appearance > Menus, WordPress uses that menu instead.
 */
function havenstone_fallback_menu(): void {
    $items = [
        ['Home', home_url('/')],
        ['Properties', get_post_type_archive_link('property')],
        ['About Us', home_url('/about-us/')],
        ['Services', home_url('/services/')],
        ['Locations', home_url('/locations/')],
        ['Agents', get_post_type_archive_link('agent')],
        ['Blog', home_url('/blog/')],
        ['Contact', home_url('/contact/')],
    ];

    echo '<ul class="menu">';
    foreach ($items as [$label, $url]) {
        if (!$url) continue;
        echo '<li class="menu-item"><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}
