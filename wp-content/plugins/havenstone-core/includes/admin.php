<?php
if (!defined('ABSPATH')) exit;

/**
 * Admin workflow helpers: lead/viewing status, property columns and enquiry detail panels.
 */
function havenstone_register_admin_meta_boxes(): void {
    foreach (['enquiry' => 'Enquiry Details', 'viewing_request' => 'Viewing Request Details'] as $post_type => $title) {
        add_meta_box('havenstone_'.$post_type.'_details', __($title, 'havenstone'), 'havenstone_render_lead_details', $post_type, 'normal', 'high');
    }
}
add_action('add_meta_boxes', 'havenstone_register_admin_meta_boxes');

function havenstone_render_lead_details(WP_Post $post): void {
    wp_nonce_field('havenstone_lead_details', 'havenstone_lead_details_nonce');
    $fields = ['email'=>'Email','phone'=>'Phone','date'=>'Preferred Date','time'=>'Preferred Time','property_id'=>'Property ID'];
    echo '<div class="havenstone-admin-details">';
    foreach ($fields as $key=>$label) {
        $value=get_post_meta($post->ID,'_havenstone_'.$key,true);
        if ($value==='') continue;
        echo '<p><strong>'.esc_html($label).':</strong> '.esc_html($value).'</p>';
    }
    $status=get_post_meta($post->ID,'_havenstone_lead_status',true) ?: 'new';
    echo '<p><label><strong>'.esc_html__('Lead status','havenstone').'</strong><select name="havenstone_lead_status"><option value="new" '.selected($status,'new',false).'>New</option><option value="contacted" '.selected($status,'contacted',false).'>Contacted</option><option value="scheduled" '.selected($status,'scheduled',false).'>Scheduled</option><option value="closed" '.selected($status,'closed',false).'>Closed</option></select></label></p></div>';
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
