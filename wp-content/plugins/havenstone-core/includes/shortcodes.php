<?php
if (!defined('ABSPATH')) exit;

function havenstone_property_card(WP_Post $property): string {
    $price = get_post_meta($property->ID, '_havenstone_price_label', true);
    $beds = get_post_meta($property->ID, '_havenstone_bedrooms', true);
    $baths = get_post_meta($property->ID, '_havenstone_bathrooms', true);
    $area = get_post_meta($property->ID, '_havenstone_area', true);
    ob_start(); ?>
    <article class="property-card">
        <a href="<?php echo esc_url(get_permalink($property)); ?>">
            <?php if (has_post_thumbnail($property)) echo get_the_post_thumbnail($property, 'large', ['loading'=>'lazy']); ?>
            <div class="property-card__body">
                <p class="property-card__price"><?php echo esc_html($price); ?></p>
                <h3><?php echo esc_html(get_the_title($property)); ?></h3>
                <p><?php echo esc_html(implode(' • ', array_filter([$beds ? $beds . ' beds' : '', $baths ? $baths . ' baths' : '', $area]))); ?></p>
            </div>
        </a>
    </article>
    <?php return (string) ob_get_clean();
}

function havenstone_properties_shortcode(array $atts = []): string {
    $query = havenstone_property_query(wp_parse_args($atts, ['posts_per_page'=>12]));
    ob_start();
    echo '<div class="property-grid">';
    while ($query->have_posts()) { $query->the_post(); echo havenstone_property_card(get_post()); }
    echo '</div>';
    wp_reset_postdata();
    return (string) ob_get_clean();
}
add_shortcode('havenstone_properties', 'havenstone_properties_shortcode');
