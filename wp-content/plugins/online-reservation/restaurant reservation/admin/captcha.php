<?php
/*
	TABLE OF CONTENTS ( CAPTCHA TAB )
	=======================================
	1.	ENABLE GOOGLE RECAPTCHA
	2.	PUBLIC KEY
	3.	PRIVATE KEY
	4.	THEME
	5.	CAPTCHA ERROR MESSAGE
*/
?>	

	<h2 class="head-parent no-margin-top icon-key">
		<?php _e('Get Public and Private Key',PLUGIN_NAME) ?>
   		<i class="fa fa-caret-down"></i>
    	<i class="fa fa-caret-up"></i>   
    </h2>
    <div class="head-parent-content">
        <p>You must <a href="https://www.google.com/recaptcha/admin#whyrecaptcha"><?php _e('Signup',PLUGIN_NAME) ?></a> , <?php _e('to get Public and Private Key',PLUGIN_NAME) ?></p>
        <p><strong><?php _e('Steps',PLUGIN_NAME) ?></strong></p>
        <p> <?php _e('1. Sign Up',PLUGIN_NAME) ?></p>
        <p> <?php _e('2. On My Account Tab , enter your site Domain , click create',PLUGIN_NAME) ?></p>
        <p> <?php _e('3. Click on your domain , you will see public and private key',PLUGIN_NAME) ?></p>
        <br class="float-none height_20px" />
	</div>
    
	
    <h2 class="head-parent icon-captcha">
		<?php _e('Google Recaptcha',PLUGIN_NAME) ?>
   		<i class="fa fa-caret-down"></i>
    	<i class="fa fa-caret-up"></i>   
    </h2>
    <div class="head-parent-content">
        <label for="enable_captcha"><?php _e('Enable Google Recaptcha',PLUGIN_NAME) ?></label>
        <input type="checkbox" id="enable_captcha" name="resto_all_setting[enable_captcha]" value="1" <?php checked('1', $options['enable_captcha']) ?> />
        <p class="float-none"></p>
        <br />
        
        <label for="public_key"><?php _e('Public Key',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="public_key" name="resto_all_setting[public_key]" value="<?php echo $options['public_key'] ?>" />
        
        <label for="private_key"><?php _e('Private Key',PLUGIN_NAME) ?></label>
        <input class="email-medium-field" type="text" id="private_key" name="resto_all_setting[private_key]" value="<?php echo $options['private_key'] ?>" />
        <p class="float-none"></p>
        
        <label for="captcha_theme" ><?php _e('Early Bookings',PLUGIN_NAME); ?></label>
        <select name="resto_all_setting[captcha_theme]" id="captcha_theme">
            <option value="red" <?php selected( $options['captcha_theme'], 'red' ); ?>><?php _e('Red',PLUGIN_NAME); ?></option>
            <option value="white" <?php selected( $options['captcha_theme'], 'white' ); ?>><?php _e('White',PLUGIN_NAME); ?></option>
            <option value="blackglass" <?php selected( $options['captcha_theme'], 'blackglass' ); ?>><?php _e('Black Glass',PLUGIN_NAME); ?></option>
            <option value="clean" <?php selected( $options['captcha_theme'], 'clean' ); ?>><?php _e('Clean',PLUGIN_NAME); ?></option>
        </select>
        <p class="float-none"></p>
        
        <label for="captcha_error_message"><?php _e('Captcha Error Message',PLUGIN_NAME) ?></label>
        <textarea class="email-medium-field" id="captcha_error_message" name="resto_all_setting[captcha_error_message]" rows="3" cols="32"><?php echo $options['captcha_error_message'] ?></textarea>
    </div>