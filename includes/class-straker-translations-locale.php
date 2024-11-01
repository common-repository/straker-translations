<?php
/**
 * Straker Locale
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */

/**
 * The Straker Locale Class.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Locale {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var string The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var string The current version of this plugin.
	 */
	private $version;

	/**
	 * WP Query Variables
	 *
	 * @param string $plugin_name Plugin Name.
	 * @param string $version Plugin Version.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		global $straker_default;
		$straker_default = $this->default_locale();
	}

	/**
	 * Straker Locale
	 *
	 * @param string $locale Lang Locale.
	 */
	public function straker_locale( $locale ) {

		if ( is_admin() ) {
			return $locale;
		}

		// set locale by URL.
		$rewrite = Straker_Translations_Config::straker_rewrite_type();

		if ( 'domain' === $rewrite ) {
			$urls = get_option( Straker_Translations_Config::straker_option_urls );
			$urls = array_map( 'esc_url', $urls );

			$site_url = site_url();

			if ( in_array( $site_url, $urls, true ) ) {
				$lang   = array_search( $site_url, $urls, true );
				$locale = $this->wp_locale( $lang );
				return $locale;
			}
		}

		// set locale by Language code.
		if ( 'code' === $rewrite ) {
			$locale = $this->langage_code();
			if ( $locale ) {
				return $locale;
			}
		}

		$locale = $this->default_locale();
		return $locale;
	}

	/**
	 * WP Query Variables
	 *
	 * @param array $query_vars Query Arguments.
	 */
	public function straker_query_vars( $query_vars ) {
		$query_vars[] = 'lang';
		return $query_vars;
	}

	/**
	 * Default Locale
	 */
	public function default_locale() {
		if ( defined( 'WPLANG' ) ) {
			$locale = WPLANG;
		}

		if ( is_multisite() ) {
			$ms_locale = get_option( 'WPLANG' );
			if ( defined( 'WP_INSTALLING' ) || ( false === $ms_locale ) ) {
				$ms_locale = get_site_option( 'WPLANG' );
			}

			if ( false !== $ms_locale ) {
				$locale = $ms_locale;
			}
		} else {

			$db_locale = get_option( 'WPLANG' );
			if ( false !== $db_locale && ! empty( $db_locale ) ) {

				$default_lang = Straker_Language::get_default_language();
				if ( ! empty( $default_lang ) && $db_locale === $default_lang['wp_locale'] ) {
					$locale = $db_locale;
				}
			}
		}

		if ( empty( $locale ) ) {
			$locale = 'en_US';
		}

		return $locale;
	}

	/**
	 * WP Locale
	 *
	 * @param string $lang Language Name.
	 */
	public function wp_locale( $lang ) {
		$lang_meta = Straker_Language::straker_language_meta( 'code', $lang );
		$locale    = $lang_meta[ Straker_Translations_Config::straker_wp_locale ];
		return $locale;
	}

	/**
	 * Language Code
	 */
	public function langage_code() {
		$added_language    = Straker_Language::get_added_language();
		$straker_shortcode = Straker_Language::get_shortcode( $added_language, 'code' );

		$url = is_ssl() ? 'https://' : 'http://';
		$url .= filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);
		$url .= filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );

		$home  = set_url_scheme( get_option( 'home' ) );
		$home  = trailingslashit( $home );
		$regex = '#^' . preg_quote( $home ) . '(' . implode( '|', $straker_shortcode ) . ')/#';

		if ( preg_match( $regex, trailingslashit( $url ), $matches ) ) {
			$lang_meta = Straker_Language::straker_language_meta( 'short_code', $matches[1] );
			$locale    = $lang_meta[Straker_Translations_Config::straker_wp_locale];
			return $locale;
		}

		// for Permalink Settings : Plain.
		if ( wp_parse_url( $url, PHP_URL_QUERY ) ) {
			$query = wp_parse_url( $url, PHP_URL_QUERY );
			parse_str( $query, $query_vars );
		}

		if ( isset( $query_vars['lang'] ) && in_array( $query_vars['lang'], $straker_shortcode, true ) ) {
			$lang_meta = Straker_Language::straker_language_meta( 'short_code', $query_vars['lang'] );
			$locale    = $lang_meta[Straker_Translations_Config::straker_wp_locale];
			return $locale;
		}

		return false;
	}
}
