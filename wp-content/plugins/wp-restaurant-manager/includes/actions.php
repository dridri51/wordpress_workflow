<?php
/**
 * Plugin Actions
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add Plugin Menu Page
 * @since 1.0.0
 * @global $wprm_settings_page
 */
function wprm_add_options_link() {
	global $wprm_settings_page;
	$wprm_settings_page = add_submenu_page( 'edit.php?post_type=wprm_menu', __( 'Restaurant Manager Settings', 'wprm' ), __( 'Restaurant Settings', 'wprm' ), 'manage_options', 'wprm-settings', 'wprm_options_page' );
}
add_action('admin_menu', 'wprm_add_options_link', 10);

/**
 * Adds WPRM Version to the <head> tag
 *
 * @since 1.0.0
 * @return void
*/
function wprm_version_in_header(){
	echo '<meta name="generator" content="WP Restaurant Manager v' . WPRM_VERSION . '" />' . "\n";
}
add_action( 'wp_head', 'wprm_version_in_header' );

/**
 * Add box into the sidebar of the settings page.
 * @since 1.0.0
 * @return void
 */
function wprm_add_settings_box_advert() {

	echo '<div class="postbox">
			<h3 class="hndle">'.__('WP Restaurant Manager','wprm').'<span class="plus">'.__('Pro Version','wprm').'</span></h3>
			<div class="inside">
				'.__('Upgrade to WP Restaurant Manager Pro for exceptional features, including a detailed calendar view, SMS notifications, custom booking fields, scheduled notifications and much more. Use the coupon code below for a 5% discount.','wprm').'
			<br/><br/>
			<code style="display:block;text-align:center">WPRM5OFF</code>
			</div>
			<div id="major-publishing-actions">
				<a href="https://themesdepot.org/plugins/wp-restaurant-manager/" target="_blank" class="button-primary">'.__('Upgrade To Pro Version','wprm').'</a>
			</div>
		</div>';
	
	echo '<div class="postbox">
			<h3 class="hndle">'.__('Premium WordPress Themes','wprm').'</h3>
			<div class="inside">
				'.__('Are you looking for Premium WordPress Themes? <a href="http://themesdepot.org" target="_blank">ThemesDepot</a> provides premium and affordable WordPress themes for any kind of website.','wprm').'
			</div>
			<div id="major-publishing-actions">
				<a href="http://themeforest.net/user/ThemesDepot/portfolio?ref=ThemesDepot" target="_blank" class="button-secondary">'.__('Browse Premium Themes','wprm').'</a>
			</div>
		</div>';

	echo '<div class="postbox">
			<h3 class="hndle">'.__('More WordPress Plugins','wprm').'</h3>
			<div class="inside">
				'.__('<a href="http://themesdepot.org" target="_blank">ThemesDepot</a> provides many free and premium WordPress plugins ready to extend your WordPress powered website with many useful features.','wprm').'
			</div>
			<div id="major-publishing-actions">
				<a href="http://themesdepot.org/plugins" target="_blank" class="button-secondary">'.__('Browse WordPress Plugins','wprm').'</a>
			</div>
		</div>';
}
add_action('wprm_settings_sidebar','wprm_add_settings_box_advert', 10);

/**
 * Add links next to title in settings page
 * @since 1.0.0
 * @return void
 */
function wprm_add_links_to_title() {
	echo '<a href="https://themesdepot.org/plugins/wp-restaurant-manager/" class="add-new-h2" target="_blank">'.__('Get Premium Version', 'wprm').'</a>';
	echo '<a href="http://profiles.wordpress.org/alessandrotesoro/" class="add-new-h2" target="_blank">'.__('Get More Free Plugins', 'wprm').'</a>';
	echo '<a href="http://themeforest.net/user/ThemesDepot/portfolio?ref=ThemesDepot" class="add-new-h2" target="_blank">'.__('Browse Premium WordPress Themes', 'wprm').'</a>';
}
add_action('wprm_next_to_settings_title', 'wprm_add_links_to_title');

/**
 * Displays an upgrade message below the plugin description.
 * @since 1.0.0
 * @return void
 * @todo  Test the message when update notifications are displaying.
 */
function wprm_display_upgrade_message( $plugin_file, $plugin_data, $status ) {

	$the_message = sprintf(__('<span>Upgrade to <a href="%s" target="_blank">WP Restaurant Manager Pro</a> for more features including: SMS Notifications, custom designed HTML emails, MailChimp integration, custom fields, scheduled notifications and much more.</span> <a href="%s" class="button" target="_blank">Read More &raquo;</a> ','wprm'), 'https://themesdepot.org/plugins/wp-restaurant-manager/', 'https://themesdepot.org/plugins/wp-restaurant-manager/' );

	echo '<tr class="update"><td>&nbsp;</td><td colspan="2" class="update-nag2"><div style="background-color: #fcf3ef;font-weight: 400;padding: 15px 12px;margin: -10px; 0px 0px 0px">'.$the_message.'</div></td></tr>';

}
//add_action('after_plugin_row_'.WPRM_SLUG, 'wprm_display_upgrade_message', 10, 3 );

/**
 * Register Widgets
 *
 * @since 1.0.0
 * @return void
*/
if(!function_exists('wprm_register_widgets')) : 

	function wprm_register_widgets() {
		register_widget( 'WPRM_Booking_Form' );
		register_widget( 'WPRM_Opening_Times' );
	}

endif;
add_action('widgets_init', 'wprm_register_widgets', 1);
