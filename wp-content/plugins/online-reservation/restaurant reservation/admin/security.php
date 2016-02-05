<?php
/*
	TABLE OF CONTENTS ( SECURITY TAB )
	=======================================
	1.	RESERVATION TIME NEEDED
	2.	MAXIMUM FIND TABLE ATTEMPT
	3.	MAXIMUM SEND ENQUIRY ATTEMPT
	
	4.	EMAIL CONFIRMATION EXPIRED
	5.	LOCK OUT RESERVATION
*/
?>
	<h2 class="head-parent no-margin-top">Protect Your System From Fake Bookings </h2>
    <div class="head-parent-content head-parent-security">
        <p class="field">
           	This system is built with many considerations associated with fake bookings and online fraud that always happening, 
            Then, Below setting will give you more Safety against fake bookings and online fraud.
        </p>
        
        <?php //= 1.	RESERVATION TIME NEEDED 
			$max_time = $options['reservation_time_needed'] == '' ? '3_min' :  $options['reservation_time_needed'];
		?>
       	<p class="field">
			<label for="table_size"><?php _e('Reservation Time Needed',PLUGIN_NAME) ?></label>
            <select name="resto_all_setting[reservation_time_needed]" id="reservation_time_needed">
                <option value="1_min" <?php selected( $max_time, '1_min' ); ?>><?php _e('1 minute',PLUGIN_NAME); ?></option>
                <option value="2_min" <?php selected( $max_time, '2_min' ); ?>><?php _e('2 minutes',PLUGIN_NAME); ?></option>
                <option value="3_min" <?php selected( $max_time, '3_min' ); ?>><?php _e('3 minutes',PLUGIN_NAME); ?></option>
                <option value="4_min" <?php selected( $max_time, '4_min' ); ?>><?php _e('4 minutes',PLUGIN_NAME); ?></option>
                <option value="5_min" <?php selected( $max_time, '5_min' ); ?>><?php _e('5 minutes',PLUGIN_NAME); ?></option>
                <option value="6_min" <?php selected( $max_time, '6_min' ); ?>><?php _e('6 minutes',PLUGIN_NAME); ?></option>
                <option value="7_min" <?php selected( $max_time, '7_min' ); ?>><?php _e('7 minutes',PLUGIN_NAME); ?></option>
                <option value="8_min" <?php selected( $max_time, '8_min' ); ?>><?php _e('8 minutes',PLUGIN_NAME); ?></option>
                <option value="9_min" <?php selected( $max_time, '9_min' ); ?>><?php _e('9 minutes',PLUGIN_NAME); ?></option>
                <option value="10_min" <?php selected( $max_time, '10_min' ); ?>><?php _e('10 minutes',PLUGIN_NAME); ?></option>
                <option value="20_min" <?php selected( $max_time, '20_min' ); ?>><?php _e('20 minutes',PLUGIN_NAME); ?></option>
                <option value="30_min" <?php selected( $max_time, '30_min' ); ?>><?php _e('30 minutes',PLUGIN_NAME); ?></option>
            </select>
            <br />
            <span>
            	<strong>Note : </strong> Every time your client find table on this system and Let's say if the table is found , 
                Then those table will be held for him and no one can reserve it .
                <br />
                So, You need to set the length of the time needed for your client to complete the reservation process.
                <br />
                If he pass those time, Then, the reservation process will be expired, So, he need to repeat the process.   
            </span>
        </p>
        
        <?php //= 2.	MAXIMUM FIND TABLE ATTEMPT 
			$max_find = $options['max_find_table'] == '' ? '5' :  $options['max_find_table']; 
		?>
        <p class="field">
			<label for="table_size"><?php _e('Maximum Find Table',PLUGIN_NAME) ?></label>
            <input type="text" id="max_find_table" name="resto_all_setting[max_find_table]" value="<?php echo $max_find; ?>" />
            <br />
            <span>
            	<strong>Note : </strong> Set How many times your client can make find table process, before this system is being lockout 
            </span>
        </p>
        
        <?php //= 3.	MAXIMUM SEND ENQUIRY ATTEMPT 
			$max_enquiry = $options['max_send_enquiry'] == '' ? '5' :  $options['max_send_enquiry']; 
		?>
        <p class="field">
        	<label for="table_size"><?php _e('Maximum Send Enquiry',PLUGIN_NAME) ?></label>
            <input type="text" id="max_send_enquiry" name="resto_all_setting[max_send_enquiry]" value="<?php echo $max_enquiry; ?>" />
            <br />
            <span>
            	<strong>Note : </strong> Set How many times your client can send enquiry to your restaurant ( especially to your email ), 
                before this system is being lockout. 
            </span>
       	</p>

        <?php //= 4.	EMAIL CONFIRMATION EXPIRED TIME 
			$email_conf_length = $options['email_confirmation_length'] == '' ? '3_min' :  $options['email_confirmation_length'];
		?>
        <p class="field">
     		<label for="table_size"><?php _e('EMAIL Confirmation Length ',PLUGIN_NAME) ?></label>
            <select name="resto_all_setting[email_confirmation_length]" id="email_confirmation_length">
                <option value="1_min" <?php selected( $email_conf_length, '1_min' ); ?>><?php _e('1 minute',PLUGIN_NAME); ?></option>
                <option value="2_min" <?php selected( $email_conf_length, '2_min' ); ?>><?php _e('2 minutes',PLUGIN_NAME); ?></option>
                <option value="3_min" <?php selected( $email_conf_length, '3_min' ); ?>><?php _e('3 minutes',PLUGIN_NAME); ?></option>
                <option value="4_min" <?php selected( $email_conf_length, '4_min' ); ?>><?php _e('4 minutes',PLUGIN_NAME); ?></option>
                <option value="5_min" <?php selected( $email_conf_length, '5_min' ); ?>><?php _e('5 minutes',PLUGIN_NAME); ?></option>
                <option value="6_min" <?php selected( $email_conf_length, '6_min' ); ?>><?php _e('6 minutes',PLUGIN_NAME); ?></option>
                <option value="7_min" <?php selected( $email_conf_length, '7_min' ); ?>><?php _e('7 minutes',PLUGIN_NAME); ?></option>
                <option value="8_min" <?php selected( $email_conf_length, '8_min' ); ?>><?php _e('8 minutes',PLUGIN_NAME); ?></option>
                <option value="9_min" <?php selected( $email_conf_length, '9_min' ); ?>><?php _e('9 minutes',PLUGIN_NAME); ?></option>
                <option value="10_min" <?php selected( $email_conf_length, '10_min' ); ?>><?php _e('10 minutes',PLUGIN_NAME); ?></option>
                <option value="20_min" <?php selected( $email_conf_length, '20_min' ); ?>><?php _e('20 minutes',PLUGIN_NAME); ?></option>
                <option value="30_min" <?php selected( $email_conf_length, '30_min' ); ?>><?php _e('30 minutes',PLUGIN_NAME); ?></option>
            </select>
            <br />
            <span>
            	<strong>Note : </strong> Every time your client complete the reservation process then confirmation email will be sent to him 
                and the choosen table will be held for him. 
                <br />
                So, You need to set the length of the time needed for your client to confirm his email .
                <br />
                If he pass those time, Then, the confirmation email will be expired, So, he need to repeat the process.   
            </span>  
        </p>
        
        
       	<?php //= 5.	LOCK OUT RESERVATION 
			$lock_out = $options['lock_our_reservation'] == '' ? '10_hour' :  $options['lock_our_reservation'];
		?>
        <p class="field">
            <label for="table_size"><?php _e('Lock Out Reservation',PLUGIN_NAME) ?></label>
            <select name="resto_all_setting[lock_our_reservation]" id="lock_our_reservation">
                <option value="1_hour" <?php selected( $lock_out, '1_hour' ); ?>><?php _e('1 hour',PLUGIN_NAME); ?></option>
                <option value="2_hour" <?php selected( $lock_out, '2_hour' ); ?>><?php _e('2 hours',PLUGIN_NAME); ?></option>
                <option value="3_hour" <?php selected( $lock_out, '3_hour' ); ?>><?php _e('3 hours',PLUGIN_NAME); ?></option>
                <option value="4_hour" <?php selected( $lock_out, '4_hour' ); ?>><?php _e('4 hours',PLUGIN_NAME); ?></option>
                <option value="5_hour" <?php selected( $lock_out, '5_hour' ); ?>><?php _e('5 hours',PLUGIN_NAME); ?></option>
                <option value="6_hour" <?php selected( $lock_out, '6_hour' ); ?>><?php _e('6 hours',PLUGIN_NAME); ?></option>
                <option value="7_hour" <?php selected( $lock_out, '7_hour' ); ?>><?php _e('7 hours',PLUGIN_NAME); ?></option>
                <option value="8_hour" <?php selected( $lock_out, '8_hour' ); ?>><?php _e('8 hours',PLUGIN_NAME); ?></option>
                <option value="9_hour" <?php selected( $lock_out, '9_hour' ); ?>><?php _e('9 hours',PLUGIN_NAME); ?></option>
                <option value="10_hour" <?php selected( $lock_out, '10_hour' ); ?>><?php _e('10 hours',PLUGIN_NAME); ?></option>
                <option value="13_hour" <?php selected( $lock_out, '13_hour' ); ?>><?php _e('13 hours',PLUGIN_NAME); ?></option>
                <option value="16_hour" <?php selected( $lock_out, '16_hour' ); ?>><?php _e('16 hours',PLUGIN_NAME); ?></option>
                <option value="19_hour" <?php selected( $lock_out, '19_hour' ); ?>><?php _e('19 hours',PLUGIN_NAME); ?></option>
                <option value="22_hour" <?php selected( $lock_out, '22_hour' ); ?>><?php _e('22 hours',PLUGIN_NAME); ?></option>
                <option value="24_hour" <?php selected( $lock_out, '24_hour' ); ?>><?php _e('1 day',PLUGIN_NAME); ?></option>
            </select>
		
        <br />
		<span>
      		<strong>Note : </strong> Set How long your restaurant booking system will be lockout for any visitors that do any above actions 
            which are mostly indentified as fake bookings.  
     	</span>  
        </p>
  	</div>      