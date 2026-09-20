<?php
if (!defined('ABSPATH')) exit;

function havenstone_property_card(WP_Post $property): string {
    $price = get_post_meta($property->ID, '_havenstone_price_label', true);
    $beds = get_post_meta($property->ID, '_havenstone_bedrooms', true);
    $baths = get_post_meta($property->ID, '_havenstone_bathrooms', true);
    $area = get_post_meta($property->ID, '_havenstone_area', true);
    ob_start(); ?>
    <article class="property-card">
        <a href="<?php echo esc_url(get_permalink($property)); ?>">
            <?php if (has_post_thumbnail($property)) echo get_the_post_thumbnail($property, 'large', ['loading'=>'lazy']); ?>
            <div class="property-card__body">
                <p class="property-card__price"><?php echo esc_html($price); ?></p>
                <h3><?php echo esc_html(get_the_title($property)); ?></h3>
                <p><?php echo esc_html(implode(' • ', array_filter([$beds ? $beds . ' beds' : '', $baths ? $baths . ' baths' : '', $area]))); ?></p>
            </div>
        </a>
    </article>
    <?php return (string) ob_get_clean();
}

function havenstone_properties_shortcode(array $atts = []): string {
    $query = havenstone_property_query(wp_parse_args($atts, ['posts_per_page'=>12]));
    ob_start();
    echo '<div class="property-grid">';
    while ($query->have_posts()) { $query->the_post(); echo havenstone_property_card(get_post()); }
    echo '</div>';
    wp_reset_postdata();
    return (string) ob_get_clean();
}
add_shortcode('havenstone_properties', 'havenstone_properties_shortcode');

function havenstone_enquiry_form_shortcode(): string {
    $notice = isset($_GET['havenstone_status']) && $_GET['havenstone_status'] === 'enquiry_sent' ? '<p class="havenstone-form-success" role="status">Thank you. Your enquiry has been received and the HavenStone team will follow up.</p>' : '';
    ob_start(); echo $notice; ?>
    <form class="havenstone-enquiry-form" method="post">
        <?php wp_nonce_field('havenstone_enquiry','havenstone_enquiry_nonce'); ?>
        <input type="hidden" name="havenstone_enquiry_action" value="1">
        <input type="hidden" name="property_id" value="<?php echo esc_attr(get_the_ID()); ?>">
        <p class="havenstone-hp" aria-hidden="true"><label>Website<input tabindex="-1" autocomplete="off" name="website"></label></p>
        <p><label>Name<input required name="name"></label></p>
        <p><label>Email<input required type="email" name="email"></label></p>
        <p><label>Phone<input name="phone"></label></p>
        <p><label>Message<textarea required name="message"></textarea></label></p>
        <button type="submit">Send enquiry</button>
    </form>
    <?php return (string)ob_get_clean();
}
add_shortcode('havenstone_enquiry_form','havenstone_enquiry_form_shortcode');

function havenstone_send_notification(string $subject, string $html, string $reply_to=''): bool {
    $to = get_option('admin_email');
    $from = apply_filters('havenstone_notification_from', get_option('admin_email'));
    if (!$to || !$from) return false;
    $headers = ['Content-Type: text/html; charset=UTF-8', 'From: '.$from];
    if ($reply_to && is_email($reply_to)) $headers[] = 'Reply-To: '.$reply_to;
    return (bool) wp_mail($to, $subject, $html, $headers);
}

function havenstone_handle_enquiry(): void {
    if(empty($_POST['havenstone_enquiry_action'])) return;
    if(empty($_POST['havenstone_enquiry_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['havenstone_enquiry_nonce'])),'havenstone_enquiry')) return;
    $name=sanitize_text_field(wp_unslash($_POST['name']??'')); $email=sanitize_email(wp_unslash($_POST['email']??'')); $phone=sanitize_text_field(wp_unslash($_POST['phone']??'')); $message=sanitize_textarea_field(wp_unslash($_POST['message']??'')); $property_id=absint($_POST['property_id']??0);
    if(!$name || !is_email($email) || !$message) return;
    if (!empty($_POST['website'])) return;
    $id=wp_insert_post(['post_type'=>'enquiry','post_status'=>'private','post_title'=>sprintf('%s — %s',$name,$property_id?get_the_title($property_id):'General enquiry'),'post_content'=>$message]);
    if($id && $phone) update_post_meta($id,'_havenstone_phone',$phone);
    if($id) update_post_meta($id,'_havenstone_email',$email);
    if($id){
        update_post_meta($id,'_havenstone_lead_status','new');
        $property_title=$property_id ? get_the_title($property_id) : 'General enquiry';
        $html='<h2>New HavenStone Realty enquiry</h2><p><strong>Name:</strong> '.esc_html($name).'</p><p><strong>Email:</strong> '.esc_html($email).'</p><p><strong>Phone:</strong> '.esc_html($phone).'</p><p><strong>Property:</strong> '.esc_html($property_title).'</p><p><strong>Message:</strong><br>'.nl2br(esc_html($message)).'</p>';
        havenstone_send_notification('New property enquiry — '.$property_title,$html,$email);
    }
}
add_action('init','havenstone_handle_enquiry');

function havenstone_viewing_form_shortcode(): string {
 $notice = isset($_GET['havenstone_status']) && $_GET['havenstone_status'] === 'viewing_sent' ? '<p class="havenstone-form-success" role="status">Your viewing request has been received. The HavenStone team will contact you to confirm the appointment.</p>' : '';
 ob_start(); echo $notice; ?><form class="havenstone-viewing-form" method="post"><?php wp_nonce_field('havenstone_viewing','havenstone_viewing_nonce'); ?><input type="hidden" name="havenstone_viewing_action" value="1"><input type="hidden" name="property_id" value="<?php echo esc_attr(get_the_ID()); ?>"><p class="havenstone-hp" aria-hidden="true"><label>Website<input tabindex="-1" autocomplete="off" name="website"></label></p><p><label>Name<input required name="name"></label></p><p><label>Email<input required type="email" name="email"></label></p><p><label>Phone<input name="phone"></label></p><p><label>Preferred date<input required type="date" name="date"></label></p><p><label>Preferred time<input required type="time" name="time"></label></p><p><label>Notes<textarea name="notes"></textarea></label></p><button type="submit">Request viewing</button></form><?php return (string)ob_get_clean();
}
add_shortcode('havenstone_viewing_form','havenstone_viewing_form_shortcode');

function havenstone_handle_viewing_request(): void {
 if(empty($_POST['havenstone_viewing_action']))return;
 if(empty($_POST['havenstone_viewing_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['havenstone_viewing_nonce'])),'havenstone_viewing'))return;
 $name=sanitize_text_field(wp_unslash($_POST['name']??''));$email=sanitize_email(wp_unslash($_POST['email']??''));$phone=sanitize_text_field(wp_unslash($_POST['phone']??''));$date=sanitize_text_field(wp_unslash($_POST['date']??''));$time=sanitize_text_field(wp_unslash($_POST['time']??''));$notes=sanitize_textarea_field(wp_unslash($_POST['notes']??''));$property_id=absint($_POST['property_id']??0);
 if(!$name||!is_email($email)||!$date||!$time)return;
 if (!empty($_POST['website'])) return;
 $id=wp_insert_post(['post_type'=>'viewing_request','post_status'=>'private','post_title'=>sprintf('%s — %s',$name,$property_id?get_the_title($property_id):'Viewing request'),'post_content'=>$notes]);
 if($id){foreach(['email'=>$email,'phone'=>$phone,'date'=>$date,'time'=>$time,'property_id'=>$property_id] as $k=>$v)update_post_meta($id,'_havenstone_'.$k,$v); update_post_meta($id,'_havenstone_lead_status','new');
   $property_title=$property_id ? get_the_title($property_id) : 'Viewing request';
   $html='<h2>New HavenStone Realty viewing request</h2><p><strong>Name:</strong> '.esc_html($name).'</p><p><strong>Email:</strong> '.esc_html($email).'</p><p><strong>Phone:</strong> '.esc_html($phone).'</p><p><strong>Property:</strong> '.esc_html($property_title).'</p><p><strong>Preferred date:</strong> '.esc_html($date).'</p><p><strong>Preferred time:</strong> '.esc_html($time).'</p><p><strong>Notes:</strong><br>'.nl2br(esc_html($notes)).'</p>';
   havenstone_send_notification('New viewing request — '.$property_title,$html,$email);
 }
}
add_action('init','havenstone_handle_viewing_request');


function havenstone_handle_property_request(): void {
    if (empty($_POST['havenstone_request_property'])) return;
    if (empty($_POST['havenstone_request_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['havenstone_request_nonce'])), 'havenstone_request_property')) return;

    $name=sanitize_text_field(wp_unslash($_POST['name']??''));
    $email=sanitize_email(wp_unslash($_POST['email']??''));
    $phone=sanitize_text_field(wp_unslash($_POST['phone']??''));
    $type=sanitize_text_field(wp_unslash($_POST['property_type']??''));
    $location=sanitize_text_field(wp_unslash($_POST['location']??''));
    $min_price=absint($_POST['min_price']??0);
    $max_price=absint($_POST['max_price']??0);
    $bedrooms=absint($_POST['bedrooms']??0);
    $bathrooms=absint($_POST['bathrooms']??0);
    $requirements=sanitize_textarea_field(wp_unslash($_POST['requirements']??''));
    if(!$name || !is_email($email) || !$requirements) return;
    if (!empty($_POST['website'])) return;

    $content=sprintf("Property type: %s\nLocation: %s\nBudget: %s - %s\nBedrooms: %s\nBathrooms: %s\n\nRequirements:\n%s",
        $type ?: 'Any', $location ?: 'Any', $min_price ? number_format_i18n($min_price) : 'Any',
        $max_price ? number_format_i18n($max_price) : 'Any', $bedrooms ?: 'Any', $bathrooms ?: 'Any', $requirements);

    $id=wp_insert_post(['post_type'=>'enquiry','post_status'=>'private','post_title'=>$name.' — Property request','post_content'=>$content]);
    if(!$id) return;
    foreach(['email'=>$email,'phone'=>$phone,'request_type'=>'property_request','property_type'=>$type,'location'=>$location,'min_price'=>$min_price,'max_price'=>$max_price,'bedrooms'=>$bedrooms,'bathrooms'=>$bathrooms] as $k=>$v) update_post_meta($id,'_havenstone_'.$k,$v);
    update_post_meta($id,'_havenstone_lead_status','new');

    $html='<h2>New HavenStone Realty property request</h2>'.
        '<p><strong>Name:</strong> '.esc_html($name).'</p>'.
        '<p><strong>Email:</strong> '.esc_html($email).'</p>'.
        '<p><strong>Phone:</strong> '.esc_html($phone).'</p>'.
        '<p><strong>Property type:</strong> '.esc_html($type ?: 'Any').'</p>'.
        '<p><strong>Location:</strong> '.esc_html($location ?: 'Any').'</p>'.
        '<p><strong>Budget:</strong> '.esc_html(($min_price ? number_format_i18n($min_price) : 'Any').' - '.($max_price ? number_format_i18n($max_price) : 'Any')).'</p>'.
        '<p><strong>Bedrooms:</strong> '.esc_html($bedrooms ?: 'Any').' &nbsp; <strong>Bathrooms:</strong> '.esc_html($bathrooms ?: 'Any').'</p>'.
        '<p><strong>Requirements:</strong><br>'.nl2br(esc_html($requirements)).'</p>';
    havenstone_send_notification('New property request — '.$name,$html,$email);
}
add_action('init','havenstone_handle_property_request');
