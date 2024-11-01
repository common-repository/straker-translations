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
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/messages/sandbox-message.php';

$default_lang = $this->straker_default_language;
$added_langs  = $this->straker_added_language;
$langs        = $this->straker_site_languages;
$get_current_user = wp_get_current_user();
$user_full_name = esc_attr( $get_current_user->user_firstname ) . esc_attr( $get_current_user->user_lastname );

printf( '<h1>%s</h1>', esc_html( __( 'Translation Order', 'straker-translations' ) ) );

if ( false === $this->straker_auth ) {
	include_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/straker-translations-admin-register-button.php';
} else {
	?>
<div class=st-hr>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="st_translation_request_quote" name="request_quote">
		<?php wp_nonce_field( 'straker-translations-request-quote', 'straker-translations-request-quote-nonce' ); ?>
		<input type="hidden" name="action" value="straker_request_quote">

		<?php
			printf(
				'<input type="hidden" name="title" value="%s">',
				esc_attr( $this->straker_job_title() )
			);

			printf(
				'<input type="hidden" name="sl" value="%s">',
				esc_attr( $this->straker_default_language['code'] )
			);
		?>
		<table class="form-table">
			<tbody>
				<tr>
					<?php printf( '<th scope="row"><label>%s</label></th>', esc_html( __( 'Source Language', 'straker-translations' ) ) ); ?>
					<td>
						<?php
						if ( empty( $default_lang ) ) {
							printf( '<p>%s</p>', esc_html( __( 'Choose source language and Add new Languages.', 'straker-translations' ) ) );
						} else {
							?>
							<div class="st-lang">
								<fieldset>
									<item>
										<?php
											printf(
												'<label style="background: url(%s.png) left no-repeat;">%s',
												esc_url( $this->flags_path ) . esc_attr( $this->straker_default_language['code'] ),
												esc_html( $this->straker_default_language['name'] )
											);

										if ( $this->straker_default_language['name'] !== $this->straker_default_language['native_name'] ) {
											printf(
												'<small class="dd-desc"> - %s </small>',
												esc_html( $this->straker_default_language['native_name'] )
											);
										}
										?>
										</label>
									</item>
								</fieldset>
							</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<?php printf( '<label>%s</label>', esc_html( __( 'Target Language', 'straker-translations' ) ) ); ?>
					</th>
					<td>
						<?php
						if ( empty( $added_langs ) ) {
								printf( '<p>%s</p>', esc_html( __( 'Choose default language and Add new Languages.', 'straker-translations' ) ) );
						} else {
							?>
						<div class="st-lang">
							<?php foreach ( $this->straker_added_language as $value ) { ?>
							<fieldset>
								<item>
									<?php
										printf( '<input type="checkbox" name="tl[]" class="st_trans_lang_cb" value="%s" />', esc_attr( $value['code'] ) );
										printf(
											'<label style="background: url(%s.png) left no-repeat;"> %s',
											esc_url( $this->flags_path ) . esc_attr( $value['code'] ),
											esc_html( $value['name'] )
										);

									if ( $value['native_name'] !== $value['name'] ) {
										printf( '<small class="dd-desc qp-tt"> - %s', esc_html( $value['native_name'] ) );
									}
									?>
									</label>
								</item>
							</fieldset>
							<?php } ?>
						</div>
						<span id="tagline-description" class="description"></span>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label>
							<?php esc_attr_e( 'Name', 'straker-translations' ); ?><span class="description">*</span>
						</label>
					</th>
					<td>
						<input type="text" name="name" class="regular-text" value="<?php ! empty( $user_full_name ) ? $user_full_name : ''; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label>
							<?php esc_attr_e( 'Email', 'straker-translations' ); ?><span class="description">*</span>
						</label>
					</th>
					<td>
						<input type="text" name="email" class="regular-text" value="<?php echo esc_attr( $get_current_user->user_email ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php esc_attr_e( 'Notes', 'straker-translations' ); ?></label>
					</th>
					<td>
						<textarea name="notes" class="widefat" style="width:80%!important;height:80px!important;" ></textarea>
					</td>
				</tr>
				<?php if ( Straker_Plugin::plugin_exist( 'wp-seo' ) ) { ?>
				<tr>
					<th scope="row">
						<label style="padding-left:20px; background-repeat: no-repeat; background-image: url(&quot;data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgc3R5bGU9ImZpbGw6IzgyODc4YyIgdmlld0JveD0iMCAwIDUxMiA1MTIiPjxnPjxnPjxnPjxnPjxwYXRoIGQ9Ik0yMDMuNiwzOTVjNi44LTE3LjQsNi44LTM2LjYsMC01NGwtNzkuNC0yMDRoNzAuOWw0Ny43LDE0OS40bDc0LjgtMjA3LjZIMTE2LjRjLTQxLjgsMC03NiwzNC4yLTc2LDc2VjM1N2MwLDQxLjgsMzQuMiw3Niw3Niw3NkgxNzNDMTg5LDQyNC4xLDE5Ny42LDQxMC4zLDIwMy42LDM5NXoiLz48L2c+PGc+PHBhdGggZD0iTTQ3MS42LDE1NC44YzAtNDEuOC0zNC4yLTc2LTc2LTc2aC0zTDI4NS43LDM2NWMtOS42LDI2LjctMTkuNCw0OS4zLTMwLjMsNjhoMjE2LjJWMTU0Ljh6Ii8+PC9nPjwvZz48cGF0aCBzdHJva2Utd2lkdGg9IjIuOTc0IiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIGQ9Ik0zMzgsMS4zbC05My4zLDI1OS4xbC00Mi4xLTEzMS45aC04OS4xbDgzLjgsMjE1LjJjNiwxNS41LDYsMzIuNSwwLDQ4Yy03LjQsMTktMTksMzcuMy01Myw0MS45bC03LjIsMXY3Nmg4LjNjODEuNywwLDExOC45LTU3LjIsMTQ5LjYtMTQyLjlMNDMxLjYsMS4zSDMzOHogTTI3OS40LDM2MmMtMzIuOSw5Mi02Ny42LDEyOC43LTEyNS43LDEzMS44di00NWMzNy41LTcuNSw1MS4zLTMxLDU5LjEtNTEuMWM3LjUtMTkuMyw3LjUtNDAuNywwLTYwbC03NS0xOTIuN2g1Mi44bDUzLjMsMTY2LjhsMTA1LjktMjk0aDU4LjFMMjc5LjQsMzYyeiIvPjwvZz48L2c+PC9zdmc+&quot;) !important;">
						<?php esc_attr_e( 'Include Yoast SEO data?', 'straker-translations' ); ?></label>
					</th>
					<td>
						<input type="checkbox" id="st-yoast-check" name="yoast">
						<a target="_blank" href="https://help.strakertranslations.com/hc/en-us/articles/115004438434-Yoast-SEO"><?php esc_attr_e( 'Read More', 'straker-translations' ); ?></a>
					</td>
				</tr>
					<?php
				}
				if ( Straker_Plugin::plugin_exist( 'acf' ) ) {
					?>
					<tr>
						<th scope="row">
							<label class="dashicons dashicons-welcome-widgets-menus"></label><label>
							 <?php esc_attr_e( 'Include Advanced Custom Fields data?', 'straker-translations' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="st-acf-check" name="acf-plugin" checked="true" />
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	<?php } ?>
