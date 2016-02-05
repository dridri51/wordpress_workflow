<?php
/**
 * Contextual Help
 *
 * @package     WPRM
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Settings contextual help.
 *
 * @access      private
 * @since       1.0.0
 * @return      void
 */
if (!function_exists('wprm_settings_contextual_help')):
    function wprm_settings_contextual_help() {
    	$screen = get_current_screen();

    	if ( $screen->id != 'wprm_menu_page_wprm-settings' )
    		return;

    	$screen->set_help_sidebar(
    		'<p><strong>' . sprintf( __( 'For more information:', 'wprm' ) . '</strong></p>' .
    		'<p>' . sprintf( __( 'Visit the <a href="%s">documentation</a> on the ThemesDepot Support website.', 'wprm' ), esc_url( 'http://support.themesdepot.org/hc/en-us/categories/200294021-WP-Restaurant-Manager' ) ) ) . '</p>' .
    		'<p>' . sprintf(
    					__( 'Should you spot a bug, feel free to <a href="%s">Post an issue</a> on <a href="%s">GitHub</a>. View <a href="%s">more plugins</a> or <a href="%s">themes</a>. %s', 'wprm' ),
    					esc_url( 'https://github.com/alessandrotesoro/wp-restaurant-manager-lite/issues' ),
    					esc_url( 'https://github.com/alessandrotesoro/wp-restaurant-manager-lite/' ),
    					esc_url( 'https://themesdepot.org/plugins/' ),
    					esc_url( 'https://themesdepot.org/themes/' ),
    					'<br/><br/><a class="button" href="https://themesdepot.org/plugins/wp-restaurant-manager/">'.__('Upgrade to pro','wprm').'</a>'
    				) . '</p>'
    	);

    	$screen->add_help_tab( array(
    		'id'	    => 'wprm-settings-general',
    		'title'	    => __( 'General Settings', 'wprm' ),
    		'content'	=> 
    			'<p>' . __( 'This screen provides the most basic settings for configuring your plugin. You can set the booking form style, make some adjustment to the layout of the form, and customize the way your food menu looks.', 'wprm' ) . '</p>' .
    			'<p><strong>' . __( 'About layout adjustments:', 'wprm' ) . '</strong></p>' . 
    			'<p>' . __( 'Please note that the options below here to adjust the layout of the form, may have minor or no effect on your site. It is impossible for those options to work on each and every theme. Each single theme is different from another and together with other plugins that might be installed on each setup, it is impossible to "predict" any conflict that might happen.', 'wprm' ) . '</p>' .
    			sprintf( __( 'Should you have issues with the looking of the booking form or your menu, feel free to open a topic on the <a href="%s">community support forum</a>. For more information about support, please check the support tab on the left here.', 'wprm' ), esc_url( 'https://github.com/alessandrotesoro/wp-restaurant-manager-lite/issues' ) ),
    					 
    	) );

    	$screen->add_help_tab( array(
    		'id'	    => 'wprm-settings-booking',
    		'title'	    => __( 'Booking Settings', 'wprm' ),
    		'content'	=> 
    			'<p>' . __( 'This screen provides you tools to configure and customize your booking form including dates and times configuration, booking intervals, messages and formatting.', 'wprm' ) . '</p>' .
    			'<p><strong>' . __( 'Booking Page:', 'wprm' ) . '</strong></p>' .
    			'<p>' . __( 'It is important that you select the page where you have placed the booking shortcode. The plugin has been designed to load the required scripts and styles only when required therefore you must select your booking page. Simply create a page and then add the [wprm_booking_form] shortcode and save it.', 'wprm' ) . '</p>' .
    			'<p><strong>' . __( 'Successful Page:', 'wprm' ) . '</strong></p>' .
    			'<p>' . __( 'The succesful page should be selected only when you have decided to redirect your users to another page after making a reservation. This page can contain anything you want. Remember to change the option "Booking Confirmation Behavior" to decide what happens after a user makes a booking.', 'wprm' ) . '</p>' .
    			'<p><strong>' . __( 'Opening and closing time:', 'wprm' ) . '</strong></p>' .
    			'<p>' . __('The opening and closing time option allow you to set the start and end times available for booking. For example, if you set the opening to 7.30 AM and closing at 14.00 PM, the booking times available will be within that time. Please note that you must follow this format when setting up the time HH:MM AM/PM','wprm') . '</p>' .
    			'<p><strong>' . __( 'Party Sizes:', 'wprm' ) . '</strong></p>' .
    			'<p>' . __( 'Use the party sizes editor to manage the options available on the booking form, this allows the customer to select how many people need a table from a specific reservation.', 'wprm' ) . '</p>' .
    			'<p><strong>' . __( 'Disable Calendar Dates:', 'wprm' ) . '</strong></p>' .
    			'<p>' . __( 'Use the following generator to disable certain dates from the calendar. Disabled dates will not be available for booking. Please do not manually edit the date text. Use the datepicker to enter the date. The date format must be the one selected from the datepicker.', 'wprm' ) . '</p>' 
    	) );

    	$screen->add_help_tab( array(
    		'id'	    => 'wprm-settings-datetime',
    		'title'	    => __( 'Date/Time Formats', 'wprm' ),
    		'content'	=> 
    			'<p><strong>' . __( 'Date Formats', 'wprm' ) . '</strong></p>' .
    			'<table class="table">
                <thead>
                    <tr>
                        <th>'.__('Rule','wprm').'</th>
                        <th>'.__('Description','wprm').'</th>
                        <th>'.__('Result','wprm').'</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>d</code></td>
                        <td>'.__('Date of the month','wprm').'</td>
                        <td>1 – 31</td>
                    </tr>
                    <tr>
                        <td><code>dd</code></td>
                        <td>'.__('Date of the month with a leading zero','wprm').'</td>
                        <td>01 – 31</td>
                    </tr>
                    <tr>
                        <td><code>ddd</code></td>
                        <td>'.__('Day of the week in short form','wprm').'</td>
                        <td>Sun – Sat</td>
                    </tr>
                    <tr>
                        <td><code>dddd</code></td>
                        <td>'.__('Day of the week in full form','wprm').'</td>
                        <td>Sunday – Saturday</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td><code>m</code></td>
                        <td>'.__('Month of the year','wprm').'</td>
                        <td>1 – 12</td>
                    </tr>
                    <tr>
                        <td><code>mm</code></td>
                        <td>'.__('Month of the year with a leading zero','wprm').'</td>
                        <td>01 – 12</td>
                    </tr>
                    <tr>
                        <td><code>mmm</code></td>
                        <td>'.__('Month name in short form','wprm').'</td>
                        <td>Jan – Dec</td>
                    </tr>
                    <tr>
                        <td><code>mmmm</code></td>
                        <td>'.__('Month name in full form','wprm').'</td>
                        <td>January – December</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td><code>yy</code></td>
                        <td>'.__('Year in short form','wprm').' <b>*</b></td>
                        <td>00 – 99</td>
                    </tr>
                    <tr>
                        <td><code>yyyy</code></td>
                        <td>'.__('Year in full form','wprm').'</td>
                        <td>2000 – 2999</td>
                    </tr>
                </tbody>
            </table>' . 
            '<p><strong>' . __( 'Time Formats', 'wprm' ) . '</strong></p>' .
            '<table class="table">
                <thead>
                    <tr>
                        <th>'.__('Rule','wprm').'</th>
                        <th>'.__('Description','wprm').'</th>
                        <th>'.__('Result','wprm').'</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>h</code></td>
                        <td>'.__('Hour in 12-hour format','wprm').'</td>
                        <td>1 – 12</td>
                    </tr>
                    <tr>
                        <td><code>hh</code></td>
                        <td>'.__('Hour in 12-hour format with a leading zero','wprm').'</td>
                        <td>01 – 12</td>
                    </tr>
                    <tr>
                        <td><code>H</code></td>
                        <td>'.__('Hour in 24-hour format','wprm').'</td>
                        <td>0 – 23</td>
                    </tr>
                    <tr>
                        <td><code>HH</code></td>
                        <td>'.__('Hour in 24-hour format with a leading zero','wprm').'</td>
                        <td>00 – 23</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td><code>i</code></td>
                        <td>'.__('Minutes','wprm').'</td>
                        <td>00 – 59</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td><code>a</code></td>
                        <td>'.__('Day time period','wprm').'</td>
                        <td>a.m. / p.m.</td>
                    </tr>
                    <tr>
                        <td><code>A</code></td>
                        <td>'.__('Day time period in uppercase','wprm').'</td>
                        <td>AM / PM</td>
                    </tr>
                </tbody>
            </table>'				 
    	) );

    	$screen->add_help_tab( array(
    		'id'	    => 'wprm-settings-notifications',
    		'title'	    => __( 'Notification Settings', 'wprm' ),
    		'content'	=> 
    			'<p>' . __( 'This screen provides the settings to customize the email notifications sent by the plugin upon reservation and changes to reservations.', 'wprm' ) . '</p>' .
    			'<p><strong>' . __( 'Admin notifications:', 'wprm' ) . '</strong></p>' .
    			'<p>' . sprintf(__( 'Enable admin notifications to send an email each time a new booking request has been made. The email notification is sent to the site admin email. You can change your admin email into your <a href="%s">general settings.</a>', 'wprm' ), admin_url( 'options-general.php#admin_email' ) ) . '</p>' .		 
    			'<p><strong>' . __( 'User notifications:', 'wprm' ) . '</strong></p>' .
    			'<p>' . __( 'This area let\'s you control the emails that are sent to customers upon reservation or when the status of their reservation changes. You can also change the email name and from parameters that user see when they receive the email from your site. Each email text supports different tags that can be used to display information related to their booking. It is important that you do not use the tags {approve_booking}, {reject_booking}, {all_bookings} and {view_booking} into emails sent to users. Those links are for administrators only.', 'wprm' ) . '</p>'
    	) );

    	$screen->add_help_tab( array(
    		'id'	    => 'wprm-settings-managing',
    		'title'	    => __( 'Managing Reservations', 'wprm' ),
    		'content'	=> 
    			'<p>' . __( 'This screen provides basic information on how to manage your reservations.', 'wprm' ) . '</p>' .
    			'<p><strong>' . __( 'Receiving reservations:', 'wprm' ) . '</strong></p>' .
    			'<p>' . sprintf(__( 'In order to receive reservations you need to place the [wprm_booking_form] shortcode inside a page. You also need to make sure, you have selected that page into the booking settings page. Once a reservation is made, it will be saved into your <a href="%s">manage reservations panel</a>. From this panel, you can approve/reject/delete reservations.', 'wprm' ), admin_url('edit.php?post_type=wprm_reservations') ) . '</p>' .
    			'<p><strong>' . __( 'Approving reservations:', 'wprm' ) . '</strong></p>' .
    			'<p>' . sprintf(__( 'Navigate to your <a href="%s">reservations management panel</a>, here you will see a list of all your bookings. If you wish to approve a booking, simply hover with your mouse on the reservation and a list of links will appear. One of the links is "approve", by pressing the approve button, the reservation will be approved and if enabled, a mail notification will be sent.', 'wprm' ), admin_url('edit.php?post_type=wprm_reservations') ) . '</p>' .
    			'<p><strong>' . __( 'Rejecting reservations:', 'wprm' ) . '</strong></p>' .
    			'<p>' . sprintf(__( 'Navigate to your <a href="%s">reservations management panel</a>, here you will see a list of all your bookings. If you wish to approve a booking, simply hover with your mouse on the reservation and a list of links will appear. One of the links is "reject", by pressing the reject button, the reservation will be rejected and if enabled, a mail notification will be sent.', 'wprm' ), admin_url('edit.php?post_type=wprm_reservations') ) . '</p>' .
    			'<p><strong>' . __( 'Filtering reservations:', 'wprm' ) . '</strong></p>' .
    			'<p>' . sprintf(__( 'WPRM offers the ability to filter reservations and display upcoming ones. By visiting the <a href="%s">upcoming reservations panel</a> you will be able to filter approved reservations by a set period of days. The available options are today, tomorrow, 2 days and 3 days. Developers who have coding knowledge, can refer to the developers online documentation and add/remove timings.', 'wprm' ), admin_url('edit.php?post_type=wprm_reservations&reservations_filter=upcoming') ) . '</p>'
    	) );

    	$screen->add_help_tab( array(
    		'id'	    => 'wprm-settings-itemsmenu',
    		'title'	    => __( 'Menu Management', 'wprm' ),
    		'content'	=> 
    			'<p>' . __( 'WPRM Comes with the ability to display your food menu. Your food menu can also be organized in different categories. Each menu item has it\'s own settings and nutritional information. ', 'wprm' ) . '</p>' .
    			'<p><strong>' . __( 'Displaying your menu:', 'wprm' ) . '</strong></p>' .
    			'<p>' . __( 'To display your menu, WPRM comes with an handy shortcodes manager available into the page editor. Click the "fork and knife" icon and a list of shortcodes will appear. Once you have created and categorized your menu, you can use the different available shortcodes to display your menu.', 'wprm' ) . '</p>' 
    	) );

    	$screen->add_help_tab( array(
    		'id'	    => 'wprm-settings-shortcodes',
    		'title'	    => __( 'Shortcodes', 'wprm' ),
    		'content'	=> 
    			'<p>' . __( 'To display your menu, WPRM comes with an handy shortcodes manager available into the page editor. Click the "fork and knife" icon and a list of shortcodes will appear. Click on the shortcode you wish to use, and if needed, a configuration menu will appear. ', 'wprm') . '</p>' .
    			'<p><strong>' . __( 'Booking Form Shortcode:', 'wprm' ) . '</strong></p>' .
    			'<p>' . __( 'The booking form shortcode allows you to display the booking form on any page/post.', 'wprm' ) . '</p>' .
    			'<p><strong>' . __( 'Single Menu Item Shortcode:', 'wprm' ) . '</strong></p>' .
    			'<p>' . __( 'The single menu item shortcode allows you to pick a menu item by ID and display it on any page/post. The id number can be retrieved into your restaurant menu page, visit your menu items page and click the edit button the menu item you wish to display, look into your browser url and you will notice a number, that number is the ID.', 'wprm' ) . '</p>' .
    			'<p><strong>' . __( 'Menu by category Shortcode:', 'wprm' ) . '</strong></p>' .
    			'<p>' . __( 'The menu by category shortcode allows you to display a section or category of your menu by using the slug of the category. You can retrieve the slug of your category by going in Restaurant -> Menu Categories and look on the table on the right side of your screen, one of the column will display the slug.', 'wprm' ) . '</p>' .
    			'<p><strong>' . __( 'Full Menu shortcode:', 'wprm' ) . '</strong></p>' .
    			'<p>' . __( 'The Full Menu shortcode simply displays your full menu into a page or post.', 'wprm' ) . '</p>'
    	) );

    	$screen->add_help_tab( array(
    		'id'	    => 'wprm-settings-customization',
    		'title'	    => __( 'Customization', 'wprm' ),
    		'content'	=> 
    			'<p>' . sprintf(__( 'Please note that no support will be provided for customizations. If you are a developer, you can access the <a href="%s">developers documentation</a> and read about all the available filters and actions and templates ready for customization.', 'wprm' ), esc_url('http://support.themesdepot.org/hc/en-us/categories/200294021-WP-Restaurant-Manager') )  . '</p>'
    	) );

    	do_action( 'wprm_settings_contextual_help', $screen );
    }
endif;
add_action( 'load-wprm_menu_page_wprm-settings', 'wprm_settings_contextual_help' );
