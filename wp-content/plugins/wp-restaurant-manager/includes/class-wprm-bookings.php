<?php
/**
 * Bookings Manager
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPRM_Bookings_Manager Class
 * This class handled all the major frontend and backend
 * bookings actions and functions.
 *
 * @since 1.0.0
 */
class WPRM_Bookings_Manager {

	private $date;
	private $date_hidden;
	private $time;
	private $fullname;
	private $phone;
	private $email;
	private $party;
	private $message;
	var $booking_form_errors;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_shortcode( 'wprm_booking_form', array($this, 'wprm_booking_form_shortcode'));
		add_action('init', array($this, 'wprm_booking_form_validation'));
	}

	/**
	 * List of all the booking fields for the form.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return $booking_fields array
	 */
	public function wprm_bookigs_fields() {
		
		$booking_fields = (array) apply_filters( 'wprm_booking_form_fields', array(
			
			'date' => array(
				'type'		=> 'text',
				'label'     => __( 'Date', 'wprm' ),
				'name'		=> 'date',
				'desc'		=> __( 'Select the date.', 'wprm' ),
				'value'		=> '',
				'required'	=> apply_filters( 'wprm_booking_field_date_required', true ),
			),
			'time' => array(
				'type'		=> 'text',
				'label'     => __( 'Time of arrival', 'wprm' ),
				'name'		=> 'time',
				'desc'		=> __( 'Select the reservation time.', 'wprm' ),
				'value'		=> '',
				'required'	=> apply_filters( 'wprm_booking_field_time_required', true ),
			),
			'clearfix' => array(
				'type'		=> 'html',
				'name'		=> 'clearfix',
				'value'		=> '<div class="wprm-clearfix"></div>',
				'required'	=> false
			),
			'fullname' => array(
				'type'		=> 'text',
				'label'     => __( 'Full name', 'wprm' ),
				'name'		=> 'fullname',
				'desc'		=> __( 'Enter your full name.', 'wprm' ),
				'value'		=> '',
				'required'	=> apply_filters( 'wprm_booking_field_name_required', true ),
			),
			'phone' => array(
				'type'		=> 'text',
				'label'     => __( 'Phone number', 'wprm' ),
				'name'		=> 'phone',
				'desc'		=> __( 'Enter your phone number.', 'wprm' ),
				'value'		=> '',
				'required'	=> apply_filters( 'wprm_booking_field_phone_required', true ),
			),
			'email' => array(
				'type'		=> 'email',
				'label'     => __( 'Email address', 'wprm' ),
				'name'		=> 'email',
				'desc'		=> __( 'Enter your email address. We will send a confirmation here.', 'wprm' ),
				'value'		=> '',
				'required'	=> apply_filters( 'wprm_booking_field_email_required', true ),
				'placeholder' => __( 'Enter your email address.', 'wprm' ),
			),
			'party' => array(
				'type'		=> 'select',
				'label'     => __( 'How many people?', 'wprm' ),
				'name'		=> 'party',
				'desc'		=> __( 'Select the number of guests.', 'wprm' ),
				'options'		=> $this->wprm_get_party_amount(),
				'required' 	=> apply_filters( 'wprm_booking_field_party_required', false )
			),
			'message' => array(
				'type'		=> 'textarea',
				'label'     => __( 'Custom message', 'wprm' ),
				'name'		=> 'message',
				'desc'		=> __( 'Please let us know if you have any extra requirements.', 'wprm' ),
				'value'		=> '',
				'required'	=> apply_filters( 'wprm_booking_field_message_required', false ),
			),

		) );

		//Remove the clearfix html hack if the layout
		//for the date and time field is not set to 2
		//columns
		if(!wprm_get_option('adjust_date_time'))
			unset($booking_fields['clearfix']);

		return $booking_fields;

	}

	/**
	 * Adds the fields to the booking form.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function wprm_get_bookigs_fields() {
		
		// Let's get all the fields
		$booking_fields = $this->wprm_bookigs_fields();
		$fields = null;

		// Cycle through each field and show proper field type
		foreach ($booking_fields as $field ) {

			switch ($field['type']) {
			    case 'text':
			        $fields .= WPRM()->html->text( array(
			        				'name'         	=> $field['name'],
			        				'label' 		=> $field['label'],
									'value'        	=> $field['value'],
									'desc'         	=> $field['desc'],
									'placeholder'  	=> isset( $field['placeholder'] )  ? $field['placeholder']  : null
								) );
			        break;
			    case 'email':
			        $fields .= WPRM()->html->email( array(
			        				'name'         	=> $field['name'],
			        				'label' 		=> $field['label'],
									'value'        	=> $field['value'],
									'desc'         	=> $field['desc'],
									'placeholder'  	=> isset( $field['placeholder'] )  ? $field['placeholder']  : null
								) );
			        break;
			    case 'checkbox':
			        $fields .= WPRM()->html->checkbox( array(
			        				'name'         	=> $field['name'],
			        				'label' 		=> $field['label'],
								) );
			        break;
			    case 'select':
			        $fields .= WPRM()->html->select( array(
			        				'name'         	=> $field['name'],
			        				'label' 		=> $field['label'],
									'desc'         	=> $field['desc'],
									'options'		=> $field['options'],
									'show_option_all' => null,
									'show_option_none' => null
								) );
			        break;
			    case 'textarea':
			        $fields .= WPRM()->html->textarea( array(
			        				'name'         	=> $field['name'],
			        				'label' 		=> $field['label'],
									'value'        	=> $field['value'],
									'desc'         	=> $field['desc'],
								) );
			        break;
			    case 'html':
			        $fields .= WPRM()->html->html( array(
									'value'        	=> $field['value'],
								) );
			        break;
			   
			}

		}

		// Output fields.
		return $fields;

	}

	/**
	 * Handles the display of the party people field options.
	 * Interacts with the plugin options panel to manage options of this field.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return array $party_options holds all the options from the plugin settings panel
	 * @todo finish integration with options panel
	 */
	public function wprm_get_party_amount() {

		$party_options = wprm_get_party_sizes();
		$party_sizes = array();
		
		foreach ($party_options as $table => $value) {
			$party_sizes[$value['size']] = $value['size'];
		}

		return apply_filters( 'wprm_party_options', $party_sizes );

	}

	/**
	 * Get the value of a posted field
	 * @param  string $key
	 * @return string
	 */
	protected static function get_posted_field( $key ) {
		
		$string = null;
		if(isset($key))
			$string = sanitize_text_field( trim( stripslashes( $key ) ) );
		return $string;

	}

	/**
	 * Get the value of a posted textarea field
	 * @param  string $key
	 * @return string
	 */
	protected static function get_posted_textarea_field( $key ) {
		$string = null;
		if(isset($key))
			$string = wp_kses_post( trim( stripslashes( $key ) ) );
		return $string;
	}

	/**
	 * Get the value of a posted email field
	 * @param  string $key
	 * @return string
	 */
	protected static function get_posted_email_field( $key ) {
		$string = null;
		if(isset($key) && is_email($key))
			$string = $key;
		return $string;
	}

	/**
	 * Prints booking form.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param string $submit_label Text of the submit button
	 * @param string $submit_wait_label The text used for the ajax validation button update
	 * @return void
	 */
	public function wprm_booking_form($submit_label, $submit_wait_label) {

		?>
		<form id="wprm-booking-form" <?php echo wprm_booking_class(); ?> name="wprm-booking-form" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
			<?php echo self::wprm_get_bookigs_fields(); ?>
			<input type="text" name="content" id="content" value="" class="wprm-hnp" />
			<input type="hidden" name="wprm_booking_form_submit" id="wprm_booking_form_submit" value="true" />
			<?php echo wp_nonce_field('wprm_submit_booking_form'); ?>
			<input type="submit" name="wprm-booking-submit-button" value="<?php echo $submit_label; ?>" data-wait-text="<?php echo $submit_wait_label ?>" tabindex="10" class="button btn wprm-booking-submit-button"/>
		</form>
		<?php
	}

	/**
	 * Validates the booking form on init.
	 * Allows us to modify the output before it's sent to the header.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function wprm_booking_form_validation() {

		if (isset($_POST['wprm_booking_form_submit']) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'wprm_submit_booking_form' ) ) {

			$errors = new WP_Error();
			$booking_fields = $this->wprm_bookigs_fields();

			// Cycle through each field 
			// Check which fields are required.
			foreach ($booking_fields as $field ) {

				if(isset($_POST[ $field['name'] ]) && empty($_POST[$field['name']]) && $field['required'] == true) {
					 $errors->add( $field['name'].'_missing', sprintf( __('%s is a required field.','wprm'), $field['label'] ));
				}

			}

			// Checking for spam honeypot method.
			// "content" has been set on purpose so bots will most likely trigger it.
			if (isset($_POST['content']) && $_POST['content'] !== '' ) {
				$errors->add( 'cheater', __('Sorry, this field should not be filled. Are you trying to cheat?','wprm'));
			}

			// Process Early Bookings
			if(wprm_get_option('early_bookings')) :
				
				$days = wprm_get_option('early_bookings');
				if( strtotime($this->get_posted_field($_POST['wprm__date__hidden'])) > strtotime('+'.$days.' day') ) {
				    $errors->add( 'invalid_date', sprintf( __('Sorry, bookings can not be made more than %s days in advance.','wprm'), $days ));
				}

			endif;

			// Process Late Bookings
			if(wprm_get_option('late_bookings') && $this->get_posted_field($_POST['wprm__time__hidden'])):
				
				$date = new DateTime($this->get_posted_field($_POST['wprm__date__hidden']));
				$time = new DateTime($this->get_posted_field($_POST['wprm__time__hidden']));

				if ( is_object( $time ) ) {

					$request = new DateTime( $date->format( 'Y-m-d' ) . ' ' . $time->format( 'H:i:s' ) );
					$get_late_bookings = wprm_get_option('late_bookings');
					
					$get_late_bookings_seconds = $get_late_bookings * 60; // Late bookings allowance in seconds
					if ( $request->format( 'U' ) < ( current_time( 'timestamp' ) + $get_late_bookings_seconds ) ) {
						if ( $get_late_bookings >= 1440 ) {
							$late_bookings_message = sprintf( __( 'Sorry, bookings must be made more than %s days in advance.', 'wprm' ), $get_late_bookings / 1440 );
						} elseif ( $get_late_bookings >= 60 ) {
							$late_bookings_message = sprintf( __( 'Sorry, bookings must be made more than %s hours in advance.', 'wprm' ), $get_late_bookings / 60 );
						} else {
							$late_bookings_message = sprintf( __( 'Sorry, bookings must be made more than %s minutes in advance.', 'wprm' ), $get_late_bookings );
						}
						$errors->add( 'invalid_late_time', $late_bookings_message);
					}

				}

			endif;

			// If not errors are detected
			// let's proceed with saving the booking.
			if (empty($errors->errors)) {

				// Get default booking settings
				$booking_data = apply_filters( 'wprm_booking_form_save_booking_data', array(
					'post_title'     => 'Booking',
					'post_content'   => '',
					'post_status'    => wprm_get_option('booking_default_status'),
					'post_type'      => 'wprm_reservations',
					'comment_status' => 'closed'
				) );

				// Let's save the values of the default fields
				$this->date = $this->get_posted_field($_POST['date']);
				$this->date_hidden = $this->get_posted_field($_POST['wprm__date__hidden']);
				$this->time = $this->get_posted_field($_POST['time']);
		        $this->fullname = $this->get_posted_field($_POST['fullname']);
		        $this->phone = $this->get_posted_field($_POST['phone']);
		        $this->email = $this->get_posted_email_field($_POST['email']);
		        $this->party = $this->get_posted_field($_POST['party']);
		        $this->message = $this->get_posted_textarea_field($_POST['message']);

		        //Store default fields into array
				$default_fields = array(
					'date' => $this->date,
					'date_hidden' => $this->date_hidden,
					'time' => $this->time,
					'fullname' => $this->fullname,
					'phone' => $this->phone,
					'email' => $this->email,
					'party' => $this->party,
					'message' => $this->message
				);

				// Retrieves extra fields added through filter
				// Excludes default ones
				$extra_fields = self::wprm_bookigs_fields();
					unset($extra_fields['date']);
					unset($extra_fields['date_hidden']);
					unset($extra_fields['time']);
					unset($extra_fields['fullname']);
					unset($extra_fields['phone']);
					unset($extra_fields['email']);
					unset($extra_fields['party']);
					unset($extra_fields['message']);

				$this->wprm_save_booking( 'Booking', $default_fields, $extra_fields );

            	//Decide wether to redirect to another page
            	//or show success message
            	$url = null;
				if(wprm_get_option('booking_confirmation_type') == 'message') :

					$url = add_query_arg('submitted', 'true', get_permalink());

				elseif(wprm_get_option('booking_confirmation_type') == 'redirect') :

					$url = get_permalink(wprm_get_option('after_booking_page'));

				endif;

                wp_redirect($url);
                exit;

            // The booking contains erros, let's save them for later usage.
            } else {

                $this->booking_form_errors = $errors;

            }

	    }

	}

	/**
	 * Create the booking from posted data
	 *
	 * @param  string $post_title
	 * @param  string $post_content
	 * @param  string $status
	 * @return void
	 */
	protected static function wprm_save_booking( $post_title, $default_fields, $extra_fields ) {
		
		$default_fields = apply_filters( 'wprm_save_booking_get_default_fields', $default_fields );
		$extra_fields = apply_filters( 'wprm_save_booking_get_extra_fields', $extra_fields );

		$booking_data = apply_filters( 'wprm_booking_form_save_booking_data', array(
			'post_title'     => $post_title,
			'post_content'   => '',
			'post_status'    => wprm_get_option('booking_default_status'),
			'post_type'      => 'wprm_reservations',
			'comment_status' => 'closed'
		) );

		// Insert booking into database
		$booking_id = wp_insert_post( $booking_data );

		// Add additional information to the booking
		update_post_meta($booking_id, '_wprm_reservation_date', strtotime($default_fields['date_hidden']));
		update_post_meta($booking_id, '_wprm_reservation_time', $default_fields['time']); 
		update_post_meta($booking_id, '_wprm_reservation_name', $default_fields['fullname']);
		update_post_meta($booking_id, '_wprm_reservation_phone_number', $default_fields['phone']);
		update_post_meta($booking_id, '_wprm_reservation_email', $default_fields['email']);
		update_post_meta($booking_id, '_wprm_reservation_size', $default_fields['party']);
		update_post_meta($booking_id, '_wprm_reservation_message', $default_fields['message']);

		// Modify reservation post title with it's ID
		$the_booking_name = apply_filters( 'wprm_default_booking_name', sprintf( __('Reservation #%s', 'wprm'), $booking_id ), $booking_id );
		wp_update_post( array('ID' => $booking_id, 'post_title' => $the_booking_name ));

		// Add Ability to manipulate extra fields after submission
		// useful if you wish to add extra fields and save their data.
		do_action( 'wprm_save_booking_after_submission', $booking_id, $extra_fields );

	}

	/**
	 * Displays the errors inside the shortcode.
	 *
	 * @access private
	 * @since 1.0.0
	 * @return void
	 * @param $errors WP_Error Class object
	 */
	private function wprm_booking_form_display_errors($errors) {

		// Validates the submission and show messages if needed
		// grabs the error messages and cycle through each error
		if ( is_wp_error($this->booking_form_errors) && !empty($this->booking_form_errors->errors)) {
		        
			// Let's get the errors
			$error_mesages = $this->booking_form_errors->get_error_messages();
			$error_messages_class = apply_filters( 'wprm_booking_form_single_error_message_class', 'wprm-single-error-message' );

		    // Let's cycle through the errors
		    echo '<div class="wprm-booking-form-errors-wrapper" id="wprm-booking-form-errors-wrapper">';
			   	foreach ($error_mesages as $message) {
					echo '<div class="'.$error_messages_class.'">';
						echo '<strong>'.$message.'</strong>';
					echo '</div>';	    
			   	}
			echo '</div>';

		} 

		if (isset($_GET['submitted']) && $_GET['submitted'] == true) {

			$success_message = wprm_get_option('success_message');
			$success_messages_class = apply_filters( 'wprm_booking_form_success_message_class', 'wprm-success' );

			// Let's cycle through the errors
		    echo '<div class="'.$success_messages_class.'" id="wprm-booking-form-success-wrapper">';
		    	echo $success_message;
			echo '</div>';

		}
	}

	/**
	 * Booking Form Shortcode
	 *
	 * @access public
	 * @since  1.0.0
	 * @return shortcode content
	 */
	public function wprm_booking_form_shortcode( $atts, $content=null ) {
		extract( shortcode_atts( array(
			'title' => '',
	        'submit_label' => __('Check availability','wprm'),
	        'submit_wait_label' => __('Checking availability...','wprm'),
		), $atts ) );

		ob_start();
 	
        $this->wprm_booking_form_display_errors($this->booking_form_errors);
        $this->wprm_booking_form($submit_label, $submit_wait_label);

        return ob_get_clean();

	}

}
new WPRM_Bookings_Manager;