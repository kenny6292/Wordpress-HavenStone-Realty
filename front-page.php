<?php get_header(); ?>
<main id="main-content">
<section class="hero">
  <div class="hero-media" aria-hidden="true">
    <img src="https://images.unsplash.com/photo-1769780265587-037ee842c0b0?auto=format&fit=crop&fm=jpg&q=82&w=2200" alt="">
  </div>
  <div class="site-container hero-content">
    <p class="eyebrow">HavenStone Realty • Homes • Land • Investment</p>
    <h1>Find a place worth calling home.</h1>
    <p>Explore professionally presented homes, land and investment properties with guidance from search through closing.</p>
    <a class="button" href="<?php echo esc_url(home_url('/properties/')); ?>">Explore Properties</a>
    <a class="button button--outline" href="<?php echo esc_url(home_url('/request-a-property/')); ?>">Request a Property</a>
  </div>
</section>

<section class="site-container section home-intro">
  <div>
    <p class="eyebrow">A better property experience</p>
    <h2>Property decisions made clearer.</h2>
    <p>HavenStone Realty is built around straightforward property discovery, clear communication and practical support for buyers, renters, owners and investors.</p>
    <p><a class="text-link" href="<?php echo esc_url(home_url('/about-us/')); ?>">Learn about HavenStone →</a></p>
  </div>
  <div class="home-intro__image">
    <img src="https://images.unsplash.com/photo-1781344334903-f33d8b76e292?auto=format&fit=crop&fm=jpg&q=82&w=1400" alt="Bright modern living room with large windows" loading="lazy">
  </div>
</section>

<section class="site-container section">
  <div class="section-heading">
    <p class="eyebrow">Discover</p>
    <h2>Search the right property</h2>
    <p>Search by keyword, type, location, budget, bedrooms and bathrooms. Results come from properties published by the HavenStone administrator.</p>
  </div>
  <form class="property-filters property-filters--advanced" method="get" action="<?php echo esc_url(get_post_type_archive_link('property')); ?>">
    <label>Search<input type="search" name="property_search" placeholder="Property name or keyword"></label>
    <label>Property type<select name="property_type"><option value="">All types</option><?php foreach(get_terms(['taxonomy'=>'property_type','hide_empty'=>true]) as $t): ?><option value="<?php echo esc_attr($t->slug); ?>"><?php echo esc_html($t->name); ?></option><?php endforeach; ?></select></label>
    <label>Location<select name="property_location"><option value="">All locations</option><?php foreach(get_terms(['taxonomy'=>'property_location','hide_empty'=>true]) as $t): ?><option value="<?php echo esc_attr($t->slug); ?>"><?php echo esc_html($t->name); ?></option><?php endforeach; ?></select></label>
    <label>Status<select name="property_status"><option value="">Any status</option><?php foreach(get_terms(['taxonomy'=>'property_status','hide_empty'=>true]) as $t): ?><option value="<?php echo esc_attr($t->slug); ?>"><?php echo esc_html($t->name); ?></option><?php endforeach; ?></select></label>
    <label>Min price<input type="number" name="min_price" min="0" step="1" placeholder="0"></label>
    <label>Max price<input type="number" name="max_price" min="0" step="1" placeholder="No maximum"></label>
    <label>Min bedrooms<select name="min_bedrooms"><option value="">Any</option><?php for($i=1;$i<=10;$i++): ?><option value="<?php echo $i; ?>"><?php echo $i; ?>+</option><?php endfor; ?></select></label>
    <label>Min bathrooms<select name="min_bathrooms"><option value="">Any</option><?php for($i=1;$i<=10;$i++): ?><option value="<?php echo $i; ?>"><?php echo $i; ?>+</option><?php endfor; ?></select></label>
    <div class="property-filters__actions"><button type="submit">Search properties</button><a class="button button--outline" href="<?php echo esc_url(get_post_type_archive_link('property')); ?>">Advanced search</a></div>
  </form>
</section>

<section class="site-container section">
  <div class="section-heading">
    <p class="eyebrow">Visual guide</p>
    <h2>Spaces worth exploring.</h2>
    <p>Editorial photography sets the tone for the HavenStone experience. It is visual inspiration, not a representation of available listings.</p>
  </div>
  <div class="visual-strip">
    <article class="visual-card">
      <img src="https://images.unsplash.com/photo-1769780265587-037ee842c0b0?auto=format&fit=crop&fm=jpg&q=82&w=1600" alt="Modern luxury residential exterior" loading="lazy">
      <div class="visual-card__overlay"><h3>Homes</h3><p>Residential spaces with character.</p></div>
    </article>
    <article class="visual-card">
      <img src="https://images.unsplash.com/photo-1781344334903-f33d8b76e292?auto=format&fit=crop&fm=jpg&q=82&w=1200" alt="Modern residential interior" loading="lazy">
      <div class="visual-card__overlay"><h3>Interiors</h3><p>Light, space and everyday living.</p></div>
    </article>
    <article class="visual-card">
      <img src="https://images.unsplash.com/photo-1781512436292-f2687f67f605?auto=format&fit=crop&fm=jpg&q=82&w=1200" alt="Modern apartment building exterior" loading="lazy">
      <div class="visual-card__overlay"><h3>Development</h3><p>Contemporary property environments.</p></div>
    </article>
  </div>
</section>

<section class="site-container section">
  <p class="eyebrow">Featured</p>
  <h2>Featured properties</h2>
  <?php $q=new WP_Query(['post_type'=>'property','post_status'=>'publish','posts_per_page'=>6,'meta_key'=>'_havenstone_featured','meta_value'=>'1']); ?>
  <?php if($q->have_posts()): ?><div class="property-grid"><?php while($q->have_posts()):$q->the_post(); echo havenstone_property_card(get_post()); endwhile; ?></div><?php else: ?>
    <div class="property-empty"><h3>Property listings are being prepared.</h3><p>HavenStone Realty is currently updating this section with available homes, land and investment opportunities.</p><a class="button" href="<?php echo esc_url(home_url('/request-a-property/')); ?>">Request a property</a></div>
  <?php endif; wp_reset_postdata(); ?>
  <p><a class="text-link" href="<?php echo esc_url(home_url('/properties/')); ?>">View all properties →</a></p>
</section>

<section class="site-container section services-grid">
  <div><p class="eyebrow">Our services</p><h2>Real estate support beyond the listing.</h2></div>
  <div class="service-item"><h3>Property Sales</h3><p>Guidance for buyers and sellers from discovery to completion.</p></div>
  <div class="service-item"><h3>Rentals</h3><p>Find residential rental opportunities matched to your needs.</p></div>
  <div class="service-item"><h3>Property Management</h3><p>Practical support for owners managing valuable assets.</p></div>
  <div class="service-item"><h3>Investment</h3><p>Explore land and property opportunities for long-term goals.</p></div>
</section>

<section class="cta section">
  <div class="site-container"><p class="eyebrow">Ready when you are</p><h2>Tell us what you are looking for.</h2><p>Share your requirements and the HavenStone team can help narrow the search.</p><a class="button" href="<?php echo esc_url(home_url('/request-a-property/')); ?>">Request a Property</a></div>
</section>
</main>
<?php get_footer(); ?>