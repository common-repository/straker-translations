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

	$job_key = filter_input( INPUT_GET, 'jk', FILTER_SANITIZE_STRING );

	if ( isset( $job_key ) && !empty( $job_key ) ) {
		?>
		<iframe class="quote-iframe" src="<?php echo esc_url( $this->straker_quote( 'job_key=' . $job_key ) ); ?>" width="100%" style="height: 100vh;" frameborder="0" scrolling="yes"></iframe>
		<?php
	} else {
		?>
	<div class='error'>
		<p><?php esc_attr_e( Straker_Translations_Config::straker_support_message, 'straker-translations' ); ?></p>
	</div>
		<?php
	} ?>
