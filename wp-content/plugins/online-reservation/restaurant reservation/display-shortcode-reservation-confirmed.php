<?php

/*
	TABLE OF CONTENTS ( DISPLAY	ON FRONT END )
	==========================================
	1.	CEATING SHORTCODE
*/

/*==============================================
	1.	CEATING SHORTCODE
		1.	GLOBAL VARIABLE
		2.	VARIABLE
		3.	CHECK CONFIRMATION KEY EXIST
		4.	CHECK IF EMAIL IS EXPIRED
		5.	UPDATE BOOKING STATUS
==============================================*/
add_shortcode('reservation_confirmed','olr_restaurant_reservation_confirmed');

function olr_restaurant_reservation_confirmed($args){
		
	if( 	isset( $_GET['confirmation_key'] )
		&&	isset( $_GET['id'] )		 
	){
		//=	1.	GLOBAL VARIABLE
		global $email_confirmation_length;
		
		//=	2.	VARIABLE
		$options 	= get_option( 'resto_all_setting' );
		$today 		= getdate();
		$today_date_time = $today['year'].'-'.$today['mon'].'-'.$today['mday'].' '.$today['hours'].':'.$today['minutes'].':'.$today['seconds'];
		$post_id 	= $_GET['id'];
		$client_data = get_post_meta( $post_id,'olr_custom_column');
		
		//= 3.	CHECK CONFIRMATION KEY AND IDS
		if( $client_data[0]['Confirmation_Key'] == $_GET['confirmation_key'] ){
			
			//= 4.	CHECK IF EMAIL IS EXPIRED
			$making_date 		= strtotime($client_data[0]['date']);
			$today_date_time 	= strtotime($today_date_time);
			
			if( making_date > $today_date_time ){
				echo 'Your Confirmation Link is Expired , Please Do your reservation again';	
				
			}else{
				
				//= 5.	UPDATE BOOKING STATUS
				$my_post = array(
					  'ID'           => $post_id,
					  'post_status' => 'Confirmed'
				  );
			
				// Update the post into the database
				if( wp_update_post( $my_post ) ){
					$message = 'Confirmation Success';
					if( $options['confirmation_success_message'] != '' ){
						$message = $options['confirmation_success_message'];
					}
					$message;
					
				}else{
					$message = 'Confirmation Failed';
					if( $options['confirmation_failed_message'] != '' ){
						$message = $options['confirmation_failed_message'];
					}
					$message;
				}
				
				echo $message;
			
			}
				
		}
		
	}

} // olr_restaurant_reservation_confirmed

?>