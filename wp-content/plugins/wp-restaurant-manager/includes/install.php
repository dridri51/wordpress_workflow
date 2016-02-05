<?php
/**
 * Install Function
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Install
 *
 * Runs on plugin install by setting up the post types, custom taxonomies,
 * flushing rewrite rules to initiate the new post types slugs and also 
 * populates the settings fields for those plugin pages. 
 * After successful install, the user is redirected to the WPRM Welcome
 * screen.
 *
 * The function also prevents Plugin Activation if host does not meet the requirements.
 * Php 5.2 was deprecated in 2011 Run away from your host if you're still using php 5.2
 *
 * @since 1.0.0
 * @global $wpdb
 * @global $wp_version
 * @return void
 */
function wprm_install() {
	global $wpdb, $wprm_options, $wp_version;

	// Disable plugin if php < 5.3
	if (version_compare(PHP_VERSION, '5.3', '<')) { 
    	deactivate_plugins(basename(__FILE__)); // Deactivate ourself 
    	wp_die("Sorry, but you can't run this plugin, it requires PHP 5.3 or higher. Contact your host and request a php update. Php 5.2 was deprecated in 2011 Run away from your host if you're still using php 5.2"); 
    }

	// Clear the permalinks
	flush_rewrite_rules();

	add_role( 'restaurant_bookings_manager', __('Bookings Manager','wprm'), array( 'read' => true  ) );

	// Add the roles you'd like to administer the custom post types
	$roles = array('restaurant_bookings_manager','administrator');
		
	// Loop through each role and assign capabilities
	foreach($roles as $the_role) { 
		$role = get_role($the_role);
		$role->add_cap( 'read' );
		$role->add_cap( 'read_wprm_reservation');
		$role->add_cap( 'read_private_wprm_reservations' );
		$role->add_cap( 'edit_wprm_reservation' );
		$role->add_cap( 'edit_wprm_reservations' );
		$role->add_cap( 'edit_others_wprm_reservations' );
		$role->add_cap( 'edit_published_wprm_reservations' );
		$role->add_cap( 'publish_wprm_reservations' );
		$role->add_cap( 'delete_others_wprm_reservations' );
		$role->add_cap( 'delete_private_wprm_reservations' );
		$role->add_cap( 'delete_published_wprm_reservations' );
	}

	// Add Upgraded From Option
	$current_version = get_option( 'wprm_version' );
	if ( $current_version ) {
		update_option( 'wprm_version_upgraded_from', $current_version );
	}

	update_option( 'wprm_version', WPRM_VERSION );

	// Bail if activating from network, or bulk
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
		return;
	}

	// Add the transient to redirect
	set_transient( '_wprm_activation_redirect', true, 30 );
}
register_activation_hook( WPRM_PLUGIN_FILE, 'wprm_install' );

/**
 * Post-installation
 *
 * Runs just after plugin installation and exposes the
 * wprm_after_install hook.
 *
 * @since 1.0.0
 * @return void
 */
function wprm_after_install() {

	if ( ! is_admin() ) {
		return;
	}

	$wprm_options = get_transient( '_wprm_installed' );

	// Exit if not in admin or the transient doesn't exist
	if ( false === $wprm_options ) {
		return;
	}

	// Delete the transient
	delete_transient( '_wprm_installed' );

	do_action( 'wprm_after_install', $wprm_options );
}
add_action( 'admin_init', 'wprm_after_install' );