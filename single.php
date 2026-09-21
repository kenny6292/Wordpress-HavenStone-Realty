<?php get_header(); ?>
<main id="main-content" class="site-container section havenstone-page">
<?php while(have_posts()): the_post(); ?>
<article <?php post_class('single-post'); ?>>
<header class="page-header"><p class="eyebrow"><?php echo esc_html(get_the_date()); ?></p><h1><?php the_title(); ?></h1></header>
<?php if(has_post_thumbnail()): ?><div class="single-post__image"><?php the_post_thumbnail('large'); ?></div><?php endif; ?>
<div class="page-content"><?php the_content(); ?></div>
</article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>