jQuery(document).ready(function($){ 
	
	/*
		TABLE OF CONTENTS
		========================
		1.	init ( INITIALIZE )
			1. 	GLOBAL VARIABLES
			2.	DATE PICKER
			3.	GET CLIENT LATITUDE AND LONGITUDE
			4.	RESTAURANT BOOKING FORM	
	*/

	var Olr = {
		init : function() {
			
			//= 1. 	GLOBAL VARIABLES
			this.plugin_folder 	= data.plugin_folder,
			this.plugin_path 	= data.plugin_path,
			this.early_bookings = data.early_bookings, 
			this.late_bookings 	= data.late_bookings; 
			this.plugin_options = data.plugin_options; 
			this.ajaxurl		= data.ajaxurl; 
			this.ip_address		= data.ip_address;
			this.fake_actions_title		= data.fake_actions_title;
			this.fake_actions_message	= data.fake_actions_message;
			this.geolocation_api		= data.geolocation_api;
			
			
			
			var all_day = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
				
			var day_off = new Array();	
			
			for( var day=0;day<7;day++ ){
				
				if( $.trim( this.plugin_options['day_off_' + all_day[day]] ) != '' ){
					day_off[day] = day - 0;
				}else{
					
					
					day_off[day] = 9;
				}
			}
			
			//= 2.	DATE PICKER
			if( $('#olr_date').length > 0 ){
				$('#olr_date').datepicker({
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
			}
			
			/*=================================================
				3.	GET CLIENT LATITUDE AND LONGITUDE
					1.	CHECK IF RESERVATION IS BEING LOCKOUT
			=================================================*/
			if( this.geolocation_api == 'telize' ){
				$.getJSON("http://http://api.hackertarget.com/geoip/?q="+this.ip_address,		  
						  
					function(json) {
					var postData= {};
						postData['latitude'] 	= json.latitude;
						postData['longitude'] 	= json.longitude;
						postData['action'] 		= 'check-table-lockout';
						
						//alert( this.fake_actions_title );
						
						$.post(ajaxurl,postData, function(data) {
							alert( data );
							if( data ){
								$('#olr_restaurant_form').remove();
								$('.olr_restaurant_wrapper').prepend('<p>We are Swamped</p></p>Come again later</p>');
							}else{
								$('#country').val(json.country);
								$('#city').val(json.city);
								$('#latitude').val(json.latitude);
								$('#longitude').val(json.longitude);
							}
						});
		
					}
				);
			}
			
			
		
			
			//= 4.	RESTAURANT BOOKING FORM	
			this.restaurant_booking_form();
			
		},
		
		
		restaurant_booking_form:function() {
			
			/*
				TABLE OF CONTENTS
				========================
				1. 	GENERAL VARIABLE
				2.	VALIDATION 
				3.	SHOW PROPERLY TIME
				4.	CHECK LATE AND EARLY BOOKINGS
				5.	CHECK TOTAL TABLE
				6. 	FIND TABLE BUTTON ( ONCLICK )
				7.	SEND AN INQUIRY BUTTON ( ONCLICK )
				8. 	BOOKING BUTTON ( ONCLICK )
			*/
					

			/*==================================
				1. 	GENERAL VARIABLE
			==================================*/
			var loading_img 			= this.plugin_folder + "image/loading.gif";
			var check_date_url 			= this.plugin_folder + "restaurant reservation/ajax-request/check-date.php";
			var check_total_table_url 	= this.plugin_folder + "restaurant reservation//ajax-request/total_table.php";
			plugin_folder 				= this.plugin_folder;
			plugin_path 				= this.plugin_path;
			plugin_options				= this.plugin_options;
			ajaxurl						= this.ajaxurl;
			
			
			/*==================================
				2.	VALIDATION 
					1.	PERSONAL INFORMATION
						1.	NAME
						2.	EMAIL
						3.	PHONE
							
					2.	BOOKING TABLE INFORMATION
						2.	PERSONS
			==================================*/
				$("#olr_name,#olr_email,#olr_phone,#olr_persons").keyup(function(){
					var id = $(this).attr('id');
					
					
					//=	1.	CHECK FIELD NAME
					if(	id == 'olr_name' ){
			
						if( /^[A-Za-z ]+$/.test( $(this).val() ) == false ){
							$(this).val($(this).val().substring(0,$(this).val().length-1));
						}
					}
				
					if(	id == 'olr_phone' ){
						if( /^[0-9-()+]{1,20}$/.test( $(this).val() ) == false ){
							$(this).val($(this).val().substring(0,$(this).val().length-1));
						}
					
					}
				
					if(	id == 'olr_persons' ){
						if( /[0-9]+$/.test( $(this).val() ) == false ){
							$(this).val($(this).val().substring(0,$(this).val().length-1));
						}
					
					}
				});
				
				
			/*===========================================
				3.	SHOW PROPERLY TIME
			===========================================*/
			$("#olr_date").change(function(){
				var postData= {};
					postData['date'] 		= $("#olr_date").val();
					postData['plugin_path'] = plugin_path;
					postData['command'] 	= 'show time';
					postData['options'] 	= plugin_options;	
				
				$.post(check_date_url, postData, function(data) {
					$("#olr_time").html(data);	
				});
			}); // $("#olr_date").change(function(){
			
			
			
			/*===========================================
				4.	CHECK LATE AND EARLY BOOKINGS
					1.	VARIABLE	
					2.	PREPEND LOADING IMAGE
					3.	CHECK DATE
			===========================================*/
			$("#olr_time").change(function(){
					
				//= 1.	VARIABLE
				var postData= {};
					postData['date'] 		= $("#olr_date").val();
					postData['time'] 		= $("#olr_time").val();
					postData['options'] 	= plugin_options;
					
				
				//= 2.	PREPEND LOADING IMAGE
				$('.time_response').append(' <img class="theImg" src="'+loading_img+'" /><span> Check Time...</span>');	


				if( $("#olr_date").val() == '' ){
					$("#olr_time").html('');
					alert( 'Please Choose Your booking date' );
					$('.time_response').html('');
					
				}
				
					if( 	$("#olr_date").val() != '' 
						&& 	$("#olr_time").val() != '' ){
						
						
						//= 3.	CHECK DATE
						$.post(check_date_url, postData, function(data) {
							
							if( data != '' ){
									
									//= SEARCH FOR ","
									if( data.search("}") > 0 ){
										
										var data = data.split("}");
										if( data.length > 0 ){
											for (var i = 0; i< data.length; i++) {
												if( i == 0 && data[i] != ''){
													//alert(data[i]);
													
													var display = data[i].split("<br/>");
													alert( 	display[0] + '\n\n' 
															+ display[1] + '\n\n' 
															+ display[2] + '\n\n' 
															+ display[3]
															);
													
												}
												
												if( i == 1  && data[i] != ''){
												//if( i == 2 && data[i] != ''){
													$('#olr_date').val('');
													$("#olr_time").html('');
													if( $('.date_error').length > 0 ){
														$('.date_error').remove();
													}
													$('.olr_date_wrap').append('<span class="date_error">'+data[i]+'<span>');	
													
												}else if( i == 1  && data[i] == '' ){
													if( $('.date_error').length > 0 ){
														$('.date_error').remove();
													}
												}
												
												if( i == 2  && data[i] != ''){
												//if( i == 2 && data[i] != ''){
													$('#olr_time').val('');
													if( $('.time_error').length > 0 ){
														$('.time_error').remove();
													}
													
													$('.olr_time_wrap').append('<span class="time_error">'+data[i]+'<span>');	
						
												}else if( i == 2  && data[i] == '' ){
													if( $('.time_error').length > 0 ){
														$('.time_error').remove();
													}
												}

											}
										}
									
									}else if( data.search("!") > 0 ){
										$('#olr_date').val('');
										$("#olr_time").html('');
										alert(data);
									}else{
										alert(data);									
									}
									
									$('.time_response').html('');
									
								}else{
									if( $('.date_error').length > 0 ){
										$('.date_error').remove();
									}
									if( $('.time_error').length > 0 ){
										$('.time_error').remove();
									}
									
									$('.time_response').html('');
									
								}  // if( data != '' ){
							
							
							
						}); // $.post(check_date_url, postData, function(data) {
					} // if( 	$("#olr_time").val() != '' 
							   
			}); // $("#olr_date,#olr_time").change(function(){
			
			
			/*==================================
				5.	CHECK TOTAL TABLE
			==================================*/
			if($("#olr_type_of_table").length > 0 ){
				$("#olr_type_of_table").change(function(){
					
					var postData= {};
						postData['options'] 	= plugin_options;
						postData['table_name'] 	= $(this).val();
					
					$.post(check_total_table_url, postData, function(data) {
						$("#olr_table")
						.find('option')
						.remove()
						.end()
						.append(data);
					});										 
														 
				});
			}
			
			
			/*=======================================
				6. 	FIND TABLE BUTTON ( ONCLICK )
					1.	VARIABLE
					2. 	EMPTY SOME CONTAINER
					3. 	CHECK REQUIRED FIELDS
			=======================================*/
			$("#olr_find_table_button").click(function(event){
				event.preventDefault();
				
				//= 1.	VARIABLE
				var postData= {};
					postData['person'] 	= $("#olr_persons").val();
					postData['date']	= $("#olr_date").val();
					postData['time'] 	= $("#olr_time").val();
					postData['action'] 	= 'resto-find-table-ajax-submit';
					postData['country'] 	= $("#country").val();
					postData['city']		= $("#city").val();
					postData['latitude'] 	= $("#latitude").val();
					postData['longitude'] 	= $("#longitude").val();
					
					
				//= 2. 	EMPTY SOME CONTAINER
				$('.personal_information_wrapper').html('');
				$('#olr_restaurant_booking_button').css('display','none'); 
				$('#olr_restaurant_response').css('display','none');
				$('#olr_send_enquiry_button').css('display','none'); 
				
				//= 3. 	CHECK REQUIRED FIELDS
				error = false;
				$('.olr_restaurant_wrapper').find('.olr_required').each(function(index){
					if( $(this).prev().val() == '' ){
						error = true;
					}
				});
				
				if( error ){
					error_content = 'All Required Fields must be filled or selected ';
					alert( error_content );
				}else{
					$('.olr_find_table_response').css('display','none');
					
					$.post(ajaxurl,postData, function(data) {
						//alert( data );
						$('.olr_find_table_response').css('display','block');
						//$('.olr_response_result').text(data); 
						var datas = jQuery.parseJSON( data );
						
						if( datas.status == 'Found' ){
							$('.olr_response_recommend').text('');
							$('.olr_response_result').text(datas.answer); 
							$('.personal_information_wrapper').append($('.personal_information_container').html());
							$('.personal_information_wrapper').css('display','block'); 
							$('.olr_type_table').val(datas.type_table); 
							$('#olr_restaurant_booking_button').css('display','block'); 
						}
						
						if( datas.status == 'Not Found' ){
							$('.olr_response_result').text(datas.title); 
							$('.olr_response_recommend').text(datas.answer);
						}
						
						
						if( 	datas.status == 'Enquiry' 
							||	datas.status == 'Fully Booked' 
						){
							$('.olr_response_result').text(datas.title); 
							$('.olr_response_recommend').text(datas.answer);
							$('#olr_send_enquiry_button').css('display','block'); 
						}
						
						if( datas.status == 'TOO MANY FAKE ACTIONS' ){
							$('.olr_response_result').text(datas.title); 
							$('.olr_response_recommend').text(datas.answer);
							$('#olr_find_table_button').remove();	
						}

					});		
				}
			});
			
			/*=============================================
				7.	SEND AN INQUIRY BUTTON ( ONCLICK )
			=============================================*/
			$("#olr_send_enquiry_button").click(function(event){
				
				var element = '<p style="display: none"><input type="text" id="olr_booking_status" value="enquiry" /></p>';
				
				$(this).css('display','none');
				$('.personal_information_wrapper').append($('.personal_information_container').html());
				$('.personal_information_wrapper').append(element);
				$('.personal_information_wrapper').css('display','block'); 
				$('.olr_type_table_wrap').css('display','none'); 
				$('#olr_restaurant_booking_button').val('Send Us an Enquiry');
				$('#olr_restaurant_booking_button').css('display','block');
			});
			
			
			/*==================================
				8. 	BOOKING BUTTON ( ONCLICK )
					1.	VARIABLE
					2.	GOOGLE RECAPTCHA VARIABLE
					3.	NONCE 
					4.	VALIDATION 
					5. 	CHECK REQUIRED FIELDS
					6. 	COLLECT ALL POST DATA
					7. 	SUBMIT FORM
			==================================*/
			$("#olr_restaurant_booking_button").click(function(event){
						
				event.preventDefault();
				
				var error = false;
				var postData= {};
				var error_content = '';
				
				//= 1.	VARIABLE 
				var postData= {};
					postData['plugin_url'] 	= plugin_folder;
					postData['plugin_path'] = plugin_path;
					postData['options'] 	= plugin_options;
					postData['action'] 		= 'resto-booking-ajax-submit';
					
					
				//=	2.	GOOGLE RECAPTCHA VARIABLE
				if( $("#recaptcha_challenge_image").length > 0 ){
						postData['recaptcha_challenge_field'] 		= $("#recaptcha_challenge_field").val();
						postData['recaptcha_response_field'] 		= $("#recaptcha_response_field").val();
				}
				
					
				//= 3.	NONCE 
				postData['restaurant_nonce'] = $('#olr_restaurant_form').find('#restaurant_nonce_id').val()
				
				//= 4.	VALIDATION 
				if( /[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,4}/.test( $('#olr_email').val() ) == false ){
					error = true;
					error_content += 'Email is not Valid \n';
				}
					
				
				//= 5. 	CHECK REQUIRED FIELDS
				$('.olr_restaurant_wrapper').find('.olr_required').each(function(index){
					if( $(this).prev().val() == '' ){
						error = true;
					}
				});
				
				//= 6. 	COLLECT ALL POST DATA
				$('#olr_restaurant_form p').find(':text,select,textarea').each(function(index){
					postData[$(this).attr('id')] = $(this).val();																																		
				});
				$('#olr_restaurant_form div').find(':text,select,textarea').each(function(index){
					postData[$(this).attr('id')] = $(this).val();																																		
				});
				

				//=	7. 	SUBMIT FORM
				if( error ){
					
					error_content += 'All Required Fields must be filled or selected ';
					alert( error_content );
					
				}else{
					if( $('#theImg').length == 0 ){
						$('#olr_restaurant_response').prepend('<img id="theImg" src="'+loading_img+'" /><span>Please Wait...</span>');	
					}	
					
					$.post(ajaxurl,postData, function(data) {
						//alert( data );
						var captcha_error = false;
						
						if( $("#recaptcha_challenge_image").length > 0 ){
							Recaptcha.reload();
							var captcha_error_msg = data;
							if( captcha_error_msg.search(/reCAPTCHA said/i) > 0 ){
								var data = captcha_error_msg.split("<br/>");
								$('.olr_captcha_response_error').text(data[0]);
								$('#recaptcha_response_field').val('').focus();
								$('#olr_restaurant_response').html('');
								captcha_error = true;
							}else{
								$('.olr_captcha_response_error').text('');
							}
						}
						
						if( !captcha_error ){
							//alert( data );
							//= EMPTY SOME CONTAINER
							$('.personal_information_wrapper').html('');
							$('#olr_restaurant_booking_button').css('display','none'); 
							
							//= DISPLAY SUCCESS MESSAGE
							$('.olr_find_table_response').css('display','none');
							$('#olr_restaurant_response').html(data).fadeIn(1000);
							$('#olr_find_table_button').focus();
							
						}
					});				
				}
				
			}); // $("#olr_restaurant_booking_button").click(function(event){
		} // restaurant_booking_form:function() {
					
	}
	
	//initialize Archieve
	Olr.init();
	
});