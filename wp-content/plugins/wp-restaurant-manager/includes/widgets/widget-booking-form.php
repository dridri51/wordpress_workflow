<?php
/**
 * Actions and Filters
 *
 * Register any and all actions here. Nothing should actually be called
 * directly, the entire system will be based on these actions and hooks.
 */
 
/**
 * This is the class that you'll be working with. Duplicate this class as many times as you want. Make sure
 * to include an add_action call to each class, like the line above.
 *
 * @author Alessandro Tesoro
 * @version  1.0.0
 * @since  1.0.0
 *
 */
class WPRM_Booking_Form extends Empty_Widget_Abstract
{
	/**
	 * Widget settings
	 *
	 * Simply use the following field examples to create the WordPress Widget options that
	 * will display to administrators. These options can then be found in the $params
	 * variable within the widget method.
	 *
	 *
	 */
	protected $widget = array(
		// you can give it a name here, otherwise it will default
		// to the classes name. BTW, you should change the class
		// name each time you create a new widget. Just use find
		// and replace!
		'name' => '[WPRM] - Booking Form',
 
		// this description will display within the administrative widgets area
		// when a user is deciding which widget to use.
		'description' => 'Use the following widget to display the booking form.',
 
		// determines whether or not to use the sidebar _before and _after html
		'do_wrapper' => true,
 
		// determines whether or not to display the widgets title on the frontend
		'do_title'	=> true,
 
		// string : if you set a filename here, it will be loaded as the view
		// when using a file the following array will be given to the file :
		// array('widget'=>array(),'params'=>array(),'sidebar'=>array(),
		// alternatively, you can return an html string here that will be used
		'view' => false,
	
		// If you desire to change the size of the widget administrative options
		// area
		'width'	=> 350,
		'height' => 350,
	
		// Shortcode button row
		'buttonrow' => 4,
	
		// The image to use as a representation of your widget.
		// Whatever you place here will be used as the img src
		// so we have opted to use a basencoded image.
		'thumbnail' => '',
	
		/* The field options that you have available to you. Please
		 * contribute additional field options if you create any.
		 *
		 */
		'fields' => array(
			
			// You should always offer a widget title
			array(
				'name' => 'Title',
				'desc' => '',
				'id' => 'title',
				'type' => 'text',
				'default' => 'Booking Form'
			),

			array(
				'name' => 'Submit button label',
				'desc' => '',
				'id' => 'label',
				'type' => 'text',
				'default' => 'Check availability'
			),
			
		)
	);
 
	/**
	 * Widget HTML
	 *
	 * If you want to have an all inclusive single widget file, you can do so by
	 * dumping your css styles with base_encoded images along with all of your
	 * html string, right into this method.
	 *
	 * @param array $widget
	 * @param array $params
	 * @param array $sidebar
	 */
	function html($widget = array(), $params = array(), $sidebar = array()){	

		$js_dir  = WPRM_PLUGIN_URL . 'assets/js/';
		$css_dir = WPRM_PLUGIN_URL . 'assets/css/';

		wp_enqueue_script( 'wprm-picker-js' );

		//Determine if we have to load a translation
		if(wprm_get_option('calendar_language')):
			$the_language = wprm_get_option('calendar_language');
			wp_enqueue_script( 'wprm-picker-js-language', $js_dir . '/picker/translations/'.$the_language.'.js', array( 'jquery' ), WPRM_VERSION, true );
		endif;

		//Add Main Script to the end
		wp_enqueue_script( 'wprm-front-scripts' );

		//Add Main Css File
		wp_enqueue_style( 'wprm-front-css' );
		if(wprm_get_option('booking_style') == 'default'):
			wp_enqueue_style( 'wprm-picker-default-css' );
		elseif(wprm_get_option('booking_style') == 'classic'):
			wp_enqueue_style( 'wprm-picker-classic-css' );
		endif;

		echo do_shortcode( '[wprm_booking_form submit_label="'.$params['label'].'"]' );
		
	}
	
}