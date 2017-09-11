<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Bp_List_Members
 * @subpackage Bp_List_Members/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Bp_List_Members
 * @subpackage Bp_List_Members/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Bp_List_Members {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Bp_List_Members_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_name = 'bp-list-members';
        $this->version = '1.0.0';
        self::constants();
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    #define constant variables

    public static function constants() {
        define('BLM_GROUPS_OPTIONS', 'blm_groups_options');
        define('BLM_IN_LIST', 'blm_in_list');
        define('BLM_IN_LIST_CHECK', 'blm_in_list_check');
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Bp_List_Members_Loader. Orchestrates the hooks of the plugin.
     * - Bp_List_Members_i18n. Defines internationalization functionality.
     * - Bp_List_Members_Admin. Defines all hooks for the admin area.
     * - Bp_List_Members_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bp-list-members-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bp-list-members-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-bp-list-members-admin.php';

        /**
         * The class responsible for defining Tabs
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bp-list-members-ajax-tabs.php';
        
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-bp-list-members-public.php';

        $this->loader = new Bp_List_Members_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Bp_List_Members_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Bp_List_Members_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Bp_List_Members_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_notices', $plugin_admin, 'blm_admin_notice');
        
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Bp_List_Members_Public($this->get_plugin_name(), $this->get_version());
        $plugin_public_tabs = new Bp_List_Members_Ajax_Tabs($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('bp_get_add_friend_button', $plugin_public, 'bp_list_members_add_friend_button',1);
        $this->loader->add_action('bp_setup_nav', $plugin_public_tabs, 'bp_list_members_profile_close_friends_tab', 301);
        $this->loader->add_action('bp_setup_nav', $plugin_public_tabs, 'bp_list_members_profile_tabs', 301);
        $this->loader->add_filter('bp_user_query_populate_extras', $plugin_public, 'bp_list_members_bp_friends_filter_user_query_populate_extras', 4, 2);
        $this->loader->add_action('wp_ajax_nopriv_bp_list_members_dynamic_firends_group_papulate_ajax', $plugin_public, 'bp_list_members_dynamic_firends_group_papulate_ajax');
        $this->loader->add_action('wp_ajax_bp_list_members_dynamic_firends_group_papulate_ajax', $plugin_public, 'bp_list_members_dynamic_firends_group_papulate_ajax');
        $this->loader->add_action('wp_ajax_nopriv_blm_firends_group_delete_ajax', $plugin_public, 'blm_firends_group_delete_ajax');
        $this->loader->add_action('wp_ajax_blm_firends_group_delete_ajax', $plugin_public, 'blm_firends_group_delete_ajax');
        $this->loader->add_action('wp_ajax_nopriv_blm_firends_list_switch_ajax', $plugin_public, 'blm_firends_list_switch_ajax');
        $this->loader->add_action('wp_ajax_blm_firends_list_switch_ajax', $plugin_public, 'blm_firends_list_switch_ajax');
        $this->loader->add_action('blm_get_members_pagination_count', $plugin_public, 'blm_get_members_pagination_count_callback');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Bp_List_Members_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}
