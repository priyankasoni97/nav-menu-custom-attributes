<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/priyankasoni97/
 * @since             1.0.0
 * @package           Nav_Menu_Custom_Attributes
 *
 * @wordpress-plugin
 * Plugin Name:       Navigation Menu Custom Attributes
 * Plugin URI:        https://github.com/priyankasoni97/nav-menu-custom-attributes/
 * Description:       The plugin that helps you to add custom attributes in WordPress navigation menu.
 * Version:           1.0.0
 * Author:            Priyanka Soni
 * Author URI:        https://github.com/priyankasoni97/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nav-menu-custom-attributes
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NAV_MENU_CUSTOM_ATTRIBUTES_VERSION', '1.0.0' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nav-menu-custom-attributes.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nav_menu_custom_attributes() {

	$plugin = new Nav_Menu_Custom_Attributes();
	$plugin->run();

}
run_nav_menu_custom_attributes();
