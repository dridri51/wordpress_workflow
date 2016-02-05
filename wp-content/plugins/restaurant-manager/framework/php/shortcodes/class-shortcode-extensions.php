<?php

namespace syntaxthemes\restaurant;

/**
 * Initialise shortcode loader
 */
require_once('class-shortcode-loader.php');

/**
 * Initialise shortcode script loader
 */
require_once('class-shortcode-script-loader.php');

/**
 * Initialise shortcode controls generator
 */
require_once('class-shortcode-controls-generator.php');

/**
 * The tinymce manager.
 */
require_once('tinymce/class-synth-tinymce-manager.php');

/**
 * Description of class-shortcode-extensions
 *
 * @author Ryan Haworth
 */
abstract class syn_shortcode_extensions {

    private $_config;
    private $_shortcodes_button;
    protected $_shortcodes;
    private $_tinymce_manager;

    public function __construct() {

        global $syn_restaurant_config;
        $this->_config = $syn_restaurant_config;

        $this->load_additional_libraries();
    }

    public function create_shortcodes_button() {

        $this->_shortcodes_button = array(
            'id' => 'restaurant_shortcodes',
            'plugin_name' => 'syn_restaurant_manager_shortcodes_plugin',
            'title' => __('Shortcodes Manager', 'syn_restaurant_plugin'),
            'image' => $this->_config->plugin_url . '/framework/php/shortcodes/tinymce/images/shortcode-button.png',
            'js' => 'tmce-synth-tinymce-sc-plugin',
            'shortcodes' => array(
            )
        );
    }

    public abstract function create_shortcodes();

    public abstract function load_additional_libraries();

    public function initialise_tinymce_manager() {

        $this->_tinymce_manager = new syn_tinymce_manager($this->_shortcodes_button, $this->_shortcodes);
    }

}

?>
