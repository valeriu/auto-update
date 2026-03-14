<?php
/**
 * Plugin Name: Auto Update
 * Plugin URI: https://stylishwp.com
 * Description: Adds a settings page for managing automatic updates for WordPress core, plugins, and themes.
 * Text Domain: auto-update
 * Domain Path: /languages
 * Version: 1.1.0
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Valeriu Tihai
 * Author URI: http://valeriu.tihai.ca
 * Contributors: valeriutihai
 * Donate link: https://paypal.me/valeriu/5
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Auto_Update
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ATU_VERSION', '1.1.0' );
define( 'ATU_PLUGIN_FILE', __FILE__ );
define( 'ATU_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once ATU_PLUGIN_DIR . 'includes/class-atu-auto-update-plugin.php';

register_activation_hook( ATU_PLUGIN_FILE, array( 'ATU_Auto_Update_Plugin', 'atu_activate' ) );

/**
 * Gets the main plugin instance.
 *
 * @since 1.1.0
 *
 * @return ATU_Auto_Update_Plugin
 */
function atu_get_plugin(): ATU_Auto_Update_Plugin {
	static $plugin = null;

	if ( null === $plugin ) {
		$plugin = new ATU_Auto_Update_Plugin();
	}

	return $plugin;
}

atu_get_plugin();
