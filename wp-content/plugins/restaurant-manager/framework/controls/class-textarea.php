<?php

namespace syntaxthemes\restaurant;

/**
 * Description of synth-control-textarea
 *
 * @author Ryan Haworth
 */
class syn_textarea extends syn_control {

    protected $_rows;

    public function __construct($id, $name, $default_value, $rows) {

        parent::__construct($id, $name, $default_value);

        $this->_rows = $rows;
    }

    public function render() {

        $html = '<textarea id="' . $this->_id . '" name="' . $this->_name . '" rows="' . $this->_rows . '">' . ("" != $this->_value ? $this->_value : $this->_defaultValue) . '</textarea>';

        return $html;
    }

    public function test_control() {

        $this->_id = 'syn_textarea_control';
        $this->_name = 'syn_textarea_title';

        echo $this->render();
    }

}
