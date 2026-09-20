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

/**
 * Lightweight SEO/social metadata foundation without requiring a third-party SEO plugin.
 * A dedicated SEO plugin can take over these tags if one is installed later.
 */
function havenstone_seo_description(): string {
    if (is_singular()) {
        $description = get_the_excerpt();
        if (!$description) $description = wp_trim_words(wp_strip_all_tags(get_the_content()), 28);
        if ($description) return wp_strip_all_tags($description);
    }
    if (is_post_type_archive('property')) return 'Browse homes, land and investment properties available through HavenStone Realty.';
    if (is_post_type_archive('agent')) return 'Meet the HavenStone Realty team and connect with an agent for property guidance.';
    return 'HavenStone Realty helps buyers, renters, sellers and investors discover property opportunities.';
}

function havenstone_output_seo_meta(): void {
    if (is_admin()) return;

    $description = havenstone_seo_description();
    $url = is_singular() || is_post_type_archive() ? get_permalink() : home_url('/');
    if (!$url) $url = home_url('/');
    $title = wp_get_document_title();
    $image = is_singular() && has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : '';

    echo '<meta name="description" content="' . esc_attr($description) . '">' . "
";
    echo '<link rel="canonical" href="' . esc_url(havenstone_canonical_url()) . '">' . "
";
    echo '<meta property="og:type" content="' . esc_attr(is_singular() ? 'article' : 'website') . '">' . "
";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "
";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "
";
    echo '<meta property="og:url" content="' . esc_url($url) . '">' . "
";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "
";
    if ($image) echo '<meta property="og:image" content="' . esc_url($image) . '">' . "
";
    echo '<meta name="twitter:card" content="' . esc_attr($image ? 'summary_large_image' : 'summary') . '">' . "
";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "
";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "
";
}
function havenstone_canonical_url(): string {
    if (is_front_page()) return home_url('/');
    if (is_singular()) return get_permalink();
    if (is_post_type_archive()) return get_post_type_archive_link(get_query_var('post_type'));
    return home_url(add_query_arg([], $GLOBALS['wp']->request ?? ''));
}
add_action('wp_head', 'havenstone_output_seo_meta', 2);

function havenstone_output_organization_schema(): void {
    if (!is_front_page()) return;
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'RealEstateAgent',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
    ];
    if (has_custom_logo()) {
        $logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_url($logo_id, 'full');
        if ($logo) $schema['logo'] = $logo;
    }
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "
";
}
add_action('wp_head', 'havenstone_output_organization_schema', 3);
