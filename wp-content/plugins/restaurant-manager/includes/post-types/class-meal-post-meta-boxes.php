<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-meal-post-meta-boxes
 *
 * @author Ryan
 */
class meal_post_meta_boxes {

    /**
     * The post meta box constructor.
     */
    public function __construct() {

        add_action('add_meta_boxes', array($this, 'remove_meta_boxes'), 10);
        add_action('add_meta_boxes', array($this, 'rename_meta_boxes'), 20);
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'), 30);
        add_action('save_post', array($this, 'save_meta_boxes'), 30);
    }

    /**
     * Reorganize the metaboxes.
     * @global type $post
     * @global type $wp_meta_boxes
     */
    public function metabox_positions() {

        global $post, $wp_meta_boxes;

        do_meta_boxes(get_current_screen(), 'advanced', $post);
        unset($wp_meta_boxes[get_post_type($post)]['advanced']);
    }

    /**
     * Remove any unwanted metaboxes.
     */
    public function remove_meta_boxes() {
        
    }

    /**
     * Rename any metaboxes.
     */
    public function rename_meta_boxes() {
        
    }

    /**
     * Add enw metaboxes to the post type.
     */
    public function add_meta_boxes() {

        add_meta_box('meal_properties', __('Meal Properties', 'syn_restaurant_plugin'), array($this, 'meal_properties_meta_box'), 'syn_rest_meal', 'normal', 'high');
    }

    /**
     * Saves the metaboxes
     * @global type $post_type
     * @param type $post_id
     * @return boolean
     */
    public function save_meta_boxes($post_id) {

        global $post_type;

        $session = new session();

        if ('POST' !== strtoupper($session->server_var('REQUEST_METHOD'))) {
            return false;
        }

        if ($post_type !== 'syn_rest_meal') {
            return false;
        }

        $full_price = $session->post_var('full_price');
        $small_plate_price = $session->post_var('small_plate_price');
        $display_price = $session->post_var('display_price', 'off');
        $calorie_count = $session->post_var('calorie_count');
        $new_addition = $session->post_var('new_addition');
        $healthy_option = $session->post_var('healthy_option');
        $gluten_free = $session->post_var('gluten_free');
        $spice_rating = $session->post_var('spice_rating');

        update_post_meta($post_id, 'full_price', $full_price);
        update_post_meta($post_id, 'small_plate_price', $small_plate_price);
        update_post_meta($post_id, 'display_price', $display_price);
        update_post_meta($post_id, 'calorie_count', $calorie_count);
        update_post_meta($post_id, 'new_addition', $new_addition);
        update_post_meta($post_id, 'healthy_option', $healthy_option);
        update_post_meta($post_id, 'gluten_free', $gluten_free);
        update_post_meta($post_id, 'spice_rating', $spice_rating);
    }

    /**
     * The meal properties metabox.
     * @param type $post
     */
    public function meal_properties_meta_box($post) {

        global $syn_restaurant_config;

        $full_price = get_post_meta($post->ID, 'full_price', true);
        $small_plate_price = get_post_meta($post->ID, 'small_plate_price', true);
        $display_price = get_post_meta($post->ID, 'display_price', true);
        $calorie_count = get_post_meta($post->ID, 'calorie_count', true);
        $new_addition = get_post_meta($post->ID, 'new_addition', true);
        $healthy_option = get_post_meta($post->ID, 'healthy_option', true);
        $gluten_free = get_post_meta($post->ID, 'gluten_free', true);
        $spice_rating = get_post_meta($post->ID, 'spice_rating', true);

        $currency_symbol = get_option($syn_restaurant_config->plugin_prefix . 'currency_symbol', '£');

        $display_price = (empty($display_price)) ? 'on' : $display_price;
        ?>
        <div id="syntaxthemes_restaurant_menus">
            <div class="column metabox-content">
                <p>
                    <label><?php _e('Full Price', 'syn_restaurant_plugin') ?></label>
                    <?php echo $currency_symbol ?><input id="full_price" name="full_price" type="text" value="<?php echo (($full_price) ? $full_price : '0.00') ?>" /> 
                </p>
                <p>
                    <label><?php _e('Small Plate Price', 'syn_restaurant_plugin') ?></label>
                    <?php echo $currency_symbol ?><input id="small_plate_price" name="small_plate_price" type="text" value="<?php echo (($small_plate_price) ? $small_plate_price : '0.00') ?>" /> 
                </p>
                <p>
                    <label><?php _e('Display Pricing', 'syn_restaurant_plugin') ?></label>
                    <input id="display_price" name="display_price" type="checkbox" <?php checked($display_price, 'on', true) ?> />
                </p>
                <p>
                    <label><?php _e('Calories Count', 'syn_restaurant_plugin') ?></label>
                    <input id="calorie_count" name="calorie_count" type="text" value="<?php echo $calorie_count ?>" /><?php _e('(kcal)', 'syn_restaurant_plugin') ?> 
                </p>   
            </div>
            <div class="column metabox-content">
                <p>
                    <label><?php _e('New Addition', 'syn_restaurant_plugin') ?></label>
                    <input id="new_addition" name="new_addition" type="checkbox" <?php checked($new_addition, 'on', true) ?> />
                </p>
                <p>
                    <label><?php _e('Healthy Option', 'syn_restaurant_plugin') ?></label>
                    <input id="healthy_option" name="healthy_option" type="checkbox" <?php checked($healthy_option, 'on', true) ?> />
                </p>
                <p>
                    <label><?php _e('Gluten Free', 'syn_restaurant_plugin') ?></label>
                    <input id="gluten_free" name="gluten_free" type="checkbox" <?php checked($gluten_free, 'on', true) ?> />
                </p>
                <p>
                    <label><?php _e('Spice Rating', 'syn_restaurant_plugin') ?></label>
                    <select id="spice_rating" name="spice_rating">
                        <option value="0" <?php selected('0', $spice_rating, true) ?>><?php _e('None Spicy') ?></option>
                        <option value="1" <?php selected('1', $spice_rating, true) ?>><?php _e('1 Star') ?></option>
                        <option value="2" <?php selected('2', $spice_rating, true) ?>><?php _e('2 Star') ?></option>
                        <option value="3" <?php selected('3', $spice_rating, true) ?>><?php _e('3 Star') ?></option>
                    </select>
                </p>
            </div>
        </div>
        <?php
    }

}

//Initialize the class.
new meal_post_meta_boxes();
?>