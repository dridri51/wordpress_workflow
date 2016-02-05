<?php

namespace syntaxthemes\restaurant;

require_once('class-control.php');
require_once('class-text.php');
require_once('class-select.php');
require_once('class-textarea.php');
require_once('class-datepicker.php');
require_once('class-timepicker.php');
require_once('class-colorpicker.php');
require_once('class-checkbox-list.php');

/**
 * Description of class-synth-control-manager
 *
 * @author Ryan
 */
class syn_control_manager {

    protected $templates;
    protected $_template;

    public function __construct() {
        
    }

    public function set_template($template) {

        $this->_template = $template;
    }

    public function render($obj, $meta = null) {

        $html = '';
        $control = '';

        $id = $this->get_element('id', $obj);
        $name = $this->get_element('name', $obj);
        $type = $this->get_element('type', $obj);
        $data = $this->get_element('data', $obj);
        $validation = $this->get_element('validation', $obj);
        $template_name = $this->get_element('template', $obj);

        //get the default value
        if (empty($meta)) {
            $meta_value = get_option($name, '');
            $default_value = ($meta_value === '') ? $this->get_element('std', $obj) : $meta_value;
        } else {
            $default_value = $meta;
        }

        switch ($type) {

            case 'text': {
                    $element = new syn_text($id, $name, $default_value, $data, $validation);
                    $control .= $element->render();
                }
                break;
            case 'text-area': {
                    $element = new syn_textarea($id, $name, $default_value, 7);
                    $control .= $element->render();
                }
                break;
            case 'select': {
                    $options = $this->get_element('options', $obj);

                    $element = new syn_select($id, $name, $default_value, $data, $options);
                    $control .= $element->render();
                }
                break;
            case 'checkbox': {
                    $label = $this->get_element('label', $obj);

                    $element = new syn_checkbox($id, $name, $default_value, $label);
                    $control .= $element->render();
                }
                break;
            case 'checkbox-list': {
                    $options = $this->get_element('options', $obj);

                    $element = new syn_checkbox_list($id, $name, $default_value, $options);
                    $control .= $element->render();
                }
                break;
            case 'radio-list': {
                    $options = $this->get_element('options', $obj);

                    $element = new syn_radio_list($id, $name, $default_value, $options);
                    $control .= $element->render();
                }
                break;
            case 'radio-image-list': {
                    $options = $this->get_element('options', $obj);
                    $display = $this->get_element('display', $obj);

                    $element = new syn_radio_image_list($id, $name, $default_value, $options, $display);
                    $control .= $element->render();
                }
                break;
            case 'datepicker': {
                    $element = new syn_datepicker($id, $name, $default_value, $data, $validation);
                    $control .= $element->render();
                }
                break;
            case 'timepicker': {
                    $element = new syn_timepicker($id, $name, $default_value, $data, $validation);
                    $control .= $element->render();
                }
                break;
            case 'colorpicker': {
                    $element = new syn_colorpicker($id, $name, $default_value);
                    $control .= $element->render();
                }
                break;
            case 'color-selector': {
                    $controls = $this->get_element('controls', $obj);

                    $element = new syn_color_selector($id, $name, $default_value, $controls);
                    $control .= $element->render();
                }
                break;
            case 'notification': {
                    $title = $this->get_element('title', $obj);
                    $notification = $this->get_element('notification', $obj);

                    $element = new syn_notification($id, $name, $default_value, $title, $notification);
                    $control .= $element->render();
                }
                break;
            case 'media-upload': {
                    $uploader_title = $this->get_element('uploader_title', $obj);
                    $uploader_button_text = $this->get_element('uploader_button_text', $obj);

                    $element = new syn_media_upload($id, $name, $default_value, $uploader_title, $uploader_button_text);
                    $control .= $element->render();
                }
                break;
            case 'media-gallery-upload': {
                    $uploader_title = $this->get_element('uploader_title', $obj);
                    $uploader_button_text = $this->get_element('uploader_button_text', $obj);

                    $element = new syn_media_gallery_upload($id, $name, $default_value, $uploader_title, $uploader_button_text);
                    $control .= $element->render();
                }
                break;
            case 'control-group': {
                    $controls = $this->get_element('controls', $obj);

                    $element = new syn_control_group($id, $name, $default_value, $controls);
                    $control .= $element->render();
                }
                break;
            case 'hidden': {
                    $element = new syn_hidden($id, $name, $default_value);
                    $control .= $element->render();
                }
                break;
            case 'font-awesome-icon': {
                    $show_icons = $this->get_element('show_icons', $obj);

                    $element = new syn_font_awesome_selector($id, $name, $default_value, $show_icons, $data);
                    $control .= $element->render();
                }
                break;
            case 'backgrounds-selector': {
                    $element = new syn_background_selector($id, $name, $default_value);
                    $control .= $element->render();
                }
                break;
            case 'fonts-selector': {
                    $element = new syn_fonts_selector($id, $name, $default_value);
                    $control .= $element->render();
                }
                break;
            case 'nested-shortcode-builder': {
                    $elements = $this->get_element('controls', $obj);
                    $item_template = $this->get_element('item_template', $obj);
                    $parent_shortcode = $this->get_element('parent_shortcode', $obj);

                    $element = new syn_nested_shortcode_builder($id, $name, $default_value, $elements, $item_template, $parent_shortcode);
                    $control .= $element->render();
                }
                break;
            case 'editor': {
                    $element = new syn_editor($id, $name, $default_value, $data);
                    $control .= $element->render();
                }
                break;
            case 'wordpress-dropdown': {
                    $query_args = $this->get_element('args', $obj);

                    $element = new syn_wordpress_dropdown($id, $name, $default_value, $query_args);
                    $control .= $element->render();
                }

                break;
            case 'wordpress-authors': {
                    $element = new syn_wordpress_authors($id, $name, $default_value);
                    $control .= $element->render();
                }
                break;
            case 'wordpress-taxonomies': {
                    $args = $this->get_element('args', $obj);

                    $element = new syn_wordpress_taxonomies($id, $name, $default_value, $args);
                    $control .= $element->render();
                }
                break;
            case 'extender': {
                    $controls = $this->get_element('controls', $obj);

                    $element = new syn_extender($id, $name, $default_value, $controls);
                    $control .= $element->render();
                }
                break;
            case 'table-builder': {

                    $element = new syn_table_builder($id, $name, $default_value);
                    $control .= $element->render();
                }
                break;
            case 'tabs-builder': {

                    $parent_shortcode = $this->get_element('parent_shortcode', $obj);

                    $element = new syn_tabs_builder($id, $name, $default_value, $parent_shortcode);
                    $control .= $element->render();
                }
                break;
            default: $control .= '#error no control render function!!';
                break;
        }

        $html .= $this->apply_template($obj, $control, $template_name, '');

        return $html;
    }

    function apply_template($obj, $control, $template_name, $template = '') {

        if ($template_name === 'simple_template') {

            $label = $this->get_element('label', $obj);

            $template = '<div class="control">
                            <label>%label%</label>
                            %control%
                         </div>';

            $search = array('%label%', '%control%');
            $replace = array($label, $control);
            $template = str_replace($search, $replace, $template);
        } else if ($template_name === 'headered_template') {

            $header = $this->get_element('header', $obj);
            $description = $this->get_element('desc', $obj);

            $template = '<div class="control-field">
                            <h4>%header%</h4>
                            <div class="control">
                                %control%
                            </div>
                            <p class="description">
                                %description%
                            </p>
                        </div>';

            $search = array('%header%', '%control%', '%description%');
            $replace = array($header, $control, $description);
            $template = str_replace($search, $replace, $template);
        } else if ($template_name === 'left_align_template') {

            $header = $this->get_element('header', $obj);
            $description = $this->get_element('desc', $obj);

            $template = '<div class="control-field">
                            <div class="control">
                                %control%
                            </div>
                            <h4>%header%</h4>
                            <p class="description">
                                %description%
                            </p>
                        </div>';

            $search = array('%header%', '%control%', '%description%');
            $replace = array($header, $control, $description);
            $template = str_replace($search, $replace, $template);
        } else {

            $header = $this->get_element('header', $obj);
            $description = $this->get_element('desc', $obj);

            $template = '<div class="control-field">
                            <h4>%header%</h4>
                            <div class="control">
                                %control%
                            </div>
                            <p>
                                %description%
                            </p>
                        </div>';

            $search = array('%header%', '%control%', '%description%');
            $replace = array($header, $control, $description);
            $template = str_replace($search, $replace, $template);
        }

        return $template;
    }

    protected function get_element($key, $obj) {

        if (isset($obj[$key]) && !empty($obj[$key])) {
            return $obj[$key];
        }

        return "";
    }

}

?>
