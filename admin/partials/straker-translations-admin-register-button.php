<?php
/**
 * Provide an metabox in posts and pages
 *
 * This file is used to show the metabox.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Language_Translation
 * @subpackage Straker_Language_Translation/admin/partials
 */

?>
<h4><?php esc_attr_e( 'You need an account before you can create a new job.', 'straker-translations' ); ?></h4>

<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=st-settings&' ) ); ?> ">
	<?php esc_attr_e( 'Create Account', 'straker-translations' ); ?>
</a>
