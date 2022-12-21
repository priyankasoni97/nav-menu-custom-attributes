<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/priyankasoni97/
 * @since      1.0.0
 *
 * @package    Nav_Menu_Custom_Attributes
 * @subpackage Nav_Menu_Custom_Attributes/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nav_Menu_Custom_Attributes
 * @subpackage Nav_Menu_Custom_Attributes/public
 * @author     Priyanka Soni <priyanka.soni@cmsminds.com>
 */
class Nav_Menu_Custom_Attributes_Public {

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
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Callback function for nav_menu_link_attributes filter which add custom attributes to menu link.
	 *
	 * @param array  $atts Holds attributes array.
	 * @param object $item Holds menu item details.
	 * @param object $args Hold nav menu arguments.
	 */
	public function nav_menu_link_attributes_callback( $atts, $item, $args ) {
		$custom_attributes = get_post_meta( $item->ID, '_menu_item_custom_attributes', true );
		$attributes        = explode( ',', $custom_attributes );
		foreach ( $attributes as $attribute ) {
			$attribute_key          = explode( '-', $attribute )[0];
			$attribute_value        = explode( '-', $attribute )[1];
			$attribute_key          = str_replace( ':', '-', $attribute_key );
			$atts[ $attribute_key ] = $attribute_value;
		}
		return $atts;
	}

}
