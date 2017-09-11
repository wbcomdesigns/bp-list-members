<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Bp_List_Members
 * @subpackage Bp_List_Members/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Bp_List_Members
 * @subpackage Bp_List_Members/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Bp_List_Members_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bp-list-members',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
