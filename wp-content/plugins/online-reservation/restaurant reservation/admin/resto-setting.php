<?php
/*
	TABLE OF CONTENTS
	=======================================
	1.	REGISTER SETTING
	2.	VALIDATION AND SANITIZATION
*/

/*##############################################
	1.	REGISTER SETTING
##############################################*/	
	add_action('admin_init', 'olr_restaurant_setting');
	function olr_restaurant_setting() {
				
		//= GENERAL VARIABLE
		$parent_page 	= 'resto_all_setting';

				
		//= REGISTER SETTING
		register_setting(
			$parent_page,	// $option_group	
			$parent_page,		// $option_name
			'olr_general_validation'
		);
	} // function olr_restaurant_general_section()


/*##############################################
	2.	VALIDATION AND SANITIZATION
##############################################*/		
function olr_general_validation( $input ){
		
		// Create our array for storing the validated options
		$output = array();
		
		// Loop through each of the incoming options
		if( $input != ''){
			foreach( $input as $key => $value ) {
				
				// Check to see if the current option has a value. If so, process it.
				if( 	$input['restaurant_policies']
					|| 	$input['restaurant_information']
					|| 	$input['restaurant_message']
					|| 	$input['restaurant_footer']
				){
					$output[$key] = stripslashes(wp_filter_post_kses(addslashes($input[$key])));
					
				}else{
				
					// Strip all HTML and PHP tags and properly handle quoted strings
					//	strip_tags() , removing all HTML and PHP tags
					//	stripslashes() , will properly handle quotation marks around a string.
					$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
					
				} // end if
				
			} // end foreach
		}
		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'olr_general_validation', $output, $input );
		
}

?>