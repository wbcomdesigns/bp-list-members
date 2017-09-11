<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wbcomdesigns.com
 * @since             1.0.0
 * @package           Bp_List_Members
 *
 * @wordpress-plugin
 * Plugin Name:       BP List Members
 * Plugin URI:        http://wbcomdesigns.com
 * Description:       This plugin will add an extended feature to the big name “BuddyPress” that will allow to create friends group/list and list all selected members to the specific group/list.
 * Version:           1.0.0
 * Author:            Wbcom Designs
 * Author URI:        http://wbcomdesigns.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bp-list-members
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bp-list-members-activator.php
 */
function activate_bp_list_members() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bp-list-members-activator.php';
	Bp_List_Members_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bp-list-members-deactivator.php
 */
function deactivate_bp_list_members() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bp-list-members-deactivator.php';
	Bp_List_Members_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bp_list_members' );
register_deactivation_hook( __FILE__, 'deactivate_bp_list_members' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bp-list-members.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bp_list_members() {

	$plugin = new Bp_List_Members();
	$plugin->run();

}
run_bp_list_members();
