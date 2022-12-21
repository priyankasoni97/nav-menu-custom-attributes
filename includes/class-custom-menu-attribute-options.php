<?php
/**
 * The file that defines the setup for custom menu attributes for WordPress nav menus.
 *
 * @link       https://github.com/priyankasoni97/
 * @since      1.0.0
 *
 * @package    Custom_Menu_Attributes_For_Wordpress
 * @subpackage Custom_Menu_Attributes_For_Wordpress/includes
 */

// include this so we can access Walker_Nav_Menu_Edit.
require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

/**
 * The custom menu attributes option class.
 *
 * This is used to add custom attrinutes for menu link in WordPress nav menu items.
 *
 * @since      1.0.0
 * @package    Custom_Menu_Attributes_For_Wordpress
 * @subpackage Custom_Menu_Attributes_For_Wordpress/includes
 * @author     Priyanka Soni <priyanka.soni@cmsminds.com>
 */
class Custom_Menu_Attribute_Options extends Walker_Nav_Menu_Edit {
	/**
	 * Stores custom fields for nav menu items.
	 *
	 * @var array $displayed_fields Holds the menu items custom fields.
	 */
	public static $displayed_fields = array();

	/**
	 * Create an array with all the new fields.
	 */
	public static function get_custom_fields() {
		return array(
			array(
				'locations'   => array(),
				'type'        => 'text',
				'name'        => 'custom_attributes',
				'label'       => __( 'Custom Attributes', 'custom-menu-attributes-for-wordpress' ),
				'description' => '',
				'scripts'     => '',
				'styles'      => '',
			),
		);
	}

	/**
	 * Append the new fields to the menu system.
	 *
	 * @param string $output Holds menu option html.
	 * @param object $item Holds menu item detail.
	 * @param int    $depth used for padding.
	 * @param array  $args is nav menu object.
	 * @param int    $id is id of current menu item.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$all_menus      = get_nav_menu_locations();
		$assigned_menus = get_the_terms( $item->ID, 'nav_menu' );

		$fields = self::get_custom_fields();

		$fields_markup = '';

		// Get the menu item.
		parent::start_el( $item_output, $item, $depth, $args );

		// Set up each new custom field.
		foreach ( $fields as $field ) {
			// if fixed locations are set, see if the menu is assigned to that location, and if not, skip the field.
			if ( $field['locations'] ) {
				$skip = true;

				if ( $all_menus ) {
					foreach ( $field['locations'] as $location ) {
						if ( isset( $all_menus[ $location ] ) ) {
							foreach ( $assigned_menus as $assigned_menu ) {
								if ( $assigned_menu->term_id === $all_menus[ $location ] ) {
									$skip = false;
									continue;
								}
							}
						}
						if ( false === $skip ) {
							continue;
						}
					}
				}

				if ( true === $skip ) {
					continue;
				}
			}

			// Store the displayed fields for later use.
			if ( ! in_array( $field['name'], self::$displayed_fields, true ) ) {
				self::$displayed_fields[] = $field['name'];
			}

			// Retrieve the existing value from the database.
			$field['meta_value'] = get_post_meta( $item->ID, '_menu_item_' . $field['name'], true ); // @codingStandardsIgnoreLine WordPress.VIP.SlowDBQuery.slow_db_query 

			$fields_markup .= "<p class='field-{$field["name"]} description description-wide'>";
			$fields_markup .= "<label for='edit-menu-item-{$field["name"]}-{$item->ID}'>";
			$fields_markup .= "{$field["label"]}<br>";
			$fields_markup .= "<input id='edit-menu-item-{$field["name"]}-{$item->ID}' class='widefat edit-menu-item-{$field["name"]}' name='menu-item-{$field["name"]}[{$item->ID}]' value='{$field["meta_value"]}' type='text' />";
			$fields_markup .= '</label>';
			$fields_markup .= '</p>';
		}

		// Insert the new markup before the fieldset tag.
		$item_output = preg_replace( '/(<fieldset)/', "{$fields_markup}$1", $item_output );

		// Update the output.
		$output .= $item_output;
	}

	/**
	 * Save the new fields.
	 *
	 * @param int $post_id Holds menu item id.
	 */
	public static function save_field_data( $post_id ) {
		if ( get_post_type( $post_id ) !== 'nav_menu_item' ) {
			return;
		}

		$post_object   = get_post( $post_id );
		$custom_fields = self::get_custom_fields();

		foreach ( $custom_fields as $field ) {
			$post_key = "menu-item-{$field["name"]}";
			$meta_key = "_menu_item_{$field["name"]}";

			$field["value"] = isset( $_POST[ $post_key ][ $post_id ] ) ? sanitize_text_field( $_POST[ $post_key ][ $post_id ] ) : ''; // phpcs:ignore
			update_post_meta( $post_id, $meta_key, $field['value'] );
		}
	}

	/**
	 * Add the save function to the save_post action.
	 */
	public static function setup_custom_fields() {
		add_action( 'save_post', array( __CLASS__, 'save_field_data' ) );
	}

	/**
	 * Insert the screen options.
	 *
	 * @param object $args Holds nav menu object.
	 */
	public static function insert_custom_screen_options( $args ) {
		$fields = self::get_custom_fields();

		foreach ( $fields as $field ) {
			if ( in_array( $field['name'], self::$displayed_fields, true ) ) {
				$args[ $field['name'] ] = $field['label'];
			}
		}

		return $args;
	}
}
