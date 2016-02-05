<?php
/*
	TABLE OF CONTENTS
	========================
	1.	RESTAURANT ADMIN PAGES
*/
	
	/*=================================
		1.	RESTAURANT ADMIN PAGES
			1. 	VARIABLE 
			2.	CHECK CURRENT PAGE
	=================================*/
		
		/*===========================
			1. 	VARIABLE 
		===========================*/
		$olr_admin_page = false;
		$all_bookings_page	= false;
		$post_page = false;
		$post_new = false;
		$resto_setting_page	= false;
		$plugins_page	= false;
		$options_page = false; // ACCESS THIS PAGE ON SETTING SAVED PROCESS
		$widget_page = false;
		$admin_ajax = false;
		
		/*===========================
			2.	CHECK CURRENT PAGE
		===========================*/
		$post_type 		= $_GET['post_type'];
		$setting_page 	= $_GET['page'];
		$setting_tab 	= $_GET['tab'];
		
		if( is_admin() ){
			$olr_admin_page = true;	
		}

		if( 	is_admin() 
			&&	$pagenow == 'edit.php' 
			&& 	$post_type == 'olr_restaurant'
			&& 	$setting_page == ''
		){
			$all_bookings_page	= true;
		}
		
		if( 	is_admin() 
			&&	$pagenow == 'post.php' 
		){
			$post_page	= true;
		}
		
		if( 	is_admin() 
			&&	$pagenow == 'post-new.php' 
			&& 	$post_type == 'olr_restaurant'
		){
			$post_new	= true;
		}
		
		if( 	is_admin() 
			&&	$pagenow == 'edit.php' 
			&& 	$post_type == 'olr_restaurant'
			&& 	$setting_page == 'olr_restaurant_setting'
		){
			$resto_setting_page	= true;
		}
		
		if( 	is_admin() 
			&&	$pagenow == 'plugins.php' 
		){
			$plugins_page	= true;
		}
		
		if( 	is_admin() 
			&&	$pagenow == 'options.php' 
		){
			$options_page = true;
		}
		
		if( 	is_admin() 
			&&	$pagenow == 'widgets.php' 
		){
			$widget_page = true;
		}
		
		if( 	is_admin() 
			&&	$pagenow == 'admin-ajax.php' 
		){
			$admin_ajax = true;
		}

?>