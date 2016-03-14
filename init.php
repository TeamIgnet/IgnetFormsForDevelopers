<?php

/*
Plugin Name: Ignet Forms For Developers
Plugin URI: http://ignet.org/iffd
Description: Quickly and simply are making input fields!
Author: Ignet
Author URI: http://ignet.org
Version: 1.0
*/

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
	die( 'You are not allowed to call this page directly.' );
}

if ( ! defined( 'IFFD_DIR' ) ) {
	define( 'IFFD_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined('IFFD_URL' ) ) {
	define( 'IFFD_URL', plugin_dir_url( __FILE__ ) );
}

if ( file_exists( IFFD_DIR . 'functions.php' ) ) {
	require_once( IFFD_DIR . 'functions.php' );
}

// Load i18n domain
function load_iffd_textdomain() {
	 load_plugin_textdomain( 'IFFD-Textdomain', false, IFFD_DIR . '/languages/' );
}
add_action( 'plugins_loaded', 'load_iffd_textdomain' );


/**
 * Enqueue scripts and styles
 */
function iffd_enqueue_scripts() {

	wp_enqueue_script( 'iffd-script', IFFD_URL . 'iffd-script.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_style( 'iffd-style', IFFD_URL . 'iffd-style.css' );
	
	// Register MediaUploader
	wp_register_script( 'iffd-mediauploader-script', IFFD_URL . 'plugins/mediauploader/mediauploader-script.js', array( 'jquery' ), '1.0', true );
	wp_register_style( 'iffd-mediauploader-style', IFFD_URL . 'plugins/mediauploader/mediauploader-style.css' );
}
add_action( 'admin_enqueue_scripts', 'iffd_enqueue_scripts' );
add_action( 'wp_enqueue_scripts', 'iffd_enqueue_scripts' );

/**
 * Starts work plugin
 *
 * Run the execution of function, respectively required parameter $args['attr']['type']
 * which define desired type of HTML input elements, like text, password, select
 *
 * @param string $args
 * @return bool
 */
function iffd_get_field( $args ) {

	if ( empty( $args['attr']['type'] ) ) {
		return false;
	}

	// Define the list of field types in categories of the getting data
	$elements_list = array(
		'control_element' => array(
			'reset',
			'submit',
		),
		'data_field' => array(
			'text',
			'password',
			'checkbox',
			'hidden',

			'textarea',
			'select',
			'radio',

			'number',
			'url',
			'search',
			'tel',
			'email',
			'range',

			'color',
			'date',
			'datetime',
			'datetime-local',
			'time',
			'month',
			'week',
		),
		'media_field' => array(
			'media',
		),
	);

	// Call field function by category
	foreach ( $elements_list as $element_category => $elements_types ) {
		if ( in_array( $args['attr']['type'], $elements_types ) ) {

			$usage_function = 'use_' . $element_category;
			$usage_function( $args ); 

			return true;
		}
	}
}