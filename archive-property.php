<?php get_header(); ?>
<main id="main-content" class="site-container section">
<header class="archive-header">
<p class="eyebrow">HavenStone Realty</p>
<h1>Properties</h1>
<p>Browse available homes, land and investment opportunities. Use the filters below to narrow the results.</p>
</header>

<form class="property-filters property-filters--advanced" method="get" action="<?php echo esc_url(get_post_type_archive_link('property')); ?>">
<label>Search
<input type="search" name="property_search" value="<?php echo esc_attr(sanitize_text_field(wp_unslash($_GET['property_search']??''))); ?>" placeholder="Search by property name or keyword">
</label>
<label>Property type
<select name="property_type"><option value="">All types</option><?php foreach(get_terms(['taxonomy'=>'property_type','hide_empty'=>true]) as $term): ?><option value="<?php echo esc_attr($term->slug); ?>" <?php selected($_GET['property_type']??'', $term->slug); ?>><?php echo esc_html($term->name); ?></option><?php endforeach; ?></select>
</label>
<label>Location
<select name="property_location"><option value="">All locations</option><?php foreach(get_terms(['taxonomy'=>'property_location','hide_empty'=>true]) as $term): ?><option value="<?php echo esc_attr($term->slug); ?>" <?php selected($_GET['property_location']??'', $term->slug); ?>><?php echo esc_html($term->name); ?></option><?php endforeach; ?></select>
</label>
<label>Status
<select name="property_status"><option value="">All statuses</option><?php foreach(get_terms(['taxonomy'=>'property_status','hide_empty'=>true]) as $term): ?><option value="<?php echo esc_attr($term->slug); ?>" <?php selected($_GET['property_status']??'', $term->slug); ?>><?php echo esc_html($term->name); ?></option><?php endforeach; ?></select>
</label>
<label>Min price
<input type="number" name="min_price" value="<?php echo esc_attr($_GET['min_price']??''); ?>" min="0" step="1" placeholder="0">
</label>
<label>Max price
<input type="number" name="max_price" value="<?php echo esc_attr($_GET['max_price']??''); ?>" min="0" step="1" placeholder="No maximum">
</label>
<label>Min bedrooms
<select name="min_bedrooms"><option value="">Any</option><?php for($i=1;$i<=10;$i++): ?><option value="<?php echo $i; ?>" <?php selected($_GET['min_bedrooms']??'', (string)$i); ?>><?php echo $i; ?>+</option><?php endfor; ?></select>
</label>
<label>Min bathrooms
<select name="min_bathrooms"><option value="">Any</option><?php for($i=1;$i<=10;$i++): ?><option value="<?php echo $i; ?>" <?php selected($_GET['min_bathrooms']??'', (string)$i); ?>><?php echo $i; ?>+</option><?php endfor; ?></select>
</label>
<label>Sort by
<select name="sort">
<option value="date" <?php selected($_GET['sort']??'date','date'); ?>>Newest</option>
<option value="price_low" <?php selected($_GET['sort']??'','price_low'); ?>>Price: low to high</option>
<option value="price_high" <?php selected($_GET['sort']??'','price_high'); ?>>Price: high to low</option>
<option value="oldest" <?php selected($_GET['sort']??'','oldest'); ?>>Oldest</option>
</select>
</label>
<div class="property-filters__actions">
<button type="submit">Search properties</button>
<a class="button button--outline" href="<?php echo esc_url(get_post_type_archive_link('property')); ?>">Clear filters</a>
</div>
</form>

<?php $query=havenstone_property_query(); ?>
<?php if($query->found_posts): ?>
<p class="property-results-count"><?php echo esc_html(number_format_i18n($query->found_posts)); ?> propert<?php echo $query->found_posts===1?'y':'ies'; ?> found</p>
<div class="property-grid"><?php while($query->have_posts()):$query->the_post(); echo havenstone_property_card(get_post()); endwhile; ?></div>
<?php else: ?>
<div class="property-empty"><h2>No properties matched your search.</h2><p>Try broadening your filters or clearing the search to view all available listings.</p><a class="button" href="<?php echo esc_url(get_post_type_archive_link('property')); ?>">View all properties</a></div>
<?php endif; ?>

<?php if($query->max_num_pages>1): ?><nav class="property-pagination" aria-label="Property pagination"><?php
$pagination_args=[
'property_search'=>sanitize_text_field(wp_unslash($_GET['property_search']??'')),
'property_type'=>sanitize_text_field(wp_unslash($_GET['property_type']??'')),
'property_location'=>sanitize_text_field(wp_unslash($_GET['property_location']??'')),
'property_status'=>sanitize_text_field(wp_unslash($_GET['property_status']??'')),
'min_price'=>sanitize_text_field(wp_unslash($_GET['min_price']??'')),
'max_price'=>sanitize_text_field(wp_unslash($_GET['max_price']??'')),
'min_bedrooms'=>absint($_GET['min_bedrooms']??0),
'min_bathrooms'=>absint($_GET['min_bathrooms']??0),
'sort'=>sanitize_key(wp_unslash($_GET['sort']??'date')),
];
echo wp_kses_post(paginate_links(['total'=>$query->max_num_pages,'current'=>max(1,get_query_var('paged')),'type'=>'list','add_args'=>array_filter($pagination_args)]));
?></nav><?php endif; ?>
<?php wp_reset_postdata(); ?>
</main>
<?php get_footer(); ?>