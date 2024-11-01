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

	$job_msg = filter_input( INPUT_GET, 'msg', FILTER_SANITIZE_STRING );
	$ac_msg = filter_input( INPUT_GET, 'ac', FILTER_SANITIZE_STRING );

	if ( filter_has_var( INPUT_GET, "msg" ) && ! empty( $job_msg ) && filter_has_var( INPUT_GET, "ac" ) && ! empty( $ac_msg ) ) {

		if ( 'success' === $job_msg ) {
			?>
				<div id="message" class="updated notice is-dismissible">
					<p><?php esc_html_e( $this->straker_message( $ac_msg ), 'straker-translations' ); ?></p>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php esc_attr_e( 'Dismiss this notice.', 'straker-translations' ); ?></span></button>
				</div>
			<?php
		} elseif ( 'failed' === $job_msg ) {
			if ( ! empty( $ac_msg ) ) {
				?>
					<div class='error'>
						<p>
							<?php esc_html_e( $this->straker_message( $ac_msg ), 'straker-translations' ); ?>
						</p>
					</div>
			<?php	} else { ?>
				<div class='error'>
					<p><?php esc_attr_e( Straker_Translations_Config::straker_support_message, 'straker-translations' ); ?></p>
				</div>
				<?php
			}
		}
	}
?>
