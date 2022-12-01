<?php

/**
 * Plugin Name:       Proofreader
 * Description:       Proofread your content with a single click.
 * Version:           0.2.4
 * Author:            Layth Hussein
 * Author URI:        https://laith7ussein.com
 * Text Domain:       proofreader
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */


foreach (array(
    '/functions.php',
    '/plugin/mce-button.php',
    '/plugin/admin-menu.php',
    '/ajax.php',
) as $path) {
    $full_path = plugin_dir_path(__FILE__) . $path;
    file_exists($full_path) || wp_die("File not found: $full_path");
    require_once $full_path;
}

add_action('plugins_loaded', function () {
    $words = get_option('proofreader_words');
    if ( !$words ) {
        update_option('proofreader_words', json_encode(array()));
    }
});

add_action('init', 'proofreader_load_locales');
add_action('admin_enqueue_scripts', 'proofreader_admin_enqueue_scripts');
