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
 * The List Table Language Management.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class List_Table_Language_Management extends Straker_Translations_List_Table {


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
	/**
	 * The plugin's text domain.
	 *
	 * @access  private
	 * @var     string  The plugin's text domain. Used for localization.
	 */
	private $text_domain;
	private $all_post_types;
	private $st_added_langs;
	private $default_lang;

	/**
	 * Initializes the WP_List_Table implementation.
	 *
	 * @param $text_domain  string  The text domain used for localizing the plugin.
	 */
	public function __construct( $text_domain ) {

		// Set parent defaults
		parent::__construct(
			array(
				'singular' => 'post_page', // singular name of the listed records
				'plural'   => 'post_pages', // plural name of the listed records
				'ajax'     => false, // does this table support ajax?
			)
		);

		$this->text_domain    = $text_domain;
		$this->st_added_langs = Straker_Language::get_default_and_target_languages();
		$this->all_post_types = Straker_Util::get_all_post_types_names();
		$this->default_lang   = Straker_Language::get_default_language();

	}

	/**
	 * Message to be displayed when there are no items
	 *
	 * @since 3.1.0
	 * @access public
	 */
	public function no_items() {
		esc_html_e( 'No items found.', $this->text_domain );
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

			case 'post_date':
				return date( 'Y/m/d h:i A', strtotime( $item->post_date ) );

			case 'meta_locale':
				$post_locale = get_post_meta( $item->ID, Straker_Translations_Config::straker_meta_locale, true );
				$alng        = Straker_Language::straker_language_meta( 'code', $post_locale );

				if ( $post_locale ) {
					return $alng['name'];
				} else {
					return ' ';
				}
			case 'meta_default':
				$lang_short_code = '';
				$post_locale     = get_post_meta( $item->ID, Straker_Translations_Config::straker_meta_locale, true );
				if ( $post_locale ) {
						$lang_short_code = Straker_Language::get_single_shortcode( $post_locale );
						$source_post_id  = get_post_meta( $item->ID, Straker_Translations_Config::straker_meta_default . $lang_short_code, true );
					if ( $source_post_id ) {
						return '<a href="' . get_edit_post_link( $source_post_id ) . '" target="_blank">' . get_the_title( $source_post_id ) . '</a>';
					} else {
						return '';
					}
				} else {
					return '';
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
	 * displaying checkbox for bulk action.
	 *
	 * @return array    The database columns that can be used for sorting the table.
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" id="st-lang-manag-%2$s" class= "st-lang-manag" />',
			/*$1%s*/$this->_args['singular'],
			/*$2%s*/ $item->ID
		);
	}

	/**
	 * Custom renderer for the post_title field.
	 *
	 * @param $item     array   The database row being printed out.
	 * @return string   The text or HTML that should be shown for the column.
	 */
	public function column_post_title( $item ) {
		$post_id     = $item->ID;
		$post        = get_post( $post_id );
		$post_locale = get_post_meta( $post_id, Straker_Translations_Config::straker_meta_locale, true );
		$view_link   = '';

		if ( $post ) {
			if ( $post_locale !== $this->default_lang['code'] ) {
				if ( 'pending' === $item->post_status || 'draft' === $item->post_status ) {
					list($permalink, $post_name) = get_sample_permalink( $item->ID, $item->post_title );
					$view_link                   = str_replace( array( '%pagename%', '%postname%' ), $item->post_name, $permalink );
				} else {
					$view_link = get_the_permalink( $post_id );
				}
				return '<a st-data-tooltip title=" ' . $view_link . ' " href="' . get_edit_post_link( $post->ID ) . '" target="_blank">' . $post->post_title . '</a>';
			} else {
				return '<a href="' . get_edit_post_link( $post->ID ) . '" target="_blank">' . $post->post_title . '</a>';
			}
		}

		return __( 'No post', $this->text_domain );
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
			'cb'           => 'checkbox', // Render a checkbox instead of text
			'post_title'   => __( 'Title', $this->text_domain ),
			'post_type'    => __( 'Type', $this->text_domain ),
			'post_date'    => __( 'Date Published', $this->text_domain ),
			'post_status'  => __( 'Status', $this->text_domain ),
			'meta_locale'  => __( 'Language', $this->text_domain ),
			'meta_default' => __( 'Source post', $this->text_domain ),
		);

		return $columns;

	}

	public function print_column_headers( $with_id = true ) {

		list($columns, $hidden, $sortable, $primary) = $this->get_column_info();

		$server_host = filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);
		$server_uri = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );
		$is_url_ssl = is_ssl() ? 'https://' : 'http://';
		$current_url = set_url_scheme( $is_url_ssl . $server_host . $server_uri  );
		$current_url = remove_query_arg( 'paged', $current_url );

		if ( filter_input(INPUT_GET, "orderby", FILTER_SANITIZE_STRING ) ) {
			$current_orderby = filter_input(INPUT_GET, "orderby", FILTER_SANITIZE_STRING );
		} else {
			$current_orderby = '';
		}

		if ( filter_input(INPUT_GET, "order", FILTER_SANITIZE_STRING ) && 'desc' === filter_input(INPUT_GET, "order", FILTER_SANITIZE_STRING ) ) {
			$current_order = 'desc';
		} else {
			$current_order = 'asc';
		}

		if ( ! empty( $columns['cb'] ) ) {
			static $cb_counter = 1;
			$columns['cb']     = '<label class="screen-reader-text" for="cb-select-all-' . $cb_counter . '">' . __( 'Select All' ) . '</label>'
				. '<input id="cb-select-all-' . $cb_counter . '" class="st-lang-manag" type="checkbox" st-data-tooltip title="' . __( 'Select/Deselect All Visible', $this->text_domain ) . '" />';
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

	protected function bulk_actions( $which = '' ) {

		$actions = $this->get_bulk_actions();
		if ( empty( $actions ) ) {
			return;
		}
		echo "<label for='bulk-action-selector' class='screen-reader-text'>" . esc_html( __( 'Select bulk action' ) ) . '</label>';
		echo "<select name='action' id='bulk-action-selector' class='st-lang-spost'>\n";
		echo "<option value='' selected='selected'>" . esc_html( __( 'Select Locale Language' ) ) . "</option>\n";

		foreach ( $actions as $name => $title ) {
			echo "\t<option value='".esc_attr($name)."'>". esc_html( $title ) . "</option>\n";
		}

		echo "</optgroup></select>\n";
		echo '<input type="hidden" name="st_selected_posts" id="st_selected_posts" value="" />';
		echo "<select name='st_soruce_post' id='st_soruce_post' class='st-lang-spost'>\n";
		echo "<option value='' selected='selected'>" . esc_html( __( 'Select Source Post' ) ) . "</option>\n";
		echo "<option value='no_source_post'>" . esc_html( __( 'No Source Post' ) ) . "</option>\n";
		$post_type_filter = filter_input(INPUT_GET, "st_post_type_filter", FILTER_SANITIZE_STRING ) ? filter_input(INPUT_GET, "st_post_type_filter", FILTER_SANITIZE_STRING ) : false;

		if ( $post_type_filter ) {
			if ( $post_type_filter === 'all' ) {
				$args = array( 'post_type' => $this->all_post_types );

			} else {
				$args = array( 'post_type' => $post_type_filter );
			}
		} else {
			$args = array( 'post_type' => $this->all_post_types );
		}
				$other_rgs   = array(
					'orderby'        => 'title',
					'order'          => 'ASC',
					'posts_per_page' => PHP_INT_MAX,
					'post_status'    => array( 'publish', 'pending', 'draft', 'future', 'private' ),
					'meta_key'       => Straker_Translations_Config::straker_meta_locale,
					'meta_value'     => $this->default_lang['code'],
				);
				$final_query = array_merge( $args, $other_rgs );
				$query       = new WP_Query( $final_query );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				echo '<option value="' . esc_attr( get_the_ID() ) . '">' . esc_attr( get_the_title() ) . '</option>';
			}
			wp_reset_postdata();
		}
				echo '</select>';

				submit_button(
					__( 'Apply' ),
					'action',
					'',
					false,
					array(
						'name' => 'submit-bulk-lang-chng',
						'id'   => 'doaction',
					)
				);
				echo "\n";
	}

	public function get_bulk_actions() {

		$site_langs = $this->st_added_langs;
		$actions    = array();
		foreach ( $site_langs as $value ) {
				$actions[ $value['code'] ] = $value['name'];
		}
		ksort( $actions );
		return $actions;
	}

	public function extra_tablenav( $which = '' ) {

		if ( $which === 'top' ) {

			$post_filter = filter_input(INPUT_GET, "st_post_type_filter", FILTER_SANITIZE_STRING ) ? filter_input(INPUT_GET, "st_post_type_filter", FILTER_SANITIZE_STRING ) : '';
			$order_by    = filter_input(INPUT_GET, "orderby", FILTER_SANITIZE_STRING ) ? '&orderby=' . filter_input(INPUT_GET, "orderby", FILTER_SANITIZE_STRING ) : '';
			$order       = filter_input(INPUT_GET, "order", FILTER_SANITIZE_STRING ) ? '&order=' . filter_input(INPUT_GET, "order", FILTER_SANITIZE_STRING ) : '';
			$paged       = filter_input(INPUT_GET, "paged", FILTER_SANITIZE_STRING ) ? '&paged=' . filter_input(INPUT_GET, "paged", FILTER_SANITIZE_STRING ) : '';
			$lang_select = filter_input(INPUT_GET, "st_lang_filter", FILTER_SANITIZE_STRING ) ? '&st_lang_filter=' . filter_input(INPUT_GET, "st_lang_filter", FILTER_SANITIZE_STRING ) : false;
			$type_filter = '';

			if ( ! empty( $post_filter ) ) {
				$type_filter = '&st_post_type_filter=' . $post_filter;
			}

			echo '<div class="alignleft actions">';
			echo '<form action="?page=st-settings&tab=language_management' . esc_attr( $type_filter ). '' . esc_attr( $lang_select ). '' . esc_attr( $order_by ) . '' . esc_attr( $order ) . '' . esc_attr( $paged ) . '" id="lang_mang_bulk_action" method="post">';
			$this->bulk_actions( 'top' );
			echo '</form></div>';
			?>

			<?php
		}

		if ( $which === 'bottom' ) {
			// The code that goes after the table is there
		}
	}

	protected function process_bulk_action() {
		// Detect when a bulk action is being triggered...

		if ( filter_has_var(INPUT_POST, "submit-bulk-lang-chng" ) && filter_has_var(INPUT_POST, "action" ) ) {

			$server_host = filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);
			$server_uri	= filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );
			$posts_id         = array();
			$url              = is_ssl() ? 'https://' : 'http://';
			$lang_meta        = filter_has_var( INPUT_POST, 'action') ? filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING) : false;
			$current_url      = set_url_scheme( $url . $server_host . $server_uri );
			$current_url      = add_query_arg(
				array(
					'msg' => 'success',
					'ac'  => 'lang_management',
				),
				$current_url
			);
			$source_post      = filter_has_var( INPUT_POST, 'st_soruce_post') ? filter_input( INPUT_POST, 'st_soruce_post', FILTER_SANITIZE_STRING) : false;
			$post_locale      = Straker_Translations_Config::straker_meta_locale;
			$post_def         = Straker_Translations_Config::straker_meta_default;
			$post_target_name = Straker_Translations_Config::straker_meta_target;

			if ( filter_has_var( INPUT_POST, 'st_selected_posts') && '' !== filter_input( INPUT_POST, 'st_selected_posts', FILTER_SANITIZE_STRING ) ) {

				$posts_id = explode(",",filter_input( INPUT_POST, 'st_selected_posts', FILTER_SANITIZE_STRING ) );

				foreach ( $posts_id as $value ) {

					if ( $value  ) {

						$check_post_locale = ( $this->default_lang['code'] !== $lang_meta ) ? true : false;
						$lang_short_code   = Straker_Language::get_single_shortcode( $lang_meta );
						if ( 'no_source_post' === $source_post && $check_post_locale ) {
							update_post_meta( $value, $post_locale, $lang_meta );
							delete_post_meta( $value, $post_def . $lang_short_code );
						} elseif ( 'no_source_post' === $source_post && ! $check_post_locale ) {
							update_post_meta( $value, $post_locale, $lang_meta );
							$this->delete_st_post_locale_default( $value );
						} elseif ( 'no_source_post' !== $source_post && $check_post_locale ) {
							update_post_meta( $value, $post_locale, $lang_meta );
							update_post_meta( $value, $post_def . $lang_short_code, $source_post );
							update_post_meta( $source_post, $post_target_name, $lang_meta );
						} elseif ( 'no_source_post' !== $source_post && ! $check_post_locale ) {
							$this->delete_st_post_locale_default( $value );
						}
					}
				}
			}
			wp_redirect( $current_url );
			exit;
		}
	}

	public function lang_mangement_filters() {

		$site_langs  = $this->st_added_langs;
		$lang_filter = '';
		$type_filter = '';
		$post_filter = '';
		$lang_select = '';

		if ( filter_has_var( INPUT_POST, 'st_post_type_filter') && !filter_has_var( INPUT_POST, 'st_lang_filter') ) {
			$post_filter = filter_input( INPUT_POST, 'st_post_type_filter', FILTER_SANITIZE_STRING);
		} elseif ( ! filter_has_var( INPUT_POST, 'st_post_type_filter') && filter_has_var( INPUT_POST, 'st_lang_filter') ) {
			$lang_select = filter_input( INPUT_POST, 'st_lang_filter', FILTER_SANITIZE_STRING);
		} elseif ( filter_has_var( INPUT_POST, 'st_post_type_filter') && filter_has_var( INPUT_POST, 'st_lang_filter') ) {
			$post_filter = filter_input( INPUT_POST, 'st_post_type_filter', FILTER_SANITIZE_STRING);
			$lang_select = filter_input( INPUT_POST, 'st_lang_filter', FILTER_SANITIZE_STRING);
		}

		if ( $post_filter && ! $lang_select ) {
			$type_filter = '&st_post_type_filter=' . $post_filter;
		}
		if ( $lang_select && ! $post_filter ) {
			$lang_filter = '&st_lang_filter=' . $lang_select;
		}

		echo '<form action="?page=st-settings&tab=language_management' . esc_attr( $type_filter ). '' . esc_attr( $lang_filter ) . '" id="lang_mang_bulk_filter_action" method="post" name="st_lang_filters_form" style="float:left;">';
		$post_filter = filter_has_var( INPUT_POST, 'st_post_type_filter') ? filter_input( INPUT_POST, 'st_post_type_filter', FILTER_SANITIZE_STRING) : '';
		$lang_select = filter_has_var( INPUT_POST, 'st_lang_filter') ? filter_input( INPUT_POST, 'st_lang_filter', FILTER_SANITIZE_STRING) : '';
		?>
		<select name="st_post_type_filter" id="st_post_type_filter" class="filters_lang_type">
			<option value=""><?php echo esc_html( __( 'Filter by Post Types', $this->text_domain ) ); ?></option>
				<?php
				foreach ( $this->all_post_types as $key ) {
					$selectd_type = ( $key === $post_filter ) ? 'selected' : '';
					$input        = preg_replace( '/[^a-zA-Z0-4]+/', ' ', $key );
					$display_val  = ucwords( strtolower( $input ) );
					echo '<option value="' . esc_attr( $key ) . '" ' . esc_attr( $selectd_type ) . '>' .esc_html( $display_val ). '</option>';
				}
				?>
		</select>
		<select name="st_lang_filter" id="st_lang_filter" class="filters_lang_type">
			<option value=''><?php echo esc_html( __( 'Filter by Language', $this->text_domain ) ); ?></option>
			<?php
			foreach ( $site_langs as $value ) {
				?>
					<option value="<?php echo esc_attr( $value['code'] ); ?>"
				<?php
				if ( $lang_select === $value['code'] ) {
					echo 'selected'; }
				?>
					><?php echo esc_html( $value['name'] ); ?></option>
				<?php
			}
			?>
		</select>
			<?php
			submit_button( __( 'Filter' ), 'action', '', false, array( 'id' => 'test' ) );
			echo '</form>';

	}

	/**
	 * Populates the class fields for displaying the list of post and pages.
	 */
	public function prepare_items() {

		$post_status    = array( 'publish', 'pending', 'draft', 'future', 'private' );
		$columns        = $this->get_columns();
		$hidden         = $this->get_hidden_columns();
		$sortable       = $this->get_sortable_columns();
		$query_args     = '';
		$search_query   = '';
		$remove_args    = '';
		$args           = '';
		$filter_by_lang = filter_has_var( INPUT_POST, 'st_lang_filter') ? filter_input( INPUT_POST, 'st_lang_filter', FILTER_SANITIZE_STRING) : '';
		$post_filter    = filter_has_var( INPUT_POST, 'st_post_type_filter') ? filter_input( INPUT_POST, 'st_post_type_filter', FILTER_SANITIZE_STRING) : '';

		if ( $filter_by_lang && ! $post_filter ) {
			$query_args  = array( 'st_lang_filter' => $filter_by_lang );
			$remove_args = 'st_post_type_filter';
		} elseif ( ! $filter_by_lang && $post_filter ) {
			$query_args  = array( 'st_post_type_filter' => $post_filter );
			$remove_args = 'st_lang_filter';
		} elseif ( $filter_by_lang && $post_filter ) {
			$query_args = array(
				array(
					'st_lang_filter'      => $filter_by_lang,
					'st_post_type_filter' => $post_filter,
				),
			);
		} elseif ( ! $filter_by_lang && ! $post_filter ) {
			$remove_args = array( 'st_lang_filter', 'st_post_type_filter' );
		}

		$_SERVER['REQUEST_URI'] = add_query_arg( $query_args, filter_has_var( INPUT_SERVER, 'REQUEST_URI' ) ? filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL ) : "" );
		$_SERVER['REQUEST_URI'] = remove_query_arg( $remove_args, filter_has_var( INPUT_SERVER, 'REQUEST_URI' ) ? filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL ) : "" );
		/**
		 * Optional. You can handle your bulk actions however you see fit. In this
		 * case, we'll handle them within our package just to keep things clean.
		 */
		$this->process_bulk_action();

		if ( $filter_by_lang || $post_filter ) {
			$search_query = 'filters_lang_status';
		}

		$this->_column_headers = array( $columns, $hidden, $sortable );

		// Pagination
		$page_per_page = 100;

		$extra_args_pag = array( 'post_status' => $post_status );

		switch ( $search_query ) {

			case 'filters_lang_status':
				if ( $post_filter && $filter_by_lang ) {
					$args = array(
						'post_type'  => $post_filter,
						'meta_key'   => Straker_Translations_Config::straker_meta_locale,
						'meta_value' => $filter_by_lang,
					);
				} elseif ( $filter_by_lang && ! $post_filter ) {
					$args = array(
						'post_type'  => $this->all_post_types,
						'meta_key'   => Straker_Translations_Config::straker_meta_locale,
						'meta_value' => $filter_by_lang,
					);
				} elseif ( $post_filter && ! $filter_by_lang ) {
					$args = array(
						'post_type' => $post_filter,
					);
				}
				$query       = new WP_Query( array_merge( $args, $extra_args_pag ) );
				$total_items = $query->found_posts;
				break;

			default:
				$args        = array(
					'post_type' => $this->all_post_types,
				);
				$query       = new WP_Query( array_merge( $args, $extra_args_pag ) );
				$total_items = $query->found_posts;
				break;
		}

		$offset = filter_has_var( INPUT_GET, 'paged') ? max( 0, intval( filter_input( INPUT_GET, 'paged', FILTER_VALIDATE_INT ) - 1 ) * $page_per_page ) : 0;
		$page = 1;
		if ( filter_has_var( INPUT_GET, 'paged') ) {
			$page =  filter_input( INPUT_GET, 'paged', FILTER_VALIDATE_INT );
		}

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $page_per_page,
				'total_pages' => ceil( $total_items / $page_per_page ),
			)
		);
		// Sorting
		$order_by = 'post_title'; // Default sort key
		if ( filter_has_var( INPUT_GET, 'orderby') ) {
			// If the requested sort key is a valid column, use it for sorting
			if ( in_array( filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_STRING ), array_keys( $this->get_sortable_columns() ), true ) ) {
				$order_by = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_STRING );
			}
		}
		$order = 'asc'; // Default sort order
		if ( filter_has_var( INPUT_GET, 'order') ) {
			if ( in_array( filter_input( INPUT_GET, 'order', FILTER_SANITIZE_STRING ), array( 'asc', 'desc' ), true ) ) {
				$order = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_STRING );
			}
		}

		$extra_args = array(
			'post_status'    => $post_status,
			'orderby'        => $order_by,
			'order'          => $order,
			'posts_per_page' => $page_per_page,
			'paged'          => $page,
			'offset'         => $offset,
		);

		// Do the SQL query and populate items
		switch ( $search_query ) {

			case 'filters_lang_status':
				if ( $post_filter && $filter_by_lang ) {

					$args = array(
						'post_type'  => $post_filter,
						'meta_key'   => Straker_Translations_Config::straker_meta_locale,
						'meta_value' => $filter_by_lang,
					);
				} elseif ( $filter_by_lang ) {
					$args = array(
						'post_type'  => $this->all_post_types,
						'meta_key'   => Straker_Translations_Config::straker_meta_locale,
						'meta_value' => $filter_by_lang,
					);
				} elseif ( $post_filter && ! $filter_by_lang ) {
					$args = array(
						'post_type' => $post_filter,
					);
				}

				$query       = new WP_Query( array_merge( $args, $extra_args ) );
				$this->items = $query->posts;
				break;

			default:
				$aTyps = $this->all_post_types;
				$args  = array(
					'post_type' => $aTyps,
				);

				$query       = new WP_Query( array_merge( $args, $extra_args ) );
				$this->items = $query->posts;
				break;
		}
	}

	public function delete_st_post_locale_default( $key ) {

		$get_post_meta = get_post_custom_keys( $key );

		if ( is_array( $get_post_meta ) ) {

			$search_post_meta_array = array_values( $get_post_meta );
			$post_default_name      = Straker_Translations_Config::straker_meta_default;
			$post_target_name       = Straker_Translations_Config::straker_meta_target;

			foreach ( $search_post_meta_array as $source_post_default ) {

				if ( strpos( $source_post_default, $post_default_name ) !== false ) {
						delete_post_meta( $key, $source_post_default );
				}

				if ( $source_post_default === $post_target_name ) {
					delete_post_meta( $key, $source_post_default );
				}
			}
		}
	}

}
