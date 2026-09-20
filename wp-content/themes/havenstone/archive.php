<?php get_header(); ?>
<main id="main-content" class="site-container section">
<header class="page-header"><p class="eyebrow">HavenStone Realty</p><h1><?php the_archive_title(); ?></h1><?php the_archive_description('<div class="page-intro">','</div>'); ?></header>
<?php if(have_posts()): ?><div class="post-grid"><?php while(have_posts()): the_post(); ?><article class="post-card"><a href="<?php the_permalink(); ?>"><?php if(has_post_thumbnail()) the_post_thumbnail('large'); ?><div class="post-card__body"><p class="eyebrow"><?php echo esc_html(get_the_date()); ?></p><h2><?php the_title(); ?></h2><p><?php echo esc_html(wp_trim_words(get_the_excerpt(),24)); ?></p><span class="text-link">Read article →</span></div></a></article><?php endwhile; ?></div><?php the_posts_pagination(); ?><?php else: ?><div class="property-empty"><h2>No posts found.</h2></div><?php endif; ?>
</main>
<?php get_footer(); ?>