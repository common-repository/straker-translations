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
	<?php if ( false === $this->straker_auth ) { ?>
	<div class="st-hr">
		<h1><?php echo esc_attr_e( 'Create Account', 'straker-translations' ); ?></h1>
		<?php require_once( WP_PLUGIN_DIR . '/straker-translations/admin/partials/messages/general-message.php'); ?>
		<?php $get_current_user = wp_get_current_user(); ?>
		<form name="straker_registration_form" id="straker_registration_form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'straker-translations-register', 'straker-translations-register-nonce' ); ?>
			<input type="hidden" name="action" value="straker_register">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $this->plugin_name ); ?>-first_name">
								<?php esc_attr_e( 'First Name', 'straker-translations' ); ?> <span class="description">*</span>
							</label>
						</th>
						<td>
							<input type="text" id="first_name" name="first_name" class="regular-text" value="<?php echo esc_attr( $get_current_user->user_firstname ); ?>" required=""/>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $this->plugin_name ); ?>-last_name">
								<?php esc_attr_e( 'Last Name', 'straker-translations' ); ?> <span class="description">*</span>
							</label>
						</th>
						<td>
							<input type="text" id="last_name" name="last_name" class="regular-text" value="<?php echo esc_attr( $get_current_user->user_lastname ); ?>" required=""/>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $this->plugin_name ); ?>-email_address">
								<?php esc_attr_e( 'Email Address', 'straker-translations' ); ?> <span class="description">*</span>
							</label>
						</th>
						<td>
							<input type="email" id="email_address" name="email_address" class="regular-text" value="<?php echo esc_attr( $get_current_user->user_email ); ?>" required=""/>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $this->plugin_name ); ?>-phone_number">
								<?php esc_attr_e( 'Phone', 'straker-translations' ); ?>
							</label>
						</th>
						<td>
							<input type="number" id="phone_number" name="phone_number" class="regular-text" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $this->plugin_name ); ?>-country">
								<?php esc_attr_e( 'Country', 'straker-translations' ); ?> <span class="description">*</span>
							</label>
						</th>
						<td>
							<select name='country' id='country' required="">
								<option value="" >Select Country</option>
							<?php
								foreach ( $this->straker_countries as $key => $value ) {
									echo '<option value="' . esc_attr( $value['code'] ) . '">' . esc_html( $value['name'] ) . '</option>';
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $this->plugin_name ); ?>-company_name">
								<?php esc_attr_e( 'Company Name', 'straker-translations' ); ?>
							</label>
						</th>
						<td>
							<input type="text" id="company_name" name="company_name" class="regular-text" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $this->plugin_name ); ?>-company_size">
								<?php esc_attr_e( 'Company Size (number of employees)', 'straker-translations' ); ?>
							</label>
						</th>
						<td>
							<select name='company_size' id='company_size'>
								<option value="" selected>Select Company Size</option>
								<option value="1-10">1 - 10</option>
								<option value="10-50">10 - 50</option>
								<option value="50-250">50 - 250</option>
								<option value="250+">250+</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $this->plugin_name ); ?>-terms-condition">
							</label>
						</th>
						<td>
							<?php esc_attr_e( 'I have read and accept the ', 'straker-translations' ); ?><a href="https://www.strakertranslations.com/terms-conditions/" target="_blank"><?php esc_attr_e( 'Terms and Conditions', 'straker-translations' ); ?>:</a>&nbsp; &nbsp; <input type="checkbox" id="terms_condition" name="terms_condition" required="" />
						</td>
					</tr>
				</tbody>
			</table>
			<?php submit_button( __( 'Create Account', 'straker-translations' ), 'primary', 'submit', true ); ?>
		</form>
	</div>
	<?php } else { ?>
		<?php require_once( WP_PLUGIN_DIR . '/straker-translations/admin/partials/messages/sandbox-message.php'); ?>
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<?php
		require_once( WP_PLUGIN_DIR . '/straker-translations/admin/partials/messages/general-message.php');
		$active_tab   = filter_has_var( INPUT_GET, "tab" ) ? filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) : 'main_settings';
		$plugin_mode  = ( 'true' === Straker_Translations_Config::straker_sandbox_mode() ) ? 'Sandbox' : 'Live';
		$default_lang = $this->straker_default_language;
		$added_langs  = $this->straker_added_language;
		$rewrite_opt  = Straker_Translations_Config::straker_rewrite_type();
		?>
	<h2 class="nav-tab-wrapper">
		<a
		<?php
		if ( ! empty( $default_lang ) && ! empty( $added_langs ) && ! empty( $rewrite_opt ) ) {
			?>
			href="<?php echo esc_url( admin_url( 'admin.php?page=st-settings&tab=main_settings' ) ); ?>" <?php } ?> class="nav-tab <?php echo 'main_settings' === $active_tab ? 'nav-tab-active' : ''; ?>"
			<?php
			if ( empty( $default_lang ) || empty( $rewrite_opt ) ) {
				?>
			st-data-tooltip title="<?php esc_attr_e( 'Please select your languages and URL preferences first.', 'straker-translations' ); ?>" <?php } ?>><?php esc_attr_e( 'Main Settings', 'straker-translations' ); ?></a>
		<a
		<?php
		if ( ! empty( $default_lang ) && ! empty( $added_langs ) && ! empty( $rewrite_opt ) ) {
			?>
			href="<?php echo esc_url( admin_url( 'admin.php?page=st-settings&tab=language_management' ) ); ?>" <?php } ?> class="nav-tab <?php echo 'language_management' === $active_tab ? 'nav-tab-active' : ''; ?>"
			<?php
			if ( empty( $default_lang ) || empty( $rewrite_opt ) ) {
				?>
			st-data-tooltip title="<?php esc_attr_e( 'Please select your languages and URL preferences first.', 'straker-translations' ); ?>" <?php } ?>><?php esc_attr_e( 'Language Management', 'straker-translations' ); ?></a>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=st-settings&tab=language_settings' ) ); ?>" class="nav-tab <?php echo 'language_settings' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'Language Settings', 'straker-translations' ); ?></a>
		<a
		<?php
		if ( ! empty( $default_lang ) && ! empty( $added_langs ) ) {
			?>
			href="<?php echo esc_url( admin_url( 'admin.php?page=st-settings&tab=url_settings' ) ); ?>" <?php } ?> class="nav-tab <?php echo 'url_settings' === $active_tab ? 'nav-tab-active' : ''; ?>"
							  <?php
								if ( empty( $default_lang ) && empty( $added_langs ) ) {
									?>
	 st-data-tooltip title="<?php esc_attr_e( 'Please select your languages preferences first.', 'straker-translations' ); ?>" <?php } ?>><?php esc_attr_e( 'URL Settings', 'straker-translations' ); ?></a>
	</h2>
		<?php
		if ( 'main_settings' === $active_tab ) {
			include_once plugin_dir_path( dirname( __FILE__ ) ) . '/settings/main.php';
		} elseif ( 'url_settings' === $active_tab ) {
			include_once plugin_dir_path( dirname( __FILE__ ) ) . '/settings/url.php';
		} elseif ( 'language_settings' === $active_tab ) {
			include_once plugin_dir_path( dirname( __FILE__ ) ) . '/settings/language.php';
		} else {
			include_once plugin_dir_path( dirname( __FILE__ ) ) . '/settings/language-management.php';
		}
	}
	?>
</div>
