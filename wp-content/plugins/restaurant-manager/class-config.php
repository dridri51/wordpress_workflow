<?php

namespace syntaxthemes\restaurant;

/**
 * The main plugin config object.
 */
class config {

    public $version;
    public $plugin_name;
    public $plugin_slug;
    public $plugin_basename;
    public $plugin_url;
    public $plugin_path;
    public $text_domain;
    public $plugin_prefix;
    public $dependencies = array();

    /**
     * Setup the plugin config defaults here.
     */
    public function __construct() {

        $this->version = '1.3.1';
        $this->plugin_name = 'Restaurant Manager';
        $this->plugin_slug = 'restaurant-manager';
        $this->plugin_base_name = 'restaurant-manager/restaurant-manager.php';
        $this->plugin_url = plugins_url('', __FILE__);
        $this->plugin_path = plugin_dir_path(__FILE__);
        $this->plugin_prefix = 'syn_restaurant_';
        $this->framework_short_prefix = 'syn_';
        
        //this is the text domain, do not use it in the i18n translator functions
        $this->text_domain = 'syn_restaurant_plugin';
    }

}

/**
 * Initialize the plugin config.
 */
$GLOBALS['syn_restaurant_config'] = new config();
?>