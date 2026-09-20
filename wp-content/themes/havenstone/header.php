<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
<div class="site-container">
<a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
<nav aria-label="<?php esc_attr_e('Primary navigation', 'havenstone'); ?>">
<?php
wp_nav_menu([
    'theme_location' => 'primary',
    'fallback_cb' => 'havenstone_fallback_menu',
    'container' => false,
]);
?>
</nav>
</div>
</header>
