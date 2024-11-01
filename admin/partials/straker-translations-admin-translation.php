<?php
/**
 * Provide a admin area view for the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
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

		require_once( plugin_dir_path( __FILE__ ) . 'messages/sandbox-message.php');
		printf( '<h1>%s</h1>', esc_html( __( 'Translation Dashboard', 'straker-translations' ) ) );

	if ( false === $this->straker_auth ) {
		require_once( plugin_dir_path( __FILE__ ) . 'straker-translations-admin-register-button.php' );
	} else {

		require_once( plugin_dir_path( __FILE__ ) . 'messages/general-message.php');
		$default_lang = $this->straker_default_language;
		$added_langs  = $this->straker_added_language;
		$rewrite_opt  = Straker_Translations_Config::straker_rewrite_type();
		if ( ! empty( $_POST ) && check_admin_referer( 'straker-translations-order-form', 'straker-translations-order-form-nonce' ) ) {
			require_once( plugin_dir_path( __FILE__ ) .  'straker-translations-admin-select-all-order.php' );
		} else {
			$other_attributes = '';
			if ( ! $this->check_posts_have_locale() ) {
				$other_attributes = array(
					'disabled' => 'disabled',
					'style'    => 'background-color: #D3D3D3 !important; color: #000 !important; border-color: #D3D3D3 !important;',
				);
			}

			if ( empty( $default_lang ) && empty( $added_langs ) ) {
				wp_redirect( admin_url( 'admin.php?page=st-settings&tab=language_settings&ac=lang_setting&msg=failed' ) );
				exit();
			} elseif ( empty( $rewrite_opt ) ) {
				wp_redirect( admin_url( 'admin.php?page=st-settings&tab=url_settings&ac=url_setting&msg=failed' ) );
				exit();
			} else {
				$list_table = new Pages_List_Table( $this->plugin_name );
				$list_table->prepare_items();

				if ( Straker_Translations_Config::straker_sandbox_mode() !== 'true' ) {
					?>

				<form action="?page=st-translation" method="post" id="sp_all_trans_dash">
					<?php wp_nonce_field( 'straker-translations-order-form', 'straker-translations-order-form-nonce' ); ?>
					<span style="float: right;">
						<a href="#" class="st_show_hide"><?php echo esc_html( __( 'Select All Content', 'straker-translations' ) ); ?></a>
					</span>
					<input type="hidden" name="st_trans_all" value="yes"><br />
					<div class="st-sendAllDiv" style="display: none;">
						<span style="float: right;">
							<a href="#" class="st_show_hide"><?php esc_attr_e( 'Close', 'straker-translations' ); ?></a>
						</span><span style="font-weight: bold;"><?php esc_attr_e( 'Post Types ', 'straker-translations' ); ?></span><br /><br />
						<?php
						foreach ( $this->straker_posts_types as $value ) {
							$display_val = get_post_type_object( $value );
							echo '<input type="checkbox" name="post_types[]" value="' . esc_attr( $value ) . '" checked/><span style="margin-right: 15px;">' . esc_html( $display_val->label ) . '</span>';
						}
						?>
						<br /><br /><span style="font-weight: bold;">
						<?php
						esc_attr_e( 'Post Status ', 'straker-translations' );
						?>
						</span><br /><br />
						<?php
						foreach ( $this->straker_posts_status as $value ) {
							$publish_post = $value;
							if ( 'publish' === $value ) {
								$publish_post = 'Published';
							}
							echo '<input type="checkbox" name="post_status[]" value="' . esc_attr( $value ) . '" checked required/><span style="margin-right: 15px;">' . esc_html( ucwords( strtolower( $publish_post ) ) ) . '</span>';
						}

						?>
						<div class="st_order_all_btn">
							<?php submit_button( __( 'Select All Content', 'straker-translations' ), 'primary', 'submit-id', false, $other_attributes ); ?>
					</div>
					</div>
			</form>
			<?php } ?>
			<br />
			<form post="" method="get">
				<?php
					$list_table->search_box( __( 'Search' ), 'translate' );
					$s_term = isset( $_GET ) ? (array) $_GET : array();
					$s_term = array_map( 'esc_attr', $s_term );
				foreach ( $s_term as $key => $value ) {
					if ( 's' !== $key ) {
						echo "<input type='hidden' name='".esc_attr( $key )."' value='".esc_attr( $value )."' />";
					}
				}
				?>
			</form>
			<form action="?page=st-translation-cart" method="post" id="sp_trans_dash">

				<input type="hidden" name="page" value="ttest_list_table">
				<input type="hidden" name="st_multi_cart_ids" id="st_cart_ids">
				<div class="st-lang" style="display:none">
					<p id="tagline-description"></p>
				</div>
				<?php

					wp_nonce_field( 'straker-translations-cart-order-form', 'straker-translations-cart-order-form-nonce' );
					$list_table->display();
					submit_button( __( 'Next &raquo; Choose Languages', 'straker-translations' ), 'primary', 'stAddToCartBtn', true, $other_attributes );
				?>
			</form>
				<?php
			}
		}
	}
	?>
</div>
