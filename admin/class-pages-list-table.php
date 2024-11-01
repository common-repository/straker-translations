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
 * The Page List Table Class.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Pages_List_Table extends Straker_Translations_List_Table {

	/**
	 * The allowed html tags
	 *
	 * @since 3.1.0
	 * @var array
	 * @access public
	 */
	protected $allowed_html_tags = array(
		'span' => array(
			'class' => array(),
			'style' => array(),
			'title' => array(),
			'aria-hidden' => array()
		),
		'td' => array(
			'class' => array(),
			'id'  => array()
		),
		'th' => array(
			'class' => array(),
			'id'  => array(),
			'scope'  => array()
		),
		'a' => array(
			'class' => array(),
			'href'  => array(),
			'rel'   => array(),
			'title' => array(),
			'target' => array()
		),
		'button' => array(
			'class' => array(),
			'id' => array()
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
			'checked' => array(),
			'st-data-tooltip' => array()
		),
		'p' => array(
			'class' => array(),
			'style' => array()
		),
		'strong' => array(
			'class' => array(),
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

	/**
	 * The plugin's text domain.
	 *
	 * @access  private
	 * @var     string  The plugin's text domain. Used for localization.
	 */

	private $text_domain;
	/**
	 * All Posts types.
	 *
	 * @access  private
	 * @var     string  All posts types.
	 */
	private $all_post_types;

	/**
	 * The plugin's All Posts Status.
	 *
	 * @access  private
	 * @var     string  The plugin all posts status.
	 */
	private $all_posts_status;

	/**
	 * The plugin's default lang.
	 *
	 * @access  private
	 * @var     string  The plugin's default language.
	 */
	private $default_lang;

	/**
	 * The Option name of the Cart.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $cart_option_name    The name of the cart.
	 */

	private static $translation_cart_option = '';

	/**
	 * Initializes the WP_List_Table implementation.
	 *
	 * @param $text_domain  string  The text domain used for localizing the plugin.
	 */
	public function __construct( $text_domain ) {

		// Set parent defaults.
		parent::__construct(
			array(
				'singular' => 'post_page', // singular name of the listed records.
				'plural'   => 'post_pages', // plural name of the listed records.
				'ajax'     => false, // does this table support ajax?.
			)
		);

		$this->text_domain             = $text_domain;
		$this->default_lang            = Straker_Language::get_default_language();
		$this->all_post_types          = get_option( Straker_Translations_Config::straker_registered_posts );
		$this->all_posts_status        = array( 'publish', 'pending', 'draft', 'future', 'private' );
		self::$translation_cart_option = ( false !== get_option( Straker_Translations_Config::straker_option_translation_cart ) ) ? get_option( Straker_Translations_Config::straker_option_translation_cart ) : false;

	}

	/**
	 * Defines the database columns shown in the table and a
	 * header for each column. The order of the columns in the
	 * table define the order in which they are rendered in the list table.
	 *
	 * @return array    The database columns and their headers for the table.
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'post_title':
			case 'post_status':
				if ( $item->post_status === 'publish' ) {
					return ucfirst( 'Published' );
				} else {
					return ucfirst( $item->post_status );
				}

			case 'post_type':
				return ucfirst( $item->post_type );

			case 'post_locale':
				return '<img src="' . STRAKER_PLUGIN_ABSOLUTE_PATH . '/assets/img/flags/' . $this->default_lang['code'] . '.png" style="vertical-align:middle" st-data-tooltip title="' . $this->default_lang['name'] . '">';

			case 'post_date':
				return date( 'Y/m/d h:i A', strtotime( $item->post_date ) );

			case 'st_meta_value':
				$aMeta = Straker_Util::get_meta_by_value( $item->ID );
				if ( $aMeta ) {
					$flag = '';
					foreach ( $aMeta as $value ) {
						$alng  = Straker_Language::straker_language_meta( 'code', $value['code'] );
						$flag .= '<a st-data-tooltip style="margin-right:10px;" title="' . $alng['name'] . '" href="' . get_edit_post_link( $value['post_id'] ) . '" target="_self"><img src="' . STRAKER_PLUGIN_ABSOLUTE_PATH . '/assets/img/flags/' . $value['code'] . '.png" style="vertical-align:middle"></a>';
					};
					return $flag;
				} else {
					return ' ';
				}

			default:
				return '';
		}
	}

	/**
	 * Returns the names of columns that should be hidden from the list table.
	 *
	 * @return array    The database columns that should not be shown in the table.
	 */
	public function get_hidden_columns() {
		return array( 'post_modified' );
	}

	/**
	 * Returns the columns that can be used for sorting the list table data.
	 *
	 * @return array    The database columns that can be used for sorting the table.
	 */
	public function get_sortable_columns() {
		return array(
			'post_title' => array( 'post_title', true ),
			'post_type'  => array( 'post_type', true ),
			'post_date'  => array( 'post_date', false ),
		);
	}

	/**
	 * Message to be displayed when there are no items.
	 *
	 * @since 3.1.0
	 * @access public
	 */
	public function no_items() {
		echo wp_kses( 'No content is assigned to your source language. Please assign a source language to the pages you wish to translate under <strong>Settings > Language Management</strong>.', $this->allowed_html_tags );
	}

	private function get_target_language() {

		$straker_languages = get_option( 'straker_languages' );
		return $straker_languages['tl'];
	}

	public function extra_tablenav( $which = '' ) {
		if ( $which === 'top' ) {
			$post_filter        = filter_has_var(INPUT_GET, 'post-type-filter') ? filter_input(INPUT_GET, "post-type-filter", FILTER_SANITIZE_STRING) : '';
			$post_status        = filter_has_var(INPUT_GET, 'post-status-filter') ? filter_input(INPUT_GET, "post-status-filter", FILTER_SANITIZE_STRING) : '';
			$post_is_translated = filter_has_var(INPUT_GET, 'post-is_translated-filter') ? filter_input(INPUT_GET, "post-is_translated-filter", FILTER_SANITIZE_STRING) : '';
			?>
			<div class="alignleft actions bulkactions">
				<select name="post-type-filter" id="post-type-filter">
					<option value="all"><?php echo esc_html( __( 'Filter by Post Types', 'straker-translations' ) ); ?></option>
						<?php
						foreach ( $this->all_post_types as $key ) {
							$selectd_type = ( $key === $post_filter ) ? 'selected' : '';
							$display_val  = get_post_type_object( $key );
							echo '<option value="' . esc_attr( $key ) . '" ' . esc_attr( $selectd_type ) . '>' . esc_html( $display_val->label ) . '</option>';
						}
						?>
				</select>
				<select name="post-status-filter" id="post-status-filter">
					<option value="all"><?php echo esc_html( __( 'Filter by Post Status', 'straker-translations' ) ); ?></option>
					<?php
					foreach ( $this->all_posts_status as $post_key ) {
						$selected_status  = ( $post_key === $post_status ) ? 'selected' : '';
						$post_status_text = ( $post_key === 'publish' ) ? 'Published' : ucfirst( $post_key );
						echo '<option value="' . esc_attr( $post_key ) . '" ' .esc_attr( $selected_status ) . '>' . esc_html( $post_status_text ) . '</option>';
					}
					?>
				</select>
				<?php
				$checkboxes         = '';
				$tl_language_values = '';

				foreach ( $this->get_target_language() as $tl_code ) {
					$alng                = Straker_Language::straker_language_meta( 'code', $tl_code );
					$checked             = in_array( (string)$tl_code, explode( ',', $post_is_translated ), true ) ? 'checked' : '';
					$tl_language_values .= ( $checked === 'checked' ) ? $alng['name'] . ', ' : false;
					$checkboxes         .= '<li><input type="checkbox" value="' . $tl_code . '" '. $checked .' />' . $alng['name'] . '</li>';
				}
				?>

				<div class="multi-select">
					<input id="language-filter" name="st-lang-silter" type="text" placeholder="Filter by Language" value="<?php echo esc_attr( substr( trim( $tl_language_values ), 0, -1 ) ); ?>" readonly="readonly">
					<div class="drop-down hide" id="st_trans_dashboard_lang_filter">
						<ul>
							<li><input type="checkbox" value="all" <?php echo ( strpos( $post_is_translated, 'all' ) > -1 ) ? 'checked' : false; ?>>Select All</li>
							<hr>
							<?php echo wp_kses( $checkboxes, $this->allowed_html_tags ); ?>

						</ul>
					</div>
				</div>

				<button id="st-translations-filter"class="button action">Filter</button>
			</div>
			<?php
		}
	}

	/**
	 * displaying checkbox for bulk action.
	 *
	 * @param array $item   The Item name.
	 */
	public function column_cb( $item ) {

		if ( false !== self::$translation_cart_option ) {
			if ( in_array( (string)$item->ID, explode( ',', self::$translation_cart_option ), true ) ) {
				return sprintf(
					'<div st-data-tooltip class="st-cart-img st-txt-center" title="%s"><a href="%s"><img class="st-cart-img" src="%s"></a></div>',
					__( ucfirst( $item->post_type ) . ' already in the translation cart.', 'straker-translations' ),
					admin_url( 'admin.php?page=st-translation-cart' ),
					STRAKER_PLUGIN_ABSOLUTE_PATH . '/admin/img/st-cart.png'
				);
			} else {
				return sprintf(
					'<input type="checkbox" name="%1$s[]" value="%2$s" id="st-order-%2$s" class= "trans_chkbox" />',
					/*$1%s*/$this->_args['singular'], // Let's simply repurpose the table's singular label ("post_page").
					/*$2%s*/ $item->ID// The value of the checkbox should be the record's id.
				);
			}
		} else {
			return sprintf(
				'<input type="checkbox" name="%1$s[]" value="%2$s" id="st-order-%2$s" class= "trans_chkbox" />',
				/*$1%s*/$this->_args['singular'], // Let's simply repurpose the table's singular label ("post_page").
				/*$2%s*/ $item->ID// The value of the checkbox should be the record's id.
			);
		}
	}

	/**
	 * Custom renderer for the post_title field.
	 *
	 * @param array $item   The database row being printed out.
	 */
	public function column_post_title( $item ) {

		$post_id = $item->ID;

		$post = get_post( $post_id );

		if ( $post ) {
			return '<a href="' . get_edit_post_link( $post->ID ) . '" target="_blank">' . $post->post_title . '</a>';
		}

		return __( 'No post', 'straker-translations' );
	}

	/**
	 * Defines the database columns shown in the table and a
	 * header for each column. The order of the columns in the
	 * table define the order in which they are rendered in the list table.
	 *
	 * @return array    The database columns and their headers for the table.
	 */
	public function get_columns() {
		$columns = array(
			'cb'          => 'checkbox', // Render a checkbox instead of text.
			'post_title'  => __( 'Title', 'straker-translations' ),
			'post_type'   => __( 'Type', 'straker-translations' ),
			'post_locale' => __( 'Language', 'straker-translations' ),
			'post_date'   => __( 'Date Published', 'straker-translations' ),
			'post_status' => __( 'Status', 'straker-translations' ),
			'st_meta_value'  => __( 'Translation', 'straker-translations' ),
		);

		return $columns;

	}

	/**
	 * Defines print cloumns headers
	 *
	 * @param bool $with_id   The With ID.
	 */
	public function print_column_headers( $with_id = true ) {

		list($columns, $hidden, $sortable, $primary) = $this->get_column_info();
		$server_host = filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);
		$server_uri = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );
		$is_url_ssl = is_ssl() ? 'https://' : 'http://';
		$current_url = set_url_scheme( $is_url_ssl . $server_host . $server_uri  );

		$current_url = remove_query_arg( 'paged', $current_url );

		if ( filter_has_var(INPUT_GET, 'orderby') ) {
			$current_orderby = filter_input(INPUT_GET, "orderby", FILTER_SANITIZE_STRING);
		} else {
			$current_orderby = '';
		}

		if ( filter_has_var(INPUT_GET, 'order') && 'desc' === filter_input(INPUT_GET, "order", FILTER_SANITIZE_STRING) ) {
			$current_order = 'desc';
		} else {
			$current_order = 'asc';
		}

		if ( ! empty( $columns['cb'] ) ) {
			static $cb_counter = 1;
			$columns['cb']     = '<label class="screen-reader-text" for="cb-select-all-' . $cb_counter . '">' . __( 'Select All' ) . '</label>'
				. '<input id="stCheckAllCheckBox" class="" type="checkbox" st-data-tooltip title="' . __( 'Select/Deselect All Visible', 'straker-translations' ) . '" />';
			$cb_counter++;
		}

		foreach ( $columns as $column_key => $column_display_name ) {
			$class = array( 'manage-column', "column-$column_key" );

			if ( in_array( $column_key, $hidden, true ) ) {
				$class[] = 'hidden';
			}

			if ( 'cb' === $column_key ) {
				$class[] = 'check-column';
			} elseif ( in_array( $column_key, array( 'posts', 'comments', 'links' ), true ) ) {
				$class[] = 'num';
			}

			if ( $column_key === $primary ) {
				$class[] = 'column-primary';
			}

			if ( isset( $sortable[ $column_key ] ) ) {
				list($orderby, $desc_first) = $sortable[ $column_key ];

				if ( $current_orderby === $orderby ) {
					$order   = 'asc' === $current_order ? 'desc' : 'asc';
					$class[] = 'sorted';
					$class[] = $current_order;
				} else {
					$order   = $desc_first ? 'desc' : 'asc';
					$class[] = 'sortable';
					$class[] = $desc_first ? 'asc' : 'desc';
				}

				$column_display_name = '<a href="' . esc_url( add_query_arg( compact( 'orderby', 'order' ), $current_url ) ) . '"><span>' . $column_display_name . '</span><span class="sorting-indicator"></span></a>';
			}

			$tag   = ( 'cb' === $column_key ) ? 'td' : 'th';
			$scope = ( 'th' === $tag ) ? 'scope="col"' : '';
			$id    = $with_id ? "id='$column_key'" : '';

			if ( ! empty( $class ) ) {
				$class = "class='" . join( ' ', $class ) . "'";
			}

			echo wp_kses( "<$tag $scope $id $class>$column_display_name</$tag>", $this->allowed_html_tags );
		}
	}


	/**
	 * Populates the class fields for displaying the list of post and pages.
	 */
	public function prepare_items() {

		$post_status           = $this->all_posts_status;
		$columns               = $this->get_columns();
		$hidden                = $this->get_hidden_columns();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		// Pagination.
		$page_per_page = 100;

		$query_args = array(
			'post_status' => $post_status,
			'meta_key'    => Straker_Translations_Config::straker_meta_locale, // @codingStandardsIgnoreLine -- List table uses WP_Query
			'meta_value'  => $this->default_lang['code'], // @codingStandardsIgnoreLine --List table uses WP_Query
			'post_type'   => $this->all_post_types,
		);

		if ( filter_has_var(INPUT_GET, 's') ) {

			$query_args['s'] = filter_input(INPUT_GET, "s", FILTER_SANITIZE_STRING);
		}

		if ( filter_has_var(INPUT_GET, 'post-type-filter') && 'all' !== filter_input(INPUT_GET, "post-type-filter", FILTER_SANITIZE_STRING) ) {

			$query_args['post_type'] = filter_input(INPUT_GET, "post-type-filter", FILTER_SANITIZE_STRING);
		}

		if ( filter_has_var(INPUT_GET, 'post-status-filter') && 'all' !== filter_input(INPUT_GET, "post-status-filter", FILTER_SANITIZE_STRING) ) {

			$query_args['post_status'] = filter_input(INPUT_GET, "post-status-filter", FILTER_SANITIZE_STRING);
		}

		if ( filter_has_var(INPUT_GET, 'post-is_translated-filter') && 'all' !== filter_input(INPUT_GET, "post-is_translated-filter", FILTER_SANITIZE_STRING) ) {

			$tl_languages = explode( ',', filter_input(INPUT_GET, "post-is_translated-filter", FILTER_SANITIZE_STRING) );

			$query_args['meta_query']['relation'] = 'OR';

			foreach ( $tl_languages as $language ) {

				$query_args['meta_query'][] = array(
					'key'     => Straker_Translations_Config::straker_meta_target,
					'compare' => 'LIKE',
					'value'   => $language,
				);
			}
		}

		$query       = new WP_Query( $query_args );
		$total_items = $query->found_posts;
		$offset      = filter_has_var(INPUT_GET, 'paged') ? max( 0, intval( filter_input(INPUT_GET, "paged", FILTER_SANITIZE_STRING) - 1 ) * $page_per_page ) : 0;
		$page        = 1;

		if ( filter_has_var(INPUT_GET, 'paged') ) {
			$page = filter_input(INPUT_GET, "paged", FILTER_SANITIZE_STRING);
		}

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $page_per_page,
				'total_pages' => ceil( $total_items / $page_per_page ),
			)
		);
		// Sorting.
		$order_by = 'post_title'; // Default sort key.
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
			if ( in_array( (string)$order_filter, array( 'asc', 'desc' ), true ) ) {
				$order = $order_filter;
			}
		}
		$extra_args = array(
			'orderby'        => $order_by,
			'order'          => $order,
			'posts_per_page' => $page_per_page,
			'paged'          => $page,
			'offset'         => $offset,
		);
		// Do the SQL query and populate items.
		$get_posts   = new WP_Query( array_merge( $query_args, $extra_args ) );
		$this->items = $get_posts->posts;
	}
}
