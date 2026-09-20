<?php
if (!defined('ABSPATH')) exit;

/**
 * Admin workflow helpers: lead/viewing status, property columns and enquiry detail panels.
 */
function havenstone_register_admin_meta_boxes(): void {
    foreach (['enquiry' => 'Enquiry Details', 'viewing_request' => 'Viewing Request Details'] as $post_type => $title) {
        add_meta_box('havenstone_'.$post_type.'_details', __($title, 'havenstone'), 'havenstone_render_lead_details', $post_type, 'normal', 'high');
    }
    foreach (['enquiry','viewing_request'] as $post_type) {
        add_meta_box('havenstone_'.$post_type.'_actions', __('Lead Actions', 'havenstone'), 'havenstone_render_lead_actions', $post_type, 'side', 'high');
    }
}\nadd_action('add_meta_boxes', 'havenstone_register_admin_meta_boxes');

function havenstone_render_lead_details(WP_Post $post): void {
    wp_nonce_field('havenstone_lead_details', 'havenstone_lead_details_nonce');
    $email=get_post_meta($post->ID,'_havenstone_email',true);
    $phone=get_post_meta($post->ID,'_havenstone_phone',true);
    $date=get_post_meta($post->ID,'_havenstone_date',true);
    $time=get_post_meta($post->ID,'_havenstone_time',true);
    $property_id=absint(get_post_meta($post->ID,'_havenstone_property_id',true));
    $status=get_post_meta($post->ID,'_havenstone_lead_status',true) ?: 'new';
    echo '<div class="havenstone-admin-details">';
    if($email) echo '<p><strong>Email:</strong> <a href="mailto:'.esc_attr($email).'">'.esc_html($email).'</a></p>';
    if($phone) echo '<p><strong>Phone:</strong> <a href="tel:'.esc_attr($phone).'">'.esc_html($phone).'</a></p>';
    if($date) echo '<p><strong>Preferred Date:</strong> '.esc_html($date).'</p>';
    if($time) echo '<p><strong>Preferred Time:</strong> '.esc_html($time).'</p>';
    if($property_id && get_post_type($property_id)==='property') echo '<p><strong>Property:</strong> <a href="'.esc_url(get_edit_post_link($property_id)).'">'.esc_html(get_the_title($property_id)).'</a> <a href="'.esc_url(get_permalink($property_id)).'" target="_blank" rel="noopener">View</a></p>';
    elseif($property_id) echo '<p><strong>Property ID:</strong> '.esc_html($property_id).'</p>';
    echo '<p><strong>Submitted:</strong> '.esc_html(get_the_date(get_option('date_format').' '.get_option('time_format'),$post)).'</p>';
    echo '<p><strong>Status:</strong> '.esc_html(ucfirst($status)).'</p></div>';
}
function havenstone_render_lead_actions(WP_Post $post): void {
    $email=get_post_meta($post->ID,'_havenstone_email',true);
    $phone=get_post_meta($post->ID,'_havenstone_phone',true);
    $property_id=absint(get_post_meta($post->ID,'_havenstone_property_id',true));
    echo '<div class="havenstone-lead-actions">';
    if($email) echo '<p><a class="button button-primary" href="mailto:'.esc_attr($email).'">Email lead</a></p>';
    if($phone) echo '<p><a class="button" href="tel:'.esc_attr($phone).'">Call lead</a></p>';
    if($property_id && get_post_type($property_id)==='property') echo '<p><a class="button" href="'.esc_url(get_permalink($property_id)).'" target="_blank" rel="noopener">Open property</a></p>';
    echo '</div>';
}
function havenstone_save_lead_details(int $post_id): void {
    if (!isset($_POST['havenstone_lead_details_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['havenstone_lead_details_nonce'])),'havenstone_lead_details')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post',$post_id)) return;
    $status=sanitize_key(wp_unslash($_POST['havenstone_lead_status']??'new'));
    if (!in_array($status,['new','contacted','scheduled','closed'],true)) $status='new';
    update_post_meta($post_id,'_havenstone_lead_status',$status);
}
add_action('save_post_enquiry','havenstone_save_lead_details');
add_action('save_post_viewing_request','havenstone_save_lead_details');

function havenstone_lead_columns(array $columns): array {
    $columns['havenstone_status']=__('Status','havenstone');
    $columns['havenstone_email']=__('Email','havenstone');
    $columns['havenstone_phone']=__('Phone','havenstone');
    $columns['havenstone_property']=__('Property','havenstone');
    return $columns;
}
foreach (['enquiry','viewing_request'] as $type) add_filter("manage_{$type}_posts_columns",'havenstone_lead_columns');

function havenstone_lead_column_content(string $column,int $post_id): void {
    if ($column==='havenstone_status') {
        $status=get_post_meta($post_id,'_havenstone_lead_status',true) ?: 'new';
        echo esc_html(ucfirst($status));
    } elseif ($column==='havenstone_email') {
        echo esc_html(get_post_meta($post_id,'_havenstone_email',true));
    } elseif ($column==='havenstone_phone') {
        echo esc_html(get_post_meta($post_id,'_havenstone_phone',true));
    } elseif ($column==='havenstone_property') {
        $property_id=absint(get_post_meta($post_id,'_havenstone_property_id',true));
        if($property_id && get_post_type($property_id)==='property') echo '<a href="'.esc_url(get_edit_post_link($property_id)).'">'.esc_html(get_the_title($property_id)).'</a>';
        else echo '—';
    }
}
foreach (['enquiry','viewing_request'] as $type) add_action("manage_{$type}_posts_custom_column",'havenstone_lead_column_content',10,2);

function havenstone_property_admin_columns(array $columns): array {
    $columns['havenstone_price']=__('Price','havenstone');
    $columns['havenstone_status']=__('Status','havenstone');
    $columns['havenstone_location']=__('Location','havenstone');
    $columns['havenstone_featured']=__('Featured','havenstone');
    return $columns;
}
add_filter('manage_property_posts_columns','havenstone_property_admin_columns');
function havenstone_property_admin_column_content(string $column,int $post_id): void {
    if ($column==='havenstone_price') echo esc_html(get_post_meta($post_id,'_havenstone_price_label',true));
    if ($column==='havenstone_status') {
        $terms=get_the_terms($post_id,'property_status');
        if (!is_wp_error($terms) && $terms) echo esc_html(implode(', ',wp_list_pluck($terms,'name')));
    }
    if ($column==='havenstone_location') {
        $terms=get_the_terms($post_id,'property_location');
        if (!is_wp_error($terms) && $terms) echo esc_html(implode(', ',wp_list_pluck($terms,'name')));
    }
    if ($column==='havenstone_featured') echo get_post_meta($post_id,'_havenstone_featured',true)==='1' ? 'Yes' : '—';
}
add_action('manage_property_posts_custom_column','havenstone_property_admin_column_content',10,2);

function havenstone_admin_property_assets(string $hook): void {
    if (!in_array($hook, ['post.php','post-new.php'], true)) return;
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'property') return;
    wp_enqueue_media();
    wp_enqueue_script('havenstone-property-admin', plugins_url('../assets/js/admin-property.js', __FILE__), ['jquery'], HAVENSTONE_CORE_VERSION, true);
}
add_action('admin_enqueue_scripts', 'havenstone_admin_property_assets');
