<?php
/*
	TABLE OF CONTENTS ( EMAIL TAB )
	=======================================
	1.	EDITOR
	1.	GENERAL FIELD
		1.	RESTURANT NAME
		2.	RESTAURANT ADDRESS
		3.	RESTAURANT CITY
		4.	RESTAURANT STATE
		5.	RESTAURANT ZIP CODE
		6.	RESTAURANT PHONE
		7.	RESTAURANT FAX
		8.	RESTAURANT EMAIL
		9.	RESTAURANT WEBSITE
		10.	RESTAURANT LOGO
		11.	RESTAURANT IMAGE
		12.	SPECIAL OFFER LINK
		13.	RESERVATION LINK
		14.	RESTAURANT MESSAGE
		15.	RESTAURANT INFORMATION
		15.	RESTAURANT POLICIES
		16.	FACEBOOK 
		17.	TWITTER
		18. GOOGLE
		19. LINKEDIN
		20.	PINTEREST
		21.	YOUTUBE
		22.	FOOTER CONTENT
	2.	CONFIRMATION FIELD
		1.	ENABLE CONFIRMATION EMAIL 
		2.	DISPLAY CONFIRMATION KEY
		3.	CONFIRMATION SUCCESS MESSAGE
		4.	CONFIRMATION FAILED MESSAGE
	3.	OWNER FIELD
		1.	SEND EMAIL TO OWNER
		2.	OWNER EMAIL
		3.	OWNER EMAIL SUBJECT
	4.	CUSTOMER FIELD
		1.	SEND EMAIL TO CUSTOMER
		2.	CUSTOMER EMAIL SUBJECT
	
*/
?>
	<?php
	/*=====================================
		1.	EDITOR
	=====================================*/?>
	<h2 class="head-parent no-margin-top icon-edit">
		<?php _e('Editor',PLUGIN_NAME) ?>
   		<i class="fa fa-caret-down"></i>
    	<i class="fa fa-caret-up"></i>   
    </h2>
	<div class="head-parent-content">
        <p><?php _e('You can easier to add and style some content on your email template with below Editor',PLUGIN_NAME) ?> </p>
        <p> 
        	<label for="email-editing-content"><?php _e('Field Content',PLUGIN_NAME) ?></label>
            <span>
                <select name="email-editing-content" id="email-editing-content">
                    <option value="restaurant_message"> <?php _e('Restaurant Message',PLUGIN_NAME) ?></option>
                    <option value="restaurant_information"><?php _e('Restaurant Information',PLUGIN_NAME) ?></option>
                    <option value="restaurant_policies"><?php _e('Restaurant Policies',PLUGIN_NAME) ?></option>
                    <option value="restaurant_footer"><?php _e('Footer Content',PLUGIN_NAME) ?></option>
                </select>
            </span>
            <br  />
            <span><input id="get-field-content" type="button" value="<?php _e('Get Content',PLUGIN_NAME) ?>" /></span>
            <span><input id="save-field-content" type="button" value="<?php _e('Save Content',PLUGIN_NAME) ?>" /></span>
        </p>
        <br  />
        <?php 
            $settings = array();
            $content = '';
            $editor_id = 'mycustomeditor';
            wp_editor( $content, $editor_id, $settings );
        ?>
        <br class="float-none height_20px" />
    </div>	
    
    
	
    <?php
	/*=====================================
		1.	GENERAL FIELD
	=====================================*/?>
    <h2 class="head-parent icon-global">
		<?php _e('GENERAL',PLUGIN_NAME) ?>
   		<i class="fa fa-caret-down"></i>
    	<i class="fa fa-caret-up"></i>   
    </h2>
    <div class="head-parent-content">
        <label for="restaurant_name"><?php _e('Restaurant Name',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_name" name="resto_all_setting[restaurant_name]" value="<?php echo $options['restaurant_name'] ?>" />
        
        <label for="restaurant_address"><?php _e('Restaurant Address',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_address" name="resto_all_setting[restaurant_address]" value="<?php echo $options['restaurant_address'] ?>" />
        
        <label for="restaurant_city"><?php _e('Restaurant City',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_city" name="resto_all_setting[restaurant_city]" value="<?php echo $options['restaurant_city'] ?>" />
        
        <label for="restaurant_state"><?php _e('Restaurant State',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_state" name="resto_all_setting[restaurant_state]" value="<?php echo $options['restaurant_state'] ?>" />
        
        <label for="restaurant_zipcode"><?php _e('Restaurant Zip Code',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_zipcode" name="resto_all_setting[restaurant_zipcode]" value="<?php echo $options['restaurant_zipcode'] ?>" />
        
        <label for="restaurant_phone"><?php _e('Restaurant Phone',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_phone" name="resto_all_setting[restaurant_phone]" value="<?php echo $options['restaurant_phone'] ?>" />
        
        <label for="restaurant_fax"><?php _e('Restaurant Fax',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_fax" name="resto_all_setting[restaurant_fax]" value="<?php echo $options['restaurant_fax'] ?>" />
        
        <label for="restaurant_email"><?php _e('Restaurant Email',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_email" name="resto_all_setting[restaurant_email]" value="<?php echo $options['restaurant_email'] ?>" />
        
        <label for="restaurant_website"><?php _e('Restaurant Website',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" placeholder="www.yourwebsite.com" type="text" id="restaurant_website" name="resto_all_setting[restaurant_website]" value="<?php echo $options['restaurant_website'] ?>" />
        
        
        <label for="restaurant_logo"><?php _e('Restaurant Logo',PLUGIN_NAME) ?></label>
        <div class="common-container">
            <input class="restaurant-image" type="hidden" id="restaurant_logo" name="resto_all_setting[restaurant_logo]" value="<?php echo $options['restaurant_logo'] ?>" />
            <img class="image-preview" id="restaurant-logo-image-preview" src="<?php echo $options['restaurant_logo'] ?>" alt="logo image"/>
            <input id="olr_upload_logo_button" type="button" value="<?php _e( 'Select Logo to Upload',PLUGIN_NAME) ?>" />
            <input id="olr_remove_logo_button" type="button" value="<?php _e( 'Remove Logo',PLUGIN_NAME) ?>" />
        </div>
        <p class="float-none"></p>
        
        <label for="restaurant_image"><?php _e('Restaurant Image',PLUGIN_NAME) ?></label>
        <div class="common-container">
            <input class="restaurant-image" type="hidden" id="restaurant_image" name="resto_all_setting[restaurant_image]" value="<?php echo $options['restaurant_image'] ?>" />
            <img class="image-preview" id="restaurant-logo-image-preview" src="<?php echo $options['restaurant_image'] ?>" alt="logo image"/>
            <input id="olr_upload_logo_button" type="button" value="<?php _e( 'Select Logo to Upload',PLUGIN_NAME) ?>" />
            <input id="olr_remove_logo_button" type="button" value="<?php _e( 'Remove Logo',PLUGIN_NAME) ?>" />
        </div>
        <p class="float-none"></p>
        
        
        <label for="restaurant_offer_link"><?php _e('Special Offer Link',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_offer_link" name="resto_all_setting[restaurant_offer_link]" value="<?php echo $options['restaurant_offer_link'] ?>" />
        
        <label for="restaurant_reservation_link"><?php _e('Reservation Link',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_reservation_link" name="resto_all_setting[restaurant_reservation_link]" value="<?php echo $options['restaurant_reservation_link'] ?>" />
        
        <label for="restaurant_message"><?php _e('Restaurant Message',PLUGIN_NAME) ?></label>
        <textarea class="email-medium-field" id="restaurant_message" name="resto_all_setting[restaurant_message]" rows="3" cols="32"><?php echo $options['restaurant_message'] ?></textarea>
        
        
        <label for="restaurant_information"><?php _e('Restaurant Information',PLUGIN_NAME) ?></label>
        <textarea class="email-medium-field"  id="restaurant_information" name="resto_all_setting[restaurant_information]" rows="3" cols="32"><?php echo $options['restaurant_information'] ?></textarea>
        
        
        <label for="restaurant_policies"><?php _e('Restaurant Policies',PLUGIN_NAME) ?></label>
        <textarea class="email-medium-field" id="restaurant_policies" name="resto_all_setting[restaurant_policies]" rows="3" cols="32"><?php echo $options['restaurant_policies'] ?></textarea>
        
        <h3>Social Media</h3>
        <label for="restaurant_facebook"><?php _e('Facebook',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_facebook" name="resto_all_setting[restaurant_facebook]" value="<?php echo $options['restaurant_facebook'] ?>" />
        
        
        <label for="restaurant_twitter"><?php _e('Twitter',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_twitter" name="resto_all_setting[restaurant_twitter]" value="<?php echo $options['restaurant_twitter'] ?>" />
        
        <label for="restaurant_google"><?php _e('Google',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_google" name="resto_all_setting[restaurant_google]" value="<?php echo $options['restaurant_google'] ?>" />
        
        <label for="restaurant_linkedin"><?php _e('LinkedIn',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_linkedin" name="resto_all_setting[restaurant_linkedin]" value="<?php echo $options['restaurant_linkedin'] ?>" />
        
        
        <label for="restaurant_pinterest"><?php _e('Pinterest',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_pinterest" name="resto_all_setting[restaurant_pinterest]" value="<?php echo $options['restaurant_pinterest'] ?>" />
        
        
        <label for="restaurant_youtube"><?php _e('Youtube',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="restaurant_youtube" name="resto_all_setting[restaurant_youtube]" value="<?php echo $options['restaurant_youtube'] ?>" />
        
        
        <label for="restaurant_footer"><?php _e('Footer Content',PLUGIN_NAME) ?></label>
        <textarea class="email-medium-field" id="restaurant_footer" name="resto_all_setting[restaurant_footer]" rows="3" cols="32"><?php echo $options['restaurant_footer'] ?></textarea>
        <br class="float-none height_20px" />
  	</div>  
    
    
    <?php
	/*=====================================
		2.	CONFIRMATION FIELD
	=====================================*/?>
	<h2 class="head-parent dashicons-before dashicons-email">
		<?php _e('Confirmation',PLUGIN_NAME) ?>
   		<i class="fa fa-caret-down"></i>
    	<i class="fa fa-caret-up"></i>   
    </h2>
    <div class="head-parent-content">
        <label for="confirmation_email"><?php _e('Enable Confirmation Email',PLUGIN_NAME) ?></label>
        <input type="checkbox" id="confirmation_email" name="resto_all_setting[confirmation_email]" value="1" <?php checked('1', $options['confirmation_email']) ?> />
        <p class="float-none"></p>
        <br />
        
        <label for="confirmation_key"><?php _e('Display Confirmation Key',PLUGIN_NAME) ?><br /><span class="sub-text"><?php _e('( on List of bookings )',PLUGIN_NAME) ?></span></label>
        <input type="checkbox" id="confirmation_key" name="resto_all_setting[confirmation_key]" value="1" <?php checked('1', $options['confirmation_key']) ?> />
        
        <p class="float-none"></p>
        <br />
        <label for="confirmation_success_message"><?php _e('Confirmation Success Message',PLUGIN_NAME) ?></label>
        <textarea class="email-medium-field" id="confirmation_success_message" name="resto_all_setting[confirmation_success_message]" rows="3" cols="32"><?php echo $options['confirmation_success_message'] ?></textarea>
        
        <label for="confirmation_failed_message"><?php _e('Confirmation Failed Message',PLUGIN_NAME) ?></label>
        <textarea class="email-medium-field" id="confirmation_failed_message" name="resto_all_setting[confirmation_failed_message]" rows="3" cols="32"><?php echo $options['confirmation_failed_message'] ?></textarea>
        <br class="float-none height_20px" />
    </div>
    
    
	<?php
	/*=====================================
		3.	OWNER FIELD
	=====================================*/?>
    <h2 class="head-parent dashicons-before dashicons-businessman">
		<?php _e('Restaurant Owner',PLUGIN_NAME) ?>
   		<i class="fa fa-caret-down"></i>
    	<i class="fa fa-caret-up"></i>   
    </h2>
    <div class="head-parent-content">
        <label for="email_to_owner"><?php _e('Send Email to Owner',PLUGIN_NAME); ?></label>
        <input type="checkbox" id="email_to_owner" name="resto_all_setting[email_to_owner]" value="1" <?php checked('1', $options['email_to_owner']) ?> />
        <p class="float-none"></p>
        
        <label for="owner_email"><?php _e('Email',PLUGIN_NAME) ?></label>
        <input class="website-medium-field" type="text" id="owner_email" name="resto_all_setting[owner_email]" value="<?php echo $options['owner_email'] ?>" />
        <p class="float-none"></p>
        
        <label for="owner_email_subject"><?php _e('Subject',PLUGIN_NAME) ?></label>
        <textarea class="email-medium-field" id="owner_email_subject" name="resto_all_setting[owner_email_subject]" rows="3" cols="32"><?php echo $options['owner_email_subject'] ?></textarea>
        <br class="float-none height_20px" />
    </div>
    
    
    
    
    <?php
	/*=====================================
		4.	CUSTOMER FIELD
	=====================================*/?>
    <h2 class="head-parent dashicons-before dashicons-groups">
		<?php _e('Restaurant Customer',PLUGIN_NAME) ?>
   		<i class="fa fa-caret-down"></i>
    	<i class="fa fa-caret-up"></i>   
    </h2>
    <div class="head-parent-content">
        <label for="email_to_customer"><?php _e('Send Email to Customer',PLUGIN_NAME); ?></label>
        <input type="checkbox" id="email_to_customer" name="resto_all_setting[email_to_customer]" value="1" <?php checked('1', $options['email_to_customer']) ?> />
        <p class="float-none"></p>
        
        <label for="customer_email_subject"><?php _e('Subject',PLUGIN_NAME) ?></label>
        <textarea class="email-medium-field" id="customer_email_subject" name="resto_all_setting[customer_email_subject]" rows="3" cols="32"><?php echo $options['customer_email_subject'] ?></textarea>
    </div>
    