<?php

namespace syntaxthemes\restaurant;

/**
 * The Shortcode_Loader class. 
 * @abstract
 */
// Do not load directly
if (!defined('ABSPATH')) {
    die('-1');
}

abstract class syn_shortcode_loader {

    public $shortcode = array();
    protected $_config;
    protected $_form_elements;

    public function __construct() {

        global $syn_restaurant_config;

        $this->_config = $syn_restaurant_config;
        $this->register_button();
        $this->register($this->shortcode['name']);

        add_action('wp_ajax_' . $this->shortcode['name'], array($this, 'shortcode_popup_editor_ajax'));
    }

    public abstract function form_elements($shortcode = null);

    public abstract function register_button();

    /**
     * @param  $shortcodeName mixed either string name of the shortcode
     * (as it would appear in a post, e.g. [shortcodeName])
     * or an array of such names in case you want to have more than one name
     * for the same shortcode
     * @return void
     */
    public function register($shortcodeName) {
        $this->register_shortcode_to_function($shortcodeName, 'handle_shortcode');
    }

    /**
     * @param  $shortcodeName mixed either string name of the shortcode
     * (as it would appear in a post, e.g. [shortcodeName])
     * or an array of such names in case you want to have more than one name
     * for the same shortcode
     * @param  $functionName string name of public function in this class to call as the
     * shortcode handler
     * @return void
     */
    protected function register_shortcode_to_function($shortcodeName, $functionName) {

        if (is_array($shortcodeName)) {
            foreach ($shortcodeName as $aName) {
                add_shortcode($aName, array($this, $functionName));
            }
        } else {
            add_shortcode($shortcodeName, array($this, $functionName));
        }
    }

    /**
     * @abstract Override this function and add actual shortcode handling here
     * @param  $atts shortcode inputs
     * @return string shortcode content
     */
    public abstract function handle_shortcode($atts, $content = null);

    public function shortcode_popup_editor_ajax() {

        $shortcode = '';

        if (isset($_REQUEST['params'])) {
            $params = $_REQUEST['params'];
            $shortcode = stripslashes($params);
        }

        $this->form_elements($shortcode);
        $syn_shortcode_controls_generator = new syn_shortcode_controls_generator($this->_form_elements);
        $html = $syn_shortcode_controls_generator->render();

        $response = new \WP_Ajax_Response();

        if (!is_wp_error($response)) {

            $xml = array(
                'id' => '',
                'what' => 'syn_shortcode_container',
                'action' => 'syn_shortcode_container_elements',
                'data' => $html
            );
        } else {
            $xml = array(
                'id' => '',
                'what' => 'admin_settings',
                'action' => 'show_error_msg',
                'data' => '<div id="message" class="error"><p>' . __('Your settings have not been saved.  An error has occured.', 'syn_restaurant_plugin') . '</p></div>'
            );
        }

        $response->add($xml);
        $response->send();
    }

    protected function parse_shortcode_attribute($attribute, $text) {

        $match = '';
        $matches = array();

        if (preg_match("/\[.* $attribute=\"(?<$attribute>.*?)\".*]/s", $text, $matches)) {

            $match = $matches[$attribute];
        }

        return $match;
    }

    protected function parse_shortcode_content($text) {

        $match = '';
        $matches = array();

        if (preg_match("/\[.*\](?<content>.*)\[\/.*\]/s", $text, $matches)) {

            $match = $matches['content'];
        }

        return $match;
    }

}