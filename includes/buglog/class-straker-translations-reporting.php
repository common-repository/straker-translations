<?php
/**
 * For the buglog reporting.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes/buglog
 */

/**
 * STraker Translations Buglog.
 *
 * This class  report errors to the buglog.
 *
 * @since      1.0.0
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 * @author     Straker Translations <extensions@strakertranslations.com>
 */
class Straker_Translations_Reporting {

	/**
	 * Return Buglog Server URL.
	 */
	private static function straker_buglog() {

		$constants  = get_defined_constants( true );
		$buglog_url = $constants['user']['STRAKER_BUGLOG'];
		return $buglog_url;
	}

	/**
	 * Generate Bug Log Report Body.
	 *
	 * @param string $msg Error Msg.
	 * @param string $e_message Error Msg.
	 * @param string $e_detail Languages Array.
	 * @param int    $error_code Error Code.
	 * @param string $error_file Error File.
	 * @param int    $error_line Error Line.
	 */
	public static function straker_bug_report( $msg, $e_message, $e_detail, $error_code, $error_file, $error_line ) {

		$straker_auth = get_option( Straker_Translations_Config::straker_option_auth );
		$acces_token  = '';
		if ( false !== $straker_auth ) {
			$acces_token = $straker_auth['access_token'];
		}

		$server_host	= filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);
		$server_req_uri	= filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );
		$server_usr_agt	= filter_input( INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING );

		wp_remote_post(
			self::straker_buglog(),
			array(
				'headers' => array( 'Content-Type' => 'application/x-www-form-urlencoded' ),
				'body'    => array(
					'APIKey'           => $acces_token,
					'applicationCode'  => 'WordPress Plugin',
					'HTMLReport'       => self::straker_buglog_html_report( $acces_token, $e_detail, $error_code, $error_file, $error_line ),
					'templatePath'     => $server_host . $server_req_uri,
					'message'          => $msg,
					'severityCode'     => 'ERROR',
					'exceptionMessage' => $e_message,
					'exceptionDetails' => $e_detail,
					'userAgent'        => $server_usr_agt,
					'dateTime'         => date( 'm/d/Y H:i:s' ),
					'hostName'         => $server_host,
				),
			)
		);
	}

	/**
	 * Generate HTML Bug Log Report.
	 *
	 * @param string $acces_token Access Token.
	 * @param string $error_msg Error Msg.
	 * @param int    $error_code Error Code.
	 * @param int    $file_name Error File.
	 * @param int    $line_no Error Line.
	 */
	private static function straker_buglog_html_report( $acces_token = '', $error_msg, $error_code = 0, $file_name, $line_no ) {


		$server_address	= filter_input( INPUT_SERVER, 'SERVER_ADDR', FILTER_SANITIZE_URL);
		$server_raddres	= filter_input( INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_URL );
		$server_name	= filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_STRING );
		$request_name	= filter_input( INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING );

		$array_error_msg = (array) $error_msg;

		$html_report  = '<h4>Exception Summary</h4>';
		$html_report .= '<table style="text-align: left;">';
		$html_report .= '<tr>';
		$html_report .= '<th>Server Address: </th>';
		$html_report .= '<td>' . $server_address . '</td>';
		$html_report .= '</tr>';
		$html_report .= '<tr>';
		$html_report .= '<th>Remote Address: </th>';
		$html_report .= '<td>' . $server_raddres . '</td>';
		$html_report .= '</tr>';
		$html_report .= '<tr>';
		$html_report .= '<th>Host Name: </th>';
		$html_report .= '<td>' . $server_name . '</td>';
		$html_report .= '</tr>';
		$html_report .= '<tr>';
		$html_report .= '<th>Request Method: </th>';
		$html_report .= '<td>' . $request_name . '</td>';
		$html_report .= '</tr>';
		$html_report .= '<tr>';
		$html_report .= '<th>Server Date/Time: </th>';
		$html_report .= '<td>' . current_time( 'mysql' ) . '</td>';
		$html_report .= '</tr>';
		$html_report .= '<tr>';
		$html_report .= '<th>Acess Token: </th>';
		$html_report .= '<td>' . $acces_token . '</td>';
		$html_report .= '</tr>';
		$html_report .= '<tr>';
		$html_report .= '<th>Plugin URL: </th>';
		$html_report .= '<td>' . STRAKER_PLUGIN_RELATIVE_PATH . '</td>';
		$html_report .= '</tr>';
		$html_report .= '<tr>';
		$html_report .= '<th>Error File: </th>';
		$html_report .= '<td>' . $file_name . '</td>';
		$html_report .= '</tr>';
		$html_report .= '<tr>';
		$html_report .= '<th>Error Line No.</th>';
		$html_report .= '<td>' . $line_no . '</td>';
		$html_report .= '</tr>';
		$html_report .= '<tr>';
		$html_report .= '<th>Site URL: </th>';
		$html_report .= '<td>' . get_site_url() . '</td>';
		$html_report .= '</tr>';
		$html_report .= '<tr>';
		$html_report .= '<th>Error Code: </th>';
		$html_report .= '<td>' . $error_code . '</td>';
		$html_report .= '</tr>';
		$html_report .= '<tr>';
		$html_report .= '<th>Error Message: </th>';
		$html_report .= '<td>' . wp_json_encode( $array_error_msg ) . '</td>';
		$html_report .= '</tr>';
		$html_report .= '</table>';

		return $html_report;
	}
}
