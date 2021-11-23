<?php
/*
*
*	***** BuddyPress Birthdays *****
*
*	This file initializes all BB Core components
*
*/
// If this file is called directly, abort. //
if ( ! defined( 'WPINC' ) ) {
	die;} // end if
// Define Our Constants
define( 'BB_CORE_INC', dirname( __FILE__ ) . '/assets/inc/' );
define( 'BB_CORE_IMG', plugins_url( 'assets/img/', __FILE__ ) );
define( 'BB_CORE_CSS', plugins_url( 'assets/css/', __FILE__ ) );
define( 'BB_CORE_JS', plugins_url( 'assets/js/', __FILE__ ) );
/*
*
*  Register CSS
*
*/
function bb_register_core_css() {
	wp_enqueue_style( 'bb-core', BB_CORE_CSS . 'bb-core.css', null, time(), 'all' );
};
add_action( 'wp_enqueue_scripts', 'bb_register_core_css' );
/*
*
*  Register JS/Jquery Ready
*
*/
function bb_register_core_js() {
	// Register Core Plugin JS
	wp_enqueue_script( 'bb-core', BB_CORE_JS . 'bb-core.js', 'jquery', time(), true );
};
add_action( 'wp_enqueue_scripts', 'bb_register_core_js' );
/*
*
*  Includes
*
*/
// Load the Functions
if ( file_exists( BB_CORE_INC . 'bb-core-functions.php' ) ) {
	require_once BB_CORE_INC . 'bb-core-functions.php';
}
// Load the ajax Request
if ( file_exists( BB_CORE_INC . 'bb-ajax-request.php' ) ) {
	require_once BB_CORE_INC . 'bb-ajax-request.php';
}
// Load the Shortcodes
if ( file_exists( BB_CORE_INC . 'bb-shortcodes.php' ) ) {
	require_once BB_CORE_INC . 'bb-shortcodes.php';
}

// Load the Widget File
if ( file_exists( BB_CORE_INC . 'bbirthdays-widget.php' ) ) {
	require_once BB_CORE_INC . 'bbirthdays-widget.php';
}