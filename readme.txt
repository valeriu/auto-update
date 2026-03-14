=== Auto Update ===
Contributors: valeriutihai
Tags: automatic updates, background updates, core updates, plugin updates, theme updates
Requires at least: 5.8
Requires PHP: 7.4
Tested up to: 6.9
Stable tag: 1.1.0
Donate link: https://paypal.me/valeriu/5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds a settings page for managing automatic updates for WordPress core, plugins, and themes.

== Description ==
Auto Update is built for site owners who want a simple way to manage WordPress automatic updates from one place.

When the plugin is activated, automatic updates for WordPress core, plugins, and themes remain enabled by default, just like in the previous version.

You can choose automatic updates for:

* Minor WordPress core releases
* Major WordPress core releases
* Plugins
* Themes

This makes it easier to reduce manual maintenance while still keeping control over how updates are enabled on the site.

Checked options are forced on by the plugin. Unchecked options are forced off.

== Installation ==
In most cases you can install automatically from WordPress.org.

However, if you install this manually, follow these steps:
1. Create the directory 'auto-update' in your '/wp-content/plugins/' directory
2. Upload all plugin files to the newly created directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to 'Settings > Auto Update' and save your preferred update options

== Screenshots ==
None so far.

== Frequently Asked Questions ==
How do I enable automatic updates?

Activate the plugin, go to 'Settings > Auto Update', choose the update types you want, and save the settings.

== Changelog ==

= 1.1.0 =
* Added: settings page for managing automatic updates
* Added: separate options for minor core, major core, plugin, and theme updates
* Added: uninstall cleanup for plugin settings
* Changed: automatic updates remain enabled by default and can now be customized from the settings page
* Changed: checked options now force updates on and unchecked options force them off
* Changed: refactored the plugin bootstrap into a standard includes-based structure

= 1.0.2 =
* Updated: tested up to WordPress 6.9
* Updated: minimum supported PHP version is 7.4
* Changed: removed the manual translation loading call and now rely on WordPress.org language loading

= 1.0.1 =
* Fixed: translation text domain

= 1.0.0 =
* First Release
