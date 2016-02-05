<?php

/*
	TABLE OF CONTENTS ( DISPLAY	ON FRONT END )
	==========================================
	1.	CREATING WIDGET
	2.	ENQUEUES SCRIPTS AND STYLES
*/


/*################################################################
	1.	CREATING WIDGET
		1.	WIDGET CLASS
		2.	REGISTER WIDGET
################################################################*/
	
	/*====================================
		1.	WIDGET CLASS
	====================================*/
	class olr_restaurant_widget extends WP_Widget {
		
		/**
		 * Register widget with WordPress.
		 */
		function __construct() {
			
			parent::__construct(
				'olr_restaurant_widget', // Base ID
				__( 'OLRS Restaurant Reservation',PLUGIN_NAME), // Name
				array( 'description' => __( 'Display Online Restaurant Reservation',PLUGIN_NAME) )// Args
			);
		}
	   
		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {
			
			/*====================================
				ENQUEUES SCRIPTS
			====================================*/
			if( !wp_script_is('jquery-ui-datepicker') ){
				wp_enqueue_script( 'jquery-ui-datepicker' );
			}
				
			wp_enqueue_script( 
			'olr-script',
			OLR_FOLDER . 'js/restaurant-script.js', // $src 
				array(), 
				false, 
				true 
			);
				
			$any_data_array 	= array( 
										'plugin_folder' 	=> OLR_FOLDER,
										'plugin_path' 		=> OLR_PATH,
										'early_bookings' 	=> $options['early_bookings'],
										'late_bookings' 	=> $options['late_bookings'],
										'plugin_options' 	=> get_option('resto_all_setting'),
										'ajaxurl'         	=> admin_url( 'admin-ajax.php' ),
										'ip_address'   		=> $_SERVER['REMOTE_ADDR'],
										'fake_actions_title'   	=> $message_array['TOO MANY FAKE ACTIONS']['title'],
										'fake_actions_message' 	=> $message_array['TOO MANY FAKE ACTIONS']['message'],
										'geolocation_api' 	=> $geolocation_api
										);
			wp_localize_script( 'olr-script', 'data', $any_data_array );
			
			
			/*====================================
				DISPLAY WIDGET
			====================================*/
			$options = get_option( 'resto_all_setting' );
			$title 		= apply_filters( 'widget_title', $instance['title'] );
			
			echo $args['before_widget'];
				
				$out = '';
				$out = restaurant_reservation_content($options);
				echo $out;
			
			echo $args['after_widget'];
		}
	
		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			if ( $instance[ 'title' ] =='' ) {
				$title = __( 'Restaurant Reservation',PLUGIN_NAME);
			}else {
				$title = $instance[ 'title' ];
			}
			?>
            <p>
                <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:',PLUGIN_NAME); ?></label>
                <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" 
                    type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<?php 
		}
	
		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] 					= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		
			return $instance;
		}
	
	} // class olr_restaurant_widget extends WP_Widget {
	
	
	/*====================================
		2.	REGISTER WIDGET
	====================================*/
	function register_olr_restaurant_widget() {
		register_widget( 'olr_restaurant_widget' );
	}
	add_action( 'widgets_init', 'register_olr_restaurant_widget' );
	

/*################################################################
	2.	ENQUEUES STYLES
################################################################*/
if( !is_admin() ){	
	
	$olr_restaurant_widget_active = false;
	if( get_option('sidebars_widgets') != '' ){
		foreach( get_option('sidebars_widgets') as $sidebar ){
			if( is_array($sidebar) ){
				foreach( $sidebar as $widgets ){
					if( stripos($widgets,'lr_restaurant_widget') > 0 ){
						$olr_restaurant_widget_active = true;
						break;
					}
				}
			}
		}
	}
	
	if( $olr_restaurant_widget_active ){
		wp_enqueue_style(
			'olr-date-picker-style',	// $handle (id)	
			OLR_FOLDER .'css/jquery.ui.datepicker.css', // $sr
			false, 	// $dependencies
			false,	// $version
			false 	// in footer
		); 
		wp_enqueue_style(
			'olr-front-style',	// $handle (id)	
			OLR_FOLDER .'css/restaurant-front-style.css', // $sr
			false, 	// $dependencies
			false,	// $version
			false 	// in footer
		);  
	}
	
} // if( !is_admin() ){
	
	
?>