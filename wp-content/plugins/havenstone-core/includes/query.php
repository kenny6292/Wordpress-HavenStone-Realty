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

    // Optional keyword search across property title/content.
    if (!empty($_GET['property_search'])) {
        $args['s'] = sanitize_text_field(wp_unslash($_GET['property_search']));
    }
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

    $meta_query = [];
    if (!empty($_GET['min_bedrooms'])) {
        $meta_query[] = ['key'=>'_havenstone_bedrooms','value'=>absint($_GET['min_bedrooms']),'type'=>'NUMERIC','compare'=>'>='];
    }
    if (!empty($_GET['min_bathrooms'])) {
        $meta_query[] = ['key'=>'_havenstone_bathrooms','value'=>absint($_GET['min_bathrooms']),'type'=>'NUMERIC','compare'=>'>='];
    }
    if (!empty($_GET['min_price']) || !empty($_GET['max_price'])) {
        $meta_query[] = ['relation' => 'AND'];
        if (!empty($_GET['min_price'])) {
            $meta_query[] = [
                'key' => '_havenstone_price',
                'value' => (float) preg_replace('/[^0-9.]/', '', wp_unslash($_GET['min_price'])),
                'type' => 'NUMERIC',
                'compare' => '>=',
            ];
        }
        if (!empty($_GET['max_price'])) {
            $meta_query[] = [
                'key' => '_havenstone_price',
                'value' => (float) preg_replace('/[^0-9.]/', '', wp_unslash($_GET['max_price'])),
                'type' => 'NUMERIC',
                'compare' => '<=',
            ];
        }
    }

    if ($meta_query) {
        $args['meta_query'] = ['relation'=>'AND'];
        foreach ($meta_query as $clause) {
            if (isset($clause['relation'])) continue;
            $args['meta_query'][] = $clause;
        }
    }

    $sort = isset($_GET['sort']) ? sanitize_key(wp_unslash($_GET['sort'])) : 'date';
    if ($sort === 'price_low') { $args['meta_key']='_havenstone_price'; $args['orderby']='meta_value_num'; $args['order']='ASC'; }
    elseif ($sort === 'price_high') { $args['meta_key']='_havenstone_price'; $args['orderby']='meta_value_num'; $args['order']='DESC'; }
    elseif ($sort === 'oldest') { $args['orderby']='date'; $args['order']='ASC'; }
    else { $args['orderby']='date'; $args['order']='DESC'; }

    return new WP_Query($args);
}
