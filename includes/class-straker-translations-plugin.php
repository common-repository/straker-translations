<?php
/**
 * Straker Plugin
 *
 * @link       https://www.strakertranslations.com
 * @since      1.0.0
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */

/**
 * The Straker Plugin Class.
 *
 * @package    Straker_Translations
 * @subpackage Straker_Translations/includes
 */
class Straker_Plugin {


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
		 * The content fields of acf plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      array    $acf_content_fields    The acf-plugin content fields types.
		 */

	private static $acf_content_fields;

		/**
		 * The non content fields of acf plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      array    $acf_non_content_fields    The acf-plugin non-content fields types.
		 */
	private static $acf_non_content_fields;


	/**
	 * Set the ACF Fields Arrays
	 */
	public function __construct() {
		self::$acf_content_fields    = array(
			'textarea',
			'wysiwyg',
			'text',
		);
		self::$acf_non_content_fields = array(
			'checkbox',
			'radio',
			'select',
			'true_false',
			'file',
			'gallery',
			'image',
			'oembed',
			'message',
			'number',
			'email',
			'color_picker',
			'date_picker',
			'date_time_picker',
			'google_map',
			'time_picker',
			'page_link',
			'post_object',
			'relationship',
			'taxonomy',
			'user',
		);
	}

	/**
	 * Check if Plugin Exists
	 *
	 * @param string $plugin_name Plugin Name.
	 */
	public static function plugin_exist( $plugin_name ) {

		if ( 'acf' === $plugin_name ) {
			if ( class_exists( 'acf' ) || defined( 'ACF_VERSION' ) ) {
				return true;
			}
		} elseif ('wp-seo' === $plugin_name) {
			if ( class_exists( 'WPSEO_Options' ) || defined( 'WPSEO_VERSION' ) || defined('YOAST_ENVIRONMENT') ) {
				return true;
			}
		}
	}

	/**
	 * Yoast SEO plugin
	 *
	 * @param int $id Field ID.
	 */
	public static function straker_wpseo_check( $id ) {
		$a_wpseo = array();
		if ( get_post_meta( $id, '_yoast_wpseo_title', true ) ) {
			$a_wpseo['seo_title'] = get_post_meta( $id, '_yoast_wpseo_title', true );
		}
		if ( get_post_meta( $id, '_yoast_wpseo_metadesc', true ) ) {
			$a_wpseo['seo_description'] = get_post_meta( $id, '_yoast_wpseo_metadesc', true );
		}
		if ( get_post_meta( $id, '_yoast_wpseo_focuskw', true ) ) {
			$a_wpseo['seo_focus'] = get_post_meta( $id, '_yoast_wpseo_focuskw', true );
		}
		return $a_wpseo;
	}

	/**
	 * Advanced Custom Fields plugin
	 *
	 * @param int $id Field ID.
	 */
	public static function straker_acf_plugin_check( $id ) {
		$acf_data                = array();
		$content_field_types     = self::$acf_content_fields;
		$non_content_field_types = self::$acf_non_content_fields;
		$post_acf_fields         = ( get_field_objects( $id ) > 0 ) ? get_field_objects( $id ) : false;
		$full_reapeter_fields    = array();
		if ( $post_acf_fields ) {
			foreach ( $post_acf_fields as $field => $value ) {
				if ( 'repeater' === isset( $value['sub_fields'] ) && is_array( $value['sub_fields'] ) && $value['type'] ) {
					$return_repater_fields = self::straker_acf_plugin_repeater_field( $value['name'], $value, $id, $value['label'] );
					if ( ! empty( $return_repater_fields ) ) {
						array_push( $full_reapeter_fields, $return_repater_fields );
					}
				} elseif ( 'flexible_content' === $value['type'] ) {
					$flexible_content_fields = self::straker_acf_plugin_flexible_content_field( $value['name'], $value, $id, $value['label'] );
					if ( ! empty( $flexible_content_fields ) ) {
						array_push( $full_reapeter_fields, $flexible_content_fields );
					}
				} elseif ( in_array( $value['type'], $content_field_types, true ) ) {
					array_push(
						$acf_data,
						array(
							'field_id'    => $value['id'],
							'field_key'   => $value['key'],
							'field_label' => $value['label'],
							'field_name'  => $value['name'],
							'field_type'  => $value['type'],
							'field_value' => $value['value'],
							'acf_data'    => 'yes',
							'acf_field'   => 'yes',
						)
					);
				} elseif ( in_array( $value['type'], $non_content_field_types, true ) ) {
					array_push(
						$acf_data,
						array(
							'field_id'    => $value['id'],
							'field_key'   => $value['key'],
							'field_label' => $value['label'],
							'field_name'  => $value['name'],
							'field_type'  => $value['type'],
							'translate'   => 'false',
							'acf_data'    => 'yes',
							'acf_field'   => 'yes',
						)
					);
				}
			}
		}
		return array_merge( $acf_data, $full_reapeter_fields );
	}

	/**
	 * Advanced Custom Fields plugin --- Repeater Field
	 *
	 * @param string $repeater_field_name ACF Field Name.
	 * @param array  $repeater_sub_fields ACF SubFields Name.
	 * @param int    $post_id Posts Post ID.
	 * @param string $label Label.
	 */
	public static function straker_acf_plugin_repeater_field( $repeater_field_name, $repeater_sub_fields, $post_id, $label ) {
		$content_field_types     = self::$acf_content_fields;
		$non_content_field_types = self::$acf_non_content_fields;
		$return_repater_fields   = array();

		if ( have_rows( $repeater_field_name, $post_id ) ) {
			foreach ( array_unique( $repeater_sub_fields['sub_fields'], SORT_REGULAR ) as $key => $sub_field ) {
				foreach ( $repeater_sub_fields['value'] as $sub_field_num => $sub_field_array ) {
					foreach ( $sub_field_array as $sub_field_name => $sub_field_value ) {
						$key_detail = get_sub_field_object( $sub_field_name );

						if ( $sub_field_name === $key_detail['_name'] && ! empty( $sub_field_value ) ) {
							if ( in_array( $key_detail['type'], $content_field_types, true ) ) {
								$sub_field_name  = $repeater_field_name . '_' . $sub_field_num . '_' . $key_detail['_name'];
								$field_meta_val  = get_post_meta( $post_id, $sub_field_name, true );
								$field_meta_key  = get_post_meta( $post_id, '_' . $sub_field_name, true );
								$repeater_fields = get_post_meta( $post_id, $repeater_field_name, true );
								array_push(
									$return_repater_fields,
									array(
										'field_id'        => $key_detail['id'],
										'repeater_name'   => $repeater_field_name,
										'repeater_fields' => $repeater_fields,
										'field_key'       => $field_meta_key,
										'field_label'     => $key_detail['label'],
										'field_name'      => $sub_field_name,
										'field_type'      => $key_detail['type'],
										'field_value'     => $field_meta_val,
										'acf_data'        => 'yes',
										'acf_is_repeater' => 'yes',
									)
								);

							} elseif ( in_array( $key_detail['type'], $non_content_field_types, true ) ) {
								$sub_field_name  = $repeater_field_name . '_' . $sub_field_num . '_' . $key_detail['_name'];
								$field_meta_key  = get_post_meta( $post_id, '_' . $sub_field_name, true );
								$repeater_fields = get_post_meta( $post_id, $repeater_field_name, true );
								array_push(
									$return_repater_fields,
									array(
										'field_id'        => $key_detail['id'],
										'repeater_name'   => $repeater_field_name,
										'repeater_fields' => $repeater_fields,
										'field_key'       => $field_meta_key,
										'field_label'     => $key_detail['label'],
										'field_name'      => $sub_field_name,
										'field_type'      => $key_detail['type'],
										'acf_is_repeater' => 'yes',
										'acf_data'        => 'yes',
										'translate'       => 'false',
									)
								);
							}
						}
					}
				}
				return $return_repater_fields;
			}
		}
	}

	/**
	 * Advanced Custom Fields plugin --- Flexible Content Field
	 *
	 * @param string $fc_field_name ACF Field Name.
	 * @param array  $fc_sub_fields ACF SubFields Name.
	 * @param int    $post_id Posts Post ID.
	 * @param string $label Label.
	 */
	public static function straker_acf_plugin_flexible_content_field( $fc_field_name, $fc_sub_fields, $post_id, $label ) {
		$content_field_types     = self::$acf_content_fields;
		$non_content_field_types = self::$acf_non_content_fields;
		$return_fc_fields        = array();

		if ( have_rows( $fc_field_name, $post_id ) ) {
			foreach ( $fc_sub_fields['value'] as $sb_number => $sb_array ) {
				foreach ( $sb_array as $sb_name => $sb_value ) {
					$sb_detail = get_sub_field_object( $sb_name );
					if ( ! is_array( $sb_value ) ) {
						if ( in_array( $sb_detail['type'], $content_field_types, true ) && $sb_name === $sb_detail['_name'] ) {
							$sub_field_name = $fc_field_name . '_' . $sb_number . '_' . $sb_name;
							$field_meta_val = get_post_meta( $post_id, $sub_field_name, true );
							$field_meta_key = get_post_meta( $post_id, '_' . $sub_field_name, true );
							array_push(
								$return_fc_fields,
								array(
									'field_id'    => $sb_detail['id'],
									'fc_name'     => $fc_field_name,
									'field_key'   => $field_meta_key,
									'field_label' => $sb_detail['label'],
									'field_name'  => $sub_field_name,
									'field_type'  => $sb_detail['type'],
									'field_value' => $field_meta_val,
									'acf_is_fc'   => 'yes',
									'acf_data'    => 'yes',
								)
							);
						} elseif ( in_array( $sb_detail['type'], $non_content_field_types, true ) && $sb_name === $sb_detail['_name'] ) {
							$sub_field_name = $fc_field_name . '_' . $sb_number . '_' . $sb_name;
							$field_meta_key = get_post_meta( $post_id, '_' . $sub_field_name, true );
							array_push(
								$return_fc_fields,
								array(
									'field_id'    => $sb_detail['id'],
									'fc_name'     => $fc_field_name,
									'field_key'   => $field_meta_key,
									'field_label' => $sb_detail['label'],
									'field_name'  => $sub_field_name,
									'field_type'  => $sb_detail['type'],
									'translate'   => 'false',
									'acf_is_fc'   => 'yes',
									'acf_data'    => 'yes',
								)
							);
						}
					} elseif ( 'repeater' === $sb_detail['type'] && is_array( $sb_value ) ) {
						if ( 'repeater' === $sb_detail['type'] && is_array( $sb_detail['sub_fields'] ) && $sb_name === $sb_detail['_name'] ) {
							foreach ( $sb_value as $fc_rp_num => $fc_rp_sf ) {
								foreach ( $fc_rp_sf as $fc_rp_sf_name => $fc_rp_value ) {
									$sub_field_name = $fc_field_name . '_' . $sb_number . '_' . $sb_name . '_' . $fc_rp_num . '_' . $fc_rp_sf_name;
									$rp_sb_detail   = get_field_object( $sub_field_name );

									if ( in_array( $rp_sb_detail['type'], $content_field_types, true ) && ! empty( $rp_sb_detail['value'] ) ) {
										$sub_field_name      = $fc_field_name . '_' . $sb_number . '_' . $sb_name . '_' . $fc_rp_num . '_' . $fc_rp_sf_name;
										$field_meta_val      = get_post_meta( $post_id, $sub_field_name, true );
										$field_meta_key      = get_post_meta( $post_id, '_' . $sub_field_name, true );
										$repeater_field_name = $fc_field_name . '_' . $sb_number . '_' . $sb_name;
										$repeater_fields     = get_post_meta( $post_id, $repeater_field_name, true );
										$repeater_field_key  = get_post_meta( $post_id, '_' . $repeater_field_name, true );

										array_push(
											$return_fc_fields,
											array(
												'field_id' => $rp_sb_detail['id'],
												'fc_name'  => $fc_field_name,
												'repeater_name' => $repeater_field_name,
												'repeater_fields' => $repeater_fields,
												'repeater_field_key' => $repeater_field_key,
												'field_key' => $field_meta_key,
												'field_value' => $field_meta_val,
												'field_label' => $rp_sb_detail['label'],
												'field_name' => $sub_field_name,
												'field_type' => $sb_detail['type'],
												'acf_is_fc_repeater' => 'yes',
												'acf_data' => 'yes',
											)
										);
									} elseif ( in_array( $rp_sb_detail['type'], $non_content_field_types, true ) && ! empty( $rp_sb_detail['value'] ) && $fc_rp_sf_name === $rp_sb_detail['name'] ) {
										$field_meta_key      = get_post_meta( $post_id, '_' . $sub_field_name, true );
										$repeater_field_name = $fc_field_name . '_' . $sb_number . '_' . $sb_name;
										$repeater_fields     = get_post_meta( $post_id, $repeater_field_name, true );
										$repeater_field_key  = get_post_meta( $post_id, '_' . $repeater_field_name, true );
										array_push(
											$return_fc_fields,
											array(
												'field_id' => $rp_sb_detail['id'],
												'fc_name'  => $fc_field_name,
												'repeater_name' => $repeater_field_name,
												'repeater_fields' => $repeater_fields,
												'repeater_field_key' => $repeater_field_key,
												'field_key' => $field_meta_key,
												'field_label' => $rp_sb_detail['label'],
												'field_name' => $sub_field_name,
												'field_type' => $sb_detail['type'],
												'translate' => 'false',
												'acf_is_fc_repeater' => 'yes',
												'acf_data' => 'yes',
											)
										);
									}
								}
							}
						}
					} elseif ( 'gallery' === $sb_detail['type'] && is_array( $sb_value ) ) {
						$sub_field_name = $fc_field_name . '_' . $sb_number . '_' . $sb_name;
						$field_meta_key = get_post_meta( $post_id, '_' . $sub_field_name, true );
						array_push(
							$return_fc_fields,
							array(
								'field_id'    => $sb_detail['id'],
								'fc_name'     => $fc_field_name,
								'field_key'   => $field_meta_key,
								'field_label' => $sb_detail['label'],
								'field_name'  => $sub_field_name,
								'field_type'  => $sb_detail['type'],
								'translate'   => 'false',
								'acf_is_fc'   => 'yes',
								'acf_data'    => 'yes',
							)
						);
					}
				}
			}
			return $return_fc_fields;
		}

	}
}
