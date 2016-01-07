<?php
/*
 * Plugin Name: Auto Update
 * Plugin URI: https://stylishwp.com
 * Description: Plugin to enable auto update for WordPress core, themes and plugins.
 * Text Domain: auto-update
 * Version: 1.0.0
 * Author: Valeriu Tihai
 * Author URI: http://valeriu.tihai.ca
 * Contributors: valeriutihai
 * Donate link: https://paypal.me/valeriu/5
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Enable major version updates
add_filter( 'allow_major_auto_core_updates', '__return_true', 1 );

// Enable minor version updates
add_filter( 'allow_minor_auto_core_updates', '__return_true', 1 );

//Enable Plugin updates
add_filter( 'auto_update_plugin', '__return_true', 1 );

//Enable Themes updates
add_filter( 'auto_update_theme', '__return_true', 1 );

?>