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
$default_lang = $this->straker_default_language;
$added_langs  = $this->straker_added_language;

if ( ! empty( $default_lang ) && ! empty( $added_langs ) ) {
	?>
	<?php $slug_rewrite = $this->st_rewrite_type; ?>
	<div class="st-hr">
		<form method="post" name="url_settings" id="url_settings" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'straker-translations-url-settings', 'straker-translations-url-settings-nonce' ); ?>
			<input type="hidden" name="action" value="straker_url_settings">
			<h2><?php esc_attr_e( 'Select a URL setting', 'straker-translations' ); ?></h2>
			<p><?php esc_attr_e( 'This is how your translated website URLs will be displayed.', 'straker-translations' ); ?></p>
			<p id="errr-msg" style="display:none;">
				<label class="st-error"><?php echo esc_html( __( 'Please select a url settings.', 'straker-translations' ) ); ?></label>
			</p>
			<table class="form-table permalink-structure">
				<tbody>
					<tr>
						<th>
							<label>
								<input name="rewrite_type" id="rewrite_type_code" type="radio" value="code"
								<?php
								if ( 'code' === $slug_rewrite ) {
									echo 'checked="checked"'; }
								?>
								required > <?php esc_attr_e( 'Language code', 'straker-translations' ); ?>
							</label>
						</th>
						<td>
							<?php esc_attr_e( 'Custom structure using language code in the URL.', 'straker-translations' ); ?><br />
							<span class="gray-note"><?php esc_attr_e( 'Example: ' . home_url() . '/zh-cn', 'straker-translations' ); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label>
								<input name="rewrite_type" id="rewrite_type_domain" type="radio" value="domain"
								<?php
								if ( 'domain' === $slug_rewrite ) {
									echo 'checked="checked"'; }
								?>
								> <?php esc_attr_e( 'Domain name', 'straker-translations' ); ?>
							</label>
						</th>
						<td>
							<?php esc_attr_e( 'Please provide the url you require.', 'straker-translations' ); ?>
							<span class="gray-note"><?php esc_attr_e( 'Selecting Domain name requires a change to wp-config.php file and DNS settings.', 'straker-translations' ); ?></span>
						</td>
					</tr>
					<tr>
						<th>
							<label>
								<input name="rewrite_type" id="rewrite_type_none" type="radio" value="none"
								<?php
								if ( 'none' === $slug_rewrite ) {
									echo 'checked="checked"'; }
								?>
								> <?php esc_attr_e( 'Content only', 'straker-translations' ); ?>
							</label>
						</th>
						<td>
							<?php esc_attr_e( 'No additional URLs created for translated content.', 'straker-translations' ); ?>
							<span class="gray-note"><?php esc_attr_e( 'Content provided back in your site tree as a pending with no language switcher options. Best for translations managed as grouped pages within the same site as original text.', 'straker-translations' ); ?></span>
						</td>
					</tr>
				</tbody>
			</table>

			<h2><?php esc_attr_e( 'URLs for Domain name setting', 'straker-translations' ); ?></h2>
			<div class="st-lang">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label class="st-label" style="background: url(<?php echo esc_url( $this->flags_path ) . esc_html( $this->straker_default_language['code'] ) . '.png'; ?>) left no-repeat;">
									<?php echo esc_html( $this->straker_default_language['name'] ); ?>
									<span class="dd-desc st-slng"><?php esc_attr_e( '(Source language)', 'straker-translations' ); ?></span>
								</label>
							</th>
							<td>
								<code><?php echo esc_url( get_site_url() ); ?></code>
							</td>
						</tr>
						<?php
						switch ( $slug_rewrite ) {
							case 'code':
								$url_view       = 'hidden';
								$structure_view = '';
								break;
							case 'domain':
								$url_view       = '';
								$structure_view = 'hidden';
								break;
							case 'none':
								$url_view       = 'hidden';
								$structure_view = 'hidden';
								break;
							default:
								$url_view       = 'hidden';
								$structure_view = 'hidden';
								break;
						}
						?>
						<?php
						foreach ( $this->straker_added_language as $value ) { ?>
						<tr>
							<th scope="row">
								<label class="st-label" style="background: url(<?php echo esc_url( $this->flags_path ) . esc_html( $value['code'] ) . '.png'; ?>) left no-repeat;">
									<?php echo esc_html( $value['name'] ); ?>
								</label>
							</th>
							<td>
								<code class="st-url-structure" <?php echo esc_attr( $structure_view ); ?>><?php echo esc_url( $this->straker_url_structure( $value['short_code'] ) ); ?></code>
								<input type="hidden" name="lang[]" value="<?php echo esc_attr( $value['code'] ); ?>">
								<input type="text" name="url[]" id="url" class="regular-text st-tl-lang st-text-field" value="<?php echo esc_url( $this->straker_urls[ $value['code'] ] ); ?>" <?php echo esc_html( $url_view ); ?>>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php submit_button( __( 'Save URL Settings', 'straker-translations' ), 'primary', 'submit', true ); ?>
	</form>
<?php } else {
	wp_redirect( admin_url( 'admin.php?page=st-settings&tab=language_settings&ac=lang_setting&msg=failed' ) );
	exit;
	} ?>
