<?php
/**
 * Straker Tranlslations Cart.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */

/**
 * Straker Translations Cart handling Class.
 *
 * @since      1.0.0
 *
 * @author     Straker Translations <extensions@strakertranslations.com>
 */
class Straker_Translations_Cart_Handling {

	/**
	 * The Option name of the Cart.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $cart_option_name    The name of the cart.
	 */
	private static $cart_option_name = Straker_Translations_Config::straker_option_translation_cart;

	/**
	 * Add translation Item into Cart.
	 *
	 * @param    array $ids    Posts IDs.
	 */
	public static function add_item_into_cart( $ids ) {

		$cart_option = get_option( self::$cart_option_name );
		if ( ! get_option( self::$cart_option_name ) ) {
			update_option( self::$cart_option_name, $ids );
			return true;
		} else {
			update_option( self::$cart_option_name, $cart_option . ',' . $ids );
			return true;

		}
	}

	/**
	 * Remove translations From Cart.
	 *
	 * @param    array $ids    Posts IDs.
	 */
	public static function remove_item_from_cart( $ids ) {

		if ( get_option( self::$cart_option_name ) ) {

			$cart_as_array = explode( ',', get_option( self::$cart_option_name ) );

			if ( false !== ( array_search( $ids, $cart_as_array, true ) ) ) {
				$key = array_search( $ids, $cart_as_array, true );
				unset( $cart_as_array[ $key ] );

				if ( count( $cart_as_array ) <= 0 ) {
					delete_option( self::$cart_option_name );
				} else {
					update_option( self::$cart_option_name, implode( ',', $cart_as_array ) );
				}
				return true;
			}
		}
	}

	/**
	 * Add translation Item into Cart.
	 *
	 * @param int    $post_id Post ID.
	 * @param array  $target_langs Target Languages.
	 * @param array  $post_lang    Post Language.
	 * @param string $post_type    Post Type.
	 * @param array  $translation_cart    Translation Cart.
	 * @param string $text_domain    Text Domain.
	 */
	public static function translate_item_langs( $post_id, $target_langs, $post_lang, $post_type, $translation_cart, $text_domain ) {

		$langs_not_translated = array_diff( $target_langs, $post_lang );
		$langs_cb             = '';
		$user                 = wp_get_current_user();
		$capability           = 'manage_options';
		if ( false !== $translation_cart && in_array( (string)$post_id, $translation_cart, true ) && $user->has_cap( $capability ) ) {
			$post_type_uc = ucfirst( $post_type );
			$langs_cb = sprintf(
				'<div class="st-cart-img"><a href="%s" target="_blank"><span st-data-tooltip title="%s"><img class="st-cart-img" src="%s" /></span></a></div>',
				admin_url( 'admin.php?page=st-translation-cart' ),
				__( $post_type_uc. ' already in the translation cart.', 'straker-translations' ),
				STRAKER_PLUGIN_ABSOLUTE_PATH . '/admin/img/st-cart.png'
			);

			return $langs_cb;

		} elseif ( is_array( $langs_not_translated ) && count( $langs_not_translated ) > 0 && $target_langs !== $post_lang && $user->has_cap( $capability ) ) {
			 /* translators: %s: post type */
			$transled_text = sprintf( __( 'Translate this %s', 'straker-translations' ), ucfirst( $post_type ) );
			$langs_cb     .= sprintf(
				'<br /><div class="st_error_msg"><span style="color:red;" class="dashicons dashicons-no"></span> Item can\'t be add into the translation cart. Please try again.</div><div class="st_success_msg"><span style="color:green;" class="dashicons dashicons-yes"></span> Item added into the <a href="%s" target="_blank">cart.</a></div><span class="st-cart-update"><button class="button button-primary" id="st-cart-btn">%s</button><input id="stCartPostID" type="hidden" value="%s" /><img src="%s" /></span>',
				admin_url( 'admin.php?page=st-translation-cart' ),
				$transled_text,
				$post_id,
				STRAKER_PLUGIN_ABSOLUTE_PATH . '/admin/img/loading.gif'
			);

			return $langs_cb;

		} else {
			return $langs_cb;
		}
	}
}
