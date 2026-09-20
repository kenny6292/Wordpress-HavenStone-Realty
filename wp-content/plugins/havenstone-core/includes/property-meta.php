<?php
if (!defined('ABSPATH')) exit;

function havenstone_property_meta_boxes(): void {
    add_meta_box('havenstone_property_details', __('Property Details', 'havenstone'), 'havenstone_render_property_details', 'property', 'normal', 'high');
}
add_action('add_meta_boxes', 'havenstone_property_meta_boxes');

function havenstone_render_property_details(WP_Post $post): void {
    wp_nonce_field('havenstone_property_details', 'havenstone_property_details_nonce');
    $fields = [
        'price' => __('Price (numeric)', 'havenstone'),
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

    $gallery = get_post_meta($post->ID, '_havenstone_gallery', true);
    $amenities = get_post_meta($post->ID, '_havenstone_amenities', true);
    echo '<p><label for="havenstone_gallery"><strong>' . esc_html__('Gallery Image IDs', 'havenstone') . '</strong></label>';
    echo '<input class="widefat" id="havenstone_gallery" name="havenstone_gallery" value="' . esc_attr(is_array($gallery) ? implode(',', $gallery) : $gallery) . '">';
    echo '<small>' . esc_html__('Comma-separated WordPress Media Library image IDs. The first image can remain the featured image.', 'havenstone') . '</small></p>';

    echo '<p><label for="havenstone_amenities"><strong>' . esc_html__('Amenities', 'havenstone') . '</strong></label>';
    echo '<textarea class="widefat" rows="6" id="havenstone_amenities" name="havenstone_amenities">' . esc_textarea($amenities) . '</textarea>';
    echo '<small>' . esc_html__('Enter one amenity per line, for example: Swimming pool, BQ, Fitted kitchen.', 'havenstone') . '</small></p>';

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

    $gallery = isset($_POST['havenstone_gallery']) ? sanitize_text_field(wp_unslash($_POST['havenstone_gallery'])) : '';
    $ids = array_values(array_filter(array_map('absint', preg_split('/[,\s]+/', $gallery))));
    update_post_meta($post_id, '_havenstone_gallery', implode(',', array_unique($ids)));

    $amenities = isset($_POST['havenstone_amenities']) ? sanitize_textarea_field(wp_unslash($_POST['havenstone_amenities'])) : '';
    update_post_meta($post_id, '_havenstone_amenities', $amenities);

    update_post_meta($post_id, '_havenstone_featured', isset($_POST['havenstone_featured']) ? '1' : '0');
}
add_action('save_post_property', 'havenstone_save_property_meta');
