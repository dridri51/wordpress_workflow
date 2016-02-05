<?php
/**
 * Handles the addons page.
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPRM_Addons Class
 * @since 1.0.0
 */
class WPRM_Addons {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action('admin_menu', array($this, 'wprm_addons_page_link'), 10);
	}

	/**
	 * Add Plugin Addon Menu Page
	 * @since 1.0.0
	 * @global $wprm_settings_page
	 */
	public function wprm_addons_page_link() {
		global $wprm_settings_page;
		$wprm_settings_page = add_submenu_page( 'edit.php?post_type=wprm_menu', __( 'WP Restaurant Manager Addons', 'wprm' ), __( 'Addons', 'wprm' ), 'manage_options', 'wprm-addons', array($this,'wprm_addons_page') );
	}

	/**
	 * Display the addon page
	 * @since 1.0.0
	 */
	public function wprm_addons_page() { ob_start(); ?>
		
		<div class="wrap">

			<h2><?php printf( __( 'WP Restaurant Manager Addons', 'wprm' ), WPRM_VERSION ); ?> <a href="https://themesdepot.org/plugins/wp-restaurant-manager" class="add-new-h2" target="_blank"><?php _e('View All Addons & Themes','wprm');?></a></h2>
			<br/>
			
			<?php echo $this->wprm_addons_get_feed(); ?>

		</div><!-- .wrap -->

	<?php echo ob_get_clean(); }
	
	/**
	 * Add-ons Get Feed
	 *
	 * Gets the add-ons page feed.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function wprm_addons_get_feed() {

		// Get the transient
		$cached_feed = get_transient( 'wprm_addons_feed' );

		// Check if feed exist -
		// if feed exists get content from cached feed.
		if ($cached_feed) {
			
			$output = json_decode( $cached_feed );

			echo '<div class="wp-list-table widefat plugin-install">';

					echo '<div id="the-list">';

						foreach ($output as $addon) {

							echo '<div class="plugin-card">';
								echo '<div class="plugin-card-top">';
									echo '<a href="'.$addon->url.'" target="_blank" class="thickbox plugin-icon"><img src="'.$addon->image.'"></a>';
									echo '<div class="name column-name" style="margin-right:0px">';
										echo '<h4><a href="'.$addon->url.'" target="_blank" class="thickbox">'.$addon->name.'</a></h4>';
									echo '</div>';
									echo '<div class="desc column-description">';
										echo '<p>'.$addon->description.'</p>';
									echo '</div>';
								echo '</div>';
								echo '<div class="plugin-card-bottom">';
									echo '<a target="_blank" href="'.$addon->url.'" class="button-primary" style="display:block; text-align:center;">'.$addon->button_label.'</a>';
								echo '</div>';
							echo '</div>';

						}

					echo '</div>';

			echo '</div>';

		// Feed is not cached, get content from live api.
		} else {

			$feed = wp_remote_get( 'http://api.themesdepot.org/wprm/', array( 'sslverify' => false ) );

			if ( ! is_wp_error( $feed ) ) {

				$feed_content = wp_remote_retrieve_body( $feed );
				set_transient( 'wprm_addons_feed', $feed_content, 3600 );
				$output = json_decode( $feed_content );

				echo '<div class="wp-list-table widefat plugin-install">';

					echo '<div id="the-list">';

						foreach ($output as $addon) {

							echo '<div class="plugin-card">';
								echo '<div class="plugin-card-top">';
									echo '<a href="'.$addon->url.'" target="_blank" class="thickbox plugin-icon"><img src="'.$addon->image.'"></a>';
									echo '<div class="name column-name" style="margin-right:0px">';
										echo '<h4><a href="'.$addon->url.'" target="_blank" class="thickbox">'.$addon->name.'</a></h4>';
									echo '</div>';
									echo '<div class="desc column-description">';
										echo '<p>'.$addon->description.'</p>';
									echo '</div>';
								echo '</div>';
								echo '<div class="plugin-card-bottom">';
									echo '<a target="_blank" href="'.$addon->url.'" class="button-primary" style="display:block; text-align:center;">'.$addon->button_label.'</a>';
								echo '</div>';
							echo '</div>';

						}

					echo '</div>';

				echo '</div>';

			}

		}
		
	}

}
new WPRM_Addons();