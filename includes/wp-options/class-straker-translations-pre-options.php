<?php
/**
 * Straker Pre options.
 *
 * @since 3.1.0
 * @access private
 *
 * @package WordPress
 * @subpackage Straker_link
 */

/**
 * The Straker Pre Options Class.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Translations_Pre_Options {

	/**
	 * Straker Cache Group .
	 *
	 * @var      string    $cache_group    Cache.
	 */
	private static $cache_group = 'straker_translations_pre_options';

	/**
	 * Add translation Item into Cart.
	 *
	 * @param    string $lang    Language.
	 * @param    string $option_type    Option Type.
	 */
	public static function get_pre_options( $lang, $option_type ) {

		global $wpdb;

		$cache_key      = $option_type;
		$is_cache_found = false;

		$cache         = new Straker_Translations_Cache( self::$cache_group );
		$cache_results = $cache->get_cache( $cache_key, $is_cache_found );

		if ( ( ! $is_cache_found || ! isset( $cache_results[ $option_type ] ) ) ) {

			$cache_results[ $option_type ] = array();

			$post_id = $wpdb->get_var(  // @codingStandardsIgnoreLine -- get_option loads late so homepage not load properly
				$wpdb->prepare(
					"SELECT option_value FROM $wpdb->options WHERE option_name = %s",
					$option_type
				)
			);
			if ( ! empty( $post_id ) ) {

				$lang_meta    = Straker_Language::straker_language_meta( Straker_Translations_Config::straker_wp_locale, $lang );
				$target_posts = Straker_Util::get_meta_by_value( $post_id );

				if ( is_array( $target_posts )  ) {
					if ( count( $target_posts ) ) {
						foreach ( $target_posts as $val ) {
							if ( $val['default_id'] === $post_id && $val['code'] === $lang_meta['code'] ) {
								$cache_results[ $option_type ] = $val['post_id'];
							}
						}
					}
					$cache->set_cache( $cache_key, $cache_results );
				}
			}
		}
		return isset( $cache_results[ $option_type ] ) ? $cache_results[ $option_type ] : false;
	}
}
