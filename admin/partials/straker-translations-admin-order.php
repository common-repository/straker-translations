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

	$args            = array();
	$post_types      = $this->straker_posts_types;
	$post_status     = $this->straker_posts_status;
	$selected_types  = array();
	$selected_status = array();
	$updated_cart    = false;

if ( filter_input( INPUT_POST, 'st_multi_cart_ids', FILTER_SANITIZE_STRING ) ) {
	$translation_cart_ids = sanitize_text_field( filter_input( INPUT_POST, 'st_multi_cart_ids', FILTER_SANITIZE_STRING ) );
	$post_meta_response   = Straker_Translations_Cart_Handling::add_item_into_cart( $translation_cart_ids );
	if ( $post_meta_response ) {
			$updated_cart = explode( ',', get_option( Straker_Translations_Config::straker_option_translation_cart ) );
	}
} else {
	$updated_cart = ( get_option( Straker_Translations_Config::straker_option_translation_cart ) ) ? explode( ',', get_option( Straker_Translations_Config::straker_option_translation_cart ) ) : false;
}

if ( $updated_cart ) {

	// This file contains the source, target languages and form code.
	include_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/straker-translations-admin-order-conf.php';

	$args = array(
		'post__in'       => $updated_cart,
		'orderby'        => 'post_title',
		'order'          => 'ASC',
		'post_type'      => $post_types,
		'posts_per_page' => PHP_INT_MAX,
	);

	$results = new WP_Query( $args );

	if ( $results->have_posts() ) {

		while ( $results->have_posts() ) {

			$results->the_post();
			$tokens[] = get_the_ID();

			printf( '<input type="hidden" name="post_page[]" id="post_page-%d" value="%d" /> ', esc_html( get_the_ID() ), esc_html( get_the_ID() ) );
		}
		wp_reset_postdata();

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

		$trans_cart_list_table = new Straker_Translation_Cart_Order_Page_List_Table_Ajax( $this->plugin_name );
		$trans_cart_list_table->prepare_items();

		printf( '<input type="hidden" name="page" value="%s" />', filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING )  );
		$trans_cart_list_table->display();
	} else {
		wp_redirect( esc_url( admin_url( 'admin.php?page=st-translation&msg=failed&ac=trans_cart_empty' ) ) );
		exit();
	}
} else {
	wp_redirect( esc_url( admin_url( 'admin.php?page=st-translation&msg=failed&ac=trans_cart_empty' ) ) );
	exit;
}
?>
		<div id="st-cart-dialog-confirm" style="display: none;">
			<p>Are you sure you want to remove this item from cart?</p>
		</div>

		<p class="submit">
			<a class="q-cancel-link button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=st-translation' ) ); ?>"><?php echo esc_html( __( 'Back', 'straker-translations' ) ); ?></a>&nbsp;&nbsp;
			<?php if ( $updated_cart ) { ?>
			<input type="hidden" name="st_translation_cart" value="true" />
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_attr( __( 'Get a Quote', 'straker-translations' ) ); ?>" />
			<?php } ?>
		</p>
	</form>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="clear_translation_cart" name="clear_translation_cart" style="float: right;position: relative;top: -52px;">
		<?php wp_nonce_field( 'straker-translations-clear-cart', 'straker-translations-clear-cart-nonce' ); ?>
		<input type="hidden" name="action" value="straker_clear_tranbslation_cart">
		<button type="submit" class="button" id="clear_trans_cart_btn" formnovalidate="true"> <?php echo esc_html( __( 'Empty Translation Cart', 'straker-translations' ) ); ?> </button>
	</form>
</div>
