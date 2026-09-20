<?php get_header(); ?>
<main class="site-container section">
<header class="archive-header"><p class="eyebrow">HavenStone Realty</p><h1>Properties</h1><p>Browse available homes, land and investment opportunities.</p></header>
<form class="property-filters" method="get" action="<?php echo esc_url(get_post_type_archive_link('property')); ?>">
<label>Property type <select name="property_type"><option value="">All types</option><?php foreach(get_terms(['taxonomy'=>'property_type','hide_empty'=>true]) as $term): ?><option value="<?php echo esc_attr($term->slug); ?>" <?php selected($_GET['property_type']??'', $term->slug); ?>><?php echo esc_html($term->name); ?></option><?php endforeach; ?></select></label>
<label>Location <select name="property_location"><option value="">All locations</option><?php foreach(get_terms(['taxonomy'=>'property_location','hide_empty'=>true]) as $term): ?><option value="<?php echo esc_attr($term->slug); ?>" <?php selected($_GET['property_location']??'', $term->slug); ?>><?php echo esc_html($term->name); ?></option><?php endforeach; ?></select></label>
<label>Status <select name="property_status"><option value="">All statuses</option><?php foreach(get_terms(['taxonomy'=>'property_status','hide_empty'=>true]) as $term): ?><option value="<?php echo esc_attr($term->slug); ?>" <?php selected($_GET['property_status']??'', $term->slug); ?>><?php echo esc_html($term->name); ?></option><?php endforeach; ?></select></label>
<label>Min price <input type="number" name="min_price" value="<?php echo esc_attr($_GET['min_price']??''); ?>" min="0"></label>
<label>Max price <input type="number" name="max_price" value="<?php echo esc_attr($_GET['max_price']??''); ?>" min="0"></label>
<button type="submit">Search properties</button>
</form>
<?php $query=havenstone_property_query(); ?>
<div class="property-grid"><?php while($query->have_posts()):$query->the_post(); echo havenstone_property_card(get_post()); endwhile; ?></div>
<?php if($query->max_num_pages>1): ?><nav class="property-pagination" aria-label="Property pagination"><?php echo wp_kses_post(paginate_links(['total'=>$query->max_num_pages,'current'=>max(1,get_query_var('paged')),'type'=>'list','add_args'=>array_filter(['property_type'=>sanitize_text_field(wp_unslash($_GET['property_type']??'')),'property_location'=>sanitize_text_field(wp_unslash($_GET['property_location']??'')),'property_status'=>sanitize_text_field(wp_unslash($_GET['property_status']??'')),'min_price'=>sanitize_text_field(wp_unslash($_GET['min_price']??'')),'max_price'=>sanitize_text_field(wp_unslash($_GET['max_price']??''))])]))); ?></nav><?php endif; ?>
<?php wp_reset_postdata(); ?>
</main>
<?php get_footer(); ?>
