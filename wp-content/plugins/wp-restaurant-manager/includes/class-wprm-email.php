<?php
/**
 * Handles all emails communications.
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPRM_Email Class
 * @since 1.0.0
 */
class WPRM_Email {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action('wprm_save_booking_after_submission', array($this, 'wprm_send_email_to_admin'));
		add_action('wprm_save_booking_after_submission', array($this, 'wprm_send_email_to_user_request'));
		if( !is_admin() && wprm_get_option('booking_default_status') == 'publish' ) :
			add_action('wprm_save_booking_after_submission', array($this, 'wprm_send_email_to_user_confirmation'));
		endif;
		add_action('reject_wprm_reservations', array($this, 'wprm_send_email_to_user_rejection'));
	}

	/**
	 * Sends an email notification to the admin.
	 *
	 * @since 1.0.0
	 * @return void
	 * @param $booking_id 
	 */
	public function wprm_send_email_to_admin($booking_id) {
		if(wprm_notify_admin()) {
			wprm_send_admin_notification($booking_id);
		}
	}

	/**
	 * Sends an email notification to the user upon booking request,
	 * if automatic approval of bookings are disabled.
	 *
	 * @since 1.0.0
	 * @return void
	 * @param $booking_id 
	 */
	public function wprm_send_email_to_user_request($booking_id) {
		if(!wprm_auto_booking_approval() && wprm_get_option('mail_user_booking_received')) {
			wprm_send_user_notification_request($booking_id);
		}
	}

	/**
	 * Sends an email notification to the user upon booking confirmation,
	 *
	 * @since 1.0.0
	 * @return void
	 * @param $booking_id 
	 */
	public static function wprm_send_email_to_user_confirmation($booking_id) {
		if(wprm_get_option('mail_user_booking_success')) {
			wprm_send_user_notification_confirmation($booking_id);
		}
	}

	/**
	 * Sends an email notification to the user upon booking rejection.
	 *
	 * @since 1.0.0
	 * @return void
	 * @param $booking_id 
	 */
	public function wprm_send_email_to_user_rejection($booking_id) {
		if(wprm_get_option('mail_user_booking_rejected')) {
			wprm_send_user_notification_rejection($booking_id);
		}
	}

}
new WPRM_Email();