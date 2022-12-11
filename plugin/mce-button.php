<?php

/**
 * File Name: mce_button.php
 * Author: Layth Hussein
 * Author URI: https://laith7ussein.com
 * Description: This file is responsible for adding the button to the editor.
 * Version: 0.1.0
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: proofreader
 * Domain Path: /languages
 */

!defined('ABSPATH') && exit;

/**
 * Add the button to the editor.
 */
add_action('admin_head', function () {

    if (!current_user_can('edit_posts')) {
        return;
    }
    
    if ('true' == get_user_option('rich_editing')) {
        add_filter('mce_external_plugins', 'custom_tinymce_plugin');
        add_filter('mce_buttons', 'register_mce_button');
    }
    
});


add_action('admin_enqueue_scripts', function () {

    if (!current_user_can('edit_posts')) {
        return;
    }

    if ('true' == get_user_option('rich_editing')) {
        $words = json_decode(get_option('proofreader_words'), true);
        wp_enqueue_script('jquery');
        wp_localize_script('jquery', 'proofreader_words', $words);
    }
    
});


/**
 * Register the mce plugin and attach it to the js file.
 *
 * @param array $plugin_array
 * @return array
 */
function custom_tinymce_plugin($plugin_array)
{
    $plugin_array['proofreader_mce_button'] = plugins_url('assets/js/classic-editor.js?v=0.8.8', __DIR__);
    return $plugin_array;
}

/**
 * in case the editor is not loaded.
 * tihs happens when you open the editor using the tablet 
 * 
 * @since 0.2.14
 * @param array $plugin_array
 * @return array
 */
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_script('proofreader-mce-button', plugins_url('assets/js/classic-editor.js?v=0.8.12', __DIR__));
});


/**
 * Register the mce button.
 *
 * @param array $buttons
 * @return array
 */
function register_mce_button($buttons)
{
    array_push($buttons, 'proofreader_mce_button');
    return $buttons;
}
