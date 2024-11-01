<?php
/**
 * Provide a redirect to Straker My Account page
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
		<h1><?php esc_attr_e( 'Sending Files and Assets for Translation', 'straker-translations' ); ?></h1>
		<p class="ma-btn mj-btn">
			<a class="button button-primary" st-data-tooltip title="<?php echo esc_attr_e( 'Click to send us assets via your My Account dashboard.', 'straker-translations' ); ?>" href="https://deltaray.strakertranslations.com/" target="_blank"><?php esc_attr_e( 'My Account', 'straker-translations' ); ?>&nbsp;<span class="dashicons dashicons-migrate pt-3"></a>
		</p>
		<div class="st-hr">
			<p><?php esc_attr_e( "To send the following file types, please use the 'My Account' button at the top of the page to go through to your secure Straker Translations Dashboard:", 'straker-translations' ); ?></p>

			<ul class="st-ul">
				<li><?php esc_attr_e( 'Video files (.mpg, .mov, .wmv, and more)', 'straker-translations' ); ?></li>
				<li><?php esc_attr_e( 'WordPress Templates (.po)', 'straker-translations' ); ?></li>
				<li><?php esc_attr_e( 'CSV files (.csv)', 'straker-translations' ); ?></li>
				<li><?php esc_attr_e( 'Image files (.ai, .ps, .jpg, .png)', 'straker-translations' ); ?></li>
				<li><?php esc_attr_e( 'Spreadsheets (.xls, .xlsx)', 'straker-translations' ); ?></li>
				<li><?php esc_attr_e( 'Documents & Presentations (.doc, .docx, .ppt, .pptx)', 'straker-translations' ); ?></li>
				<li><?php esc_attr_e( 'PDFs (.pdf) - where possible send the original file type for the highest quality result, e.g. InDesign', 'straker-translations' ); ?></li>
				<li><?php esc_attr_e( 'Structured content (.xlf, .xlif, .xml, .resx)', 'straker-translations' ); ?></li>

			</ul>
		</div>

</div>
