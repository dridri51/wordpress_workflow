<?php 
/*
	TABLE OF CONTENTS
	========================
	1.  OUTPUT TIME LIST
	2.	SAVE DATA ON LOCKOUT TABLE
	3.	RESTAURANT RESERVATION CONTENT
*/
	/*=====================================================
		1.  OUTPUT TIME LIST
	=====================================================*/
	function olr_output_time_list( $options , $now = '' ,$day_choosen = '',$time_choosen = ''){
		
		$current_day		= strtolower( date("D") );
		if( $day_choosen != '' ){
			$current_day = strtolower($day_choosen);
		}

		if( $current_day == 'mon' ){
			$open_time 		= $options['open_time_monday'];
			$close_time 	= $options['close_time_monday'];
		}
		if( $current_day == 'tue' ){
			$open_time 		= $options['open_time_tuesday'];
			$close_time 	= $options['close_time_tuesday'];
		}
		if( $current_day == 'wed' ){
			$open_time 		= $options['open_time_wednesday'];
			$close_time 	= $options['close_time_wednesday'];
		}
		if( $current_day == 'thu' ){
			$open_time 		= $options['open_time_thursday'];
			$close_time 	= $options['close_time_thursday'];
		}
		if( $current_day == 'fri' ){
			$open_time 		= $options['open_time_friday'];
			$close_time 	= $options['close_time_friday'];
		}
		if( $current_day == 'sat' ){
			$open_time 		= $options['open_time_saturday'];
			$close_time 	= $options['close_time_saturday'];
		}
		if( $current_day == 'sun' ){
			$open_time 		= $options['open_time_sunday'];
			$close_time 	= $options['close_time_sunday'];
		}
		
		$time_interval = 30;
		if(  $options['time_interval'] != '' ){ 
			$time_interval = $options['time_interval'];
		}
		
		$open_times = preg_match('/([0-9]+):([0-9]+)|([0-9]+)/',$open_time, $open_match);
		$close_times = preg_match('/([0-9]+):([0-9]+)|([0-9]+)/',$close_time, $close_match);
		
		$open_hours = $open_match[1] - 0;
		$open_minutes = $open_match[2] - 0;
		
		$close_hours = $close_match[1] - 0;
		$close_minutes = $close_match[2] - 0;
		
		$start_minutes = ( $open_hours * 60 ) + $open_minutes;
		$end_minutes = ( $close_hours * 60 ) + $close_minutes;
		
		$output .= '<option selected="selected" val=""></option>';
		//$output = '<option value>'.$open_time.'</option>';
		for( $x = $start_minutes; $x < $end_minutes ;$x = $x + $time_interval ){
			$minutes = $x % 60 ;
			if( $minutes == 0 ){
				$minutes = '00';
			}else if( strlen($minutes) == 1 ){
				$minutes = '0' . $minutes;
			}
			
			$hour = ( $x - $minutes ) / 60;
			if( strlen( $hour ) == 1 ){
				$hour = '0' . $hour;
			} 
			$selected = '';
			if( $time_choosen == $hour . ":" . $minutes ){
				$selected = 'selected="selected"';
			}

			$output .= '<option '.$selected.' val="'.$hour . ":" . $minutes.'">'.$hour . ":" . $minutes.'</option>'; 
	
		}
		$output .= '<option val="'.$hour . ":" . $minutes.'">'.$close_time.'</option>';
		return $output;
	}
	
	/*=============================================================
		2.	SAVE DATA ON LOCKOUT TABLE
			1.	VARIABLE
			2.	DELETE EXPIRED DATA
			3.	CHECK IS SYSTEM IS BEING LOCKOUT
			4.	INSERT DATA
	=============================================================*/
	function lockout_table($today_date_time,$lockout_type,$action=''){
			
			global $lockout_reservation_length;
			global $country;
			global $city;
			global $latitude;
			global $longitude;
			global $wpdb;
			$ip_address = $_SERVER['REMOTE_ADDR'];
			$lockout_start = date("Y-m-d h:i:s",strtotime($today_date_time));
				
			//= 1.	VARIABLE
			$expired_lockout_date  = date("Y-m-d h:i:s",strtotime($today_date_time) - $lockout_reservation_length); 
			$email 		= '';
			
			//= 2.	DELETE EXPIRED DATA
			$sql = "DELETE FROM ".LOCKOUT_TABLE." WHERE `lockout_start` < '".$expired_lockout_date."' ";
			$delete_data = $wpdb->query($sql);
			
			//= 3.	CHECK IS SYSTEM IS BEING LOCKOUT
			if( $action == 'check is lockout' ){
				$sql = 	"SELECT lockout_type  FROM ".LOCKOUT_TABLE." WHERE latitude='".$latitude."' AND longitude='".$longitude."'";
				$data = $wpdb->get_results($sql,ARRAY_A);
				if( $data ){
					return true;	
				}else{
					return false;
				}
			}
			
			//= 4.	INSERT DATA
			if( $action == 'save data' ){
				$sql = 	"INSERT INTO ".LOCKOUT_TABLE." ( lockout_type, lockout_start, ip_address, latitude, longitude, country, city, email ) ".
						"VALUES ( %s, %s, %s, %s, %s, %s, %s, %s )";
				
				$insert = $wpdb->query( $wpdb->prepare( 
													$sql,
													$lockout_type,
													$lockout_start, 
													$ip_address,
													$latitude,
													$longitude,
													$country,
													$city,
													$email
												));
				
			}

	}
	
	
	/*=============================================================
		3.	RESTAURANT RESERVATION CONTENT
			1.	CHECK IF RESERVATION SYSTEM IS BEING LOCKOUT
			2.	UPDATE BOOKING LIST STATUS ( WHERE TIME IS EXPIRED ) 
			3.	RESERVATION FORM
	=============================================================*/
	function restaurant_reservation_content( $options ){
		
		global $geo_country;
		global $geo_city;
		global $latitude;
		global $longitude;
		global $message_array;
		global $email_confirmation_length;
		global $wpdb;
		$today 		= getdate();
		$today_date_time = $today['year'].'-'.$today['mon'].'-'.$today['mday'].' '.$today['hours'].':'.$today['minutes'].':'.$today['seconds'];
		
		
		$out = '';
		/*=============================================================
			1.	CHECK IF RESERVATION SYSTEM IS BEING LOCKOUT
		=============================================================*/
		if( lockout_table($today_date_time,'','check is lockout') ){
			$out .= '<p><strong>'.$message_array['TOO MANY FAKE ACTIONS']['title'].'</strong></p>';
			$out .= '<p>'.$message_array['TOO MANY FAKE ACTIONS']['message'].'</p>';
		}else{
			
			/*=============================================================
				2.	UPDATE BOOKING LIST STATUS ( WHERE TIME IS EXPIRED ) 
			=============================================================*/
			$expired_post_date  = date("Y-m-d H:i:s",strtotime($today_date_time) - $email_confirmation_length); 
			$sql = 	"UPDATE ".$wpdb->prefix."posts SET post_status='trash'".
					" WHERE post_type='olr_restaurant' AND post_status='pending' AND `post_date` < '".$expired_post_date."'";
			$update = $wpdb->query($sql);
			
			
			/*=============================================================
				3.	RESERVATION FORM
			=============================================================*/
			$out .='<div class="olr_restaurant_wrapper">';
			$out .='
			<form id="olr_restaurant_form" action="'.$_SERVER['PHP_SELF'].'" method="post">'.
			wp_nonce_field("restaurant_form_verify","restaurant_nonce_id")
			.'<h2>'.__( 'Booking Table', PLUGIN_NAME ).'</h2>
			<hr/>';
			
			$out .='
			<input type="hidden" name="country" id="country" disabled="disabled" value="'.$geo_country.'" />
			<input type="hidden" name="city" id="city" disabled="disabled" value="'.$geo_city.'" />
			<input type="hidden" name="latitude" id="latitude" disabled="disabled" value="'.$latitude.'" />
			<input type="hidden" name="longitude" id="longitude" disabled="disabled" value="'.$longitude.'" />
			
			<p>
				<span class="olr_label"><label for="">'.__( 'Persons', PLUGIN_NAME ).'</label></span> 
				<input type="text" name="olr_persons" id="olr_persons" value="" /> <span class="olr_required">*</span>
			</p>
			<div class="olr_date_section">
				<span class="olr_label"><label for="">'.__( 'Date', PLUGIN_NAME ).'</label></span> 
				<div class="olr_date_wrap">
					<input name="olr_date" id="olr_date" type="text" value="" /> <span class="olr_required">*</span>
				</div>	       
			</div>
			<div class="olr_time_section">
				<span class="olr_label"><label for="">'.__( 'Time', PLUGIN_NAME ).'</label></span>
				<div class="olr_time_wrap">
					<select name="olr_time" id="olr_time">
						<option value="" selected="selected"></option>';
						//$out .= olr_output_time_list ( $options );         
					$out .='</select> <span class="olr_required">*</span><span class="time_response"></span>
				</div>	
			</div><br/>';
			
			
	$out .='
			<p>
				<input type="submit" value="Find Table" id="olr_find_table_button" name="olr_find_table_button">
			</p>
			<div class="olr_find_table_response">
				<p class="olr_response_result"></p>
				<p class="olr_response_recommend"></p>
			</div>
			<p>
				<input type="button" value="Send an Enquiry" id="olr_send_enquiry_button" name="olr_send_enquiry_button">
			</p>
			<div class="personal_information_wrapper"></div>
			<p>
				<span class="olr_requireds">*</span><span>  '.__( 'Required fields', PLUGIN_NAME ).'</span> 
			</p>
			
			<p>
				<input type="submit" value="Booking" id="olr_restaurant_booking_button" name="olr_restaurant_booking_button">
			</p>
			
			</form>';
			$out .='<div id="olr_restaurant_response" ></div>';
			$out .='<div style="clear:both"></div>';
			if( $options['help_promote_plugin'] ){
				$out .='<div style="clear:both">Powered By <a href="http://www.solweder.com">solweder.com</a></div>';
			}
			$out .='</div>';
			
			$out .='<div class="personal_information_container">
				<h2>'.__( 'Personal Information', PLUGIN_NAME ).'</h2>
				<hr/>
				<p>
					<span class="olr_label"><label for="">'.__( 'Name', PLUGIN_NAME ).'</label></span> 
					<input type="text" name="olr_name" id="olr_name" value="" /> <span class="olr_required">*</span>
				</p>
				<p>
					<span class="olr_label"><label for="">'.__( 'Email Address', PLUGIN_NAME ).'</label></span> 
					<input type="text" name="olr_email" id="olr_email" value="" /> <span class="olr_required">*</span>
				</p>
				<p>
					<span class="olr_label"><label for="">'.__( 'Phone', PLUGIN_NAME ).'</label></span> 
					<input type="text" name="olr_phone" id="olr_phone" value="" /> <span class="olr_required">*</span>
				</p>
				<p>
					<span class="olr_label"><label for="">'.__( 'Message', PLUGIN_NAME ).'</label></span>
					<textarea rows="10" cols="30" name="olr_message" id="olr_message"></textarea>
				</p>
				<p class="olr_type_table_wrap">
					<span class="olr_label"><label for="">'.__( 'Type of Table', PLUGIN_NAME ).'</label></span>
					<input type="text" name="olr_type_table" id="olr_type_table" class="olr_type_table" disabled="disabled" value="" />
				</p>
				<br/>';
				
				if( $options['enable_captcha'] ){
					if( $options['public_key'] != '' ){
						$out .='<script type="text/javascript">
									var RecaptchaOptions = {
										theme : "'.$options['captcha_theme'].'"
									};
								</script>
								<script type="text/javascript"
									src="http://www.google.com/recaptcha/api/challenge?k='.$options['public_key'].'">
								</script>
								<p class="olr_captcha_response_error"></p>
								';
					}
				}
				
			$out .='</div>';
		
		} // if( lockout_table('','','check is lockout') ){

		return $out;
	}

?>