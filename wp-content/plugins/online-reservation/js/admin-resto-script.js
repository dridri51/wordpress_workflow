jQuery(document).ready(function($){ 
	
/*
	TABLE OF CONTENTS
	========================
	1.	GENERAL SETTING
	2.	EMAIL TAB SETTING	
	3.	TABLE TAB SETTING
*/


	/*=========================================================
		1.	GENERAL SETTING
			1. 	GLOBAL VARIABLES
			2.	MANIPULATE SETTING TAB AND SETTING CONTENT 
			3.	CHOOSE DATE CHANGE
			4.	TOGGLE CONTENT
	=========================================================*/
	var Olr_admin_resto = {
		init : function() {
			
			/*==========================================================
				1. 	GLOBAL VARIABLES
			==========================================================*/
			plugin_path			= data.plugin_path; 
			plugin_folder		= data.plugin_folder; 
			plugin_options 		= data.plugin_options; 
			admin_url 			= data.admin_url;
			check_date_url 		= data.plugin_folder + "restaurant reservation/ajax-request/check-date.php";
			$('table.wp-list-table a.row-title').contents().unwrap();
			$('.view-switch').css('display','none');
			$('#preview-action').css('display','none');
			$('#resto_schedule_wrapper,#resto_table_wrapper,#resto_email_wrapper,#resto_captcha_wrapper,#resto_form_wrapper,#resto_security_wrapper')
				.css('display','none');
			
			
			/*==========================================================
				2.	MANIPULATE SETTING TAB AND SETTING CONTENT 
			==========================================================*/
			$('.olr_admin_resto_wrapper .nav-tab-wrapper a:first-child').addClass('nav-tab-active');
			$('.nav-tab').click(function(event){
				event.preventDefault();
				$('#restaurant-setting-form > div').css('display','none')
				$('.nav-tab-wrapper').find('a').removeClass('nav-tab-active');
				$(this).addClass('nav-tab-active');
				$('#resto_'+$(this).text().toLowerCase()+'_wrapper').css('display','block');
			});
			
			
			/*==========================================================
				3.	CHOOSE DATE CHANGE ( ALL BOOKING LIST PAGE )
					1.	SET DAY OFF
					2.	SHOW PROPERLY TIME
			==========================================================*/
			
				/*==========================================================
					1.	SET DAY OFF
				==========================================================*/
				var all_day = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
				var day_off = new Array();	
				for( var day=0;day<7;day++ ){
					
					if( $.trim( plugin_options['day_off_' + all_day[day]] ) != '' ){
						day_off[day] = day - 0;
					}else{
						
						
						day_off[day] = 9;
					}
				}
				$("#olr-date-filter,#booking_date").datepicker({
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
		
				/*==========================================================
					2.	SHOW PROPERLY TIME
				==========================================================*/
				$("#booking_date").change(function(){
					var postData= {};
						postData['date'] 		= $("#booking_date").val();
						postData['plugin_path'] = plugin_path;
						postData['command'] 	= 'show time';
						postData['options'] 	= plugin_options;	
					
					$.post(check_date_url, postData, function(data) {
						$("#booking_time").html(data);	
					});
				}); // $("#olr_date").change(function(){
					
			
			/*==========================================================
				4.	TOGGLE CONTENT
			==========================================================*/
			$(".resto_admin_primary_content .head-parent,.resto_admin_sidebar .head-parent").click(function(){
				if( $(this).next().css('display') == 'block' ){
					$(this).find('.fa-caret-up').css('display','block');
					$(this).find('.fa-caret-down').css('display','none');
				}else{
					$(this).find('.fa-caret-up').css('display','none');
					$(this).find('.fa-caret-down').css('display','block');
				}
				$(this).next().slideToggle(500);
			}); 
			
			
			
		
		} // init : function() {		
	}
	//= INITIALIZE GENERAL SETTING
	Olr_admin_resto.init();
	
	
	/*=========================================================
		2.	EMAIL ADMIN RESTO SETTING
			1. 	GLOBAL VARIABLES
			2. 	DISPLAY LOGO PREVIEW 
			3. 	DISPLAY IMAGE PREVIEW
			4.	RESTAURANT EMAIL UPLOAD IMAGE
			5.	GET FIELD CONTENT
			6.	SAVE FIELD CONTENT
	=========================================================*/
	var Olr_email = {
		init : function() {
			
			//= 1. 	GLOBAL VARIABLES
			
			//= 2. 	DISPLAY LOGO PREVIEW 
			if( !$('#restaurant-logo-image-preview').attr('src') ){
				$('#restaurant-logo-image-preview').css('display','none');
			}
			
			//= 3. 	DISPLAY IMAGE PREVIEW 
			if( !$('#restaurant-image-preview').attr('src') ){
				$('#restaurant-image-preview').css('display','none');
			}
			
			//= 4.	RESTAURANT EMAIL UPLOAD IMAGE
			this.upload_image();
			
			//= 5.	GET FIELD CONTENT
			this.get_field_content();
			
			//= 6.	SAVE FIELD CONTENT
			this.save_field_content();
		},
		
		
		upload_image:function() {
			
			$('#olr_upload_logo_button,#olr_upload_image_button').click(function() {
					
					parent = $(this).parent();
				
					tb_show(
						'Upload Image',	 // title or caption	
						'media-upload.php?type=image&TB_iframe=true',  // media_upload url	
						false // image group
					);
					
					
					// ============== original_send_to_editor = window.send_to_editor; => for not crashing with 
					//=================== default wordpress add media
					var original_send_to_editor = window.send_to_editor;
					
					window.send_to_editor = function(html) {
						//alert(html);
						//alert( html.search( '.pdf' ) );
						
						//=========================================
						// 1. Check Filetype (.png or .pdf or .zip)
						//=========================================
						if( html.search('.png') > 0  || html.search('.gif') > 0
							|| html.search('.jpg') > 0 ){
						
							// html returns a link like this:
							// <a href="{server_uploaded_image_url}"><img src="{server_uploaded_image_url}" alt="" title="" width="" height"" class="alignzone size-full wp-image-125" /></a>
							//1. get the image src inside html variable
							var image_url = $('img',html).attr('src');

							//2. Set image src
							parent.find('.restaurant-image').val(image_url);
			
							//3. Remove Thick Box
							tb_remove();
							
							//4. display preview image
							parent.find('.image-preview').css('display','block');
							parent.find('.image-preview').attr('src',image_url);
							
						}else{ // if is not image ( such as .zip)
							//do nothing...
						}
						
						window.send_to_editor = original_send_to_editor;
					} // END window.send_to_editor	============================
				
					
					//return false;
			}); // $('#olr_upload_logo_button').click(function() {
			
			
			$('#olr_remove_logo_button,#olr_remove_image_button').click(function() {
				
				//= 1. remove preview image
				$(this).parent().find('.image-preview').css('display','none');
				$(this).parent().find('.image-preview').attr('src','');
				
				//= 2. set restaurant logo
				$(this).parent().find('.restaurant-image').val('');
			
			}); // $('#olr_upload_logo_button').click(function() {
	
	
		}, // upload_image:function() {
		
		
		
		get_field_content:function() {	
			
			$('#get-field-content').click(function() {
				var content = $( '#'+$('#email-editing-content').val() ).val();
				tinyMCE.activeEditor.setContent(content);									   
			});
			
		}, // get_field_content:function() {	
				
					
		save_field_content:function() {	
			
			$('#save-field-content').click(function() {
				var content = tinymce.activeEditor.getContent(); 
				$( '#'+$('#email-editing-content').val() ).val(content);									   
			});
			
		} // save_field_content:function() {	
					
					
	} // var Olr_email = {
	
	//= INITIALIZE EMAIL SETTING
	Olr_email.init();
	
	
	
	/*=========================================================
		3.	TABLE TAB SETTING
			1. 	GLOBAL VARIABLES
			2.	UPDATE TABLE MEMBERS ( MANY )
	=========================================================*/
	var Olr_table = {
		init : function() {
			
			//= 1. 	GLOBAL VARIABLES
			plugin_options 	= data.plugin_options; 
			admin_url 		= data.admin_url;
			plugin_folder 	= data.plugin_folder;
			loading_img		= plugin_folder + "image/loading.gif";
			
			
			//=  2.	UPDATE TABLE MEMBERS ( MANY )
			this.update_table_members();
		},
		update_table_members:function() {	
			
			$('#display_table_field_button').click(function(){
				
				var many_table_members = $('#many_type_of_table').val();
				
				if( many_table_members != '' ){
				
					$('#many_table_members').html('');
					$('#many_table_members').prepend('<img id="theImg" src="'+loading_img+'" /><span>Please Wait...</span>');
					
					var postData= {};
						postData['action'] 				= 'update-table-member';
						postData['many_type_of_table'] 	= many_table_members;
					
					$.post( admin_url , postData, function(data) {
						$('#many_table_members').html('');
						$('#many_table_members').html(data);
					});		
				
				}else{
					alert( 'Insert value on Type of table field' );
				}

			});
		
		}

	} // var Olr_table = {
	
	
	//= INITIALIZE EMAIL SETTING
	Olr_table.init();
	
	
});