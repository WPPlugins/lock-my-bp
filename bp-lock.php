<?php
/**
 * Plugin Name: WordPress Lock for BuddyPress
 * Plugin URI: https://wbcomdesigns.com/contact/
 * Description: This plugin allows site administrator to lock certain pages for non-loggedin users.
 * Version: 1.0.2
 * Author: Wbcom Designs
 * Author URI: http://wbcomdesigns.com
 * License: GPLv2+
 * Text Domain: lock-my-bp
 **/

defined('ABSPATH') || exit; // Exit if accessed directly

//Constants used in the plugin
define('LOCKBP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('LOCKBP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('LOCKBP_PLUGIN_TEXT_DOMAIN', 'lock-my-bp');
define('LOCKBP_BP_ACTIVE', in_array('buddypress/bp-loader.php', get_option('active_plugins')));

//Include needed files
$include_files = array(
	'admin/lockbp-admin.php',
	'inc/lockbp-scripts.php',
	'inc/lockbp-filters.php'
);
foreach ($include_files  as $include_file) {
	include $include_file;
}

//Settings link for this plugin
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'lockbp_admin_page_link');
function lockbp_admin_page_link($links) {
	$page_link = array('<a href="'.admin_url('admin.php?page=lock-my-bp').'">'.__( 'Lock', LOCKBP_PLUGIN_TEXT_DOMAIN ).'</a>');
	return array_merge($links, $page_link);
}
