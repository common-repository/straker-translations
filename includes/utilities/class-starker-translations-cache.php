<?php
/**
 * Straker Cache.
 *
 * @since 3.1.0
 * @access private
 *
 * @package WordPress
 * @subpackage Straker_link
 */

/**
 * The Straker Cache Class.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Translations_Cache {

	/**
	 * Cache Hroup name .
	 *
	 * @var      string    $cache_group_name    Cache Group name.
	 */
	private $cache_group_name;

	/**
	 * Class Constructor.
	 *
	 * @param    string $cahce_group    Language.
	 */
	public function __construct( $cahce_group = '' ) {
		$this->cache_group_name = $cahce_group;
	}

	/**
	 * Get the Cache.
	 *
	 * @param    string $cache_key    Cache Key.
	 * @param    bool   $is_cache_found    Check If cache available.
	 */
	public function get_cache( $cache_key, &$is_cache_found ) {

		$cahce_value = wp_cache_get( $this->get_cache_key( $cache_key ), $this->cache_group_name );

		if ( is_array( $cahce_value ) && array_key_exists( 'data', $cahce_value ) ) {

			$is_cache_found = true;

			return $cahce_value['data'];

		} else {

			$is_cache_found = false;
			return $cahce_value;

		}
	}

	/**
	 * Set the Cache.
	 *
	 * @param    string $key    Cache Key.
	 * @param    array  $data    Cache Datae.
	 * @param    int    $expire    Option Type.
	 */
	public function set_cache( $key, $data ) {
		wp_cache_set( $this->get_cache_key( $key ), array( 'data' => $data ), $this->cache_group_name, 300 );
	}

	/**
	 * Get Cache Key.
	 *
	 * @param    string $key    Cache Key.
	 */
	public function get_cache_key( $key ) {

		$current_cache_index = wp_cache_get( 'current_key_index', $this->cache_group_name );

		if ( false === $current_cache_index ) {

			$current_cache_index = 1;
			wp_cache_set( 'current_key_index', $current_cache_index, $this->cache_group_name );

		}

		return $key . $current_cache_index;
	}
}
