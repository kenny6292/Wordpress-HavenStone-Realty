<?php get_header(); ?>
<main class="site-container section havenstone-page">
<?php if(have_posts()): while(have_posts()): the_post(); ?>
<header class="page-header"><p class="eyebrow">HavenStone Realty</p><h1><?php the_title(); ?></h1><?php if(has_excerpt()): ?><p class="page-intro"><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?></header>
<div class="page-content"><?php the_content(); ?></div>
<?php endwhile; else: ?><p>No content found.</p><?php endif; ?>
</main>
<?php get_footer(); ?>