<?php
/**
 * Provide an metabox in posts and pages.
 *
 * This file is used to show the metabox.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Language_Translation
 * @subpackage Straker_Language_Translation/admin/partials
 */

?>
  <div class="wrap">
	<?php
	$post_meta           = get_post_custom( $post->ID );
	$screen              = get_current_screen();
	$post_meta_locale    = Straker_Translations_Config::straker_meta_locale;
	$post_straker_target = ( get_post_meta( $post->ID, Straker_Translations_Config::straker_meta_target ) ) ? explode( ',', get_post_meta( $post->ID, Straker_Translations_Config::straker_meta_target, true ) ) : array();
	$translation_cart    = ( false !== get_option( Straker_Translations_Config::straker_option_translation_cart ) ) ? explode( ',', get_option( Straker_Translations_Config::straker_option_translation_cart ) ) : false;
	$default_language    = $this->straker_default_language['code'];
	$post_language       = isset( $post_meta[ $post_meta_locale ] ) ? esc_attr( $post_meta[ $post_meta_locale ][0] ) : false;
	$post_meta_default   = Straker_Translations_Config::straker_meta_default . '' . Straker_Language::get_single_shortcode( $post_language );
	$source_post_id      = isset( $post_meta[ $post_meta_default ] ) ? esc_attr( $post_meta[ $post_meta_default ][0] ) : false;
	$source_post_meta    = get_post_meta( $source_post_id, $post_meta_locale, true );
	$translated_posts    = Straker_Util::get_meta_by_value( $post->ID );
	$get_post_type       = get_post_type( $post->ID );
	$lang_meta           = Straker_Language::straker_language_meta( 'code', $post_language );
	$allowed_html_tags   = array(
		'span' => array(
			'class' => array(),
			'style' => array(),
			'title' => array(),
			'st-data-tooltip' => array()
		),
		'a' => array(
			'class' => array(),
			'href'  => array(),
			'rel'   => array(),
			'title' => array(),
			'target' => array()
		),
		'button' => array(
			'class' => array(),
			'id' => array()
		),
		'div' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'img' => array(
			'style'  => array(),
			'src'    => array(),
			'class'	=> array()
		),
		'input' => array(
			'class' => array(),
			'id' => array(),
			'type' => array(),
			'value' => array()
		),
		'p' => array(
			'class' => array(),
			'style' => array()
		),
		'strong' => array(),
		'ul' => array(
			'class' => array(),
		),
	);
	// Nonce to verify intention later.
	wp_nonce_field( 'straker_save_lang_meta', 'straker_save_lang_meta_nonce' );

	if ( 'add' !== $screen->action && ! empty( $post_meta[ $post_meta_locale ][0] ) ) {

		printf(
			'<p class="st-tl-post-lang"><label for="post-lang">%s<label for="post-lang"><img src="%s" /><span> %s</span></p>',
			esc_html( __( 'Language: ', 'straker-translations' ) ),
			esc_url( $this->flags_path ) . esc_html( $post_language ) . '.png',
			esc_html( $lang_meta['name'] )
		);

		if ( $source_post_id ) {

			printf( '<p class="st-tl-post-lang"> <label for="post-lang">%s</label>', esc_html( __( 'Original: ', 'straker-translations' ) ) );

			if ( $source_post_meta ) {

				$source_lang_name = Straker_Language::straker_language_meta( 'code', $source_post_meta );
				printf(
					'<img src="%s"  st-data-tooltip title="%s" />&nbsp;',
					esc_url( $this->flags_path ) . esc_html( $source_post_meta ) . '.png',
					esc_html( $source_lang_name['name'] )
				);

			}

			printf(
				'<a href="%s" target="_blank">%s</a></p>',
				esc_url( get_edit_post_link( $source_post_id ) ),
				esc_html( get_the_title( $source_post_id ) )
			);

		}

		if ( $translated_posts ) {

			$straker_translation_cart = ( get_option( Straker_Translations_Config::straker_option_translation_cart ) ) ? explode( ',', get_option( Straker_Translations_Config::straker_option_translation_cart ) ) : false;
			$non_translted_langs      = Straker_Translations_Cart_Handling::translate_item_langs( $post->ID, $this->straker_site_languages['tl'], $post_straker_target, $get_post_type, $straker_translation_cart, 'straker-translations' );

			if ( ! empty( $non_translted_langs ) ) {
				echo wp_kses( $non_translted_langs, $allowed_html_tags );
			}

			printf( '<p> <label for="post-lang">%s</label><br />',  wp_kses_post( __( '<strong> Translations</strong>:', 'straker-translations' ) ) );
			foreach ( $translated_posts as $value ) {
				printf(
					'<br /><img src="%s" st-data-tooltip title="%s" /> &nbsp;',
					esc_url( $this->flags_path ) . esc_html( $value['code'] )  . '.png',
					esc_html( $value['name'] )
				);
				printf(
					'<a style="position:relative;top:-7px;" href="%s" target="_blank">%s</a>',
					esc_url( get_edit_post_link( $value['post_id'] ) ),
					esc_html( get_the_title( $value['post_id'] ) )
				);
			}
			echo '</p>';
		} else {

			if ( ! $source_post_id && $post_language === $default_language ) {

				$straker_translation_cart = ( get_option( Straker_Translations_Config::straker_option_translation_cart ) ) ? explode( ',', get_option( Straker_Translations_Config::straker_option_translation_cart ) ) : false;
				$non_translted_langs      = Straker_Translations_Cart_Handling::translate_item_langs( $post->ID, $this->straker_site_languages['tl'], $post_straker_target, $get_post_type, $straker_translation_cart, 'straker-translations' );
				echo wp_kses( $non_translted_langs, $allowed_html_tags );

			}
		}
	} else {

		printf(
			'<p><label for="post-lang">%s</label> <select name="st_lang_select" id="st_lang_select">',
			esc_html( __( 'Language:', 'straker-translations' ) )
		);

		foreach ( $this->straker_def_targ_langs as $val ) {
			printf( '<option value="%s">%s</option>', esc_html( $val['code'] ), esc_html( $val['name'] ) );
		}

		printf(
			'</select><div class="error" id="st-message" style="display:none;"><p>%s</p></div></p>',
			esc_html( __( 'Please select the language of the page.', 'straker-translations' ) )
		);
	}
	echo '</div>';
