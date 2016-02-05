<?php

/*
	TABLE OF CONTENTS
	========================
	1.	ADD CUSTOM FIELD
		1.	ADD METABOX
		2.	SAVE METABOX
*/
	/*========================================
		1.	ADD METABOX
	========================================*/
	add_action( 'add_meta_boxes', 'reservation_custom_field_add' );
	function reservation_custom_field_add()
	{
			add_meta_box( 
				'reservation-custom-field',		// $id 
				'Reservation Information',		// $title 
				'reservation_information', 		// $function_callback
				'olr_restaurant', 				// $edit_Screen ($post_type)
				'normal',						// $context (where edit screen to be shown on page)
				'high'							// $priority 
			);
	}
	
	function reservation_information( $post )
	{
			$meta_key = 'olr_custom_column';
			$values = $column = get_post_meta($post->ID, $meta_key, true);
			
			$options = get_option('resto_all_setting');
			$booking_date	 = isset( $values['Booking_Date'] ) ? esc_attr( $values['Booking_Date'] ) : '';
				$day	= substr($booking_date ,3,2);
				$month 	= substr($booking_date ,0,2);
				$year 	= substr($booking_date ,6,4);
				$day_letter = date('D', strtotime($day .' '. $month .' '. $year));
			$booking_time 	= isset( $values['Booking_Time'] ) ? esc_attr( $values['Booking_Time'] ) : '';
			$type_tables 	= isset( $values['Type_of_Tables'] ) ? esc_attr( $values['Type_of_Tables'] ) : '';
			$table		 	= isset( $values['Tables'] ) ? esc_attr( $values['Tables'] ) : '';
			
			
			$persons 		= isset( $values['Persons'] ) ? esc_attr( $values['Persons'] ) : '';
			$confirmation_key = isset( $values['Confirmation_Key'] ) ? esc_attr( $values['Confirmation_Key'] ) : '';
			
			$phone 		= isset( $values['Phone'] ) ? esc_attr( $values['Phone'] ) : '';
			$email 		= isset( $values['Email'] ) ? esc_attr( $values['Email'] ) : '';
			$message 	= isset( $values['Message'] ) ? esc_attr( $values['Message'] ) : '';
			
			
			/*echo "<pre>";
				print_r( $options );
			echo "</pre>";*/

			wp_nonce_field( 'custom-field-nonce', 'custom-field-id' );
			?>
            <div id="reservation-information-wrap">
            	<label for="booking_date"><?php _e('Booking Date',PLUGIN_NAME) ?></label>
                <input name="booking_date" id="booking_date" type="text" value="<?php echo $booking_date; ?>" />
                <br/>
            	<label for="booking_time"><?php _e('Booking Time',PLUGIN_NAME) ?></label>
                <select name="booking_time" id="booking_time">
					<?php echo olr_output_time_list( $options , $now = '' ,$day_letter,$booking_time) ?>
 				</select>
                <br/>
            
            	<label for="type_tables"><?php _e('Type of Tables',PLUGIN_NAME) ?></label>
            	<select name="type_tables" id="type_tables">
                <?php 
					$many_table_type = explode(',',str_replace(' ','',$options['many_type_of_table']) );
					if( $many_table_type != '' ){
					
						$first_table = '';
						foreach( $many_table_type as $val){
							echo $val; 
							
							$table_type = preg_match('/(.+)(\()([0-9]+)(\))/', $val, $match_table);
							
							if( $first_table == ''){
								$first_table = 	$match_table['1'];	
							}
							
							?>
								<option <?php selected( $type_tables, $match_table['1'] ); ?> value="<?php echo $match_table['1'] ?>"><?php echo $match_table['1'].' ( '.$match_table['3'] .' person )'; ?></option>
							<?php 					
						} // foreach( $many_table_type as $val){
					
					} // if( $many_table_type != '' ){
				?>
				</select>
                <br/>
            	<label for="table"><?php _e('Total Table',PLUGIN_NAME) ?></label>
				<input type="text" name="table" id="table" value="<?php echo $table; ?>" />
                <br/>
                <label for="Person"><?php _e('Person',PLUGIN_NAME) ?></label>
				<input type="text" name="person" id="person" value="<?php echo $persons; ?>" />
                <br/>
                <label for=""><?php _e('Confirmation Key',PLUGIN_NAME) ?></label>
                <input type="hidden" name="confirmation_key" id="confirmation_key" value="<?php echo $confirmation_key; ?>" />
				<input type="text" disabled="disabled" value="<?php echo $confirmation_key; ?>" />
                <br/>
                <label for=""><?php _e('Status',PLUGIN_NAME) ?></label>
                <?php $status = get_post_status ( $post->ID ); ?>
                <select name="status" id="status">
                	<option value="pending" <?php selected( $status, 'pending' ); ?>>Pending</option>
                    <option value="confirmed" <?php selected( $status, 'confirmed' ); ?>>Confirmed</option>
                    <option value="closed" <?php selected( $status, 'closed' ); ?>>Closed</option>
                    <option value="enquiry" <?php selected( $status, 'enquiry' ); ?>>Enquiry</option>
                </select>
                <br/>
               
                <hr />
                <h2>Client Information</h2>
                <label for="phone"><?php _e('Phone',PLUGIN_NAME) ?></label>
				<input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" />
                <br/>
                <label for="email"><?php _e('Email',PLUGIN_NAME) ?></label>
				<input type="text" name="email" id="email" value="<?php echo $email; ?>" />
                <br/>
                <label for="message"><?php _e('Message',PLUGIN_NAME) ?></label>
				<textarea rows="3" cols="25" name="message" id="message"><?php echo $message; ?></textarea>
               
            </div>
			<?php	
	}   
	
	
	/*========================================
		2.	SAVE METABOX
	========================================*/
	add_action( 'save_post', 'reservation_custom_field_save' ); 
	function reservation_custom_field_save( $post_id )
	{
		$meta_key = 'olr_custom_column';
		
		// Bail if we're doing an auto save
		if( 	defined( 'DOING_AUTOSAVE' ) 
			&& 	DOING_AUTOSAVE 
		){ 
			return;
		}
		
		// if our nonce isn't there, or we can't verify it, bail
		if( 	!isset( $_POST['custom-field-id'] ) 
			|| 	!wp_verify_nonce( $_POST['custom-field-id'], 'custom-field-nonce' ) 
		){
			return;
		}
		
		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post' ) ) return;  
		
		// now we can actually save the data    
		$allowed = array(
			'a' => array( // on allow a tags
				'href' => array() // and those anchords can only have href attribute
			)
		);
		
		// Probably a good idea to make sure your data is set
		if( 	isset( $_POST['phone'] ) 
			||	isset( $_POST['email'] ) 
			||	isset( $_POST['type_tables'] ) 
			||	isset( $_POST['table'] ) 
			||	isset( $_POST['person'] ) 
			||	isset( $_POST['booking_date'] ) 
			||	isset( $_POST['booking_time'] ) 
			||	isset( $_POST['confirmation_key'] ) 
			||	isset( $_POST['message'] ) 
			|| 	isset( $_POST['status'] )
		){
			
			$meta = array(
						'Phone' 			=> wp_strip_all_tags($_POST['phone']),
						'Email' 			=> wp_strip_all_tags($_POST['email']),
						'Type_of_Tables'	=> $_POST['type_tables'],
						'Tables' 			=> $_POST['table'],
						'Persons' 			=> wp_strip_all_tags($_POST['person']),
						'Booking_Date' 		=> $_POST['booking_date'],
						'Booking_Time' 		=> $_POST['booking_time'],
						'Confirmation_Key' 	=> $_POST['confirmation_key'],
						'Message'			=> wp_strip_all_tags($_POST['message'])
						);
		
			update_post_meta($post_id, $meta_key, $meta);
			
			
			
		} // if( 	isset( $_POST['phone'] ) 
		
		if( isset( $_POST['status'] ) ){
			/*
			Note :	If you are calling a function such as wp_update_post that includes the save_post hook , 
					your hooked function will create an infinite loop. To avoid this, 
					unhook your function before calling the function you need, then re-hook it afterward.
			*/
			
			// unhook this function so it doesn't loop infinitely
			remove_action( 'save_post', 'reservation_custom_field_save' );
			
			$post_data = array(
				'ID'           	=> $post_id,
				'post_status' 	=> $_POST['status']
			);
			wp_update_post( $post_data );
				
			// re-hook this function
			add_action( 'save_post', 'reservation_custom_field_save' );
		} // if( isset( $_POST['status'] ) ){
			
		
	}

?>