<?php
/**
 * Admin Options Page
 *
 * @package     WPRM
 * @subpackage  Admin/Settings
 * @copyright   Copyright (c) 2014, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Options Page
 *
 * Renders the options page contents.
 *
 * @since 1.0
 * @global $wprm_options Array of all the WPRM Options
 * @return void
 */
function wprm_options_page() {
	global $wprm_options;

	$active_tab = isset( $_GET[ 'tab' ] ) && array_key_exists( $_GET['tab'], wprm_get_settings_tabs() ) ? $_GET[ 'tab' ] : 'general';

	ob_start();
	?>
	<div class="wrap">

	<?php do_action( 'admin_notices' ); ?>

		<h2><?php printf( __( 'WP Restaurant Manager v%s Settings', 'wprm' ), WPRM_VERSION ); ?> <?php do_action('wprm_next_to_settings_title');?></h2>
		<br/>

		<?php 
		if ( ! empty( $_GET['settings-updated'] ) ) {
			echo '<div class="updated fade wprm-settings-updated"><p>' . __( 'Settings successfully saved', 'wprm' ) . '</p></div>';
		}
		?>

		<h2 class="nav-tab-wrapper">
			<?php
			foreach( wprm_get_settings_tabs() as $tab_id => $tab_name ) {

				$tab_url = add_query_arg( array(
					'settings-updated' => false,
					'tab' => $tab_id
				) );

				$active = $active_tab == $tab_id ? ' nav-tab-active' : '';

				echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">';
					echo esc_html( $tab_name );
				echo '</a>';
			}
			?>
		</h2>
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div class="inner-sidebar">
		        <div id="side-sortables" class="meta-box-sortables ui-sortable">
		            <?php do_action('wprm_settings_sidebar');?>
		        </div>
		    </div>
		    <div id="post-body">
		        <div id="post-body-content">
		            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<form method="post" action="options.php">
							<table class="form-table">
							<?php
							settings_fields( 'wprm_settings' );
							do_settings_fields( 'wprm_settings_' . $active_tab, 'wprm_settings_' . $active_tab );
							?>
							</table>
							<?php submit_button(); ?>
						</form>
					</div>
				</div>
			</div>
		</div><!-- #poststuff-->
	</div><!-- .wrap -->
	<?php
	echo ob_get_clean();
}
