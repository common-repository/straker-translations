<?php
/**
 * Edit the posts, pages categories multi-lingual links.
 *
 * @since 3.1.0
 * @access private
 *
 * @package WordPress
 * @subpackage Straker_link
 */

/**
 * The Straker Link Class.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Link {


	/**
	 * Straker Link constructor.
	 */
	public function __construct() {     }

	/**
	 * Edit the Post link for the multi-lingual.
	 *
	 * @param string $permalink Post permalink.
	 * @param string $post Post.
	 * @param string $leavename Leavename.
	 */
	public function straker_post_link( $permalink, $post, $leavename ) {

		$locale              = Straker_Language::straker_language_locale( get_locale() );
		$sample              = ( isset( $post->filter ) && 'sample' === $post->filter );
		$permalink_structure = get_option( 'permalink_structure' );

		$using_permalinks = $permalink_structure &&
			( $sample || ! in_array( $post->post_status, array( 'draft', 'pending', 'auto-draft' ), true ) );

		$permalink = $this->straker_shortcode_url( $permalink, $locale, array( 'using_permalinks' => $using_permalinks ) );

		return $permalink;

	}

	/**
	 * Edit the Home Page URL.
	 *
	 * @param string $url Page URL.
	 * @param string $locale Default Locale.
	 */
	public function straker_home_url( $url, $locale = '' ) {

		if ( is_admin() || ! did_action( 'template_redirect' ) ) {
			return $url;
		}

		if ( '' === $locale ) {
			$locale = Straker_Language::straker_language_locale( get_locale() );
		}

		$rewrite_option = Straker_Translations_Config::straker_rewrite_type();

		if ( $locale !== $GLOBALS['straker_default'] ) {
			if ( 'code' === $rewrite_option ) {
				$args = array( 'using_permalinks' => (bool) get_option( 'permalink_structure' ) );
				return $this->straker_shortcode_url( $url, $locale, $args );
			} elseif ( 'domain' === $rewrite_option ) {

				$straker_urls           = get_option( Straker_Translations_Config::straker_option_urls );
				$straker_added_language = Straker_Language::get_added_language();
				$code                   = '';

				foreach ( $straker_added_language as $value ) {
					if ( $value['wp_locale'] === $locale ) {
						$code = $value['code'];
					}
				}

				return $straker_urls[ $code ];
			} else {
				return $url;
			}
		} else {
			return $url;
		}

	}

	/**
	 * Edit the Pages URL's.
	 *
	 * @param string $permalink Page Permalink.
	 * @param int    $id Page ID.
	 * @param string $sample Sample.
	 */
	public function straker_page_link( $permalink, $id, $sample ) {

		$locale = Straker_Language::straker_language_locale( get_locale() );
		$post   = get_post( $id );

		$permalink_structure = get_option( 'permalink_structure' );

		$using_permalinks = $permalink_structure &&
			( $sample || ! in_array( $post->post_status, array( 'draft', 'pending', 'auto-draft' ), true ) );

		$permalink = $this->straker_shortcode_url( $permalink, $locale, array( 'using_permalinks' => $using_permalinks ) );

		return $permalink;
	}

	/**
	 * Edit the Shortcode URL's.
	 *
	 * @param string $url Page URL.
	 * @param string $locale Page ID.
	 * @param array  $args Arguments.
	 */
	public function straker_shortcode_url( $url = null, $locale = null, $args = '' ) {

		$defaults = array( 'using_permalinks' => true );

		$args = wp_parse_args( $args, $defaults );

		if ( ! $url ) {
			$url  = is_ssl() ? 'https://' : 'http://';
			$url .= filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);
			$url .= filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );

			if ( strstr( $url, '#' ) ) {
				$frag = strstr( $url, '#' );
				$url = substr( $url, 0, -strlen( $frag ) );
			}

			if ( wp_parse_url( $url, PHP_URL_QUERY ) ) {
				$query = wp_parse_url( $url, PHP_URL_QUERY );
				parse_str( $query, $query_vars );

				foreach ( array_keys( $query_vars ) as $qv ) {
					if ( ! get_query_var( $qv ) ) {
						$url = remove_query_arg( $qv, $url );
					}
				}
			}
		}

		$home = set_url_scheme( get_option( 'home' ) );
		$home = trailingslashit( $home );

		$lang_meta = Straker_Language::straker_language_meta( Straker_Translations_Config::straker_wp_locale, $locale );
		$url       = remove_query_arg( 'lang', $url );

		if ( ! $args['using_permalinks'] ) {
			$url = add_query_arg( array( 'lang' => $lang_meta['short_code'] ), $url );
			return $url;
		}

		$regex = Straker_Language::shortcode_regex();
		$url   = preg_replace( '#^' . preg_quote( $home ) . '(' . $regex . '/)?#', $home . $lang_meta['short_code'] . '/', trailingslashit( $url ) );
		return $url;

	}

	/**
	 * Get the Home page URL.
	 */
	public function straker_default_home() {
		$url = get_option( 'home' );
		return $url;

	}

	/**
	 * Edit the Hpme URL.
	 *
	 * @param string $wp_locale WP Locale.
	 */
	public function straker_locale_home( $wp_locale ) {

		$url = home_url();
		$locale = $wp_locale;
		$url = $this->straker_home_url( $url, $locale );
		return $url;

	}

}
