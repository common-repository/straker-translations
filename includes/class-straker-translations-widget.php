<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */

/**
 * The Straker Language List Widget.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Language_List extends WP_Widget {


	/**
	 * The name of this plugin.
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
	 * @var      object    $version    The version of this plugin.
	 */
	protected $st_link;

	/**
	 * The default language of the plugin.
	 *
	 * @var      string    $default_language THe Default Language.
	 */
	protected $default_language;

	/**
	 * The default language of the plugin.
	 *
	 * @var      string    $added_language The Added Language.
	 */
	protected $added_language;

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		 $this->st_link         = new Straker_Link();
		$this->default_language = Straker_Language::get_default_language();
		$this->added_language   = Straker_Language::get_added_language();

		$name = 'Straker Language Switcher &lt;ul&gt;';

		$widget_ops  = array( 'description' => 'This is a language switcher for the Straker Translations plugin.' );
		$control_ops = '';

		parent::__construct(
			false,
			$name,
			$widget_ops,
			$control_ops
		);

		$this->str = 'Straker Widget';

	}

	/**
	 * Widget.
	 *
	 * @param array $args Arguments.
	 * @param array $instance Content.
	 */
	public function widget( $args, $instance ) {

		$list = '<ul style="list-style: none;"';
		if ( $instance['horizontal'] && $instance['horizontal'] === 'on' ) {
			$list .= " id='langlist'";
		}
		if ( $instance['custom_css'] && $instance['custom_css'] !== '' ) {
			$list .= " class='" . $instance['custom_css'] . "'";
		}
		$list .= '>';
		$list .= "<li><a href='" . esc_url( $this->st_link->straker_default_home() ) . "'>";
		if ( $instance['flag'] && $instance['flag'] === 'on' ) {
			$list .= "<img src='" . STRAKER_PLUGIN_ABSOLUTE_PATH . '/assets/img/flags/' . $this->default_language['code'] . ".png' alt='" . $this->default_language['native_name'] . "' style='vertical-align: text-top;' /> ";
		}
		if ( $instance['lang'] && $instance['lang'] === 'on' ) {
			$list .= $this->default_language['native_name'];
		}
		$list .= '</a></li>';
		foreach ( $this->added_language as $value ) {
			if ( in_array( $value['native_name'], $instance['available'], true ) ) {
					$list .= "<li><a href='" . esc_url( $this->st_link->straker_locale_home( $value['wp_locale'] ) ) . "'>";
				if ( $instance['flag'] === 'on' ) {
					$list .= "<img src='" . STRAKER_PLUGIN_ABSOLUTE_PATH . '/assets/img/flags/' . $value['code'] . ".png' alt='" . $this->default_language['native_name'] . "' style='vertical-align: text-top;' /> ";
				}
				if ( $instance['lang'] === 'on' ) {
						$list .= $value['native_name'];
				}
					$list .= '</a></li>';
			}
		}
		$list .= '</ul>';
		echo wp_kses( $list, wp_kses_allowed_html( 'post') );
	}

	/**
	 * Widget Form.
	 *
	 * @param array $instance Content.
	 */
	public function form( $instance ) {

		$defaults = array(
			'custom_css' => '',
			'flag'       => 'off',
			'lang'       => 'off',
			'horizontal' => 'off',
			'available'  => array(),
		);

			$instance = wp_parse_args( (array) $instance, $defaults );
			echo '<br />' . esc_html( __( 'Available Languages:', $this->plugin_name ) );
			foreach ( $this->added_language as $value ) {
			?>
				<p>
					<input class="checkbox" type="checkbox" id="<?php echo esc_attr( $value['native_name'] ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'available' ) ); ?>[]" value="<?php echo esc_attr( $value['native_name'] ); ?>"
						<?php
						if ( $instance['available'] ) {
							checked( in_array( $value['native_name'], $instance['available'], true ) );}
						?>
					/>
					<label for=""><?php echo esc_html( $value['name'] ) . ' - ' . esc_html( $value['native_name'] ); ?></label>
				</p>
			<?php } ?>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['flag'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'flag' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'flag' ) ); ?>" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'flag' ) ); ?>"><?php esc_attr_e( 'Display flag', $this->plugin_name ); ?></label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['lang'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'lang' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'lang' ) ); ?>" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'lang' ) ); ?>"><?php esc_attr_e( 'Display language', $this->plugin_name ); ?></label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['horizontal'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'horizontal' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'horizontal' ) ); ?>" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'horizontal' ) ); ?>"><?php esc_attr_e( 'Dipslay horizontal', $this->plugin_name ); ?></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'custom_css' ) ); ?>"><?php esc_attr_e( 'CSS Class for', $this->plugin_name ); ?> &lt;ul&gt;</label>
				<input class="widefat"  id="<?php echo esc_attr( $this->get_field_id( 'custom_css' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'custom_css' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['custom_css'] ); ?>" />
			</p>
			<?php
	}

	/**
	 * Widget Update.
	 *
	 * @param array $new_instance New Content.
	 * @param array $old_instance Old Content.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance               = $old_instance;
		$instance['custom_css'] = wp_strip_all_tags( $new_instance['custom_css'] );
		// The update for the variable of the checkbox.
		$instance['flag']       = isset( $new_instance['flag'] ) ? esc_attr( $new_instance['flag'] ) : 'off';
		$instance['lang']       = isset( $new_instance['lang'] ) ? esc_attr( $new_instance['lang'] ) : 'off';
		$instance['horizontal'] = isset( $new_instance['horizontal'] ) ? esc_attr( $new_instance['horizontal'] ) : 'off';
		$instance['available']  = isset( $new_instance['available'] ) ? $new_instance['available'] : array();
		return $instance;
	}

}

?>
