<?php
/**
 * Menu Switcher Class
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */

/**
 * The Menu Switcher Class.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Translations_Menu_Switcher {

	/**
	 * Private static variable for option table
	 *
	 * @var string
	 */
	private static $switcher_option_name = Straker_Translations_Config::straker_option_menu_switcher;

	/**
	 * Save Langauge Switcher Settings
	 *
	 * This ajax based method save the menu language swither settings in the database option.
	 *
	 * @since    1.0.0
	 */
	public static function save_switcher_settings() {

		check_ajax_referer( 'st-lang-switcher-nonce', 'st_lang_switcher_nonce' );
		$menu_status = filter_has_var( INPUT_GET, 'menu_switcher_status')  ? filter_input( INPUT_GET, "menu_switcher_status", FILTER_SANITIZE_STRING) : false;
		$switcher_menu =filter_has_var( INPUT_GET, 'lang_switcher_menu')  ? filter_input( INPUT_GET, "lang_switcher_menu", FILTER_SANITIZE_STRING) : false;
		$item_postion = filter_has_var( INPUT_GET, 'position_of_item')  ? filter_input( INPUT_GET, "position_of_item", FILTER_SANITIZE_STRING) : false;
		$switcher_style = filter_has_var( INPUT_GET, 'menu_style')  ? filter_input( INPUT_GET, "menu_style", FILTER_SANITIZE_STRING) : false;
		$display_flags = filter_has_var( INPUT_GET, 'display_flags')  ? filter_input( INPUT_GET, "display_flags", FILTER_SANITIZE_STRING) : false;
		$display_language = filter_has_var( INPUT_GET, 'display_language')  ? filter_input( INPUT_GET, "display_language", FILTER_SANITIZE_STRING) : false;
		$st_language_switcher = self::$switcher_option_name;

		$st_lang_switcher_option_data = array(
			'status'           => $menu_status,
			'switcher_menu'    => $switcher_menu,
			'item_postion'     => $item_postion,
			'switcher_style'   => $switcher_style,
			'display_flags'    => $display_flags,
			'display_language' => $display_language,
		);

		if ( ! get_option( $st_language_switcher ) ) {
			add_option( $st_language_switcher, $st_lang_switcher_option_data );
			wp_send_json_success( array( 'isResponse' => true ) );
			wp_die();
		} else {
			update_option( $st_language_switcher, $st_lang_switcher_option_data );
			wp_send_json_success( array( 'isResponse' => true ) );
			wp_die();
		}
	}

	/**
	 * Generate Language Switcher Menu Item.
	 *
	 * @param string $menu_args Menu Argusments Settings.
	 * @param array  $items Menu items.
	 */
	public static function generate_language_switcher_menu_item( $menu_args, $items ) {

		$menu_response = self::compare_menu_id( $menu_args );
		$swither_settings = self::menu_swither_option();


		if ( $swither_settings['status'] && true === (boolean)$menu_response['response'] ) {

			$straker_languages = Straker_Language::get_default_and_target_languages();
			$straker_default_language = Straker_Language::get_default_language();
			$st_link  = new Straker_Link();
			$lang_url = '';
			$menu_item_list = [];

			foreach ( $straker_languages as $key ) {

				if ( $key['code'] === $straker_default_language['code'] ) {
					$lang_url = esc_url( $st_link->straker_default_home() );
				} else {
					$lang_url = esc_url( $st_link->straker_locale_home( $key['wp_locale'] ) );
				}
				$menu_item_list[] = new Straker_Translations_Menu_List_Item( $swither_settings, $key['code'], $key['native_name'], $lang_url, $menu_response['menu_classes'] );
			}
			if ( 'first' === $swither_settings['item_postion'] ) {
				return array_merge( $menu_item_list, $items );
			} else {
				return array_merge( $items, $menu_item_list );
			}
		} else {
			return false;
		}
	}

	/**
	 * Compare Menu ID.
	 *
	 * @param string $menu Menu.
	 * @return array
	 */
	private static function compare_menu_id( $menu ) {

		$menu         = (object) $menu;
		$menu_id      = array();
		$response     = 0;
		$menu_classes = '';

		if ( isset( $menu->menu ) ) {
			if ( is_object( $menu->menu ) && isset( $menu->menu->term_id ) ) {
				$menu_id = $menu->menu->term_id;
			} elseif ( ! is_object( $menu->menu ) ) {
				$menu_id = $menu->menu;
			}
			$menu_classes = $menu->menu_class;
		}
		if ( self::menu_swither_option() ) {

			$swither_settings = self::menu_swither_option();

			if ( (int)$swither_settings['switcher_menu'] === $menu_id ) {
				return array(
					'response'     => 1,
					'menu_classes' => $menu_classes,
				);
			}
		}
		return array( 'response' => $response );
	}

	/**
	 * Menu Switcher Option.
	 *
	 * @return bool
	 */
	private static function menu_swither_option() {

		if ( get_option( self::$switcher_option_name ) ) {
			return get_option( self::$switcher_option_name );
		} else {
			return false;
		}
	}

}
