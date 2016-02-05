<?php
/**
 * Metaboxes Functions
 * Handles all the custom fields for each custom post type.
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 * @version  1.0.0
 */

add_filter( 'cmb_meta_boxes', 'wprm_custom_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 * @since  1.0.0
 * @version  1.0.0
 */
function wprm_custom_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_wprm_';

	/**
	 * Metabox to be displayed.
	 */
	$meta_boxes['wprm_menu_item_details'] = array(
		'id'         => 'wprm_menu_item_details',
		'title'      => __( 'Menu Item Details', 'wprm' ),
		'pages'      => array( 'wprm_menu', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => apply_filters( 'wprm_menu_item_details_metaboxes_fields', array(
			array(
				'name' => __( 'Item Price', 'wprm' ),
				'desc' => __( 'Enter the price of this menu item.', 'wprm' ),
				'id'   => $prefix . 'item_price',
				'type' => 'text_small',
			),
			array(
				'name'    => __( 'Spicy Level', 'wprm' ),
				'desc'    => __( 'Select the spicy level of this dish. 0 indicates the dish is not spicy/hot, 5 indicates the dish is extremely hot and spicy.', 'wprm' ),
				'id'      => $prefix . 'spicy_level',
				'type'    => 'select',
				'options' => wprm_get_spicy_levels(),
			),
			array(
				'name'    => __( 'Vegetarian Item', 'wprm' ),
				'desc'    => __( 'Select if this item is vegetarian.', 'wprm' ),
				'id'      => $prefix . 'is_vegetarian',
				'type'    => 'select',
				'options' => array(
					'yes' => __( 'Yes', 'wprm' ),
					'no'   => __( 'No', 'wprm' ),
				),
			),
		) ) 
	);

	$meta_boxes['wprm_menu_item_nutrition'] = array(
		'id'         => 'wprm_menu_item_nutrition',
		'title'      => __( 'Menu Item Nutritional Information', 'wprm' ),
		'pages'      => array( 'wprm_menu', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => apply_filters( 'wprm_menu_item_nutrition_metaboxes_fields', array(
			array(
				'name' => __( 'Calories', 'wprm' ),
				'desc' => __( 'Enter the calories value. Ex: 250kcal', 'wprm' ),
				'id'   => $prefix . 'calories',
				'type' => 'text_medium',
 			),
 			array(
				'name' => __( 'Cholesterol', 'wprm' ),
				'desc' => __( 'Enter the Cholesterol value. Ex: 50mg', 'wprm' ),
				'id'   => $prefix . 'cholesterol',
				'type' => 'text_medium',
 			),
 			array(
				'name' => __( 'Fiber', 'wprm' ),
				'desc' => __( 'Enter the Fiber value. Ex: 2g', 'wprm' ),
				'id'   => $prefix . 'fiber',
				'type' => 'text_medium',
 			),
 			array(
				'name' => __( 'Sodium', 'wprm' ),
				'desc' => __( 'Enter the Sodium value. Ex: 175mg', 'wprm' ),
				'id'   => $prefix . 'sodium',
				'type' => 'text_medium',
 			),
 			array(
				'name' => __( 'Carbohydrates', 'wprm' ),
				'desc' => __( 'Enter the Carbohydrates value. Ex: 4.5g', 'wprm' ),
				'id'   => $prefix . 'carbohydrates',
				'type' => 'text_medium',
 			),
 			array(
				'name' => __( 'Fat', 'wprm' ),
				'desc' => __( 'Enter the Fat value. Ex: 3g', 'wprm' ),
				'id'   => $prefix . 'fat',
				'type' => 'text_medium',
 			),
 			array(
				'name' => __( 'Protein', 'wprm' ),
				'desc' => __( 'Enter the Protein value. Ex: 1.7g', 'wprm' ),
				'id'   => $prefix . 'protein',
				'type' => 'text_medium',
 			),
		) )
	);

	// Bookings metaboxes
	$meta_boxes['wprm_bookings'] = array(
		'id'         => 'wprm_bookings',
		'title'      => __( 'Reservation Details', 'wprm' ),
		'pages'      => array( 'wprm_reservations', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => apply_filters( 'wprm_bookings_metaboxes_fields', array(
			array(
				'name' => __( 'Full Name', 'wprm' ),
				'desc' => __( 'Full name of the person who made the reservation.', 'wprm' ),
				'id'   => $prefix . 'reservation_name',
				'type' => 'text',
 			),
 			array(
				'name' => __( 'Phone Number', 'wprm' ),
				'desc' => __( 'Phone number of the person who made the reservation.', 'wprm' ),
				'id'   => $prefix . 'reservation_phone_number',
				'type' => 'text',
 			),
 			array(
				'name' => __( 'Email', 'wprm' ),
				'desc' => __( 'Email address of the person who made the reservation.', 'wprm' ),
				'id'   => $prefix . 'reservation_email',
				'type' => 'text_email',
 			),
 			array(
				'name'    => __( 'Party Size', 'wprm' ),
				'desc'    => __( 'The amount of people for this reservation.', 'wprm' ),
				'id'      => $prefix . 'reservation_size',
				'type'    => 'text'
			),
			array(
				'name' => __( 'Reservation Date', 'wprm' ),
				'desc' => __( 'The date of the reservation.', 'wprm' ),
				'id'   => $prefix . 'reservation_date',
				'type' => 'text_date_timestamp',
				'date_format' => 'F d, Y',
			),
			array(
				'name' => __( 'Reservation Time', 'wprm' ),
				'desc' => __( 'The time of the reservation.', 'wprm' ),
				'id'   => $prefix . 'reservation_time',
				'type' => 'text',
			),
			array(
				'name' => __( 'Reservation Extra Details', 'wprm' ),
				'desc' => __( 'Extra details provided by the customer.', 'wprm' ),
				'id'   => $prefix . 'reservation_message',
				'type' => 'textarea',
			),
		) )
	);

	return $meta_boxes;
}

add_action( 'init', 'wprm_cmb_initialize_cmb_meta_boxes', 9999 );

/**
 * Initialize the metabox class.
 * @since  1.0.0
 * @version  1.0.0
 * @return  void
 */
function wprm_cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once WPRM_PLUGIN_DIR . 'includes/admin/metaboxes/init.php';

}
