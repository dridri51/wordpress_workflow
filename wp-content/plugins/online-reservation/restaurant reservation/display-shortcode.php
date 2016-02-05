<?php
/*
	TABLE OF CONTENTS ( DISPLAY	ON FRONT END )
	==========================================
	1.	CEATING SHORTCODE
*/
global $lockout_reservation_length;

/*==============================================
	1.	CEATING SHORTCODE
		1.	GENERAL VARIABLE
		2. 	RETRIEVE OPTION DATA
		3.	ENQUEQE SCRIPT AND STYLE
==============================================*/

add_shortcode('online_restaurant_reservation','olr_shortcode_display');

function olr_shortcode_display($args){
		
		/*==============================================
			1.	GENERAL VARIABLE
		==============================================*/
		global $lockout_reservation_length;
		global $message_array;
		global $geolocation_api;
		
		/*==============================================
			2. 	RETRIEVE OPTION DATA
		==============================================*/
		$options = get_option( 'resto_all_setting' );
		
		/*==============================================
			3.	ENQUEQE SCRIPT AND STYLE
		==============================================*/
		if( !is_admin() ) {
			
			if( !wp_script_is('jquery-ui-datepicker') ){
				wp_enqueue_script( 'jquery-ui-datepicker' );
			}
			wp_enqueue_style(
				'olr-date-picker-style',	// $handle (id)	
				OLR_FOLDER .'css/jquery.ui.datepicker.css', // $sr
				false, 	// $dependencies
				false,	// $version
				false 	// in footer
			); 
			wp_enqueue_style(
				'olr-front-style',	// $handle (id)	
				OLR_FOLDER .'css/restaurant-front-style.css', // $sr
				false, 	// $dependencies
				false,	// $version
				false 	// in footer
			);  
			wp_enqueue_script( 
				'olr-script',
				OLR_FOLDER . 'js/restaurant-script.js', // $src 
				array(), 
				false, 
				true 
			);
			
			
			$any_data_array 	= array( 
										'plugin_folder' 	=> OLR_FOLDER,
										'plugin_path' 		=> OLR_PATH,
										'early_bookings' 	=> $options['early_bookings'],
										'late_bookings' 	=> $options['late_bookings'],
										'plugin_options' 	=> get_option('resto_all_setting'),
										'ajaxurl'         	=> admin_url( 'admin-ajax.php' ),
										'ip_address'   		=> $_SERVER['REMOTE_ADDR'],
										'fake_actions_title'   	=> $message_array['TOO MANY FAKE ACTIONS']['title'],
										'fake_actions_message' 	=> $message_array['TOO MANY FAKE ACTIONS']['message'],
										'geolocation_api' 	=> $geolocation_api
										);
			wp_localize_script( 'olr-script', 'data', $any_data_array );
			
		} 

		
		//======== NOT USED , BECAUSE THE SHORTCODE IS FILLED WITH ALL ATTRIBUTE ================
		extract(shortcode_atts(array(
			/*'img'		=> 'small',
			'des'		=> 'blue',*/
		), $atts));
		
		$out = '';
		$out = restaurant_reservation_content($options);
		return $out;
	
} // olr_shortcode_display
?>