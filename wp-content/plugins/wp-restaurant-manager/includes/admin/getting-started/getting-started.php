<?php
/**
 * Getting Started Page Class
 *
 * @package     wp-restaurant-manager
 * @copyright   Copyright (c) 2014, Alessandro Tesoro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WPRM_Getting_Started Class
 *
 * A general class for About and Credits page.
 *
 * @since 1.0.0
 */
class WPRM_Getting_Started {

	/**
	 * @var string The capability users should have to view the page
	 */
	public $minimum_capability = 'manage_options';

	/**
	 * Get things started
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_enqueue_scripts', array( $this,'getting_started_admin_scripts') );
		add_action( 'admin_init', array( $this, 'welcome'    ) );
	}

	/**
	 * Register the Dashboard Pages which are later hidden but these pages
	 * are used to render the Welcome and Credits pages.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_menus() {
		// Getting Started Page
		add_submenu_page(
			'edit.php?post_type=wprm_menu',
			__( 'Getting started with WP Restaurant Manager', 'wprm' ),
			__( 'About WPRM', 'wprm' ),
			$this->minimum_capability,
			'wprm-getting-started',
			array( $this, 'getting_started_screen' )
		);

	}

	/**
	 * Load Getting Started styles in the admin
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function getting_started_admin_scripts() {

		// Load styles only on our page
		global $pagenow;
		if( 'edit.php' != $pagenow )
			return;

		// Getting started script and styles
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'getting-started', WPRM_PLUGIN_URL . 'includes/admin/getting-started/getting-started.js', array( 'jquery' ), '1.0.0', true );
		wp_register_style( 'getting-started', WPRM_PLUGIN_URL . 'includes/admin/getting-started/getting-started.css', false, '1.0.0' );
		wp_enqueue_style( 'getting-started' );

		// Thickbox
		add_thickbox();
	}

	/**
	 * Render Getting Started Screen
	 *
	 * The function provides 2 actions that can be used to customize
	 * the content of the getting started page.
	 *
	 * Use the action "wprm_getting_started_tabs" to add new links.
	 * Use the action "wprm_getting_started_panels" to add the content of the new links.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function getting_started_screen() {

		?>

		<div class="wrap getting-started">
			<div class="intro-wrap">
				<img class="theme-image" src="<?php echo WPRM_PLUGIN_URL . '/assets/images/wprm_screenshot.jpg'; ?>" alt="" />
				<div class="intro">
					<h2><?php printf( __( 'Welcome to WP Restaurant Manager v%s', 'wprm' ), WPRM_VERSION ); ?></h2>
					<h3><?php printf( __( 'Thank you for using WP Restaurant Manager v%s! You are now ready to improve your restaurant online presence. Please visit the tabs below to get started setting up your restaurant website!', 'wprm' ), WPRM_VERSION ); ?></h3>
				</div>
			</div>

			<div class="panels">
				
				<ul class="inline-list">
					<span class="inline-list-links">
						
						<li class="current"><a id="help" href="#"><?php _e( 'Getting Started', 'wprm' ); ?></a></li>
						<li class="license-tab"><a id="pro" href="#"><?php _e( 'Go Pro &raquo;', 'wprm' ); ?></a></li>
						<li class="license-tab"><a id="license" href="#"><?php _e( 'What\'s New', 'wprm' ); ?></a></li>
						
						<?php do_action('wprm_getting_started_tabs');?>
					
					</span>
				</ul>

				<!-- Help file panel -->
				<div id="help-panel" class="panel visible clearfix">
					
					<div class="panel-left">
						<h3><?php _e('Use the tips below to get started using WP Restaurant Manager. You will be up and running in no time!','wprm');?></h3>
						<p><?php _e('The WP Restaurant Manager plugin provides an all around restaurants management solution, including menus and bookings. WPRM allows your website to accept restaurant reservations and table bookings online. An easy to use booking management page provides you tools to quickly confirm or reject bookings, send out custom email notifications, restrict booking times, dates and much more.','wprm');?></p>
						
						<h4><?php _e('Documentation Access:','wprm');?></h4>
						<p><?php echo sprintf(__('A quick documentation can be found into the <a href="%s">plugin\'s settings page</a> by clicking the "help" button on the top right corner of your screen. The documentation gives a quick overview of the features and functionalities of the plugin. All of the options are described and should give enough information on what they do and how to customize them.','wprm'), admin_url( 'edit.php?post_type=wprm_menu&page=wprm-settings' ) );?></p>
						
						<h4><?php _e('Getting started:','wprm');?></h4>
						<p><?php echo sprintf(__('Using WPRM is very easy! First thing you should do is visit the <a href="%s">plugin\'s settings page</a> and get comfortable with the options. The general settings provides the most basic settings for configuring your plugin. You can set the booking form style, make some adjustment to the layout of the form, and customize the way your food menu looks.','wprm'), admin_url( 'edit.php?post_type=wprm_menu&page=wprm-settings' ));?></p>

						<h4><?php _e('Booking Settings:','wprm');?></h4>
						<p><?php echo sprintf(__('<a href="%s">The booking settings page</a> provides you tools to configure and customize your booking form including dates and times configuration, booking intervals, messages and formatting. It is important that you select the page where you have placed the booking shortcode. The plugin has been designed to load the required scripts and styles only when required therefore you must select your booking page. Simply create a page and then add the [wprm_booking_form] shortcode and save it.','wprm'), admin_url( 'edit.php?post_type=wprm_menu&page=wprm-settings&tab=booking' ) );?></p>
					
						<h4><?php _e('Notifications Settings:','wprm');?></h4>
						<p><?php echo sprintf( __('<a href="%s">The notifications settings</a> screen provides the settings to customize the email notifications sent by the plugin upon reservation and changes to reservations. WPRM comes with flexible email tags ready to be used to create customized emails with relevant information about the booking. Admin notifications have been designed to provide quick access to the approval and rejection tools.','wprm'), admin_url( 'edit.php?post_type=wprm_menu&page=wprm-settings&tab=notifications' )); ?></p>
					</div>

					<div class="panel-right">

						<div class="panel-aside">
							<h4><?php _e( 'Upgrade To WP Restaurant Manager ', 'wprm' ); ?> <span class="plus"><?php _e('Pro Version','wprm');?></span></h4>
							<p><?php _e( 'Upgrade to WP Restaurant Manager Pro for exceptional features, including a detailed calendar view, SMS notifications, custom booking fields, scheduled notifications, 1 to 1 support and much more. Use the coupon code below for a 5% discount. ', 'wprm' ); ?></p>
							<code style="display:block;width:100%;text-align:center">WPRM5OFF</code><br/>
							<a class="button button-primary" href="https://themesdepot.org/plugins/wp-restaurant-manager/" title="<?php esc_attr_e( __( 'Upgrade', 'wprm' ) ); ?>"><?php _e( 'Read more about the pro features &raquo;', 'wprm' ); ?></a>
						</div>

						<div class="panel-aside">
							<h4><?php _e( 'Visit the Knowledge Base', 'wprm' ); ?></h4>
							<p><?php _e( 'New to WP Restaurant Manager? Visit the online knowledge base for detailed documentaion, tutorials, and faqs about the usage and customization of the plugin.', 'wprm' ); ?></p>
							<a class="button button-primary" href="http://support.themesdepot.org/hc/en-us/categories/200294021-WP-Restaurant-Manager" title="<?php esc_attr_e( __( 'Visit the knowledge base', 'wprm' ) ); ?>"><?php _e( 'Visit the Knowledge Base', 'wprm' ); ?></a>
						</div>

						<div class="panel-aside">
							<h4><?php _e( 'Community Support', 'wprm' ); ?></h4>
							<p><?php _e( 'Free support is <strong>exclusively limited</strong> to bugs within the plugin. No support will be provided for customizations or issues with third party plugins and/or themes.', 'wprm' ); ?></p>
							<p><?php _e('Use the WordPress.org forums for community support - I cannot offer support directly for free. If you spot a bug, you can of course log it on <a href="https://github.com/alessandrotesoro/wp-restaurant-manager-lite/" target="_blank">Github</a> instead where I can act upon it more efficiently.'); ?></p>
							<a class="button button" href="https://wordpress.org/support/plugin/wp-restaurant-manager" title="<?php esc_attr_e( __( 'Get Community Support', 'wprm' ) ); ?>"><?php _e( 'Get Community Support', 'wprm' ); ?></a>
						</div>

					</div>
				</div><!-- #help-panel -->

				<!-- License panel -->
				<div id="license-panel" class="panel clearfix">
					<div class="panel-left">
						<h3><?php _e('Upgrade To WP Restaurant Manager Pro','wprm');?></h3>
						<p><?php _e('Managing a restaurant isn\'t easy! WP Restaurant Manager Pro makes it easier. WPRM aims to be the best restaurant management plugin for WordPress with it\'s exceptional features. ','wprm');?></p>
						
						<p><?php _e('Take your restaurant to the next level. The pro version adds exclusive premium features.','wprm');?></p>

						<p><?php _e('Upgrade to WP Restaurant Manager Pro for exceptional features, including a detailed calendar view, SMS notifications, custom booking fields, scheduled notifications, 1 to 1 support and much more.','wprm'); ?></p>
						
						<a href="https://themesdepot.org/plugins/wp-restaurant-manager" class="button-primary"><?php _e('Upgrade Now &amp; Read More');?></a>

					</div><!-- .panel-left -->

					<div class="panel-right">
						<div class="panel-aside">
							<h4><?php _e( 'Upgrade To WP Restaurant Manager ', 'wprm' ); ?> <span class="plus"><?php _e('Pro Version','wprm');?></span></h4>
							<p><?php _e( 'Upgrade to WP Restaurant Manager Pro for exceptional features, including a detailed calendar view, SMS notifications, custom booking fields, scheduled notifications, 1 to 1 support and much more. Use the coupon code below for a 5% discount. ', 'wprm' ); ?></p>
							<code style="display:block;width:100%;text-align:center">WPRM5OFF</code><br/>
							<a class="button button-primary" href="https://themesdepot.org/plugins/wp-restaurant-manager/" title="<?php esc_attr_e( __( 'Upgrade', 'wprm' ) ); ?>"><?php _e( 'Read more about the pro features &raquo;', 'wprm' ); ?></a>
						</div>

						<div class="panel-aside">
							<h4><?php _e( 'Premium Support', 'wprm' ); ?></h4>
							<p><?php _e( 'Premium 1 to 1 support is <strong>exclusively available</strong> to customers who purchased the pro version of WPRM. Please visit this page for the <a href="https://themesdepot.org/support-policy">full support policy.</a>', 'wprm' ); ?></p>
							<a class="button button-primary" href="https://themesdepot.org/support" title="<?php esc_attr_e( __( 'Premium Support', 'wprm' ) ); ?>"><?php _e( 'Get Premium Support', 'wprm' ); ?></a>
						</div>
					</div><!-- .panel-right -->
				</div><!-- #license-panel -->

				<!-- Updates panel -->
				<div id="updates-panel" class="panel">
					<div class="panel-left">

						<h3><?php _e( 'Latest WP Restaurant Manager Updates', 'wprm' ); ?></h3>

						<?php echo $this->parse_readme(); ?>

					</div><!-- .panel-left -->

					<div class="panel-right">
						<div class="panel-aside">
							<h4><?php _e( 'Upgrade To WP Restaurant Manager ', 'wprm' ); ?> <span class="plus"><?php _e('Pro Version','wprm');?></span></h4>
							<p><?php _e( 'Upgrade to WP Restaurant Manager Pro for exceptional features, including a detailed calendar view, SMS notifications, custom booking fields, scheduled notifications, 1 to 1 support and much more. Use the coupon code below for a 5% discount. ', 'wprm' ); ?></p>
							<code style="display:block;width:100%;text-align:center">WPRM5OFF</code><br/>
							<a class="button button-primary" href="https://themesdepot.org/plugins/wp-restaurant-manager/" title="<?php esc_attr_e( __( 'Upgrade', 'wprm' ) ); ?>"><?php _e( 'Read more about the pro features &raquo;', 'wprm' ); ?></a>
						</div>
					</div><!-- .panel-right -->
				</div><!-- #updates-panel -->

				<?php do_action('wprm_getting_started_panels');?>

			</div><!-- .panels -->
		</div><!-- .getting-started -->

		<?php
	}

	/**
	 * Parse the WPRM readme.txt file
	 *
	 * @since 1.0.0
	 * @return string $readme HTML formatted readme file
	 */
	public function parse_readme() {
		$file = file_exists( WPRM_PLUGIN_DIR . 'readme.txt' ) ? WPRM_PLUGIN_DIR . 'readme.txt' : null;

		if ( ! $file ) {
			$readme = '<p>' . __( 'No valid changlog was found.', 'wprm' ) . '</p>';
		} else {
			$readme = file_get_contents( $file );
			$readme = nl2br( esc_html( $readme ) );

			$readme = explode( '== Changelog ==', $readme );
			$readme = end( $readme );

			$readme = preg_replace( '/`(.*?)`/', '<code>\\1</code>', $readme );
			$readme = preg_replace( '/[\040]\*\*(.*?)\*\*/', ' <strong>\\1</strong>', $readme );
			$readme = preg_replace( '/[\040]\*(.*?)\*/', ' <em>\\1</em>', $readme );
			$readme = preg_replace( '/= (.*?) =/', '<h4>\\1</h4>', $readme );
			$readme = preg_replace( '/\[(.*?)\]\((.*?)\)/', '<a href="\\2">\\1</a>', $readme );
		}

		return $readme;
	}

	/**
	 * Sends user to the Welcome page on first activation of WPRM as well as each
	 * time WPRM is upgraded to a new version
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function welcome() {

		// Bail if no activation redirect
		if ( ! get_transient( '_wprm_activation_redirect' ) )
			return;

		// Delete the redirect transient
		delete_transient( '_wprm_activation_redirect' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
			return;

		$upgrade = get_option( 'wprm_version_upgraded_from' );

		if( ! $upgrade ) { // First time install
			wp_safe_redirect( admin_url( 'edit.php?post_type=wprm_menu&page=wprm-getting-started' ) ); exit;
		} else { // Update
			wp_safe_redirect( admin_url( 'edit.php?post_type=wprm_menu&page=wprm-getting-started' ) ); exit;
		}
	}

} // Class End.

new WPRM_Getting_Started();