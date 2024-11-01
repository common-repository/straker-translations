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
<div class="wrap">
	<div class=st-hr>
		<div class="about-logo">
			<img alt="<?php echo esc_attr( get_admin_page_title() ); ?>" width="300" src="<?php echo esc_url( plugins_url( '../img/s_logo.png', dirname( __FILE__ ) ) ); ?>" >
		</div>
		<div class="about-body">
			<div class="about-text">
				<?php esc_attr_e( 'We make it fast and easy to professionally translate all your WordPress content.', 'straker-translations' ); ?>
			</div>

			<div class="">
				<div class="about-text">
					<?php echo esc_html( __( 'Easy step by step process', 'straker-translations' ) ); ?>
				</div>
				<div class="about-link">
					<ul>
						<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=st-settings' ) ); ?>"><?php esc_attr_e( 'Step 1: Create an account', 'straker-translations' ); ?></a>&nbsp;<?php esc_attr_e( '(You only have to do this once)', 'straker-translations' ); ?></li>
						<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=st-settings&tab=language_settings' ) ); ?>"><?php esc_attr_e( 'Step 2: Setup site languages', 'straker-translations' ); ?></a>&nbsp;<?php esc_attr_e( '(You only need to do this once or whenever you wish to add new languages to your website)', 'straker-translations' ); ?></li>
						<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=st-translation' ) ); ?>"><?php esc_attr_e( 'Step 3: Select your content and Submit a translation job', 'straker-translations' ); ?></a></li>
						<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=st-translation' ) ); ?>"><?php esc_attr_e( 'Step 4: Receive a quote & get started', 'straker-translations' ); ?></a></li>
						<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=st-jobs' ) ); ?>"><?php esc_attr_e( 'Step 5: Import & Publish!', 'straker-translations' ); ?></a></li>
					</ul>
				</div>
			</div>

		</div>
	</div>

	<div class="about-info">
		<div class="find-more">
			<?php echo esc_html( __( 'Find out more about us:', 'straker-translations' ) ); ?>
		</div>

		<a href="<?php echo esc_url( 'https://www.strakertranslations.com/' ); ?>" target="_blank">
			<div class="about-blue" style="position: absolute;left: 25px;height:80px;width: 155px;margin-bottom: 20px;">
				<span class="about-title"><?php esc_attr_e( '4x Faster', 'straker-translations' ); ?></span>
				<div><?php esc_attr_e( 'than industry average delivery times.', 'straker-translations' ); ?></div>
			</div>
		</a>
		<div class="about-blue" style="position: absolute;left: 200px;height:80px;width: 155px;margin-bottom: 20px;">
			<span class="about-title"><?php esc_attr_e( 'Technology', 'straker-translations' ); ?></span>
			<div><?php esc_attr_e( 'We have technology to Automate and simplify the translation process.', 'straker-translations' ); ?></div>
		</div>
		<a href="<?php echo esc_url( 'https://www.strakertranslations.com/translation-quote/' ); ?>" target="_blank">
			<div class="about-blue" style="position: absolute;left: 375px;height:80px;width: 155px;margin-bottom: 20px;">
				<span class="about-title"><?php esc_attr_e( 'Instant Quote', 'straker-translations' ); ?></span>
				<div><?php esc_attr_e( 'Get a price for your translation instantly.', 'straker-translations' ); ?></div>
			</div>
		</a>
	</div>

</div>
