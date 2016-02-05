<?php

namespace syntaxthemes\restaurant;

/**
 * Description of synth-control-colorpicker
 *
 * @author Ryan Haworth
 */
class syn_colorpicker extends syn_control {

    public function __construct($id, $name, $default_value) {

        parent::__construct($id, $name, $default_value);
    }

    public function render() {

        $html = '<input class="synth-colorpicker synth-text-small" type="text" id="' . $this->_id . '" name="' . $this->_name . '" value="' . $this->_value . '" />';

        return $html;
    }

    public function test_control() {

        $this->_id = 'syn_colorpicker_control';
        $this->_name = 'syn_colorpicker_title';
        $this->_value = '#ff0000';

        echo $this->render();
    }

}
