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

<div class="form-field custom-field-lang">
  <label for="st-tag-lang"><?php esc_attr_e( 'Language', 'straker-translations' ); ?></label>
  <select name="straker-tag-lang" id="st-tag-lang" required="true">
	<?php $defualt_lang = $this->straker_default_language; ?>
	<option value="<?php echo esc_attr( $defualt_lang['code'] ); ?>" data-description="<?php echo esc_attr( $defualt_lang['name'] ); ?>" selected=""> <?php echo esc_html( $defualt_lang['native_name'] ); ?>
	</option>
	<?php foreach ( $this->straker_added_language as $key => $value ) { ?>
	<option value="<?php echo esc_attr( $value['code'] ); ?>" data-description="<?php echo esc_attr( $value['name'] ); ?>"> <?php echo esc_html( $value['native_name'] ); ?>
	</option>
	<?php } ?>
  </select>
</div>
