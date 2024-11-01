<?php
/**
 * Provide a admin area view for the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */

?>
<div class="wrap">
<?php require_once( WP_PLUGIN_DIR . '/straker-translations/admin/partials/messages/sandbox-message.php'); ?>
	<h1><?php esc_attr_e( 'Re Import Content', 'straker-translations' ); ?></h1>
	<div id="st-msg" class="below-h2"></div>
	<div class=st-hr>
		<?php
			if ( filter_has_var( INPUT_GET, "jk" ) ) {
				$job_key = filter_input( INPUT_GET, 'jk', FILTER_SANITIZE_STRING );
				?>
				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="re_import_content" name="re_import_content" >
					<p class="search-box">
						<input type="hidden" id="target_post_id" name="target_post_id" value="" />
					</p>
					<p class="st-p-font">
					<?php esc_attr_e( 'Import Content Manually', 'straker-translations' ); ?>
					</p>
					<table class="wp-list-table widefat fixed striped post_pages">
						<thead>
							<tr>
								<th class="col" id="post_status" scope="col"><?php esc_attr_e( 'Title', 'straker-translations' ); ?></th>
								<th class="col" id="meta_value" scope="col"><?php esc_attr_e( 'Type', 'straker-translations' ); ?></th>
								<th class="col" id="content" scope="col"><?php esc_attr_e( 'Content', 'straker-translations' ); ?></th>
							</tr>
						</thead>
						<tbody>
					<?php
						$new_posts = $this->straker_content_query( $this->straker_imported_links( $job_key ) );
					if ( $new_posts->have_posts() ) {
						while ( $new_posts->have_posts() ) {
							$new_posts->the_post();
							?>
								<tr class="type-post format-standard">
									<td class="title column-title page-title">
										<?php
										$lang_code      = get_post_meta( get_the_ID(), Straker_Translations_Config::straker_meta_locale );
										$lang_meta_name = Straker_Language::straker_language_meta( 'code', $lang_code[0] );
										?>
										<img style="vertical-align:middle" src="<?php echo esc_url( $this->flags_path ) . esc_html( $lang_code[0] ); ?>.png">&nbsp;&nbsp;<?php echo esc_html( $lang_meta_name['name'] ); ?><br />
										<a href="<?php echo esc_url( get_edit_post_link() ); ?>" target="_blank"><?php the_title(); ?></a>
									</td>
									<td><?php echo esc_html( ucfirst( get_post_type() ) ); ?></td>
									<td><?php echo esc_html( mb_substr( get_the_excerpt(), 0, 25 ) ) . '...'; ?></td>
								</tr>
						<?php
						}
						wp_reset_postdata();
					}
					?>
						</tbody>
					</table>
					<br />
					<p class="st-p-font">
						<?php esc_attr_e( 'Import Content From API', 'straker-translations' ); ?>
					</p>
					<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" name="straker_import_translation_form" id="straker_import_translation_form" method="post">
						<input type="hidden" name="action" value="straker_import_translation">
						<input type="hidden" name="jk" value="<?php echo esc_attr( $job_key ); ?>">
						<input type="hidden" name="re_import" value="true">
						<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Re Import All Content From API', 'straker-translations' ); ?>" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to re-import this job?  It will overwrite existing pages and posts with new content.', 'straker-translations' ); ?>');">
					</form>
					<p class="submit">
						<a class="q-cancel-link button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=st-jobs&jk=' . $job_key . '&pr=ready' ) ); ?>"><?php echo esc_html( __( 'Back', 'straker-translations' ) ); ?></a>&nbsp;&nbsp;
					</p>
				</form>
			<?php } ?>
	</div>
</div>
