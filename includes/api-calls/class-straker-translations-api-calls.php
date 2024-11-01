<?php
/**
 * Make API calls
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */

/**
 * Make the API requests.
 *
 * This class defines api calls.
 *
 * @since      1.0.0
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 * @author     Straker Translations <extensions@strakertranslations.com>
 */
class Straker_Translations_API_Calls {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function api_get_request( $url, $body = array()) {

		$straker_access_token = '';
		$straker_application_key = '';
		$response = '';

		$straker_auth = get_option( Straker_Translations_Config::straker_option_auth );

		if ( $straker_auth !== false ) {
			$straker_access_token    = $straker_auth['access_token'];
			$straker_application_key = $straker_auth['application_key'];
		}

		if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
			$response = vip_safe_wp_remote_get(
				$url,
				array(
					'headers' => array(
						'Authorization' => 'Bearer ' . $straker_access_token,
						'X-Auth-App'    => $straker_application_key,
						'Content-Type'  => 'application/x-www-form-urlencoded',
					),
					'body'    => $body,
				)
			);
		} else {
			$response = wp_remote_get(  // @codingStandardsIgnoreLine -- for non-VIP environments
				$url,
				array(
					'headers' => array(
						'Authorization' => 'Bearer ' . $straker_access_token,
						'X-Auth-App'    => $straker_application_key,
						'Content-Type'  => 'application/x-www-form-urlencoded',
					),
					'body'    => $body,
				)
			);
		}
		return $response;
	}
}
