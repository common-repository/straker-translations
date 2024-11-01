<?php
/**
 * Menu List Item Class
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */

/**
 * The Menu List Item Class.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Translations_Menu_List_Item {

	/**
	 * The term_id if the menu item represents a taxonomy term.
	 *
	 * @var int
	 */
	public $ID;

	/**
	 * The title attribute of the link element for this menu item.
	 *
	 * @var string
	 */
	public $attr_title;

	/**
	 * The array of class attribute values for the link element of this menu item.
	 *
	 * @var array
	 */
	public $classes = array();

	/**
	 * The DB ID of this item as a nav_menu_item object, if it exists (0 if it doesn't exist).
	 *
	 * @var int
	 */
	public $db_id;

	/**
	 * The description of this menu item.
	 *
	 * @var string
	 */
	public $description;

	/**
	 * The DB ID of the nav_menu_item that is this item's menu parent, if any. 0 otherwise.
	 *
	 * @var int
	 */
	public $menu_item_parent;

	/**
	 * The type of object originally represented, such as "category," "post", or "attachment."
	 *
	 * @var object
	 */
	public $object = 'st_ms_menu_item';

	/**
	 * The DB ID of the original object this menu item represents, e.g. ID for posts and term_id for categories.
	 *
	 * @var int
	 */
	public $object_id;

	/**
	 * The DB ID of the original object's parent object, if any (0 otherwise).
	 *
	 * @var int
	 */
	public $post_parent;

	/**
	 * A "no title" label if menu item represents a post that lacks a title.
	 *
	 * @var string
	 */
	public $post_title;

	/**
	 * A "no title" label if menu item represents a post that lacks a title.
	 *
	 * @var string
	 */
	public $post_name;

	/**
	 * The target attribute of the link element for this menu item.
	 *
	 * @var string
	 */
	public $target;

	/**
	 * The title of this menu item.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * The family of objects originally represented, such as "post_type" or "taxonomy."
	 *
	 * @var string
	 */
	public $type = 'st_ms_menu_item';

	/**
	 * The singular label used to describe this type of menu item.
	 *
	 * @var string
	 */
	public $type_label;

	/**
	 * The URL to which this menu item points.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * The XFN relationship expressed in the link of this menu item.
	 *
	 * @var link
	 */
	public $xfn;

	/**
	 * Whether the menu item represents an object that no longer exist
	 *
	 * @var boolean
	 */
	public $_invalid = false;

	/**
	 * For drop down to set this item as parent
	 *
	 * @var boolean
	 */
	public $is_parent;

	/**
	 * Menu Item Type
	 *
	 * @var string
	 */
	public $post_type = 'nav_menu_item';

	/**
	 * Class constructor.
	 *
	 * @param string $swither_settings Switcher Settings.
	 * @param string $lang_code Language Code.
	 * @param string $lang_name Language Name.
	 * @param string $lang_url Language URL.
	 * @param string $menu_classes Menu Classes.
	 */
	public function __construct( $swither_settings, $lang_code, $lang_name, $lang_url, $menu_classes ) {

		$this->create_menu_item_object( $swither_settings, $lang_code, $lang_name, $lang_url, $menu_classes );
	}

	/**
	 * Create the menu item object.
	 *
	 * @param string $swither_settings Switcher Settings.
	 * @param string $lang_code Language Code.
	 * @param string $lang_name Language Name.
	 * @param string $lang_url Language URL.
	 * @param string $menu_classes Menu Classes.
	 * @return void
	 */
	private function create_menu_item_object( $swither_settings, $lang_code, $lang_name, $lang_url, $menu_classes ) {

		$current_lang           = Straker_Language::straker_language_meta( Straker_Translations_Config::straker_wp_locale, Straker_Language::straker_language_locale( get_locale() ) );
		$if_default_lang        = ( isset( $current_lang['code'] ) && $current_lang['code'] === $lang_code ) ? true : false;
		$check_if_dropdown      = ( 'dropdown' === $swither_settings['switcher_style'] ) ? true : false;
		$parent_db_id           = ( $check_if_dropdown && $if_default_lang ) ? $swither_settings['switcher_menu'] . '-' . $current_lang['short_code'] : '';
		$display_lang_name      = ( $swither_settings['display_language'] ) ? $lang_name : '';
		$flag_img               = sprintf( '<img class="st-flag" src="%s" alt="%s" /> ', STRAKER_PLUGIN_ABSOLUTE_PATH . '/assets/img/flags/' . $lang_code . '.png', $lang_code );
		$display_flag           = ( $swither_settings['display_flags'] ) ? $flag_img : '';
		$this->ID               = isset( $swither_settings['switcher_menu'] ) ? $swither_settings['switcher_menu'] : null;
		$this->object_id        = isset( $swither_settings['switcher_menu'] ) ? $swither_settings['switcher_menu'] : null;
		$this->db_id            = ( $check_if_dropdown && $if_default_lang ) ? $parent_db_id : $swither_settings['switcher_menu'];
		$this->attr_title       = $lang_name;
		$this->title            = $display_flag . '<span class="st-lang-name">' . $display_lang_name . '</span>';
		$this->url              = $lang_url;
		$this->post_name        = $lang_code;
		$this->menu_item_parent = ( $check_if_dropdown && ! $if_default_lang && isset( $current_lang['short_code'] ) ) ? $swither_settings['switcher_menu'] . '-' . $current_lang['short_code'] : '';
		$this->is_parent        = ( $check_if_dropdown && $if_default_lang ) ? true : false;
		$this->classes          = ( $check_if_dropdown && $if_default_lang ) ? explode( ' ', 'menu-item menu-item-has-children' ) : explode( ' ', 'menu-item' );

	}

	/**
	 * Magic Method Get.
	 *
	 * @param string $property Propert.
	 */
	public function __get( $property ) {
		return isset( $this->{$property} ) ? $this->{$property} : null;
	}

}
