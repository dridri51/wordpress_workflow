<?php

namespace syntaxthemes\restaurant;

require_once(ABSPATH . 'wp-admin/includes/plugin.php');

/**
 * The Core class.
 */
if (!class_exists('plugin_core')) {

    class plugin_core {

        protected $_config;
        public $_has_requirements;

        /**
         * Minimal required PHP version string.
         * @var type string
         */
        private $_required_php_version = '5.3';
        private $_required_wordpress_version = '3.5';

        public function __construct($config) {

            $this->_config = $config;

            add_action('init', array($this, 'plugin_requirements_check'));
        }

        public function plugin_requirements_check() {

            $passed = true;

            if (!$this->php_version_check() && $passed) {
                $passed = false;
            }

            if (!$this->wordpress_version_check() && $passed) {
                $passed = false;
            }

            if (!$this->plugin_check_dependencies() && $passed) {
                $passed = false;
            }

            $this->_has_requirements = $passed;
            return $passed;
        }

        public function plugin_check_dependencies() {

            $passed = true;

            foreach ($this->_config->dependencies as $dependency) {

                if (!is_plugin_active($dependency['basename']) && $passed) {
                    $dependency['installed'] = false;
                    $passed = false;
                }
            }

            if (!$passed) {
                add_action('admin_notices', array($this, 'notice_dependency_required'));
            }

            return $passed;
        }

        public function plugin_manual_deactivate() {

            deactivate_plugins($this->_config->plugin_basename);
        }

        /**
         * Set the minimum PHP version which the plugin requires.
         * @param type $minimumPhpVersion
         */
        public function set_php_version($minimum_php_version) {
            $this->_required_php_version = $minimum_php_version;
        }

        public function set_wordpress_version($minimum_wordpress_version) {
            $this->_required_wordpress_version = $minimum_wordpress_version;
        }

        /**
         * Check the PHP version, if the version is wrong then display an
         * error message.
         * @return boolean
         */
        public function php_version_check() {

            if (version_compare(phpversion(), $this->_required_php_version) <= 0) {
                add_action('admin_notices', array($this, 'notice_php_version_wrong'));
                return false;
            }
            return true;
        }

        public function wordpress_version_check() {

            global $wp_version;

            if (version_compare($wp_version, $this->_required_wordpress_version) <= 0) {
                add_action('admin_notices', array($this, 'notice_wordpress_version_wrong'));
                return false;
            }

            return true;
        }

        /**
         * If the PHP version is wrong then display this message.
         */
        public function notice_php_version_wrong() {

            echo '<div class="error"><p>' .
            __('Error: Plugin: ' . $this->_config->plugin_name . ' requires a newer version of PHP to be running.', 'syntaxstudio') .
            '<br/>' . __('Minimal version of PHP required: ', 'syntaxstudio') . '<strong>' . $this->_required_php_version . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', $this->_config->text_domain) . '<strong>' . phpversion() . '</strong>' .
            '</p></div>';
        }

        public function notice_wordpress_version_wrong() {

            global $wp_version;

            echo '<div class="error"><p>' .
            __('Error: Plugin: ' . $this->_config->plugin_name . ' requires a newer version of Wordpress to be running.', $this->_config->text_domain) .
            '<br/>' . __('Minimal version of Wordpress required: ', $this->_config->text_domain) . '<strong>' . $this->_required_wordpress_version . '</strong>' .
            '<br/>' . __('Your server\'s Wordpress version: ', $this->_config->text_domain) . '<strong>' . $wp_version . '</strong>' .
            '</p></div>';
        }

        public function notice_dependency_required() {

            $html = '';

            foreach ($this->_config->dependencies as $dependency) {
                $html .= __('The following plugin: ' . $dependency['name'] . ' is not installed and is required', $this->_config->text_domain) . '<br/>';
            }

            echo '<div class="error"><p>' .
            __('Error: Plugin: ' . $this->_config->plugin_name . ' requires the following plugins to work.', $this->_config->text_domain) . '<br/>' . $html .
            '</p></div>';
        }

    }

}
?>