<?php

/** 
 * File Name: admin-page.php
 * Description: This file is responsible for viwing the words and their replacements and adding new words.
 * Author: Layth Hussein
 * Author URI: https://laith7ussein.com
 * Version: 0.1.0
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: proofreader
 * Domain Path: /languages
 */

!defined('ABSPATH') && exit;
!current_user_can_edit_proofreader() && wp_die(__('You do not have sufficient permissions to access this page.', 'proofreader'));

$words = json_decode(get_option('proofreader_words'), true);

if (isset($_POST['word']) && isset($_POST['replacement'])) {
    $word = trim(sanitize_text_field($_POST['word']));
    $replacement = trim(sanitize_text_field($_POST['replacement']));
    if (empty($word) || empty($replacement)) {
        $error = __('Word and replacement are required.', 'proofreader');
    } else {
        $words[$word] = $replacement;
        update_option('proofreader_words', json_encode($words));
    }
}

?>
<div class="wrap">
    <?php
    $users = get_users();
    if (current_user_can('administrator')) {
    ?>
        <h1 class="wp-heading-inline">
            <?php _e('Admin Settings', 'proofreader'); ?>
        </h1>
        <div>
            <label>
                <?php _e('Select the roles that can add words', 'proofreader'); ?>
            </label>
            <select id="users">
                <?php
                foreach ($users as $user) {
                    if (in_array('administrator', $user->roles)) continue;
                    echo '<option value="' . $user->ID . '">' . $user->display_name . '</option>';
                }
                ?>
            </select>
            <a href="#" id="save-settings" class="button button-primary" style="margin-top: 10px">
                <?php _e('Save', 'proofreader'); ?>
            </a>
        </div>
        <script>
            jQuery(document).ready(function($) {
                $('#users').select2({
                    placeholder: '<?php _e('Select roles', 'proofreader'); ?>',
                    width: '100%',
                    multiple: true,
                    dir: '<?php echo is_rtl() ? 'rtl' : 'ltr' ?>',
                });
                $('#users').val(<?php echo json_encode(get_option('proofreader_access_users')); ?>).trigger('change');
                $('#save-settings').on('click', function() {
                    var users = $('#users').val();
                    $.ajax({
                        url: proofreader_ajax.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'proofreader_update_access_users',
                            nonce: proofreader_ajax.nonce,
                            users,
                        },
                        success: function(response) {
                            window.location.reload();
                        }
                    });
                });
            });
        </script>
    <?php
    }
    ?>
    <h1 class="wp-heading-inline" style="margin-top: 20px">
        <?php _e('Proofreader Rules', 'proofreader'); ?>
    </h1>

    <div style="margin-top: 15px">
        <form method="post">
            <input type="text" name="word" placeholder="<?php _e("Word", "proofreader") ?>">
            <input type="text" name="replacement" placeholder="<?php _e("Replacement", "proofreader") ?>">
            <input class="page-title-action" style="transform: translateY(3px)" type="submit" value="<?php _e("Add", "proofreader") ?>">
        </form>
    </div>

    <table class="wp-list-table widefat fixed striped table-view-list posts" style="margin-top: 20px">
        <thead>
            <tr>
                <th scope="col" id="word" class="manage-column column-word"><?php _e("Word", "proofreader") ?></th>
                <th scope="col" id="replacement" class="manage-column column-replacement"><?php _e("Replacement", "proofreader") ?></th>
                <th scope="col" id="actions" class="manage-column column-actions"></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach (array_reverse($words) as $word => $replacement) : ?>
                <tr>
                    <td scope="col" class="word column-word"><?php echo $word; ?></td>
                    <td scope="col" class="replacement column-replacement"><?php echo $replacement; ?></td>
                    <td scope="col" class="replacement column-actions">
                        <!-- <a href="#" class="edit-word"><?php _e("Edit", "proofreader") ?></a> -->
                        <span class="trash">
                            <a href="#" style="color: #b32d2e;" class="delete-word submitdelete" data-word="<?php echo $word ?>"><?php _e("Delete", "proofreader") ?></a>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot>
            <tr>
                <th scope="col" id="word" class="manage-column column-word"><?php _e("Word", "proofreader") ?></th>
                <th scope="col" id="replacement" class="manage-column column-replacement"><?php _e("Replacement", "proofreader") ?></th>
                <th scope="col" id="actions" class="manage-column column-actions"></th>
            </tr>
        </tfoot>

    </table>
</div>