<?php get_header(); ?>
<main class="havenstone-page">
<section class="page-hero"><div class="site-container"><p class="eyebrow">Locations</p><h1>Explore property opportunities by location.</h1><p>Browse HavenStone listings by market and discover homes, land and investment opportunities.</p></div></section>
<section class="site-container section location-grid"><?php $locations=['Lagos','Abuja','Port Harcourt','Other Locations']; foreach($locations as $location): ?><article class="location-card"><h2><?php echo esc_html($location); ?></h2><p>Explore available HavenStone properties and opportunities in <?php echo esc_html($location); ?>.</p><a class="text-link" href="<?php echo esc_url(home_url('/properties/')); ?>">View properties →</a></article><?php endforeach; ?></section>
<section class="cta section"><div class="site-container"><h2>Looking for a property in a specific area?</h2><p>Send your requirements and HavenStone can help identify suitable opportunities.</p><a class="button" href="<?php echo esc_url(home_url('/request-a-property/')); ?>">Request a property</a></div></section>
</main>
<?php get_footer(); ?>