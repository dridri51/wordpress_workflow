<?php

namespace syntaxthemes\restaurant;

/**
 * Description of PluginBase
 *
 * @author Ryan Haworth
 */
if (!class_exists('plugin_base')) {

    abstract class plugin_base extends plugin_core {

        public function __construct($config) {

            parent::__construct($config);
            $this->_config = $config;
        }

        function activate() {

            if ($this->php_version_check()) {

                $this->install_database_tables();

                $this->install_options();

                $this->plugin_installed_version();

                $this->mark_plugin_activated();
            } else {
                $this->deactivate();
            }
        }

        public abstract function install_database_tables();

        public abstract function install_options();

        public abstract function upgrade();

        public abstract function add_actions_and_filters();

        public abstract function add_admin_menu();

        public abstract function admin_scripts($hook);

        public abstract function frontend_scripts();

        public function deactivate() {

            $this->mark_plugin_deactivated();
        }

        /**
         * Check if the plugin is already installed and return true
         * if it is.
         * @return $installed
         */
        public function is_installed() {

            $installed = get_option($this->_config->plugin_prefix . 'activated');
            
            return $installed;
        }

        /**
         * Get the plugin installed version.
         * @return $version
         */
        public function plugin_installed_version() {

            $version = get_option($this->_config->plugin_prefix . 'version');

            return $version;
        }

        /**
         * When the plugin has been activated, mark as activated.  Also the
         * plugin version is updated.
         */
        public function mark_plugin_activated() {

            update_option($this->_config->plugin_prefix . 'activated', true);
            update_option($this->_config->plugin_prefix . 'version', $this->_config->version);
        }

        /**
         * When the plugin is deactivated mark as deactivated.
         */
        public function mark_plugin_deactivated() {

            update_option($this->_config->plugin_prefix . 'activated', false);
        }

    }

}
?>