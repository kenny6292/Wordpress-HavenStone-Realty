<footer class="site-footer">
<div class="site-container">
<p>&copy; <?php echo esc_html(wp_date('Y')); ?> <?php bloginfo('name'); ?>.</p>
<?php wp_nav_menu(['theme_location'=>'footer','fallback_cb'=>false]); ?>
</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
