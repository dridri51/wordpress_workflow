<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-template-locator
 *
 * @author Ryan
 */
class template_locator {

    private $_config;

    public function __construct() {

        global $syn_restaurant_config;
        $this->_config = $syn_restaurant_config;

        add_filter('template_include', array($this, 'template_include'));
    }

    public function template_include($template) {

        $find = array("{$this->_config->plugin_slug}.php");
        $file = '';

        if (is_singular('syn_rest_meal')) {
            $file = 'single-meal.php';
            $find[] = $file;
            $find[] = 'templates/restaurant-manager/' . $file;
        }
        
        if (is_tax('syn_rest_menu')) {
            $file = 'taxonomy-menu.php';
            $find[] = $file;
            $find[] = 'templates/restaurant-manager/' . $file;
        }
        
        if (is_tax('syn_rest_course')) {
            $file = 'taxonomy-course.php';
            $find[] = $file;
            $find[] = 'templates/restaurant-manager/' . $file;
        }
        
        if (is_tax('syn_rest_diet')) {
            $file = 'taxonomy-diet.php';
            $find[] = $file;
            $find[] = 'templates/restaurant-manager/' . $file;
        }
        
        if (is_tax('syn_rest_cuisine')) {
            $file = 'taxonomy-cuisine.php';
            $find[] = $file;
            $find[] = 'templates/restaurant-manager/' . $file;
        }

        if ($file) {

            $template = locate_template($find);

            if (!$template) {
                $template = $this->_config->plugin_path . 'templates/restaurant-manager/' . $file;
            }
        }

        return $template;
    }

    public function get_template_part($slug, $name = '') {

        $template = '';

        // Look in yourtheme/slug-name.php and yourtheme/templates/restaurant-manager/slug-name.php
        if ($name) {
            $template = locate_template(array("{$slug}-{$name}.php", "templates/restaurant-manager/{$slug}-{$name}.php"));
        }

        // Get default slug-name.php
        if (!$template && $name && file_exists("{$this->_config->plugin_path}templates/restaurant-manager/{$slug}-{$name}.php")) {
            $template = "{$this->_config->plugin_path}templates/restaurant-manager/{$slug}-{$name}.php";
        }

        // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/templates/restaurant-manager/slug.php
        if (!$template) {
            $template = locate_template(array("{$slug}.php", get_stylesheet_directory() . "{$slug}.php"));
        }
        if ($template) {
            load_template($template, false);
        }
    }

}

new template_locator();
?>