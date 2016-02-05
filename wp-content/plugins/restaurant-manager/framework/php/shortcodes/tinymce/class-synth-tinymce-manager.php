<?php

namespace syntaxthemes\restaurant;

/**
 * Description of tiny-mce-manager-class
 *
 * @author Ryan Haworth
 */
class syn_tinymce_manager {

    private $_config;
    private $button;
    private $shortcodes;

    public function __construct($button = array(), $shortcodes = array()) {

        global $syn_restaurant_config;

        $this->_config = $syn_restaurant_config;
        $this->button = $button;
        $this->shortcodes = $shortcodes;

        add_action('admin_init', array($this, 'enqueue_scripts'));
        add_action('init', array($this, 'tinymce_init'));
        
        $this->tinymce_init();
    }

    public function enqueue_scripts() {

        //wp_enqueue_script('synth-modal', $this->_config->plugin_url . '/framework/js/synth-modal.js', array('jquery'), $this->_config->version, true);
    }

    public function tinymce_init() {

        if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
            return;

        if (get_user_option('rich_editing') == 'true') {
            add_filter('mce_external_plugins', array($this, 'tinymce_plugins'));
            add_filter('mce_buttons', array($this, 'tinymce_buttons'));
            add_filter('admin_print_scripts', array($this, 'tinymce_shortcode_globals'));
        }
    }

    function tinymce_plugins($plugin_array) {

        global $tinymce_version;

        if (version_compare($tinymce_version[0], 4, '>=')) {
            $plugin_array[$this->button['plugin_name']] = $this->_config->plugin_url . '/framework/php/shortcodes/tinymce/js/min/tmce-synth-tinymce-sc-plugin-4x.min.js';
        } else {
            $plugin_array[$this->button['plugin_name']] = $this->_config->plugin_url . '/framework/php/shortcodes/tinymce/js/min/tmce-synth-tinymce-sc-plugin-3x.min.js';
        }

        return $plugin_array;
    }

    function tinymce_buttons($buttons) {

        array_push($buttons, "separator", $this->button['id']);

        return $buttons;
    }

    function tinymce_fullscreen_buttons($buttons) {

        $buttons[$this->button['id']] = array(
            'title' => __('Restaurant Manager Shortcodes', 'syn_restaurant_plugin'),
            'both' => true
        );
        return $buttons;
    }

    function tinymce_shortcode_globals() {

        $global_variable_name = 'syn_restaurant_manager_shortcodes';

        echo "\n <script type='text/javascript'>\n /* <![CDATA[ */  \n";
        echo "var $global_variable_name = $global_variable_name  || {}; \n";
        echo "    $global_variable_name.globals = $global_variable_name.globals || {};\n";
        echo "    $global_variable_name.globals['" . $this->button['id'] . "'] = []; \n";
        echo "    $global_variable_name.globals['" . $this->button['id'] . "'].plugin_name = '" . $this->button['plugin_name'] . "'; \n";
        echo "    $global_variable_name.globals['" . $this->button['id'] . "'].title = '" . $this->button['title'] . "'; \n";
        echo "    $global_variable_name.globals['" . $this->button['id'] . "'].image = '" . $this->button['image'] . "'; \n";
        echo "    $global_variable_name.globals['" . $this->button['id'] . "'].config = []; \n";

        if (!empty($this->shortcodes)) {
            foreach ($this->shortcodes as $shortcode) {

                echo "    $global_variable_name.globals['" . $this->button['id'] . "'].config['" . $shortcode->shortcode['name'] . "'] = " . json_encode($shortcode->shortcode) . "; \n";
            }
        }

        echo "/* ]]> */ \n";
        echo "</script>\n \n ";
    }

}

?>