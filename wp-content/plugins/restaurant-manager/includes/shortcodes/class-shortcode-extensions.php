<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-taurus-shortcode-extensions
 *
 * @author Ryan Haworth
 */
class shortcode_extensions extends syn_shortcode_extensions {

    /**
     * The shortcode constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * This function will iterate the shortcode class
     * objects and initialise the shortcodes.
     */
    public function create_shortcodes() {

        $shortcode_classes = array(
            'syntaxthemes\restaurant\syn_restaurant_reservation',
            'syntaxthemes\restaurant\syn_restaurant_menu'
        );
                
        $shortcode_classes = apply_filters('syn_restaurant_manager_add_shortcodes', $shortcode_classes);
      
        foreach ($shortcode_classes as $class) {

            $this->_shortcodes[$class] = new $class();
        }
    }

    /**
     * This function will search through the shortcode components folder
     * and add shortcodes to its library.
     */
    public function load_additional_libraries() {

        $paths[] = dirname(__FILE__) . "/components/";

        foreach ($paths as $path) {
            foreach (glob($path . '*.php') as $file) {
                require_once( $file );
            }
        }
    }

}