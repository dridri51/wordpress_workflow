<?php
	/*
		TABLE OF CONTENTS
		========================
		1.	GLOBAL VARIABLE
		2.	RESTO BOOKING AJAX SUBMIT
		3.	RESTO FIND TABLE AJAX SUBMIT	
		4.	RESTO CHECK TABLE LOCKOUT
	*/
	
	/*#################################################################
		1.	GLOBAL VARIABLE
			1.	PLUGIN OPTIONS 
			2.	TODAY DATE
			3.	IP ADDRESS AND SESSION ID
	#################################################################*/
	//= 1.	PLUGIN OPTIONS
	global $options;
	global $plugin_options;
	$options = $plugin_options; 
	
	//= 2.	TODAY DATE
	global $today_date_time;
	global $today_date;
	global $today_time;
	$today 		= getdate();
	$t_year 	= $today['year'];
	$t_month	= strlen($today['mon']) == 1 ? '0'. $today['mon'] : $today['mon'];
	$t_day		= strlen($today['mday']) == 1 ? '0'. $today['mday'] : $today['mday'];
	$t_hour		= strlen($today['hours']) == 1 ? '0'. $today['hours'] : $today['hours'];
	$t_min		= strlen($today['minutes']) == 1 ? '0'. $today['minutes'] : $today['minutes'];
	$t_sec		= strlen($today['seconds']) == 1 ? '0'. $today['seconds'] : $today['seconds'];
	$today_date_time 	= $t_year.'-'.$t_month.'-'.$t_day.' '.$t_hour.':'.$t_min.':'.$t_sec;
	$today_date 		= $t_year.'-'.$t_month.'-'.$t_day;
	$today_time 		= $t_hour.':'.$t_min.':'.$t_sec;
	
	//=  3.	IP ADDRESS AND SESSION ID
	global $ip_address;
	global $session_id;
	$ip_address = $_SERVER['REMOTE_ADDR'];
	$session_id	= session_id();	
	
	
	/*#################################################################
		2.	RESTO BOOKING AJAX SUBMIT
			IS TRIGGERED ON , restaurant-script.js
			postData['action'] 		= 'resto-booking-ajax-submit';
			
			CONTENT
			=================
			1.	GLOBAL VARIABLE
			2.	POST DATA	
			3. 	GENERAL VARIABLE
			4.	FORM VERIFICATION
			5.	GOOGLE RECAPTCHA VERIFICATION
			6.	CHECK IS FORM EXPIRED
			7.	CHECK ENQUIRY ACTION
			8.	PREVENT CODE INJECTION
			9.	SAVING PROCESS
	#################################################################*/	
	add_action( 'wp_ajax_nopriv_resto-booking-ajax-submit', 'olr_resto_booking_ajax' );
	add_action( 'wp_ajax_resto-booking-ajax-submit', 'olr_resto_booking_ajax' );
		 
	function olr_resto_booking_ajax() {
		
		/*=================================================
			1.	GLOBAL VARIABLE	
		=================================================*/
		global	$name;
		global	$email; 			
		global	$phone; 			
		global	$message; 		
		global	$table;	
		global 	$type_of_table;
		global	$persons;					
		global	$date;			
		global 	$time;		
		global 	$recipient;
		global 	$from;
		global  $subject;
		global  $messages;
		global  $restaurant_name;
		global  $restaurant_address;
		global  $restaurant_city;
		global  $restaurant_state;
		global  $restaurant_zipcode;
		global  $restaurant_address;
		global  $restaurant_phone;
		global  $restaurant_fax;
		global  $restaurant_email;
		global  $restaurant_website;
		global  $restaurant_logo;
		global  $restaurant_image;
		global  $restaurant_offer_link;
		global  $restaurant_reservation_link;
		global  $restaurant_message;
		global  $restaurant_information;
		global  $restaurant_policies;
		global  $restaurant_facebook;
		global  $restaurant_twitter;
		global  $restaurant_google;
		global  $restaurant_linkedin;
		global  $restaurant_pinterest;
		global  $restaurant_youtube;
		global  $restaurant_footer;
		global  $email_template;
		global 	$text_email;
		global 	$confirmation_link;
		global 	$email_template;
		global 	$text_email;
		global 	$wpdb;
		global 	$temporary_data_length;
		global 	$lockout_reservation_length;
		global 	$message_array;
		global 	$email_message_array;
		global	$options; // PLUGIN OPTIONS
		global 	$today_date_time;
		global 	$today_date;
		global 	$today_time;
		global 	$ip_address;
		global 	$session_id;
		
		
		/*=================================================
			2.	POST DATA	
				1.	GEOLOCATION INFORMATION
				2.	PLUGIN URL
				3.	PERSONAL INFORMATION
				4.	BOOKING TABLE INFORMATION
		=================================================*/
		
			/*========================================
				1.	GEOLOCATION INFORMATION
			========================================*/
			$country 		= $_POST['country'];	
			$city 			= $_POST['city'];	
			$latitude		= $_POST["latitude"];
			$longitude		= $_POST["longitude"];
		
			/*========================================
				2.	PLUGIN URL
			========================================*/
			$plugin_url 	= $_POST['plugin_url'];
			$plugin_path 	= $_POST['plugin_path'];
			
			/*=====================================
				 3.	PERSONAL INFORMATION
			=====================================*/
			$name 			= $_POST['olr_name'];	
			$email 			= $_POST['olr_email'];	
			$phone			= $_POST["olr_phone"];
			$message		= $_POST["olr_message"];
			
			/*=====================================
				 4.	BOOKING TABLE INFORMATION
			=====================================*/
			$type_of_table = '';
			if( 	isset($_POST["olr_type_table"]) 
				||	$_POST["olr_type_table"] != ''){
				$type_of_table	= $_POST["olr_type_table"];
			}
			//$table			= $_POST["olr_table"];
			$table			= '1';
			$persons 		= $_POST["olr_persons"];
			$date			= $_POST["olr_date"];
			$time 			= $_POST["olr_time"];
			$booking_status = 'pending';
			if( isset($_POST["olr_booking_status"]) ){
				$booking_status = $_POST["olr_booking_status"];
			}
			
		
		/*=================================================
			3. 	GENERAL VARIABLE
		=================================================*/
		$olr['trueImg'] 	= $plugin_url . 'image/true.gif';
		$olr['post_type'] 	= 'olr_restaurant';
		$olr['meta_key'] 	= 'olr_custom_column';
		
		
		/*=================================================
			4.	FORM VERIFICATION
				1.	NONCE ( FORM KEY ) , PREVENT XSS
		=================================================*/
		$nonce 	= $_POST['restaurant_nonce'];	
		if ( 	empty($_POST) 
			|| 	!wp_verify_nonce($nonce,'restaurant_form_verify') 
		){
			echo 'Sorry, your form is not valid.';
			exit;		
		}
		
		/*=================================================
			5.	GOOGLE RECAPTCHA VERIFICATION
		=================================================*/
		if ( 	isset( $options['enable_captcha'] ) 
			&&	$options['enable_captcha']
		){
			if( $_SERVER['HTTP_HOST'] == 'localhost' ){
				require_once( $plugin_path . 'helper\recaptchalib.php');
			}else{
				require_once( $plugin_path . 'helper/recaptchalib.php');
			}
			
			$resp = recaptcha_check_answer(
				$options['private_key'],
				$_SERVER["REMOTE_ADDR"],
				$_POST["recaptcha_challenge_field"],
				$_POST["recaptcha_response_field"]
			);
			
			
			if (!$resp->is_valid) {
				// What happens when the CAPTCHA was entered incorrectly
				$captcha_error_message = "The reCAPTCHA wasn't entered correctly. try it again.";
			
				if( $options['captcha_error_message'] != '' ){
					$captcha_error_message = $options['captcha_error_message'];
				}
				$captcha_response = $captcha_error_message .
									"<br/>(reCAPTCHA said: " . $resp->error . ")"; // ONLY FOR DEVELOPMENT 
				echo $captcha_response;
				exit;												
			}
		} 
		
		
		/*=================================================
			6.	CHECK IS FORM EXPIRED
		=================================================*/
		$sql = 	"SELECT date FROM ".TEMPORARY_DATA_TABLE.
				" WHERE session_id ='".session_id()."'";
		$find_table_date = $wpdb->get_results($sql,ARRAY_A);
		
		if( count($find_table_date) > 0 ){
			if( strtotime($find_table_date[0]['date']) < strtotime($today_date) - $temporary_data_length ){
				echo 'Apologize Session Expired, ensure that your booking process do not more than '.$temporary_data_length.' seconds , 
						So Please repeat your reservation process';
				exit();
			}
		}
		
		/*=================================================
			7.	CHECK ENQUIRY ACTION
				1.	SAVE DATA INTO LOCKOUT TABLE
		=================================================*/
		if( $booking_status == 'enquiry' ){
			$sql = 	"SELECT send_enquiry_attempt FROM ".ENQUIRY_ATTEMPT_TABLE.
					" WHERE latitude='".$latitude."' AND longitude='".$longitude."'";
			$enguiry_data = $wpdb->get_results($sql,ARRAY_A);
			
			if( $enguiry_data ){
				
				$send_enquiry_attempt = ($enguiry_data[0]['send_enquiry_attempt'] - 0);
				
				if( $send_enquiry_attempt > $max_send_enquiry_attempt ){
					
					//= 1.	SAVE DATA INTO LOCKOUT TABLE
					$lockout_type = 'TOO MANY ENQUIRY ACTION';
					lockout_table($today_date_time,$lockout_type,'save data');
					
					echo $message_array['TOO MANY FAKE ACTIONS']['title'];
					echo "<br />";
					echo $message_array['TOO MANY FAKE ACTIONS']['message'];
					exit();
				}else{
					$sql = 	"UPDATE ".ENQUIRY_ATTEMPT_TABLE." SET today_time='".$today_time."' , send_enquiry_attempt='".($send_enquiry_attempt + 1)."'".
							" WHERE latitude='".$latitude."' AND longitude='".$longitude."'";
					$wpdb->query($sql);
				}
				
			}else{
				$sql = 	"INSERT INTO ".ENQUIRY_ATTEMPT_TABLE." ( date, today_date, today_time, ip_address, latitude, longitude, send_enquiry_attempt) ".
						"VALUES ( %s, %s, %s, %s, %s, %s, %s )";
				
				$today_date_time 	= $today_date_time;
				$today_date 		= $today_date;
				$today_time 		= $today_time;
				$ip_address 		= $ip_address;	
				$send_enquiry_attempt = '1';
				
				$action = $wpdb->query( $wpdb->prepare( 
													$sql,
													$today_date_time,
													$today_date,
													$today_time,
													$ip_address, 
													$latitude,
													$longitude,
													$send_enquiry_attempt
												));			
						
			}
		} // if( $booking_status == 'enquiry' ){
			
		/*=================================================
			8.	PREVENT CODE INJECTION
		=================================================*/
		$name 			= wp_strip_all_tags($name);
		$email 			= wp_strip_all_tags($email);
		$phone 			= wp_strip_all_tags($phone);
		$message 		= wp_strip_all_tags($message);
		$table			= wp_strip_all_tags($table);
		$persons		= wp_strip_all_tags($persons);
		$date			= wp_strip_all_tags($date);
		$time			= wp_strip_all_tags($time);
		
			
		/*=================================================
			9.	SAVING PROCESS
				1. 	VARIABLE
				2.	CREATE CONFIRMATION KEY
				3.	SAVING DATA TO DATABASE
				4.	SENDING EMAIL		
		=================================================*/	
					
					/*=====================================
						1. 	VARIABLE
					=====================================*/	
					$saving_database 			= true;
					$send_email_to_owner		= false;
					if( $options['email_to_owner'] ){
						$send_email_to_owner = true;
					}
					
					$send_email_to_customer		= false;
					if( $options['email_to_customer'] ){
						$send_email_to_customer	 = true;
					}
					
					
						/*=====================================
							2.	CREATE CONFIRMATION KEY
						=====================================*/
						$confirm_code = md5(uniqid(rand())); 
						$string1 = substr($confirm_code,0,3);
						$string2 = substr($confirm_code,8,11);
						$string3 = substr($confirm_code,5,7);
						$string4 = substr($confirm_code,12,13);
						$string5 = substr($confirm_code,8,11);
						$confirmation_key = $string1.$string2.$string3.$string4.$string5;
						
		
						/*======================================================================
							3.	SAVING DATA TO DATABASE
								1.	DELETE DATA ON , client_reservation_ip_address , TABLE
								2.	DELETE DATA ON , temporary_table_reservation , TABLE
						======================================================================*/
						if( $saving_database ) {
							
							$post = array(
											'post_type'		=> $olr['post_type'],
											'post_title'	=> wp_strip_all_tags($name),
											'post_status'	=> $booking_status,
											'post_date'		=> $today_date_time
										);
							
							$post_id = wp_insert_post( $post);
							
							$meta = array(
										'Phone' 			=> wp_strip_all_tags($phone),
										'Email' 			=> wp_strip_all_tags($email),
										'Type_of_Tables'	=> $type_of_table,
										'Tables' 			=> $table,
										'Persons' 			=> wp_strip_all_tags($persons),
										'date'				=> $today_date_time,
										'Booking_Date' 		=> $date,
										'Booking_Time' 		=> $time,
										'Confirmation_Key' 	=> $confirmation_key,
										'Message'			=> $message
									);
		
							$success = update_post_meta($post_id, $olr['meta_key'], $meta);
							
							
							if( $success ){
								
								if( $booking_status == 'pending' ){
									$success_message = 'Booking Success';
									if( 	isset($options['success_message']) 
										&& 	$options['success_message'] != '' ){
										$success_message = $options['success_message']; 
									}
									
									$confirmation_link = 	$options['resto_thank_you_page_url'] 
																.'&confirmation_key=' .$confirmation_key
																.'&id=' .$post_id; 
									echo $success_message;
									
								}else{ //$booking_status == 'enquiry'
									echo 'Your Enquiry is sent';	
									exit();
								}
								
								//=	1.	DELETE DATA ON , client_reservation_ip_address , TABLE
								$sql = "DELETE FROM ".$ip_address_tablename." WHERE session_id='".session_id()."'";
								$wpdb->query($sql);
								//=	2.	DELETE DATA ON , temporary_table_reservation , TABLE
								$sql = "DELETE FROM ".TEMPORARY_DATA_TABLE." WHERE session_id='".session_id()."'";
								$wpdb->query($sql);
									

								
							}else{
								echo $failed_message = $options['failed_message'];	
							}

						} // if( $saving_database ) {
	
						
						/*================================================================
							4.	SENDING EMAIL
								1.	EMAIL INFORMATION
								2.	SEND EMAIL TO OWNER
								3.	SEND EMAIL TO CUSTOMER
						================================================================*/
						if( $success ){ // INSERT POST 
							
							/*================================================================
								1.	EMAIL INFORMATION
							================================================================*/
							$email_from = wp_strip_all_tags( $options['email_from'] );
							$restaurant_icon_exist = false;
							
							if( $options['restaurant_name'] != '' ){
								$restaurant_name = $options['restaurant_name'];
							}
							if( $options['restaurant_address'] != '' ){
								$restaurant_address = $options['restaurant_address'];
							}
							if( $options['restaurant_phone'] != '' ){
								$restaurant_phone = $options['restaurant_phone'];
							}
							if( $options['restaurant_fax'] != '' ){
								$restaurant_fax = $options['restaurant_fax'];
							}
							if( $options['restaurant_email'] != '' ){
								$restaurant_email = $options['restaurant_email'];
							}
							if( $options['restaurant_website'] != '' ){
								$restaurant_website = $options['restaurant_website'];
							}
							if( $options['restaurant_logo'] != '' ){
								$restaurant_logo = $options['restaurant_logo'];
							}
							if( $options['restaurant_image'] != '' ){
								$restaurant_image = $options['restaurant_image'];
							}
							if( $options['restaurant_offer_link'] != '' ){
								$restaurant_offer_link = $options['restaurant_offer_link'];
							}
							if( $options['restaurant_reservation_link'] != '' ){
								$restaurant_reservation_link = $options['restaurant_reservation_link'];
							}
							if( $options['restaurant_message'] != '' ){
								$restaurant_message = $options['restaurant_message'];
							}
							if( $options['restaurant_information'] != '' ){
								$restaurant_information = $options['restaurant_information'];
							}
							if( $options['restaurant_policies'] != '' ){
								$restaurant_policies = $options['restaurant_policies'];
							}
							if( $options['restaurant_facebook'] != '' ){
								$restaurant_facebook = $options['restaurant_facebook'];
								$restaurant_icon_exist = true;
							}
							if( $options['restaurant_twitter'] != '' ){
								$restaurant_twitter = $options['restaurant_twitter'];
								$restaurant_icon_exist = true;
							}
							if( $options['restaurant_google'] != '' ){
								$restaurant_google = $options['restaurant_google'];
								$restaurant_icon_exist = true;
							}
							if( $options['restaurant_linkedin'] != '' ){
								$restaurant_linkedin = $options['restaurant_linkedin'];
								$restaurant_icon_exist = true;
							}
							if( $options['restaurant_pinterest'] != '' ){
								$restaurant_pinterest = $options['restaurant_pinterest'];
								$restaurant_icon_exist = true;
							}
							if( $options['restaurant_youtube'] != '' ){
								$restaurant_youtube = $options['restaurant_youtube'];
								$restaurant_icon_exist = true;
							}
							if( $options['restaurant_footer'] != '' ){
								$restaurant_footer = $options['restaurant_footer'];
							}
						
						
							/*=====================================
								2.	SEND EMAIL TO OWNER
							=====================================*/
							//= CREATE BOUNDARY 
							$boundary = uniqid('np');
							
							if( $send_email_to_owner ) {
								
								$owner_email 			= wp_strip_all_tags( $options['owner_email'] );
								
								if( $options['owner_email_subject'] != '' ){
									$owner_email_subject 	= wp_strip_all_tags( $options['owner_email_subject'] );
								}else{
									$owner_email_subject 	= 'Restaurant Reservation';
								}
								
								if( $owner_email != '' ){
									
									//= 1.	RECIPIENT
									$recipient = $owner_email;
									
									//= 2.	SUBJECT
									$subject = $owner_email_subject;
									
									/*================================================
										3.	MESSAGE
											1.	PLAIN TEXT
											2.	HTML
									================================================*/
										require_once('email-content.php');
										
										//= 1.	PLAIN TEXT
										$messages = "\r\n\r\n--" . $boundary . "\r\n";
										$messages .= "Content-type: text/plain;charset=utf-8\r\n\r\n";
										$messages .= $text_email;
										
										
										//= 2.	HTML
										$messages .= "\r\n\r\n--" . $boundary . "\r\n";
										$messages .= "Content-type: text/html;charset=utf-8\r\n\r\n";
										$messages .= $email_template;
										$messages .= "\r\n\r\n--" . $boundary . "--";
										
										
									//= 4.	HEADERS
									$headers = "MIME-Version: 1.0" . "\r\n";
									$headers .= "From: ".$restaurant_name." \r\n";
									$headers .= "To: ".$name." \r\n";
									//$headers .= "To: ".$email." \r\n";
									//$headers .= "Content-type: text/html;charset=UTF-8" . "\r\n"; // FOR HTML
									$headers .= "Content-Type: multipart/alternative;boundary=" . $boundary . "\r\n"; // FOR SENDING TEXT AND HTML
									
									if( $_SERVER['HTTP_HOST'] != 'localhost' ){
										$result  = mail($recipient,$subject, $messages,$headers);
									}
		
								} // if( $owner_email != ''){
									
							}	// if( $send_email_to_owner ) {		
									
								
							/*=====================================
								3.	SEND EMAIL TO CUSTOMER
									1.	RECIPIENT
									2.	SUBJECT
									3.	MESSAGE
									4.	HEADERS
							=====================================*/
							if( $send_email_to_customer ) {
								
							
								$customer_email 			= $email;
								if( $options['customer_email_subject'] != '' ){
									$customer_email_subject	= wp_strip_all_tags( $options['customer_email_subject'] );
								}else{
									$customer_email_subject = 'Restaurant Reservation';
								}
								
								
								if( $customer_email != '' ){
									//= 1.	RECIPIENT
									$recipient = $customer_email;
									
									//= 2.	SUBJECT
									$subject = $customer_email_subject;
	
									
									/*================================================
										3.	MESSAGE
											1.	PLAIN TEXT
											2.	HTML
									================================================*/
										require_once( $plugin_path . 'restaurant reservation/email-content.php');
										
										//= 1.	PLAIN TEXT
										$messages = "\r\n\r\n--" . $boundary . "\r\n";
										$messages .= "Content-type: text/plain;charset=utf-8\r\n\r\n";
										$messages .= $text_email;
										
										
										//= 2.	HTML
										$messages .= "\r\n\r\n--" . $boundary . "\r\n";
										$messages .= "Content-type: text/html;charset=utf-8\r\n\r\n";
										$messages .= $email_template;
										$messages .= "\r\n\r\n--" . $boundary . "--";
	
									
									//= 4.	HEADERS
									$headers = "MIME-Version: 1.0" . "\r\n";
									$headers .= "From: ".$restaurant_name." \r\n";
									$headers .= "To: ".$name." \r\n";
									//$headers .= "To: ".$email." \r\n";
									//$headers .= "Content-type: text/html;charset=UTF-8" . "\r\n"; // FOR HTML
									$headers .= "Content-Type: multipart/alternative;boundary=" . $boundary . "\r\n"; // FOR SENDING TEXT AND HTML
									
									
									if( $_SERVER['HTTP_HOST'] != 'localhost' ){
										$result  = mail($recipient,$subject, $messages,$headers);
										if( $result ){
											echo "<br />";
											echo $email_message_array['customer']['message'];
										}
	
									}
									
								
								} // if( $customer_email != ''){
								
								
							}	// if( $send_email_to_customer ) {
						
						} // if( $success ){
		exit;
		
	} // function olr_resto_booking_ajax()
		
	
	/*#################################################################
		3.	RESTO FIND TABLE AJAX SUBMIT
			IS TRIGGERED ON , restaurant-script.js
			postData['action'] 	= 'resto-find-table-ajax-submit';
			
			CONTENT
			===========
			1.	GENERAL VARIABLE
			2.	HELPER FUNCTIONS
			3.	FIND TYPE OF TABLE 
			4.	FIND CUSTOMER DATA RELATED WITH CHOOSEN DATE
			5.	INITIALIZE FINDING A TABLE
	#################################################################*/	
	add_action( 'wp_ajax_nopriv_resto-find-table-ajax-submit', 'olr_resto_find_table_ajax' );
	add_action( 'wp_ajax_resto-find-table-ajax-submit', 'olr_resto_find_table_ajax' );
		 
	function olr_resto_find_table_ajax() {
		
		global $message_array;
		global $choosen_date;
		global $choosen_time;
		global $country;
		global $city;
		global $latitude;
		global $longitude;
		$choosen_date 		= $_POST['date'];
		$choosen_time 		= $_POST['time'];
		$persons_request 	= $_POST['person'];
		$country			= $_POST['country'];
		$city				= $_POST['city'];
		$latitude			= $_POST['latitude'];
		$longitude			= $_POST['longitude'];
	

		/*=======================================================================
			1.	GENERAL VARIABLE
				1.	OPTIONS
				2.	OPEN AND CLOSE TIME
				3.	RESERVATION LENGTH
				4.	HOW MANY OTHER DIFFERENT TIMES
				5.	CUSTOMER DATE SAME TIME
				6.	TYPE TABLE CHOOSEN
				7.	SET TIMEZONE
				8.	TEMPORARY DATA LENGTH ( SECONDS )
				9.	DISABLE RESERVATION LENGTH
				10.	MAX FIND TABLE ATTEMPTS
				11.	TODAY DATE
				12.	IP ADDRESSS AND SESSION ID
		=======================================================================*/
		global $wpdb;
		
		//=	1.	OPTIONS
		global $options;
		
		//= 2.	OPEN AND CLOSE TIME
		global $open_time;
		global $close_time;
		$all_date = array(
						  'Mon' => 'monday',
						  'Tue' => 'tuesday',
						  'Wed' => 'wednesday',
						  'Thu' => 'thursday',
						  'Fri' => 'friday',
						  'Sat' => 'saturday',
						  'Sun' => 'sunday'
						  );
		$chosen_day = $all_date[ date("D" , strtotime($choosen_date) ) ];
		$open_time	= $options['open_time_' . $chosen_day ];
		$open_time 	= ( substr($open_time,0,2) * 60 ) + substr($open_time,3,2);
		$close_time = $options['close_time_' . $chosen_day ];
		$close_time = ( substr($close_time,0,2) * 60 ) + substr($close_time,3,2);
		
		//= 3.	RESERVATION LENGTH
		$reservation_length = $options['reservation_length'];
		
		//= 4.	HOW MANY OTHER DIFFERENT TIMES
		global $other_times;
		$other_times = $options['other_times'] - 0;
		
		//=	5.	CUSTOMER DATE SAME TIME
		global $customer_data_same_time;
		
		//= 6.	TYPE TABLE CHOOSEN
		global $type_table_choosen;
		
		//= 7.	SET TIMEZONE
		date_default_timezone_set( $options['timezone'] );
		
		//= 8.	TEMPORARY DATA LENGTH ( SECONDS )
		global $temporary_data_length; // DECLARED ON , online-reservation.php 
		
		//= 9.	DISABLE RESERVATION LENGTH
		global $lockout_reservation_length; // DECLARED ON , online-reservation.php 
		
		//= 10.	MAX FIND TABLE ATTEMPTS
		global $max_find_table_attempt; // DECLARED ON , online-reservation.php 
		
		//= 11.	TODAY DATE
		global $today_date_time;
		
		//= 12.	IP ADDRESSS AND SESSION ID
		global $ip_address;
		global $session_id;
		
		
		
		/*================================================================
			2.	HELPER FUNCTIONS
				1.	CHECK IF TABLE IS FOUND ON SPECIFIED TIME
				2.	COMBINE CUSTOMER DATA
				3.	OUTPUT RESULT
				4.	SAVING DATA TO TEMPORARY TABLE RESERVATION
				5.	COUNT FIND TABLE ATTEMPT
				6	SAVING DATA TO LOCKOUT TABLE	
		================================================================*/
		
			/*===========================================================
				1.	CHECK IF TABLE IS FOUND ON SPECIFIED TIME
					1.	ALL VARIABLE
					2.	FIND CUSTOMER DATA RELATED WITH CHOOSEN TIME
					3.	CHECK IF TABLE IS FOUND
			===========================================================*/
			function check_if_table_is_found($all_customers_data,$reservation_length,$choosen_time,$options,$repeat='',$find_new_time=''){
				global $type_table_choosen;
				$time = $choosen_time;
				
				$choosen_time = ( substr($choosen_time,0,2) * 60 ) + substr($choosen_time,3,2) - 0;
				
				if( $find_new_time != '' ){
					$choosen_time = $choosen_time + ( ( $options['time_interval'] - 0) * ( $find_new_time - 0 ) );
				}
				
				$minutes 	= ( $choosen_time % 60 ) - 0;
				$hour  		= ( ( $choosen_time - $minutes ) / 60) - 0;
				if( $minutes < 10 ){
					$minutes = '0'.$minutes;
				}
				
				if( $hour < 10 ){
					$hour = '0'. $hour;
				}
				
				/*===========================================================
					1.	ALL VARIABLE
				===========================================================*/
				$x = 0;
				$total_tables_used = 0;
				
				/*===========================================================
					2.	FIND CUSTOMER DATA RELATED WITH CHOOSEN TIME
				===========================================================*/
				$same_time_list = '';
				
				foreach( $all_customers_data as $data ){
					
					$start_time = ( substr($data['Booking_Time'],0,2) * 60 ) + substr($data['Booking_Time'],3,2);
					$end_time 	= $start_time + $reservation_length;
					
					$same_time = false;
					$first_condition 	= false;
					$second_condition 	= false;
					if( 	$start_time <= $choosen_time 
						&&	$end_time >	$choosen_time 	  
					){
						$first_condition = true;
					}
					if( 	$start_time >= $choosen_time 
						&&	$start_time < ( $choosen_time + $reservation_length ) 	  
					){
						$second_condition = true;
					}
					
					if( 	$first_condition 
						||	$second_condition
					){
						$same_time = true;
					}
					
					$debug = 'no';
					
					if( $same_time ){
						
						if( $debug == 'yes' ){
							$minutes1 	= ( $start_time % 60 ) - 0;
							$hour1  		= ( ( $start_time - $minutes1 ) / 60) - 0;
							if( $minutes1 < 10 ){
								$minutes1 = '0'.$minutes1;
							}

							
							if( $hour1 < 10 ){
								$hour1 = '0'. $hour1;
							}
							
							
							$same_time_list .= 'start_time ,  ' . $hour1 . ':' .$minutes1 ;
							$same_time_list .= '<br />'; 
							
							
							$minutes2 	= ( $end_time % 60 ) - 0;
							$hour2  		= ( ( $end_time - $minutes2 ) / 60) - 0;
							if( $minutes2 < 10 ){
								$minutes2 = '0'.$minutes2;
							}
							
							if( $hour2 < 10 ){
								$hour2 = '0'. $hour2;
							}
							$same_time_list .= 'end_time ,  ' . $hour2 . ':' .$minutes2 ;
							$same_time_list .= '<br />'; 
						} // if( $debug == 'yes' ){
						
						$customer_data_same_times[$x]['Email'] 				= $data['Email'];
						$customer_data_same_times[$x]['Booking_Time'] 		= $data['Booking_Time'];
						$total_table_used_array[$x]['type'] 				= $data['Type_of_Tables'];
						$total_table_used_array[$x]['total_table'] 			= $data['Tables'];
						
						$total_tables_used += $data['Tables'];
					}
					$x++;
				
				} // foreach( $customers_data as $data ){
				
				
				//= COMBINE TOTAL TABLE USED DATA
				$filter_table = '';
				$p = 0;
				if( $total_table_used_array != '' ){
					foreach( $total_table_used_array as $val ){
						if( $filter_table == '' ){
							$filter_table[$p] =  $val;	
						}else{
							
							$same = true;
							foreach( $filter_table as $key => $filter ){
								if( $filter['type'] == $val['type'] ){
									$total_table = $filter['total_table'] + $val['total_table'];
									$filter_table[$key]['total_table'] = $total_table;
									break;
								}else{
									$same = false;	
								}
							}
							if( !$same ){
								$filter_table[$p] = $val;
							}
						}
						$p++;
					}
					$total_table_used_array = $filter_table;
				}
				
				/*=====================================================
					3.	CHECK IF TABLE IS FOUND
				=====================================================*/
				if( $options['table_size'] == 'one' ){
					
					if( ($options['one_total_table'] - $total_tables_used) > 0 ){
						$still_have_table = true;
					}else{
						$still_have_table = false;
					}	
				}else{
					
					$total_table = '';
					foreach( $type_table_choosen as $table ){
						$total_table += $table['total_table'];
					}
					
					if( ($total_table - $total_tables_used) > 0 ){
						$still_have_table = true;
						
						if( $total_table_used_array != '' ){
							//= FIND TYPE OF TABLE IS STILL FREE TO BE BOOKED
							foreach( $total_table_used_array as $table_used ){
								foreach( $type_table_choosen as $key => $table ){
									if( $table_used['type'] == $table['type'] ){
										$table_left = $table['total_table'] - $table_used['total_table'];
										$type_table_choosen[$key]['total_table'] = $table_left;

										break;
									}
								}
							}
						}	
						
						foreach( $type_table_choosen as $table ){
							if( $table['total_table'] > 0 ){
								$table_type_choosen_for_customer = $table['type'];
								break;
							}
						}
						
					}else{
						$still_have_table = false;
					}
	
				}
				
				if( $debug == 'yes' ){
					echo 'choosen time : ' .$hour.':'.$minutes;  	
					echo '<br/ >';
					echo $same_time_list;
					echo 'total table used : ' .$total_tables_used;
					echo '<br/ >';
					echo 'total table : ' . $total_table;
					echo '<br/ >';
					echo '<br/ >';
					exit();
				}
				
				
				
				
				
				
				if( $still_have_table ){
					$response['have_table'] 	= 'true';
					$response['repeat'] 		= $repeat;
					$response['choosen_time']	= $choosen_time;
					$response['choosen_date']  	= $choosen_date; 
					$response['type_table']		= $table_type_choosen_for_customer;
					return $response;
				}else{
					$response['have_table'] 			= 'false';
					$response['all_customer_data'] 		= $all_customers_data;
					$new_customer_data 	= combine_customer_data($customer_data_same_times);
					$response['customer_data_same_time'] = $new_customer_data;
					$response['choosen_time']	= $choosen_time;
					return $response;	
				}
				
				
			} // function check_customer_date_has_same_time
			
			
			/*===========================================================
				2.	COMBINE CUSTOMER DATA
			===========================================================*/
			function combine_customer_data($customer_data){
				
				global $customer_data_same_time;
				
				$old_data = '';
				if( $customer_data_same_time != ''){
					$old_data = $customer_data_same_time;
				}
				$new_data = $customer_data;
				
					$data_same = true;
					if( $new_data != '' ){
						foreach( $new_data as $new ){
							
							$data_same = false;
							if( $old_data != '' ){
								foreach( $old_data as $old ){
									if( 	( $old['Email'] ==  $new['Email'] )
										&&	( $old['Booking_Time'] ==  $new['Booking_Time'] )
									){
										$data_same = true;
										break;
									}else{
										$data_same = false;
									}
								}
							}
							
							if( !$data_same){
								$old_data[] = array(
												  	'Email'			=>	$new['Email'],
													'Booking_Time'	=>	$new['Booking_Time'],
													'process'		=>	''
												  );
							}
						}
					}
					//$combine_data = array_merge($old_data,$new_data);
					$customer_data_same_time = $old_data;
					$customer_data = $old_data;
					
				return $customer_data;
				
			} // function combine_customer_data
			
			/*===========================================================
				3.	OUTPUT RESULT
					1.	VARIABLE
					2.	STATUS
					3.	SAVING TEMPORARY DATA
					4.	COUNT FIND TABLE ATTEMPT
					5.	SAVING DATA TO LOCKOUT TABLE
			===========================================================*/
			function output_result($type ='',$other_time='',$type_table=''){

				//= 1.	VARIABLE
				global $choosen_date;
				global $choosen_time;
				global $lockout_reservation_length;
				global $ip_address;
				global $country;
				global $city;
				global $latitude;
				global $longitude;
				global $message_array;
				global $today_date_time;
				$email = '';
				
				//= 2.	STATUS
				$output = array(
									'0'	=> 	array(
												  	'status'	=>	'Found',
													'title'		=>	$message_array['Found']['title'],
												   	'answer'	=>	$message_array['Found']['message']
													),
									'1'	=> 	array(
												  	'status'	=>	'Not Found',
													'title'		=>	$message_array['Not Found']['title'],
												   	'answer'	=>	$message_array['Not Found']['message']
													),
									'2'	=> 	array(
												  	'status'	=>	'Not Found',
													'title'		=>	$message_array['Not Found With Other Time']['title'],
												   	'answer'	=>	$message_array['Not Found With Other Time']['message'] . $other_time
													),
									'3'	=> 	array(
												  	'status'	=>	'Fully Booked',
													'title'		=>	$message_array['Fully Booked']['title'],
												   	'answer'	=>	$message_array['Fully Booked']['message']
													),
									'4' => 	array(
												  	'status'	=>	'TOO MANY FAKE ACTIONS',
													'title'		=>	$message_array['TOO MANY FAKE ACTIONS']['title'],
												   	'answer'	=>	$message_array['TOO MANY FAKE ACTIONS']['message']
													),
									'5' => 	array(
												  	'status'	=>	'Enquiry',
													'title'		=>	$message_array['Enquiry']['title'],
												   	'answer'	=>	$message_array['Enquiry']['message']
													)
									);
			
				
				
				
				//= 3.	SAVING TEMPORARY DATA
				if( $type == '0' ){
					$booking_date = $choosen_date . ' ' . $choosen_time ;
					saving_data_into_temporary_table($booking_date,$type_table);
				}
				
				//= 4.	COUNT FIND TABLE ATTEMPT
				if( $type == '0' ){
					if( count_find_table_attempt() == 'true' ){
						
						$type = '4';
						
						//= 5.	SAVING DATA TO LOCKOUT TABLE
						saving_data_into_lockout_table('TOO MANY FIND TABLE ATTEMPT');
					}
				}
				
				$outputs['status'] 	= $output[$type]['status'];
				$outputs['title'] 	= $output[$type]['title'];
				$outputs['answer'] 	= $output[$type]['answer'];
                if( $type_table != '' ){
					$outputs['type_table'] 	= $type_table;
				}
				return json_encode( $outputs );
			}
		
			/*===========================================================
				4.	SAVING DATA TO TEMPORARY TABLE RESERVATION
					1.	VARIABLE
					2.	SAVING DATA INTO TEMPORARY TABLE
			===========================================================*/
			function saving_data_into_temporary_table($booking_date,$type_table){
				
				/*===================================================================
					1.	VARIABLE
				===================================================================*/
						global $wpdb;
						global $temporary_data_length;
						global $lockout_reservation_length;
						global $max_find_table_attempt;
						global $today_date_time;
						global $ip_address;
						global $session_id;
						
						$expired_date_temporary_table  = date("Y-m-d h:i:s",strtotime($today_date_time) - $temporary_data_length); 
						
				/*===================================================================
					2.	SAVING DATA INTO TEMPORARY TABLE
						1. 	DELETE ALL EXPIRED DATA 
						2.	CHECK SESSION ID IS EXIST
				===================================================================*/
							$tablename = TEMPORARY_DATA_TABLE;
							//= 1. DELETE ALL EXPIRATION DATA 
							$sql = "DELETE FROM ".$tablename." WHERE `date` < '".$expired_date_temporary_table."' ";
							$action = $wpdb->query($sql);	
							
							
							//= 2.	CHECK SESSION ID IS EXIST
							$sql = "SELECT session_id FROM ".$tablename;
							$data = $wpdb->get_results($sql,ARRAY_A);
							if( $data ){
								
								$booking_date 	= date("Y-m-d H:i",strtotime($booking_date)); 
								$type_table 	= $type_table;	
								$sql = 	"UPDATE ".$tablename." SET date='".$today_date_time."' , booking_date='".$booking_date."' ".
										",type_table='".$type_table."' WHERE session_id='".$session_id."'";
								$update = $wpdb->query($sql);
	
							}else{
								
								$sql = 	"INSERT INTO ".$tablename." ( date, session_id, booking_date, book_date, type_table, email) ".
										"VALUES ( %s, %s, %s, %s, %s, %s)";
								$booking_date 	= date("Y-m-d H:i",strtotime($booking_date)); 
								$book_date 		= date("Y-m-d",strtotime($booking_date)); 
								$type_table 	= $type_table;	
								$email 			= $session_id;	
								
								$action = $wpdb->query( $wpdb->prepare( 
												$sql,
												$today_date_time,
												$session_id, 
												$booking_date,
												$book_date,
												$type_table,
												$email
											));	
							}
			
			} // function saving_data_into_temporary_table($booking_date,$type_table){
			
			
			/*===========================================================
				5.	COUNT FIND TABLE ATTEMPT
					1.	VARIABLE
					2. 	DELETE ALL EXPIRED DATA 
					3.	CHECK IP ADDRESS EXIST
					4.	CHECK FIND TABLE ATTEMPT
					5.	BLOCK CLIENT TO ACCESS RESERVATION SYSTEM
			===========================================================*/
			function count_find_table_attempt(){
				
				global $today_date_time;
				global $lockout_reservation_length;
				global $max_find_table_attempt;
				global $ip_address;
				global $country;
				global $city;
				global $latitude;
				global $longitude;
				global $wpdb;
				
				//= 1.	VARIABLE
				$expired_date_find_attempt  = date("Y-m-d h:i:s",strtotime($today_date_time) - $lockout_reservation_length); 
				$email 		= '';
				
				//= 2.	DELETE ALL EXPIRED DATA
				$sql = "DELETE FROM ".FIND_TABLE_ATTEMPT." WHERE `date` < '".$expired_date_find_attempt."' ";
				$delete_data = $wpdb->query($sql);
								
				//= 3.	CHECK IP ADDRESS EXIST
				$sql = 	"SELECT * FROM ".FIND_TABLE_ATTEMPT." WHERE latitude='".$latitude."' AND longitude='".$longitude."'";
				$data = $wpdb->get_results($sql,ARRAY_A);
								
				if( !$data){
					
					$find_table_click_times = '1';
					$sql = 	"INSERT INTO ".FIND_TABLE_ATTEMPT." ( date, ip_address, latitude, longitude, find_table_attempt) ".
						  	"VALUES ( %s, %s, %s, %s, %s )";
					$insert = $wpdb->query( $wpdb->prepare( 
												$sql,
												$today_date_time,
												$ip_address, 
												$latitude,
												$longitude,
												$find_table_click_times
											));	
					
					$too_many_find_table = 'false';
										
				}else{
					
					//= 4.	CHECK FIND TABLE ATTEMPT
					$sql = 	"SELECT find_table_attempt FROM ".FIND_TABLE_ATTEMPT.
							" WHERE latitude='".$latitude."' AND longitude='".$longitude."'";
					$current_attempt = $wpdb->get_results($sql,ARRAY_A);
					$current_attempt = $current_attempt[0]['find_table_attempt'];
					
					if( ( $current_attempt - 0 ) < $max_find_table_attempt ){
										
						$current_attempt = ( $current_attempt - 0 ) + 1;
						$sql = 	"UPDATE ".FIND_TABLE_ATTEMPT." SET find_table_attempt='".$current_attempt."'".
								" WHERE latitude='".$latitude."' AND longitude='".$longitude."'";
						$update = $wpdb->query($sql);
						
						$too_many_find_table = 'false';
	
					}else{
						$too_many_find_table = 'true';
					}
				}
				return $too_many_find_table;
			}
			
			/*===========================================================
				6	SAVING DATA TO LOCKOUT TABLE
			===========================================================*/
			function saving_data_into_lockout_table($lockout_type='',$email=''){
				global $today_date_time;
				lockout_table($today_date_time,$lockout_type,'save data');
			}

		
		/*=======================================================
			3.	FIND TYPE OF TABLE
		=======================================================*/
		if( $options['table_size'] == 'many' ){
			if( $options['many_type_of_table'] != '' ){
				$many_table_type = explode(',',str_replace(' ','',$options['many_type_of_table']) );
				
				global $many_table_id;
				if( $many_table_type != '' ){
					$b = 0;
					foreach( $many_table_type as $key => $val){
						$table_type = preg_match('/(.+)(\()([0-9]+)(\))/', $val, $match_table);
						
						if( count($match_table) > 0 ){
							$person_per_table = $match_table['3'] - 0;
							$type_table = $match_table['1'];
							if( $b<3){
								if( $person_per_table >= $persons_request ){
									if( $options[ $type_table . '_table'] > 0 ){
										$type_table_choosen[$b] = array(
																		'total_table' 	=>  $options[ $type_table . '_table'],
																		'type' 			=>  $type_table
																		);
																				
										if( $persons_request == $person_per_table ){
											$type_table_choosen[$b] = array(
																		'total_table' 	=>  $options[ $type_table . '_table'],
																		'type' 			=>  $type_table
																		);
										}
									}
									$b++;
								}
							}
							if( $b==2){
								break;
							}
						}
										
					} // foreach( $many_table_type as $val){				
				} // if( $many_table_type != '' ){				
			} // if( $options['many_type_of_table'] != '' ){	
		} // if( $options['table_size'] == 'many' ){
		
		if( $type_table_choosen  == '' ){
			echo output_result('5'); // SEND AN ENQUIRY
			exit();
		}
		
		
		/*=======================================================
			4.	FIND CUSTOMER DATA RELATED WITH CHOOSEN DATE
		=======================================================*/	
		if( $options['table_size'] == 'one' ){		
			$args = array(
				  'posts_per_page' 	=> -1,
				  'post_type' 		=> 'olr_restaurant',
				  'post_status' 	=> array('pending','confirmed'),
				  'order' 			=> 'ASC',
				  'meta_query' => array (
						array (
						  'key' 	=> 'olr_custom_column',
						  'value' 	=> $choosen_date,
						  'compare' => 'LIKE'
						)
					  ) 
				  );		
			$new_query = new WP_Query( $args );
			$data = get_post_meta( $post->ID,'olr_custom_column' );
			$all_customers_data[] = $data[0];
		}
		
		if( $options['table_size'] == 'many' ){	
			if( $type_table_choosen != '' ){
				foreach( $type_table_choosen as $type ){
					
					$args = array(
					  'posts_per_page' 	=> -1,
					  'post_type' 		=> 'olr_restaurant',
					  'post_status' 	=> array('pending','confirmed'),
					  'order' 			=> 'ASC',
					  'meta_query' => array (
							array (
							  'key' 	=> 'olr_custom_column',
							  'value' 	=> $choosen_date,
							  'compare' => 'LIKE'
							),
							array (
							  'key' 	=> 'olr_custom_column',
							  'value' 	=> $type['type'],
							  'compare' => 'LIKE'
							)
						  ) 
					  );		
					$new_query = new WP_Query( $args );
					
					
					foreach( $new_query->posts as $post ){
						$data = get_post_meta( $post->ID,'olr_custom_column' );
						$all_customers_data[] = $data[0];
					}
				}
			}
		}
	
		
		
		/*=====================================================================================
			4.	FIND CUSTOMER DATA RELATED WITH CHOOSEN DATE ( ON temporary_reservation_data )
				1.	DELETE ALL EXPIRED DATA
				2.	DELETE DATA OF CURRENT ACTIVE CLIENT 
				3.	FIND DATA 
		=====================================================================================*/	
			//= 1.	DELETE ALL EXPIRED DATA
			$expired_date_ip_table  = date("Y-m-d H:i:s",strtotime($today_date_time) - $temporary_data_length); 
			$sql = "DELETE FROM ".TEMPORARY_DATA_TABLE." WHERE `date` < '".$expired_date_ip_table."' ";
			$delete_data = $wpdb->query($sql);
			
			//= 2.	DELETE DATA OF CURRENT ACTIVE CLIENT
			$expired_date_ip_table  = date("Y-m-d H:i:s",strtotime($today_date_time) - $temporary_data_length); 
			$sql = "DELETE FROM ".TEMPORARY_DATA_TABLE." WHERE session_id='".$session_id."'";
			$delete_data = $wpdb->query($sql);
			
			//= 3.	FIND DATA 
			$sql = 	"SELECT * FROM ".TEMPORARY_DATA_TABLE.
					" WHERE book_date='".date("Y-m-d",strtotime($choosen_date))."'";
			$temporary_data = $wpdb->get_results($sql,ARRAY_A);

			
			foreach( $temporary_data as $data ){
				
				$new_data = array(
									'Phone' 			=> '',	 
									'Email' 			=> $data['email'],
									'Type_of_Tables' 	=> $data['type_table'],
									'Tables' 			=> '1',
									'Persons' 			=> '',
									'Booking_Date' 		=> date("m/d/Y",strtotime($data['book_date'])),
									'Booking_Time' 		=> date("H:i",strtotime($data['booking_date'])),
									'Confirmation_Key' 	=> '',
									'Message' 			=> ''
								);
				
				$all_customers_data[] = $new_data;
			}
			
			
		/*================================================================
			5.	INITIALIZE FINDING A TABLE
		================================================================*/
		if( $all_customers_data != '' ){
			
			global $all_suggested_time;
			global $auto_find;
			global $c;
			$auto_find = 1;
			$c=0;
			
			function initialiaze_finding_table($all_customers_data,$reservation_length,$choosen_time,$options,$repeat='',$find_new_time=''){
				global $other_times;
				global $customer_data_same_time;
				global $c;
				global $all_suggested_time;
				global $auto_find;
				global $open_time;
				global $close_time;
				
				$output_allowed = false;
				
				$find_table_response = check_if_table_is_found($all_customers_data,$reservation_length,$choosen_time,$options,$repeat,$find_new_time);
				
			
				if( $find_table_response['have_table'] == 'false' ){
					
					if( $options['provide_other_time'] ){
					
						$all_customers_data			= $find_table_response['all_customer_data'];
						$customers_data_same_times 	= $find_table_response['customer_data_same_time'];
							
						for( $x=0;$x<count($customers_data_same_times);$x++ ){ 
							if( $customers_data_same_times[$x]['process'] != 'true' ){
								
								$choosen_time 	= $customers_data_same_times[$x]['Booking_Time'];
								foreach( $customer_data_same_time as $key => $val  ){
									if( $val['Booking_Time'] == $choosen_time ){
										$customer_data_same_time[$key]['process'] = 'true';
									}
								}
								initialiaze_finding_table($all_customers_data,$reservation_length,$choosen_time,$options,'yes');
							}
						} 
						
						$total_customer = count($customer_data_same_time)-1;
						$choosen_time = $customer_data_same_time[$total_customer]['Booking_Time'];
							
						$c++;
							
						for( $k=$c;$k<100;$k++){
							if( $auto_find <= $other_times ){
								initialiaze_finding_table($all_customers_data,$reservation_length,$choosen_time,$options,'yes',$k);
							}
						}
						exit();
						
					}else{
						
						echo output_result('1'); // TABLE IS NOT FOUND
						exit();
					} // if( $options['provide_other_time'] ){

				}else{
					
					$all_suggested_time[] = $find_table_response['choosen_time'];
					
					if( $repeat == 'yes' ){
						if( $auto_find >= $other_times ){
							
							$time_arrange = '';
							foreach( $all_suggested_time as $time ){
								if( $time_arrange == '' ){
									$time_arrange[] = $time;	
								}
								foreach( $time_arrange as $new ){
									
									if( $new != $time ){
										$same = false;	
									}else{
										$same = true;
										break;
									}
								}
								if( !$same ){
									$time_arrange[] = $time;
								}
							}
							sort( $time_arrange );
							
							foreach( $time_arrange as $time ){
								if( 	$time >= $open_time
									&&	$time <= $close_time
								){
									$filter_time_arrange[] = $time;
								}
							}
							
							if( $filter_time_arrange != '' ){
								foreach( $filter_time_arrange as $key => $val ){
									$minutes 	= ( $val % 60 ) - 0;
									$hour  		= ( ( $val - $minutes ) / 60) - 0;
									if( $minutes < 10 ){
										$minutes = '0'.$minutes;
									}
									
									if( $hour < 10 ){
										$hour = '0'. $hour;
									}
									$filter_time_arrange[$key] = $hour .':'.$minutes;
								}
								
								$all_other_time = implode(' , ',$filter_time_arrange);
								
								$output['type'] 		= '2'; // TABLE IS NOT FOUND
								$output['other_time'] 	= $all_other_time;
								$output['type_table'] 	= '';
								$output_allowed = true;	
								
							}else{
								$output['type'] 		= '3'; // FULLY BOOKED
								$output['other_time'] 	= '';
								$output['type_table'] 	= '';
								$output_allowed = true;	
							}
						}
						$auto_find++;
					} // if( $repeat == 'yes' ){
					
					if( $repeat == '' ){
						$output['type'] 		= '0'; // TABLE FOUND
						$output['other_time'] 	= '';
						$output['type_table'] 	= $find_table_response['type_table'];
						$output_allowed = true;	
					}
					
					if( $output_allowed ){
						echo output_result($output['type'],$output['other_time'],$output['type_table']);
						exit();
					}
					
				} // if( $find_table_response['have_table'] == 'false' ){
				
			} // function initialiaze_finding_table(
			
			echo initialiaze_finding_table($all_customers_data,$reservation_length,$choosen_time,$options);	
			
		}else{
			echo output_result('0','',$type_table_choosen[0]['type']); // TABLE FOUND
			
		} // if( $all_customers_data != '' ){
		
		exit();
	}

	
	/*#################################################################
		4.	RESTO CHECK TABLE LOCKOUT
	#################################################################*/	
	add_action( 'wp_ajax_nopriv_check-table-lockout', 'olr_check_table_lockout' );
	add_action( 'wp_ajax_check-table-lockout', 'olr_check_table_lockout' );
		 
	function olr_check_table_lockout(){
		
		//= 1.	VARIABLE
		global $wpdb;
		global $lockout_reservation_length;
		
		$expired_lockout_date  = date("Y-m-d h:i:s",strtotime($today_date_time) - $lockout_reservation_length); 
		
		//= 2.	DELETE EXPIRED DATA
		$sql = "DELETE FROM ".LOCKOUT_TABLE." WHERE `date` < '".$expired_lockout_date."' ";
		$delete_data = $wpdb->query($sql);
		
		//= 3.	CHECK IF DATA IS EXIST
		$sql = "SELECT * FROM ".LOCKOUT_TABLE." WHERE latitude='".$_POST['latitude']."' AND longitude='".$_POST['longitude']."'";
		$data = $wpdb->get_results($sql,ARRAY_A);
		
		if( $data ){
			echo true;	
		}else{
			echo false;	
		}
		exit();
	}

?>