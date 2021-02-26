<?php
/**
 * Plugin Name: Albrecht AM Challenge 
 * Version: 1.0
 * Description: A Developer Applicant Challenge
 * Author: Albrecht Villamor
 */

if ( !function_exists( 'add_action' ) ) {
	echo 'I need wordpress to run.';
	exit;
}

define('ALBRECHT_AM_CHALLENGE__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ALBRECHT_AM_CHALLENGE__PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
define('ALBRECHT_AM_CHALLENGE__VERSION', '1.0');

require_once( ALBRECHT_AM_CHALLENGE__PLUGIN_DIR . 'class.am-challenge.php' );

add_action( 'init', ['AM_Challenge', 'init']);

if(is_admin()) {
	require_once( ALBRECHT_AM_CHALLENGE__PLUGIN_DIR . 'class.am-challenge-admin.php' );
	add_action( 'init', ['AM_Challenge_Admin', 'init']);
}

if(defined('WP_CLI') && WP_CLI) {
	require_once( ALBRECHT_AM_CHALLENGE__PLUGIN_DIR . 'class.am-challenge-cli.php' );
}