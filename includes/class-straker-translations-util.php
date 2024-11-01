<?php
/**
 * Straker Util
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */

/**
 * The Straker Utilities Class.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Util {

	/**
	 * Get Meta By Key Value.
	 *
	 * @param string $key Meta Key.
	 * @param string $value Meta Value.
	 */
	public static function get_meta_by_key_value( $key, $value ) {

		global $wpdb;
		// @codingStandardsIgnoreStart -- no WP API function available for this query
		$meta = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `$wpdb->postmeta` WHERE meta_key = %s AND meta_value = %s", $key, $value ) );
		// @codingStandardsIgnoreEnd

		if ( is_array( $meta ) && ! empty( $meta ) && isset( $meta[0] ) ) {
			$meta = $meta[0];
		}
		if ( is_object( $meta ) ) {
			return $meta->post_id;
		} else {
			return false;
		}
	}

	/**
	 * Get Meta By Value.
	 *
	 * @param string $value Value.
	 */
	public static function get_meta_by_value( $value ) {

		global $wpdb;
		$st_lang_def = Straker_Translations_Config::straker_meta_default;
		// @codingStandardsIgnoreStart
		$meta = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `$wpdb->postmeta` WHERE `meta_key` LIKE '%s' AND `meta_value` = %s", $st_lang_def.'%' , $value ) );
		// @codingStandardsIgnoreEnd
		if ( is_array( $meta ) && ! empty( $meta ) && isset( $meta[0] ) ) {
			$meta_array = array();
			foreach ( $meta as $value ) {
				$lang_array          = array();
				$lang_array['post_id']    = $value->post_id;
				$lang_array['default_id'] = $value->meta_value;
				$post_array               = get_post_meta( $value->post_id, Straker_Translations_Config::straker_meta_locale );
				$lang_meta            = Straker_Language::straker_language_meta( 'code', $post_array[0] );
				$lang_array['name']       = $lang_meta['name'];
				$lang_array['code']       = $post_array[0];
				array_push( $meta_array, $lang_array );
			}

			return $meta_array;
		} else {
			return false;
		}
	}

	/**
	 * Get Post Permalink.
	 *
	 * @param string $post_type Post Type.
	 */
	public static function get_post_permalink_structure( $post_type ) {
		$structure = '';
		if ( is_string( $post_type ) ) {
			$pt_object = get_post_type_object( $post_type );
		} else {
			$pt_object = $post_type;
		}
		if ( ! empty( $pt_object->rewrite['slug'] ) ) {
			$structure = $pt_object->rewrite['slug'];
		} else {
			$structure = $pt_object->name;
		}

		return $structure;
	}

	/**
	 * Get Post Front Date.
	 *
	 * @param string $post_type Post Type.
	 */
	public static function get_post_date_front( $post_type ) {
		$structure = self::get_post_permalink_structure( $post_type );
		$front     = '';
		preg_match_all( '/%.+?%/', $structure, $tokens );
		$tok_index = 1;
		foreach ( (array) $tokens[0] as $token ) {
			if ( '%post_id%' === $token && ( $tok_index <= 3 ) ) {
				$front = '/date';
				break;
			}
			++$tok_index;
		}
		return $front;
	}

	/**
	 * Get All Post Types.
	 */
	public static function get_all_post_types_names() {
		$args          = array(
			'public'   => true,
			'_builtin' => false,
		);
		$post_types    = get_post_types( $args, 'names' );
		$builtin_types = array(
			'post' => 'post',
			'page' => 'page',
		);
		return array_merge( $post_types, $builtin_types );
	}

	/**
	 * Get Translated Post Meta Meta.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $meta_default Meta Default.
	 */
	public static function get_translated_post_meta( $post_id, $meta_default ) {
		// @codingStandardsIgnoreStart
		$wp_query_args = array(
			'post_type'  => get_post_type( $post_id ),
			'meta_query' => array(
				array(
					'key'     => $meta_default,
					'value'   => $post_id,
					'compare' => '=',
				),
			),
		);
		// @codingStandardsIgnoreEnd
		$query         = new WP_Query( $wp_query_args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$id = get_the_ID();
				return $id;
			}
		}
		return false;
	}

	/**
	 * Get Lang Meta.
	 *
	 * @param array  $meta_array Meta Araay.
	 * @param string $lang_code Lang Code.
	 */
	public static function get_lang_meta_into_array( $meta_array, $lang_code ) {

		$return_array = array();
		if ( ! empty( $meta_array ) && is_array( $return_array ) ) {
			foreach ( $meta_array as $key ) {
				if ( $key['code'] === $lang_code ) {
					$return_array['source_id'] = $key['default_id'];
					$return_array['target_id'] = $key['post_id'];
				}
			}
			return $return_array;
		} else {
			return false;
		}
	}

}
