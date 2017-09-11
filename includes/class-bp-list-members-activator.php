<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Bp_List_Members
 * @subpackage Bp_List_Members/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Bp_List_Members
 * @subpackage Bp_List_Members/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Bp_List_Members_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        if (!is_plugin_active('buddypress/bp-loader.php') and current_user_can('activate_plugins')) {
            // Stop activation redirect and show error
            wp_die('Sorry, but this plugin requires the BuddyPress Plugin to be installed and active. <br><a href="' . admin_url('plugins.php') . '">&laquo; Return to Plugins</a>');
        }
    }

}
