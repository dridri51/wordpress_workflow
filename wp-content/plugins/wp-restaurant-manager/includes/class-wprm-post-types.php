<?php
/**
 * Post Type Functions
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPRM_Post_Types Class
 * Registers and sets up the Restaurant Manager Menu custom post type and taxonomies.
 * It also handles the customization of the admin part of each custom post type.
 *
 * @since 1.0.0
 */
class WPRM_Post_Types {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', array( $this,'wprm_setup_wprm_post_types'), 1 );
		add_action( 'admin_enqueue_scripts', array( $this,'wprm_admin_scripts') );
		add_filter( 'enter_title_here', array( $this, 'wprm_enter_title_here' ), 1, 2 );
		add_filter( 'post_updated_messages', array( $this, 'wprm_post_updated_messages' ) );
		add_filter( 'bulk_post_updated_messages', array( $this, 'wprm_bulk_post_updated_messages' ) );
		add_filter( 'post_row_actions', array( $this, 'wprm_action_row'), 10, 2 );
		add_filter( 'manage_edit-wprm_reservations_columns', array( $this, 'wprm_reservations_columns' ) );
		add_action( 'manage_wprm_reservations_posts_custom_column', array( $this, 'wprm_reservations_custom_columns_content' ), 2 );
		add_action( 'admin_print_styles-post-new.php', array( $this, 'wprm_remove_elements'), 10); 
		add_action( 'admin_print_styles-post.php', array( $this, 'wprm_remove_elements'), 10); 
    	add_filter( "views_edit-wprm_reservations", array( $this,'wprm_modify_reservations_views' ));
    	add_action( "restrict_manage_posts", array( $this, "wprm_menu_items_by_category" ) );
    	add_action( "restrict_manage_posts", array( $this, "wprm_menu_items_by_tag" ) );
    	add_action( 'init', array( $this, 'wprm_admin_post_approve_reservation'));
    	add_action( 'init', array( $this, 'wprm_admin_post_reject_reservation'));
    	add_action( 'admin_notices', array( $this, 'wprm_reservations_admin_notices') );
    	add_action( 'pre_get_posts', array( $this, 'wprm_admin_filter_upcoming_reservations'), 9999);
    	add_action( 'restrict_manage_posts', array($this, 'wprm_reservations_upcoming_reservations_days_selectors') );
    	add_filter( 'the_content', array( $this, 'wprm_menu_content' ) );
    	add_action( 'admin_footer-edit.php', array( $this, 'wprm_add_bulk_actions' ) );
		add_action( 'load-edit.php', array( $this, 'wprm_do_bulk_actions' ) );

	}

	/**
	 * Registers and sets up the Restaurant Manager Menu custom post type and taxonomies.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function wprm_setup_wprm_post_types() {

		// Register The Post Type
		$archives = defined( 'WPRM_DISABLE_ARCHIVE' ) && WPRM_DISABLE_ARCHIVE ? false : true;
		$slug     = defined( 'WPRM_TYPE_SLUG' ) ? WPRM_TYPE_SLUG : 'wprm-menu';
		$rewrite  = defined( 'WPRM_DISABLE_REWRITE' ) && WPRM_DISABLE_REWRITE ? false : array('slug' => $slug, 'with_front' => false);
		$reservations_position = current_user_can('administrator') ? 'edit.php?post_type=wprm_menu' : true;

		$labels = apply_filters( 'wprm_menu_labels', array(
			'name'                => _x( 'Menu Item', 'Post Type General Name', 'wprm' ),
			'singular_name'       => _x( 'Menu Item', 'Post Type Singular Name', 'wprm' ),
			'menu_name'           => __( 'Restaurant', 'wprm' ),
			'parent_item_colon'   => __( 'Parent Menu Item:', 'wprm' ),
			'all_items'           => __( 'All Menu Items', 'wprm' ),
			'view_item'           => __( 'View Menu Item', 'wprm' ),
			'add_new_item'        => __( 'Add New Menu Item', 'wprm' ),
			'add_new'             => __( 'New Menu Item', 'wprm' ),
			'edit_item'           => __( 'Edit Menu Item', 'wprm' ),
			'update_item'         => __( 'Update Menu Item', 'wprm' ),
			'search_items'        => __( 'Search Menu Items', 'wprm' ),
			'not_found'           => __( 'No Menu Items found', 'wprm' ),
			'not_found_in_trash'  => __( 'No Menu Items found in Trash', 'wprm' ),
		) );
		$rewrite = array(
			'slug'                => $slug,
		);
		$args = array(
			'label'               => __( 'WPRM', 'wprm' ),
			'description'         => __( 'WPRM Post Type', 'wprm' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'can_export'          => true,
			'has_archive'         => $archives,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'page',
		);
		register_post_type( 'wprm_menu', apply_filters( 'wprm_menu_post_type_args',$args) );

		// Register the taxonomy
		$labels = apply_filters( 'wprm_menu_categories_labels', array(
			'name'                       => _x( 'Menu Categories', 'Taxonomy General Name', 'wprm' ),
			'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'wprm' ),
			'menu_name'                  => __( 'Menu Categories', 'wprm' ),
			'all_items'                  => __( 'All Categories', 'wprm' ),
			'parent_item'                => __( 'Parent Category', 'wprm' ),
			'parent_item_colon'          => __( 'Parent Category:', 'wprm' ),
			'new_item_name'              => __( 'New Category Name', 'wprm' ),
			'add_new_item'               => __( 'Add New Category', 'wprm' ),
			'edit_item'                  => __( 'Edit Category', 'wprm' ),
			'update_item'                => __( 'Update Category', 'wprm' ),
			'separate_items_with_commas' => __( 'Separate Categories with commas', 'wprm' ),
			'search_items'               => __( 'Search Categories', 'wprm' ),
			'add_or_remove_items'        => __( 'Add or remove Categories', 'wprm' ),
			'choose_from_most_used'      => __( 'Choose from the most used Categories', 'wprm' ),
		) );
		$rewrite = array(
			'slug'                       => apply_filters( 'wprm_menu_categories_slug','menu-type'),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
			'query_var'                  => true,
			'rewrite'                    => $rewrite,
		);
		register_taxonomy( 'menu_category', 'wprm_menu', apply_filters( 'wprm_menu_categories_args', $args) );

		// Register the taxonomy
		$labels = apply_filters( 'wprm_menu_tags_labels', array(
			'name'                       => _x( 'Menu Tags', 'Taxonomy General Name', 'wprm' ),
			'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'wprm' ),
			'menu_name'                  => __( 'Menu Tags', 'wprm' ),
			'all_items'                  => __( 'All Tags', 'wprm' ),
			'parent_item'                => __( 'Parent Tag', 'wprm' ),
			'parent_item_colon'          => __( 'Parent Tag:', 'wprm' ),
			'new_item_name'              => __( 'New Tag Name', 'wprm' ),
			'add_new_item'               => __( 'Add New Tag', 'wprm' ),
			'edit_item'                  => __( 'Edit Tag', 'wprm' ),
			'update_item'                => __( 'Update Tag', 'wprm' ),
			'separate_items_with_commas' => __( 'Separate Tags with commas', 'wprm' ),
			'search_items'               => __( 'Search Tags', 'wprm' ),
			'add_or_remove_items'        => __( 'Add or remove Tags', 'wprm' ),
			'choose_from_most_used'      => __( 'Choose from the most used Tags', 'wprm' ),
		) );
		$rewrite = array(
			'slug'                       => apply_filters( 'wprm_menu_tags_slug','menu-tags'),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
			'query_var'                  => true,
			'rewrite'                    => $rewrite,
		);
		register_taxonomy( 'menu_tag', 'wprm_menu', apply_filters( 'wprm_menu_tags_args', $args) );

		// Reservation post type
		$labels = apply_filters( 'wprm_reservations_labels', array(
			'name'                => _x( 'Manage Reservations', 'Post Type General Name', 'wprm' ),
			'singular_name'       => _x( 'Reservation', 'Post Type Singular Name', 'wprm' ),
			'menu_name'           => __( 'Reservations', 'wprm' ),
			'parent_item_colon'   => __( 'Parent Reservation:', 'wprm' ),
			'all_items'           => __( 'Manage Reservations', 'wprm' ),
			'view_item'           => __( 'View Reservation', 'wprm' ),
			'add_new_item'        => __( 'Add New Reservation', 'wprm' ),
			'add_new'             => __( 'New Reservation', 'wprm' ),
			'edit_item'           => __( 'Edit Reservation', 'wprm' ),
			'update_item'         => __( 'Update Reservation', 'wprm' ),
			'search_items'        => __( 'Search Reservation', 'wprm' ),
			'not_found'           => __( 'No Reservation Found', 'wprm' ),
			'not_found_in_trash'  => __( 'No Reservation found in Trash', 'wprm' ),
		) );
		$rewrite = array(
			'slug'                => apply_filters( 'wprm_reservation_slug','wprm-reservation'),
		);
		$args = array(
			'label'               => __( 'Reservation', 'wprm' ),
			'description'         => __( 'Reservation Post Type', 'wprm' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => $reservations_position,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => array('wprm_reservation','wprm_reservations'),
            'map_meta_cap'        => true,
		);
		register_post_type( 'wprm_reservations', apply_filters( 'wprm_reservation_post_type_args',$args) );

		/**
		 * Post status
		 */
		register_post_status( 'pending', array(
			'label'                     => _x( 'Pending', 'post status', 'wprm' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>', 'wprm' ),
		) );
		register_post_status( 'reject', array(
			'label'                     => _x( 'Rejected', 'post status', 'wprm' ),
			'public'                    => true,
			'exclude_from_search'       => true,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Rejected <span class="count">(%s)</span>', 'Rejected <span class="count">(%s)</span>', 'wprm' ),
		) );

	}

	/**
	 * Load styles in the admin
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function wprm_admin_scripts() {

		// Load styles only on our page
		global $pagenow;

		if( 'edit.php' != $pagenow && isset($_GET['post_type']) && $_GET['post_type'] == 'wprm_reservations' )
			return;
		
		// Script and styles
		wp_enqueue_script( 'jquery' );
		wp_register_style( 'wprm-admin-res-css', WPRM_PLUGIN_URL . 'assets/css/wprm-admin.css', false, WPRM_VERSION);
		wp_enqueue_style( 'wprm-admin-res-css' );
		// Thickbox
		add_thickbox();
	}

	/**
	 * enter_title_here function.
	 * modifies the enter title here placeholder into the custom post type admin page.
	 *
	 * @access public
	 * @return $text string
	 */
	public function wprm_enter_title_here( $text, $post ) {
		
		// For Menu Items
		if ( $post->post_type == 'wprm_menu' )
			return __( 'Enter dish title.', 'wprm' );

		// For Reservations
		if ( $post->post_type == 'wprm_reservations' )
			return __( 'Enter reservation title.', 'wprm' );
		return $text;
	}

	/**
	 * post_updated_messages function.
	 *
	 * @access public
	 * @param mixed $messages
	 * @return void
	 */
	public function wprm_post_updated_messages( $messages ) {
		global $post, $post_ID;

		$messages['wprm_reservations'] = array(
			0 => '',
			1 => sprintf( __( 'Reservation successfully updated. %s', 'wprm' ), wprm_reservations_single_notifications_enabled() ),
			2 => __( 'Custom field updated.', 'wprm' ),
			3 => __( 'Custom field deleted.', 'wprm' ),
			4 => __( 'Reservation updated.', 'wprm' ),
			5 => isset( $_GET['revision'] ) ? sprintf( __( 'Reservation restored to revision from %s', 'wprm' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __( 'Reservation successfully created. %s', 'wprm' ), wprm_reservations_single_notifications_enabled() ),
			7 => __('Reservation saved.', 'wprm' ),
			8 => __( 'Reservation submitted.', 'wprm' ),
			9 => __( 'Reservation scheduled.', 'wprm' ),
			10 => __( 'Reservation draft updated.', 'wprm' ),
		);

		return $messages;
	}

	/**
	 * Modifies the text of the trash function.
	 *
	 * @access public
	 * @param mixed $bulk_messages
	 * @return array $bulk_messages
	 */
	public function wprm_bulk_post_updated_messages( $bulk_messages ) {
		global $post, $post_ID;

		$bulk_counts = array(
			'updated'   => isset( $_REQUEST['updated'] )   ? absint( $_REQUEST['updated'] )   : 0,
			'locked'    => isset( $_REQUEST['locked'] )    ? absint( $_REQUEST['locked'] )    : 0,
			'deleted'   => isset( $_REQUEST['deleted'] )   ? absint( $_REQUEST['deleted'] )   : 0,
			'trashed'   => isset( $_REQUEST['trashed'] )   ? absint( $_REQUEST['trashed'] )   : 0,
			'untrashed' => isset( $_REQUEST['untrashed'] ) ? absint( $_REQUEST['untrashed'] ) : 0,
		);

		$bulk_messages['wprm_reservations'] = array(
			'updated'   => _n( '%s reservation updated.', '%s reservation updated.', $bulk_counts['updated'], 'wprm' ),
			'locked'    => _n( '%s reservations not updated, somebody is editing it.', '%s reservations not updated, somebody is editing them.', $bulk_counts['locked'], 'wprm' ),
			'deleted'   => _n( '%s reservation permanently deleted.', '%s reservation permanently deleted.', $bulk_counts['deleted'], 'wprm' ),
			'trashed'   => _n( '%s reservation has been deleted.', '%s reservation have been deleted.', $bulk_counts['trashed'], 'wprm' ),
			'untrashed' => _n( '%s reservation restored from the Trash.', '%s reservation restored from the Trash.', $bulk_counts['untrashed'], 'wprm' ),
		);

		return $bulk_messages;
	}

	/**
	 * Action Row Links.
	 * Modifies the action links into the admin page.
	 *
	 * @access public
	 * @return $actions array contains all action links.
	 */
	public function wprm_action_row( $actions, $post ) {
		
		if ( $post->post_type == 'wprm_reservations' ) {
			unset($actions['inline hide-if-no-js']);
			unset($actions['view']);
			
			//check capabilites
           	$post_type_object = get_post_type_object( $post->post_type );
           	if ( !$post_type_object ) return;
           	if ( !current_user_can( 'publish_wprm_reservations', $post->ID ) ) return;
			
           	// Approve Reservation
			$actions['approve_reservation'] = '<a class="submitapprove" href="' . wprm_get_reservation_approve_link( $post->ID ) . '">'.__('Approve','wprm').'</a>';

			// Reject Reservation
			$actions['reject_reservation'] = '<a class="submitdelete" href="' . wprm_get_reservation_reject_link( $post->ID ) . '">'.__('Reject','wprm').'</a>';

		}

		return $actions;

	}

	/**
	 * Reservation columns function.
	 * Modifies the list of columns available into the reservation post type.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return void
	 */
	public function wprm_reservations_columns( $columns ) {
		if ( ! is_array( $columns ) )
			$columns = array();

		unset( $columns['titl2e'], $columns['date'], $columns['author'] );

		$columns["status"]     = __( "Status", 'wprm' );
		$columns["reservation_date"]     = __( "Reservation Date", 'wprm' );
		$columns["reservation_time"]     = __( "Reservation Time", 'wprm' );
		$columns["name"]     = __( "Full Name", 'wprm' );
		$columns["phone_number"]     = __( "Phone Number", 'wprm' );
		$columns["email"]     = __( "Email", 'wprm' );
		$columns["party"]     = __( "Party Size", 'wprm' );
		$columns["message"]     = __( "Message", 'wprm' );

		return $columns;
	}

	/**
	 * wprm_reservations_custom_columns_content function.
	 * Adds the content to the custom columns for the reservations post type
	 *
	 * @access public
	 * @param mixed $column
	 * @return void
	 */
	public function wprm_reservations_custom_columns_content( $column ) {
		global $post;

		switch ( $column ) {
			case "status" :
				echo wprm_get_reservation_status($post->ID);
			break;
			case "reservation_date" :
				echo date_i18n( get_option( 'date_format' ), get_post_meta( $post->ID, '_wprm_reservation_date', true ) );
			break;
			case "reservation_time" :
				echo get_post_meta( $post->ID, '_wprm_reservation_time', true );
			break;
			case "name" :
				echo get_post_meta( $post->ID, '_wprm_reservation_name', true );
			break;
			case "phone_number" :
				echo get_post_meta( $post->ID, '_wprm_reservation_phone_number', true );
			break;
			case "email" :
				echo get_post_meta( $post->ID, '_wprm_reservation_email', true );
			break;
			case "party" :
				echo get_post_meta( $post->ID, '_wprm_reservation_size', true );
			break;
			case "message" :
				
				// Prepare message of the thickbox
				$the_message = get_post_meta( $post->ID, '_wprm_reservation_message', true );

				if($the_message) {
					// Set size of thickbox container
					$wprm_thickbox_size = apply_filters('wprm_reservations_thickbox_size',array('width' => '883', 'height' => '400'));

					// Prepare the content of the thickbox
					$name = get_post_meta( $post->ID, '_wprm_reservation_name', true );
					$date = date_i18n( get_option( 'date_format' ), get_post_meta( $post->ID, '_wprm_reservation_date', true ) );
					$title = sprintf( __( 'Message from %s for reservation on %s', 'wprm' ), $name, $date );
					
					echo '<a href="#TB_inline?width='.$wprm_thickbox_size['width'].'&height='.$wprm_thickbox_size['height'].'&inlineId=reservation_detail_'.$post->ID.'" title="'.$title.'" class="button secondary thickbox">'.__('View Message','wprm').'</a>';
					echo '<div id="reservation_detail_'.$post->ID.'" style="display:none"><p>'.$the_message.'</p></div>';
				} else {
					echo __('No Message','wprm');
				}

			break;
		}
	}

	/**
	 * wprm_modify_reservations_views function.
	 * Modifies the views links of the post type
	 *
	 * @access public
	 * @param mixed $column
	 * @return void
	 */
	public function wprm_modify_reservations_views( $views ) {

	    $views['upcoming'] = '<a href="'.admin_url( 'edit.php?post_type=wprm_reservations&reservations_filter=upcoming' ).'">'.__('Upcoming Reservations','wprm').'</a>';

	    return $views;
	}


	/**
	 * Removes useless buttons and elements from the reservation page.
	 *
	 * @access public
	 * @global $post_type
	 * @return void
	 */
	public function wprm_remove_elements( $views ) {
		global $post_type;
		
		if($post_type == 'wprm_reservations')
			echo '<style type="text/css">#save-post, #preview-action, .misc-pub-visibility, .curtime, #visibility, #save-action {display: none;}</style>';
	}

	/**
	 * wprm_menu_items_by_category function.
	 * Adds the ability to filter menu items by category into the post type admin table.
	 *
	 * @access public
	 * @param int $show_counts (default: 1)
	 * @param int $hierarchical (default: 1)
	 * @param int $show_uncategorized (default: 1)
	 * @param string $orderby (default: '')
	 * @return void
	 */
	public function wprm_menu_items_by_category( $show_counts = 1, $hierarchical = 1, $show_uncategorized = 1, $orderby = '' ) {
		global $typenow, $wp_query;

	    if ( $typenow != 'wprm_menu' || ! taxonomy_exists( 'menu_category' ) )
	    	return;

		include_once( 'class-wprm-menu-category-walker.php' );

		$r = array();
		$r['pad_counts'] 	= 1;
		$r['hierarchical'] 	= $hierarchical;
		$r['hide_empty'] 	= 0;
		$r['show_count'] 	= $show_counts;
		$r['selected'] 		= ( isset( $wp_query->query['menu_category'] ) ) ? $wp_query->query['menu_category'] : '';

		$r['menu_order'] = false;

		if ( $orderby == 'order' )
			$r['menu_order'] = 'asc';
		elseif ( $orderby )
			$r['orderby'] = $orderby;

		$terms = get_terms( 'menu_category', $r );

		if ( ! $terms )
			return;

		$output  = "<select name='menu_category' id='dropdown_menu_category'>";
		$output .= '<option value="" ' .  selected( isset( $_GET['menu_category'] ) ? $_GET['menu_category'] : '', '', false ) . '>'.__( 'Select a category', 'wprm' ).'</option>';
		$output .= $this->wprm_walk_menu_category_dropdown_tree( $terms, 0, $r );
		$output .="</select>";

		echo $output;
	}

	/**
	 * Walker to show Menu Categories.
	 *
	 * @access public
	 * @return void
	 */
	private function wprm_walk_menu_category_dropdown_tree() {
		$args = func_get_args();

		// the user's options are the third parameter
		if ( empty($args[2]['walker']) || !is_a($args[2]['walker'], 'Walker') )
			$walker = new WPRM_Menu_Category_Walker;
		else
			$walker = $args[2]['walker'];

		return call_user_func_array( array( $walker, 'walk' ), $args );
	}

	/**
	 * wprm_menu_items_by_tag function.
	 * Adds the ability to filter menu items by tag into the post type admin table.
	 *
	 * @access public
	 * @param int $show_counts (default: 1)
	 * @param int $hierarchical (default: 1)
	 * @param int $show_uncategorized (default: 1)
	 * @param string $orderby (default: '')
	 * @return void
	 */
	public function wprm_menu_items_by_tag( $show_counts = 1, $hierarchical = 1, $show_uncategorized = 1, $orderby = '' ) {
		global $typenow, $wp_query;

	    if ( $typenow != 'wprm_menu' || ! taxonomy_exists( 'menu_tag' ) )
	    	return;

		include_once( 'class-wprm-menu-tags-walker.php' );

		$r = array();
		$r['pad_counts'] 	= 1;
		$r['hierarchical'] 	= $hierarchical;
		$r['hide_empty'] 	= 0;
		$r['show_count'] 	= $show_counts;
		$r['selected'] 		= ( isset( $wp_query->query['menu_tag'] ) ) ? $wp_query->query['menu_tag'] : '';

		$r['menu_order'] = false;

		if ( $orderby == 'order' )
			$r['menu_order'] = 'asc';
		elseif ( $orderby )
			$r['orderby'] = $orderby;

		$terms = get_terms( 'menu_tag', $r );

		if ( ! $terms )
			return;

		$output  = "<select name='menu_tag' id='dropdown_menu_tag'>";
		$output .= '<option value="" ' .  selected( isset( $_GET['menu_tag'] ) ? $_GET['menu_tag'] : '', '', false ) . '>'.__( 'Select a tag', 'wprm' ).'</option>';
		$output .= $this->wprm_walk_menu_tags_dropdown_tree( $terms, 0, $r );
		$output .="</select>";

		echo $output;
	}

	/**
	 * Walker to show Menu Categories.
	 *
	 * @access public
	 * @return void
	 */
	private function wprm_walk_menu_tags_dropdown_tree() {
		$args = func_get_args();

		// the user's options are the third parameter
		if ( empty($args[2]['walker']) || !is_a($args[2]['walker'], 'Walker') )
			$walker = new WPRM_Menu_Tags_Walker;
		else
			$walker = $args[2]['walker'];

		return call_user_func_array( array( $walker, 'walk' ), $args );
	}

	/**
	 * Quick link in admin panel to approve reservation.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function wprm_admin_post_approve_reservation() {
	    
		if(isset( $_GET['post']) && isset($_REQUEST['action']) && $_REQUEST['action'] == 'approve_reservation' ) {

			$id = ( isset($_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
			$post = get_post($id);
			
			if (isset($post) && $post!=null && $post->post_type == 'wprm_reservations') {
				
				$update_reservation = array(
						'ID' => $post->ID,
						'post_status' => 'publish'
				);

				// Let's update the status of the reservation
				wp_update_post( $update_reservation );

				WPRM_Email::wprm_send_email_to_user_confirmation( $post->ID );

				$reservation_approved_link = add_query_arg( array('reservation' => 'approved', 'reservation_id' => $post->ID), admin_url( 'edit.php?post_type=wprm_reservations' ) );

				wp_safe_redirect( $reservation_approved_link );
				exit();

			} else {
				
				wp_die(esc_attr(__('Approval failed for reservation: ', 'wprm')) . ' ' . htmlspecialchars($id));
				exit();
			
			}
			
		}

	}

	/**
	 * Quick link in admin panel to reject reservation.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function wprm_admin_post_reject_reservation() {
	    
		if(isset( $_GET['post']) && isset($_REQUEST['action']) && $_REQUEST['action'] == 'reject_reservation' ) {

			$id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
			$post = get_post($id);

			if (isset($post) && $post!=null && $post->post_type == 'wprm_reservations') {
				
				$update_reservation = array(
						'ID' => $post->ID,
						'post_status' => 'reject'
				);

				// Let's update the status of the reservation
				wp_update_post( $update_reservation );

				$reservation_rejected_link = add_query_arg( array('reservation' => 'rejected', 'reservation_id' => $post->ID), admin_url( 'edit.php?post_type=wprm_reservations' ) );

				wp_safe_redirect( $reservation_rejected_link );
				exit();

			} else {
				
				wp_die(esc_attr(__('Rejection failed for reservation: ', 'wprm')) . ' ' . htmlspecialchars($id));
				exit();
			
			}
			
		}

	}

	/**
	 * Display admin notices when reservation status is changed through quick link.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function wprm_reservations_admin_notices() {
    	
   		global $pagenow;
   	
   		$the_message = null;

   		if( 'edit.php' == $pagenow && isset($_GET['post_type']) && $_GET['post_type'] == 'wprm_reservations' ):

   			if(isset($_GET['reservation']) && $_GET['reservation'] == 'approved' && isset($_GET['reservation_id']) && is_numeric($_GET['reservation_id'])) :

   				$the_message = sprintf(__('Reservation #%s has been approved. %s','wprm'), $_GET['reservation_id'], wprm_reservations_single_notifications_enabled());

   				echo '<div class="updated"><p>'.$the_message.'</p></div>';

   			elseif(isset($_GET['reservation']) && $_GET['reservation'] == 'rejected' && isset($_GET['reservation_id']) && is_numeric($_GET['reservation_id'])) :

   				$the_message = sprintf(__('Reservation #%s has been rejected. %s','wprm'), $_GET['reservation_id'], wprm_reservations_single_notifications_enabled());

   				echo '<div class="updated"><p>'.$the_message.'</p></div>';

   			endif;

   		endif;

   		if ( $pagenow == 'edit.php' && isset($post_type) && $post_type == 'wprm_reservations' && ! empty( $_REQUEST['mass_delete_reservation'] ) ) {
			
			$mass_deleted_reservations = $_REQUEST['mass_delete_reservation'];
			
			if ( is_array( $mass_deleted_reservations ) ) {
			
				$mass_deleted_reservations = array_map( 'absint', $mass_deleted_reservations );
				$titles        = array();
			
				foreach ( $mass_deleted_reservations as $reservation_id )
					$titles[] = get_the_title( $reservation_id );
				echo '<div class="updated"><p>' . sprintf( __( 'Reservations successfully deleted.', 'wprm' ), '&quot;' . implode( '&quot;, &quot;', $titles ) . '&quot;' ) . '</p></div>';
			
			} else {
			
				echo '<div class="updated"><p>' . sprintf( __( 'Reservations successfully deleted.', 'wprm' ), '&quot;' . get_the_title( $mass_deleted_reservations ) . '&quot;' ) . '</p></div>';
			
			}

		}

    }

    /**
	 * Display various options to change the days filter of the upcoming reservations list.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function wprm_reservations_upcoming_reservations_days_selectors() {

		global $typenow, $wp_query;

	    if ( $typenow != 'wprm_reservations' || !isset($_GET['reservations_filter']))
	    	return;

		$days_filters = apply_filters( 'wprm_reservations_upcoming_reservations_days_selectors', array( 
			'today' => __('Today', 'wprm'), 
			'tomorrow' => __('Tomorrow','wprm'), 
			'2 days' => __('Within 2 Days','wprm'),
			'3 days' => __('Within 3 Days','wprm')
		));

		foreach ($days_filters as $period => $day ) {

			$period_link = add_query_arg( array('period' => $period), admin_url( 'edit.php?post_type=wprm_reservations&reservations_filter=upcoming' ) );
			echo '<a href="'.$period_link.'" class="button">'.$day.'</a> ';

		}

	}

    /**
	 * Display upcoming reservations in admin panel.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
    public function wprm_admin_filter_upcoming_reservations( $query ) {

    	global $pagenow;

    	if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wprm_reservations' && $query->is_main_query() && isset($_GET['reservations_filter']) && $_GET['reservations_filter'] == 'upcoming') {
	        
	        $query->set( 'post_status', 'publish' );

			$futuredate = date('Y-m-d', strtotime('today'));

			if(isset($_GET['reservations_filter']) && $_GET['reservations_filter'] == 'upcoming' && isset($_GET['period'])):
				$futuredate = date('Y-m-d', strtotime($_GET['period']));
			endif;

			$timestamp = new DateTime($futuredate);

	        $metaquery = array(
				array(
				     'key' => '_wprm_reservation_date',
				     'value' => $timestamp->getTimestamp(),
				     'compare' => '='
				),
			);

	        $query->set( 'meta_query', $metaquery );
	        $query->set( 'meta_key', '_wprm_reservation_time' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'order', 'ASC' );

		}

    }

    /**
	 * Adds extra content on the single wprm_menu page.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return $content 
	 */
	public function wprm_menu_content( $content ) {
		
		global $post;

		if ( ! is_singular( 'wprm_menu' ) || ! in_the_loop() ) {
			return $content;
		}

		remove_filter( 'the_content', array( $this, 'wprm_menu_content' ) );

		if ( 'wprm_menu' === $post->post_type ) {
			ob_start();

			do_action( 'wprm_menu_content_start' );

			get_wprm_template_part( 'content-single', 'menu-item' );

			do_action( 'wprm_menu_content_end' );

			$content = ob_get_clean();
		}

		add_filter( 'the_content', array( $this, 'wprm_menu_content' ) );

		return $content;
	}

	/**
	 * Edit bulk actions
	 * 
	 * @access public
	 * @return void
	 */
	public function wprm_add_bulk_actions() {
		global $post_type;

		if ( $post_type == 'wprm_reservations' ) {
			?>
			<script type="text/javascript">
		      jQuery(document).ready(function() {
		        jQuery('<option>').val('mass_delete_reservation').text('<?php _e( 'Delete Reservation', 'wprm' )?>').appendTo("select[name='action']");
		        jQuery('<option>').val('mass_delete_reservation').text('<?php _e( 'Delete Reservation', 'wprm' )?>').appendTo("select[name='action2']");
		      });
		    </script>
		    <?php
		}
	}

	/**
	 * Do custom bulk actions
	 *
	 * @access public
	 * @return void
	 */
	public function wprm_do_bulk_actions() {
		$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
		$action        = $wp_list_table->current_action();

		switch( $action ) {
			case 'mass_delete_reservation' :
				check_admin_referer( 'bulk-posts' );

				$post_ids     = array_map( 'absint', array_filter( (array) $_GET['post'] ) );
				$mass_deleted_reservations = array();

				if ( ! empty( $post_ids ) )
					foreach( $post_ids as $post_id ) {
						
						if ( wp_trash_post( $post_id ) )
							$mass_deleted_reservations[] = $post_id;
					}

				wp_redirect( add_query_arg( 'mass_delete_reservation', $mass_deleted_reservations, remove_query_arg( array( 'mass_deleted_reservations' ), admin_url( 'edit.php?post_type=wprm_reservations' ) ) ) );
				exit;
			break;
		}

		return;
	}
    
}