<?php
get_header();

while (have_posts()) :
    the_post();

    $id       = get_the_ID();
    $price    = get_post_meta($id, '_havenstone_price_label', true);
    $beds     = get_post_meta($id, '_havenstone_bedrooms', true);
    $baths    = get_post_meta($id, '_havenstone_bathrooms', true);
    $parking  = get_post_meta($id, '_havenstone_parking', true);
    $area     = get_post_meta($id, '_havenstone_area', true);
    $address  = get_post_meta($id, '_havenstone_address', true);
    $map      = get_post_meta($id, '_havenstone_map_url', true);
    $agent_id = (int) get_post_meta($id, '_havenstone_agent_id', true);
    $gallery  = get_post_meta($id, '_havenstone_gallery', true);
    $gallery_ids = array_values(array_filter(array_map('absint', preg_split('/[,\s]+/', (string) $gallery))));
    $amenities = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) get_post_meta($id, '_havenstone_amenities', true)))));
    $status_terms = get_the_terms($id, 'property_status');
    $type_terms = get_the_terms($id, 'property_type');
    $location_terms = get_the_terms($id, 'property_location');
    $status = (!is_wp_error($status_terms) && $status_terms) ? $status_terms[0]->name : '';
    $type = (!is_wp_error($type_terms) && $type_terms) ? $type_terms[0]->name : '';
    $location = (!is_wp_error($location_terms) && $location_terms) ? $location_terms[0]->name : '';
    ?>
<?php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'RealEstateListing',
        'name' => get_the_title(),
        'url' => get_permalink(),
        'description' => wp_strip_all_tags(get_the_excerpt()),
    ];
    if ($price) $schema['offers'] = ['@type' => 'Offer', 'price' => preg_replace('/[^0-9.]/', '', (string) get_post_meta($id, '_havenstone_price', true)), 'priceCurrency' => 'NGN'];
    if ($address) $schema['address'] = ['@type' => 'PostalAddress', 'streetAddress' => $address];
    if ($beds !== '') $schema['numberOfRooms'] = (int) $beds;
    if (has_post_thumbnail()) $schema['image'] = get_the_post_thumbnail_url($id, 'full');
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
?>
    <main class="property-single site-container section">
        <a class="back-link" href="<?php echo esc_url(get_post_type_archive_link('property')); ?>">← All properties</a>

        <header class="property-single__header">
            <div class="property-single__badges">
                <?php if ($status) : ?><span class="property-badge"><?php echo esc_html($status); ?></span><?php endif; ?>
                <?php if ($type) : ?><span class="property-badge property-badge--muted"><?php echo esc_html($type); ?></span><?php endif; ?>
                <?php if ($location) : ?><span class="property-badge property-badge--muted"><?php echo esc_html($location); ?></span><?php endif; ?>
            </div>
            <p class="property-single__price"><?php echo esc_html($price); ?></p>
            <h1><?php the_title(); ?></h1>
            <?php if ($address) : ?><p class="property-single__address"><?php echo esc_html($address); ?></p><?php endif; ?>
        </header>

        <?php if (has_post_thumbnail()) : ?>
            <figure class="property-single__hero"><?php the_post_thumbnail('full', ['loading' => 'eager']); ?></figure>
        <?php endif; ?>

        <?php if ($gallery_ids) : ?>
            <section class="property-gallery" aria-label="<?php esc_attr_e('Property gallery', 'havenstone'); ?>">
                <?php foreach ($gallery_ids as $image_id) :
                    $image = wp_get_attachment_image($image_id, 'large', false, ['loading' => 'lazy']);
                    if (!$image) continue;
                    ?>
                    <a class="property-gallery__item" href="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'full')); ?>" target="_blank" rel="noopener">
                        <?php echo $image; ?>
                    </a>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <div class="property-single__facts">
            <?php foreach ([['Bedrooms', $beds], ['Bathrooms', $baths], ['Parking', $parking], ['Area', $area]] as [$label, $value]) :
                if ($value === '') continue;
                ?>
                <div><strong><?php echo esc_html($value); ?></strong><span><?php echo esc_html($label); ?></span></div>
            <?php endforeach; ?>
        </div>

        <div class="property-single__content">
            <article>
                <?php the_content(); ?>

                <?php if ($amenities) : ?>
                    <section class="property-amenities">
                        <h2>Features &amp; amenities</h2>
                        <ul>
                            <?php foreach ($amenities as $amenity) : ?><li><?php echo esc_html($amenity); ?></li><?php endforeach; ?>
                        </ul>
                    </section>
                <?php endif; ?>

                <section class="property-actions">
                    <h2>Interested in this property?</h2>
                    <p>Send an enquiry or request a viewing and the HavenStone team can follow up with the details.</p>
                    <?php echo do_shortcode('[havenstone_enquiry_form]'); ?>
                    <?php echo do_shortcode('[havenstone_viewing_form]'); ?>
                </section>
            </article>

            <aside>
                <div class="enquiry-card">
                    <h2>Property enquiry</h2>
                    <p>Get availability, pricing details and viewing information.</p>
                    <?php if ($map) : ?><a class="button" href="<?php echo esc_url($map); ?>" target="_blank" rel="noopener">View on map</a><?php endif; ?>
                </div>

                <?php if ($agent_id && get_post_status($agent_id)) :
                    $agent_phone = get_post_meta($agent_id, '_havenstone_phone', true);
                    $agent_whatsapp = get_post_meta($agent_id, '_havenstone_whatsapp', true);
                    $agent_email = get_post_meta($agent_id, '_havenstone_email', true);
                    ?>
                    <div class="agent-card">
                        <h3>Your agent</h3>
                        <a class="agent-card__name" href="<?php echo esc_url(get_permalink($agent_id)); ?>"><?php echo esc_html(get_the_title($agent_id)); ?></a>
                        <?php if ($agent_phone) : ?><a class="button button--small" href="<?php echo esc_url('tel:' . preg_replace('/[^0-9+]/', '', $agent_phone)); ?>">Call agent</a><?php endif; ?>
                        <?php if ($agent_whatsapp) : ?><a class="button button--small" target="_blank" rel="noopener" href="<?php echo esc_url('https://wa.me/' . preg_replace('/[^0-9]/', '', $agent_whatsapp)); ?>">WhatsApp</a><?php endif; ?>
                        <?php if ($agent_email) : ?><a class="button button--small" href="<?php echo esc_url('mailto:' . $agent_email); ?>">Email agent</a><?php endif; ?>
                    </div>
                <?php endif; ?>
            </aside>
        </div>

        <?php
        $similar_args = [
            'post_type' => 'property',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'post__not_in' => [$id],
        ];
        if ($location_terms && !is_wp_error($location_terms)) {
            $similar_args['tax_query'] = [[
                'taxonomy' => 'property_location',
                'field' => 'term_id',
                'terms' => wp_list_pluck($location_terms, 'term_id'),
            ]];
        }
        $similar = new WP_Query($similar_args);
        if ($similar->have_posts()) :
            ?>
            <section class="similar-properties">
                <div class="section-heading"><p class="eyebrow">Explore more</p><h2>Similar properties</h2></div>
                <div class="property-grid">
                    <?php while ($similar->have_posts()) : $similar->the_post(); echo havenstone_property_card(get_post()); endwhile; ?>
                </div>
            </section>
            <?php wp_reset_postdata(); endif; ?>
    </main>
    <?php
endwhile;

get_footer();
