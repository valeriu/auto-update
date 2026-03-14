<?php
/**
 * Uninstall the Auto Update plugin.
 *
 * @package Auto_Update
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( is_multisite() ) {
	$site_ids = get_sites(
		array(
			'fields' => 'ids',
			'number' => 0,
		)
	);

	foreach ( $site_ids as $site_id ) {
		switch_to_blog( $site_id );
		delete_option( 'atu_auto_update_settings' );
		restore_current_blog();
	}
} else {
	delete_option( 'atu_auto_update_settings' );
}
