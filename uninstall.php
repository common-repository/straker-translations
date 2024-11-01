<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 */

// If uninstall is not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

/**
 * Remove the Straker Plugin data from the Options and Postmeta tables.
 *
 * @return void
 */
function uninstall_straker_translations() {

	global $wpdb;
	// @codingStandardsIgnoreLine -- Straker Plugin adds too many options
	$wpdb->query( "DELETE FROM $wpdb->options WHERE `option_name` LIKE 'straker_%';" );
	// @codingStandardsIgnoreLine -- Straker Plugin adds unlimited posts meta fields
	$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE `meta_key` LIKE 'straker_%';" );

}

uninstall_straker_translations();
