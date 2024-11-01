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
filter_has_var( INPUT_GET, "jk" );
$job_key = filter_input( INPUT_GET, 'jk', FILTER_SANITIZE_STRING );
$allowed_html_tags = array(
	'form' => array(
		'class' => array(),
		'action' => array(),
		'name' => array(),
		'id' => array(),
		'mehtod' => array()
	),
	'a' => array(
		'class' => array(),
		'href'  => array(),
		'rel'   => array(),
		'title' => array(),
		'target' => array()
	),
	'button' => array(
		'type' => array(),
		'name' => array(),
		'class' => array(),
		'id' => array(),
		'st-data-tooltip' => array(),
		'title' => array(),
	),
	'div' => array(
		'class' => array(),
		'title' => array(),
		'style' => array(),
	),
	'input' => array(
		'class' => array(),
		'id' => array(),
		'type' => array(),
		'value' => array(),
		'name' => array(),
		'title' => array(),
		'st-data-tooltip' => array()
	),
	'p' => array(
		'class' => array(),
		'style' => array()
	),
	'label' => array(
		'class' => array(),
		'style' => array(),
		'for' => array()
	),
	'div' => array(
		'class' => array(),
		'id' => array(),
		'style' => array()
	),
	'span' => array(
		'class' => array(),
		'id' => array()
	),
);
?>
<div class="wrap">
	<?php
	if ( filter_has_var( INPUT_GET, "jk" ) ) {
		if ( filter_has_var( INPUT_GET, "jk" ) && filter_has_var( INPUT_GET, "v" ) ) {
			require_once( WP_PLUGIN_DIR . '/straker-translations/admin/partials/straker-translations-admin-quote.php');
		} elseif ( filter_has_var( INPUT_GET, "jk" ) ) {

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'listing/single-job.php';
		}
	} else {
		?>
		<?php require_once( WP_PLUGIN_DIR . '/straker-translations/admin/partials/messages/sandbox-message.php' ); ?>
	<h1><?php esc_attr_e( 'My Jobs', 'straker-translations' ); ?></h1>
		<?php
		if ( false === $this->straker_auth ) {

			require_once( WP_PLUGIN_DIR . '/straker-translations/admin/partials/straker-translations-admin-register-button.php' );

		} else {
			?>
		<div class=st-hr>
				<?php
				$default_lang     = $this->straker_default_language;
				$added_langs      = $this->straker_added_language;
				$rewrite_opt      = Straker_Translations_Config::straker_rewrite_type();
				$straker_api_jobs = $this->straker_return_api_jobs();
				?>
			<div class="st-set-btns">
				<br />
				<?php
				$send_content_url = '';
				$error_msg_url    = '';
				if ( empty( $default_lang ) && empty( $added_langs ) ) {
					$send_content_url = true;
					$error_msg_url    = 'lang';

				} elseif ( ! empty( $default_lang ) && ! empty( $added_langs ) && '' === $rewrite_opt ) {
					$send_content_url = true;
					$error_msg_url    = 'url';
				} else {
					$send_content_url = false;
				}

				if ( $send_content_url && ! empty( $send_content_url ) ) {
					?>
						<p class="mj-btn">
							<a class="button button-primary" id="st-lang-url-err-msg" href="#" st-data-tooltip title="<?php echo esc_attr_e( 'Click here to get a quote for pages, posts and other editable web content.', 'straker-translations' ); ?>"><?php esc_attr_e( 'Send Web Content', 'straker-translations' ); ?></a>
						</p>
						<?php } else { ?>
				<p class="mj-btn">
					<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=st-translation' ) ); ?>" st-data-tooltip title="<?php echo esc_attr_e( 'Click here to get a quote for pages, posts and other editable web content.', 'straker-translations' ); ?>"><?php esc_attr_e( 'Send Web Content', 'straker-translations' ); ?></a>
				</p>
				<?php } ?>
				<p class="mj-btn cen-btn">
					<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=st-tm' ) ); ?>" st-data-tooltip title="<?php echo esc_attr_e( 'Click here to get a quote for images, .po files, or content in other file formats.', 'straker-translations' ); ?>"><?php esc_attr_e( 'Send Files/Assets', 'straker-translations' ); ?></a>
				</p>
				<div class="lang-error">
					<p class="description">
							<?php echo esc_attr_e( 'Setup has not been completed, please ', 'straker-translations' ); ?>
						<a href="
						<?php
						if ( 'lang' === $error_msg_url ) {
							echo esc_url( admin_url( 'admin.php?page=st-settings&tab=language_settings&ac=lang_setting&msg=failed' ) );
						} elseif ( 'url' === $error_msg_url ) {
							echo esc_url( admin_url( 'admin.php?page=st-settings&tab=url_settings&ac=url_setting&msg=failed' ) ); }
						?>
						">
								<?php echo esc_attr_e( 'click here', 'straker-translations' ); ?>
						</a>
							<?php echo esc_attr_e( ' to select your languages and url preferences.', 'straker-translations' ); ?>
					</p>
				</div>
			</div>
	</div>
			<?php
				$completed_jobs = array();
			if ( is_array( $straker_api_jobs ) ) {
				foreach ( $straker_api_jobs as $key => $value ) {
					if ( is_array( $value ) ) {
						foreach ( $value as $data ) {
							if ( ! $this->straker_imported_links( $data['job_key'] ) && 'COMPLETED' === $data['status'] ) {
								$completed_jobs[ $data['job_key'] ] = $data['job_key'];
							}
						}
					}
				}
			}
			?>
			<div class=st-hr>
				<p><h3><?php esc_attr_e( 'List Jobs:', 'straker-translations' ); ?></h3></p>
				<?php
					require_once( WP_PLUGIN_DIR . '/straker-translations//admin/partials/messages/general-message.php' );
					$api_jobs_status = $this->straker_get_total_jobs();
				?>
				<div class="st-tabs">
				<ul class="st-tab-links">
					<li class="
					<?php
					if ( ! isset( $api_jobs_status['completed'] ) ) {
						echo 'st-active';}
					?>
					"><a href="#tab1"
			<?php
			if ( isset( $api_jobs_status['awaiting_quote'] ) ) {
				?>
							class="blue" st-jobs-noti-bubble="<?php echo count( $api_jobs_status['awaiting_quote'] ); ?>" <?php } ?>><?php echo esc_attr_e( 'Quote Requested', 'straker-translations' ); ?></a></li>
					<li><a href="#tab2"
					<?php
					if ( isset( $api_jobs_status['ready'] ) ) {
						?>
							class="blue" st-jobs-noti-bubble="<?php echo count( $api_jobs_status['ready'] ); ?>" <?php } ?>><?php esc_attr_e( 'Order Now', 'straker-translations' ); ?></a></li>
					<li><a href="#tab3"
					<?php
					if ( isset( $api_jobs_status['in_progress'] ) ) {
						?>
							class="blue" st-jobs-noti-bubble="<?php echo count( $api_jobs_status['in_progress'] ); ?>" <?php } ?>><?php esc_attr_e( 'In Progress', 'straker-translations' ); ?></a></li>
					<li class="
					<?php
					if ( isset( $api_jobs_status['completed'] ) ) {
						echo 'st-active'; }
					?>
					"><a href="#tab4"
			<?php
			if ( ! empty( $completed_jobs ) ) {
				?>
							class="blue" st-jobs-noti-bubble="<?php echo count( $completed_jobs ); ?>" <?php } ?>><?php esc_attr_e( 'Completed', 'straker-translations' ); ?></a></li>
				</ul>
				<a href="#" class="st-refresh-a" onclick="location.reload(true); return false;">
					<img width="32" height="32" src="<?php echo esc_url( $this->plugin_absolute_path ) . '/admin/img/st-refresh-button-icon.png'; ?>" />
				</a>
				<div class="st-tab-content">
					<div id="tab1" class="st-tab
					<?php
					if ( ! isset( $api_jobs_status['completed'] ) ) {
						echo 'st-active';}
					?>
					">
						<?php if ( isset( $api_jobs_status['awaiting_quote'] ) && is_array( $straker_api_jobs ) ) { ?>
						<table class="wp-list-table widefat fixed striped">
							<thead>
								<th class="" scope="col"><?php esc_attr_e( 'Job Reference', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Source Language', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Target Language', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Number of items', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Status', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Date Submitted', 'straker-translations' ); ?></th>
								<th class="" scope="col"></th>
							</thead>
							<tbody>
								<?php
								foreach ( $straker_api_jobs as $key => $value ) {
									foreach ( $value as $data ) {
										if ( 'QUEUED' === $data['status'] && '' === $data['quotation'] ) {
											?>
										<tr>
											<td class=""><a href="admin.php?page=st-jobs&jk=<?php echo esc_attr( $data['job_key'] ); ?>" ><?php echo esc_html( $data['tj_number'] ); ?></a></td>
											<td class="">
												<?php
												$lang_meta = Straker_Language::straker_language_meta( 'code', $data['sl'] );
												echo '<label class="st-label" style="padding-left:30px; background: url(' . esc_url( $this->flags_path ) . esc_attr( $lang_meta['code'] ) . '.png) left no-repeat;">' . esc_html( $lang_meta['name'] ) . '</label>';
												?>
											</td>
											<td>
												<?php
												$langs = str_getcsv( $data['tl'] );
												foreach ( $langs as $lang ) {
													$lang_meta = Straker_Language::straker_language_meta( 'code', $lang );
													echo '<label class="st-label" style="padding-left:30px; background: url(' . esc_url( $this->flags_path ) . esc_attr( $lang_meta['code'] ) . '.png) left no-repeat;">' . esc_html( $lang_meta['name'] ) . '</label><br><br>';
												}
												?>
											</td>
											<td style="text-align:center;">
												<?php
												$post_ids = explode( ',', $data['token'] );
												echo count( $post_ids );
												?>
											</td>
											<td class=""><?php echo esc_html( $this->straker_api_status( $data['status'], $data['quotation'] ) ); ?></td>
											<td class=""><?php echo esc_html( $data['created_at'] ); ?></td>
											<td class=""><?php echo wp_kses_post( $this->straker_api_action( $data['status'], $data['quotation'], $data['job_key'] ) ); ?></td>
										</tr>
											<?php
										}
									}
								}
								?>
							</tbody>
						</table>
							<?php
						} else {
							echo esc_attr_e( 'No quotes requested', 'straker-translations' );
						}
						?>
					</div>
					<div id="tab2" class="st-tab">
						<?php if ( isset( $api_jobs_status['ready'] ) && is_array( $straker_api_jobs ) ) { ?>
						<table class="wp-list-table widefat fixed striped">
							<thead>
								<th class="" scope="col"><?php esc_attr_e( 'Job Reference', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Source Language', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Target Language', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Page / Post Title', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Status', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Date Submitted', 'straker-translations' ); ?></th>
								<th class="" scope="col"></th>
							</thead>
							<tbody>
								<?php
								foreach ( $straker_api_jobs as $key => $value ) {
									foreach ( $value as $data ) {
										if ( 'QUEUED' === $data['status'] && 'READY' === $data['quotation'] ) {
											?>
										<tr>
											<td class=""><a href="admin.php?page=st-jobs&jk=<?php echo esc_attr( $data['job_key'] ); ?>" ><?php echo esc_html( $data['tj_number'] ); ?></a></td>
											<td class="">
													<?php
													$lang_meta = Straker_Language::straker_language_meta( 'code', $data['sl'] );
													echo '<label class="st-label" style="padding-left:30px; background: url(' . esc_url( $this->flags_path ) . esc_attr( $lang_meta['code'] ) . '.png) left no-repeat;">' . esc_html( $lang_meta['name'] ) . '</label>';
													?>
											</td>
											<td>
													<?php
													$langs = str_getcsv( $data['tl'] );
													foreach ( $langs as $lang ) {
														$lang_meta = Straker_Language::straker_language_meta( 'code', $lang );
														echo '<label class="st-label" style="padding-left:30px; background: url(' . esc_url( $this->flags_path ) . esc_attr( $lang_meta['code'] ) . '.png) left no-repeat;">' . esc_html( $lang_meta['name'] ) . '</label><br><br>';
													}
													?>
											</td>
											<td class="st-jb title column-title page-title">
													<?php
													$post_ids = explode( ',', $data['token'] );
													foreach ( $post_ids as $p_id ) {
														?>
													<a title="<?php echo esc_attr( get_the_title( $p_id ) ); ?>" href="<?php echo esc_url( get_edit_post_link( $p_id ) ); ?>" target="_blank"><?php echo esc_html( wp_trim_words( get_the_title( $p_id ), 2, '...' ) ); ?></a><br />
													<?php } ?>
											</td>
											<td class=""><?php echo esc_html( $this->straker_api_status( $data['status'], $data['quotation'] ) ); ?></td>
											<td class=""><?php echo esc_html( $data['created_at'] ); ?></td>
											<td class=""><?php echo wp_kses_post( $this->straker_api_action( $data['status'], $data['quotation'], $data['job_key'] ) ); ?></td>
										</tr>
											<?php
										}
									}
								}
								?>
							</tbody>
						</table>
							<?php
						} else {
							echo esc_attr_e( 'No jobs with Order Now status', 'straker-translations' );
						}
						?>
					</div>
					<div id="tab3" class="st-tab">
							<?php if ( isset( $api_jobs_status['in_progress'] ) && is_array( $straker_api_jobs ) ) { ?>
						<table class="wp-list-table widefat fixed striped">
							<thead>
								<th class="" scope="col"><?php esc_attr_e( 'Job Reference', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Source Language', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Target Language', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Page / Post Title', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Status', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Date Submitted', 'straker-translations' ); ?></th>
								<th class="" scope="col"></th>
							</thead>
							<tbody>
								<?php
								foreach ( $straker_api_jobs as $key => $value ) {
									foreach ( $value as $data ) {
										if ( 'IN_PROGRESS' === $data['status'] ) {
											?>
										<tr>
											<td class=""><a href="admin.php?page=st-jobs&jk=<?php echo esc_attr( $data['job_key'] ); ?>" ><?php echo esc_html( $data['tj_number'] ); ?></a></td>
											<td class="">
												<?php
												$lang_meta = Straker_Language::straker_language_meta( 'code', $data['sl'] );
												echo '<label class="st-label" style="padding-left:30px; background: url(' . esc_url( $this->flags_path ) . esc_attr( $lang_meta['code'] ) . '.png) left no-repeat;">' . esc_html( $lang_meta['name'] ) . '</label>';
												?>
											</td>
											<td>
												<?php
												$langs = str_getcsv( $data['tl'] );
												foreach ( $langs as $lang ) {
													$lang_meta = Straker_Language::straker_language_meta( 'code', $lang );
													echo '<label class="st-label" style="padding-left:30px; background: url(' . esc_url( $this->flags_path ) . esc_attr( $lang_meta['code'] ) . '.png) left no-repeat;">' . esc_html( $lang_meta['name'] ) . '</label><br><br>';
												}
												?>
											</td>
											<td class="st-jb title column-title page-title">
												<?php
												$post_ids = explode( ',', $data['token'] );
												foreach ( $post_ids as $p_id ) {
													?>
													<a title="<?php echo esc_attr( get_the_title( $p_id ) ); ?>" href="<?php echo esc_url( get_edit_post_link( $p_id ) ); ?>" target="_blank"><?php echo esc_html( wp_trim_words( get_the_title( $p_id ), 2, '...' ) ); ?></a><br />
												<?php } ?>
											</td>
											<td class=""><?php echo esc_html( $this->straker_api_status( $data['status'], $data['quotation'] ) ); ?></td>
											<td class=""><?php echo esc_html( $data['created_at'] ); ?></td>
											<td class=""><?php echo wp_kses_post( $this->straker_api_action( $data['status'], $data['quotation'], $data['job_key'] ) ); ?></td>
										</tr>
											<?php
										}
									}
								}
								?>
							</tbody>
						</table>
								<?php
							} else {
								echo esc_attr_e( 'No jobs in progress', 'straker-translations' );
							}
							?>
					</div>
					<div id="tab4" class="st-tab
					<?php
					if ( isset( $api_jobs_status['completed'] ) ) {
						echo 'st-active'; }
					?>
					">
							<?php if ( isset( $api_jobs_status['completed'] ) && is_array( $straker_api_jobs ) ) { ?>
						<table class="wp-list-table widefat fixed striped">
							<thead>
								<th class="" scope="col"><?php esc_attr_e( 'Job Reference', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Source Language', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Target Language', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Page / Post Title', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Status', 'straker-translations' ); ?></th>
								<th class="" scope="col"><?php esc_attr_e( 'Date Submitted', 'straker-translations' ); ?></th>
								<th class="" scope="col"></th>
							</thead>
							<tbody>
								<?php
								foreach ( $straker_api_jobs as $key => $value ) {
									foreach ( $value as $data ) {
										if ( 'COMPLETED' === $data['status'] ) {
											?>
										<tr>
											<td class=""><a href="admin.php?page=st-jobs&jk=<?php echo esc_attr( $data['job_key'] ); ?>" ><?php echo esc_html( $data['tj_number'] ); ?></a></td>
											<td class="">
												<?php
												$lang_meta = Straker_Language::straker_language_meta( 'code', $data['sl'] );
												echo '<label class="st-label" style="padding-left:30px; background: url(' . esc_url( $this->flags_path ) . esc_attr( $lang_meta['code'] ) . '.png) left no-repeat;">' . esc_html( $lang_meta['name'] ) . '</label>';
												?>
											</td>
											<td>
												<?php
												$langs = str_getcsv( $data['tl'] );
												foreach ( $langs as $lang ) {
													$lang_meta = Straker_Language::straker_language_meta( 'code', $lang );
													echo '<label class="st-label" style="padding-left:30px; background: url(' . esc_url( $this->flags_path ) . esc_attr( $lang_meta['code'] ) . '.png) left no-repeat;">' . esc_html( $lang_meta['name'] ) . '</label><br><br>';
												}
												?>
											</td>
											<td class="st-jb title column-title page-title">
												<?php
												$post_ids = explode( ',', $data['token'] );
												foreach ( $post_ids as $p_id ) {
													?>
													<a title="<?php echo esc_attr( get_the_title( $p_id ) ); ?>" href="<?php echo esc_url( get_edit_post_link( $p_id ) ); ?>" target="_blank"><?php echo esc_html( wp_trim_words( get_the_title( $p_id ), 2, '...' ) ); ?></a><br />
												<?php } ?>
											</td>
											<td class=""><?php echo  esc_html( $this->straker_api_status( $data['status'], $data['quotation'] ) ); ?></td>
											<td class=""><?php echo esc_html( $data['created_at'] ); ?></td>
											<td class=""><?php echo wp_kses( $this->straker_api_action( $data['status'], $data['quotation'], $data['job_key'] ), $allowed_html_tags); ?></td>
										</tr>
											<?php
										}
									}
								}
								?>
							</tbody>
						</table>
								<?php
							} else {
								echo esc_attr_e( 'No jobs completed', 'straker-translations' );

							}
							?>
					</div>
				</div>
			</div>
		</div>
			<?php
		}
	}
	?>
</div>
