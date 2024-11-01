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
	global $wp;
	// This file contains the source, target languages and form code.
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/straker-translations-admin-order-conf.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) .'class-straker-translation-order-page-list-table-ajax.php';

	if (  filter_has_var( INPUT_POST, 'st_trans_all' ) ) {

		$tokens          = array();
		$args            = array();
		$post_types      = $this->straker_posts_types;
		$post_status     = $this->straker_posts_status;
		$post_types_filters = array('post_types'=>
		array(
			'filter'    => FILTER_SANITIZE_STRING,
			'flags'     => FILTER_REQUIRE_ARRAY
			),
		);
		$post_status_filters = array('post_status'=>
		array(
			'filter'    => FILTER_SANITIZE_STRING,
			'flags'     => FILTER_REQUIRE_ARRAY
			),
		);
		$selected_types  = filter_has_var( INPUT_POST, 'post_types' ) ? filter_input_array( INPUT_POST, $post_types_filters ) : array();
		$selected_status = filter_has_var( INPUT_POST, 'post_status' ) ? filter_input_array( INPUT_POST, $post_status_filters ) : array();

		$args            = array(
			'orderby'        => 'post_title',
			'order'          => 'ASC',
			'posts_per_page' =>  PHP_INT_MAX,
			'post_status'    => $selected_status['post_status'],
			'post_type'      => $selected_types['post_types'],
			'meta_key'       => Straker_Translations_Config::straker_meta_locale,
			'meta_value'     => $this->straker_default_language['code'],
		);
		$results = new WP_Query( $args );

		if ( $results->have_posts() ) {

			?>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<label>
								<?php esc_attr_e( 'Number of items', 'straker-translations' ); ?>
								</label>
							</th>
							<td>
								<label class="st-total-items">
								<?php echo esc_html( $results->post_count ); ?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				<?php
				$post_stats = array();
				while ( $results->have_posts() ) {

					$results->the_post();
					$tokens[] = get_the_ID();

					printf( '<input type="hidden" name="post_page[]" id="post_page-%d" value="%d" /> ', esc_html( get_the_ID() ), esc_html( get_the_ID() ) );
				}
				wp_reset_postdata();
				?>
				<table style=" border-bottom : 1px solid #000 !important; padding-bottom: 15px;">
				<?php

				foreach ( $selected_types['post_types'] as $key => $value ) {
					$get_locale_posts       = get_posts(
						array(
							'post_type'      => $value,
							'posts_per_page' => PHP_INT_MAX,
							'post_status'    => $selected_status['post_status'],
							'suppress_filters' => false,
							'meta_query'     => array(
								array(
									'key'   => Straker_Translations_Config::straker_meta_locale,
									'value' => $this->straker_default_language['code'],
								),
							),
						)
					);
					$post_obj_type = get_post_type_object( $value );
					$post_count    = count( $get_locale_posts );
					$post_stats[]  = $post_count;
					?>
					<tr>
					<?php
						printf( '<td class="st-order-stats"><label class="st-total-%s">%s</label></td>', esc_html( $value ), esc_html( $post_count ) );
						printf( '<td class="st-order-stats"><label>%s selected </label></td>', esc_html( $post_obj_type->labels->name ) );
					?>
					</tr>
					<?php } ?>
				</table>
				<div id="st-all-cart-dialog-confirm" style="display: none;">
					<p>Are you sure you want to remove this item from cart?</p>
				</div>
				<div id="st-all-cart-minimum-dialog-confirm" style="display: none;">
					<p>There must be atleast one item in the selection</p>
				</div>
				<?php


				printf( '<span class="st-order-stats"><label class="st-total-selected">%d</label> Total Selected </span>', esc_html( array_sum( $post_stats ) ) );

				$order_list_table = new Straker_Translation_Order_Page_List_Table_Ajax( $this->plugin_name );
				$order_list_table->set_ids( $tokens );
				$order_list_table->set_types( $selected_types['post_types'] );
				$order_list_table->prepare_items();

				printf( '<input type="hidden" name="status_query_args" id="st_wp_posts_ids" value="%s" />', esc_html( implode( ',', $tokens ) ) );
				printf( '<input type="hidden" name="types_query_args" id="st_wp_query_types" value="%s" />', esc_html( implode( ',', $selected_types['post_types'] ) ) );
				printf( '<input type="hidden" name="page" value="%s" />', filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) );
				$order_list_table->display();

		} else {
			wp_redirect( esc_url( admin_url( 'admin.php?page=st-translation&msg=failed&ac=empty_translation' ) ) );
			exit();
		}
	} else {
		wp_redirect( esc_url( admin_url( 'admin.php?page=st-translation&msg=failed&ac=empty_translation' ) ) );
		exit();
	}
?>
		<p class="submit">
			<a class="q-cancel-link button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=st-translation' ) ); ?>"><?php echo esc_html( __( 'Back', 'straker-translations' ) ); ?></a>&nbsp;&nbsp;
			<?php if ( $results->have_posts() ) { ?>
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_attr( __( 'Get a Quote', 'straker-translations' ) ); ?>" />
			<?php } ?>
		</p>
	</form>
</div>
