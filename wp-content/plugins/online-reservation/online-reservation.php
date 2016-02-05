<?php 
/**
 * Plugin Name: Online Reservation
 * Plugin URI: http://solweder.com/
 * Description: Allow you to manage and receive your business reservation online
 * Version: 1.7
 * Author: Wahsidin Tjandra
 * Author URI: http://solweder.com/about-me/
 * License:     GNU General Public License v2.0 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 3.6
 * Tested up to: 4.2
 *
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

/*
	TABLE OF CONTENTS
	========================
	1.	GLOBAL VARIABLE
	2. 	CONSTANT
	3. 	ADMIN PAGES
	4.	CONFIGURATION
	5.	PLUGIN ACTIVATION
	6.	ALL RESERVATION
	7.	RESTAURANT RESERVATION

*/
session_start();
/*==========================================
	1.	GLOBAL VARIABLE
==========================================*/
global $wpdb;
global $plugin_options;
$plugin_options = get_option('resto_all_setting');
date_default_timezone_set( $plugin_options['timezone'] );

/*==========================================
	2. 	CONSTANT
==========================================*/
if ( !defined( 'OLR_PREFIX' ) ){
	define( 'OLR_PREFIX', 'olr_' );
}
if ( !defined( 'OLR_FOLDER' ) ){
	define( 'OLR_FOLDER', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'OLR_PATH' ) ){
	define( 'OLR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'OLR_BASENAME' ) ){
	define( 'OLR_BASENAME', plugin_basename( __FILE__ ) );
}
if ( !defined( 'PLUGIN_NAME' ) ){
	define( 'PLUGIN_NAME', 'online-reservation' );
}
if ( !defined( 'FIND_TABLE_ATTEMPT' ) ){
	define( 'FIND_TABLE_ATTEMPT', $wpdb->prefix.OLR_PREFIX.'find_table_attempt' );
}
if ( !defined( 'ENQUIRY_ATTEMPT_TABLE' ) ){
	define( 'ENQUIRY_ATTEMPT_TABLE', $wpdb->prefix.OLR_PREFIX.'send_enquiry_attempt' );
}
if ( !defined( 'TEMPORARY_DATA_TABLE' ) ){
	define( 'TEMPORARY_DATA_TABLE', $wpdb->prefix.OLR_PREFIX.'temporary_reservation_data' );
}
if ( !defined( 'LOCKOUT_TABLE' ) ){
	define( 'LOCKOUT_TABLE', $wpdb->prefix.OLR_PREFIX.'lockout_reservation' );
}


/*==========================================
	3. 	ADMIN PAGES
		1.	RESTAURANT ADMIN PAGES
==========================================*/

	/*=================================
		1.	RESTAURANT ADMIN PAGES
	=================================*/
	global $olr_admin_page;
	global $all_bookings_page;
	global $post_page;
	global $post_new;
	global $resto_setting_page;
	global $plugins_page;
	global $options_page;
	global $widget_page;
	global $admin_ajax;
	require_once('check-admin-page.php');


/*==========================================
	4.	CONFIGURATION
==========================================*/
if( 	$all_bookings_page 
	||	$post_page 
	||	$post_new
	||	$resto_setting_page
	||	$plugins_page
	||	$options_page
){
	require_once('config.php');
	require_once('helper/helper_functions.php');
}




/*==========================================
	5.	PLUGIN ACTIVATION
		1.	CREATE PAGES
		2.	CREATE TABLE
==========================================*/
function olr_plugin_activation() {
		
	/*=====================================================
		1.	CREATE PAGES
			1.	RESTAURANT RESERVATION	
	=====================================================*/	
		
		/*=====================================================
			1.	RESTAURANT RESERVATION
				1.	CREATE RESERVATION AND THANK YOU PAGE
		=====================================================*/
		
			/*=====================================================
				1.	CREATE RESERVATION AND THANK YOU PAGE
			=====================================================*/
			global $olr_resto_thank_you_page;
			global $wpdb;
			
			//= CHECK IF PAGE IS EXIST 
			if( get_page_by_title('Thank You For Reservation') == '' ){
				
				$post_thank_you = array(
									'post_type'		=> 'page',
									'post_title'	=> 'Thank You For Reservation',
									'post_content'	=> '[reservation_confirmed]',
									'post_status'	=> 'publish'
									);
											
				$post_id = wp_insert_post( $post_thank_you );
				$olr_resto_thank_you_page = site_url() .'?page_id='. $post_id ;	 	
				update_option( 'resto_thank_you_url', $olr_resto_thank_you_page ); 
				update_option( 'resto_thank_you_page_id', $post_id ); 
			}
			
			//= CHECK IF PAGE IS EXIST 
			if( get_page_by_title('Restaurant Reservation') == '' ){
				
				$post_thank_you = array(
								'post_type'		=> 'page',
								'post_title'	=> 'Restaurant Reservation',
								'post_content'	=> '[online_restaurant_reservation]',
								'post_status'	=> 'publish'
								);
										
				$post_id = wp_insert_post( $post_thank_you );
				update_option( 'resto_reservation_page_id', $post_id ); 
			}
	
	/*=====================================================
		2.	CREATE TABLE
			1.	RESTAURANT RESERVATION	
	=====================================================*/	
		/*=====================================================
			1.	RESTAURANT RESERVATION	
				1.	FIND TABLE ATTEMPT ( TABLE )
				2.	SEND ENQUIRY ATTEMPT TABLE
				3.	TEMPORARY DATA TABLE
				4.	LOCKOUT TABLE
		=====================================================*/
			$all_table = array(
								"CREATE TABLE IF NOT EXISTS ".FIND_TABLE_ATTEMPT."(no int NOT NULL AUTO_INCREMENT,date DATETIME NOT NULL, 
							     ip_address CHAR( 100 ) NOT NULL, latitude CHAR( 30 ) NOT NULL, longitude CHAR( 30 ) NOT NULL, 
								 find_table_attempt CHAR( 5 ) NOT NULL,PRIMARY KEY (no))ENGINE = MYISAM;",
							 	
								"CREATE TABLE IF NOT EXISTS ".ENQUIRY_ATTEMPT_TABLE."(no int NOT NULL AUTO_INCREMENT,date DATETIME 
							     NOT NULL, today_date DATE NOT NULL, today_time TIME NOT NULL, ip_address CHAR( 100 ) NOT NULL, 
								 latitude CHAR( 30 ) NOT NULL, longitude CHAR( 30 ) NOT NULL, 
								 send_enquiry_attempt CHAR( 5 ) NOT NULL,PRIMARY KEY (no))ENGINE = MYISAM; ",
								
								"CREATE TABLE IF NOT EXISTS ".TEMPORARY_DATA_TABLE."(no int NOT NULL AUTO_INCREMENT,date DATETIME NOT NULL, session_id 
							 	 CHAR( 100 ) NOT NULL, booking_date DATETIME NOT NULL, book_date DATE NOT NULL, 
							     type_table CHAR( 30 ) NOT NULL, email CHAR( 60 ) NOT NULL,PRIMARY KEY (no))ENGINE = MYISAM;",
								
								"CREATE TABLE IF NOT EXISTS ".LOCKOUT_TABLE."(no int NOT NULL AUTO_INCREMENT,lockout_type CHAR( 50 ) NOT NULL, 
								 lockout_start DATETIME NOT NULL, ip_address CHAR( 100 ) NOT NULL, 
								 latitude CHAR( 30 ) NOT NULL, longitude CHAR( 30 ) NOT NULL, 
								 country CHAR( 30 ) NOT NULL, city CHAR( 30 ) NOT NULL, 
								 email CHAR( 60 ) NOT NULL,PRIMARY KEY (no))ENGINE = MYISAM;"
							   );
			foreach( $all_table as $table => $ql ){
				$wpdb->query($ql); 
			}
		
} // function olr_plugin_activation() {
register_activation_hook( __FILE__, 'olr_plugin_activation' );


/*========================================================
	6.	ALL RESERVATION
		1.	STYLE AND SCRIPT 
========================================================*/
	
	/*========================================================
		1.	STYLE AND SCRIPT 
	========================================================*/
	if( $olr_admin_page ){
		wp_enqueue_style(
			'all-admin-style',	// $handle (id)	
			OLR_FOLDER .'css/admin-all.css', // $sr
			false, 	// $dependencies
			false,	// $version
			false 	// in footer
		); 
	}


/*========================================================
	7.	RESTAURANT RESERVATION
		1.	ADMIN
		2.	SHORTCODE ( FRONT END )
		3.	WIDGET
========================================================*/

	/*==========================================
		1.	ADMIN
	==========================================*/
	if( $olr_admin_page ){
		require_once('restaurant reservation/ajax-request/ajax-admin.php');
		require_once('restaurant reservation/admin/admin.php');
	}
	
	/*=================================================
		2.	SHORTCODE ( FRONT END )
			1.	ADMIN AJAX
			2.	CALING SHORTCODES
	=================================================*/
	//= 1.	FRONTEND AJAX
	if( $olr_admin_page ){
		require_once('restaurant reservation/ajax-request/ajax-front-end.php');
	}
	//= 2.	CALING SHORTCODES
	function olr_plugin_shortcodes(){
		global $plugin_options;
		global $post; 
		if ( !empty($post) ){
			
			if( $plugin_options['reservation_page'] != '' ){
				$reservation_page = $plugin_options['reservation_page'];
			}else{
				$reservation_page = get_option('resto_reservation_page_id');
			}
			
			if( $plugin_options['thank_you_page'] != '' ){
				$thank_you_page = $plugin_options['thank_you_page'];
			}else{
				$thank_you_page = get_option('resto_thank_you_page_id');
			}
		
			if( $post->ID == $reservation_page ){
				require_once( OLR_PATH .'/config.php');
				require_once( OLR_PATH .'helper/helper_functions.php');
				require_once('restaurant reservation/display-shortcode.php');
			}
			if( $post->ID == $thank_you_page ){
				require_once( OLR_PATH .'/config.php');
				require_once( OLR_PATH .'helper/helper_functions.php');
				require_once('restaurant reservation/display-shortcode-reservation-confirmed.php');	
			}
		}	
	} 
	if ( !$olr_admin_page ){
		add_action( 'wp_enqueue_scripts', 'olr_plugin_shortcodes' );
	}

	
	/*==========================================
		3.	WIDGET
	==========================================*/
	$access_widget = false;
	if( 	$widget_page 
		||	$admin_ajax
	) {
		$access_widget = true;
	}
	if( !$olr_admin_page ){
		if( is_active_widget('','','olr_restaurant_widget') ){
			$access_widget = true;
		}
	}
	if( $access_widget ){
		require_once( OLR_PATH .'/config.php');
		require_once( OLR_PATH .'helper/helper_functions.php');
		require_once('restaurant reservation/display-widget.php');
	}
	

?>