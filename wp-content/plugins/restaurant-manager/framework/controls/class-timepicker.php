<?php

namespace syntaxthemes\restaurant;

/**
 * Description of synth-time-picker 
 *
 * @author Ryan Haworth
 */
class syn_timepicker extends syn_control {

    /**
     * 
     * @param type $id
     * @param type $name
     * @param type $default_value
     * @param type $validation
     */
    public function __construct($id, $name, $default_value, $data, $validation) {

        parent::__construct($id, $name, $default_value, $data, $validation);
    }

    public function render() {

        $html = '<input class="syn-timepicker" type="text" id="' . $this->_id . '" name="' . $this->_name . '" value="' . ("" != $this->_value ? $this->_value : $this->_defaultValue) . '" ' . $this->_validation . $this->data_attrs() . ' readonly/>';

        return $html;
    }

    public function test_control() {

        $this->_id = 'syn_timepicker_control';
        $this->_name = 'syn_timepicker_title';

        echo $this->render();
    }

}
