<?php
/**
 * A Wp_List_Table implementation for listing posts and pages.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/admin
 */

/**
 * The Order Page List.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Translation_Order_Page_List_Table_Ajax extends Straker_Translations_Ajax_List_Table {


	/**
	 *  The Query Args for the WP_Query.
	 *
	 * @access  private
	 * @var     array  The  Query Args for the WP_Query.
	 */
	private $query_ids;

	/**
	 *  The Query TYPES for the WP_Query.
	 *
	 * @access  private
	 * @var     array  The  Query Args for the WP_Query.
	 */
	private $query_types;

	/**
	 * The plugin's text domain.
	 *
	 * @access  private
	 * @var     string  The plugin's text domain. Used for localization.
	 */
	private $text_domain;

	public function __contruct( $text_domain ) {

		parent::__construct(
			array(
				'singular' => 'st_trans_order',
				'plural'   => 'st_trans_orders',
				'ajax'     => true,
			)
		);
		$this->text_domain = $text_domain;

	}

	/**
	 * Set the Query Arguments for the WP_QUERY.
	 */
	public function set_ids( $args ) {
		$this->query_ids = $args;
	}


	/**
	 * Get the Query Arguments for the WP_QUERY.
	 */
	public function get_ids() {
		return $this->query_ids;
	}

	/**
	 * Set the Query Arguments for the WP_QUERY.
	 */
	public function set_types( $args ) {
		$this->query_types = $args;
	}


	/**
	 * Get the Query Arguments for the WP_QUERY.
	 */
	public function get_types() {
		return $this->query_types;
	}
	private function check_acf_data_chb() {

		if ( filter_has_var(INPUT_GET, 'acf_data_checked') ) {
			if ( 'true' === filter_input(INPUT_GET, "acf_data_checked", FILTER_SANITIZE_STRING) ) {
				return 'show';
			} elseif ( 'false' === filter_input(INPUT_GET, "acf_data_checked", FILTER_SANITIZE_STRING) ) {
				return 'hide';
			}
		}
	}

	private function check_yoast_data_chb() {

		if ( filter_has_var(INPUT_GET, 'yoast_data_checked') ) {
			if ( 'true' === filter_input(INPUT_GET, "yoast_data_checked", FILTER_SANITIZE_STRING) ) {
				return 'show';
			} elseif ( 'false' === filter_input(INPUT_GET, "yoast_data_checked", FILTER_SANITIZE_STRING) ) {
				return 'hide';
			}
		}
	}

	public function column_default( $item, $column_name ) {
		$translated_tooltip = sprintf( __( 'Remove from selection', '%s' ), $this->text_domain );
		$translated_acf     = sprintf( __( 'Advance Custom Field source data created', '%s' ), $this->text_domain );
		$translated_yoast   = sprintf( __( 'Yoast source data created', '%s' ), $this->text_domain );
		switch ( $column_name ) {
			case 'post_title':
			case 'post_status':
				if ( 'publish' === $item->post_status ) {
					return ucfirst( 'Published' );
				} else {
					return ucfirst( $item->post_status );
				}
			case 'post_type':
				return ucfirst( $item->post_type );
			case 'st_post_acf_data':
				if ( Straker_Plugin::plugin_exist( 'acf' ) ) {
					if ( count( Straker_Plugin::straker_acf_plugin_check( $item->ID ) ) > 0 ) {
						if ( 'show' === $this->check_acf_data_chb() ) {
							return '<img st-data-tooltip title="' . $translated_acf . '" width="25px" src="' . plugins_url( 'admin/img/green-mark.svg', dirname( __FILE__ ) ) . '" class="st_acf_img" alt="acf data">';
						} elseif ( 'hide' === $this->check_acf_data_chb() ) {
							return '<img st-data-tooltip title="' . $translated_acf . '" width="25px" src="' . plugins_url( 'admin/img/green-mark.svg', dirname( __FILE__ ) ) . '" class="st_acf_img" alt="acf data" style="display:none;">';
						} else {
							return '<img st-data-tooltip title="' . $translated_acf . '" width="25px" src="' . plugins_url( 'admin/img/green-mark.svg', dirname( __FILE__ ) ) . '" class="st_acf_img" alt="acf data">';
						}
					} else {
						return '';
					}
				}
			case 'st_post_yoast_data':
				if ( Straker_Plugin::plugin_exist( 'wp-seo' ) ) {
					if ( count( Straker_Plugin::straker_wpseo_check( $item->ID ) ) > 0 ) {
						if ( 'show' === $this->check_yoast_data_chb() ) {
							return '<img st-data-tooltip title="' . $translated_yoast . '" width="25px" src="' . plugins_url( 'admin/img/green-mark.svg', dirname( __FILE__ ) ) . '" class="st_yoast_img" alt="yoast data">';
						} elseif ( 'hide' === $this->check_yoast_data_chb() ) {
							return '<img st-data-tooltip title="' . $translated_yoast . '" width="25px" src="' . plugins_url( 'admin/img/green-mark.svg', dirname( __FILE__ ) ) . '" class="st_yoast_img" alt="yoast data" style="display:none;">';
						} else {
							return '<img st-data-tooltip title="' . $translated_yoast . '" width="25px" src="' . plugins_url( 'admin/img/green-mark.svg', dirname( __FILE__ ) ) . '" class="st_yoast_img" alt="yoast data">';
						}
					} else {
						return '';
					}
				}
			case 'post_date':
				return date( 'Y/m/d h:i A', strtotime( $item->post_date ) );
			case 'remove_selection':
				return '<a href="#" class="st-delete-item" st-data-tooltip title="' . $translated_tooltip . '" data-type="' . $item->post_type . '" id="' . $item->ID . '"><img src="' . plugins_url( 'admin/img/remove_icon.gif', dirname( __FILE__ ) ) . '" /></a>';
			default:
				return $column_name;
		}
	}

	public function get_columns() {

		if ( Straker_Plugin::plugin_exist( 'wp-seo' ) && ! Straker_Plugin::plugin_exist( 'acf' ) ) {
			return array(
				'post_title'         => __( 'Title', $this->text_domain ),
				'post_type'          => __( 'Type', $this->text_domain ),
				'st_post_yoast_data' => __( 'Yoast', $this->text_domain ),
				'post_status'        => __( 'Status', $this->text_domain ),
				'post_date'          => __( 'Date Published', $this->text_domain ),
				'remove_selection'   => '',
			);
		} elseif ( ! Straker_Plugin::plugin_exist( 'wp-seo' ) && Straker_Plugin::plugin_exist( 'acf' ) ) {
			return array(
				'post_title'       => __( 'Title', $this->text_domain ),
				'post_type'        => __( 'Type', $this->text_domain ),
				'st_post_acf_data' => __( 'ACF', $this->text_domain ),
				'post_status'      => __( 'Status', $this->text_domain ),
				'post_date'        => __( 'Date Published', $this->text_domain ),
				'remove_selection' => '',
			);
		} elseif ( Straker_Plugin::plugin_exist( 'wp-seo' ) && Straker_Plugin::plugin_exist( 'acf' ) ) {
			return array(
				'post_title'         => __( 'Title', $this->text_domain ),
				'post_type'          => __( 'Type', $this->text_domain ),
				'st_post_acf_data'   => __( 'ACF', $this->text_domain ),
				'st_post_yoast_data' => __( 'Yoast', $this->text_domain ),
				'post_status'        => __( 'Status', $this->text_domain ),
				'post_date'          => __( 'Date Published', $this->text_domain ),
				'remove_selection'   => '',
			);
		} else {
			return array(
				'post_title'       => __( 'Title', $this->text_domain ),
				'post_type'        => __( 'Type', $this->text_domain ),
				'post_status'      => __( 'Status', $this->text_domain ),
				'post_date'        => __( 'Date Published', $this->text_domain ),
				'remove_selection' => '',
			);
		}
	}

	public function get_hidden_columns() {
		return array( 'post_modified' );
	}

	public function column_post_title( $item ) {

		$post_id = $item->ID;
		$post    = get_post( $post_id );

		if ( $post ) {
			return '<a href="' . get_edit_post_link( $post->ID ) . '" target="_blank">' . $post->post_title . '</a>';
		}

		return 'No post';
	}

	/**
	 * Returns the columns that can be used for sorting the list table data.
	 *
	 * @return array    The database columns that can be used for sorting the table.
	 */
	public function get_sortable_columns() {
		return array(
			'post_title' => array( 'post_title', true ),
			'post_date'  => array( 'post_date', true ),
			'post_type'  => array( 'post_type', true ),

		);
	}

	public function prepare_items() {

		$columns               = $this->get_columns();
		$hidden                = $this->get_hidden_columns();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$page_per_page         = 100;

		$query_args = array(
			'post__in'  => $this->get_ids(),
			'post_type' => $this->get_types(),
		);

		$query = new WP_Query( $query_args );

		$total_items = $query->found_posts;
		$offset  = filter_has_var(INPUT_GET, 'paged') ? max( 0, intval( filter_input(INPUT_GET, "paged", FILTER_SANITIZE_STRING) - 1 ) * $page_per_page ) : 0;
		$page = 1;

		if ( filter_has_var(INPUT_GET, 'paged') ) {
			$page = filter_input(INPUT_GET, "paged", FILTER_SANITIZE_STRING);
		}

		// Sorting.
		$order_by = 'post_title'; // Default sort ke.
		if ( filter_has_var(INPUT_GET, 'orderby') ) {
			// If the requested sort key is a valid column, use it for sorting.
			$orderby_filter = filter_input(INPUT_GET, "orderby", FILTER_SANITIZE_STRING);
			if ( in_array( $orderby_filter, array_keys( $this->get_sortable_columns() ), true ) ) {
				$order_by = $orderby_filter;
			}
		}
		$order = 'asc'; // Default sort order.
		if ( filter_has_var(INPUT_GET, 'order') ) {

			$order_filter = filter_input(INPUT_GET, "order", FILTER_SANITIZE_STRING);
			if ( in_array( $order_filter, array( 'asc', 'desc' ), true ) ) {
				$order = $order_filter;
			}
		}

		$extra_args = array(
			'posts_per_page' => $page_per_page,
			'offset'         => $offset,
			'paged'          => $page,
			'orderby'        => $order_by,
			'order'          => $order,
		);

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $page_per_page,
				'total_pages' => ceil( $total_items / $page_per_page ),
				'orderby'     => $order_by,
				'order'       => $order,
			)
		);
		$get_posts   = new WP_Query( array_merge( $query_args, $extra_args ) );
		$this->items = $get_posts->posts;
	}

		/**
		 * Display the table
		 * Adds a Nonce field and calls parent's display method
		 *
		 * @since 3.1.0
		 * @access public
		 */
	public function display() {

		wp_nonce_field( 'st-trans-order-ajax-nonce', '_st_trans_oredr_ajax_nonce' );

		echo '<input type="hidden" id="order" name="order" value="' . esc_attr( $this->_pagination_args['order'] ) . '" />';
		echo '<input type="hidden" id="orderby" name="orderby" value="' . esc_attr( $this->_pagination_args['orderby'] ) . '" />';

		parent::display();
	}

	/**
	 * Handle an incoming ajax request (called from admin-ajax.php)
	 *
	 * @since 3.1.0
	 * @access public
	 */
	public function ajax_response() {

		check_ajax_referer( 'st-trans-order-ajax-nonce', '_st_trans_oredr_ajax_nonce' );
		$total_items = '';
		$total_pages = '';

		$this->prepare_items();

		$placeholder = filter_input(INPUT_GET, 'no_placeholder', FILTER_SANITIZE_STRING);
		ob_start();
		if ( ! empty( $placeholder ) ) {
			$this->display_rows();
		} else {
			$this->display_rows_or_placeholder();
		}
		$rows = ob_get_clean();

		ob_start();
		$this->print_column_headers();
		$headers = ob_get_clean();

		ob_start();
		$this->pagination( 'top' );
		$pagination_top = ob_get_clean();

		ob_start();
		$this->pagination( 'bottom' );
		$pagination_bottom = ob_get_clean();

		$response                         = array( 'rows' => $rows );
		$response['pagination']['top']    = $pagination_top;
		$response['pagination']['bottom'] = $pagination_bottom;
		$response['column_headers']       = $headers;

		if ( isset( $total_items ) && ! empty( $total_items ) ) {
			$response['total_items_i18n'] = sprintf( _n( '1 item', '%s items', $total_items ), number_format_i18n( $total_items ) );
		}

		if ( isset( $total_pages ) && ! empty( $total_pages ) ) {
			$response['total_pages']      = $total_pages;
			$response['total_pages_i18n'] = number_format_i18n( $total_pages );
		}

		die( wp_json_encode( $response ) );
	}
}
