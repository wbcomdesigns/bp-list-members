(function ($) {
    'use strict';
    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    $(function () {
        $('.dropdown-content .bp-list-members-btn').remove();
        $("#buddypress ul.item-list li div.is_friend.generic-button").hover(function () {
            var single_str = $(this).attr('id');
            var single_id = single_str.split("-");
            $('#buddypress ul.item-list li div.is_friend div#dropdown-content-friend-' + single_id[2]).show();
            $('#buddypress ul.item-list li div.is_friend div.dropdown-content .add-new-friend').show();
            $('#buddypress ul.item-list li div.is_friend div.dropdown-content .blm_add_group').hide();
        }, function () {
            $('#buddypress ul.item-list li div.is_friend div.dropdown-content').hide();
        });
        $(document).on('click', "#buddypress ul.item-list li div.is_friend div.dropdown-content .add-new-friend", function () {
            $('#buddypress ul.item-list li div.is_friend div.dropdown-content .add-new-friend').hide();
            $('#buddypress ul.item-list li div.is_friend div.dropdown-content .blm_add_group').show();
            $('.blm_group_name').css('width', '100%').focus();
        });
        $(document).on("keydown", '.blm_group_name', function (e) {

            if (e.keyCode == 13) {
                var current_mid = $(this).data().mmid;
                var data = {
                    'action': 'bp_list_members_dynamic_firends_group_papulate_ajax',
                    'group_name': $(this).val(),
                    'current_mid': current_mid
                };

                $('#buddypress ul.item-list li div.action div.dropdown-content div.blm_create_list').hide();
                $('#buddypress ul.item-list li div.action div.dropdown-content div.blm_list_msg').show();

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                $.post(blm_ajax.ajax_url, data, function (response) {
                    $('#buddypress ul.item-list li div.action div.dropdown-content div.blm_list_msg').hide();
                    $('#buddypress ul.item-list li div.action div.dropdown-content div.blm_create_list').show();
                    window.location.reload();
                });
                $(this).val('');

            }
        });
        $(document).on("click", '#blm_group_delete #delete_group', function (e) {
            var group_did = $(this).data().id;
            var data = {
                'action': 'blm_firends_group_delete_ajax',
                'group_slug': group_did
            };
            if (confirm('Are you sure to remove this List!')) {
                $.post(blm_ajax.ajax_url, data, function (response) {
                    var response = $.parseJSON(response);
                    if (response.status === 'success') {
                        window.location.href = response.furl + 'friends';
                    }
                });
            }
        });
        $(document).on('click', '.blm_friends', function () {
            var current_id = $(this).data().id;
            var current_slug = $(this).data().slug;
            var current_fid = $(this).data().fid;
            $('#' + current_id + ' > span > i.blm_tick').toggleClass('fa fa-check').promise().done(function () {
                if ($(this).hasClass('fa fa-check')) {
                    var data = {
                        'action': 'blm_firends_list_switch_ajax',
                        'check': 'checked',
                        'group_slug': current_slug,
                        'member_id': current_fid
                    };
                    $.post(blm_ajax.ajax_url, data, function (response) {
                        console.log(response);
                    });
                } else {
                    var data = {
                        'action': 'blm_firends_list_switch_ajax',
                        'check': 'unchecked',
                        'group_slug': current_slug,
                        'member_id': current_fid
                    };
                    $.post(blm_ajax.ajax_url, data, function (response) {
                        console.log(response);
                    });
                }
            });
        });
        $(document).on('click', '.blm_remove_friend', function () {
            if (confirm('Are you sure to remove this friend!')) {
                return true;
            } else {
                return false;
            }
        });
    });
})(jQuery);