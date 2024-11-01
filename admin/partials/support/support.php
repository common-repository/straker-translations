<?php

/**
 * Provide a admin area view for the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 */
?>
<div class="wrap">
	<?php
	if ( filter_has_var( INPUT_GET, "msg" ) ) {
		$msg_status = filter_input( INPUT_GET, 'msg', FILTER_SANITIZE_STRING );
		if ( 'success' === $msg_status ) {
			?>
			<div id="message" class="updated notice is-dismissible">
				<p><?php esc_html_e( 'We have received your email and will get back to you as soon as possible.', esc_html($this->plugin_name ) ); ?></p>
				<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php esc_attr_e( 'Dismiss this notice.', $this->plugin_name ); ?></span></button>
			</div>
			<?php
		} elseif ( 'failed' === $msg_status ) {
			?>
			<div class='error'>
				<p><?php esc_attr_e( Straker_Translations_Config::straker_support_message, $this->plugin_name ); ?></p>
			</div>
			<?php
		}
	}
	?>
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1><br />
	<a href="https://help.strakertranslations.com/hc/en-us/categories/115000412473-WordPress-Plugin" target="_blank"><?php echo esc_attr_e( 'Click here to read our Help Documentation.', $this->plugin_name ); ?></a>
</div>
