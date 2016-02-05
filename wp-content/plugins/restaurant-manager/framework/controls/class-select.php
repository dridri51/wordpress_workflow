<?php

namespace syntaxthemes\restaurant;

/**
 * Description of synth-control-select
 *
 * @author Ryan Haworth
 */
class syn_select extends syn_control {

    private $_options;

    public function __construct($id, $name, $default_value, $data, $options) {

        parent::__construct($id, $name, $default_value, $data);

        $this->_options = $options;
    }

    public function render() {

        $html = '<select id="' . $this->_id . '" name="' . $this->_name . '"' . $this->data_attrs() . '>';

        foreach ($this->_options as $option) {

            if (isset($option['group'])) {
                $group_name = $option['text'];
                $options = $option['options'];

                $html .= $this->create_optgroup($group_name, $options);
            } else {
                $html .= '<option value="' . $option['value'] . '"' . selected($option['value'], $this->_value, false) . '>' . $option['text'] . '</option>';
            }
        }

        $html .= '</select>';

        return $html;
    }

    private function create_optgroup($group_name, $options) {

        $html = '<optgroup label="' . $group_name . '">';

        foreach ($options as $option) {

            $html .= '<option value="' . $option['value'] . '"' . selected($option['value'], $this->_value, false) . '>' . $option['text'] . '</option>';
        }

        $html .= '</optgroup>';

        return $html;
    }

    public function test_control() {

        $this->_id = 'syn_select_control';
        $this->_name = 'syn_select_animals';
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
