<?php
/**
 * Provide an metabox in posts and pages.
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

<div class="wrap">
	<?php
	global $wpdb;
	global $post;

	$table_post = $wpdb->prefix . 'posts';

	$attachements_ids = $this->straker_getImgs_ids( $post->ID );

	if ( ! empty( $attachements_ids ) ) {
		wp_nonce_field( $this->plugin_name, 'straker_assets_meta_box_nonce' );
		?>
		<table class="wp-list-table widefat fixed striped">
		<thead>
			<th class="manage-column" scope="col"><?php echo esc_attr_e( 'Browse Localized File', 'straker-translations' ); ?></th>
			<th class="manage-column" scope="col"><?php echo esc_attr_e( 'Image File', 'straker-translations' ); ?></th>
		</thead>
		<tbody>
		<?php
			foreach ( $attachements_ids as $key => $value ) {

				$non_localized_images = wp_get_attachment_image( $key,  array('100', '100') );
				if ( $non_localized_images ) {
					?>
					<tr>
						<td>
							<input type="file" name="localized_files[]" id="localized_files_id" accept="image/*" />
						</td>
						<td>
							<?php echo wp_kses_post( $non_localized_images ); ?>
							<input type="hidden" name="s_img[]" value="<?php echo esc_attr( $value ); ?>" />
							<input type="hidden" name="imgs_ids[]" value="<?php echo esc_attr( $key ); ?>" />
						</td>
					</tr>
				<?php
				}
			}
		?>
		</tbody>
	</table>
	<?php } ?>
</div>
