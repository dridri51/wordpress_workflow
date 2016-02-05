<?php
	
	/*
		TABLE OF CONTENTS
		========================
		1.	RESTO UPDATE TABLE MEMBERS ( MANY ) AJAX SUBMIT		
		2.	RESTO QUICK EDIT UPDATE
		3.	RESTO REFRESH DATA
	*/
	
	
	/*#################################################################
		1.	RESTO UPDATE TABLE MEMBERS ( MANY ) AJAX SUBMIT	
	#################################################################*/	
	add_action( 'wp_ajax_nopriv_update-table-member', 'olr_update_table_members_ajax' );
	add_action( 'wp_ajax_update-table-member', 'olr_update_table_members_ajax' );
		 
	function olr_update_table_members_ajax(){
		
		if( $_POST['many_type_of_table'] != '' ){
            $many_table_type = explode(',',str_replace(' ','',$_POST['many_type_of_table']) );
            global $many_table_id;
            if( $many_table_type != '' ){
                foreach( $many_table_type as $val){
                    $table_type = preg_match('/(.+)(\()([0-9]+)(\))/', $val, $match_table);
                     
					 if( count($match_table) > 0 ){
                        $id = $match_table['1'] . '_table'; 
                        ?>
                        <label for="early_bookings" ><?php echo __('Total',PLUGIN_NAME) . ' ' . $match_table['1'] . ' ' .__('table',PLUGIN_NAME);  ?></label>
                        <input type="text" id="<?php echo $match_table['1'] . '_table'?>" name="resto_all_setting[<?php echo $match_table['1'] . __('_table',PLUGIN_NAME); ?>]" value="<?php echo $options[$id] ?>" />
                        
                        <p class="float-none"></p>
                        <?php 	               
                    }
                                    
                } // foreach( $many_table_type as $val){
                                
            } // if( $many_table_type != '' ){
                            
        } // if( $options['many_type_of_table'] != '' ){
			
		exit();	
	}

	/*#################################################################
		2.	RESTO QUICK EDIT UPDATE
			1.	FORM VERIFICATION
			2.	SAVE FIELD
	#################################################################*/	
	add_action( 'wp_ajax_nopriv_update_quick_edit', 'olr_update_quick_edit' );
	add_action( 'wp_ajax_update_quick_edit', 'olr_update_quick_edit' );
		 
	function olr_update_quick_edit() {
		
		/*= 1.	FORM VERIFICATION */
		if( 	!isset( $_POST['_wpnonce'] ) 
			|| 	!wp_verify_nonce( $_POST['_wpnonce'], 'quick_edit_nonce' ) 
		){
			echo 'Sorry, your form is not valid.';
			exit;
		}
		
		$meta_key = 'olr_custom_column';
		
		/*= 2.	SAVE FIELD */
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
		
		update_post_meta($_POST['post_id'], $meta_key, $meta);
		
		if( isset( $_POST['status'] ) ){
			/*
			Note :	If you are calling a function such as wp_update_post that includes the save_post hook , 
					your hooked function will create an infinite loop. To avoid this, 
					unhook your function before calling the function you need, then re-hook it afterward.
			*/
			
			$post_data = array(
				'ID'           	=> $_POST['post_id'],
				'post_status' 	=> $_POST['status']
			);
			$success = wp_update_post( $post_data );

		} // if( isset( $_POST['status'] ) ){
		
		if( $success ){
			echo 'success';
		}else{
			echo 'failed';
		}
		exit();
	
	} // function olr_update_quick_edit() {
		
		
	/*#################################################################
		3.	RESTO REFRESH DATA
	#################################################################*/		
	add_action( 'wp_ajax_nopriv_refresh_quick_edit', 'olr_refresh_quick_edit' );
	add_action( 'wp_ajax_refresh_quick_edit', 'olr_refresh_quick_edit' );
		 
	function olr_refresh_quick_edit() {
		$meta_key = 'olr_custom_column';
		
		$string = '';
		$count = 1;
		$data = get_post_meta($_POST['post_id'], $meta_key);
		$data[0]['status'] = get_post_status ( $_POST['post_id'] );
		echo json_encode($data);
		exit();
	}
		
	
?>