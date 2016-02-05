<?php

/*
  Plugin Name: Restaurant Manager
  Plugin URI: http://www.syntaxthemes.co.uk
  Version: 1.3.1
  Author: Ryan Haworth
  Description: Restaurant Manager is a plugin to manage your restaurant.  Create your dinner menus, take reservations and send and receive notifications with your customers.
  Text Domain: syn_restaurant_plugin
  License: GPLv3
 */

namespace syntaxthemes\restaurant;

require_once('class-config.php');
global $syn_restaurant_config;
$syn_restaurant_config->plugin_basename = plugin_basename(__FILE__);

/**
 * Initialise the framework.
 */
require_once('framework/framework.php');

/**
 * Initialise the plugin prerequisites.
 */
require_once('class-initialize.php');
require_once('class-plugin.php');
require_once('includes/includes.php');

//////////////////////////////////
// initialization
//////////////////////////////////
$initialize = new initialize();

// Register the Plugin Activation Hook
register_activation_hook(__FILE__, array($initialize, 'activate'));

// Register the Plugin Deactivation Hook
register_deactivation_hook(__FILE__, array($initialize, 'deactivate'));
?>