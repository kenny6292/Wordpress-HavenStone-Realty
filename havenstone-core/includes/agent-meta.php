<?php
if (!defined('ABSPATH')) exit;
function havenstone_agent_meta_boxes(): void { add_meta_box('havenstone_agent_details','Agent Details','havenstone_render_agent_details','agent','normal','high'); }
add_action('add_meta_boxes','havenstone_agent_meta_boxes');
function havenstone_render_agent_details(WP_Post $post): void {
 wp_nonce_field('havenstone_agent_details','havenstone_agent_details_nonce');
 foreach(['phone'=>'Phone','whatsapp'=>'WhatsApp','email'=>'Email','role'=>'Role','license'=>'License / Registration'] as $key=>$label){$value=get_post_meta($post->ID,'_havenstone_'.$key,true);echo '<p><label><strong>'.esc_html($label).'</strong></label><input class="widefat" name="havenstone_agent_'.$key.'" value="'.esc_attr($value).'"></p>';}
}
function havenstone_save_agent_meta(int $post_id): void {
 if(!isset($_POST['havenstone_agent_details_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['havenstone_agent_details_nonce'])),'havenstone_agent_details'))return;
 if(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE)return;if(!current_user_can('edit_post',$post_id))return;
 foreach(['phone','whatsapp','email','role','license'] as $key)if(isset($_POST['havenstone_agent_'.$key]))update_post_meta($post_id,'_havenstone_'.$key,sanitize_text_field(wp_unslash($_POST['havenstone_agent_'.$key])));
}
add_action('save_post_agent','havenstone_save_agent_meta');

function havenstone_agent_whatsapp_url(string $value): string {
 $value=trim($value); if(!$value)return '';
 if(filter_var($value,FILTER_VALIDATE_URL))return esc_url_raw($value);
 $digits=preg_replace('/[^0-9]/','',$value); return $digits ? 'https://wa.me/'.$digits : '';
}

function havenstone_agent_shortcode(array $atts=[]): string {
 $q=new WP_Query(['post_type'=>'agent','post_status'=>'publish','posts_per_page'=>12]);ob_start();echo '<div class="agent-grid">';
 while($q->have_posts()){$q->the_post();$id=get_the_ID();$role=get_post_meta($id,'_havenstone_role',true);$phone=get_post_meta($id,'_havenstone_phone',true);$wa=get_post_meta($id,'_havenstone_whatsapp',true);echo '<article class="agent-card">';if(has_post_thumbnail())echo get_the_post_thumbnail('','medium',['loading'=>'lazy']);echo '<h3><a href="'.esc_url(get_permalink()).'">'.esc_html(get_the_title()).'</a></h3>';if($role)echo '<p>'.esc_html($role).'</p>';if($phone)echo '<a href="tel:'.esc_attr($phone).'">Call</a> ';$wa_url=havenstone_agent_whatsapp_url($wa);if($wa_url)echo '<a href="'.esc_url($wa_url).'" target="_blank" rel="noopener">WhatsApp</a>';echo '</article>';}
 wp_reset_postdata();echo '</div>';return (string)ob_get_clean();
}
add_shortcode('havenstone_agents','havenstone_agent_shortcode');