<?php
/**
 * Plugin Filters
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add Settings Link To WP-Plugin Page
 * @since    1.0.0
 */
function wprm_add_settings_link( $links ) {
	$settings_link = '<a href="'.admin_url( 'edit.php?post_type=wprm_menu&page=wprm-settings' ).'">'.__('Settings','wprm').'</a>';
	array_push( $links, $settings_link );
	return $links;
}
add_filter( "plugin_action_links_".WPRM_SLUG , 'wprm_add_settings_link');

/**
 * Plugin row meta links
 * @since 1.0.0
 */
function wprm_plugin_row_meta( $input, $file ) {
	
	if ( $file != 'wp-restaurant-manager/wp-restaurant-manager.php' )
		return $input;

	$links = array(
		'<a href="http://themeforest.net/user/ThemesDepot/portfolio" target="_blank">' . esc_html__( 'Get Premium WordPress Themes', 'wprm' ) . '</a>',
		'<a href="http://themesdepot.org/plugins/" target="_blank">' . esc_html__( 'Get More Plugins', 'wprm' ) . '</a>',
	);

	$input = array_merge( $input, $links );

	return $input;
}
add_filter( 'plugin_row_meta', 'wprm_plugin_row_meta', 10, 2 );

/**
 * Add rating links to the admin dashboard
 *
 * @since	    1.0.0
 * @global		string $typenow
 * @param       string $footer_text The existing footer text
 * @return      string
 */
function wprm_admin_rate_us( $footer_text ) {
	global $typenow;

	if ( $typenow == 'wprm_menu' ) {
		$rate_text = sprintf( __( 'Thank you for using <a href="%1$s" target="_blank">WP Restaurant Manager</a>! Please <a href="%2$s" target="_blank">rate us</a> on <a href="%2$s" target="_blank">WordPress.org</a>', 'wprm' ),
			'https://themesdepot.org',
			'http://wordpress.org/support/view/plugin-reviews/wp-restaurant-manager?filter=5#postform'
		);

		return str_replace( '</span>', '', $footer_text ) . ' | ' . $rate_text . '</span>';
	} else {
		return $footer_text;
	}
}
add_filter( 'admin_footer_text', 'wprm_admin_rate_us' );