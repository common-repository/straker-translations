<?php
/**
 * Provide a admin area view for the plugin
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
	$rewrite_opt = Straker_Translations_Config::straker_rewrite_type();
if ( 'true' === Straker_Translations_Config::straker_sandbox_mode() && false !== $this->straker_auth && $this->straker_added_language && ! empty( $rewrite_opt ) ) {
	?>
		<div class="update-nag notice">
			<p>
				<strong><?php esc_html_e( $this->straker_message( 'test_mode' ), 'straker-translations' ); ?></strong>
			</p>
			<p>
				<?php esc_html_e( $this->straker_message( 'test_text' ), 'straker-translations' ); ?>&nbsp;
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=st-settings#st-sandbox-settings-box' ) ); ?>"><?php esc_html_e( ' Settings.', 'straker-translations' ); ?></a>
			</p>
		</div>
	<?php } ?>
