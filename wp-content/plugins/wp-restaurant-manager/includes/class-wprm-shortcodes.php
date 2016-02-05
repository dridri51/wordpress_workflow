<?php
/**
 * Shortcodes
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPRM_Shortcodes Class
 * Registers shortcodes together with a shortcodes editor.
 *
 * @since 1.0.0
 */
class WPRM_Shortcodes {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		add_filter('widget_text', 'do_shortcode');
		add_action('admin_head', array($this, 'wprm_shortcodes_add_mce_button'));
		add_action( 'admin_enqueue_scripts', array($this, 'wprm_shortcodes_mce_css' ));
		add_shortcode( 'wprm_single_item', array($this, 'wprm_single_menu_item_shortcode'));
		add_shortcode( 'wprm_menu_category', array($this, 'wprm_menu_category_shortcode'));
		add_shortcode( 'wprm_menu', array($this, 'wprm_menu_full'));

	}

	/**
	 * Check on which pages to enable the shortcodes editor.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function wprm_shortcodes_add_mce_button() {
		// check user permissions
		if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
			return;
		}
		// check if WYSIWYG is enabled
		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array($this,'wprm_shortcodes_add_tinymce_plugin' ));
			add_filter( 'mce_buttons', array($this,'wprm_shortcodes_register_mce_button' ));
		}
	}

	/**
	 * Load tinymce plugin
	 *
	 * @access public
	 * @since  1.0.0
	 * @return $plugin_array
	 */
	public function wprm_shortcodes_add_tinymce_plugin( $plugin_array ) {
		$plugin_array['wprm_shortcodes_mce_button'] = apply_filters( 'wprm_shortcodes_tinymce_js_file_url', plugins_url( '/admin/tinymce/js/wprm_shortcodes_tinymce.js', __FILE__ ) );
		return $plugin_array;
	}

	/**
	 * Load tinymce button
	 *
	 * @access public
	 * @since  1.0.0
	 * @return $buttons
	 */
	public function wprm_shortcodes_register_mce_button( $buttons ) {
		array_push( $buttons, 'wprm_shortcodes_mce_button' );
		return $buttons;
	}

	/**
	 * Load tinymce style
	 *
	 * @access public
	 * @since  1.0.0
	 * @return $buttons
	 */
	public function wprm_shortcodes_mce_css() {
		wp_enqueue_style('wprm_shortcodes-tc', plugins_url('/admin/tinymce/css/wprm_shortcodes_tinymce_style.css', __FILE__) );
	}

	/**
	 * Single Menu Item Shortcode
	 *
	 * @access public
	 * @since  1.0.0
	 * @return $output shortcode output
	 */
	public function wprm_single_menu_item_shortcode( $atts, $content=null ) {

		extract( shortcode_atts( array(
			'id' => '',
			'hyperlink' => '',
			'description' => '',
			'price' => '',
			'display_images' => ''
		), $atts ) );

		if ( ! $id )
			return;

		ob_start();

		$args = apply_filters('wprm_single_menu_item_args',array(
			'post_type'   => 'wprm_menu',
			'post_status' => 'publish',
			'p'           => $id
		), $id);

		$menu_items = new WP_Query( $args );

		if ( $menu_items->have_posts() ) : ?>

			<?php 

			do_action( 'wprm_single_menu_item_before', $id );

			while ( $menu_items->have_posts() ) : $menu_items->the_post(); ?>

				<?php get_wprm_template( 'shortcode-single-menu-item.php', array(
						'hyperlink'     => $hyperlink,
						'description'   => $description,
						'price'			=> $price,
						'display_images'=> $display_images
				)); ?>

			<?php 

			endwhile; 

			do_action( 'wprm_single_menu_item_after', $id );

			?>

		<?php endif;

		wp_reset_postdata();

		return '<div class="wprm_shortcode wprm_single_menu_item '.wprm_get_option('menu_style').'">' . ob_get_clean() . '</div>';

	}

	/**
	 * Menu Section/Category Shortcode
	 *
	 * @access public
	 * @since  1.0.0
	 * @return $output shortcode output
	 */
	public function wprm_menu_category_shortcode( $atts, $content=null ) {

		extract( shortcode_atts( array(
			'category_slug' => '',
			'category_title' => '',
			'category_description' => '',
			'hyperlink' => '',
			'description' => '',
			'price' => '',
			'display_images' => ''
		), $atts ) );

		if ( ! $category_slug )
			return;

		ob_start();

		$args = apply_filters('wprm_menu_category_args',array(
			'post_type'   => 'wprm_menu',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'menu_category',
					'field'    => 'slug',
					'terms'    => $category_slug,
				),
			),
		), $category_slug );

		$the_menu_category = get_term_by( 'slug', $category_slug, 'menu_category');

		$menu_items = new WP_Query( $args );

		if ( $menu_items->have_posts() ) : 

			do_action( 'wprm_menu_category_before', $category_slug );

			?>

			<?php if($category_title == 'true'): ?>

				<h3 class="wprm_category_title"><?php echo $the_menu_category->name;?></h3>

			<?php endif; ?>

			<?php if($category_description == 'true'): ?>

				<p class="wprm_category_description"><?php echo apply_filters( 'wprm_menu_category_description', $the_menu_category->description, $category_slug );?></p>

			<?php endif; ?>

			<?php while ( $menu_items->have_posts() ) : $menu_items->the_post(); ?>

				<?php get_wprm_template( 'shortcode-single-menu-item.php', array(
						'hyperlink'     => $hyperlink,
						'description'   => $description,
						'price'			=> $price,
						'display_images'=> $display_images
				)); ?>

			<?php endwhile; 

			do_action( 'wprm_menu_category_after', $category_slug );

			?>

		<?php endif;

		wp_reset_postdata();

		return '<div class="wprm_shortcode wprm_category_menu wprm_single_menu_item '.wprm_get_option('menu_style').'">' . ob_get_clean() . '</div>';

	}

	/**
	 * Full menu Shortcode
	 *
	 * @access public
	 * @since  1.0.0
	 * @return $output shortcode output
	 */
	public function wprm_menu_full( $atts, $content=null ) {

		extract( shortcode_atts( array(
			'category_title' => '',
			'category_description' => '',
			'hyperlink' => '',
			'description' => '',
			'price' => '',
			'display_images' => ''
		), $atts ) );

		ob_start();

		$menu_categories = get_terms( 'menu_category', 'hide_empty=0' );

		foreach ($menu_categories as $menu_category ) {

			$args = apply_filters('wprm_full_menu_args', array(
				'post_type'   => 'wprm_menu',
				'posts_per_page' => -1,
				'tax_query' => array(
					array(
						'taxonomy' => 'menu_category',
						'field'    => 'slug',
						'terms'    => array($menu_category->slug),
					),
				),
			));

			$menu_items = new WP_Query( $args );

			if ( $menu_items->have_posts() ) : 

				do_action( 'wprm_fullmenu_before' );

				?>

				<?php if($category_title == 'true'): ?>

					<h3 class="wprm_category_title"><?php echo $menu_category->name;?></h3>

				<?php endif; ?>

				<?php if($category_description == 'true'): ?>

					<p class="wprm_category_description"><?php echo apply_filters( 'wprm_fullmenu_category_description', $menu_category->description, $menu_category->slug );?></p>

				<?php endif; ?>

				<?php while ( $menu_items->have_posts() ) : $menu_items->the_post(); ?>

					<?php get_wprm_template( 'shortcode-single-menu-item.php', array(
							'hyperlink'     => $hyperlink,
							'description'   => $description,
							'price'			=> $price,
							'display_images'=> $display_images
					)); ?>

				<?php endwhile; 

				do_action( 'wprm_fullmenu_after' );

				?>

			<?php endif;

			wp_reset_postdata();

		}


		return '<div class="wprm_shortcode wprm_category_menu wprm_full_menu wprm_single_menu_item '.wprm_get_option('menu_style').'">' . ob_get_clean() . '</div>';

	}

}

new WPRM_Shortcodes;