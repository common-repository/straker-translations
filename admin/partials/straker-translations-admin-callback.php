<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Language_Translation
 * @subpackage Straker_Language_Translation/admin/partials
 */

?>

<?php
	$auth_token = filter_input( INPUT_POST, 'auth_token', FILTER_SANITIZE_STRING );
	$api_sig    = $this->straker_api_signature();
	$p          = '';
if ( filter_has_var(INPUT_POST, "p" ) ) {
	$p = '&p=' . filter_input( INPUT_POST, 'p', FILTER_SANITIZE_STRING );
}
	$myaccount_auth = $this->straker_api( 'myaccount/authorize' ) . '?api_sig=' . $api_sig . '&auth_token=' . $auth_token . $p;
	wp_redirect( $myaccount_auth, $status_code = 302 );
	exit;

