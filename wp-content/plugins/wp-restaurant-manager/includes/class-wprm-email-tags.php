<?php
/**
 * WPRM API for creating Email template tags
 *
 * Email tags are wrapped in { }
 *
 * A few examples:
 *
 * {booking_id}
 * {fullname}
 * {sitename}
 *
 *
 * To replace tags in content, use: wprm_do_email_tags( $content, booking_id );
 *
 * To add tags, use: wprm_add_email_tag( $tag, $description, $func ). Be sure to wrap wprm_add_email_tag()
 * in a function hooked to the 'wprm_email_tags' action
 *
 * @package     wprm
 * @copyright   Copyright (c) 2014, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WPRM_Email_Template_Tags {

	/**
	 * Container for storing all tags
	 *
	 * @since 1.0.0
	 */
	private $tags;

	/**
	 * Booking ID
	 *
	 * @since 1.0.0
	 */
	private $booking_id;

	/**
	 * Add an email tag
	 *
	 * @since 1.0.0
	 *
	 * @param string   $tag  Email tag to be replace in email
	 * @param callable $func Hook to run when email tag is found
	 */
	public function add( $tag, $description, $func ) {
		if ( is_callable( $func ) ) {
			$this->tags[$tag] = array(
				'tag'         => $tag,
				'description' => $description,
				'func'        => $func
			);
		}
	}

	/**
	 * Remove an email tag
	 *
	 * @since 1.0.0
	 *
	 * @param string $tag Email tag to remove hook from
	 */
	public function remove( $tag ) {
		unset( $this->tags[$tag] );
	}

	/**
	 * Check if $tag is a registered email tag
	 *
	 * @since 1.0.0
	 *
	 * @param string $tag Email tag that will be searched
	 *
	 * @return bool
	 */
	public function email_tag_exists( $tag ) {
		return array_key_exists( $tag, $this->tags );
	}

	/**
	 * Returns a list of all email tags
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_tags() {
		return $this->tags;
	}

	/**
	 * Search content for email tags and filter email tags through their hooks
	 *
	 * @param string $content Content to search for email tags
	 * @param int $booking_id The booking id
	 *
	 * @since 1.0.0
	 *
	 * @return string Content with email tags filtered out.
	 */
	public function do_tags( $content, $booking_id ) {

		// Check if there is atleast one tag added
		if ( empty( $this->tags ) || ! is_array( $this->tags ) ) {
			return $content;
		}

		$this->booking_id = $booking_id;

		$new_content = preg_replace_callback( "/{([A-z0-9\-\_]+)}/s", array( $this, 'do_tag' ), $content );

		$this->booking_id = null;

		return $new_content;
	}

	/**
	 * Do a specific tag, this function should not be used. Please use wprm_do_email_tags instead.
	 *
	 * @since 1.0.0
	 *
	 * @param $m message
	 *
	 * @return mixed
	 */
	public function do_tag( $m ) {

		// Get tag
		$tag = $m[1];

		// Return tag if tag not set
		if ( ! $this->email_tag_exists( $tag ) ) {
			return $m[0];
		}

		return call_user_func( $this->tags[$tag]['func'], $this->booking_id, $tag );
	}

}

/**
 * Add an email tag
 *
 * @since 1.0.0
 *
 * @param string   $tag  Email tag to be replace in email
 * @param callable $func Hook to run when email tag is found
 */
function wprm_add_email_tag( $tag, $description, $func ) {
	WPRM()->email_tags->add( $tag, $description, $func );
}

/**
 * Remove an email tag
 *
 * @since 1.0.0
 *
 * @param string $tag Email tag to remove hook from
 */
function wprm_remove_email_tag( $tag ) {
	WPRM()->email_tags->remove( $tag );
}

/**
 * Check if $tag is a registered email tag
 *
 * @since 1.0.0
 *
 * @param string $tag Email tag that will be searched
 *
 * @return bool
 */
function wprm_email_tag_exists( $tag ) {
	return WPRM()->email_tags->email_tag_exists( $tag );
}

/**
 * Get all email tags
 *
 * @since 1.0.0
 *
 * @return array
 */
function wprm_get_email_tags() {
	return WPRM()->email_tags->get_tags();
}

/**
 * Get a formatted HTML list of all available email tags
 *
 * @since 1.0.0
 * @todo  Filter the list with or without admin tags.
 * @return string
 */
function wprm_get_emails_tags_list() {
	// The list
	$list = '';

	// Get all tags
	$email_tags = wprm_get_email_tags();

	// Check
	if ( count( $email_tags ) > 0 ) {

		// Loop
		foreach ( $email_tags as $email_tag ) {

			// Add email tag to list
			$list .= '{' . $email_tag['tag'] . '} - ' . $email_tag['description'] . '<br/>';

		}

	}

	// Return the list
	return $list;
}

/**
 * Search content for email tags and filter email tags through their hooks
 *
 * @param string $content Content to search for email tags
 * @param int $booking_id The booking id
 *
 * @since 1.0.0
 *
 * @return string Content with email tags filtered out.
 */
function wprm_do_email_tags( $content, $booking_id ) {

	// Replace all tags
	$content = WPRM()->email_tags->do_tags( $content, $booking_id );

	// Maintaining backwards compatibility
	$content = apply_filters( 'wprm_email_template_tags', $content, $booking_id );

	// Return content
	return $content;
}

/**
 * Load email tags
 *
 * @since 1.0.0
 */
function wprm_load_email_tags() {
	do_action( 'wprm_add_email_tags' );
}
add_action( 'init', 'wprm_load_email_tags', -999 );

/**
 * Add default WPRM email template tags
 *
 * @since 1.0.0
 */
function wprm_setup_email_tags() {

	// Setup default tags array
	$email_tags = array(
		array(
			'tag'         => 'booking_id',
			'description' => __( 'The unique ID number for this purchase.', 'wprm' ),
			'function'    => 'wprm_email_tag_booking_id'
		),
		array(
			'tag'         => 'sitename',
			'description' => __( 'Your site name.', 'wprm' ),
			'function'    => 'wprm_email_tag_sitename'
		),
		array(
			'tag'         => 'fullname',
			'description' => __( 'The name of the person who made the request.', 'wprm' ),
			'function'    => 'wprm_email_tag_fullname'
		),
		array(
			'tag'         => 'email',
			'description' => __( 'The email address of the person who made the reservation.', 'wprm' ),
			'function'    => 'wprm_email_tag_email'
		),
		array(
			'tag'         => 'party',
			'description' => __( 'The amount of people for the reservation.', 'wprm' ),
			'function'    => 'wprm_email_tag_party'
		),
		array(
			'tag'         => 'date',
			'description' => __( 'The date of the reservation.', 'wprm' ),
			'function'    => 'wprm_email_tag_date'
		),
		array(
			'tag'         => 'time',
			'description' => __( 'The time of the reservation.', 'wprm' ),
			'function'    => 'wprm_email_tag_time'
		),
		array(
			'tag'         => 'phone',
			'description' => __( 'The phone number of the person who of the reservation.', 'wprm' ),
			'function'    => 'wprm_email_tag_phone'
		),
		array(
			'tag'         => 'message',
			'description' => __( 'The message from the person who of the reservation.', 'wprm' ),
			'function'    => 'wprm_email_tag_message'
		),
		array(
			'tag'         => 'view_booking',
			'description' => __( 'Display link to booking details in admin panel.', 'wprm' ),
			'function'    => 'wprm_email_tag_view_booking'
		),
		array(
			'tag'         => 'approve_booking',
			'description' => __( 'Display link to directly approve booking from admin panel.', 'wprm' ),
			'function'    => 'wprm_email_tag_approve_booking'
		),
		array(
			'tag'         => 'reject_booking',
			'description' => __( 'Display link to directly reject booking from admin panel.', 'wprm' ),
			'function'    => 'wprm_email_tag_reject_booking'
		),
		array(
			'tag'         => 'all_bookings',
			'description' => __( 'Display link to complete list of bookings in admin panel.', 'wprm' ),
			'function'    => 'wprm_email_tag_all_bookings'
		),
	);

	// Apply wprm_email_tags filter
	$email_tags = apply_filters( 'wprm_email_tags', $email_tags );

	// Add email tags
	foreach ( $email_tags as $email_tag ) {
		wprm_add_email_tag( $email_tag['tag'], $email_tag['description'], $email_tag['function'] );
	}

}
add_action( 'wprm_add_email_tags', 'wprm_setup_email_tags' );

/**
 * Email template tag: booking_id
 * The unique ID number for this booking
 *
 * @param int $booking_id
 *
 * @return int booking_id
 */
function wprm_email_tag_booking_id( $booking_id ) {
	return $booking_id;
}

/**
 * Email template tag: sitename
 * Your site name
 *
 * @param int $booking_id
 *
 * @return string sitename
 */
function wprm_email_tag_sitename( $booking_id ) {
	return get_bloginfo( 'name' );
}

/**
 * Email template tag: fullname
 * Full name of the person who made the booking request.
 * 
 * @since  1.0.0
 * @param int $booking_id
 * @return string fullname
 */
function wprm_email_tag_fullname( $booking_id ) {
	return get_post_meta($booking_id, '_wprm_reservation_name', true);
}

/**
 * Email template tag: email
 * Full name of the person who made the booking request.
 * 
 * @since  1.0.0
 * @param int $booking_id
 * @return string email
 */
function wprm_email_tag_email( $booking_id ) {
	return wprm_get_booking_email($booking_id);
}

/**
 * Email template tag: party
 * The size of the party.
 * 
 * @since  1.0.0
 * @param int $booking_id
 * @return string party
 */
function wprm_email_tag_party( $booking_id ) {
	return get_post_meta($booking_id, '_wprm_reservation_size', true);
}

/**
 * Email template tag: date
 * Date of the booking request.
 * 
 * @since  1.0.0
 * @param int $booking_id
 * @return string date
 */
function wprm_email_tag_date( $booking_id ) {

	$the_date = date_i18n( get_option( 'date_format' ), get_post_meta( $booking_id, '_wprm_reservation_date', true ) );

	return $the_date;
}

/**
 * Email template tag: time
 * Time of the booking request.
 * 
 * @since  1.0.0
 * @param int $booking_id
 * @return string time
 */
function wprm_email_tag_time( $booking_id ) {
	return get_post_meta($booking_id, '_wprm_reservation_time', true);
}

/**
 * Email template tag: phone
 * Phone number of the person who made the booking request.
 * 
 * @since  1.0.0
 * @param int $booking_id
 * @return string phone
 */
function wprm_email_tag_phone( $booking_id ) {
	return get_post_meta($booking_id, '_wprm_reservation_phone_number', true);
}

/**
 * Email template tag: message
 * Message from the person who made the booking request.
 * 
 * @since  1.0.0
 * @param int $booking_id
 * @return string message
 */
function wprm_email_tag_message( $booking_id ) {
	return get_post_meta($booking_id, '_wprm_reservation_message', true);
}

/**
 * Email template tag: view_booking
 * Time of the booking request.
 * 
 * @since  1.0.0
 * @param int $booking_id
 * @return string view_booking
 */
function wprm_email_tag_view_booking( $booking_id ) {
	
	$url = '<a href="'.admin_url( 'post.php?post='.$booking_id.'&action=edit' ).'">'.__('View Booking','wprm').'</a>';
	return $url;
}

/**
 * Email template tag: approve_booking
 * Time of the booking request.
 * 
 * @since  1.0.0
 * @param int $booking_id
 * @return string approve_booking
 */
function wprm_email_tag_approve_booking( $booking_id ) {
	
	$url = '<a href="'.wprm_get_reservation_approve_link($booking_id).'">'.__('Approve Booking','wprm').'</a>';
	return $url;
}

/**
 * Email template tag: reject_booking
 * Time of the booking request.
 * 
 * @since  1.0.0
 * @param int $booking_id
 * @return string reject_booking
 */
function wprm_email_tag_reject_booking( $booking_id ) {
	
	$url = '<a href="'.wprm_get_reservation_reject_link($booking_id).'">'.__('Reject Booking','wprm').'</a>';
	return $url;
}

/**
 * Email template tag: all_bookings
 * Time of the booking request.
 * 
 * @since  1.0.0
 * @param int $booking_id
 */
function wprm_email_tag_all_bookings() {
	
	$url = '<a href="'.admin_url( 'edit.php?post_type=wprm_reservations' ).'">'.__('View All Booking','wprm').'</a>';
	return $url;
}