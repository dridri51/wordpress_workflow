<?php

namespace syntaxthemes\restaurant;

/**
 * Description of synth-checkbox-list
 *
 * @author Ryan Haworth
 */
class syn_checkbox_list extends syn_control {

    protected $_options;

    public function __construct($id, $name, $default_value, $options) {

        parent::__construct($id, $name, $default_value);

        $this->_options = $options;
    }

    public function render() {

        $html = '<ul id="' . $this->_id . '">';

        if (!empty($this->_options)) {
            foreach ($this->_options as $option) {
                $html .= '<li><input type="checkbox" name="' . $this->_name . '" value="' . $option['value'] . '"' . checked($option['value'], $this->_value, false) . '>&nbsp;<label>' . $option['text'] . '</label></li>';
            }
        }

        $html .= '</ul>';

        return $html;
    }

    public function test_control() {

        $this->_id = 'syn_checkbox_list_control';
        $this->_name = 'syn_checkbox_list_control';
        $this->_value = 'fish';
        $this->_options = array(
            array('value' => 'cat', 'text' => 'Cat'),
            array('value' => 'dog', 'text' => 'Dog'),
            array('value' => 'fish', 'text' => 'Fish'),
            array('value' => 'bird', 'text' => 'Bird'),
            array('value' => 'mouse', 'text' => 'Mouse'),
        );

        echo $this->render();
    }

}
