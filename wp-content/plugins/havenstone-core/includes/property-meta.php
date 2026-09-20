<?php
if (!defined('ABSPATH')) exit;

function havenstone_property_meta_boxes(): void {
    add_meta_box('havenstone_property_details', __('Property Details', 'havenstone'), 'havenstone_render_property_details', 'property', 'normal', 'high');
}
add_action('add_meta_boxes', 'havenstone_property_meta_boxes');

function havenstone_render_property_details(WP_Post $post): void {
    wp_nonce_field('havenstone_property_details', 'havenstone_property_details_nonce');
    $fields = [
        'price' => __('Price', 'havenstone'),
        'price_label' => __('Price Label', 'havenstone'),
        'bedrooms' => __('Bedrooms', 'havenstone'),
        'bathrooms' => __('Bathrooms', 'havenstone'),
        'parking' => __('Parking Spaces', 'havenstone'),
        'area' => __('Area / Size', 'havenstone'),
        'address' => __('Address', 'havenstone'),
        'map_url' => __('Google Maps URL', 'havenstone'),
        'agent_id' => __('Agent Post ID', 'havenstone'),
    ];
    echo '<div class="havenstone-meta-grid">';
    foreach ($fields as $key => $label) {
        $value = get_post_meta($post->ID, '_havenstone_' . $key, true);
        echo '<p><label for="havenstone_' . esc_attr($key) . '"><strong>' . esc_html($label) . '</strong></label>';
        echo '<input class="widefat" id="havenstone_' . esc_attr($key) . '" name="havenstone_' . esc_attr($key) . '" value="' . esc_attr($value) . '"></p>';
    }
    echo '<p><label><strong>' . esc_html__('Featured Property', 'havenstone') . '</strong></label><br>';
    echo '<input type="checkbox" name="havenstone_featured" value="1" ' . checked(get_post_meta($post->ID, '_havenstone_featured', true), '1', false) . '> ' . esc_html__('Show as featured', 'havenstone') . '</p>';
    echo '</div>';
}

function havenstone_save_property_meta(int $post_id): void {
    if (!isset($_POST['havenstone_property_details_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['havenstone_property_details_nonce'])), 'havenstone_property_details')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $keys = ['price','price_label','bedrooms','bathrooms','parking','area','address','map_url','agent_id'];
    foreach ($keys as $key) {
        if (isset($_POST['havenstone_' . $key])) {
            update_post_meta($post_id, '_havenstone_' . $key, sanitize_text_field(wp_unslash($_POST['havenstone_' . $key])));
        }
    }
    update_post_meta($post_id, '_havenstone_featured', isset($_POST['havenstone_featured']) ? '1' : '0');
}
add_action('save_post_property', 'havenstone_save_property_meta');
