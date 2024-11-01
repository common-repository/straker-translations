<?php
/**
 * Straker Language.
 *
 * @since 3.1.0
 * @access private
 *
 * @package WordPress
 * @subpackage Straker_Language
 */

/**
 * The Straker Language Class.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Language {


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Straker Languages
	 *
	 * @var array $straker_languages  The Straker Languages.
	 */
	public static $straker_languages      = array();

	/**
	 * Straker Site Languages
	 *
	 * @var array $straker_site_languages  The Straker SIte Languages.
	 */
	public static $straker_site_languages = array();

	/**
	 * Straker Language Constructor
	 */
	public function __construct() {
			self::$straker_languages      = self::get_json();
			self::$straker_site_languages = get_option( Straker_Translations_Config::straker_option_languages );
	}

	/**
	 * Language Meta
	 *
	 * @param string $key Meta Key.
	 * @param string $value Meta Value.
	 */
	public static function straker_language_meta( $key, $value ) {

			$lang_meta = array();
			$lang_meta = self::search( self::get_json(), $key, $value );
			return $lang_meta;

	}

	/**
	 * Language Search.
	 *
	 * @param array  $array Languages Array.
	 * @param string $key Meta Key.
	 * @param string $value Meta Value.
	 */
	public static function search( $array, $key, $value ) {
			$results = array();

		if ( is_array( $array ) ) {
			if ( isset( $array[ $key ] ) && $array[ $key ] === $value ) {
				$results = $array;
			}

			foreach ( $array as $subarray ) {
					$results = array_merge( $results, self::search( $subarray, $key, $value ) );
			}
		}

			return $results;
	}

	/**
	 * Language WP Locale.
	 *
	 * @param array $added_langs Languages Array.
	 */
	public static function get_lang_wp_locale( $added_langs ) {
			$lang_meta = array();
			$langs     = $added_langs;

		foreach ( $langs as $value ) {
				$lang_array = array();
				$lang_array = self::search( self::$straker_languages, 'code', $value );

				array_push( $lang_meta, $lang_array );
		}
			return $lang_meta;
	}

	/**
	 * Get target Languages.
	 */
	public static function get_target_languages() {
			$lang_meta          = array();
			$array_diff_is       = array();
			$straker_added_langs = self::get_added_language();
			$straker_all_langs   = self::get_json();

		foreach ( $straker_all_langs as $data1 ) {
				$duplicate = false;
			foreach ( $straker_added_langs as $data2 ) {
				if ( $data1['native_name'] === $data2['native_name'] && $data1['name'] === $data2['name'] && $data1['code'] === $data2['code'] ) {
						$duplicate = true;
				}
			}

			if ( false === $duplicate ) {
					$lang_meta[] = $data1;
			}
		}

			$array_diff_is = $lang_meta;
			return $array_diff_is;
	}

	/**
	 * Get Site Languages.
	 */
	public static function get_site_languages() {
		return self::$straker_site_languages;
	}

	/**
	 * Get Default Languages.
	 */
	public static function get_default_language() {
			$lang_meta = array();
			$langs     = self::$straker_site_languages;

		if ( false === $langs ) {
			return $lang_meta;
		} else {
			$lang_meta = self::search( self::$straker_languages, 'code', $langs['sl'] );
			return $lang_meta;
		}

	}

	/**
	 * Get Added Language.
	 */
	public static function get_added_language() {
			$lang_meta = array();
			$langs     = self::$straker_site_languages;

		if ( false === $langs ) {
				return $lang_meta;
		} else {

				$target_langs_array = $langs['tl'];
			foreach ( $target_langs_array as $value ) {
					$lang_array = array();
					$lang_array = self::search( self::$straker_languages, 'code', $value );
					array_push( $lang_meta, $lang_array );
			}
				return $lang_meta;
		}

	}

	/**
	 * Get Default and Target Languages.
	 */
	public static function get_default_and_target_languages() {
			$lang_meta = array();
			$langs     = self::$straker_site_languages;

		if ( false === $langs ) {
				return $lang_meta;
		} else {
				array_push( $lang_meta, self::search( self::$straker_languages, 'code', $langs['sl'] ) );
				$target_langs_array = $langs['tl'];
			foreach ( $target_langs_array as $value ) {
					$lang_array = array();
					$lang_array = self::search( self::$straker_languages, 'code', $value );
					array_push( $lang_meta, $lang_array );
			}
				return $lang_meta;
		}
	}

	/**
	 * Get Language Shortcode.
	 *
	 * @param array  $lang_array Languages Array.
	 * @param string $key Meta Key.
	 */
	public static function get_shortcode( $lang_array, $key ) {

		$code_array = array();
		foreach ( $lang_array as $value ) {
			$shortcode = $value[ Straker_Translations_Config::straker_short_code ];
			array_push( $code_array, $shortcode );
		}

		return $code_array;

	}

	/**
	 * Get Signle Shortcode.
	 *
	 * @param array $lang Language.
	 */
	public static function get_single_shortcode( $lang ) {
			$lang_code = '';
		foreach ( self::get_default_and_target_languages() as $value ) {
			if ( $value['code'] === $lang ) {
				$lang_code = $value['wp_locale'];
			}
		}
			return $lang_code;
	}

	/**
	 * Get Languages JSON.
	 */
	public static function get_json() {
			$json              = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . '/includes/languages.json' );
			$body              = json_decode( $json, true );
			$straker_languages = $body['languages'];

			return $straker_languages;

	}

	/**
	 * Get API Languages JSON.
	 */
	public static function get() {
		if ( self::$straker_languages ) {
			return self::$straker_languages;
		}

		$response                = traker_Translations_API_Calls::api_get_request( Straker_Translations_Config::straker_api_url( 'languages' ) );
		$body                    = json_decode( $response['body'], true );
		self::$straker_languages = $body['languages'];

		return self::$straker_languages;

	}

	/**
	 * Return Language Shortcode.
	 */
	public static function shortcode_regex() {
			$added_language    = self::get_added_language();
			$straker_shortcode = self::get_shortcode( $added_language, 'code' );

		if ( empty( $straker_shortcode ) ) {
				return '';
		}

			return '(' . implode( '|', $straker_shortcode ) . ')';
	}

	/**
	 * Get Language Locale.
	 *
	 * @param string $locale Language Locale.
	 */
	public static function straker_language_locale( $locale ) {

		$lang_meta = self::straker_language_meta( Straker_Translations_Config::straker_wp_locale, $locale );

		if ( ! empty( $lang_meta ) ) {
			$find_locale = $lang_meta['wp_locale'];
			return $find_locale;
		} else {
			$default_lang_meta = self::get_default_language();
			return $default_lang_meta['wp_locale'];
		}
	}
}
