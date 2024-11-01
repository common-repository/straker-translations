<?php
/**
 * MyClass File Doc Comment
 *
 * @category Straker_Translations
 * @package  Straker_Translations
 * @link     https://www.strakertranslations.com/
 */

/**
 * The file that defines the custom function of plugin
 *
 * @package    Straker_Translations
 */
function straker_translation_link( $link ) {
	$locale = Straker_Language::straker_language_locale( get_locale() );
	if ( $locale === $GLOBALS['straker_default'] ) {
		return $link;
	} else {
		$postid           = url_to_postid( $link );
		$key              = Straker_Translations_Config::straker_meta_default . $locale;
		$translated       = Straker_Util::get_meta_by_key_value( $key, $postid );
		$translation_link = get_permalink( $translated );
		return $translation_link;
	}
}

/**
 * The file that defines the custom function of plugin
 *
 * @package    Straker_Translations
 */
function is_straker_frontpage() {
	$locale       = Straker_Language::straker_language_locale( get_locale() );
	$locale_home  = home_url();
	$frontpage_id = get_option( 'page_on_front' );
	$key          = Straker_Translations_Config::straker_meta_default . $locale;
	$redirect     = Straker_Util::get_meta_by_key_value( $key, $frontpage_id );
	if ( $redirect ) {
		return true;
	}
	return false;
}
