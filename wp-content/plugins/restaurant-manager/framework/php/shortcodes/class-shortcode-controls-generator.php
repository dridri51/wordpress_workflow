<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-synth-shortcode-controls-generator
 *
 * @author Ryan Haworth
 */
class syn_shortcode_controls_generator {

    private $_shortcode_options;

    public function __construct($shortcode_options) {

        $this->_shortcode_options = $shortcode_options;
    }

    public function render() {


        foreach (array($this->_shortcode_options) as $control) {

            $control_manager = new syn_control_manager();

            $html = '<div id="' . $control['id'] . '" class="shortcode-content">';
            $fields = $control['fields'];

            foreach ($fields as $field) {

                $html .= $control_manager->render($field);
            }

            $html .= '</div>';
        }

        return $html;
    }

}

?>
