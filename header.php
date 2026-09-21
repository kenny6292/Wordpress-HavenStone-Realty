<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content">Skip to content</a>
<header class="site-header">
<div class="site-container">
<div class="site-header__bar">
<a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
<button class="site-menu-toggle" type="button" aria-expanded="false" aria-controls="primary-menu" aria-label="<?php esc_attr_e('Open navigation menu', 'havenstone'); ?>">
<span></span><span></span><span></span>
</button>
</div>
<nav id="primary-menu" class="site-nav" aria-label="<?php esc_attr_e('Primary navigation', 'havenstone'); ?>">
<?php
wp_nav_menu([
    'theme_location' => 'primary',
    'fallback_cb' => 'havenstone_fallback_menu',
    'container' => false,
    'menu_id' => 'primary-menu-list',
]);
?>
</nav>
<a class="site-header__cta button button--small" href="<?php echo esc_url(home_url('/request-a-property/')); ?>">Request a Property</a>
</div>
</header>
