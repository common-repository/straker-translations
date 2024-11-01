<?php
/**
 * Straker Short Code
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */

/**
 * The Straker Short Code Generation Class.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Short_Code {

	/**
	 * Constructor Add the Short Code.
	 */
	public function __construct() {
		add_shortcode( 'straker_translations', array( $this, 'straker_language_switcher_shortcode' ) );
	}

	/**
	 * Straker Language Switcher ShortCode.
	 *
	 * @param array $atts Attributes.
	 */
	public function straker_language_switcher_shortcode( $atts ) {
		$this->straker_default_language = Straker_Language::get_default_language();
		$this->straker_added_language   = Straker_Language::get_added_language();
		$this->st_link                  = new Straker_Link();

		$lang_switcher_sc = shortcode_atts(
			array(
				'languages'        => '',
				'display_flag'     => 'off',
				'display_language' => 'off',
				'horizontal'       => 'off',
			),
			$atts
		);

		$available_langs = explode( ',', $lang_switcher_sc['languages'] );

		$list = '<ul style="list-style: none;"';
		if ( $lang_switcher_sc['horizontal'] && 'on' === $lang_switcher_sc['horizontal'] ) {
			$list .= " id='langlist'";
		}
		$list .= '>';
		if ( in_array( $this->straker_default_language['code'], $available_langs, true ) ) {
			$list .= "<li><a href='" . esc_url( $this->st_link->straker_default_home() ) . "'>";
			if ( $lang_switcher_sc['display_flag'] && 'on' === $lang_switcher_sc['display_flag'] ) {
				$list .= "<img src='" . STRAKER_PLUGIN_ABSOLUTE_PATH . '/assets/img/flags/' . $this->straker_default_language['code'] . ".png' alt='" . $this->straker_default_language['native_name'] . "' style='vertical-align: text-top;' /> ";
			}
			if ( $lang_switcher_sc['display_language'] && 'on' === $lang_switcher_sc['display_language'] ) {
				$list .= $this->straker_default_language['native_name'];
			}
			$list .= '</a></li>';
		}
		foreach ( $this->straker_added_language as $value ) {
			if ( in_array( $value['code'], $available_langs, true ) ) {
				$list .= "<li><a href='" . esc_url( $this->st_link->straker_locale_home( $value['wp_locale'] ) ) . "'>";
				if ( 'on' === $lang_switcher_sc['display_flag'] ) {
					$list .= "<img src='" . STRAKER_PLUGIN_ABSOLUTE_PATH . '/assets/img/flags/' . $value['code'] . ".png' alt='" . $this->straker_default_language['native_name'] . "' style='vertical-align: text-top;' /> ";
				}
				if ( 'on' === $lang_switcher_sc['display_language'] ) {
					$list .= $value['native_name'];
				}
				$list .= '</a></li>';
			}
		}
		$list .= '</ul>';
		return $list;
	}

}
