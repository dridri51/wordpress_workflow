<?php 
/*
	TABLE OF CONTENTS
	========================
	1. 	GLOBAL VARIABLE
	2.	LOCALIZATION ( TRANSLATION )
*/

/*==================================================
	1. 	GLOBAL VARIABLES
		1.	PLUGIN OPTIONS
		2.	RESERVATION TIME NEEDED 
		3.	LOCKOUT RESERVATION LENGTH
		4.	MAXIMUM FIND TABLE ATTEMPT
		5.	MAXIMUM SEND ENQUIRY ATTEMPT
		6.	EMAIL CONFIRMATION LENGTH
		7.	MESSAGE
		8.	GET LATITUDE AND LONGITUDE
==================================================*/
//= 1.	PLUGIN OPTIONS
global $plugin_options;

//= 2.	RESERVATION TIME NEEDED 
global $temporary_data_length;
$temporary_data_length 	= (str_replace('_min','',$plugin_options['reservation_time_needed']) - 0) * 60; // seconds

//= 3.	LOCKOUT RESERVATION LENGTH
global $lockout_reservation_length;
$lockout_reservation_length = (str_replace('_hour','',$plugin_options['lock_our_reservation']) - 0) * 3600; // seconds


//= 4.	MAXIMUM FIND TABLE ATTEMPT
global $max_find_table_attempt;
$max_find_table_attempt = $plugin_options['max_find_table'] - 0;

//= 5.	MAXIMUM SEND ENQUIRY ATTEMPT
global $max_send_enquiry_attempt;
$max_send_enquiry_attempt = $plugin_options['max_send_enquiry'] - 0;

//= 6.	EMAIL CONFIRMATION LENGTH
global $email_confirmation_length;
$email_confirmation_length = (str_replace('_min','',$plugin_options['email_confirmation_length']) - 0) * 60; // seconds


/*==========================================
	7.	MESSAGE
		1.	TABLE FOUND
		2.	TABLE NOT FOUND
		3.	FULLY BOOKED
		4.	TOO MANY FAKE ACTIONS
		5.	ENQUIRY
==========================================*/
global $message_array;
	$message_array = array(
						   	'Found'	=>	array(
											'title' 	=>	'Table Found',
											'message' 	=>	'Yes, Your table is found'
											),
							'Not Found'	=>	array(
											'title' 	=>	'Table is not Found',
											'message' 	=>	'Try, to find other time'
											),
			'Not Found With Other Time'	=>	array(
											'title' 	=>	'Table is not Found',
											'message' 	=>	'But we recommed you several time where table is ready for booking , '
											),
						'Fully Booked'	=>	array(
											'title' 	=>	'Fully Booked',
											'message' 	=>	'We are sorry , Today is fully booked. Try make a reservation for tomorrow / Send Us an Enquiry and We will manually find for any table that match your request and inform you as soon as possible'
											),
				'TOO MANY FAKE ACTIONS'	=>	array(
											'title' 	=>	'We are swamped',
											'message' 	=>	'Come again after ' . $lockout_reservation_length / 60 .' minutes'
											),
							'Enquiry'	=>	array(
											'title' 	=>	'Table is not Found',
											'message' 	=>	'Send Us an Enquiry and We will manually find for any table that match your request an inform you as soon as possible'
											)
						);

/*==========================================
	8.	CONFIRMATION EMAIL MESSAGE
		1.	TABLE FOUND
		2.	TABLE NOT FOUND
		3.	FULLY BOOKED
		4.	TOO MANY FAKE ACTIONS
		5.	ENQUIRY
==========================================*/
global $email_message_array;
	$email_message_array = array(
						   	'customer'	=>	array(
											'message' 	=>	'A Confirmation Email has Been Sent to Your Email, 
															 Please Click on the Confirmation Link to complete your reservation process 
															 or it will expired after '. $email_confirmation_length / 60 .' minutes'
											)
						);

/*==========================================
	8.	GET LATITUDE AND LONGITUDE
		1.	FIRST METHOD ( hackertarget )
		2.	SECOND METHOD ( telize ) , on restaurant-script.js
		3.	THIRD METHOD ( geobytes ) 
==========================================*/
global $geolocation_api;
global $geo_country;
global $geo_city;
global $latitude;
global $longitude;
$geolocation_api = 'hackertarget';

if( 	$_SERVER['HTTP_HOST'] != 'localhost'
   	&&	$geolocation_api == 'hackertarget'
){

	$URL = "http://api.hackertarget.com/geoip/?q=" . $_SERVER['REMOTE_ADDR'];
	$geo_array = file($URL);
		
	if( count($geo_array) > 0 ){
		$geo_country 	= str_replace('Country: ','', wp_strip_all_tags($geo_array['1']) );
		$geo_city 		= str_replace('City: ','', wp_strip_all_tags($geo_array['3']) );
		$latitude 		= str_replace('Latitude: ','', wp_strip_all_tags($geo_array['4']) );
		$longitude 		= str_replace('Longitude: ','', wp_strip_all_tags($geo_array['5']) );
	}else{
		$geo_country 	= '';
		$geo_city 		= '';
		$latitude 		= '';
		$longitude 		= '';	
	}
		
	$geo_country 	= str_replace(' ','', $geo_country );
	$geo_city 		= str_replace(' ','', $geo_city );
	$latitude 		= str_replace(' ','', $latitude );
	$longitude 		= str_replace(' ','', $longitude );
	
}


/*==========================================
	2.	LOCALIZATION ( TRANSLATION )
==========================================*/
load_plugin_textdomain( PLUGIN_NAME, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

?>