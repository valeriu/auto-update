<?php
/**
 * Plugin Name: Auto Update
 * Plugin URI: https://stylishwp.com
 * Description: Keeps WordPress core, plugins, and themes updated automatically to reduce manual maintenance and improve security.
 * Text Domain: auto-update
 * Domain Path: /languages
 * Version: 1.0.2
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

defined( 'ABSPATH' ) || die( 'Plugin file cannot be accessed directly.' );

// No direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Main Class - AutoUpdate
 *
 * @package Auto_Update
 *
 * @since 1.0.0
 */
class AutoUpdate {


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Enable update filters.
		add_action( 'plugins_loaded', array( &$this, 'autoupdate_filters' ), 1 );

		// Add the support link.
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( &$this, 'autoupdate_links' ), 1 );
	}

	/**
	 * Update function
	 *
	 * Update all themes, plugins and WordPress core (minor and major version).
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function autoupdate_filters() {
		// Enable major version updates.
		add_filter( 'allow_major_auto_core_updates', '__return_true', 1 );

		// Enable minor version updates.
		add_filter( 'allow_minor_auto_core_updates', '__return_true', 1 );

		// Enable plugin updates.
		add_filter( 'auto_update_plugin', '__return_true', 1 );

		// Enable theme updates.
		add_filter( 'auto_update_theme', '__return_true', 1 );
	}

	/**
	 * Add settings links to plugin page.
	 *
	 * @param array $links The plugin action links.
	 *
	 * @since 1.0.0
	 * @access  public
	 *
	 * @return array
	 */
	public function autoupdate_links( $links ) {
		$links[] = '<a href="https://wordpress.org/support/plugin/auto-update" target="_blank">' . __( 'Support', 'auto-update' ) . '</a>';
		return $links;
	}
}
// Instantiate the main class.
new AutoUpdate();
