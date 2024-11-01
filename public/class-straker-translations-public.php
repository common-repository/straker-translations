<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/public
 * @author     Straker Translations <apisupport@strakertranslations.com>
 */
class Straker_Translations_Public {


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Straker_Translations_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Straker_Translations_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/straker-translations-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Straker_Translations_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Straker_Translations_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/straker-translations-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the Straker Widget.
	 *
	 * @since    1.0.0
	 */
	public function straker_register_widget() {
		register_widget( 'Straker_Language_List' );
	}

	/**
	 * Set the Straker Widget title.
	 *
	 * @param  string $instance instace.
	 * @return string
	 */
	public function straker_widget_title( $instance ) {
		$theme      = wp_get_theme();
		$translated = translate( $instance, $theme->template );
		return $translated;

	}

	/**
	 * Load the Translted posts front page.
	 *
	 * @return bool
	 */
	public function straker_pre_option_page_for_posts() {

		$current_locale      = Straker_Language::straker_language_locale( get_locale() );
		$default_lang_locale = Straker_Language::get_default_language();

		if ( ! is_admin() && $current_locale !== $default_lang_locale['wp_locale'] ) {
			$post_id = Straker_Translations_Pre_Options::get_pre_options( $current_locale, 'page_for_posts' );
			return ( isset( $post_id ) && 0 !== (int) $post_id ) ? $post_id : false;
		} else {
			return false;
		}
	}

	/**
	 * Load the Translated front page.
	 */
	public function straker_pre_option_page_for_frontpage() {

		$current_locale      = Straker_Language::straker_language_locale( get_locale() );
		$default_lang_locale = Straker_Language::get_default_language();

		if ( ! is_admin() && $current_locale !== $default_lang_locale['wp_locale'] ) {

			$post_id = Straker_Translations_Pre_Options::get_pre_options( $current_locale, 'page_on_front' );
			return ( isset( $post_id ) && 0 !== (int) $post_id ) ? $post_id : false;

		} else {
			return false;
		}
	}

	/**
	 * Filter the posts categories widget.
	 *
	 * @param  array $cat_args Categories arguments.
	 * @return array
	 */
	public function straker_widget_categories_args_filter( $cat_args ) {

		if ( ! is_admin() ) {

			$locale    = Straker_Language::straker_language_locale( get_locale() );
			$lang_meta = Straker_Language::straker_language_meta( Straker_Translations_Config::straker_wp_locale, $locale );

			$cat_args = array(
				'hide_empty' => false,
				'title_li'   => '',
				'meta_key'   => Straker_Translations_Config::straker_cat_lang_meta, // @codingStandardsIgnoreLine -- For posts categories
				'meta_value' => $lang_meta['code'], // @codingStandardsIgnoreLine -- For posts categories
			);

			return $cat_args;
		}
	}

	/**
	 * Filter the posts tags widget.
	 *
	 * @param  array $tag_args Tags arguments.
	 * @return array
	 */
	public function straker_widget_tag_cloud_args_filter( $tag_args ) {

		if ( ! is_admin() ) {

			$locale    = Straker_Language::straker_language_locale( get_locale() );
			$lang_meta = Straker_Language::straker_language_meta( Straker_Translations_Config::straker_wp_locale, $locale );

			$tag_args = array(
				'echo'       => '',
				'orderby'    => 'name',
				'meta_key'   => Straker_Translations_Config::straker_tag_lang_meta, // @codingStandardsIgnoreLine -- For posts tags
				'meta_value' => $lang_meta['code'], // @codingStandardsIgnoreLine -- For posts tags
			);

			return $tag_args;

		}
	}

	/**
	 * Aletr the Terms Widget.
	 *
	 * @param  array $args Widget options.
	 * @param  array $taxonomies taxonomies.
	 * @return array
	 */
	public function straker_terms_widget_args( $args, $taxonomies ) {

		$locale    = Straker_Language::straker_language_locale( get_locale() );
		$lang_meta = Straker_Language::straker_language_meta( Straker_Translations_Config::straker_wp_locale, $locale );
		if ( is_admin() ) {
			return $args;
		}

		if ( ( is_array( $args['taxonomy'] ) && in_array( 'category', $args['taxonomy'], true ) ) || 'category' === $args['taxonomy'] ) {

			$args = array(
				'taxonomy'               => null,
				'hide_empty'             => false,
				'child_of'               => '',
				'parent'                 => '',
				'exclude_tree'           => '',
				'update_term_meta_cache' => '',
				'fields'                 => '',
				'type'                   => '',
				'offset'                 => '',
				'number'                 => '',
				'get'                    => '',
				'fields'                 => 'all',
				'hierarchical'           => '',
				'childless'              => '',
				'include'                => '',
				'exclude'                => '',
				'order'                  => '',
				'orderby'                => '',
				'pad_counts'             => '',
				'meta_query'             => array( // @codingStandardsIgnoreLine -- For posts taxonomies
					array(
						'key'   => Straker_Translations_Config::straker_cat_lang_meta,
						'value' => $lang_meta['code'],
					),
				),
			);
				return $args;

		}

		if ( 'category' === $taxonomies[0] ) {
			$args = array(
				'taxonomy'               => null,
				'hide_empty'             => false,
				'child_of'               => '',
				'parent'                 => '',
				'exclude_tree'           => '',
				'update_term_meta_cache' => '',
				'fields'                 => '',
				'offset'                 => '',
				'number'                 => '',
				'get'                    => '',
				'fields'                 => 'all',
				'hierarchical'           => '',
				'childless'              => '',
				'include'                => '',
				'exclude'                => '',
				'order'                  => '',
				'orderby'                => '',
				'pad_counts'             => '',
				'meta_query'             => array( // @codingStandardsIgnoreLine --  For posts categories filter
					array(
						'key'   => Straker_Translations_Config::straker_cat_lang_meta,
						'value' => $lang_meta['code'],
					),
				),
			);
			return $args;
		} elseif ( 'post_tag' === $taxonomies[0] ) {
			$args = array(
				'taxonomy'               => null,
				'hide_empty'             => false,
				'child_of'               => '',
				'parent'                 => '',
				'exclude_tree'           => '',
				'update_term_meta_cache' => '',
				'fields'                 => '',
				'offset'                 => '',
				'number'                 => '',
				'fields'                 => 'all',
				'hierarchical'           => '',
				'childless'              => '',
				'get'                    => '',
				'include'                => '',
				'exclude'                => '',
				'order'                  => '',
				'orderby'                => '',
				'pad_counts'             => '',
				'meta_query'             => array( // @codingStandardsIgnoreLine -- For posts tags
					array(
						'key'   => Straker_Translations_Config::straker_tag_lang_meta,
						'value' => $lang_meta['code'],
					),
				),
			);
			return $args;
		} else {
			return $args;
		}
			return $args;
	}

	/**
	 * Aletr the Widget .
	 *
	 * @param  string $args Widget options.
	 * @return array
	 */
	public function straker_alter_widget( $args ) {
		$locale   = Straker_Language::straker_language_locale( get_locale() );
		$lang_meta = Straker_Language::straker_language_meta( Straker_Translations_Config::straker_wp_locale, $locale );
		$args      = array(
			'posts_per_page' => $args['posts_per_page'],
			'meta_query'     => array( // @codingStandardsIgnoreLine -- For widget filter
				array(
					'key'   => Straker_Translations_Config::straker_meta_locale,
					'value' => $lang_meta['code'],
				),
			),
		);
		return $args;
	}

	/**
	 * Posts Archives Widget Area.
	 *
	 * @param  string $where SQL query for the inner join.
	 * @return string
	 */
	public function straker_archives_widget_args( $where ) {
		global $wpdb;
		$table_post = $wpdb->prefix . 'posts';
		$table_meta = $wpdb->prefix . 'postmeta';
		$locale     = Straker_Language::straker_language_locale( get_locale() );
		$lang_meta  = Straker_Language::straker_language_meta( Straker_Translations_Config::straker_wp_locale, $locale );
		$$where     = " INNER JOIN $table_meta ON (	$table_post.ID = $table_meta.post_id) WHERE $table_meta.meta_key = '" . Straker_Translations_Config::straker_meta_locale . "' AND $table_meta.meta_value = '" . $lang_meta['code'] . "'";
		return $where;
	}

	/**
	 * Comments Widget Area.
	 *
	 * @param  array $comment_args Comments widget array.
	 * @return array
	 */
	public function straker_comments_widget_args( $comment_args ) {
		$locale       = Straker_Language::straker_language_locale( get_locale() );
		$lang_meta    = Straker_Language::straker_language_meta( Straker_Translations_Config::straker_wp_locale, $locale );
		$comment_args = array(
			'meta_key'   => Straker_Translations_Config::straker_meta_locale,  // @codingStandardsIgnoreLine -- For WP_Comment_Query
			'meta_value' => $lang_meta['code'],  // @codingStandardsIgnoreLine -- For WP_Comment_Query
		);
		return $comment_args;
	}

	/**
	 * Insert the comment for translated posts.
	 *
	 * @param  int    $id Comment ID.
	 * @param  string $comment Post comment.
	 */
	public function straker_insert_comment( $id, $comment ) {
		$post_locale = get_post_meta( $comment->comment_post_ID, Straker_Translations_Config::straker_meta_locale );
		$post_meta   = ! empty( $post_locale ) ? get_post_meta( $comment->comment_post_ID, Straker_Translations_Config::straker_meta_locale, true ) : false;
		if ( $post_meta ) {
			add_comment_meta( $comment->comment_ID, Straker_Translations_Config::straker_meta_locale, $post_meta );
		}
	}

	/**
	 * Aletr the Query for translated content.
	 *
	 * @param  query $query query.
	 */
	public function straker_alter_query( $query ) {

		if ( ! is_admin() && $query->is_main_query() ) {

			$locale    = Straker_Language::straker_language_locale( get_locale() );
			$lang_meta = Straker_Language::straker_language_meta( Straker_Translations_Config::straker_wp_locale, $locale );
			$query->set( 'meta_key', Straker_Translations_Config::straker_meta_locale );
			$query->set( 'meta_value', $lang_meta['code'] );
			remove_all_actions( '__after_loop' );
		}

	}

}
