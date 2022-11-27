<?php
/**
 * File Name: admin_menu.php
 * Author: Layth Hussein
 * Author URI: https://laith7ussein.com
 * Description: plugin function.
 * Version: 0.1.0
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: proofreader
 * Domain Path: /languages
 */

/**
 * Check if the current user can edit proofreader.
 * @since 0.2.0
 * @package proofreader
 * @version 0.1.0
 */
function current_user_can_edit_proofreader () {
    $users_can_view = get_option('proofreader_access_users');
	if ( !current_user_can('administrator') && !empty($users_can_view) && !in_array(get_current_user_id(), $users_can_view) ) {
        return false;
    }
    return true;
}

/**
 * Load plugin scripts and styles.
 * @since 0.1.0
 * @package proofreader
 * @version 0.1.0
 * @license GPL-2.0+
 * @license URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
function proofreader_admin_enqueue_scripts () {
    
    /**
     * Enqueue table scripts.
     */
    if ( isset($_GET['page']) && $_GET['page'] === 'proofreader.php' ) {
        wp_enqueue_script('select2', plugins_url('assets/js/select2.min.js', __FILE__), array('jquery'), '4.1.0', true);
        wp_enqueue_style('select2', plugins_url('assets/css/select2.min.css', __FILE__), array(), '4.1.0', 'all');
        wp_enqueue_script('proofreader-ajax', plugins_url('assets/js/ajax-handler.js', __FILE__), array('jquery', 'wp-i18n'), '0.2.0', true);
        wp_localize_script('proofreader-ajax', 'proofreader_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('proofreader-nonce'),
            'i18n' => array(
                'confirm' => __('Are you sure you want to delete this rule?', 'proofreader'),
                'success' => __('Rule deleted successfully.', 'proofreader'),
                'error' => __('An error occured while deleting the rule.', 'proofreader'),
            ),
        ));
    };
    
}

/**
 * Load plugin locales
 * @since 0.1.0
 * @package proofreader
 * @version 0.1.0
 * @license GPL-2.0+
 * @license URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
function proofreader_load_locales () {
    load_plugin_textdomain('proofreader', false, basename(__DIR__) . '/languages');
}