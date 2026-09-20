<?php
/**
 * Plugin Name: HavenStone Realty Core
 * Description: Core property, agent, enquiry and real-estate functionality for HavenStone Realty.
 * Version: 1.0.0
 * Author: DEYOUNGTECH
 * Requires PHP: 8.1
 */
if (!defined('ABSPATH')) exit;
define('HAVENSTONE_CORE_VERSION','1.0.0');
define('HAVENSTONE_CORE_PATH',plugin_dir_path(__FILE__));
require_once HAVENSTONE_CORE_PATH.'includes/post-types.php';
require_once HAVENSTONE_CORE_PATH.'includes/taxonomies.php';
require_once HAVENSTONE_CORE_PATH.'includes/property-meta.php';
require_once HAVENSTONE_CORE_PATH.'includes/query.php';
require_once HAVENSTONE_CORE_PATH.'includes/shortcodes.php';
require_once HAVENSTONE_CORE_PATH.'includes/agent-meta.php';
require_once HAVENSTONE_CORE_PATH.'includes/admin.php';
