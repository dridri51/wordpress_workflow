<?php

namespace syntaxthemes\restaurant;

/**
 * Description of synth-control
 *
 * @author Ryan Haworth
 */
abstract class syn_control {

    protected $_config;
    protected $_id;
    protected $_name;
    protected $_defaultValue;
    protected $_data;
    protected $_validation;
    protected $_value;

    public function __construct($id, $name, $default_value, $data = array(), $validation = array()) {

        global $syn_restaurant_config;

        $this->_config = $syn_restaurant_config;
        $this->_id = $id;
        $this->_name = $name;
        $this->_defaultValue = $default_value;
        $this->_data = $data;
        $this->_validation = $validation;
        $this->_value = get_option($id, '');

        if (empty($this->_value)) {
            $this->_value = $this->_defaultValue;
        }
    }

    public function data_attrs() {

        $attributes = '';

        if (!empty($this->_data)) {

            foreach ($this->_data as $key => $value) {
                $attributes .= ' data-' . $key . '="' . $value . '"';
            }
        }

        return $attributes;
    }

    public abstract function render();

    public abstract function test_control();
}
