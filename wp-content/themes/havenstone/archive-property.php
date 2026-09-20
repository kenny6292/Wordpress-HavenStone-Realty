<?php get_header(); ?>
<main class="site-container section">
<header class="archive-header"><p class="eyebrow">HavenStone Realty</p><h1>Properties</h1><p>Browse available homes, land and investment opportunities.</p></header>
<form class="property-filters" method="get" action="<?php echo esc_url(get_post_type_archive_link('property')); ?>">
<label>Property type <select name="property_type"><option value="">All types</option><?php foreach(get_terms(['taxonomy'=>'property_type','hide_empty'=>true]) as $term): ?><option value="<?php echo esc_attr($term->slug); ?>" <?php selected($_GET['property_type']??'', $term->slug); ?>><?php echo esc_html($term->name); ?></option><?php endforeach; ?></select></label>
<label>Location <select name="property_location"><option value="">All locations</option><?php foreach(get_terms(['taxonomy'=>'property_location','hide_empty'=>true]) as $term): ?><option value="<?php echo esc_attr($term->slug); ?>" <?php selected($_GET['property_location']??'', $term->slug); ?>><?php echo esc_html($term->name); ?></option><?php endforeach; ?></select></label>
<label>Min price <input type="number" name="min_price" value="<?php echo esc_attr($_GET['min_price']??''); ?>" min="0"></label>
<label>Max price <input type="number" name="max_price" value="<?php echo esc_attr($_GET['max_price']??''); ?>" min="0"></label>
<button type="submit">Search properties</button>
</form>
<div class="property-grid"><?php $query=have_posts()?null:havenstone_property_query(); if(!$query)$query=havenstone_property_query(); while($query->have_posts()):$query->the_post(); echo havenstone_property_card(get_post()); endwhile; wp_reset_postdata(); ?></div>
</main>
<?php get_footer(); ?>