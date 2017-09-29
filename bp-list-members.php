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

//Define Plugin Constants
define( 'BPLM_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'BPLM_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'BPLM_TEXT_DOMAIN', 'bp-list-members' );


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

add_action('plugins_loaded', 'bp_list_members_plugin_init');

/**
 * Function to check buddypress is active to enable disable plugin functionality.
 */
 function bp_list_members_plugin_init(){
 	$bp_active = in_array( 'buddypress/bp-loader.php', get_option( 'active_plugins' ) );
    if ( current_user_can('activate_plugins') && $bp_active !== true ) {
        add_action('admin_notices', 'bp_list_member_plugin_admin_notice');
    } else {
        run_bp_list_members();
        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'bp_list_members_plugin_links' );
    }
 }

 /**
 * Function to show admin notice when BuddyPress is deactivate.
 */
function bp_list_member_plugin_admin_notice() {
    $bplist_plugin = 'BP List Members';
    $bp_plugin = 'BuddyPress';

    echo '<div class="error"><p>'
    . sprintf(__('%1$s is ineffective as it requires %2$s to be installed and active.', BPLM_TEXT_DOMAIN), '<strong>' . $bplist_plugin . '</strong>', '<strong>' . $bp_plugin . '</strong>')
    . '</p></div>';
    if (isset($_GET['activate'])) unset($_GET['activate']);
}

/**
 * Function to add plugin links.
 * @param      string    $links
 * @return     string    $links
 */
function bp_list_members_plugin_links( $links ) {
    $bplist_links = array(
        '<a href="https://wbcomdesigns.com/contact/" target="_blank">'.__( 'Support', BPLM_TEXT_DOMAIN ).'</a>'
    );
    return array_merge( $links, $bplist_links );
}

