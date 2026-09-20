<?php get_header(); while(have_posts()):the_post(); $id=get_the_ID(); $role=get_post_meta($id,'_havenstone_role',true); $phone=get_post_meta($id,'_havenstone_phone',true); $wa=get_post_meta($id,'_havenstone_whatsapp',true); $email=get_post_meta($id,'_havenstone_email',true); $license=get_post_meta($id,'_havenstone_license',true); ?>
<main class="site-container section">
<a class="back-link" href="<?php echo esc_url(get_post_type_archive_link('agent')); ?>">← All agents</a>
<div class="agent-profile">
<div><?php if(has_post_thumbnail()) the_post_thumbnail('large',['loading'=>'eager']); ?></div>
<div><p class="eyebrow">HavenStone Realty</p><h1><?php the_title(); ?></h1><?php if($role): ?><p class="agent-profile__role"><?php echo esc_html($role); ?></p><?php endif; ?><?php if($license): ?><p>License / Registration: <?php echo esc_html($license); ?></p><?php endif; ?><div class="agent-profile__actions"><?php if($phone): ?><a class="button" href="<?php echo esc_url('tel:'.preg_replace('/[^0-9+]/','',$phone)); ?>">Call</a><?php endif; ?><?php if($wa): ?><a class="button" target="_blank" rel="noopener" href="<?php echo esc_url('https://wa.me/'.preg_replace('/[^0-9]/','',$wa)); ?>">WhatsApp</a><?php endif; ?><?php if($email): ?><a class="button" href="<?php echo esc_url('mailto:'.$email); ?>">Email</a><?php endif; ?></div></div>
</div>
<section class="agent-profile__bio"><?php the_content(); ?></section>
</main><?php endwhile; get_footer(); ?>
