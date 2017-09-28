<?php
/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */
/**
 * Fires before the display of the members loop.
 *
 * @since 1.2.0
 */
do_action('bp_before_members_loop');
?>

<?php if (bp_get_current_member_type()) : ?>
    <p class="current-member-type"><?php bp_current_member_type_message() ?></p>
<?php endif; ?>

<?php
if (bp_has_members(bp_ajax_querystring('members'))) {
    global $members_template;
    
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
    $factive = isset($members_template->members[0]->friendship_type) ? $members_template->members[0]->friendship_type : '';

    $uid = array();
    foreach ($factive as $key => $value) {
        $temp = explode('@', $value);
        if ($temp[0] === $current_screen) {
            $uid[] = $temp[1];
        }
    }
    $members_template->total_member_count = count($uid);
    if($members_template->total_member_count > 0){
    ?>

    <div id="pag-top" class="pagination">

        <div class="pag-count" id="member-dir-count-top">
            <?php
            do_action('blm_get_members_pagination_count', count($uid));
            ?>
        </div>

        <div class="pagination-links" id="member-dir-pag-top">
            <?php bp_members_pagination_links(); ?>
        </div>

    </div>

    <?php
    /**
     * Fires before the display of the members list.
     *
     * @since 1.1.0
     */
    do_action('bp_before_directory_members_list');
    ?>

    <ul id="members-list" class="item-list" aria-live="assertive" aria-relevant="all">

        <?php
        while (bp_members()) : bp_the_member();
            if (!empty($factive)):
                $id = bp_get_member_user_id();
                if (in_array($id, $uid)):
                    ?>
                    <li <?php bp_member_class(); ?>>
                        <div class="item-avatar">
                            <a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
                        </div>

                        <div class="item">
                            <div class="item-title">
                                <a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>

                                <?php if (bp_get_member_latest_update()) : ?>

                                    <span class="update"> <?php bp_member_latest_update(); ?></span>

                                <?php endif; ?>

                            </div>

                            <div class="item-meta"><span class="activity" data-livestamp="<?php bp_core_iso8601_date(bp_get_member_last_active(array('relative' => false))); ?>"><?php bp_member_last_active(); ?></span></div>

                            <?php
                            /**
                             * Fires inside the display of a directory member item.
                             *
                             * @since 1.1.0
                             */
                            do_action('bp_directory_members_item');
                            ?>

                            <?php
                            /*                             * *
                             * If you want to show specific profile fields here you can,
                             * but it'll add an extra query for each member in the loop
                             * (only one regardless of the number of fields you show):
                             *
                             * bp_member_profile_data( 'field=the field name' );
                             */
                            ?>
                        </div>

                        <div class="action">

                            <?php
                            /**
                             * Fires inside the members action HTML markup to display actions.
                             *
                             * @since 1.1.0
                             */
                            do_action('bp_directory_members_actions');
                            ?>

                        </div>

                        <div class="clear"></div>
                    </li>
                    <?php
                endif;
            endif;
            ?>
        <?php endwhile; ?>

    </ul>

    <?php
    /**
     * Fires after the display of the members list.
     *
     * @since 1.1.0
     */
    do_action('bp_after_directory_members_list');
    ?>

    <?php bp_member_hidden_fields(); ?>

    <div id="pag-bottom" class="pagination">
        <div class="pag-count" id="member-dir-count-bottom">
            <?php do_action('blm_get_members_pagination_count', count($uid)); ?>
        </div>
        <div class="pagination-links" id="member-dir-pag-bottom">

            <?php bp_members_pagination_links(); ?>

        </div>

    </div>
    <?php }else{ ?>
            <div id="pag-top" style="padding-bottom:10px;"></div>
            <div id="message" class="info">
                <p><?php _e("Sorry, no friends inside the list were found.", 'buddypress'); ?></p>
            </div>
    <?php } ?>

<?php }else{ ?>
    
    <div id="message" class="info">
        <p><?php _e("Sorry, no members were found.daw", 'buddypress'); ?></p>
    </div>

<?php } ?>

<?php
/**
 * Fires after the display of the members loop.
 *
 * @since 1.2.0
 */
do_action('bp_after_members_loop');
?>