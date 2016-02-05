<?php
/**
 * Uninstall WPRM
 *
 * @package     wprm
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

// Load plugin file
include_once( 'wp-restaurant-manager.php' );

global $wpdb, $wprm_options, $wp_roles;

if( wprm_get_option( 'uninstall_on_delete' ) ) {

	/** Delete All the Custom Post Types */
	$wprm_taxonomies = array( 'menu_tag', 'menu_category' );
	$wprm_post_types = array( 'wprm_menu', 'wprm_reservations' );
	foreach ( $wprm_post_types as $post_type ) {
	
		$wprm_taxonomies = array_merge( $wprm_taxonomies, get_object_taxonomies( $post_type ) );
		$items = get_posts( array( 'post_type' => $post_type, 'post_status' => 'any', 'numberposts' => -1, 'fields' => 'ids' ) );

		if ( $items ) {
			foreach ( $items as $item ) {
				wp_delete_post( $item, true);
			}
		}
	}

	/** Delete All the Terms & Taxonomies */
	foreach ( array_unique( array_filter( $wprm_taxonomies ) ) as $taxonomy ) {
		
		$terms = $wpdb->get_results( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('%s') ORDER BY t.name ASC", $taxonomy ) );
		
		// Delete Terms
		if ( $terms ) {
			foreach ( $terms as $term ) {
				$wpdb->delete( $wpdb->term_taxonomy, array( 'term_taxonomy_id' => $term->term_taxonomy_id ) );
				$wpdb->delete( $wpdb->terms, array( 'term_id' => $term->term_id ) );
			}
		}
		
		// Delete Taxonomies
		$wpdb->delete( $wpdb->term_taxonomy, array( 'taxonomy' => $taxonomy ), array( '%s' ) );
	}

	/** Delete all the Plugin Options */
	delete_option( 'wprm_settings' );
	
}