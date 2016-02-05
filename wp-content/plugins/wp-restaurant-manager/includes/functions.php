<?php
/**
 * Main Functions
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'wprm_get_reservation_status' ) ) :
/**
 * Get status of a reservation
 *
 * @since 1.0.0
 * @param [$post_id] contains id number of the reservation
 * @return string 
 */
function wprm_get_reservation_status($post_id) {
	$status = get_post_status( $post_id );
	$reservation_status = null;

	if ( $status == 'publish' ) {
		$reservation_status = __('Approved','wprm');
	} else if ( $status == 'pending' ) {
		$reservation_status = __('Pending','wprm');
	} else if ( $status == 'reject' ) {
		$reservation_status = __('Rejected','wprm');
	} else if ( $status == 'closed' ) {
		$reservation_status = __('Closed','wprm');
	}

	return '<span class="'.$status.'">'.$reservation_status.'</span>';
}
endif;

if ( ! function_exists( 'wprm_get_reservations_post_statuses' ) ) :
/**
 * Get post statuses used for reservations
 *
 * @access public
 * @return array
 */
function wprm_get_reservations_post_statuses() {
	return apply_filters( 'wprm_reservations_post_statuses', array(
		'publish'       => _x( 'Active', 'post status', 'wprm' ),
		'pending'       => _x( 'Pending approval', 'post status', 'wprm' ),
		'rejected'		=> _x( 'Rejected', 'post status', 'wprm' ),
		'closed'		=> _x( 'Closed', 'post status', 'wprm' ),
	) );
}
endif;

if ( ! function_exists( 'wprm_booking_class' ) ) :
/**
 * Display the classes for the boking form.
 *
 * @since 1.0.0
 *
 * @param string|array $class One or more classes to add to the class list.
 */
function wprm_booking_class( $class = '' ) {
	// Separates classes with a single space, collates classes for form element
	return 'class="' . join( ' ', wprm_get_booking_class( $class ) ) . '"';
}
endif;

if ( ! function_exists( 'wprm_get_booking_class' ) ) :
/**
 * Retrieve the classes for the booking form element as an array.
 *
 * @since 1.0.0
 *
 * @param string|array $class One or more classes to add to the class list.
 * @return array Array of classes.
 */
function wprm_get_booking_class( $class = '' ) {

	$classes = array('wprm-booking-form');

	// Let's modify the style of the form
	// based on the selected options
	if(wprm_get_option('form_style_extended'))
		$classes[] = 'extended';

	if(wprm_get_option('adjust_picker'))
		$classes[] = 'adjusted';

	if(wprm_get_option('adjust_date_time'))
		$classes[] = 'split_column';

	if ( ! empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );

	/**
	 * Filter the list of CSS booking form classes for the current post or page.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $classes An array of form classes.
	 * @param string $class   A comma-separated list of additional classes added to the form.
	 */
	return apply_filters( 'wprm_booking_class', $classes, $class );
}
endif;

if ( ! function_exists( 'wprm_get_default_admin_notification_email' ) ) :
/**
 * Retrieve the default admin notification email.
 *
 * @since 1.0.0
 * @return string $message the content of the email.
 */
function wprm_get_default_admin_notification_email() {

	global $wprm_options;

	$default_email_body = __( 'A new booking request has been made at: ', 'wprm' ) . ' {sitename}' . "\n";
	$default_email_body .= __( 'Name: ', 'wprm' ) . ' {fullname}' . "\n";
	$default_email_body .= __( 'Party size: ', 'wprm' ) . ' {party}' . "\n";
	$default_email_body .= __( 'Date: ', 'wprm' ) . ' {date} '.__('at','wprm').' {time}' . "\n\n";
	$default_email_body .= __( 'Manage this booking: ', 'wprm' ) . ' {view_booking} | {approve_booking} | {reject_booking}' . "\n\n";
	$default_email_body .= __( 'View all bookings: ', 'wprm' ) . '{all_bookings}';

	$message = ( isset( $wprm_options['admin_notification_content'] ) && !empty( $wprm_options['admin_notification_content'] ) ) ? $wprm_options['admin_notification_content'] : $default_email_body;

	return $message;

}
endif;

if ( ! function_exists( 'wprm_get_default_user_request_notification_email' ) ) :
/**
 * Retrieve the default user notification email.
 *
 * @since 1.0.0
 * @return string $message the content of the email.
 */
function wprm_get_default_user_request_notification_email() {

	global $wprm_options;

	$default_email_body = __( 'Dear ', 'wprm' ) . ' {fullname},' . "\n\n";
	$default_email_body .= __( 'Your booking request, <b>is waiting to be confirmed</b>. You will receive another email when the status of your booking is updated.', 'wprm' ) . "\n\n";
	$default_email_body .= __( 'Regards,', 'wprm' ) . "\n";
	$default_email_body .= sprintf(__( '%s.', 'wprm' ), get_option( 'blogname' )) . "\n";

	$message = ( isset( $wprm_options['user_booking_requested_email'] ) && !empty( $wprm_options['user_booking_requested_email'] ) ) ? $wprm_options['user_booking_requested_email'] : $default_email_body;

	return $message;

}
endif;

if ( ! function_exists( 'wprm_get_default_user_confirmed_notification_email' ) ) :
/**
 * Retrieve the default user confirmation email.
 *
 * @since 1.0.0
 * @return string $message the content of the email.
 */
function wprm_get_default_user_confirmed_notification_email() {

	global $wprm_options;

	$default_email_body = __( 'Dear ', 'wprm' ) . ' {fullname},' . "\n\n";
	$default_email_body .= __( 'Thank you for choosing {sitename}. We are delighted to confirm your reservation for {party} on {date} at {time}.', 'wprm' ) . "\n\n";
	$default_email_body .= __( 'You added the following message to the booking:', 'wprm' ) . "\n";
	$default_email_body .= __( '"{message}"', 'wprm' ) . "\n";
	$default_email_body .= __( 'We will do our best to accommodate your request.', 'wprm' ) . "\n\n";
	$default_email_body .= __( 'Regards,', 'wprm' ) . "\n";
	$default_email_body .= sprintf(__( '%s.', 'wprm' ), get_option( 'blogname' )) . "\n";

	$message = ( isset( $wprm_options['user_booking_confirmed_email'] ) && !empty( $wprm_options['user_booking_confirmed_email'] ) ) ? $wprm_options['user_booking_confirmed_email'] : $default_email_body;

	return $message;

}
endif;

if ( ! function_exists( 'wprm_get_default_user_rejected_notification_email' ) ) :
/**
 * Retrieve the default user rejected email.
 *
 * @since 1.0.0
 * @return string $message the content of the email.
 */
function wprm_get_default_user_rejected_notification_email() {

	global $wprm_options;

	$default_email_body = __( 'Dear ', 'wprm' ) . ' {fullname},' . "\n\n";
	$default_email_body .= __( 'Thank you for choosing {sitename}. Unfortunately we are not able to accommodate your reservation for {party} on {date} at {time}.', 'wprm' ) . "\n\n";
	$default_email_body .= __( 'Should you have any further question, please do not hesitate to contact us.', 'wprm' ) . "\n\n";
	$default_email_body .= __( 'Regards,', 'wprm' ) . "\n";
	$default_email_body .= sprintf(__( '%s.', 'wprm' ), get_option( 'blogname' )) . "\n";

	$message = ( isset( $wprm_options['user_booking_rejected_email'] ) && !empty( $wprm_options['user_booking_rejected_email'] ) ) ? $wprm_options['user_booking_rejected_email'] : $default_email_body;

	return $message;

}
endif;

if ( ! function_exists( 'wprm_notify_admin' ) ) :
/**
 * Checkes wether admin notifications are enabled.
 *
 * @since 1.0.0
 * @return string $retval.
 */
function wprm_notify_admin() {

	global $wprm_options;
	$retval = isset( $wprm_options['email_site_admin'] );

	return $retval;

}
endif;

if ( ! function_exists( 'wprm_auto_booking_approval' ) ) :
/**
 * Checkes wether booking requests are automatically approved.
 *
 * @since 1.0.0
 * @return string $retval.
 */
function wprm_auto_booking_approval() {

	$status = null;

	if(wprm_get_option('booking_default_status') == 'publish') {
		$status = true;
	} else {
		$status = false;
	}

	return $status;

}
endif;

if ( ! function_exists( 'wprm_get_booking_email' ) ) :
/**
 * Retrieve email address of the customer.
 *
 * @since 1.0.0
 * @return string $email.
 */
function wprm_get_booking_email($booking_id) {

	return get_post_meta( $booking_id, '_wprm_reservation_email', true );

}
endif;

if ( ! function_exists( 'wprm_send_admin_notification' ) ) :
/**
 * Send admin a notification upon booking request.
 *
 * @since 1.0.0
 * @return void
 */
function wprm_send_admin_notification( $booking_id = 0 ) {

	global $wprm_options;

	$email = apply_filters( 'wprm_admin_booking_notification_sendto_mail', get_option('admin_email') );

	$subject = apply_filters( 'wprm_admin_booking_notification_subject', sprintf( __('New Booking Request #%s','wprm'), $booking_id ) );

	$from_name = isset( $wprm_options['mail_from_name'] ) ? $wprm_options['mail_from_name'] : get_bloginfo('name');
	$from_name = apply_filters( 'wprm_booking_from_name', $from_name, $booking_id );

	$from_email = isset( $wprm_options['mail_from_address'] ) ? $wprm_options['mail_from_address'] : get_option('admin_email');
	$from_email = apply_filters( 'wprm_booking_from_address', $from_email, $booking_id );

	$message = wpautop($wprm_options['admin_notification_content']);
	$message = wprm_do_email_tags( $message, $booking_id );

	$headers = "From: " . stripslashes_deep( html_entity_decode( $from_name, ENT_COMPAT, 'UTF-8' ) ) . " <$from_email>\r\n";
	$headers .= "Reply-To: ". $from_email . "\r\n";
	$headers .= "Content-Type: text/html; charset=utf-8\r\n";
	$headers = apply_filters( 'wprm_admin_booking_notification_headers', $headers, $booking_id);

	wp_mail( $email, $subject, $message, $headers );

}
endif;

if ( ! function_exists( 'wprm_send_user_notification_request' ) ) :
/**
 * Send the user a notification upon booking request.
 *
 * @since 1.0.0
 * @return void
 */
function wprm_send_user_notification_request( $booking_id = 0 ) {

	$email = wprm_get_booking_email( $booking_id );

	$subject = wprm_do_email_tags(wprm_get_option( 'user_booking_requested_email_subject' ), $booking_id);

	$from_name = isset( $wprm_options['mail_from_name'] ) ? $wprm_options['mail_from_name'] : get_bloginfo('name');
	$from_name = apply_filters( 'wprm_booking_from_name', $from_name, $booking_id );

	$from_email = isset( $wprm_options['mail_from_address'] ) ? $wprm_options['mail_from_address'] : get_option('admin_email');
	$from_email = apply_filters( 'wprm_booking_from_address', $from_email, $booking_id );

	$message = wpautop(wprm_get_option('user_booking_requested_email'));
	$message = wprm_do_email_tags( $message, $booking_id );

	$headers = "From: " . stripslashes_deep( html_entity_decode( $from_name, ENT_COMPAT, 'UTF-8' ) ) . " <$from_email>\r\n";
	$headers .= "Reply-To: ". $from_email . "\r\n";
	$headers .= "Content-Type: text/html; charset=utf-8\r\n";
	$headers = apply_filters( 'wprm_user_booking_notification_request_headers', $headers, $booking_id);

	wp_mail( $email, $subject, $message, $headers );

}
endif;

if ( ! function_exists( 'wprm_send_user_notification_confirmation' ) ) :
/**
 * Send the user a notification upon booking confirmation.
 *
 * @since 1.0.0
 * @return void
 */
function wprm_send_user_notification_confirmation( $booking_id = 0 ) {

	$email = wprm_get_booking_email( $booking_id );

	$subject = wprm_do_email_tags(wprm_get_option( 'user_booking_confirmed_email_subject' ), $booking_id);

	$from_name = isset( $wprm_options['mail_from_name'] ) ? $wprm_options['mail_from_name'] : get_bloginfo('name');
	$from_name = apply_filters( 'wprm_booking_from_name', $from_name, $booking_id );

	$from_email = isset( $wprm_options['mail_from_address'] ) ? $wprm_options['mail_from_address'] : get_option('admin_email');
	$from_email = apply_filters( 'wprm_booking_from_address', $from_email, $booking_id );

	$message = wpautop(wprm_get_option('user_booking_confirmed_email'));
	$message = wprm_do_email_tags( $message, $booking_id );

	$headers = "From: " . stripslashes_deep( html_entity_decode( $from_name, ENT_COMPAT, 'UTF-8' ) ) . " <$from_email>\r\n";
	$headers .= "Reply-To: ". $from_email . "\r\n";
	$headers .= "Content-Type: text/html; charset=utf-8\r\n";
	$headers = apply_filters( 'wprm_user_booking_notification_confirmed_headers', $headers, $booking_id);

	wp_mail( $email, $subject, $message, $headers );

}
endif;

if ( ! function_exists( 'wprm_send_user_notification_rejection' ) ) :
/**
 * Send the user a notification upon booking rejection.
 *
 * @since 1.0.0
 * @return void
 */
function wprm_send_user_notification_rejection( $booking_id = 0 ) {

	$email = wprm_get_booking_email( $booking_id );

	$subject = wprm_do_email_tags(wprm_get_option( 'user_booking_rejected_email_subject' ), $booking_id);

	$from_name = isset( $wprm_options['mail_from_name'] ) ? $wprm_options['mail_from_name'] : get_bloginfo('name');
	$from_name = apply_filters( 'wprm_booking_from_name', $from_name, $booking_id );

	$from_email = isset( $wprm_options['mail_from_address'] ) ? $wprm_options['mail_from_address'] : get_option('admin_email');
	$from_email = apply_filters( 'wprm_booking_from_address', $from_email, $booking_id );

	$message = wpautop(wprm_get_option('user_booking_rejected_email'));
	$message = wprm_do_email_tags( $message, $booking_id );

	$headers = "From: " . stripslashes_deep( html_entity_decode( $from_name, ENT_COMPAT, 'UTF-8' ) ) . " <$from_email>\r\n";
	$headers .= "Reply-To: ". $from_email . "\r\n";
	$headers .= "Content-Type: text/html; charset=utf-8\r\n";
	$headers = apply_filters( 'wprm_user_booking_notification_rejected_headers', $headers, $booking_id);

	wp_mail( $email, $subject, $message, $headers );

}
endif;

if ( ! function_exists( 'wprm_get_reservation_approve_link' ) ) :
/**
 * Prints the url of the reservation approval link.
 *
 * @since 1.0.0
 * @param string $id the id of the reservation
 * @return void
 */
function wprm_get_reservation_approve_link( $id = 0 ) {
	
	if ( !$post = get_post( $id ) )
		return;

	$post_type_object = get_post_type_object( $post->post_type );
	if ( !$post_type_object )
		return;

	if ( !current_user_can( 'edit_wprm_reservation', $post->ID ) )
		return;

	$approval_link = add_query_arg( 'action', 'approve_reservation', admin_url( sprintf( $post_type_object->_edit_link, $post->ID ) ) );

	return apply_filters( 'wprm_get_reservation_approve_link', wp_nonce_url( $approval_link, "approve_reservation-post_{$post->ID}" ), $post->ID);
}
endif;

if ( ! function_exists( 'wprm_get_reservation_reject_link' ) ) :
/**
 * Prints the url of the reservation rejection link.
 *
 * @since 1.0.0
 * @param string $id the id of the reservation
 * @return void
 */
function wprm_get_reservation_reject_link( $id = 0 ) {
	
	if ( !$post = get_post( $id ) )
		return;

	$post_type_object = get_post_type_object( $post->post_type );
	if ( !$post_type_object )
		return;

	if ( !current_user_can( 'edit_wprm_reservation', $post->ID ) )
		return;

	$reject_link = add_query_arg( 'action', 'reject_reservation', admin_url( sprintf( $post_type_object->_edit_link, $post->ID ) ) );

	return apply_filters( 'wprm_get_reservation_reject_link', wp_nonce_url( $reject_link, "reject_reservation-post_{$post->ID}" ), $post->ID);
}
endif;

if ( ! function_exists( 'wprm_get_item_price' ) ) :
/**
 * Prints item price.
 *
 * @since 1.0.0
 * @param string $id the id of the item
 * @return void
 */
function wprm_get_item_price( $id = null) {
		
	$price = null;

	if($id) :
		$price = get_post_meta( $id, '_wprm_item_price', true );
	else:
		$price = get_post_meta( get_the_id(), '_wprm_item_price', true );
	endif;

	return $price;
	
}
endif;