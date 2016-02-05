<?php

namespace syntaxthemes\restaurant;

/**
 * Initialize class.
 */
class initialize {

    private $_config;
    private $_plugin;

    /**
     * The Initialize constructor.
     * 
     * @param type $plugin_name
     * @param type $plugin_text_domain
     * @param type $plugin_option_prefix
     * @param type $plugin_version
     */
    public function __construct() {

        global $syn_restaurant_config;

        $this->_config = $syn_restaurant_config;
        $this->_plugin = new plugin();

        //////////////////////////////////
        // Run the plugin
        //////////////////////////////////
        add_action('init', array($this, 'run'));
    }

    /**
     * The activate function is called when the plugin is activated.
     * @return void
     */
    public function activate() {

        // Execute the plugin requirements 
        if ($this->_plugin->plugin_requirements_check()) {

            // Perform any version-upgrade events prior to activation (e.g. database changes)
            //$this->_plugin->upgrade();

            if (!$this->_plugin->is_installed()) {
                $this->_plugin->activate();
            }

            //Temp for marking the plugin version.
            $this->_plugin->mark_plugin_activated();
        }
    }

    /**
     * The deactivate function is called when the plugin is deactivated.
     * @return void
     */
    public function deactivate() {

        if ($this->_plugin->is_installed()) {
            $this->_plugin->deactivate();
        }
    }

    function check_auto_update() {

        $plugin_current_version = $this->_config->version;
        $plugin_remote_path = 'http://www.syntaxthemes.co.uk/synth-server/synth-rift-slider-update.php';
        $plugin_slug = $this->_config->plugin_name;

        //new rift_slider_pro_wp_autoupdate($plugin_current_version, $plugin_remote_path, $plugin_slug);
    }

    /**
     * This function will run on every web request.  Keep this function
     * as slim-lined as possible for effecient execution.
     * @return void
     */
    public function run() {

        // Execute the plugin requirements 
        if ($this->_plugin->_has_requirements) {

            //check if the plugin is installed before running
            if ($this->_plugin->is_installed()) {

                $this->_plugin->upgrade();

                //Temp for marking the plugin version.
                $this->_plugin->mark_plugin_activated();

                $taurus_shortcode_extensions = new shortcode_extensions();
                $taurus_shortcode_extensions->create_shortcodes_button();
                $taurus_shortcode_extensions->create_shortcodes();
                $taurus_shortcode_extensions->initialise_tinymce_manager();

                //$this->check_auto_update();
                //add callbacks to hooks            
                $this->_plugin->add_actions_and_filters();
            }
        } else {
            $this->_plugin->plugin_manual_deactivate();
        }
    }

}

?>