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
    ob_start(); ?>
    <form class="havenstone-enquiry-form" method="post">
        <?php wp_nonce_field('havenstone_enquiry','havenstone_enquiry_nonce'); ?>
        <input type="hidden" name="havenstone_enquiry_action" value="1">
        <input type="hidden" name="property_id" value="<?php echo esc_attr(get_the_ID()); ?>">
        <p><label>Name<input required name="name"></label></p>
        <p><label>Email<input required type="email" name="email"></label></p>
        <p><label>Phone<input name="phone"></label></p>
        <p><label>Message<textarea required name="message"></textarea></label></p>
        <button type="submit">Send enquiry</button>
    </form>
    <?php return (string)ob_get_clean();
}
add_shortcode('havenstone_enquiry_form','havenstone_enquiry_form_shortcode');

function havenstone_handle_enquiry(): void {
    if(empty($_POST['havenstone_enquiry_action'])) return;
    if(empty($_POST['havenstone_enquiry_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['havenstone_enquiry_nonce'])),'havenstone_enquiry')) return;
    $name=sanitize_text_field(wp_unslash($_POST['name']??'')); $email=sanitize_email(wp_unslash($_POST['email']??'')); $phone=sanitize_text_field(wp_unslash($_POST['phone']??'')); $message=sanitize_textarea_field(wp_unslash($_POST['message']??'')); $property_id=absint($_POST['property_id']??0);
    if(!$name || !is_email($email) || !$message) return;
    $id=wp_insert_post(['post_type'=>'enquiry','post_status'=>'private','post_title'=>sprintf('%s — %s',$name,$property_id?get_the_title($property_id):'General enquiry'),'post_content'=>$message]);
    if($id && $phone) update_post_meta($id,'_havenstone_phone',$phone);
    if($id) update_post_meta($id,'_havenstone_email',$email);
}
add_action('init','havenstone_handle_enquiry');
