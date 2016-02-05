<?php

/*
	TABLE OF CONTENTS
	========================
	1.	ADDING FIELD IN QUICK EDIT MENU
	2.	SAVE FIELD
*/
		
		/*======================================================
			1.	ADDING FIELD IN QUICK EDIT MENU
		======================================================*/
		add_action('quick_edit_custom_box',  'olr_adding_field_on_quick_edit', 10, 2);
		 
		function olr_adding_field_on_quick_edit($column_name, $post_type) {
			
			?>
            <?php if( $column_name == 'Booking Date'):?>
            	<?php 
					global $post;
					$meta_key = 'olr_custom_column';
					$values = $column = get_post_meta($post->ID, $meta_key, true);
					$options = get_option('resto_all_setting');
					$booking_date	 = isset( $values['Booking Date'] ) ? esc_attr( $values['Booking Date'] ) : '';
						$day	= substr($booking_date ,3,2);
						$month 	= substr($booking_date ,0,2);
						$year 	= substr($booking_date ,6,4);
						$day_letter = date('D', strtotime($day .' '. $month .' '. $year));
					$booking_time 	= isset( $values['Booking Time'] ) ? esc_attr( $values['Booking Time'] ) : '';
					$type_tables 	= isset( $values['Type of Tables'] ) ? esc_attr( $values['Type of Tables'] ) : '';
					$table		 	= isset( $values['Tables'] ) ? esc_attr( $values['Tables'] ) : '';
					
					
					$persons 		= isset( $values['Persons'] ) ? esc_attr( $values['Persons'] ) : '';
					$confirmation_key = isset( $values['Confirmation Key'] ) ? esc_attr( $values['Confirmation Key'] ) : '';
					
					$phone 		= isset( $values['Phone'] ) ? esc_attr( $values['Phone'] ) : '';
					$email 		= isset( $values['Email'] ) ? esc_attr( $values['Email'] ) : '';
					$message 	= isset( $values['Message'] ) ? esc_attr( $values['Message'] ) : '';
				
				?>
            
            	<div style="clear:both;"></div>
       			<div id="reservation-information-wrap">
                	
                	<?php wp_nonce_field("quick_edit_nonce","quick_edit_nonce_id"); ?>
                    <input name="post_id" id="post_id" type="hidden" value="<?php echo $post->ID; ?>" />
                    <br/>
                    <label for="booking_date"><?php _e('Booking Date',PLUGIN_NAME) ?></label>
                    <input name="booking_dates" class="booking_dates" id="booking_dates" type="text" value="<?php echo $booking_date; ?>" />
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
                                //echo $val; 
                                
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
                    <select name="post_status" id="post_status">
                        <option value="pending" <?php selected( $status, 'pending' ); ?>>Pending</option>
                        <option value="confirmed" <?php selected( $status, 'confirmed' ); ?>>Confirmed</option>
                        <option value="closed" <?php selected( $status, 'closed' ); ?>>Closed</option>
                        <option value="enquiry" <?php selected( $status, 'enquiry' ); ?>>Enquiry</option>
                    </select>
                    <br/>
                   
                    <hr />
                    <h2>Client Information</h2>
                    <label for="phone"><?php _e('Name',PLUGIN_NAME) ?></label>
                    <span><?php echo get_the_title( $post->ID );?></span>
                    <br/>
                    <label for="phone"><?php _e('Phone',PLUGIN_NAME) ?></label>
                    <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" />
                    <br/>
                    <label for="email"><?php _e('Email',PLUGIN_NAME) ?></label>
                    <input type="text" name="email" id="email" value="<?php echo $email; ?>" />
                    <br/>
                    <label for="message"><?php _e('Message',PLUGIN_NAME) ?></label>
                    <textarea rows="3" cols="25" name="message" id="message"><?php echo $message; ?></textarea>
                    <br/>
                    <br/>
             		<input type="button" id="olr-quick-menu-update-button" value="Update" />
                    <p class="quick-edit_response"></p>
                </div> <?php // <div id="reservation-information-wrap"> ?>
           <?php endif; ?>
            
            
			<?php
		}
		
		/*======================================================
			2.	SAVE FIELD
		======================================================*/
		add_action ('admin_footer-edit.php', 'olr_quick_edit') ;
		function olr_quick_edit (){
			
				$plugin_path = str_replace("\\","/",OLR_PATH);  
				
		 	?>
			   	<script type='text/javascript'>
				(function($) {
					
					check_date_url 	= '<?php echo OLR_FOLDER; ?>' + 'restaurant reservation/ajax-request/check-date.php';
					plugin_path 	= '<?php echo $plugin_path ?>';  
					plugin_options 	= <?php echo json_encode(get_option('resto_all_setting')); ?>;
					admin_url 		= '<?php echo admin_url( 'admin-ajax.php' ); ?>';
					
					function olr_quick_edit_init(){
						
						
						
						var _edit = inlineEditPost.edit ;
						
						//= "call" the original WP edit function
						//= and then we overwrite the function with our own code
						inlineEditPost.edit = function (id) {
							
							$('.quick-edit_response').css('display','none');
							
							var args = [].slice.call (arguments) ;
							_edit.apply (this, args) ;
							
							// get the post ID
							if (typeof (id) == 'object') {
								id = this.getId (id) ;
							}
							
							if (this.type == 'post' || this.type == 'page') {
								if ( id > 0 ) {
									
									var editRow = $('#edit-' + id) ;
									var postRow = $('#post-' + id) ;
									
									var postData= {};
										postData['action'] 			= 'refresh_quick_edit';
										postData['post_id'] 		= id ;
										
									//alert( id );
									
									$.post( admin_url, postData ).success( function(data) {
										data = data.replace('[','');
										data = data.replace(']','');
										var obj = $.parseJSON( data );
										$(':input[name="post_id"]', editRow).val( id );
										$(':input[name="booking_dates"]', editRow).val( obj.Booking_Date );
										$(':input[name="booking_time"]', editRow).val( obj.Booking_Time );
										$(':input[name="type_tables"]', editRow).val( obj.Type_of_Tables );
										$(':input[name="table"]', editRow).val( obj.Tables );
										$(':input[name="person"]', editRow).val( obj.Persons );
										$(':input[name="phone"]', editRow).val( obj.Phone);
										$(':input[name="email"]', editRow).val( obj.Email );
										$(':input[name="post_status"]', editRow).val( obj.status );
										$(':input[name="message"]', editRow).val( obj.Message );
										
									});	
									
									
									
									
									var all_day = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
									var day_off = new Array();	
									for( var day=0;day<7;day++ ){
										
										if( $.trim( plugin_options['day_off_' + all_day[day]] ) != '' ){
											day_off[day] = day - 0;
										}else{
											
											
											day_off[day] = 9;
										}
									}
									$(':input[name="booking_dates"]', editRow).datepicker({
																							minDate: 0,
																							beforeShowDay: function(date) {
																								var day = date.getDay();
																								
																								return [(		day != day_off[0] 
																											&& 	day != day_off[1] 
																											&& 	day != day_off[2]  
																											&& 	day != day_off[3] 
																											&& 	day != day_off[4] 
																											&& 	day != day_off[5] 
																											&& 	day != day_off[6] 
																										)];
																							}
																						  });
									//$(':input[name="booking_dates"]', editRow).datepicker();
								}
							
							} // if (this.type == 'post' || this.type == 'page') {
						
						} ;
				
						return ;
						 
					} // function My_QuickEdit(){
						
					olr_quick_edit_init();	
					
					$('#olr-quick-menu-update-button').click(function(event){
				
						me = $(this);
						parent_id = $(this).parents('tr').attr('id').replace('edit-','');
						prev_parent = $('#the-list').find('#post-'+parent_id);
						
						$('.quick-edit_response').css('display','none');
						event.preventDefault();
						var postData= {};
							postData['action'] 			= 'update_quick_edit';
							postData['post_id'] 		= $('#post_id').val();
							postData['_wpnonce'] 		= $('#quick_edit_nonce_id').val();
							postData['booking_date'] 	= $('#booking_dates').val();
							postData['booking_time'] 	= $('#booking_time').val();
							postData['type_tables'] 	= $('#type_tables').val();
							postData['table'] 			= $('#table').val();
							postData['person'] 			= $('#person').val();
							postData['confirmation_key'] 	= $('#confirmation_key').val();
							postData['status'] 			= $('#post_status').val();
							postData['phone'] 			= $('#phone').val();
							postData['email'] 			= $('#email').val();
							postData['message'] 		= $('#message').val();
							
							
							$.post( admin_url, postData ).success( function(data) {
								//alert( data );
								if( data == 'success' ){
									
									prev_parent.find('.Booking').text( postData['booking_date']+', '+postData['booking_time'] );
									prev_parent.find('.Phone').text( postData['phone']);
									prev_parent.find('.Email').text( postData['email'] 	);
									prev_parent.find('.Tables').text( postData['table'] + ' ( ' + postData['type_tables'] + ' )' );
									prev_parent.find('.Persons').text( postData['person'] );
									prev_parent.find('.status').html( '<span class="'+postData['status']+'">'+postData['status']+'</span>' );
									$('.quick-edit_response').css('display','block');
									$('.quick-edit_response').text(data );
									
								}
								
							});	
					});
					
					
					/*==========================================================
						4.	BOOKING DATE CHANGE
					==========================================================*/
					$(".booking_dates").change(function(){
						
						var postData= {};
							postData['date'] 		= $(".booking_dates").val();
							postData['plugin_path'] = plugin_path;
							postData['command'] 	= 'show time';
							postData['options'] 	= plugin_options;	
						
						$.post(check_date_url, postData, function(data) {
							$("#booking_time").html(data);	
						});
						
					}); // $("#olr_date").change(function(){
						
				})(jQuery);
			 	</script>
			<?php
			
			return ;
		} // function quick_edit (){
		
?>