<?php

namespace syntaxthemes\restaurant;

/**
 * Description of dropcap
 *
 * @author Ryan Haworth
 */
// Do not load this file directly
if (!defined('ABSPATH')) {
    die('-1');
}

/**
 * The reservation shortcode class.
 */
class syn_restaurant_menu extends syn_shortcode_script_loader {

    /**
     * The shortcode registration button.  This will appear in the shortcodes
     * menu.
     */
    public function register_button() {

        $this->shortcode = array();
        $this->shortcode['name'] = $this->_config->framework_short_prefix . 'restaurant_menu';
        $this->shortcode['heading'] = __('Restaurant Menu', 'syn_restaurant_plugin');
        $this->shortcode['modal_editor'] = true;
        $this->shortcode['modal_button'] = 'save';
    }

    /**
     * The form elements for the modal window.
     * @param type $shortcode
     */
    public function form_elements($shortcode = null) {

        $this->_form_elements = array(
            'id' => $this->_config->framework_short_prefix . 'shortcode_restaurant_menu',
            'fields' => array(
                array(
                    'id' => $this->_config->framework_short_prefix . 'restaurant_menu_menu_id',
                    'name' => $this->_config->framework_short_prefix . 'restaurant_menu_menu_id',
                    'header' => __('All Menus', 'synth_astrono_theme'),
                    'label' => __('Text', 'synth_astrono_theme'),
                    'desc' => __('Choose your restaurant menu.', 'syn_restaurant_plugin'),
                    'std' => '',
                    'data' => array(
                        'visibility' => 'hidden'
                    ),
                    'options' => syn_restaurant_menus_get_all_terms_options(array('syn_rest_menu')),
                    'type' => 'select'
                ),
                array(
                    'id' => $this->_config->framework_short_prefix . 'restaurant_menu_course_id',
                    'name' => $this->_config->framework_short_prefix . 'restaurant_menu_course_id',
                    'header' => __('All Courses', 'synth_astrono_theme'),
                    'label' => __('Text', 'synth_astrono_theme'),
                    'desc' => __('Choose your restaurant course.', 'syn_restaurant_plugin'),
                    'std' => '',
                    'data' => array(
                        'visibility' => 'hidden'
                    ),
                    'options' => syn_restaurant_menus_get_all_terms_options(array('syn_rest_course')),
                    'type' => 'select'
                ),
                array(
                    'id' => $this->_config->framework_short_prefix . 'restaurant_menu_ids',
                    'name' => $this->_config->framework_short_prefix . 'restaurant_menu_ids',
                    'header' => __('Menu Item', 'synth_astrono_theme'),
                    'label' => __('Meal', 'synth_astrono_theme'),
                    'desc' => __('Choose your next meal to display in the menu.', 'synth_astrono_theme'),
                    'std' => __('All Meals', 'synth_astrono_theme'),
                    'options' => syn_restaurant_menus_get_all_meal_options(),
                    'type' => 'checkbox-list'
                ),
                array(
                    'id' => $this->_config->framework_short_prefix . 'restaurant_menu_show_image',
                    'name' => $this->_config->framework_short_prefix . 'restaurant_menu_show_image',
                    'header' => __('Show Image', 'syn_taurus_core_plugin'),
                    'label' => __('Show Image', 'syn_taurus_core_plugin'),
                    'desc' => __('Display the image set for the meal items.', 'syn_taurus_core_plugin'),
                    'std' => 'false',
                    'options' => array(
                        array('text' => __('No', 'syn_taurus_core_plugin'), 'value' => 'false'),
                        array('text' => __('Yes', 'syn_taurus_core_plugin'), 'value' => 'true')
                    ),
                    'type' => 'select'
                ),
                array(
                    'id' => $this->_config->framework_short_prefix . 'restaurant_menu_image_size',
                    'name' => $this->_config->framework_short_prefix . 'restaurant_menu_image_size',
                    'header' => __('Post Image Size', 'synth_astrono_theme'),
                    'label' => __('Image Size', 'synth_astrono_theme'),
                    'desc' => __('Choose the scale of the post image.', 'synth_astrono_theme'),
                    'std' => 'medium',
                    'options' => syn_restaurant_manager_image_size_options(),
                    'type' => 'select'
                ),
            )
        );
    }

    /**
     * This functions builds the html for the shortcode.
     * @param type $atts
     * @param type $content
     * @return type
     */
    public function handle_shortcode($atts, $content = null) {

        global $syn_restaurant_config;

        $atts = shortcode_atts(array(
            'ids' => '',
            'show_image' => 'false',
            'image_size' => 'medium'
                ), $atts);

        extract($atts);

        $args = array(
            'post_type' => 'syn_rest_meal',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post__in' => explode(',', $ids)
        );

        $query = new \WP_Query($args);

        $html = '';

        ob_start();

        if ($query->have_posts()) {
            ?>
            <div class="syntaxthemes_restaurant_menu">
                <ul class="syn-restaurant-menu">
                    <?php
                    while ($query->have_posts()) {
                        $query->the_post();

                        $post_id = get_the_ID();
                        
                        $image = '';
                        if ($show_image === 'true') {
                            $image = get_the_post_thumbnail(get_the_ID(), $image_size);
                        }

                        $currency_symbol = get_option($syn_restaurant_config->plugin_prefix . 'currency_symbol', '£');
                        $full_price = get_post_meta($post_id, 'full_price', true);
                        $small_plate_price = get_post_meta($post_id, 'small_plate_price', true);
                        $display_price = get_post_meta($post_id, 'display_price', true);
                        $new_addition = get_post_meta($post_id, 'new_addition', true);
                        $calorie_count = get_post_meta($post_id, 'calorie_count', true);
                        $healthy_option = get_post_meta($post_id, 'healthy_option', true);
                        $gluten_free = get_post_meta($post_id, 'gluten_free', true);
                        $spice_rating = get_post_meta($post_id, 'spice_rating', true);
                        ?>
                        <li class="restaurant-menu-item">
                            <?php echo $image ?>
                            <h4 class="syn-menu-title">
                                <a href="<?php the_permalink() ?>"><?php the_title() ?></a>                        
                            </h4>                            
                            <div class="syn-menu-content">
                                <div class="syn-menu-excerpt"><?php the_excerpt() ?></div>
                                <?php if ($full_price && ($full_price !== '0.00') && ($display_price === 'on')) { ?>
                                    <span class="syn-menu-price"><?php echo $currency_symbol . $full_price ?></span>
                                <?php } ?>
                                <div class="syn-menu-properties">
                                    <?php if ($new_addition) { ?>
                                        <span class="syn-new-addition">
                                            <?php echo (($new_addition === 'on') ? __('New', 'syn_restaurant_plugin') : '') ?>
                                        </span>
                                    <?php } ?>
                                    <?php if ($small_plate_price && ($small_plate_price !== '0.00') && ($display_price === 'on')) { ?>
                                        <span class="syn-small-price">
                                            <?php echo __('Small Plate', 'syn_restaurant_plugin') . ' ' . $currency_symbol . $small_plate_price ?>
                                        </span>
                                    <?php } ?>
                                    <?php if ($calorie_count) { ?>
                                        <span class="syn-calorie-count">
                                            <?php echo $calorie_count . __('(kcal)', 'syn_restaurant_plugin') ?>
                                        </span>
                                    <?php } ?>
                                    <?php if ($healthy_option) { ?>
                                        <span class="syn-healthy-option">
                                            <?php echo (($healthy_option === 'on') ? __('Healthy Option', 'syn_restaurant_plugin') : '') ?>
                                        </span>
                                    <?php } ?>
                                    <?php if ($gluten_free) { ?>
                                        <span class="syn-gluten-free">
                                            <?php echo (($gluten_free === 'on') ? __('Gluten Free', 'syn_restaurant_plugin') : '') ?>
                                        </span>
                                    <?php } ?>
                                    <?php if ($spice_rating) { ?>
                                        <span class="syn-spice-rating">
                                            <?php echo syn_restaurant_menus_get_spice_rating($spice_rating) ?>
                                        </span>
                                    <?php } ?>
                                </div>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        return ob_get_clean();
    }

    /**
     * If the shortcode requires any scripts or styles they can be
     * enqueued here.
     */
    public function add_script() {

        if ($this->do_add_script) {

            $this->do_add_script = false;
        }
    }

}
?>