<?php 
/**
 * File Name: admin_menu.php
 * Author: Layth Hussein
 * Author URI: https://laith7ussein.com
 * Description: This file is responsible for adding the admin menu.
 * Version: 0.1.0
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: proofreader
 * Domain Path: /languages
 */
!defined('ABSPATH') && exit;

function wpdocs_register_my_custom_menu_page() {
    if ( !file_exists(plugin_dir_path( __FILE__ ) . 'admin-page.php') ) return;
	
	if (!current_user_can_edit_proofreader()) return;

	add_menu_page(
		__( 'Proofreader Rules', 'proofreader' ),
		__( 'Proofreader Rules', 'proofreader' ),
		'edit_posts',
		'proofreader.php',
		function () { require_once plugin_dir_path( __FILE__ ) . 'admin-page.php'; },
		'dashicons-editor-spellcheck',
		6
	);
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

// add_menu_page( $page_title:string, $menu_title:string, $capability:string, $menu_slug:string, $callback:callable, $icon_url:string, $position:integer|float|null )