<?php
if (!defined('ABSPATH')) exit;

function havenstone_property_query(array $args = []): WP_Query {
    $defaults = [
        'post_type' => 'property',
        'post_status' => 'publish',
        'posts_per_page' => 12,
        'paged' => max(1, (int) get_query_var('paged')),
    ];
    $args = wp_parse_args($args, $defaults);

    $tax_query = [];
    foreach (['property_type','property_location','property_status'] as $taxonomy) {
        if (!empty($_GET[$taxonomy])) {
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => sanitize_title(wp_unslash($_GET[$taxonomy])),
            ];
        }
    }
    if ($tax_query) {
        $args['tax_query'] = array_merge(['relation' => 'AND'], $tax_query);
    }

    if (!empty($_GET['min_price']) || !empty($_GET['max_price'])) {
        $args['meta_query'] = ['relation' => 'AND'];
        if (!empty($_GET['min_price'])) {
            $args['meta_query'][] = [
                'key' => '_havenstone_price',
                'value' => (float) sanitize_text_field(wp_unslash($_GET['min_price'])),
                'type' => 'NUMERIC',
                'compare' => '>=',
            ];
        }
        if (!empty($_GET['max_price'])) {
            $args['meta_query'][] = [
                'key' => '_havenstone_price',
                'value' => (float) sanitize_text_field(wp_unslash($_GET['max_price'])),
                'type' => 'NUMERIC',
                'compare' => '<=',
            ];
        }
    }

    return new WP_Query($args);
}
