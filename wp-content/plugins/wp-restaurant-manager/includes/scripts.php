<?php
/**
 * Scripts
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Load Admin Scripts
 *
 * Enqueues the required admin scripts.
 *
 * @since 1.0
 * @global $post
 * @param string $hook Page hook
 * @return void
 */
function wprm_load_admin_scripts( $hook ) {

	if ( ! apply_filters( 'wprm_load_admin_scripts', wprm_is_admin_page(), $hook ) ) {
		return;
	}

	global $wp_version;

	$js_dir  = WPRM_PLUGIN_URL . 'assets/js/';
	$css_dir = WPRM_PLUGIN_URL . 'assets/css/';

	// Use minified libraries if SCRIPT_DEBUG is turned off
	//$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	$suffix = '';

	// These have to be global
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_style( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'wprm-admin-scripts', $js_dir . 'admin-scripts' . $suffix . '.js', array( 'jquery' ), WPRM_VERSION, false );
	wp_enqueue_style( 'wprm-admin', $css_dir . 'wprm-admin' . $suffix . '.css', WPRM_VERSION );
	wp_enqueue_style( 'wprm-ui', $css_dir . '/jquery-ui/jquery-ui.min.css', WPRM_VERSION );
	wp_enqueue_style( 'wprm-ui-theme', $css_dir . '/jquery-ui/jquery-ui.theme.min.css', WPRM_VERSION );

	// Allows translation of js messages on backend.
	$admin_messages = array( 
		'delete_table' => __('Are you sure you want to delete this table?','wprm'),
		'delete_time' => __('Are you sure you want to delete this time?','wprm'),
		'delete_date' => __('Are you sure you want to delete this date?','wprm')
	);
    wp_localize_script( 'wprm-admin-scripts', 'wprm_admin_js_settings', $admin_messages );

}
add_action( 'admin_enqueue_scripts', 'wprm_load_admin_scripts', 100 );

/**
 * Load Custom Admin Icon
 *
 * @since 1.0.0
 * @return void
 */
function wprm_add_menu_icons_styles() { ?>
 
	<style>
	@font-face {
		font-family: 'wprestaurantmanager';
		src:url('<?php echo WPRM_PLUGIN_URL; ?>assets/fonts/wprestaurantmanager.eot?pzdk6f');
		src:url('<?php echo WPRM_PLUGIN_URL; ?>assets/fonts/wprestaurantmanager.eot?#iefixpzdk6f') format('embedded-opentype'),
			url('<?php echo WPRM_PLUGIN_URL; ?>assets/fonts/wprestaurantmanager.woff?pzdk6f') format('woff'),
			url('<?php echo WPRM_PLUGIN_URL; ?>assets/fonts/wprestaurantmanager.ttf?pzdk6f') format('truetype'),
			url('<?php echo WPRM_PLUGIN_URL; ?>assets/fonts/wprestaurantmanager.svg?pzdk6f#wprestaurantmanager') format('svg');
		font-weight: normal;
		font-style: normal;
	}
	#adminmenu #menu-posts-wprm_menu div.wp-menu-image:before, #adminmenu #menu-posts-wprm_reservations div.wp-menu-image:before {
		font-family: 'wprestaurantmanager';
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		font-size: 16px;
	  	content: '\e600';
	}
	</style>
 
	<?php

}
add_action( 'admin_head', 'wprm_add_menu_icons_styles' );

/**
 * Load Frontend Scripts and styles
 *
 * Enqueues the required frontend scripts and styles.
 *
 * @since 1.0
 * @return void
 */
function wprm_load_frontend_scripts() {

	$js_dir  = WPRM_PLUGIN_URL . 'assets/js/';
	$css_dir = WPRM_PLUGIN_URL . 'assets/css/';
	$booking_page = wprm_get_option('booking_page');

	// Use minified libraries if SCRIPT_DEBUG is turned off
	//$suffix  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	$suffix = '';

	// Register Styles and scripts
	wp_enqueue_script( 'jquery' );
	wp_register_script( 'wprm-front-scripts', apply_filters( 'wprm_front_scripts_url', $js_dir . 'wprm-front-scripts' . $suffix . '.js' ), array( 'jquery' ), WPRM_VERSION, true );
	wp_register_script( 'wprm-picker-js', $js_dir . 'picker/picker.js', array( 'jquery' ), WPRM_VERSION, true );

	wp_register_style( 'wprm-front-css', apply_filters( 'wprm_front_css_url', $css_dir . 'wprm-front-css' . $suffix . '.css' ), WPRM_VERSION );
	wp_register_style( 'wprm-picker-classic-css', apply_filters( 'wprm_picker_url', $css_dir . 'wprm-picker-classic.css' ), WPRM_VERSION );
	wp_register_style( 'wprm-picker-default-css', apply_filters( 'wprm_picker_url', $css_dir . 'wprm-picker-default.css' ), WPRM_VERSION );

	// Add booking scripts only on booking pages
	// Just to prevent waste of resources
	if(is_page( $booking_page )):
		wp_enqueue_script( 'wprm-picker-js' );

		//Determine if we have to load a translation
		if(wprm_get_option('calendar_language')):
			$the_language = wprm_get_option('calendar_language');
			wp_enqueue_script( 'wprm-picker-js-language', $js_dir . '/picker/translations/'.$the_language.'.js', array( 'jquery' ), WPRM_VERSION, true );
		endif;

	endif;

	//Add Main Script to the end
	wp_enqueue_script( 'wprm-front-scripts' );

	//Add Main Css File
	wp_enqueue_style( 'wprm-front-css' );
	if(is_page( $booking_page ) && wprm_get_option('booking_style') == 'default'):
		wp_enqueue_style( 'wprm-picker-default-css' );
	elseif(is_page( $booking_page ) && wprm_get_option('booking_style') == 'classic'):
		wp_enqueue_style( 'wprm-picker-classic-css' );
	endif;

	//Handler for picker script customization
	//through backend settings
	$wprm_frontend_js_settings = array( 
		'date_format' => wprm_get_option('date_format'),
		'time_format' => wprm_get_option('time_format'),
		'time_interval' => wprm_get_option('time_interval'),
		'opening_time' => wprm_get_option('opening_time'),
		'closing_time' => wprm_get_option('closing_time'),
		'disabled_dates' => get_option('wprm_dates_to_exclude'),
	);
    wp_localize_script( 'wprm-front-scripts', 'wprm_frontend_js_settings', $wprm_frontend_js_settings );

}
add_action( 'wp_enqueue_scripts', 'wprm_load_frontend_scripts');
