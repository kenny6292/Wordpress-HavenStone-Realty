<?php get_header(); ?>
<main class="havenstone-page">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article <?php post_class(); ?>>
<h1><?php the_title(); ?></h1>
<div><?php the_content(); ?></div>
</article>
<?php endwhile; else : ?>
<p><?php esc_html_e('No content found.', 'havenstone'); ?></p>
<?php endif; ?>
</main>
<?php get_footer(); ?>
