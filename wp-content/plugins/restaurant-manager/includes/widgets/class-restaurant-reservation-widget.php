<?php

/**
 * Description of restaurant reservation widget
 *
 * @author Ryan Haworth
 */
class synth_restaurant_reservation_widget extends WP_Widget {

    private $_config;

    function synth_restaurant_reservation_widget() {

        global $themeconfig;
        $this->_config = $themeconfig;

        $widget_ops = array(
            'classname' => 'synth_restaurant_reservation_widget',
            'description' => __('This enables you to display a reservation form in your widget areas.', 'syn_taurus_theme')
        );

        $this->WP_Widget('synth_restaurant_reservation_widget', 'Restaurant Manager Reservation Form', $widget_ops);
    }

    function form($instance) {

        $defaults = array(
            'title' => '',
            'image' => '',
            'name' => '',
            'phone' => '',
            'email' => '',
            'profile' => ''
        );

        $instance = wp_parse_args((array) $instance, $defaults);
        $title = $instance['title'];
        $image = $instance['image'];
        $name = $instance['name'];
        $phone = $instance['phone'];
        $email = $instance['email'];
        $profile = $instance['profile'];
        ?>
        <div class="synth-widget-container">
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title: ', 'syn_taurus_theme'); ?></Label>
                <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>"/>
            </p>       
        </div>
        <?php
    }

    function update($newInstance, $oldInstance) {

        $instance = $oldInstance;
        $instance['title'] = strip_tags($newInstance['title']);

        return $instance;
    }

    function widget($args, $instance) {

        extract($args);
        extract($instance);

        echo $before_widget;
        echo $before_title . $title . $after_title;
        
        echo do_shortcode("[syn_restaurant_reservation]");

        echo $after_widget;
    }

}
?>
