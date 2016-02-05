<?php

/*
	TABLE OF CONTENTS
	========================
	1.	DATE FILTER 
*/

	
	/*==============================================================
		1.	DATE FILTER 
	==============================================================*/
	add_action( 'restrict_manage_posts', 'my_filter_list' );
		
	function my_filter_list() {
			$screen = get_current_screen();
			global $wp_query;
			if ( $screen->post_type == 'olr_restaurant' ) {
				
				$condition = '<a class="date-filter-link" href="'.admin_url().'edit.php?post_type=olr_restaurant&date=today" 
									id="tomorrow-date" name="today-date">'.__('Today',PLUGIN_NAME).'</a>';
				$condition .= '<a class="date-filter-link" href="'.admin_url().'edit.php?post_type=olr_restaurant&date=tomorrow" 
									id="tomorrow-date" name="today-date">'.__('Tomorrow',PLUGIN_NAME).'</a>';
				$condition .= '<input class="date-filter-link" placeholder="'.__('Choose Date',PLUGIN_NAME).'" name="olr-date-filter" id="olr-date-filter" >';
				echo $condition;
			}
	}	

	add_filter( 'parse_query', 'filter_date_by_meta_value' );
	function filter_date_by_meta_value($query) {
		global $pagenow;
		if ( 	( is_admin() && $pagenow=='edit.php' && isset( $_GET['date'] ) )
			|| 	( is_admin() && $pagenow=='edit.php' && isset( $_GET['olr-date-filter'] ) )
			
		){
			
			$query->query_vars['post_type'] = 'olr_restaurant';
			$query->query_vars['post_status'] = array('pending','confirmed');
			$query->query_vars['meta_key'] = 'olr_custom_column';
			$qv = &$query->query_vars; //grab a reference to manipulate directly
			
			
			if( $_GET['date'] == 'today' ){
				$date = date("m/d/Y" , strtotime("today") );
			}
			
			if( $_GET['date'] == 'tomorrow' ){
				$date = date("m/d/Y" , strtotime("+1 day") );
			}
			
			if( $_GET['olr-date-filter'] ){
				$date = $_GET['olr-date-filter'];
			}

			$qv['meta_query'][] = array(
									'field' => 'olr_custom_column',
									'value' => $date,
									'compare' => 'LIKE'
									);
			
		}
		
		
		
	}
		
	
	
		