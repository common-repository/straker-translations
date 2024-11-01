<?php
/**
 * Straker Nav Menu
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */

/**
 * The Straker Nav Menu Generation Class.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Nav_Menu {

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
	 * Set Plugin name and version
	 *
	 * @param array  $plugin_name Plugin Name.
	 * @param object $version Plugin Version.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Set Nav Menu objects.
	 *
	 * @param array  $items Menu Items.
	 * @param object $args Menu Arguments.
	 */
	public function set_nav_menu_objects( $items, $args ) {

		if ( is_admin() ) {
			return $items;
		}
		$args            = (object) $args;
		$menu_lang_items = array();
		$menu_lang_items = Straker_Translations_Menu_Switcher::generate_language_switcher_menu_item( $args, $items );
		if ( false !== $menu_lang_items ) {
			return $menu_lang_items;
		} else {
			return $items;
		}
	}

	/**
	 * Get Nav Menu Items.
	 *
	 * @param array $items Menu Items.
	 */
	public function get_nav_menu_items( $items ) {

		if ( is_admin() ) {
			return $items;
		}
		$locale       = Straker_Language::straker_language_locale( get_locale() );
		$lang_meta    = Straker_Language::straker_language_meta( Straker_Translations_Config::straker_wp_locale, $locale );
		$straker_lang = $lang_meta['code'];
		// Filter menu items for none default language.
		if ( $locale !== $GLOBALS['straker_default'] ) {
			foreach ( $items as $key => $item ) {
				if ( ! in_array( $straker_lang, $item->st_langs, true ) ) {
					unset( $items[ $key ] );
				}
			}
		} else {
			foreach ( $items as $key => $item ) {
				if ( ! in_array( $straker_lang, $item->st_langs, true ) ) {
					unset( $items[ $key ] );
				}
			}
		}

		return $items;
	}

	/**
	 * Set Nav Menu Item.
	 *
	 * @param array $menu_item Menu Items.
	 */
	public function setup_nav_menu_item( $menu_item ) {
		$menu_item->st_langs = array();
		$straker_lang = [];
		if ( isset( $menu_item->post_type ) && 'nav_menu_item' === $menu_item->post_type ) {

			$straker_default_lang = Straker_Language::get_default_language();
			$straker_lang[0]      = $straker_default_lang['code'];
			$post_meta            = get_post_meta( $menu_item->ID, Straker_Translations_Config::straker_meta_locale );
			$menu_item->st_langs  = ! empty( $post_meta ) ? $post_meta : $straker_lang;

		}

		return $menu_item;
	}
}
