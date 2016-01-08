<?php
/*
 * Plugin Name: Auto Update
 * Plugin URI: https://stylishwp.com
 * Description: This plugin enable Automatic Background Updates for WordPress core, Themes and Plugins.
 * Text Domain: auto-update
 * Domain Path: /languages
 * Version: 1.0.1
 * Author: Valeriu Tihai
 * Author URI: http://valeriu.tihai.ca
 * Contributors: valeriutihai
 * Donate link: https://paypal.me/valeriu/5
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Main Class - AutoUpdate
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

		// Enable Update filters
		add_action( 'plugins_loaded', array( &$this, 'autoupdate_filters' ), 1 );

		// Load language file
		add_action( 'plugins_loaded', array( &$this, 'autoupdate_load_textdomain' ), 2 );

		// Add Support Link
		add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), array( &$this, 'autoupdate_links' ), 1 );

	}

	/**
	 * Update function
	 *
	 * Update all themes, plugins and WordPress core (minor and major version)
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function autoupdate_filters() {
		// Enable major version updates
		add_filter( 'allow_major_auto_core_updates', '__return_true', 1 );

		// Enable minor version updates
		add_filter( 'allow_minor_auto_core_updates', '__return_true', 1 );

		//Enable Plugin updates
		add_filter( 'auto_update_plugin', '__return_true', 1 );

		//Enable Themes updates
		add_filter( 'auto_update_theme', '__return_true', 1 );
	}

	/**
	 * Load language file
	 *
	 * This will load the MO file for the current locale.
	 * The translation file must be named auto-update-$locale.mo.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function autoupdate_load_textdomain() {
		load_plugin_textdomain( 'auto-update', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
	}

	/**
	 * Add settings links to plugin page
	 *
	 * @since 1.0.0
	 * @access  public
	 */
	public function autoupdate_links( $links ) {
		$links[] = '<a href="https://wordpress.org/support/plugin/auto-update" target="_blank">'.__("Support", 'auto-update').'</a>';
		return $links;
	}

}
// Instantiate the main class
 new AutoUpdate();
?>