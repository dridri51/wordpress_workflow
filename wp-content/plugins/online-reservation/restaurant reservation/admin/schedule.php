<?php
/*
	TABLE OF CONTENTS ( SCHEDULE TAB )
	=======================================
	1.	OPEN TIME MONDAY
	2.	CLOSE TIME MONDAY
	3.	OPEN TIME TUESDAY
	4.	CLOSE TIME TUESDAY
	5.	OPEN TIME WEDNESDAY
	6.	CLOSE TIME WEDNESDAY
	7.	OPEN TIME THURSDAY
	8.	CLOSE TIME THURSDAY
	9.	OPEN TIME FRIDAY
	10.	CLOSE TIME FRIDAY
	11.	OPEN TIME SATURDAY
	12.	CLOSE TIME SATURDAY
	13.	OPEN TIME SUNDAY
	14.	CLOSE TIME SUNDAY
	15.	TIME INTERVAL
	16.	EARLY BOOKINGS
	17.	LATE BOOKINGS
*/
	$every_day = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
?>	
	
	<?php //= 1.	OPEN TIME MONDAY  ?>
    <h2 class="dashicons-before dashicons-clock olr-heading-title no-margin-top head-parent">
		<?php _e('Working Days',PLUGIN_NAME); ?>
    	<i class="fa fa-caret-down"></i>
    	<i class="fa fa-caret-up"></i>    
  	</h2>
    <div class="open-time-wrap head-parent-content">
		<?php 
            foreach( $every_day as $day ){ 
                $option_open = $options['open_time_' . $day];
                if( $option_open == '' ){
                    $option_open = '08:00';
                }
                $option_close = $options['close_time_' . $day];
                if( $option_close == '' ){
                    $option_close = '22:00';
                }
                
        ?>
        <?php if ( $day == 'monday' ) { ?> 
        <h3 class="day-title"><?php _e('Monday',PLUGIN_NAME); ?></h3>
        <?php } ?> 
        <?php if ( $day == 'tuesday' ) { ?> 
        <h3 class="day-title"><?php _e('Tuesday',PLUGIN_NAME); ?></h3>
        <?php } ?> 
        <?php if ( $day == 'wednesday' ) { ?> 
        <h3 class="day-title"><?php _e('Wednesday',PLUGIN_NAME); ?></h3>
        <?php } ?> 
        <?php if ( $day == 'thursday' ) { ?> 
        <h3 class="day-title"><?php _e('Thursday',PLUGIN_NAME); ?></h3>
        <?php } ?> 
        <?php if ( $day == 'friday' ) { ?> 
        <h3 class="day-title"><?php _e('Friday',PLUGIN_NAME); ?></h3>
        <?php } ?> 
        <?php if ( $day == 'saturday' ) { ?> 
        <h3 class="day-title"><?php _e('Saturday',PLUGIN_NAME); ?></h3>
        <?php } ?> 
        <?php if ( $day == 'sunday' ) { ?> 
        <h3 class="day-title"><?php _e('Sunday',PLUGIN_NAME); ?></h3>
        <?php } ?> 
        <label for="day_off_<?php echo $day; ?>" ><span class="medium-long"><?php _e('Day Off',PLUGIN_NAME); ?></span></label>    
        <input type="checkbox" id="day_off_<?php echo $day; ?>" name="resto_all_setting[day_off_<?php echo $day; ?>]" <?php checked('on', $options['day_off_' . $day]) ?> />
        <br class="height_10px" />
        <label for="open_time_<?php echo $day; ?>" ><span class="medium-long"><?php _e('Open Time',PLUGIN_NAME); ?></span></label>
        <input type="text" id="open_time_<?php echo $day; ?>" name="resto_all_setting[open_time_<?php echo $day; ?>]" value="<?php echo $option_open ?>" />
        <br class="height_10px" />
        <label for="close_time_<?php echo $day; ?>" ><span class="medium-long"><?php _e('Close Time',PLUGIN_NAME); ?></span></label>
        <input type="text" id="close_time_<?php echo $day; ?>" name="resto_all_setting[close_time_<?php echo $day; ?>]" value="<?php echo $option_close ?>" />
        <?php } //foreach( $every_day as $day ){ ?>
    
    
    </div>
    
    
    <hr class="float-none" />
	
    
	<?php //= 15.	TIME INTERVAL ?>
    <?php
		if( $options['time_interval'] == '' ){
				$options['time_interval'] = '30';
		}
	?>
    <label for="open_time_<?php echo $day; ?>" ><strong><?php _e('Time Interval',PLUGIN_NAME); ?></strong></label>
   	<input type="text" id="time_interval" name="resto_all_setting[time_interval]" value="<?php echo $options['time_interval'] ?>" />
    <p class="no-margin"><strong><?php _e('Note : ',PLUGIN_NAME); ?></strong><?php _e('the range between hours , if you set 30 , then it would be (  01.30 , 02.00 , 02.30  )',PLUGIN_NAME); ?></p>
    
	<hr class="float-none" />
	
    <?php //= 16.	EARLY BOOKINGS ?>
    <label for="early_bookings" ><strong><?php _e('Early Bookings',PLUGIN_NAME); ?></strong></label>
   	<select name="resto_all_setting[early_bookings]" id="early_bookings">
      	<option value="" <?php selected( $options['early_bookings'], '' ); ?>><?php _e('Anytime',PLUGIN_NAME); ?></option>
    	<option value="1 day" <?php selected( $options['early_bookings'], '1 day' ); ?>><?php _e('1 day in advance',PLUGIN_NAME); ?></option>
   		<option value="1 week" <?php selected( $options['early_bookings'], '1 week' ); ?>><?php _e('1 week in advance',PLUGIN_NAME); ?></option>
    	<option value="2 week" <?php selected( $options['early_bookings'], '2 week' ); ?>><?php _e('2 weeks in advance',PLUGIN_NAME); ?></option>
      	<option value="3 week" <?php selected( $options['early_bookings'], '3 week' ); ?>><?php _e('3 weeks in advance',PLUGIN_NAME); ?></option>
    	<option value="1 month" <?php selected( $options['early_bookings'], '1 month' ); ?>><?php _e('1 month in advance',PLUGIN_NAME); ?></option>
    	<option value="2 month" <?php selected( $options['early_bookings'], '2 month' ); ?>><?php _e('2 months in advance',PLUGIN_NAME); ?></option>
       	<option value="3 month" <?php selected( $options['early_bookings'], '3 month' ); ?>><?php _e('3 months in advance',PLUGIN_NAME); ?></option>
	</select>
    <p class="no-margin"><strong><?php _e('Note :',PLUGIN_NAME); ?></strong> <?php _e('set early booking your customer can make',PLUGIN_NAME); ?></p>
    
    <hr class="float-none" />
    
    <?php //= 17.	LATE BOOKINGS ?>
    <label for="late_bookings" ><strong><?php _e('Late Bookings',PLUGIN_NAME); ?></strong></label>
    <select name="resto_all_setting[late_bookings]" id="late_bookings">
       	<option value="1 minutes" <?php selected( $options['late_bookings'], '1 minutes' ); ?>><?php _e('last minute in advance',PLUGIN_NAME); ?></option>
  		<option value="15 minutes" <?php selected( $options['late_bookings'], '15 minutes' ); ?>><?php _e('15 minutes in advance',PLUGIN_NAME); ?></option>
  		<option value="30 minutes" <?php selected( $options['late_bookings'], '30 minutes' ); ?>><?php _e('30 minutes in advance',PLUGIN_NAME); ?></option>
   		<option value="45 minutes" <?php selected( $options['late_bookings'], '45 minutes' ); ?>><?php _e('45 minutes in advance',PLUGIN_NAME); ?></option>
   		<option value="1 hour" <?php selected( $options['late_bookings'], '1 hour' ); ?>><?php _e('1 hour in advance',PLUGIN_NAME); ?></option>
    	<option value="3 hour" <?php selected( $options['late_bookings'], '3 hour' ); ?>><?php _e('3 hours in advance',PLUGIN_NAME); ?></option>
      	<option value="6 hour" <?php selected( $options['late_bookings'], '6 hour' ); ?>><?php _e('6 hours in advance',PLUGIN_NAME); ?></option>
       	<option value="9 hour" <?php selected( $options['late_bookings'], '9 hour' ); ?>><?php _e('9 hours in advance',PLUGIN_NAME); ?></option>
    	<option value="12 hour" <?php selected( $options['late_bookings'], '12 hous' ); ?>><?php _e('12 hours in advance',PLUGIN_NAME); ?></option>
     	<option value="18 hour" <?php selected( $options['late_bookings'], '18 hour' ); ?>><?php _e('18 hours in advance',PLUGIN_NAME); ?></option>
 	 	<option value="23 hour" <?php selected( $options['late_bookings'], '23 hour' ); ?>><?php _e('23 hours in advance',PLUGIN_NAME); ?></option>
 	</select>
    <p class="no-margin"><strong><?php _e('Note :',PLUGIN_NAME); ?></strong> <?php _e('set late booking your customer can make',PLUGIN_NAME); ?></p>