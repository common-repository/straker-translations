<?php
/**
 * Provide a admin area view for the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */

?>
	<?php require_once( WP_PLUGIN_DIR . '/straker-translations/admin/partials/messages/sandbox-message.php' );  ?>
	<h1><?php esc_attr_e( 'Job Details', 'straker-translations' ); ?></h1>
	<?php require_once( WP_PLUGIN_DIR . '/straker-translations/admin/partials/messages/general-message.php' ); ?>
	<?php
		if ( filter_has_var( INPUT_GET, "jk" ) ) {

			$job_key = filter_input( INPUT_GET, 'jk', FILTER_SANITIZE_STRING );
			$data = $this->straker_get_api_job( $job_key );
			$source_langguage = Straker_Language::straker_language_meta( 'code', $data['sl'] );
		?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label>
								<?php esc_attr_e( 'Job Reference', 'straker-translations' ); ?>
							</label>
						</th>
						<td>
							<?php echo esc_html( $data['tj_number'] ); ?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>
								<?php esc_attr_e( 'Source Language', 'straker-translations' ); ?>
							</label>
						</th>
						<td>
							<div class="st-lang">
								<fieldset>
									<item>
											<label style="background: url('<?php echo esc_url( $this->flags_path ) . esc_attr( $source_langguage['code'] ); ?>.png') left no-repeat;">
											<?php echo esc_html( $source_langguage['name'] ); ?>
											<?php if ( $source_langguage['name'] !== $source_langguage['native_name'] ) { ?>
											<small class="dd-desc"><?php echo ' - ' . esc_html( $source_langguage['native_name'] ); ?>
											<?php } ?>
										</label>
									</item>
								</fieldset>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label><?php esc_attr_e( 'Target Language', 'straker-translations' ); ?>
						</th>
						<td>
							<div class="st-lang">
								<fieldset>
								<?php
									$langs = str_getcsv( $data['tl'] );
									foreach ( $langs as $lang ) {
										$lang_meta = Straker_Language::straker_language_meta( 'code', $lang );
										echo '<item>';
										echo '<label style="background: url(' . esc_url( $this->flags_path ) . esc_attr( $lang ) . '.png) left no-repeat;">' . esc_html( $lang_meta['name'] ) . ' <small class="dd-desc"> - ' . esc_html( $lang_meta['native_name'] ) . '</small></label>';
										echo '</item>';
									}
								?>
								</fieldset>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>
								<?php esc_attr_e( 'Date Quoted', 'straker-translations' ); ?>
							</label>
						</th>
						<td>
							<?php echo esc_html( $data['created_at'] ); ?>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label>
								<?php esc_attr_e( 'Job Status', 'straker-translations' ); ?>
							</label>
						</th>
						<td>

							<?php echo esc_html( $this->straker_api_status( $data['status'], $data['quotation'] ) ); ?>
						</td>
					</tr>
				</tbody>
			</table>
			<?php if ( 'COMPLETED' === $data['status'] ) { ?>
				<p class="st-p-font">
					<?php esc_attr_e( 'Download Translation', 'straker-translations' ); ?>
				</p>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<th class="" scope="col"><?php esc_attr_e( 'Target Language', 'straker-translations' ); ?></th>
						<th class="" scope="col"><?php esc_attr_e( 'XML File', 'straker-translations' ); ?></th>
					</thead>
					<tbody>
							<?php
								$trans_data = get_option( Straker_Translations_Config::straker_option_translation_resx . $data['tj_number'] );
							if ( ! $trans_data ) {
								$trans_data = $this->straker_download_resx( $data['translated_file'], $data['tj_number'], $data['job_key'], $this->straker_default_language['name'] );
							}
							?>
							<tr>
								<td>
									<?php
										$langs = str_getcsv( $data['tl'] );
									foreach ( $langs as $lang ) {
										$lang_meta = Straker_Language::straker_language_meta( 'code', $lang );
										echo '<a st-data-tooltip title="' . esc_attr( str_replace( '_', ' ', $lang ) ) . '" href="#" ><img src="' .esc_url( $this->flags_path ) . esc_attr( $lang ) . '.png" style="vertical-align:middle"></a>&nbsp;&nbsp' . esc_html( $lang_meta['name'] ) . '<br />';
									}
									?>
								</td>
								<td>
									<?php foreach ( $trans_data['xml_files'] as $file_key => $xml_file ) { ?>
									<a class="" href="
										<?php
											echo esc_url( wp_get_attachment_url( $xml_file ) );
										?>
										"download="<?php basename( get_attached_file( $xml_file ) ); ?>"><?php echo esc_html( basename( get_attached_file( $xml_file ) ) ); ?></a><br />
									<?php } ?>
								</td>
							</tr>
					</tbody>
				</table>
			<?php
				}

				$results = $this->straker_content_query( $this->straker_job_links( $data['job_key'] ) );

				if ( $results->have_posts() ) {
					?>
					<table class="form-table">
						<tbody>
								<th scope="row">
									<label>
										<?php esc_attr_e( 'Number of items', 'straker-translations' ); ?>
									</label>
								</th>
								<td>
									<?php
										$count = $results->post_count;
										echo esc_html( $count );
										?>
								</td>
							</tr>
						</tbody>
					</table>
				<?php
					}
					if ( ! filter_has_var( INPUT_GET, "pr" ) ) { ?>
						<table class="wp-list-table widefat fixed striped posts">
							<thead>
								<tr>
									<th scope="col" class="manage-column column-comments">&nbsp;</th>
									<th scope="col" id="title" class="column-title"><?php esc_attr_e( 'Title', 'straker-translations' ); ?></th>
									<th scope="col" id="content" class="manage-column"><?php esc_attr_e( 'Type', 'straker-translations' ); ?></th>
									<th scope="col" id="content" class="manage-column"><?php esc_attr_e( 'Content', 'straker-translations' ); ?></th>
									<th scope="col" id="categories" class="manage-column"><?php esc_attr_e( 'Date Published', 'straker-translations' ); ?></th>
								</tr>
							</thead>
							<tbody id="the-list">
								<?php
								$count = 0;
								while ( $results->have_posts() ) {
									$count++;
									$results->the_post();
									?>
									<tr class="type-post format-standard">
										<td>
												<?php echo esc_html( $count ); ?>
										</td>
										<td class="title column-title page-title">
											<a href="<?php echo esc_url( get_edit_post_link() ); ?>" target="_blank"><?php the_title(); ?></a>
										</td>
										<td>
												<?php echo esc_html( ucfirst( get_post_type( get_the_ID() ) ) ); ?>
										</td>
										<td>
												<?php echo esc_html( mb_substr( get_the_excerpt(), 0, 70 ) . '...' ); ?>
										</td>
										<td>
												<?php echo get_the_date(); ?>
										</td>
									</tr>
									<?php } wp_reset_postdata(); ?>
							</tbody>
						</table>
						<br />
					<?php
					}

					if ( filter_has_var( INPUT_GET, "pr" ) ) { ?>
						<p class="st-p-font">
							<?php esc_attr_e( 'Translated Content', 'straker-translations' ); ?>
						</p>
						<table class="wp-list-table widefat fixed striped posts">
							<thead>
								<tr>
									<th scope="col" id="title" class="column-title" width=""><?php esc_attr_e( 'Target Title', 'straker-translations' ); ?></th>
									<th scope="col" id="title" class="column-title" width=""><?php esc_attr_e( 'Source Title', 'straker-translations' ); ?></th>
									<th scope="col" id="content" class="manage-column" width="107px"><?php esc_attr_e( 'Type', 'straker-translations' ); ?></th>
									<th scope="col" id="content" class="manage-column" width="665px"><?php esc_attr_e( 'Content', 'straker-translations' ); ?></th>
									<th scope="col" id="content" class="manage-column" width=""><?php esc_attr_e( 'Date Source Published', 'straker-translations' ); ?></th>
								</tr>
							</thead>
							<tbody id="the-list">
								<tbody>
									<?php
									$target_posts = $this->straker_content_query( $this->straker_imported_links( $data['job_key'] ) );
									if ( $target_posts->have_posts() ) {
										while ( $target_posts->have_posts() ) {
											$target_posts->the_post();
											$lang_code      = get_post_meta( get_the_ID(), Straker_Translations_Config::straker_meta_locale );
											$lang_meta_name = Straker_Language::straker_language_meta( 'code', $lang_code[0] );
											$source_post_id = get_post_meta( get_the_ID(), 'straker_default_' . $lang_meta_name['wp_locale'], true );
											?>
											<tr class="type-post format-standard">
												<td class="title column-title page-title">
													<img style="vertical-align:middle" st-data-tooltip title="<?php echo esc_attr( str_replace( '_', ' ', $lang_code[0] ) ); ?>" src='<?php echo esc_url( $this->flags_path ) . esc_attr( $lang_code[0] ); ?>.png'>&nbsp;&nbsp;<?php echo esc_html( $lang_meta_name['name'] ); ?><br  />
													<a href="<?php echo esc_url( get_edit_post_link() ); ?>" target="_blank"><?php the_title(); ?></a>
												</td>
												<td class="title column-title page-title">
													<a href="<?php echo esc_url( get_edit_post_link( $source_post_id ) ); ?>" target="_blank"><?php echo esc_html( get_the_title( $source_post_id ) ); ?></a>
												</td>
												<td>
													<?php echo esc_html( ucfirst( get_post_type( get_the_ID() ) ) ); ?>
												</td>
												<td>
													<?php echo esc_html( mb_substr( get_the_excerpt(), 0, 65 ) . '...' ); ?>
												</td>
												<td>
													<?php echo get_the_date( '', $source_post_id ); ?>
												</td>
											</tr>
											<?php
										} wp_reset_postdata();
									}
									?>
							</tbody>
						</table>
						<br />
					<?php } ?>
			<p>
				<a class="q-cancel-link button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=st-jobs' ) ); ?>"><?php echo esc_html( __( 'Back', 'straker-translations' ) ); ?></a>
				<?php
				if ( 'COMPLETED' === $data['status'] ) {
					if ( $this->straker_imported_links( $job_key ) ) {
						?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=st-reimport&jk=' . $job_key ) ); ?>" class="button button-primary"><?php esc_attr_e( 'Import', 'straker-translations' ); ?></a>
					<?php } ?>
				<?php } ?>
				<?php if ( 'QUEUED' === $data['status'] ) { ?>
					<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" name="straker_cancel_form" id="straker_cancel_form" method="post">
						<?php wp_nonce_field( 'straker-translations-cancel-job', 'straker-translations-cancel-job-nonce' ); ?>
						<input type="hidden" name="action" value="straker_cancel_job">
						<input type="hidden" name="tj" value="<?php echo esc_attr( $data['tj_number'] ); ?>">
						<input type="hidden" name="jk" value="<?php echo esc_attr( $job_key ); ?>">
						<input type="submit" name="submit" id="submit" class="button " value="Cancel Job" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to cancel this job?', 'straker-translations' ); ?>');">
					</form>
				<?php } ?>
			</p>
		<?php
		}
	?>