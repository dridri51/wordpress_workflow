<?php

namespace syntaxthemes\restaurant;

/**
 * Description of syntaxthemes_custom_meta_box
 *
 * @author Ryan
 */
if (!class_exists('custom_meta_box')) {

    class custom_meta_box {

        protected $_meta_box;

        /**
         * The Custom_Meta_Box constructor.
         * @param type $meta_box
         */
        public function __construct($meta_box) {

            $this->_meta_box = $meta_box;

            add_action('admin_menu', array(&$this, 'add_meta_box'));
            add_action('save_post', array(&$this, 'save_meta_box'));
        }

        /**
         * Add the metabox to the post type page.
         */
        public function add_meta_box() {

            $this->_meta_box['context'] = empty($this->_meta_box['context']) ? 'normal' : $this->_meta_box['context'];
            $this->_meta_box['priority'] = empty($this->_meta_box['priority']) ? 'high' : $this->_meta_box['priority'];
            $this->_meta_box['show_on'] = empty($this->_meta_box['show_on']) ? array('key' => false, 'value' => false) : $this->_meta_box['show_on'];

            foreach ($this->_meta_box['pages'] as $page) {
                if (apply_filters('syn_show_on', true, $this->_meta_box))
                    add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show_metabox'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
            }
        }

        /**
         * Show the metabox on the post type page.
         * @global type $post
         */
        public function show_metabox() {

            //get the post for this page
            global $post;
            $control_manager = new syn_control_manager();

            //use nonce for verification
            echo '<input type="hidden" name="wp_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

            //if (!empty($this->_meta_box['promotion'])) {
            //$promotion = $this->_meta_box['promotion'];
            //$this->_fields_generator->add_promotion_box($promotion);
            //}

            echo '<table class="form-table syn_metabox">';

            //loop the fields
            foreach ($this->_meta_box['fields'] as $field) {

                //get the post fields
                $meta = get_post_meta($post->ID, $field['name'], 'multicheck' != $field['type'] /* If multicheck this can be multiple values */);

                echo '<tr class="synth-type-' . sanitize_html_class($field['type']) . ' cmb_id_' . sanitize_html_class($field['id']) . '">';
                echo '<td>';

                echo $control_manager->render($field, $meta);

                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }

        /**
         * Save the metabox data for the specific post.  The post Id
         * is automatically passed into this function.
         * @param type $post_id
         * @return type
         */
        public function save_meta_box($post_id) {

            // verify nonce
            if (!isset($_POST['wp_meta_box_nonce']) || !wp_verify_nonce($_POST['wp_meta_box_nonce'], basename(__FILE__))) {
                return $post_id;
            }

            // check autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }

            // check permissions
            if ('page' == $_POST['post_type']) {
                if (!current_user_can('edit_page', $post_id)) {
                    return $post_id;
                }
            } elseif (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }

            // get the post types applied to the metabox group
            // and compare it to the post type of the content
            $post_type = get_post_type($post_id);
            $meta_type = $this->_meta_box['pages'];
            $type_comp = in_array($post_type, $meta_type) ? true : false;

            foreach ($this->_meta_box['fields'] as $field) {
                $name = $field['name'];

                if (!isset($field['multiple']))
                    $field['multiple'] = ( 'multicheck' == $field['type'] ) ? true : false;

                $old = get_post_meta($post_id, $name, !$field['multiple'] /* If multicheck this can be multiple values */);
                $new = isset($_POST[$field['name']]) ? $_POST[$field['name']] : null;

                if ($type_comp == true && in_array($field['type'], array('taxonomy_select', 'taxonomy_radio', 'taxonomy_multicheck'))) {
                    $new = wp_set_object_terms($post_id, $new, $field['taxonomy']);
                }

                if (($field['type'] == 'textarea') || ($field['type'] == 'textarea_small')) {
                    $new = htmlspecialchars($new);
                }

                if (($field['type'] == 'textarea_code')) {
                    $new = htmlspecialchars_decode($new);
                }

                if ($type_comp == true && $field['type'] == 'text_date_timestamp') {
                    $new = strtotime($new);
                }

                if ($type_comp == true && $field['type'] == 'text_datetime_timestamp') {
                    $string = $new['date'] . ' ' . $new['time'];
                    $new = strtotime($string);
                }

                $new = apply_filters('cmb_validate_' . $field['type'], $new, $post_id, $field);

                // validate meta value
                if (isset($field['validate_func'])) {
                    $ok = call_user_func(array('cmb_Meta_Box_Validate', $field['validate_func']), $new);
                    if ($ok === false) { // pass away when meta value is invalid
                        continue;
                    }
                } elseif ($field['multiple']) {
                    delete_post_meta($post_id, $name);
                    if (!empty($new)) {
                        foreach ($new as $add_new) {
                            add_post_meta($post_id, $name, $add_new, false);
                        }
                    }
                } elseif ('' !== $new && $new != $old) {
                    update_post_meta($post_id, $name, $new);
                } elseif ('' == $new) {
                    delete_post_meta($post_id, $name);
                }

                if ('file' == $field['type']) {
                    $name = $field['id'] . "_id";
                    $old = get_post_meta($post_id, $name, !$field['multiple'] /* If multicheck this can be multiple values */);
                    if (isset($field['save_id']) && $field['save_id']) {
                        $new = isset($_POST[$name]) ? $_POST[$name] : null;
                    } else {
                        $new = "";
                    }

                    if ($new && $new != $old) {
                        update_post_meta($post_id, $name, $new);
                    } elseif ('' == $new && $old) {
                        delete_post_meta($post_id, $name, $old);
                    }
                }
            }
        }

    }

}
?>
