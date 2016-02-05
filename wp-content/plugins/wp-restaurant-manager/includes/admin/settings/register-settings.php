<?php
/**
 * Register Settings
 *
 * @package     wp-restaurant-manager
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * Get an option
 *
 * Looks to see if the specified setting exists, returns default if not
 *
 * @since 1.0.0
 * @return mixed
 */
function wprm_get_option( $key = '', $default = false ) {
	global $wprm_options;
	$value = ! empty( $wprm_options[ $key ] ) ? $wprm_options[ $key ] : $default;
	$value = apply_filters( 'wprm_get_option', $value, $key, $default );
	return apply_filters( 'wprm_get_option_' . $key, $value, $key, $default );
}

/**
 * Get Settings
 *
 * Retrieves all plugin settings
 *
 * @since 1.0
 * @return array WPRM settings
 */
function wprm_get_settings() {

	$settings = get_option( 'wprm_settings' );

	if( empty( $settings ) ) {

		// Update old settings with new single option
		$general_settings = is_array( get_option( 'wprm_settings_general' ) )    ? get_option( 'wprm_settings_general' )  	: array();
		$booking_settings = is_array( get_option( 'wprm_settings_booking' ) )    ? get_option( 'wprm_settings_booking' )  	: array();

		$settings = array_merge( $general_settings, $booking_settings );

		update_option( 'wprm_settings', $settings );

	}
	return apply_filters( 'wprm_get_settings', $settings );
}

/**
 * Add all settings sections and fields
 *
 * @since 1.0
 * @return void
*/
function wprm_register_settings() {

	if ( false == get_option( 'wprm_settings' ) ) {
		add_option( 'wprm_settings' );
	}

	foreach( wprm_get_registered_settings() as $tab => $settings ) {

		add_settings_section(
			'wprm_settings_' . $tab,
			__return_null(),
			'__return_false',
			'wprm_settings_' . $tab
		);

		foreach ( $settings as $option ) {

			$name = isset( $option['name'] ) ? $option['name'] : '';

			add_settings_field(
				'wprm_settings[' . $option['id'] . ']',
				$name,
				function_exists( 'wprm_' . $option['type'] . '_callback' ) ? 'wprm_' . $option['type'] . '_callback' : 'wprm_missing_callback',
				'wprm_settings_' . $tab,
				'wprm_settings_' . $tab,
				array(
					'id'      => isset( $option['id'] ) ? $option['id'] : null,
					'desc'    => ! empty( $option['desc'] ) ? $option['desc'] : '',
					'name'    => isset( $option['name'] ) ? $option['name'] : null,
					'section' => $tab,
					'size'    => isset( $option['size'] ) ? $option['size'] : null,
					'options' => isset( $option['options'] ) ? $option['options'] : '',
					'std'     => isset( $option['std'] ) ? $option['std'] : ''
				)
			);
		}

	}

	// Creates our settings in the options table
	register_setting( 'wprm_settings', 'wprm_settings', 'wprm_settings_sanitize' );

}
add_action('admin_init', 'wprm_register_settings');

/**
 * Retrieve the array of plugin settings
 *
 * @since 1.8
 * @return array
*/
function wprm_get_registered_settings() {

	/**
	 * 'Whitelisted' WPRM settings, filters are provided for each settings
	 * section to allow extensions and other plugins to add their own settings
	 */
	$wprm_settings = array(
		/** General Settings */
		'general' => apply_filters( 'wprm_settings_general',
			array(
				'booking_style' => array(
					'id' => 'booking_style',
					'name' => __( 'Booking Form Styles', 'wprm' ),
					'desc' => sprintf( __( 'Choose the style of the date and time pickers. <a href="%s" target="_blank">You can preview the styles available here.</a>', 'wprm' ), 'http://amsul.ca/pickadate.js/' ),
					'type' => 'select',
					'options' => wprm_get_booking_styles()
				),
				'form_style_extended' => array(
					'id' => 'form_style_extended',
					'name' => __( 'Extend Form Fields', 'wprm' ),
					'desc' => __( 'Check this box if you would like to extend all form fields to the fullwidth of the container.', 'wprm' ),
					'type' => 'checkbox'
				),
				'adjust_picker' => array(
					'id' => 'adjust_picker',
					'name' => __( 'Adjust Date/Time Picker Style', 'wprm' ),
					'desc' => __( 'Check this box if you would like WPRM to "automatically" adjust the style of the date and time picker on your site.<br/><br/><strong><small>Note: this option simply adjusts the alignments of certain elements within the booking form. Also note, that every theme is different, this option might not work with every theme and it is impossible to make it work for every theme. If you have html/css coding experience, and you spot some weird alignment issues on your pickers, either fix it yourself or contact the author of your theme who might be willing to help you.</small></strong>', 'wprm' ),
					'type' => 'checkbox'
				),
				'adjust_date_time' => array(
					'id' => 'adjust_date_time',
					'name' => __( 'Adjust Date/Time in a column', 'wprm' ),
					'desc' => __( 'Check this box if you would like WPRM to align the date and time field of the booking form into a single row of 2 columns.', 'wprm' ),
					'type' => 'checkbox'
				),
				'food_menu_header' => array(
					'id' => 'food_menu_header',
					'name' => '<strong>' . __( 'Food Menu Styling &amp; Settings', 'wprm' ) . '</strong>',
					'type' => 'header'
				),
				'menu_style' => array(
					'id' => 'menu_style',
					'name' => __( 'Menu Style', 'wprm' ),
					'desc' => __( 'Choose the style of the menu.', 'wprm' ),
					'type' => 'select',
					'options' => wprm_get_menu_styles()
				),
				'disable_details' => array(
					'id' => 'disable_details',
					'name' => __( 'Hide Dish Details Display', 'wprm' ),
					'desc' => __( 'Check this box if you would like WPRM to completely remove details for menu items into single pages.', 'wprm' ),
					'type' => 'checkbox'
				),
				'disable_spicy' => array(
					'id' => 'disable_spicy',
					'name' => __( 'Hide Spicy Level Display', 'wprm' ),
					'desc' => __( 'Check this box if you would like WPRM to completely remove all spicy level details for menu items.', 'wprm' ),
					'type' => 'checkbox'
				),
				'disable_vegetarian' => array(
					'id' => 'disable_vegetarian',
					'name' => __( 'Hide Vegetarian Display', 'wprm' ),
					'desc' => __( 'Check this box if you would like WPRM to completely remove all vegetarian details for menu items.', 'wprm' ),
					'type' => 'checkbox'
				),
				'thumbnail_width' => array(
					'id' => 'thumbnail_width',
					'name' => __( 'Thumbnail Width', 'wprm' ),
					'desc' => __( 'Define the size of the thumbnail. This size will be used for the menu lists. Enter a value in px without "px".', 'wprm' ),
					'type' => 'text',
					'size' => 'medium',
					'std' => '100'
				),
				'thumbnail_height' => array(
					'id' => 'thumbnail_height',
					'name' => __( 'Thumbnail Height', 'wprm' ),
					'desc' => __( 'Define the size of the thumbnail. This size will be used for the menu lists. Enter a value in px without "px".', 'wprm' ),
					'type' => 'text',
					'size' => 'medium',
					'std' => '100'
				),
				'uninstall_on_delete' => array(
					'id' => 'uninstall_on_delete',
					'name' => __( 'Remove Data on Uninstall?', 'wprm' ),
					'desc' => __( 'Check this box if you would like WPRM to completely remove all of its data when the plugin is deleted.', 'wprm' ),
					'type' => 'checkbox'
				)
			)
		),

		'booking' => apply_filters('wprm_settings_bookings',
			array(
				'booking_page' => array(
					'id' => 'booking_page',
					'name' => __( 'Booking Page', 'wprm' ),
					'desc' => __( 'This is the booking page. The [wprm_booking_form] short code must be on this page.', 'wprm' ),
					'type' => 'select',
					'options' => wprm_get_pages()
				),
				'after_booking_page' => array(
					'id' => 'after_booking_page',
					'name' => __( 'Successful Booking Page', 'wprm' ),
					'desc' => __( 'This is the successful booking page. The user will be redirected here upon successful booking.', 'wprm' ),
					'type' => 'select',
					'options' => wprm_get_pages()
				),
				'booking_confirmation_type' => array(
					'id' => 'booking_confirmation_type',
					'name' => __( 'Booking Confirmation Behavior', 'wprm' ),
					'desc' => __( 'Choose the behavior of the booking confirmation.', 'wprm' ),
					'type' => 'select',
					'options' => array(
						'message' => __( 'Display successful message only.', 'wprm' ),
						'redirect' => __( 'Redirect user to successful page.', 'wprm' )
					)
				),
				'booking_default_status' => array(
					'id' => 'booking_default_status',
					'name' => __( 'Booking Default Status', 'wprm' ),
					'desc' => __( 'Choose the default status of a booking when is submitted.', 'wprm' ),
					'type' => 'select',
					'options' => array(
						'publish' => __( 'Automatically approve bookings.', 'wprm' ),
						'pending' => __( 'Set all bookings to pending. Manual approval required.', 'wprm' )
					)
				),
				'success_message' => array(
					'id' => 'success_message',
					'name' => __( 'Success Message', 'wprm' ),
					'desc' => __( 'Enter the message to display when a booking request is made.".', 'wprm' ),
					'type' => 'textarea',
					'std'  => 'Thank you for your booking. Your request is waiting to be confirmed. An email will be sent to you when updates are available.'
				),
				'date_format' => array(
					'id' => 'date_format',
					'name' => __( 'Date Format', 'wprm' ),
					'desc' => __( 'Define how the date should appear after it has been selected.', 'wprm' ),
					'type' => 'text',
					'size' => 'medium',
					'std' => 'mmmm d, yyyy'
				),
				'time_format' => array(
					'id' => 'time_format',
					'name' => __( 'Time Format', 'wprm' ),
					'desc' => __( 'Define how the time should appear after it has been selected.', 'wprm' ),
					'type' => 'text',
					'size' => 'medium',
					'std' => 'h:i A'
				),
				'calendar_language' => array(
					'id' => 'calendar_language',
					'name' => __( 'Calendar Language', 'wprm' ),
					'desc' => __( 'Select a language to use for the booking form datepicker if it is different than your WordPress language setting.', 'wprm' ),
					'type' => 'select',
					'options' => wprm_get_calendar_languages(),
					'default' => 'en_EN'
				),
				'booking_schedule_header' => array(
					'id' => 'booking_schedule_header',
					'name' => '<strong>' . __( 'Booking Schedule', 'wprm' ) . '</strong>',
					'desc' => __( 'Define the weekly schedule during which you accept bookings.', 'wprm' ),
					'type' => 'header'
				),
				'early_bookings' => array(
					'id' => 'early_bookings',
					'name' => __( 'Early Bookings', 'wprm' ),
					'desc' => __( 'Select how early customers can make their booking.', 'wprm' ),
					'type' => 'select',
					'options' => wprm_get_early_bookings(),
				),
				'late_bookings' => array(
					'id' => 'late_bookings',
					'name' => __( 'Late Bookings', 'wprm' ),
					'desc' => __( 'Select how late customers can make their booking.', 'wprm' ),
					'type' => 'select',
					'options' => wprm_get_late_bookings(),
				),
				'time_interval' => array(
					'id' => 'time_interval',
					'name' => __( 'Time Interval', 'wprm' ),
					'desc' => __( 'Select the number of minutes between each available time for bookings.', 'wprm' ),
					'type' => 'select',
					'options' => wprm_get_time_interval(),
				),
				'opening_time' => array(
					'id' => 'opening_time',
					'name' => __( 'Opening Time', 'wprm' ),
					'desc' => __( 'Define from what time you accept bookings. For example, if your restaurant starts serving food at 7.30pm, simply enter 19:30 PM into the field here. The time format for this field must be HH:MM AM/PM. Example: 7:30 AM or 7:30 PM', 'wprm' ),
					'type' => 'text',
					'size' => 'medium',
					'std' => ''
				),
				'closing_time' => array(
					'id' => 'closing_time',
					'name' => __( 'Closing Time', 'wprm' ),
					'desc' => __( 'Define from what time you stop accepting bookings. For example, if your restaurant stops serving food after 11.30pm, simply enter 23:30 PM into the field here. The time format for this field must be HH:MM AM/PM. Example: 7:30 AM or 7:30 PM', 'wprm' ),
					'type' => 'text',
					'size' => 'medium',
					'std' => ''
				),
				'party_sizes' => array(
					'id' => 'party_sizes',
					'name' => '<strong>' . __( 'Table Sizes/Party Sizes', 'wprm' ) . '</strong>',
					'desc' => __( 'Specify the bookable table sizes/party sizes on the booking form.', 'wprm' ),
					'type' => 'party_sizes'
				),
				'dates_to_exclude' => array(
					'id' => 'dates_to_exclude',
					'name' => '<strong>' . __( 'Disable Calendar Dates', 'wprm' ) . '</strong>',
					'desc' => __( 'Use the following generator to disable certain dates from the calendar. Disabled dates will not be available for booking. <strong>Please do not manually edit the date text. Use the datepicker to enter the date.</strong>', 'wprm' ),
					'type' => 'dates_to_exclude'
				),
			)
		),
		'notifications' => apply_filters('wprm_settings_notifications',
			array(

				'admin_notifications_header' => array(
					'id' => 'admin_notifications_header',
					'name' => '<strong>' . __( 'Admin Notifications Settings', 'wprm' ) . '</strong>',
					'type' => 'header'
				),
				'email_site_admin' => array(
					'id' => 'email_site_admin',
					'name' => __( 'Email Site Administrator', 'wprm' ),
					'desc' => sprintf(__( 'Check this box if you would like WPRM to send an email notifications when a booking request has been made. <a href="%s" target="_blank">Click here to customize the email address where admin emails are sent.</a>', 'wprm' ), admin_url('options-general.php#admin_email')),
					'type' => 'checkbox'
				),
				'admin_notification_content' => array(
					'id' => 'admin_notification_content',
					'name' => __( 'Admin Booking Notification', 'wprm' ),
					'desc' => __( 'Enter the email that is sent to admin when a booking request is received. Html is accepted. Available Template Tags: ', 'wprm' ) . '<br/><br/>' . wprm_get_emails_tags_list( true ),
					'type' => 'rich_editor',
					'std' => wprm_get_default_admin_notification_email()
				),
				'user_notifications_header' => array(
					'id' => 'user_notifications_header',
					'name' => '<strong>' . __( 'Users Notifications Settings', 'wprm' ) . '</strong>',
					'type' => 'header'
				),
				'mail_user_booking_received' => array(
					'id' => 'mail_user_booking_received',
					'name' => __( 'Booking Request Received', 'wprm' ),
					'desc' => __( 'Check this box if you would like WPRM to send an email notifications when the booking request has been received.', 'wprm' ),
					'type' => 'checkbox'
				),
				'mail_user_booking_success' => array(
					'id' => 'mail_user_booking_success',
					'name' => __( 'Booking Request Approved', 'wprm' ),
					'desc' => __( 'Check this box if you would like WPRM to send an email notifications when the booking request has been approved.', 'wprm' ),
					'type' => 'checkbox'
				),
				'mail_user_booking_rejected' => array(
					'id' => 'mail_user_booking_rejected',
					'name' => __( 'Booking Request Rejected', 'wprm' ),
					'desc' => __( 'Check this box if you would like WPRM to send an email notifications when the booking request has been rejected.', 'wprm' ),
					'type' => 'checkbox'
				),
				'mail_from_name' => array(
					'id' => 'mail_from_name',
					'name' => __( 'From Name', 'wprm' ),
					'desc' => __( 'Enter the name that will be displayed as "from/reply-to" in the email that your users will receive.', 'wprm' ),
					'type' => 'text',
					'size' => 'medium',
					'std' => get_option( 'blogname' )
				),
				'mail_from_address' => array(
					'id' => 'mail_from_address',
					'name' => __( 'From Address', 'wprm' ),
					'desc' => __( 'Enter the email that will be displayed as "from/reply-to" in the email that your users will receive.', 'wprm' ),
					'type' => 'text',
					'size' => 'medium',
					'std' => get_option( 'admin_email' )
				),
				'user_booking_requested_email_subject' => array(
					'id' => 'user_booking_requested_email_subject',
					'name' => __( 'Booking Request Email Subject', 'wprm' ),
					'desc' => __( 'Enter the subject that will be used for the booking requested email.', 'wprm' ),
					'type' => 'text',
					'std' => 'Booking Request received on {date} at {time}'
				),
				'user_booking_requested_email' => array(
					'id' => 'user_booking_requested_email',
					'name' => __( 'User Booking Request Email', 'wprm' ),
					'desc' => __( 'Enter the email that is sent to the user when a booking request is received.', 'wprm' ) . '<br/><br/>' . wprm_get_emails_tags_list(),
					'type' => 'rich_editor',
					'std' => wprm_get_default_user_request_notification_email()
				),
				'user_booking_confirmed_email_subject' => array(
					'id' => 'user_booking_confirmed_email_subject',
					'name' => __( 'Booking Confirmed Email Subject', 'wprm' ),
					'desc' => __( 'Enter the subject that will be used for the booking confirmed email.', 'wprm' ),
					'type' => 'text',
					'std' => 'Booking Confirmed #{booking_id}: for {party} the {date} at {time}'
				),
				'user_booking_confirmed_email' => array(
					'id' => 'user_booking_confirmed_email',
					'name' => __( 'User Booking Confirmed Email', 'wprm' ),
					'desc' => __( 'Enter the email that is sent to the user when a booking request is confirmed.', 'wprm' ) . '<br/><br/>' . wprm_get_emails_tags_list(),
					'type' => 'rich_editor',
					'std' => wprm_get_default_user_confirmed_notification_email()
				),
				'user_booking_rejected_email_subject' => array(
					'id' => 'user_booking_rejected_email_subject',
					'name' => __( 'Booking Rejected Email Subject', 'wprm' ),
					'desc' => __( 'Enter the subject that will be used for the booking rejected email.', 'wprm' ),
					'type' => 'text',
					'std' => 'Booking Rejected on {date} at {time}'
				),
				'user_booking_rejected_email' => array(
					'id' => 'user_booking_rejected_email',
					'name' => __( 'User Booking Rejected Email', 'wprm' ),
					'desc' => __( 'Enter the email that is sent to the user when a booking request is rejected.', 'wprm' ) . '<br/><br/>' . wprm_get_emails_tags_list(),
					'type' => 'rich_editor',
					'std' => wprm_get_default_user_rejected_notification_email()
				),

			)
		),
		/** Extension Settings */
		'extensions' => apply_filters('wprm_settings_extensions',
			array()
		),
		'licenses' => apply_filters('wprm_settings_licenses',
			array()
		),
	);

	return $wprm_settings;
}

/**
 * Settings Sanitization
 *
 * Adds a settings error (for the updated message)
 * At some point this will validate input
 *
 * @since 1.0.8.2
 *
 * @param array $input The value inputted in the field
 *
 * @return string $input Sanitizied value
 */
function wprm_settings_sanitize( $input = array() ) {

	global $wprm_options;

	if ( empty( $_POST['_wp_http_referer'] ) ) {
		return $input;
	}

	parse_str( $_POST['_wp_http_referer'], $referrer );

	$settings = wprm_get_registered_settings();
	$tab      = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';

	$input = $input ? $input : array();
	$input = apply_filters( 'wprm_settings_' . $tab . '_sanitize', $input );

	// Loop through each setting being saved and pass it through a sanitization filter
	foreach ( $input as $key => $value ) {

		// Get the setting type (checkbox, select, etc)
		$type = isset( $settings[$tab][$key]['type'] ) ? $settings[$tab][$key]['type'] : false;

		if ( $type ) {
			// Field type specific filter
			$input[$key] = apply_filters( 'wprm_settings_sanitize_' . $type, $value, $key );
		}

		// General filter
		$input[$key] = apply_filters( 'wprm_settings_sanitize', $value, $key );
	}

	// Loop through the whitelist and unset any that are empty for the tab being saved
	if ( ! empty( $settings[$tab] ) ) {
		foreach ( $settings[$tab] as $key => $value ) {

			// settings used to have numeric keys, now they have keys that match the option ID. This ensures both methods work
			if ( is_numeric( $key ) ) {
				$key = $value['id'];
			}

			if ( empty( $input[$key] ) ) {
				unset( $wprm_options[$key] );
			}

		}
	}

	// Merge our new settings with the existing
	$output = array_merge( $wprm_options, $input );

	add_settings_error( 'wprm-notices', '', __( 'Settings updated.', 'wprm' ), 'updated' );

	return $output;
}

/**
 * Booking Schedule Settings Sanitization
 *
 * This saves the booking schedule table and exceptions schedule.
 *
 * @since 1.0.0
 * @param array $input The value inputted in the field
 * @return string $input Sanitized value
 */
function wprm_settings_sanitize_booking( $input ) {

	$new_sizes = ! empty( $_POST['party_sizes'] ) ? array_values( $_POST['party_sizes'] ) : array();
	$new_dates = ! empty( $_POST['date_to_exclude'] ) ? array_values( $_POST['date_to_exclude'] ) : array();

	update_option( 'wprm_party_sizes', $new_sizes );
	update_option( 'wprm_dates_to_exclude', $new_dates );

	return $input;
}
add_filter( 'wprm_settings_booking_sanitize', 'wprm_settings_sanitize_booking' );

/**
 * Sanitize text fields
 *
 * @since 1.8
 * @param array $input The field value
 * @return string $input Sanitizied value
 */
function wprm_sanitize_text_field( $input ) {
	return trim( $input );
}
add_filter( 'wprm_settings_sanitize_text', 'wprm_sanitize_text_field' );

/**
 * Retrieve settings tabs
 *
 * @since 1.8
 * @param array $input The field value
 * @return string $input Sanitizied value
 */
function wprm_get_settings_tabs() {

	$settings = wprm_get_registered_settings();

	$tabs             = array();
	$tabs['general']  = __( 'General Settings', 'wprm' );
	$tabs['booking']  = __( 'Booking Settings', 'wprm' );
	$tabs['notifications']  = __( 'Notifications Settings', 'wprm' );

	if( ! empty( $settings['extensions'] ) ) {
		$tabs['extensions'] = __( 'Extensions', 'wprm' );
	}
	if( ! empty( $settings['licenses'] ) ) {
		$tabs['licenses'] = __( 'Licenses', 'wprm' );
	}

	return apply_filters( 'wprm_settings_tabs', $tabs );
}

/**
 * Retrieve a list of all published pages
 *
 * On large sites this can be expensive, so only load if on the settings page or $force is set to true
 *
 * @since 1.9.5
 * @param bool $force Force the pages to be loaded even if not on settings
 * @return array $pages_options An array of the pages
 */
function wprm_get_pages( $force = false ) {

	$pages_options = array( 0 => '' ); // Blank option

	if( ( ! isset( $_GET['page'] ) || 'wprm-settings' != $_GET['page'] ) && ! $force ) {
		return $pages_options;
	}

	$pages = get_pages();
	if ( $pages ) {
		foreach ( $pages as $page ) {
			$pages_options[ $page->ID ] = $page->post_title;
		}
	}

	return $pages_options;
}

/**
 * Header Callback
 *
 * Renders the header.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @return void
 */
function wprm_header_callback( $args ) {
	echo '<hr/>';
}

/**
 * Checkbox Callback
 *
 * Renders checkboxes.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_checkbox_callback( $args ) {
	global $wprm_options;

	$checked = isset( $wprm_options[ $args[ 'id' ] ] ) ? checked( 1, $wprm_options[ $args[ 'id' ] ], false ) : '';
	$html = '<input type="checkbox" id="wprm_settings[' . $args['id'] . ']" name="wprm_settings[' . $args['id'] . ']" value="1" ' . $checked . '/>';
	$html .= '<label for="wprm_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Multicheck Callback
 *
 * Renders multiple checkboxes.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_multicheck_callback( $args ) {
	global $wprm_options;

	if ( ! empty( $args['options'] ) ) {
		foreach( $args['options'] as $key => $option ):
			if( isset( $wprm_options[$args['id']][$key] ) ) { $enabled = $option; } else { $enabled = NULL; }
			echo '<input name="wprm_settings[' . $args['id'] . '][' . $key . ']" id="wprm_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked($option, $enabled, false) . '/>&nbsp;';
			echo '<label for="wprm_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
		endforeach;
		echo '<p class="description">' . $args['desc'] . '</p>';
	}
}

/**
 * Radio Callback
 *
 * Renders radio boxes.
 *
 * @since 1.3.3
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_radio_callback( $args ) {
	global $wprm_options;

	foreach ( $args['options'] as $key => $option ) :
		$checked = false;

		if ( isset( $wprm_options[ $args['id'] ] ) && $wprm_options[ $args['id'] ] == $key )
			$checked = true;
		elseif( isset( $args['std'] ) && $args['std'] == $key && ! isset( $wprm_options[ $args['id'] ] ) )
			$checked = true;

		echo '<input name="wprm_settings[' . $args['id'] . ']"" id="wprm_settings[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked(true, $checked, false) . '/>&nbsp;';
		echo '<label for="wprm_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
	endforeach;

	echo '<p class="description">' . $args['desc'] . '</p>';
}

/**
 * Text Callback
 *
 * Renders text fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_text_callback( $args ) {
	global $wprm_options;

	if ( isset( $wprm_options[ $args['id'] ] ) )
		$value = $wprm_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="text" class="' . $size . '-text" id="wprm_settings[' . $args['id'] . ']" name="wprm_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
	$html .= '<label for="wprm_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Number Callback
 *
 * Renders number fields.
 *
 * @since 1.9
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_number_callback( $args ) {
	global $wprm_options;

	if ( isset( $wprm_options[ $args['id'] ] ) )
		$value = $wprm_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$max  = isset( $args['max'] ) ? $args['max'] : 999999;
	$min  = isset( $args['min'] ) ? $args['min'] : 0;
	$step = isset( $args['step'] ) ? $args['step'] : 1;

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="number" step="' . esc_attr( $step ) . '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . $size . '-text" id="wprm_settings[' . $args['id'] . ']" name="wprm_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
	$html .= '<label for="wprm_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Textarea Callback
 *
 * Renders textarea fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_textarea_callback( $args ) {
	global $wprm_options;

	if ( isset( $wprm_options[ $args['id'] ] ) )
		$value = $wprm_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<textarea class="large-text" cols="50" rows="5" id="wprm_settings[' . $args['id'] . ']" name="wprm_settings[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
	$html .= '<label for="wprm_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Password Callback
 *
 * Renders password fields.
 *
 * @since 1.3
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_password_callback( $args ) {
	global $wprm_options;

	if ( isset( $wprm_options[ $args['id'] ] ) )
		$value = $wprm_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="password" class="' . $size . '-text" id="wprm_settings[' . $args['id'] . ']" name="wprm_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
	$html .= '<label for="wprm_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Missing Callback
 *
 * If a function is missing for settings callbacks alert the user.
 *
 * @since 1.3.1
 * @param array $args Arguments passed by the setting
 * @return void
 */
function wprm_missing_callback($args) {
	printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'wprm' ), $args['id'] );
}

/**
 * Select Callback
 *
 * Renders select fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_select_callback($args) {
	global $wprm_options;

	if ( isset( $wprm_options[ $args['id'] ] ) )
		$value = $wprm_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$html = '<select id="wprm_settings[' . $args['id'] . ']" name="wprm_settings[' . $args['id'] . ']"/>';

	foreach ( $args['options'] as $option => $name ) :
		$selected = selected( $option, $value, false );
		$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
	endforeach;

	$html .= '</select>';
	$html .= '<label for="wprm_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Color select Callback
 *
 * Renders color select fields.
 *
 * @since 1.8
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_color_select_callback( $args ) {
	global $wprm_options;

	if ( isset( $wprm_options[ $args['id'] ] ) )
		$value = $wprm_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$html = '<select id="wprm_settings[' . $args['id'] . ']" name="wprm_settings[' . $args['id'] . ']"/>';

	foreach ( $args['options'] as $option => $color ) :
		$selected = selected( $option, $value, false );
		$html .= '<option value="' . $option . '" ' . $selected . '>' . $color['label'] . '</option>';
	endforeach;

	$html .= '</select>';
	$html .= '<label for="wprm_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Rich Editor Callback
 *
 * Renders rich editor fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @global $wp_version WordPress Version
 */
function wprm_rich_editor_callback( $args ) {
	global $wprm_options, $wp_version;

	if ( isset( $wprm_options[ $args['id'] ] ) )
		$value = $wprm_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	if ( $wp_version >= 3.3 && function_exists( 'wp_editor' ) ) {
		ob_start();
		wp_editor( stripslashes( $value ), 'wprm_settings_' . $args['id'], array( 'textarea_name' => 'wprm_settings[' . $args['id'] . ']' ) );
		$html = ob_get_clean();
	} else {
		$html = '<textarea class="large-text" rows="10" id="wprm_settings[' . $args['id'] . ']" name="wprm_settings[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
	}

	$html .= '<br/><label for="wprm_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Upload Callback
 *
 * Renders upload fields.
 *
 * @since 1.0
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_upload_callback( $args ) {
	global $wprm_options;

	if ( isset( $wprm_options[ $args['id'] ] ) )
		$value = $wprm_options[$args['id']];
	else
		$value = isset($args['std']) ? $args['std'] : '';

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="text" class="' . $size . '-text wprm_upload_field" id="wprm_settings[' . $args['id'] . ']" name="wprm_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
	$html .= '<span>&nbsp;<input type="button" class="wprm_settings_upload_button button-secondary" value="' . __( 'Upload File', 'wprm' ) . '"/></span>';
	$html .= '<label for="wprm_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}


/**
 * Color picker Callback
 *
 * Renders color picker fields.
 *
 * @since 1.6
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_color_callback( $args ) {
	global $wprm_options;

	if ( isset( $wprm_options[ $args['id'] ] ) )
		$value = $wprm_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$default = isset( $args['std'] ) ? $args['std'] : '';

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="text" class="wprm-color-picker" id="wprm_settings[' . $args['id'] . ']" name="wprm_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '" data-default-color="' . esc_attr( $default ) . '" />';
	$html .= '<label for="wprm_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

	echo $html;
}

/**
 * Table Sizes Callback
 *
 * Renders table sizes table
 *
 * @since 1.0.0
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_party_sizes_callback($args) {
	global $wprm_options;
	$sizes = wprm_get_party_sizes();
	ob_start(); ?>
	<p><?php echo $args['desc']; ?></p>

	<table id="wprm_party_sizes" class="wp-list-table widefat fixed posts">
		<thead>
			<tr>
				<th scope="col" class="wprm_party_size"><?php _e( 'Table Sizes/Party Sizes', 'wprm' ); ?></th>
				<th scope="col"><?php _e( 'Remove', 'wprm' ); ?></th>
			</tr>
		</thead>
		<?php if( ! empty( $sizes ) ) : ?>
			<?php foreach( $sizes as $key => $rate ) : ?>
				<tr>
				<td class="wprm_party_size">
					<?php
					echo WPRM()->html->text( array(
						'name'	=> 'party_sizes[' . $key . '][size]',
						'value' => $rate['size'],
					) );
					?>
				</td>
				<td><span class="wprm_remove_party_size button-secondary"><?php _e( 'Remove Size', 'wprm' ); ?></span></td>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td class="wprm_party_size">
					<?php echo WPRM()->html->text( array(
						'name'             => 'party_sizes[0][size]'
					) ); ?>
				</td>
				<td><span class="wprm_remove_party_size button-secondary"><?php _e( 'Remove Size', 'wprm' ); ?></span></td>
			</tr>
		<?php endif; ?>
	</table>
	<p>
		<span class="button-secondary" id="wprm_add_party_size"><?php _e( 'Add Option', 'wprm' ); ?></span>
	</p>
	<?php
	echo ob_get_clean();
}

/**
 * Dates Callback
 *
 * Renders dates table
 *
 * @since 1.0.0
 * @param array $args Arguments passed by the setting
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_dates_to_exclude_callback($args) {
	global $wprm_options;
	$dates = wprm_get_dates_to_exclude();

	ob_start(); ?>
	<p><?php echo $args['desc']; ?></p>

	<table id="wprm_dates_to_exclude" class="wp-list-table widefat fixed posts">
		<thead>
			<tr>
				<th scope="col" class="wprm_date_to_exclude"><?php _e( 'Date To Exclude', 'wprm' ); ?></th>
				<th scope="col"><?php _e( 'Remove', 'wprm' ); ?></th>
			</tr>
		</thead>
		<?php if( ! empty( $dates ) ) : ?>
			<?php foreach( $dates as $key => $date ) : ?>
				<tr>
				<td class="wprm_date_to_exclude">
					<?php
					echo WPRM()->html->text( array(
						'name'	=> 'date_to_exclude[' . $key . '][date]',
						'value' => $date['date'],
					) );
					?>
				</td>
				<td><span class="wprm_remove_date_to_exclude button-secondary"><?php _e( 'Remove Date', 'wprm' ); ?></span></td>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td class="wprm_date_to_exclude">
					<?php echo WPRM()->html->text( array(
						'name'             => 'date_to_exclude[0][date]'
					) ); ?>
				</td>
				<td><span class="wprm_remove_date_to_exclude button-secondary"><?php _e( 'Remove Date', 'wprm' ); ?></span></td>
			</tr>
		<?php endif; ?>
	</table>
	<p>
		<span class="button-secondary" id="wprm_add_date_to_exclude"><?php _e( 'Add New Date', 'wprm' ); ?></span>
	</p>
	<?php
	echo ob_get_clean();
}

/**
 * Hook Callback
 *
 * Adds a do_action() hook in place of the field
 *
 * @since 1.0.8.2
 * @param array $args Arguments passed by the setting
 * @return void
 */
function wprm_hook_callback( $args ) {
	do_action( 'wprm_' . $args['id'] );
}

/**
 * Set manage_options as the cap required to save WPRM settings pages
 *
 * @since 1.9
 * @return string capability required
 */
function wprm_set_settings_cap() {
	return 'manage_options';
}
add_filter( 'option_page_capability_wprm_settings', 'wprm_set_settings_cap' );
