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
class Bp_List_Members_Public {

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

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Bp_List_Members_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Bp_List_Members_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style('wpb-fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/bp-list-members-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Bp_List_Members_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Bp_List_Members_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/bp-list-members-public.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'blm_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    public function bp_list_members_add_friend_button($button) {
        $user_id = bp_loggedin_user_id();
        if (!$user_id) {
            return;
        }
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
            if (isset($r[$parent_index]) && $r[$parent_index] === $r[$p]) {
                $p_current = $name_index + 2;
                if (isset($r[$p_current])) {
                    $current_screen = $r[$p_current];
                }
            }
        }
        $link_id = explode('-', $button['link_id']);
        $fid = $link_id[1];
        $exist_in_list_temp = get_option(BLM_IN_LIST);
        $exist_in_list_tmp = array();
        $exist_in_list = array();
        if (!empty($exist_in_list_temp)) {
            foreach ($exist_in_list_temp as $key => $value) {
                $t = explode('@', $key);
                if (isset($current_screen) && $value === 1 && $current_screen === $t[0]) {
                    $exist_in_list_tmp[] = $t[1];
                }
                if ($t[1] === $fid) {
                    $exist_in_list[$t[0]] = $value;
                }
            }
        }

        $bp_user_groups = get_option(BLM_GROUPS_OPTIONS);

        $fricon_remove = '<i class="fa fa-caret-down" data-original-title="' . __("Friends", BPLM_TEXT_DOMAIN) . '" data-toggle="tooltip"></i>';
        $user_groups = '';
        if (in_array($fid, $exist_in_list_tmp)) {
            $euser_groups = '';
            if (!empty($bp_user_groups)) {
                $suser_groups = '';
                foreach ($bp_user_groups as $applied_key => $applied_value) {
                    $user_groups_tmp = '';
                    if ($user_id === $applied_key) {

                        if (isset($exist_in_list['close_friends']) && $exist_in_list['close_friends'] === 1) {
                            $user_groups_tmp = '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="close_friends-' . $button['link_id'] . '" data-slug="close_friends" class="blm_friends close_friends" id="close_friends-' . $button['link_id'] . '"><span><i class="blm_tick  fa fa-check" aria-hidden="true"></i></span>'.__('Close Friends',BPLM_TEXT_DOMAIN).'</a>';
                        } else {
                            $user_groups_tmp = '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="close_friends-' . $button['link_id'] . '" data-slug="close_friends" class="blm_friends close_friends" id="close_friends-' . $button['link_id'] . '"><span><i class="blm_tick" aria-hidden="true"></i></span>'.__('Close Friends',BPLM_TEXT_DOMAIN).'</a>';
                        }
                        foreach ($applied_value as $key => $a_value) {
                            $ch = '';
                            $group_slug = strtolower(preg_replace('/\s+/', '_', $a_value));
                            if (isset($exist_in_list[$group_slug]) && $exist_in_list[$group_slug] === 1) {
                                $ch = ' fa fa-check';
                            } else {
                                $ch = '';
                            }
                            $user_groups_tmp .= '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="' . $group_slug . '-' . $button['link_id'] . '" data-slug="' . $group_slug . '" class = "blm_friends ' . $group_slug . '" id = "' . $group_slug . '-' . $button['link_id'] . '"><span><i class="blm_tick' . $ch . '" aria-hidden="true"></i></span>' . ' ' . $a_value . '</a>';
                        }
                        $suser_groups = $user_groups_tmp;
                    } else {
                        if (isset($exist_in_list['close_friends']) && $exist_in_list['close_friends'] === 1) {
                            $user_groups_tmp = '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="close_friends-' . $button['link_id'] . '" data-slug="close_friends" class="blm_friends close_friends" id="close_friends-' . $button['link_id'] . '"><span><i class="blm_tick  fa fa-check" aria-hidden="true"></i></span>'.__('Close Friends',BPLM_TEXT_DOMAIN).'</a>';
                        } else {
                            $user_groups_tmp = '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="close_friends-' . $button['link_id'] . '" data-slug="close_friends" class="blm_friends close_friends" id="close_friends-' . $button['link_id'] . '"><span><i class="blm_tick" aria-hidden="true"></i></span>'.__('Close Friends',BPLM_TEXT_DOMAIN).'</a>';
                        }
                        $suser_groups = $user_groups_tmp;
                    }
                }
                $euser_groups = $suser_groups;
            } else {
                if (isset($exist_in_list['close_friends']) && $exist_in_list['close_friends'] === 1) {
                    $puser_groups = '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="close_friends-' . $button['link_id'] . '" data-slug="close_friends" class="blm_friends close_friends" id="close_friends-' . $button['link_id'] . '"><span><i class="blm_tick  fa fa-check" aria-hidden="true"></i></span>'.__('Close Friends',BPLM_TEXT_DOMAIN).'</a>';
                } else {
                    $puser_groups = '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="close_friends-' . $button['link_id'] . '" data-slug="close_friends" class="blm_friends close_friends" id="close_friends-' . $button['link_id'] . '"><span><i class="blm_tick" aria-hidden="true"></i></span>'.__('Close Friends',BPLM_TEXT_DOMAIN).'</a>';
                }
                $euser_groups = $puser_groups;
            }
            $user_groups = $euser_groups;
        } else {
            $euser_groups = '';
            if (!empty($bp_user_groups)) {
                $suser_groups = '';
                foreach ($bp_user_groups as $applied_key => $applied_value) {
                    $user_groups_tmp = '';
                    if ($user_id === $applied_key) {

                        if (isset($exist_in_list['close_friends']) && $exist_in_list['close_friends'] === 1) {
                            $user_groups_tmp = '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="close_friends-' . $button['link_id'] . '" data-slug="close_friends" class="blm_friends close_friends" id="close_friends-' . $button['link_id'] . '"><span><i class="blm_tick  fa fa-check" aria-hidden="true"></i></span>'.__('Close Friends',BPLM_TEXT_DOMAIN).'</a>';
                        } else {
                            $user_groups_tmp = '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="close_friends-' . $button['link_id'] . '" data-slug="close_friends" class="blm_friends close_friends" id="close_friends-' . $button['link_id'] . '"><span><i class="blm_tick" aria-hidden="true"></i></span>'.__('Close Friends',BPLM_TEXT_DOMAIN).'</a>';
                        }
                        foreach ($applied_value as $key => $a_value) {
                            $ch = '';
                            $group_slug = strtolower(preg_replace('/\s+/', '_', $a_value));
                            if (isset($exist_in_list[$group_slug]) && $exist_in_list[$group_slug] === 1) {
                                $ch = ' fa fa-check';
                            } else {
                                $ch = '';
                            }
                            $user_groups_tmp .= '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="' . $group_slug . '-' . $button['link_id'] . '" data-slug="' . $group_slug . '" class = "blm_friends ' . $group_slug . '" id = "' . $group_slug . '-' . $button['link_id'] . '"><span><i class="blm_tick' . $ch . '" aria-hidden="true"></i></span>' . ' ' . $a_value . '</a>';
                        }
                        $suser_groups = $user_groups_tmp;
                    } else {
                        if (isset($exist_in_list['close_friends']) && $exist_in_list['close_friends'] === 1) {
                            $user_groups_tmp = '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="close_friends-' . $button['link_id'] . '" data-slug="close_friends" class="blm_friends close_friends" id="close_friends-' . $button['link_id'] . '"><span><i class="blm_tick  fa fa-check" aria-hidden="true"></i></span>'.__('Close Friends',BPLM_TEXT_DOMAIN).'</a>';
                        } else {
                            $user_groups_tmp = '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="close_friends-' . $button['link_id'] . '" data-slug="close_friends" class="blm_friends close_friends" id="close_friends-' . $button['link_id'] . '"><span><i class="blm_tick" aria-hidden="true"></i></span>'.__('Close Friends',BPLM_TEXT_DOMAIN).'</a>';
                        }
                        $suser_groups = $user_groups_tmp;
                    }
                }
                $euser_groups = $suser_groups;
            } else {
                if (isset($exist_in_list['close_friends']) && $exist_in_list['close_friends'] === 1) {
                    $puser_groups = '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="close_friends-' . $button['link_id'] . '" data-slug="close_friends" class="blm_friends close_friends" id="close_friends-' . $button['link_id'] . '"><span><i class="blm_tick  fa fa-check" aria-hidden="true"></i></span>'.__('Close Friends',BPLM_TEXT_DOMAIN).'</a>';
                } else {
                    $puser_groups = '<a href = "javascript:void(0)" data-fid="' . $fid . '" data-id="close_friends-' . $button['link_id'] . '" data-slug="close_friends" class="blm_friends close_friends" id="close_friends-' . $button['link_id'] . '"><span><i class="blm_tick" aria-hidden="true"></i></span>'.__('Close Friends',BPLM_TEXT_DOMAIN).'</a>';
                }
                $euser_groups = $puser_groups;
            }
            $user_groups = $euser_groups;
        }
        $user_groups = isset($user_groups) ? $user_groups : '';
        if (bp_is_my_profile() || bp_is_current_component('members')) {
            if ($button['id'] === 'is_friend') {
                $link_href = $button['link_href'];
                $button['link_href'] = 'javascript:void(0)';
                $button['link_class'] .= ' bp-list-members-btn';
                $append_lists = '<div class="dropdown-content" id="dropdown-content-' . $button['link_id'] . '">'
                        . $user_groups
                        . '<div class="blm_create_list"><a href="javascript:void(0)" data-mid="' . $fid . '" class="add-new-friend" id="add-' . $button['link_id'] . '"><span><i class="fa fa-plus" aria-hidden="true"></i></span>'.__('Create List',BPLM_TEXT_DOMAIN).'</a><div class="blm_add_group"><input type="text" name="blm_group_name" class="blm_group_name" data-mmid="' . $fid . '" id="blm_group_name_' . $button['link_id'] . '" ></div></div>'
                        . '<div class="blm_list_msg"><span><i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i></i></span></div>'
                        . '<a class="blm_remove_friend" href="' . $link_href . '"><span><i class="fa fa-times" aria-hidden="true"></i></span>'.__('Unfriend',BPLM_TEXT_DOMAIN).'</a>'
                        . '</div>';
                $button['link_text'] = __('Friends', BPLM_TEXT_DOMAIN) . ' ' . $fricon_remove . $append_lists;
            }
        }


        return $button;
    }

    public function bp_list_members_bp_friends_filter_user_query_populate_extras(BP_User_Query $user_query, $user_ids_sql) {

        global $wpdb, $bp;
        $user_id = bp_loggedin_user_id();
        if (!$user_id) {
            return;
        }

        $maybe_friend_ids = wp_parse_id_list($user_ids_sql);
        $exist_in_list = get_option(BLM_IN_LIST);
        $ac = array();
        foreach ($maybe_friend_ids as $friend_id) {
            $status = BP_Friends_Friendship::check_is_friend($user_id, $friend_id);

            if ('is_friend' == $status) {

                if (get_option(BLM_IN_LIST_CHECK) === false) {
                    if (get_option(BLM_IN_LIST) == false) {
                        $bp_new_groups = array();
                        foreach ($maybe_friend_ids as $sfriend_id) {
                            $statuss = BP_Friends_Friendship::check_is_friend($user_id, $sfriend_id);
                            if ('is_friend' == $statuss) {
                                $bp_new_groups['close_friends@' . $sfriend_id] = 1;
                            }
                        }
                        $deprecated = null;
                        $autoload = 'no';
                        add_option(BLM_IN_LIST, $bp_new_groups, $deprecated, $autoload);
                    } else {
                        $bp_new_groups = get_option(BLM_IN_LIST);
                        foreach ($maybe_friend_ids as $sfriend_id) {
                            $statuss = BP_Friends_Friendship::check_is_friend($user_id, $sfriend_id);
                            if ('is_friend' == $statuss) {
                                $bp_new_groups['close_friends@' . $sfriend_id] = 1;
                            }
                        }
                        update_option(BLM_IN_LIST, $bp_new_groups);
                    }
                    update_option(BLM_IN_LIST_CHECK, 1);
                } else {
                    $ch = get_option(BLM_IN_LIST_CHECK);
                    if ($ch === 0) {
                        if (get_option(BLM_IN_LIST) == false) {
                            $bp_new_groups = array();
                            foreach ($maybe_friend_ids as $sfriend_id) {
                                $statuss = BP_Friends_Friendship::check_is_friend($user_id, $sfriend_id);
                                if ('is_friend' == $statuss) {
                                    $bp_new_groups['close_friends@' . $sfriend_id] = 1;
                                }
                            }
                            $deprecated = null;
                            $autoload = 'no';
                            add_option(BLM_IN_LIST, $bp_new_groups, $deprecated, $autoload);
                        } else {
                            $bp_new_groups = get_option(BLM_IN_LIST);
                            foreach ($maybe_friend_ids as $sfriend_id) {
                                $statuss = BP_Friends_Friendship::check_is_friend($user_id, $sfriend_id);
                                if ('is_friend' == $statuss) {
                                    $bp_new_groups['close_friends@' . $sfriend_id] = 1;
                                }
                            }
                            update_option(BLM_IN_LIST, $bp_new_groups);
                        }
                        update_option(BLM_IN_LIST_CHECK, 1);
                    }
                }
                if (!empty($exist_in_list)) {
                    foreach ($exist_in_list as $key => $value) {
                        if ($exist_in_list[$key] === 1) {
                            $ac[] = $key;
                        }
                    }
                }
                $user_query->results[$friend_id]->friendship_type = array_unique($ac);
            }
        }
    }

    public function bp_list_members_dynamic_firends_group_papulate_ajax() {
        if (isset($_POST['action'])) {
            $group_name = sanitize_text_field($_POST['group_name']);
            $member_id = sanitize_text_field($_POST['current_mid']);
            $group_slug = strtolower(preg_replace('/\s+/', '_', $group_name));
            $user_id = bp_loggedin_user_id();

            if (!empty($group_name)) {
                if (get_option(BLM_GROUPS_OPTIONS) !== false) {
                    $exist_option = get_option(BLM_GROUPS_OPTIONS);
                    if (!empty($exist_option)) {
                        if (get_option(BLM_IN_LIST) !== false) {
                            $exist_in_list = get_option(BLM_IN_LIST);
                            if (!empty($exist_in_list)) {

                                foreach ($exist_option as $user_id_specific => $group_names_specific) {
                                    if ($user_id_specific === $user_id) {
                                        if (!in_array($group_name, $group_names_specific)) {
                                            $exist_in_list[$group_slug . '@' . $member_id] = 1;
                                            $exist_option[$user_id_specific][] = $group_name;
                                            break;
                                        }
                                    } else {
                                        $exist_in_list[$group_slug . '@' . $member_id] = 1;
                                        $exist_option[$user_id][] = $group_name;
                                        break;
                                    }
                                }
                                print_r($exist_in_list);
                                update_option(BLM_IN_LIST, $exist_in_list);
                            } else {
                                $exist_in_list = array();
                                foreach ($exist_option as $user_id_specific => $group_names_specific) {
                                    if ($user_id_specific === $user_id) {
                                        if (!in_array($group_name, $group_names_specific)) {
                                            $exist_in_list[$group_slug . '@' . $member_id] = 1;
                                            $exist_option[$user_id_specific][] = $group_name;
                                            break;
                                        }
                                    } else {
                                        $exist_in_list[$group_slug . '@' . $member_id] = 1;
                                        $exist_option[$user_id][] = $group_name;
                                        break;
                                    }
                                }
                                update_option(BLM_IN_LIST, $exist_in_list);
                            }
                            update_option(BLM_GROUPS_OPTIONS, $exist_option);
                        } else {
                            $exist_in_list = array();
                            $exist_in_list[$group_slug . '@' . $member_id] = 1;
                            $deprecated = null;
                            $autoload = 'no';
                            add_option(BLM_IN_LIST, $exist_in_list, $deprecated, $autoload);
                        }
                    } else {
                        if (get_option(BLM_IN_LIST) !== false) {
                            $exist_in_list = get_option(BLM_IN_LIST);
                            $exist_in_list[$group_slug . '@' . $member_id] = 1;
                            update_option(BLM_IN_LIST, $exist_in_list);
                        } else {
                            $exist_in_list = array();
                            $exist_in_list[$group_slug . '@' . $member_id] = 1;
                            $deprecated = null;
                            $autoload = 'no';
                            add_option(BLM_IN_LIST, $exist_in_list, $deprecated, $autoload);
                        }

                        $bp_new_groups = array(
                            $user_id => array($group_name)
                        );
                        update_option(BLM_GROUPS_OPTIONS, $bp_new_groups);
                    }
                } else {
                    if (get_option(BLM_IN_LIST) !== false) {
                        $exist_in_list = get_option(BLM_IN_LIST);
                        $exist_in_list[$group_slug . '@' . $member_id] = 1;
                        update_option(BLM_IN_LIST, $exist_in_list);
                    } else {
                        $exist_in_list = array();
                        $exist_in_list[$group_slug . '@' . $member_id] = 1;
                        $deprecated = null;
                        $autoload = 'no';
                        add_option(BLM_IN_LIST, $exist_in_list, $deprecated, $autoload);
                    }
                    $bp_new_groups = array(
                        $user_id => array($group_name)
                    );
                    $deprecated = null;
                    $autoload = 'no';
                    add_option(BLM_GROUPS_OPTIONS, $bp_new_groups, $deprecated, $autoload);
                }
            }
        }
        die();
    }

    public function blm_firends_group_delete_ajax() {
        if (isset($_POST['action'])) {
            $group_slug = sanitize_text_field($_POST['group_slug']);
            $exist_option = get_option(BLM_GROUPS_OPTIONS);

            $user_id = bp_loggedin_user_id();
            if (!empty($exist_option)) {
                $data = array();
                $dcheck = false;
                foreach ($exist_option as $ukey => $exist_group_name) {
                    if ($ukey === $user_id) {
                        foreach ($exist_group_name as $key => $value) {
                            $exist_group_slug = strtolower(preg_replace('/\s+/', '_', $value));
                            if ($exist_group_slug === $group_slug) {
                                unset($exist_option[$ukey][$key]);
                                update_option(BLM_GROUPS_OPTIONS, $exist_option);
                                $data['status'] = 'success';
                                $dcheck = true;
                            }
                        }
                    }
                }
                if ($dcheck) {
                    $exist_list = get_option(BLM_IN_LIST);
                    if (!empty($exist_list)) {
                        foreach ($exist_list as $key => $value) {
                            $tmp = explode('@', $key);
                            if ($tmp[0] === $group_slug) {
                                unset($exist_list[$key]);
                            }
                        }
                        update_option(BLM_IN_LIST, $exist_list);
                    }
                }
                $data['furl'] = bp_loggedin_user_domain();
                echo json_encode($data);
            }
        }
        die();
    }

    public function blm_firends_list_switch_ajax() {
        if (isset($_POST['action'])) {
            $group_slug = sanitize_text_field($_POST['group_slug']);
            $member_id = sanitize_text_field($_POST['member_id']);
            $checked = sanitize_text_field($_POST['check']);
            if (get_option(BLM_IN_LIST) !== false) {
                $exist_in_list = get_option(BLM_IN_LIST);
                if ($checked === 'checked') {
                    $exist_in_list[$group_slug . '@' . $member_id] = 1;
                } else {
                    $exist_in_list[$group_slug . '@' . $member_id] = 0;
                }
                update_option(BLM_IN_LIST, $exist_in_list);
            }
        } else {
            $bp_new_groups = array();
            $bp_new_groups[$group_slug . '@' . $member_id] = 1;
            $deprecated = null;
            $autoload = 'no';
            add_option(BLM_IN_LIST, $bp_new_groups, $deprecated, $autoload);
        }
        die();
    }

    public function blm_get_members_pagination_count_callback($count) {
        global $members_template;
        $members_template->total_member_count = $count;
        if (empty($members_template->type))
            $members_template->type = '';

        $start_num = intval(( $members_template->pag_page - 1 ) * $members_template->pag_num) + 1;
        $from_num = bp_core_number_format($start_num);
        $to_num = bp_core_number_format(( $start_num + ( $members_template->pag_num - 1 ) > $members_template->total_member_count ) ? $members_template->total_member_count : $start_num + ( $members_template->pag_num - 1 ) );
        $total = bp_core_number_format($members_template->total_member_count);

        if ('active' == $members_template->type) {
            if (1 == $members_template->total_member_count) {
                $pag = __('Viewing 1 active member', 'buddypress');
            } else {
                $pag = sprintf(_n('Viewing %1$s - %2$s of %3$s active member', 'Viewing %1$s - %2$s of %3$s active members', $members_template->total_member_count, 'buddypress'), $from_num, $to_num, $total);
            }
        } elseif ('popular' == $members_template->type) {
            if (1 == $members_template->total_member_count) {
                $pag = __('Viewing 1 member with friends', 'buddypress');
            } else {
                $pag = sprintf(_n('Viewing %1$s - %2$s of %3$s member with friends', 'Viewing %1$s - %2$s of %3$s members with friends', $members_template->total_member_count, 'buddypress'), $from_num, $to_num, $total);
            }
        } elseif ('online' == $members_template->type) {
            if (1 == $members_template->total_member_count) {
                $pag = __('Viewing 1 online member', 'buddypress');
            } else {
                $pag = sprintf(_n('Viewing %1$s - %2$s of %3$s online member', 'Viewing %1$s - %2$s of %3$s online members', $members_template->total_member_count, 'buddypress'), $from_num, $to_num, $total);
            }
        } else {
            if (1 == $members_template->total_member_count) {
                $pag = __('Viewing 1 member', 'buddypress');
            } else {
                $pag = sprintf(_n('Viewing %1$s - %2$s of %3$s member', 'Viewing %1$s - %2$s of %3$s members', $members_template->total_member_count, 'buddypress'), $from_num, $to_num, $total);
            }
        }
        echo $pag;
    }
}
