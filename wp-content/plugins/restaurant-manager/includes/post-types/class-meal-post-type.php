<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-meal-post-type
 *
 * @author Ryan
 */
class meal_post_type {

    private $_post_type;

    /**
     * The restaurant reservation post type.
     */
    public function __construct() {

        $this->_post_type = 'syn_rest_meal';

        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('init', array($this, 'register_post_status'));
        add_action('save_post', array($this, 'save_post'), 20);
        add_filter('manage_posts_columns', array($this, 'column_headers'), 10);
        add_filter('manage_edit-posts_columns', array($this, 'column_headers'), 10);
        add_action('manage_posts_custom_column', array($this, 'column_content'), 10, 2);
        add_filter("manage_edit-{$this->_post_type}_sortable_columns", array($this, 'column_sort'));
        add_filter('request', array($this, 'column_orderby'));
    }

    /**
     * Register the post type.
     */
    public function register_post_type() {

        register_post_type($this->_post_type, array(
            'labels' => array(
                'name' => __('Meals', 'syn_restaurant_plugin'),
                'singular_name' => __('Meal', 'syn_restaurant_plugin'),
                'all_items' => __('Meals', 'syn_restaurant_plugin'),
                'add_new' => __('Add New', 'syn_restaurant_plugin'),
                'add_new_item' => __('Add New Meal', 'syn_restaurant_plugin'),
                'edit' => __('Edit', 'syn_restaurant_plugin'),
                'edit_item' => __('Edit Meal', 'syn_restaurant_plugin'),
                'new_item' => __('New Meal', 'syn_restaurant_plugin'),
                'view' => __('View Meal', 'syn_restaurant_plugin'),
                'view_item' => __('View Meals', 'syn_restaurant_plugin'),
                'search_items' => __('Search Meals', 'syn_restaurant_plugin'),
                'not_found' => __('No booking found', 'syn_restaurant_plugin'),
                'not_found_in_trash' => __('No booking found in trash', 'syn_restaurant_plugin'),
                'parent' => __('Parent Meal', 'syn_restaurant_plugin'),
                'menu_name' => 'House Menus'
            ),
            'description' => __('This is where meal items are stored', 'syn_restaurant_plugin'),
            'public' => true,
            'map_meta_cap' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => true,
            'hierarchical' => false,
            'rewrite' => array('slug' => 'meal', 'with_front' => true),
            'query_var' => true,
            'supports' => array('title', 'editor', 'comments', 'thumbnail', 'excerpt'),
            'has_archive' => true,
            'menu_position' => 211,
            'show_ui' => true,
            'show_in_menu' => false,
            'capability_type' => array('meal', 'meals')
        ));
    }

    /**
     * Register the custom post type taxonomies.
     */
    public function register_taxonomies() {

        register_taxonomy('syn_rest_menu', array($this->_post_type), array(
            'labels' => array(
                'name' => _x('Menus', 'taxonomy general name', 'syn_restaurant_plugin'),
                'singular_name' => _x('Menu', 'taxonomy singular name', 'syn_restaurant_plugin'),
                'search_items' => __('Search Menus', 'syn_restaurant_plugin'),
                'all_items' => __('All Menus', 'syn_restaurant_plugin'),
                'parent_item' => null,
                'parent_item_colon' => null,
                'edit_item' => __('Edit Menu', 'syn_restaurant_plugin'),
                'update_item' => __('Update Menu', 'syn_restaurant_plugin'),
                'add_new_item' => __('Add New Menu', 'syn_restaurant_plugin'),
                'new_item_name' => __('New Menu Name', 'syn_restaurant_plugin'),
                'menu_name' => __('Menus', 'syn_restaurant_plugin'),
            ),
            'public' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'capabilities' => array(
                'manage_terms' => 'manage_syn_rest_menu',
                'edit_terms' => 'manage_syn_rest_menu',
                'delete_terms' => 'manage_syn_rest_menu',
                'assign_terms' => 'edit_meals'
            ),
            'rewrite' => array(
                'slug' => 'menu',
                'with_front' => false
        )));

        register_taxonomy('syn_rest_course', array($this->_post_type), array(
            'labels' => array(
                'name' => _x('Courses', 'taxonomy general name', 'syn_restaurant_plugin'),
                'singular_name' => _x('Course', 'taxonomy singular name', 'syn_restaurant_plugin'),
                'search_items' => __('Search Courses', 'syn_restaurant_plugin'),
                'all_items' => __('All Courses', 'syn_restaurant_plugin'),
                'parent_item' => null,
                'parent_item_colon' => null,
                'edit_item' => __('Edit Course', 'syn_restaurant_plugin'),
                'update_item' => __('Update Course', 'syn_restaurant_plugin'),
                'add_new_item' => __('Add New Course', 'syn_restaurant_plugin'),
                'new_item_name' => __('New Course Name', 'syn_restaurant_plugin'),
                'menu_name' => __('Courses', 'syn_restaurant_plugin'),
            ),
            'public' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'capabilities' => array(
                'manage_terms' => 'manage_syn_rest_course',
                'edit_terms' => 'manage_syn_rest_course',
                'delete_terms' => 'manage_syn_rest_course',
                'assign_terms' => 'edit_meals'
            ),
            'rewrite' => array(
                'slug' => 'course',
                'with_front' => false
        )));

        register_taxonomy('syn_rest_diet', array($this->_post_type), array(
            'labels' => array(
                'name' => _x('Dietary', 'taxonomy general name', 'syn_restaurant_plugin'),
                'singular_name' => _x('Dietary', 'taxonomy singular name', 'syn_restaurant_plugin'),
                'search_items' => __('Search Diets', 'syn_restaurant_plugin'),
                'all_items' => __('All Diets', 'syn_restaurant_plugin'),
                'parent_item' => null,
                'parent_item_colon' => null,
                'edit_item' => __('Edit Dietary', 'syn_restaurant_plugin'),
                'update_item' => __('Update Dietary', 'syn_restaurant_plugin'),
                'add_new_item' => __('Add New Dietary', 'syn_restaurant_plugin'),
                'new_item_name' => __('New Dietary Name', 'syn_restaurant_plugin'),
                'menu_name' => __('Diets', 'syn_restaurant_plugin'),
            ),
            'public' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'capabilities' => array(
                'manage_terms' => 'manage_syn_rest_diet',
                'edit_terms' => 'manage_syn_rest_diet',
                'delete_terms' => 'manage_syn_rest_diet',
                'assign_terms' => 'edit_meals'
            ),
            'rewrite' => array(
                'slug' => 'diet',
                'with_front' => false
        )));

        register_taxonomy('syn_rest_cuisine', array($this->_post_type), array(
            'labels' => array(
                'name' => _x('Cuisines', 'taxonomy general name', 'syn_restaurant_plugin'),
                'singular_name' => _x('Cuisine', 'taxonomy singular name', 'syn_restaurant_plugin'),
                'search_items' => __('Search Cuisines', 'syn_restaurant_plugin'),
                'all_items' => __('All Cuisines', 'syn_restaurant_plugin'),
                'parent_item' => null,
                'parent_item_colon' => null,
                'edit_item' => __('Edit Cuisine', 'syn_restaurant_plugin'),
                'update_item' => __('Update Cuisine', 'syn_restaurant_plugin'),
                'add_new_item' => __('Add New Cuisine', 'syn_restaurant_plugin'),
                'new_item_name' => __('New Cuisine Name', 'syn_restaurant_plugin'),
                'menu_name' => __('Cuisines', 'syn_restaurant_plugin'),
            ),
            'public' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'capabilities' => array(
                'manage_terms' => 'manage_syn_rest_cuisine',
                'edit_terms' => 'manage_syn_rest_cuisine',
                'delete_terms' => 'manage_syn_rest_cuisine',
                'assign_terms' => 'edit_meals'
            ),
            'rewrite' => array(
                'slug' => 'cuisine',
                'with_front' => false
        )));
    }

    /**
     * Register the post status for this post type.
     */
    public function register_post_status() {
        
    }

    /**
     * Save the post
     * 
     * @global type $syn_restaurant_config
     * @global type $post
     * @global type $wpdb
     * @return type
     */
    public function save_post($post_id) {

        if ($post_id == null || empty($_POST)) {
            return null;
        }
        if (!isset($_POST['post_type']) || $_POST['post_type'] != 'syn_rest_reservation') {
            return null;
        }
    }

    /**
     * Create the column headers for the post type.
     * 
     * @global type $post_type
     * @param type $defaults
     * @return type
     */
    public function column_headers($defaults) {

        global $post;

        if ($post->post_type === $this->_post_type) {

            $column_array = array(
                'thumbnail' => ''
            );

            $offset = 1;

            $defaults = array_slice($defaults, 0, $offset, true) +
                    $column_array +
                    array_slice($defaults, $offset, NULL, true);

            $column_array = array(
                'full_price' => __('Price', 'syn_restaurant_plugin')
            );

            $offset = 3;

            $defaults = array_slice($defaults, 0, $offset, true) +
                    $column_array +
                    array_slice($defaults, $offset, NULL, true);
        }

        return $defaults;
    }

    /**
     * Create the column content for the post type.
     * 
     * @global \syntaxthemes\restaurant\type $post
     * @global \syntaxthemes\restaurant\type $post_type
     * @param type $column
     * @param type $post_id
     */
    public function column_content($column, $post_id) {

        global $post, $syn_restaurant_config;

        if ($post->post_type === $this->_post_type) {

            if ($column == 'thumbnail') {

                $post_featured_image = syn_restaurant_menu_portfolio_featured_image($post_id);

                if ($post_featured_image) {
                    echo '<img class="thumbnail" src="' . $post_featured_image . '" />';
                } else {
                    echo '<img class="no-image" src="' . $syn_restaurant_config->plugin_url . '/assets/images/no-image-available.png' . '" />';
                }
            }
            if ($column == 'full_price') {

                $currency_symbol = get_option($syn_restaurant_config->plugin_prefix . 'currency_symbol', '£');
                $price = get_post_meta($post_id, 'full_price', true);
                echo $currency_symbol . $price;
            }
        }
    }

    /**
     * Enable column sorting.
     * 
     * @global \syntaxthemes\restaurant\type $post
     * @global \syntaxthemes\restaurant\type $post_type
     * @param type $columns
     * @return string
     */
    public function column_sort($columns) {

        global $post_type;

        if ($post_type === $this->_post_type) {
            $columns['full_price'] = 'full_price';
        }

        return $columns;
    }

    /**
     * Create the orderby query for the column.
     * 
     * @param type $vars
     * @return type
     */
    public function column_orderby($vars) {

        if (isset($vars['orderby']) && 'full_price' == $vars['orderby']) {

            $vars = array_merge($vars, array(
                'meta_key' => 'full_price',
                'orderby' => 'meta_value'
            ));
        }

        return $vars;
    }

}

new meal_post_type();
?>