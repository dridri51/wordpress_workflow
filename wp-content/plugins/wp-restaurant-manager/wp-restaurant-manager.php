<?php
/**
 * Plugin Name: WP Restaurant Manager
 * Plugin URI: http://wprestaurantmanager.themesdepot.org
 * Description: The WP Restaurant Manager plugin provides an all around restaurants management solution, including menus and bookings.
 * Author: Alessandro Tesoro
 * Author URI: http://themesdepot.org
 * Version: 1.0.5
 * Text Domain: wprm
 * Domain Path: languages
 *
 * WP Restaurant Manager is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP Restaurant Manager is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Restaurant Manager. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package wp-restaurant-manager
 * @author Alessandro Tesoro
 * @version 1.0.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_Restaurant_Manager' ) ) :

	/**
	 * Main WP_Restaurant_Manager Class
	 *
	 * @since 1.0.0
	 */
	class WP_Restaurant_Manager {

		/** Singleton *************************************************************/
		/**
		 * @var WP_Restaurant_Manager the yummy WP_Restaurant_Manager.
		 * @since 1.0.0
		 */
		private static $instance;

		/**
		 * WPRM Email Template Tags Object
		 *
		 * @var object
		 * @since 1.0.0
		 */
		public $email_tags;

		/**
		 * WPRM Post Types Object
		 *
		 * @var object
		 * @since 1.0.0
		 */
		public $post_types;

		/**
		 * WPRM HTML Element Helper Object
		 *
		 * @var object
		 * @since 1.0.0
		 */
		public $html;

		/**
		 * Main WP_Restaurant_Manager Instance
		 *
		 * Insures that only one instance of WP_Restaurant_Manager exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 * @static
		 * @staticvar array $instance
		 * @uses WP_Restaurant_Manager::setup_constants() Setup the constants needed
		 * @uses WP_Restaurant_Manager::includes() Include the required files
		 * @uses WP_Restaurant_Manager::load_textdomain() load the language files
		 * @see WPRM()
		 * @return The yummy WP_Restaurant_Manager
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WP_Restaurant_Manager ) ) {
				self::$instance = new WP_Restaurant_Manager;
				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->load_textdomain();
				self::$instance->html       = new WPRM_HTML_Elements();
				self::$instance->post_types  = new WPRM_Post_Types();
				self::$instance->email_tags = new WPRM_Email_Template_Tags();

			}
			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wprm' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wprm' ), '1.0.0' );
		}

		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @since 1.0.0
		 * @return void
		 */
		private function setup_constants() {
			
			// Plugin version
			if ( ! defined( 'WPRM_VERSION' ) ) {
				define( 'WPRM_VERSION', '1.0.5' );
			}

			// Plugin Folder Path
			if ( ! defined( 'WPRM_PLUGIN_DIR' ) ) {
				define( 'WPRM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'WPRM_PLUGIN_URL' ) ) {
				define( 'WPRM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'WPRM_PLUGIN_FILE' ) ) {
				define( 'WPRM_PLUGIN_FILE', __FILE__ );
			}

			if ( ! defined( 'WPRM_SLUG' ) ) {
				define( 'WPRM_SLUG', plugin_basename(__FILE__));
			}
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @since 1.0.0
		 * @return void
		 */
		private function includes() {

			global $wprm_options;

			require_once WPRM_PLUGIN_DIR . 'includes/admin/settings/register-settings.php';
			$wprm_options = wprm_get_settings();

			require_once WPRM_PLUGIN_DIR . 'includes/actions.php';
			require_once WPRM_PLUGIN_DIR . 'includes/filters.php';
			require_once WPRM_PLUGIN_DIR . 'includes/scripts.php';
			require_once WPRM_PLUGIN_DIR . 'includes/functions.php';
			require_once WPRM_PLUGIN_DIR . 'includes/misc-functions.php';
			require_once WPRM_PLUGIN_DIR . 'includes/metaboxes.php';
			require_once WPRM_PLUGIN_DIR . 'includes/class-wprm-post-types.php';
			require_once WPRM_PLUGIN_DIR . 'includes/class-wprm-html-elements.php';
			require_once WPRM_PLUGIN_DIR . 'includes/class-wprm-bookings.php';
			require_once WPRM_PLUGIN_DIR . 'includes/class-wprm-shortcodes.php';
			require_once WPRM_PLUGIN_DIR . 'includes/class-wprm-email-tags.php';
			require_once WPRM_PLUGIN_DIR . 'includes/class-wprm-email.php';

			// Includes widgets
			require_once WPRM_PLUGIN_DIR . 'includes/vendor/widget-class.php';
			if(class_exists('Empty_Widget_Abstract')):
				require_once WPRM_PLUGIN_DIR . 'includes/widgets/widget-booking-form.php';
				require_once WPRM_PLUGIN_DIR . 'includes/widgets/widget-opening-times.php';
			endif;
			
			if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
				require_once WPRM_PLUGIN_DIR . 'includes/admin/getting-started/getting-started.php';
				require_once WPRM_PLUGIN_DIR . 'includes/admin/settings/display-settings.php';
				require_once WPRM_PLUGIN_DIR . 'includes/admin/settings/contextual-help.php';
				require_once WPRM_PLUGIN_DIR . 'includes/class-wprm-addons.php';
			}

			require_once WPRM_PLUGIN_DIR . 'includes/install.php';
		}

		/**
		 * Loads the plugin language files
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function load_textdomain() {
			// Set filter for plugin's languages directory
			$wprm_lang_dir = dirname( plugin_basename( WPRM_PLUGIN_FILE ) ) . '/languages/';
			$wprm_lang_dir = apply_filters( 'wprm_languages_directory', $wprm_lang_dir );

			// Traditional WordPress plugin locale filter
			$locale        = apply_filters( 'plugin_locale',  get_locale(), 'wprm' );
			$mofile        = sprintf( '%1$s-%2$s.mo', 'wprm', $locale );

			// Setup paths to current locale file
			$mofile_local  = $wprm_lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/wprm/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/wprm folder
				load_textdomain( 'wprm', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/wp-restaurant-manager/languages/ folder
				load_textdomain( 'wprm', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'wprm', false, $wprm_lang_dir );
			}
		}

	}

endif; // End if class_exists check

/**
 * The main function responsible for returning the one true WP_Restaurant_Manager
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $wprm = WPRM(); ?>
 *
 * @since 1.0.0
 * @return object The one true WP_Restaurant_Manager Instance
 */
function WPRM() {
	return WP_Restaurant_Manager::instance();
}

// Get WPRM Running
WPRM();