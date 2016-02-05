<?php

	/*
		TABLE OF CONTENTS
		========================
		1.	POST DATA
		2. 	GENERAL VARIABLE
		3.	DATE AND TIME CHOOSEN ( ms )
		4.	RETRIEVE DATABASE DATA
		5.	SHOW TIME
		6.	CHECKING BOOKING DATE
	*/

	/*########################################
		1.	POST DATA
	########################################*/	
	$date 		= $_POST['date'];
	$time 		= $_POST['time'];
	$Options 	= $_POST['options'];
	
	/*########################################
		2. 	GENERAL VARIABLE
	########################################*/	
	
	
	/*########################################
		3.	DATE AND TIME CHOOSEN ( ms )
	########################################*/	
		$day = substr($date ,3,2);
		$month = substr($date ,0,2);
		$year = substr($date ,6,4);
		$hour = substr($time ,0,2);
		$minute = substr($time ,3,2);
		
		$month = date("F",mktime(0,0,0,$month,$day,$year) );
		$date_choosen = strtotime($day .' '. $month .' '. $year .' +'.$hour.' hours'.' +'.$minute.' minutes');
		$day_letter = date('D', strtotime($day .' '. $month .' '. $year));
		$now = strtotime("now");
		$day_now 	= date('j', $now );
		$month_now 	= date('n', $now );
		$year_now 	= date('Y', $now );
	

	
	/*########################################
		4.	RETRIEVE DATABASE DATA
	########################################*/	
	$options = $Options;	

	
	/*########################################
		5.	SHOW TIME
	########################################*/	
	if( isset($_POST['command']) ){
		require_once( $_POST['plugin_path'] . 'helper/helper_functions.php');
		echo olr_output_time_list( $options,'',$day_letter);
		exit;
	}
	
	
	/*########################################################################
		6.	CHECKING BOOKING DATE
			1.	CHECK IS PAST DATE
			2.	CHECK LATE BOOKINGS ( BOOKING BETWEEN ONE DAY )
			3.	CHECK EARLY BOOKINGS ( BOOKING MORE THAN ONE DAY )
	########################################################################*/	
	
		/*==========================================
			1.	CHECK IS PAST DATE
				1.	CHECK YEAR , MONTH AND DAY 
		==========================================*/
		if( $date_choosen < $now ){
			
			/*==========================================
				1.	CHECK YEAR , MONTH AND DAY 
			==========================================*/
			$past_date = false; 
			if( ( date('Y',$date_choosen) - 0 ) < ( date('Y',$now) - 0 ) ){
				$past_date = true;
			}
			if( ( date('n',$date_choosen) - 0 ) < ( date('n',$now) - 0 ) ){
				$past_date = true;
			}
			if( ( date('j',$date_choosen) - 0 ) < ( date('j',$now) - 0 ) ){
				$past_date = true;
			}
			
			if( $past_date ){
				echo $notification = 'Your Booking day , month and year should be date in advance' .'!';	
				die();
			}

		}
		
			
		/*======================================================
			2.	CHECK LATE BOOKINGS ( BOOKING BETWEEN ONE DAY )
		======================================================*/
			if( 	$date_choosen < $now 
				||  $date_choosen < ( $date_choosen + 24 * 60 * 60 ) 
				){
			
				$late_bookings = $options['late_bookings'];
				
				if( stripos($late_bookings,'minutes') > 0 ){
					$late_bookings = trim(str_replace( 'minutes','',$late_bookings));
					$late_bookings_time = $late_bookings * 60;
				}
				if( stripos($late_bookings,'hour') > 0 ){
					$late_bookings = trim(str_replace( 'hour','',$late_bookings));
					$late_bookings_time = $late_bookings * 60 * 60;
				}
				if( stripos($late_bookings,'day') > 0 ){
					$late_bookings = trim(str_replace( 'day','',$late_bookings));
					$late_bookings_time = $late_bookings * 24 * 60 * 60;
				}
				
				//= CHECK DAY , MONTH AND YEAR , INPUT IS CORRECT
				$month = substr($date ,0,2);
				$day_now 	= date('j', $now + $late_bookings_time );
				$month_now 	= date('n', $now + $late_bookings_time );
				$year_now 	= date('Y', $now + $late_bookings_time );
				
				$date_is_true = 'false';
				if( ($year_now-0) == ($year - 0) ){
					if( ($month_now-0) == ($month - 0) ){
						if( ($day_now-0) == ($day - 0) ){
							$date_is_true = 'true';
						}
					}
				}
				
				$time_is_true = 'true';
				
				if( $date_choosen < $now + $late_bookings_time ){
					
					$time_is_true = 'false';
					
					$output = 'Now : ' . date("d-M-Y H:i:s" , $now) . '<br/>';
					$output .= 'Late Booking ( must be '.$options['late_bookings'].' in advance )' . '<br/>';
					$output .= 'Date : must be at least ' . date("d-M-Y" , $now + $late_bookings_time ) . '<br/>';
					$output .= 'Time : must bigger than ' . date("H:i" , $now + $late_bookings_time ) . '<br/>';
					$output .= '}';
					if( $date_is_true == 'false' ){
						$output .= ' at least ( ' . date("d-M-Y" , $now + $late_bookings_time ) . ' )';
					}
					$output .= '}';
					if( $time_is_true == 'false' ){
						$output .= ' must bigger than ' . date("H:i" , $now + $late_bookings_time );
					}
					
					echo $output;
					die();
				}
			}
			
			
		/*============================================================
			3.	CHECK EARLY BOOKINGS ( BOOKING MORE THAN ONE DAY )
		============================================================*/
			if( $date_choosen > ( $now + 24 * 60 * 60 ) ){
				
				$early_bookings = $options['early_bookings'];
				$early_bookings_time = $early_bookings;
				
				if( stripos($early_bookings,'day') > 0 ){
					$early_bookings = trim(str_replace( 'day','',$early_bookings));
					$early_bookings_time = $early_bookings * 24 * 60 * 60;
				}
				if( stripos($early_bookings,'week') > 0 ){
					$early_bookings = trim(str_replace( 'week','',$early_bookings));
					$early_bookings_time = $early_bookings * 7 * 24 * 60 * 60;
				}
				
				
				if( stripos($early_bookings,'month') > 0 ){
					$early_bookings = trim(str_replace( 'month','',$early_bookings));
					$early_bookings_time = $early_bookings * 30 * 24 * 60 * 60;
				}
				
				//= CHECK DAY , MONTH AND YEAR , INPUT IS CORRECT
				$month = substr($date ,0,2);
				$day_now 	= date('j', $now + $early_bookings_time );
				$month_now 	= date('n', $now + $early_bookings_time );
				$year_now 	= date('Y', $now + $early_bookings_time );
				
				$date_is_true = 'false';
				if( ($year_now-0) == ($year - 0) ){
					if( ($month_now-0) == ($month - 0) ){
						if( ($day_now-0) == ($day - 0) ){
							$date_is_true = 'true';
						}
					}
				}
				
				$time_is_true = 'true';
				
				if( $date_choosen < $now + $early_bookings_time ){
					
					$time_is_true = 'false';
					
					$output = 'Now : ' . date("d-M-Y H:i:s" , $now) . '<br/>';
					$output .= 'Early Booking ( must be '.$options['early_bookings'].' in advance )' . '<br/>';
					$output .= 'Date : must be at least ' . date("d-M-Y" , $now + $early_bookings_time ) . '<br/>';
					$output .= 'Time : must bigger than ' . date("H:i" , $now + $early_bookings_time ) . '<br/>';
					$output .= '}';
					if( $date_is_true == 'false' ){
						$output .= ' at least ( ' . date("d-M-Y" , $now + $early_bookings_time ) . ' )';
					}
					$output .= '}';
					if( $time_is_true == 'false' ){
						$output .= ' must bigger than ' . date("H:i" , $now + $early_bookings_time );
					}
					
					echo $output;
					die();
				
				}
			
			} // if( $date_choosen > ( $now + 24 * 60 * 60 ) ){

?>