<?php
if (!defined('ABSPATH')) exit;

/**
 * Production security and privacy hardening for public HavenStone forms.
 */
function havenstone_security_headers(): void {
    if (is_admin() || headers_sent()) return;
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}
add_action('send_headers', 'havenstone_security_headers');

function havenstone_validate_public_request(): bool {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') return false;
    $origin = isset($_SERVER['HTTP_ORIGIN']) ? esc_url_raw(wp_unslash($_SERVER['HTTP_ORIGIN'])) : '';
    if ($origin) {
        $site = wp_parse_url(home_url('/'));
        $request = wp_parse_url($origin);
        if (!$site || !$request || empty($site['host']) || empty($request['host']) || strtolower($site['host']) !== strtolower($request['host'])) return false;
    }
    return true;
}

/**
 * Prevent private lead records from being exposed through front-end queries.
 */
function havenstone_exclude_private_leads_from_search(WP_Query $query): void {
    if (is_admin() || !$query->is_main_query()) return;
    if ($query->is_search()) {
        $post_types = $query->get('post_type');
        if (!$post_types) {
            $query->set('post_type', ['post', 'page', 'property', 'agent']);
        }
    }
}
add_action('pre_get_posts', 'havenstone_exclude_private_leads_from_search');

/**
 * Remove WordPress version disclosure from generated HTML.
 */
remove_action('wp_head', 'wp_generator');

/**
 * Disable XML-RPC pingback abuse while retaining normal WordPress functionality.
 */
add_filter('xmlrpc_enabled', '__return_false');
add_filter('wp_headers', function(array $headers): array {
    unset($headers['X-Pingback']);
    return $headers;
});

/**
 * Avoid indexing internal/private lead post types if a crawler requests them directly.
 */
function havenstone_private_post_robots(): void {
    if (is_singular(['enquiry', 'viewing_request'])) {
        echo '<meta name="robots" content="noindex,nofollow">' . "
";
    }
}
add_action('wp_head', 'havenstone_private_post_robots', 1);
