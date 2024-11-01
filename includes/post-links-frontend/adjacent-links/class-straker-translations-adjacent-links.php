<?php
/**
 * Adjacent Links.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes/posts-links-forward/adjacent-links
 */

/**
 * Straker Translations Adjacent Links.
 *
 * This class get previous and next posts.
 *
 * @since      1.0.0
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 * @author     Straker Translations <extensions@strakertranslations.com>
 */
class Straker_Translations_Adjacent_Links {

	/**
	 *  Get Prev or Next Post
	 *
	 * @param string $join_clause Join Clause.
	 */
	public function get_previous_or_next_post_join( $join_clause ) {

		global $wpdb;
		$join_clause .= $wpdb->prepare(
			" JOIN {$wpdb->prefix}postmeta pm ON pm.post_id = p.ID AND pm.meta_key = %s",
			Straker_Translations_Config::straker_meta_locale
		);
		return $join_clause;

	}

	/**
	 *  Get Prev or Next Post
	 *
	 * @param string $where_clause Where Clause.
	 */
	public function get_previous_or_next_post_where( $where_clause ) {

		global $wpdb;

		$current_locale = Straker_Language::straker_language_locale( get_locale() );
		$lang_meta      = Straker_Language::straker_language_meta( Straker_Translations_Config::straker_wp_locale, $current_locale );

		$where_clause .= $wpdb->prepare( " AND meta_value = '%s'", $lang_meta['code'] );

		return $where_clause;

	}

}
