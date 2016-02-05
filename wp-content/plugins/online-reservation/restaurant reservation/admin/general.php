<?php
/*
	TABLE OF CONTENTS ( GENERAL TAB )
	=======================================
	1.	RESERVATION PAGE
	2.	THANK YOU PAGE
	3.	SUCCESS MESAGE 
	4.	FAILED MESSAGE
	5.	HELP TO PROMOTE PLUGIN
	6.	RESERVATION LENGTH
	7.	PROVIDE OTHER TIME ( IF CHOOSEN TIME FROM CUSTOMER IS FULLY BOOKED )
	8.	OTHER DIFFERENT TIMES
	9.	TIMEZONE
*/
?>
	<?php //= 1.	RESERVATION PAGE  ?>
	<label for="reservation_page" class="label"><?php _e('Reservation Page',PLUGIN_NAME); ?></label>    
	<select name="resto_all_setting[reservation_page]" id="reservation_page">
      	<?php
			$args = array(
				'sort_order' => 'ASC',
				'sort_column' => 'post_title',
				'hierarchical' => 1,
				'exclude' => '',
				'include' => '',
				'meta_key' => '',
				'meta_value' => '',
				'authors' => '',
				'child_of' => 0,
				'parent' => -1,
				'exclude_tree' => '',
				'number' => '',
				'offset' => 0,
				'post_type' => 'page',
				'post_status' => 'publish'
			); 
			$pages = get_pages($args);
			
			if( $options['reservation_page'] == '' ){
				$options['reservation_page'] = get_option( 'resto_reservation_page_id');
			}
			
			foreach( $pages as $page ):
		?>
        	<option value="<?php echo $page->ID ?>" <?php selected( $options['reservation_page'], $page->ID ); ?>><?php _e($page->post_title,PLUGIN_NAME); ?></option>
        <?php endforeach; ?>  	
	</select>
	<p class="float-none"></p>
    
    <?php //= 2.	THANK YOU PAGE  ?>
	<label for="thank_you_page" class="label"><?php _e('Thank You Page',PLUGIN_NAME); ?></label>    
	<select name="resto_all_setting[thank_you_page]" id="thank_you_page">
      	<?php
			if( $options['thank_you_page'] == '' ){
				$options['thank_you_page'] = get_option( 'resto_thank_you_page_id');
			}
			foreach( $pages as $page ):
		?>
        	<option value="<?php echo $page->ID ?>" <?php selected( $options['thank_you_page'], $page->ID ); ?>><?php _e($page->post_title,PLUGIN_NAME); ?></option>
        <?php endforeach; ?>  	
	</select>
	<p class="float-none"></p>



	<?php //= 1.	SUCCESS MESAGE  ?>
	<label for="success_message" class="label"><?php _e('Success Message',PLUGIN_NAME); ?></label>    
	<textarea class="email-medium-field" id="success_message" name="resto_all_setting[success_message]" rows="3" cols="25"><?php echo $options['success_message'] ?></textarea>
  	
    <?php //= 2.	FAILED MESSAGE  ?>
    <label for="failed_message" class="setting-title"><?php _e('Failed Message',PLUGIN_NAME); ?></label> 
 	<textarea class="email-medium-field" id="failed_message" name="resto_all_setting[failed_message]" rows="3" cols="25"><?php echo $options['failed_message'] ?></textarea>
   	<p class="float-none"></p>
    
    <?php //= 3.	HELP TO PROMOTE PLUGIN  ?> 
    <label for="help_promote_plugin"><?php _e('Help to promote this plugin',PLUGIN_NAME); ?><br /><span class="sub-text"><?php _e('( By Display Link Below Reservation Form )',PLUGIN_NAME); ?></span></label>                        
 	<input type="checkbox" id="help_promote_plugin" name="resto_all_setting[help_promote_plugin]" value="1" <?php checked('1', $options['help_promote_plugin']) ?> />
	<p class="float-none"></p>
    <br/>
    
    <?php //= 4.	RESERVATION LENGTH  ?>   
    <label for="reservation_length"><?php _e('Reservation Lengths',PLUGIN_NAME); ?><br /><span class="sub-text"><?php _e(' ( in minutes )',PLUGIN_NAME); ?></label>                      
	<select name="resto_all_setting[reservation_length]" id="reservation_length">
    	<?php for( $min=20; $min<900; $min=$min+20 ): ?>
        	<option value="<?php echo $min; ?>" <?php selected( $options['reservation_length'], $min ); ?>><?php echo $min; ?></option>
   		<?php endfor; ?>
	</select>
    <p class="float-none"></p>
    <br/>
    
    <?php //= 5.	PROVIDE OTHER TIME  ?>   
    <label for="provide_other_time"><?php _e('Provide Other time',PLUGIN_NAME); ?>
    	<br /><span class="sub-text"><?php _e(' ( if table on choosen time is fully booked ) ',PLUGIN_NAME); ?>
   	</label>                      
	<input type="checkbox" id="provide_other_time" name="resto_all_setting[provide_other_time]" value="1" <?php checked('1', $options['provide_other_time']) ?> />
   	<p class="float-none"></p>
    <br/>
    
    <?php //= 6.	OTHER DIFFERENT TIMES ?>   
    <label for="other_times" ><?php _e('How many other different times to be displayed',PLUGIN_NAME); ?></label>
   	<select name="resto_all_setting[other_times]" id="other_times">
      	<option value="5" <?php selected( $options['other_times'], '5' ); ?>><?php _e('5',PLUGIN_NAME); ?></option>
        <option value="6" <?php selected( $options['other_times'], '6' ); ?>><?php _e('6',PLUGIN_NAME); ?></option>
        <option value="7" <?php selected( $options['other_times'], '7' ); ?>><?php _e('7',PLUGIN_NAME); ?></option>
        <option value="8" <?php selected( $options['other_times'], '8' ); ?>><?php _e('8',PLUGIN_NAME); ?></option>
        <option value="9" <?php selected( $options['other_times'], '9' ); ?>><?php _e('9',PLUGIN_NAME); ?></option>
        <option value="10" <?php selected( $options['other_times'], '10' ); ?>><?php _e('10',PLUGIN_NAME); ?></option>    	
	</select>
    <p class="float-none"></p>
    <br/>
    
    <?php //= 7.	TIMEZONE ?>   
    <label for="timezone" ><?php _e('Timezone',PLUGIN_NAME); ?></label>
    <select name="resto_all_setting[timezone]" id="timezone">
		<option value="Pacific/Midway" <?php selected( $options['timezone'], 'Pacific/Midway' ); ?>>GMT-11:00</option>
        <option value="Pacific/Honolulu" <?php selected( $options['timezone'], 'Pacific/Midway' ); ?>>GMT-10:00</option>
        <option value="Pacific/Marquesas" <?php selected( $options['timezone'], 'Pacific/Marquesas' ); ?>>GMT-09:30</option>
  		<option value="America/Anchorage" <?php selected( $options['timezone'], 'America/Anchorage' ); ?>>GMT-09:00</option>
        <option value="America/Los_Angeles" <?php selected( $options['timezone'], 'America/Los_Angeles' ); ?>>GMT-08:00</option>
        <option value="America/Denver" <?php selected( $options['timezone'], 'America/Denver' ); ?>>GMT-07:00</option>
        <option value="America/Chicago" <?php selected( $options['timezone'], 'America/Chicago' ); ?>>GMT-06:00</option>
        <option value="America/Cayman" <?php selected( $options['timezone'], 'America/Cayman' ); ?>>GMT-05:00</option>
        <option value="America/Caracas" <?php selected( $options['timezone'], 'America/Caracas' ); ?>>GMT-04:30</option>
        <option value="America/Antigua" <?php selected( $options['timezone'], 'America/Antigua' ); ?>>GMT-04:00</option>
        <option value="America/St_Johns" <?php selected( $options['timezone'], 'America/St_Johns' ); ?>>GMT-03:30</option>
        <option value="America/Belem" <?php selected( $options['timezone'], 'America/Belem' ); ?>>GMT-03:00</option>
        <option value="America/Noronha" <?php selected( $options['timezone'], 'America/Noronha' ); ?>>GMT-02:00</option>
        <option value="Atlantic/Azores" <?php selected( $options['timezone'], 'Atlantic/Azores' ); ?>>GMT-01:00</option>
        <option value="Africa/Accra" <?php selected( $options['timezone'], 'Africa/Accra' ); ?>>GMT</option>
        <option value="Europe/Amsterdam" <?php selected( $options['timezone'], 'Europe/Amsterdam' ); ?>>GMT+01:00</option>
        <option value="Africa/Cairo" <?php selected( $options['timezone'], 'Africa/Cairo' ); ?>>GMT+02:00</option>
        <option value="Africa/Kampala" <?php selected( $options['timezone'], 'Africa/Kampala' ); ?>>GMT+03:00</option>
        <option value="Asia/Tehran" <?php selected( $options['timezone'], 'Asia/Tehran' ); ?>>GMT+03:30</option>
        <option value="Asia/Dubai" <?php selected( $options['timezone'], 'Asia/Dubai' ); ?>>GMT+04:00</option>
        <option value="Asia/Karachi" <?php selected( $options['timezone'], 'Asia/Karachi' ); ?>>GMT+05:00</option>
        <option value="Asia/Almaty" <?php selected( $options['timezone'], 'Asia/Almaty' ); ?>>GMT+06:00</option>
        <option value="Asia/Rangoon" <?php selected( $options['timezone'], 'Asia/Rangoon' ); ?>>GMT+06:30</option>
        <option value="Asia/Bangkok" <?php selected( $options['timezone'], 'Asia/Bangkok' ); ?>>GMT+07:00</option>
        <option value="Asia/Hong_Kong" <?php selected( $options['timezone'], 'Asia/Hong_Kong' ); ?>>GMT+08:00</option>
        <option value="Asia/Seoul" <?php selected( $options['timezone'], 'Asia/Seoul' ); ?>>GMT+09:00</option>
        <option value="Australia/Adelaide" <?php selected( $options['timezone'], 'Australia/Adelaide' ); ?>>GMT+09:30</option>
        <option value="Australia/Brisbane" <?php selected( $options['timezone'], 'Australia/Brisbane' ); ?>>GMT+10:00</option>
        <option value="Pacific/Kosrae" <?php selected( $options['timezone'], 'Pacific/Kosrae' ); ?>>GMT+11:00</option>
        <option value="Pacific/Norfolk" <?php selected( $options['timezone'], 'Pacific/Norfolk' ); ?>>GMT+11:30</option>
        <option value="Pacific/Fiji" <?php selected( $options['timezone'], 'Pacific/Fiji' ); ?>>GMT+12:00</option>
        <option value="Pacific/Enderbury" <?php selected( $options['timezone'], 'Pacific/Enderbury' ); ?>>GMT+13:00</option>
    	<option value="Pacific/Kiritimati" <?php selected( $options['timezone'], 'Pacific/Kiritimati' ); ?>>GMT+14:00</option>
   </select>
   <p class="float-none"></p>	
   <br/>	