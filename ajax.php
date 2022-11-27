<?php
/**
 * File Name: ajax.php
 * Author: Layth Hussein
 * Author URI: https://laith7ussein.com
 * Description: all ajax endpoints for the plugin.
 * Version: 0.1.0
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: proofreader
 * Domain Path: /languages
 */
!defined('ABSPATH') && exit;

/**
 * Delete a word from proofreader dictionary.
 * @since 0.2.0
 * @package proofreader
 * @version 0.1.0
 */
add_action('wp_ajax_proofreader_delete_word', function () {
    
    // check nonce and permissions first
    !wp_verify_nonce($_POST['nonce'], 'proofreader-nonce') && wp_send_json_error(__($_POST['nonce'], 'proofreader'));
    !current_user_can_edit_proofreader() && wp_send_json_error(__('You don\'t have the right permissions to do this.', 'proofreader'));
    !isset($_POST['word']) && wp_send_json_error(__('Word is required.', 'proofreader'));
    
    $word_to_delete = sanitize_text_field($_POST['word']);
    empty($word_to_delete) && wp_send_json_error(__('Word and replacement are required.', 'proofreader'));

    $words = json_decode(get_option('proofreader_words'), true);    
    $words_filtered = array();

    foreach ($words as $word => $replacement) {
        if ( $word != $word_to_delete ) {
            $words_filtered[$word] = $replacement;
        }
    }
    
    update_option('proofreader_words', json_encode($words_filtered));
    wp_send_json_success(__('Word deleted succefully.', 'proofreader'));
    
});

/**
 * Add a new word to proofreader dictionary.
 * @since 0.2.0
 * @package proofreader
 * @version 0.1.0
 */
add_action('wp_ajax_proofreader_update_access_users', function () {

    !wp_verify_nonce($_POST['nonce'], 'proofreader-nonce') && wp_send_json_error(__($_POST['nonce'], 'proofreader'));

    if ( current_user_can('administrator') ) {
        $users = $_POST['users'];
        update_option('proofreader_access_users', $users);
        wp_send_json_success(__('Users updated successfully.', 'proofreader'));
    } else {
        wp_send_json_error(__('You don\'t have the right permissions to do this.', 'proofreader'));
    }

});