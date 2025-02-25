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

<?php
	$page_on_front             = get_option( 'page_on_front' );
	$page_for_posts            = get_option( 'page_for_posts' );
	$show_on_front             = get_option( 'show_on_front' );
	$nav_menus                 = wp_get_nav_menus();
	$menu_count                = count( $nav_menus );
	$menu_switcher_option_name = Straker_Translations_Config::straker_option_menu_switcher;
	$menu_switcher_settings    = ( get_option( $menu_switcher_option_name ) ) ? get_option( $menu_switcher_option_name ) : false;
	$menu_switcher_status      = ( $menu_switcher_settings['status'] && $menu_switcher_settings ) ? '' : 'style="display:none"';
	$front_page_target         = explode( ',', get_post_meta( $page_on_front, Straker_Translations_Config::straker_meta_target, true ) );
	$posts_page_target         = explode( ',', get_post_meta( $page_for_posts, Straker_Translations_Config::straker_meta_target, true ) );
	$default_lang              = $this->straker_default_language;
	$added_langs               = $this->straker_added_language;
	$rewrite_opt               = $this->st_rewrite_type;
if ( empty( $default_lang ) && empty( $added_langs ) ) {
	wp_redirect( admin_url( 'admin.php?page=st-settings&tab=language_settings&ac=lang_setting&msg=failed' ) );
	exit();
} elseif ( empty( $rewrite_opt ) ) {
	wp_redirect( admin_url( 'admin.php?page=st-settings&tab=url_settings&ac=url_setting&msg=failed' ) );
	exit();
} else {
	?>
<div class='st-hr'>
	<div id="st-settings-nav-links">
		<ul>
			<li>
				<a href="#st-sandbox-settings-box" ><?php echo esc_html( __( 'Sandbox settings', 'straker-translations' ) ); ?></a>
			</li>
		<?php if ( 'none' !== $this->st_rewrite_type ) { ?>
				<li>
					<a href="#st-frontpage-display-box" ><?php echo esc_html( __( 'Front page display settings', 'straker-translations' ) ); ?></a>
				</li>
			<?php } ?>
			<li>
				<a href="#st-shortcode-settings-box" ><?php echo esc_html( __( 'Shortcode settings', 'straker-translations' ) ); ?></a>
			</li>
		<?php if ( 'none' !== $this->st_rewrite_type ) { ?>
				<li>
					<a href="#st-menu-swticher-box" ><?php echo esc_html( __( 'Menu language switcher settings', 'straker-translations' ) ); ?></a>
				</li>
			<?php } ?>
		</ul>

	</div>

	<div id="st-box-container">

		<!-- Start of Plugin Mode Settings -->
		<div class="st-multi-div-accord" id="st-sandbox-settings-box">
			<div class="st-main-settings-postbox">
			<?php $sandbox_mode = Straker_Translations_Config::straker_sandbox_mode(); ?>
				<form method="post" name="general_settings" id="general_settings" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'straker-translations-general-settings', 'straker-translations-general-settings-nonce' ); ?>
					<input type="hidden" name="action" value="straker_general_settings">
					<h1 class="st-postbox-heading"><?php echo esc_html( __( 'Sandbox Settings', 'straker-translations' ) ); ?></h1>
					<h1 class="st-postbox-heading-desc"></h1>
					<hr style="clear:both;"/>

					<!--Start of Sandbox Settings option -->
					<div class="inside st-box-inside">
						<div class="st-accord-label">
							<label class="st-box-inside-label"><?php echo esc_html( __( 'Plugin Mode', 'straker-translations' ) ); ?></label>
						</div>
						<div class="st-accord-detail">
							<input name="sandbox_mode" id="sandbox_mode_code" type="radio" value="true"
							<?php
							if ( 'true' === $sandbox_mode ) {
								echo 'checked="checked"'; }
							?>
							><?php echo esc_html( __( 'Sandbox (Testing) ', 'straker-translations' ) ); ?>
							<p class="description"><br /><?php echo esc_html( __( 'Setting the plugin to Sandbox will allow you to test the workflow and features of the plugin without creating live jobs. Your content will be pseudo-translated (all text will be reversed) and not provided by a human. Please make sure you test the plugin using this mode.', 'straker-translations' ) ); ?></p>
						</div>
					</div>
					<!-- End of Sandbox Settings option -->

					<!-- Start of Live Settings option -->
					<div class="inside st-box-inside">
						<div class="st-accord-label">
							<label class="st-box-inside-label"><?php echo esc_html( __( 'Plugin Mode', 'straker-translations' ) ); ?></label>
						</div>
						<div class="st-accord-detail">
							<input name="sandbox_mode" id="sandbox_mode_domain" type="radio" value="false"
							<?php
							if ( 'false' === $sandbox_mode ) {
								echo 'checked="checked"'; }
							?>
							/> <?php echo esc_html( __( 'Live', 'straker-translations' ) ); ?>
							<p class="description"><br /><?php echo esc_html( __( 'Setting the plugin to Live will allow you to create real live jobs. You will receive a quote which you will need to purchase, and your content will be translated by a human translator.', 'straker-translations' ) ); ?></p>
						</div>
					</div>
					<!-- End of Live Settings option -->

					<!-- Sart of Save Setting Button -->
					<div class="inside" style="height: 30px;padding: 10px;">
						<div class="st-lang-switch-save-btn">
						<?php if ( Straker_Translations_Config::straker_sandbox_mode() === 'true' ) { ?>
								<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_attr( __( 'Save Sandbox Settings', 'straker-translations' ) ); ?>" onclick="return confirm('<?php esc_attr_e( 'You can delete test jobs before changing the mode to Live. Please note, that Delete Test Jobs will also delete all pages and posts created by Sandbox jobs.', 'straker-translations' ); ?>');">
							<?php } else { ?>
								<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_attr( __( 'Save Sandbox Settings', 'straker-translations' ) ); ?>">
							<?php } ?>
						</div>
					</div>
					<!-- End of Save Setting Button -->
				</form>
				<!-- Start of Delete Sandox Jobs -->
			<?php if ( Straker_Translations_Config::straker_sandbox_mode() === 'true' ) { ?>
					<form method="post" name="test_delete" id="test_delete" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="straker_test_delete">
						<?php wp_nonce_field( 'straker-translations-test-delete', 'straker-translations-test-delete-nonce' ); ?>
						<div class="inside" style="height: 45px;padding: 10px;border-top: 1px solid #E1E1E1;">
							<div class="st-lang-switch-save-btn">
								<input type="submit" name="submit" class="button button-primary st-delete-jobs-btn" value="Delete Test Jobs" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete all test jobs?', 'straker-translations' ); ?>');" /><br />
								<p class="description"><?php echo esc_html( __( 'Delete Test Jobs will also delete all pages and posts created by Sandbox jobs.', 'straker-translations' ) ); ?></p>
							</div>
						</div>
					</form>
				<?php } ?>
				<!-- End of Delete Sandox Jobs -->
			</div>
		</div>
		<!-- End of Plugin Mode Settings -->
	<?php if ( 'none' !== $this->st_rewrite_type ) { ?>
			<!-- Start of Page/Posts Display Settings -->
			<div class="st-multi-div-accord" id="st-frontpage-display-box">
				<div class="st-main-settings-postbox">
					<h1 class="st-postbox-heading"><?php echo esc_html( __( 'Front page display settings', 'straker-translations' ) ); ?></h1>
					<h1 class="st-postbox-heading-desc"><?php echo esc_html( __( 'Any changes to a front page on WordPress Settings - Reading will impact the display of your translated content. Your home page(s) must be translated to display related content. ', 'straker-translations' ) ); ?></h1>
					<hr style="clear:both;"/>
					<!--Start of Page/Posts Display Settings for Default Language -->
					<?php if ( 'posts' !== $show_on_front && 'page' === $show_on_front ) { ?>
						<div class="inside st-box-inside">
							<div class="st-accord-label">
								<label class="st-box-inside-label"><?php echo esc_html( $this->straker_default_language['name'] ) . ' - source language'; ?></label>
							</div>
							<div class="st-accord-detail">
								<?php if ( 0 !== $page_on_front ) { ?>
									<label class="st-box-inside-label">
									<?php
									echo esc_html( __( 'Front page : ', 'straker-translations' ) );
									/* translators: %s: search term */
									printf( wp_kses_post( '<a href="%1$s" target="_blank">%2$s</a> ' ), esc_html( get_edit_post_link( $page_on_front ) ), esc_html( get_the_title( $page_on_front ) ) );
									?>
									</label><br />
								<?php } ?>
								<?php if ( 0 !== $page_for_posts ) { ?>
									<label class="st-box-inside-label">
									<?php
									echo esc_html( __( 'Posts page : ', 'straker-translations' ) );
									/* translators: %s: search term */
									printf( wp_kses_post( '<a href="%1$s" target="_blank">%2$s</a> ' ), esc_url( get_edit_post_link( $page_for_posts ) ), esc_html( get_the_title( $page_for_posts ) ) );
									?>
									</label>
								<?php } ?>
							</div>
						</div>
						<!-- End of Page/Posts Display Settings for Default Language -->

						<!-- Start of Page/Posts Display Settings for Target Languages -->
						<?php foreach ( $this->straker_added_language as $index => $key ) { ?>
							<div class="inside st-box-inside">
								<div class="st-accord-label">
									<label class="st-box-inside-label"><?php echo esc_html( $key['name'] ); ?></label>
								</div>
								<div class="st-accord-detail">
									<?php if ( 0 !== $page_on_front ) { ?>
										<label class="st-box-inside-label">
											<?php $check_front_page_target_exits = Straker_Util::get_lang_meta_into_array( Straker_Util::get_meta_by_value( $page_on_front ), $key['code'] ); ?>

												<?php
												if ( is_array( $check_front_page_target_exits ) && isset( $check_front_page_target_exits['source_id'] ) && $check_front_page_target_exits['source_id'] === $page_on_front ) {
															printf(
																/* translators: %s: search term */
																wp_kses_post( 'Front page : <a href="%1$s" target="_blank">%2$s</a>', 'straker-translations' ),
																esc_url( get_edit_post_link( $check_front_page_target_exits['target_id'] ) ),
																esc_html( get_the_title( $check_front_page_target_exits['target_id'] ) )
															);

												} else {
													echo esc_html( __( 'Front page : ', 'straker-translations' ) );
													?>
															<div class="st-alert-box st-error-msg">
																<?php
																/* translators: %s: search term */
																printf( wp_kses_post( ' Change to %1$s home page must be translated in order for related pages to display. Please translate this page <a href="%2$s" target="_blank">%3$s</a> ' ), esc_html( $key['name'] ), esc_url( get_edit_post_link( $page_on_front ) ), esc_html( get_the_title( $page_on_front ) ) );
																?>
															</div>
												<?php } ?>
										</label>
									<?php } ?>
									<br />
									<?php if ( 0 !== $page_for_posts ) { ?>
										<?php $check_posts_page_target_exits = Straker_Util::get_lang_meta_into_array( Straker_Util::get_meta_by_value( $page_for_posts ), $key['code'] ); ?>
										<label class="st-box-inside-label">
											<?php
											if ( is_array( $check_posts_page_target_exits ) && isset( $check_posts_page_target_exits['source_id'] ) && $check_posts_page_target_exits['source_id'] === $page_for_posts ) {
												printf(
													/* translators: %s: search term */
													wp_kses_post( 'Posts page : <a href="%1$s" target="_blank">%2$s</a>', 'straker-translations' ),
													esc_url( get_permalink( $check_posts_page_target_exits['target_id'] ) ),
													esc_html( get_the_title( $check_posts_page_target_exits['target_id'] ) )
												);
											} else {
												echo esc_html( __( 'Posts page : ', 'straker-translations' ) );
												?>
												<div class="st-alert-box st-error-msg">
													<?php /* translators: %s: search term */ ?>
													<?php printf( wp_kses_post( ' Change to %1$s posts page must be translated in order for related posts to display. Please translate this page <a href="%2$s" target="_blank">%3$s</a> ' ), esc_html( $key['name'] ), esc_url( get_edit_post_link( $page_for_posts ) ), esc_html( get_the_title( $page_for_posts ) ) ); ?>
												</div>
											<?php } ?>
										</label>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
						<!-- End of Page/Posts Display Settings for Target Language -->

					<?php } else { ?>

						<!--Start of Page/Posts Display Settings for Display Postse -->
						<div class="inside st-box-inside">
							<div class="st-accord-label">
								<label class="st-box-inside-label"><?php echo esc_html( __( 'For source language and target languages ', 'straker-translations' ) ); ?></label>
							</div>
							<div class="st-accord-detail">
								<label class="st-box-inside-label"><?php echo esc_html( __( 'Front page: Your latest posts ', 'straker-translations' ) ); ?></label><br />
							</div>
						</div>
						<!--ENd of page/posts display settings for display posts -->

					<?php } ?>
				</div>
			</div>
			<!-- End Of Page/Posts Display Seeting-->
		<?php } ?>

		<!-- Start of Shortcode Settings -->
		<div class="st-multi-div-accord" id="st-shortcode-settings-box">
			<div class="st-main-settings-postbox">
				<form method="post" name="shortcode_settings" id="st_shortcode_settings" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php
					wp_nonce_field( 'straker-translations-shortcode-settings', 'straker-translations-shortcode-settings-nonce' );
					$shortcode_option = get_option( Straker_Translations_Config::straker_option_shortcode );
					$avaialable_langs = '';
					if ( $shortcode_option ) {
						$avaialable_langs = isset( $shortcode_option['available_langs'] ) && ! empty( $shortcode_option['available_langs'] ) ? $shortcode_option['available_langs'] : '';
						$display_flags    = isset( $shortcode_option['display_flags'] ) ? $shortcode_option['display_flags'] : '';
						$display_langs    = isset( $shortcode_option['display_langs'] ) ? $shortcode_option['display_langs'] : '';
						$display_horiz    = isset( $shortcode_option['display_horiz'] ) ? $shortcode_option['display_horiz'] : '';
					}
					?>
					<input type="hidden" name="action" value="straker_shortcode_settings">
					<input type="hidden" id="sl" name="sl" value="">
					<h1 class="st-postbox-heading"><?php echo esc_html( __( 'Shortcode Settings', 'straker-translations' ) ); ?></h1>
					<h1 class="st-postbox-heading-desc"><?php echo esc_html( __( 'To create a language switcher short code, select the relevant information below, click \'Generate Shortcode\' and enter the code on the page(s) required.', 'straker-translations' ) ); ?></h1>
					<hr style="clear:both;"/>

					<!--Start of Shartcode preview -->
					<?php if ( $shortcode_option ) { ?>
						<div class="inside" style="height: 30px;padding: 10px; text-align: center;border-bottom:1px solid #E1E1E1; margin: 16px 0px 0px 0px !important">
								<code id="st-shortcode">[straker_translations languages="
								<?php
								foreach ( $avaialable_langs as $alng ) {
									if ( end( $avaialable_langs ) !== $alng ) {
										echo esc_html( $alng ). ',';
									} else {
										echo esc_html( $alng );
									}
								}
								?>
									"
									<?php
									if ( 'on' === $display_flags ) {
										echo 'display_flag="' . esc_attr( $display_flags ) . '"';
									}
									if ( 'on' === $display_flags ) {
										echo ' display_language="' . esc_attr( $display_langs ) . '"';
									}
									if ( 'on' === $display_flags ) {
										echo ' horizontal="' . esc_attr( $display_horiz ) . '"';
									}
									?>
									]
								</code>
								&nbsp;&nbsp;<a style="box-shadow: none !important; text-decoration: none;cursor: pointer;" class="st-cb-cp" data-clipboard-action="copy" data-clipboard-target="#st-shortcode" st-data-tooltip title="Copy to Clipboard"><span class="dashicons dashicons-clipboard"></span></a>
								&nbsp;&nbsp;&nbsp;<span id="st-copied" style="display:none;"><?php echo esc_html( __( 'Copied!', 'straker-translations' ) ); ?></span>
						</div>
					<?php } ?>
					<!-- End of Shartcode preview -->

					<!--Start of Available languages -->
					<div class="inside st-box-inside">
						<div class="st-accord-label">
							<label class="st-box-inside-label"><?php echo esc_html( __( 'Available Languages', 'straker-translations' ) ); ?></label>
						</div>
						<div class="st-accord-detail">
							<p id="tagline-description" class="description"></p><br />
							<input type="checkbox" name="tl[]" id="tl[]" value="
								<?php
								if ( empty( $avaialable_langs ) ) {
									echo esc_attr( $this->straker_default_language['code'] );
								} else {
									echo esc_attr( $this->straker_default_language['code'] );}
								?>
								"
								<?php
								if ( ! empty( $avaialable_langs ) ) {
									if ( in_array( $this->straker_default_language['code'], $avaialable_langs, true ) ) {
										echo 'checked'; }
								}
								?>
							/>
							<label class="st-label" style="background: url('<?php echo esc_url( $this->flags_path ) . esc_html( $this->straker_default_language['code'] ); ?>.png') left no-repeat;padding-left: 35px;">
								<?php echo esc_html( $this->straker_default_language['name'] ); ?>
								<?php if ( $this->straker_default_language['name'] !== $this->straker_default_language['native_name'] ) { ?>
								<small class="dd-desc"><?php echo ' - ' . esc_html( $this->straker_default_language['native_name'] ); ?>
								<?php } ?>
							</label><br /><br />
								<?php foreach ( $this->straker_added_language as $value ) { ?>
									<input type="checkbox" name="tl[]" id="tl[]" value="<?php echo esc_attr( $value['code'] ); ?>"
										<?php
										if ( $avaialable_langs ) {
											foreach ( $avaialable_langs as $dis_langs ) {
												if ( $dis_langs === $value['code'] ) {
													echo 'checked';
												}
											}
										}
										?>
									/>
									<label class="st-label" style="background: url(<?php echo esc_url( $this->flags_path ) . esc_html( $value['code'] ) . '.png'; ?>) left no-repeat; padding-left: 35px;"><?php echo esc_html( $value['name'] ); ?>
										<?php if ( $value['native_name'] !== $value['name'] ) { ?>
											<small class="dd-desc"><?php echo '- ' . esc_html( $value['native_name'] ); ?></small>
										<?php } ?>
									</label>
									<br /><br />
								<?php } ?>
						</div>
					</div>
					<!-- End of Available languages -->

					<!-- Start of Live Settings option -->
					<div class="inside st-box-inside">
						<div class="st-accord-label">
							<label class="st-box-inside-label"><?php echo esc_html( __( 'General Settings', 'straker-translations' ) ); ?></label>
						</div>
						<div class="st-accord-detail">
							<input type="checkbox" name="display_flags" value="on"
							<?php
							if ( isset( $display_flags ) && 'on' === $display_flags ) {
								echo 'checked';}
							?>
							class="display_flag_lang" />
							<label><?php esc_attr_e( 'Display Flag', 'straker-translations' ); ?></label>
							<span class="gray-note st-shortcode-note"><?php esc_attr_e( 'Show the flag as an icon.', 'straker-translations' ); ?></span><br / >
							<input type="checkbox" name="display_langs" value="on"
							<?php
							if ( isset( $display_langs ) && 'on' === $display_langs ) {
								echo 'checked';}
							?>
							class="display_flag_lang" />
							<label><?php esc_attr_e( 'Display Language', 'straker-translations' ); ?></label>
							<span class="gray-note st-shortcode-note"><?php esc_attr_e( 'Show the language name written out.', 'straker-translations' ); ?></span><br / >
							<input type="checkbox" name="display_horizontal" value="on"
							<?php
							if ( isset( $display_horiz ) && 'on' === $display_horiz ) {
								echo 'checked';}
							?>
							/>
							<label><?php esc_attr_e( 'Display Horizontal', 'straker-translations' ); ?></label>
							<span class="gray-note st-shortcode-note"><?php esc_attr_e( 'Show the options horizontally (not in a list).', 'straker-translations' ); ?></span>
						</div>
					</div>
					<!-- End of Live Settings option -->

					<!-- Sart of Save Setting Button -->
					<div class="inside" style="height: 30px;padding: 10px;">
						<div class="st-lang-switch-save-btn">
							<?php submit_button( esc_html( __( 'Generate Shortcode', 'straker-translations' ) ), 'primary', 'submit', false ); ?>
						</div>
					</div>
					<!-- End of Save Setting Button -->
				</form>
			</div>
		</div>
		<!-- End of shortcode Settings -->
		<?php if ( 'none' !== $this->st_rewrite_type ) { ?>
			<!-- Start of Menu Language Switcher Settings -->
			<div class="st-multi-div-accord" id="st-menu-swticher-box">

				<div class="st-main-settings-postbox">
					<h1 class="st-postbox-heading"><?php echo esc_html( __( 'Menu language switcher settings', 'straker-translations' ) ); ?></h1>
					<h1 class="st-postbox-heading-desc"></h1>
					<hr style="clear:both;"/>

					<!-- Start of Enable/Disable Menu Switcher Option-->
					<div class="inside st-box-inside">
						<div class="st-accord-label">
							<label class="st-box-inside-label"><?php echo esc_html( __( 'Display language menu switcher', 'straker-translations' ) ); ?></label>
						</div>
						<div class="st-accord-detail">
							<?php if ( $menu_switcher_settings['status'] ) { ?>
								<label class="st-checkbox-skewed" style="font-size: 16px">
									<input id="lang_menu_switcher_status" type="checkbox" name="lang_menu_switcher_status" checked />
									<span data-on="Yes" data-off="No"></span>
								</label>

							<?php } else { ?>
								<label class="st-checkbox-skewed" style="font-size: 16px">
									<input id="lang_menu_switcher_status" type="checkbox" name="lang_menu_switcher_status" />
								<span data-on="Yes" data-off="No"></span>
								</label>
							<?php } ?>
							<label class="st-tgl-btn" data-tg-off="DISABLE" data-tg-on="ENABLE" for="lang_menu_switcher_status"></label>
						</div>
					</div>
					<!-- End of Enable/Disable Menu Switcher Option-->

					<!-- Start of Menus Options Option-->
					<div class="inside st-box-inside st-box-show-hide" <?php echo esc_attr( $menu_switcher_status ); ?>>
						<div class="st-accord-label">
							<label class="st-box-inside-label"><?php echo esc_html( __( 'Menu', 'straker-translations' ) ); ?></label>
						</div>
						<div class="st-accord-detail">
							<select name="menu" id="select_menu_options" class="st-switcher-status">
								<option value=''><?php echo esc_html( __( ' -- Choose a menu --', 'straker-translations' ) ); ?></option>
								<?php
								foreach ( (array) $nav_menus as $_nav_menu ) {
									printf(
										'<option value="%s" %s>%s</option>',
										(string)$_nav_menu->term_id,
										( $_nav_menu->term_id === (int)$menu_switcher_settings['switcher_menu'] ) ? 'selected="selected"' : '',
										esc_html( trim( (string)$_nav_menu->name ) )
									);
								}
								?>
							</select>
						</div>
					</div>
					<!-- End of Menus Options Option-->

					<!-- Start of Menus Item Position-->
					<div class="inside st-box-inside st-box-show-hide" <?php echo esc_attr( $menu_switcher_status ); ?>>
						<div class="st-accord-label">
							<label class="st-box-inside-label"><?php echo esc_html( __( 'Position', 'straker-translations' ) ); ?></label>
						</div>
						<div class="st-accord-detail">
							<input id="position_of_items_first" class="st-switcher-status" type="radio" name="position_of_items" value="first" <?php echo ( 'first' === $menu_switcher_settings['item_postion'] ) ? 'checked="checked"' : ''; ?> checked/>
							<label for="position_of_items_first"><?php echo esc_html( __( 'Show as initial menu option', 'straker-translations' ) ); ?></label>
							<br />
							<input id="position_of_items_last" class=" st-switcher-status" type="radio" name="position_of_items" value="last" <?php echo ( 'last' === $menu_switcher_settings['item_postion'] ) ? 'checked="checked"' : ''; ?> />
							<label for="position_of_items_last"><?php echo esc_html( __( 'Show as last menu option', 'straker-translations' ) ); ?></label>
						</div>
					</div>
					<!-- End of Menus Options Option-->

					<!-- Start of Menus Item Position-->
					<div class="inside st-box-inside st-box-show-hide" <?php echo esc_attr( $menu_switcher_status ); ?>>
						<div class="st-accord-label">
							<label class="st-box-inside-label"><?php echo esc_html( __( 'Language menu style', 'straker-translations' ) ); ?></label>
						</div>
						<div class="st-accord-detail">
							<input id="style_of_items_dropdown" class=" st-switcher-status" type="radio" name="style_of_items" value="dropdown" <?php echo ( 'dropdown' === $menu_switcher_settings['switcher_style'] ) ? 'checked="checked"' : ''; ?> checked/>
							<label for="style_of_items_dropdown"><?php echo esc_html( __( 'Display as dropdown', 'straker-translations' ) ); ?></label>
							<br />
							<input id="style_of_items_list" class=" st-switcher-status" type="radio" name="style_of_items" value="list" <?php echo ( 'list' === $menu_switcher_settings['switcher_style'] ) ? 'checked="checked"' : ''; ?> />
							<label for="style_of_items_list"><?php echo esc_html( __( 'Display as row', 'straker-translations' ) ); ?></label>

						</div>
					</div>
					<!-- End of Menus Item Position -->

					<!-- Sart of Items as flags/items -->
					<div class="inside st-box-inside st-box-show-hide" <?php echo esc_attr( $menu_switcher_status ); ?>>
						<div class="st-accord-label">
							<label class="st-box-inside-label"><?php echo esc_html( __( 'General settings', 'straker-translations' ) ); ?></label>
						</div>
						<div class="st-accord-detail">
							<input id="display_flags" class="st-general-display st-switcher-status" type="checkbox" name="display_flags" <?php echo ( true === (boolean)$menu_switcher_settings['display_flags'] ) ? 'checked="checked"' : ''; ?> />
							<label for="display_flags" id="display_flags_lbl"><?php echo esc_html( __( 'Display flag', 'straker-translations' ) ); ?></label>
							<br />
							<input id="display_language" class="st-general-display st-switcher-status" type="checkbox" name="display_language" <?php echo ( true === (boolean)$menu_switcher_settings['display_language'] ) ? 'checked="checked"' : ''; ?> />
							<label for="display_language" id="display_language_lbl"><?php echo esc_html( __( 'Display language', 'straker-translations' ) ); ?></label>

						</div>
					</div>
					<!-- End of Items as flags/items -->

					<!-- Sart of Save Setting Button -->
					<div class="inside" style="height: 30px;padding: 10px;">
						<div class="st-lang-switch-save-btn">
							<span id="js-st-ms-message">
								<span class="before-saving-msg"><img src="<?php echo esc_url( STRAKER_PLUGIN_ABSOLUTE_PATH ) . '/admin/img/loading.gif'; ?>">Saving settings ... </span>
								<span class="after-saved-msg">Settings saved </span>
							</span>
							<span><?php submit_button( __( 'Save Settings', 'straker-translations' ), 'primary', 'stMenuSwitcherButton', false ); ?> </span>
						</div>
					</div>
					<!-- End of Save Setting Button -->
				</div>
			</div>
			<!-- End of Menu Language Switcher Settings -->
		<?php } ?>
	</div>
	<?php } ?>
