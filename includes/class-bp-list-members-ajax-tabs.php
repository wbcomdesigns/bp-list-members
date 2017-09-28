<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Bp_List_Members
 * @subpackage Bp_List_Members/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bp_List_Members
 * @subpackage Bp_List_Members/public
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Bp_List_Members_Ajax_Tabs {

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
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function bp_list_members_profile_close_friends_tab() {

        if (bp_is_my_profile()) :
            global $bp;
            $name = bp_get_displayed_user_username();
            $parent_slug = 'friends';
            bp_core_new_subnav_item(
                    array(
                        'name' => __('Close Friends', $this->plugin_name),
                        'slug' => 'close_friends',
                        'parent_url' => $bp->loggedin_user->domain . $parent_slug . '/',
                        'parent_slug' => $parent_slug,
                        'screen_function' => array($this, 'bp_list_members_close_friends_show_screen'),
                        'position' => 100,
                        'default_subnav_slug' => 'close_friends',
                        'link' => site_url() . "/members/$name/$parent_slug/close_friends/",
                    )
            );
        endif;
    }

    public function bp_list_members_close_friends_show_screen() {
        add_action('bp_template_title', array($this, 'bp_list_members_close_friends_show_title'));
        add_action('bp_template_content', array($this, 'bp_list_members_close_friends_show_content'));
        bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
    }

    public function bp_list_members_close_friends_show_title() {
        _e('Close Friends List',BPLM_TEXT_DOMAIN);
    }

    public function bp_list_members_close_friends_show_content() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bp-list-members-loop.php';
    }

    public function bp_list_members_profile_tabs() {

        global $bp;
        $name = bp_get_displayed_user_username();
        $user_id = bp_loggedin_user_id();
        $parent_slug = 'friends';
        $exist_in_list = get_option(BLM_IN_LIST);
        $bp_user_groups = get_option(BLM_GROUPS_OPTIONS);
        if (!empty($bp_user_groups)) {
            $i = 100;
            foreach ($bp_user_groups as $ukey => $gname) {
                if ($user_id === $ukey) {
                    foreach ($gname as $key => $value) {
                        $group_name = $value;
                        $group_slug = strtolower(preg_replace('/\s+/', '_', $value));

                        bp_core_new_subnav_item(
                                array(
                                    'name' => __($group_name, $this->plugin_name),
                                    'slug' => $group_slug,
                                    'parent_url' => $bp->loggedin_user->domain . $parent_slug . '/',
                                    'parent_slug' => $parent_slug,
                                    'screen_function' => array($this, 'bp_list_members_show_screen_tabs_handler'),
                                    'position' => $i,
                                    'default_subnav_slug' => $group_slug,
                                    'link' => site_url() . "/members/$name/$parent_slug/$group_slug/",
                                )
                        );
                        $i++;
                    }
                }
            }
        }
    }

    public function bp_list_members_show_screen_tabs_handler() {
        add_action('bp_template_title', array($this, 'bp_list_members_show_title_tabs'));
        add_action('bp_template_content', array($this, 'bp_list_members_show_content_tabs'));
        bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
    }

    public function bp_list_members_show_title_tabs() {
        $bp_user_groups = get_option(BLM_GROUPS_OPTIONS);
        $user_id = bp_loggedin_user_id();
        if (!empty($bp_user_groups)) {
            $name = bp_get_displayed_user_username();
            $parent_slug = 'friends';
            $r = $_SERVER['REQUEST_URI'];
            $r = explode('/', $r);
            $r = array_filter($r);
            if (in_array($name, $r)) {
                $name_index = 0;
                if ($index = array_search($name, $r)) {
                    $name_index = $index;
                }
                $parent_index = 0;
                if ($f_index = array_search($parent_slug, $r)) {
                    $parent_index = $f_index;
                }
                $p = $name_index + 1;
                if ($r[$parent_index] === $r[$p]) {
                    $p_current = $name_index + 2;
                    $current_screen = $r[$p_current];
                }
            }
            foreach ($bp_user_groups as $u_key => $applied_value) {
                if ($u_key === $user_id) {
                    foreach ($applied_value as $key => $value) {
                        $applied_value_slug = strtolower(preg_replace('/\s+/', '_', $value));
                        if ($applied_value_slug === $current_screen) {
                            echo '<span id="blm_group_h1">' . $value . '</span>';
                        }
                    }
                }
            }
        }
    }

    public function bp_list_members_show_content_tabs() {
        $bp_user_groups = get_option(BLM_GROUPS_OPTIONS);
        $user_id = bp_loggedin_user_id();
        if (!empty($bp_user_groups)) {
            $name = bp_get_displayed_user_username();
            $parent_slug = 'friends';
            $r = $_SERVER['REQUEST_URI'];
            $r = explode('/', $r);
            $r = array_filter($r);
            if (in_array($name, $r)) {
                $name_index = 0;
                if ($index = array_search($name, $r)) {
                    $name_index = $index;
                }
                $parent_index = 0;
                if ($f_index = array_search($parent_slug, $r)) {
                    $parent_index = $f_index;
                }
                $p = $name_index + 1;
                if ($r[$parent_index] === $r[$p]) {
                    $p_current = $name_index + 2;
                    $current_screen = $r[$p_current];
                }
            }
            foreach ($bp_user_groups as $applied_key => $applied_value) {
                if ($user_id === $applied_key) {
                    foreach ($applied_value as $key => $value) {
                        $applied_value_slug = strtolower(preg_replace('/\s+/', '_', $value));
                        if ($applied_value_slug === $current_screen) {
                            echo '<span id="blm_group_delete"><a href="javascript:void(0)" class="button" id="delete_group" data-id="' . $current_screen . '">'.__('Delete List',BPLM_TEXT_DOMAIN).'</a></span>';
                            require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bp-list-members-loop.php';
                        }
                    }
                }
            }
        }
    }
}
