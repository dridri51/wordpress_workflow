<?php

namespace syntaxthemes\restaurant;

/**
 * Description of dropcap
 *
 * @author Ryan Haworth
 */
// Do not load this file directly
if (!defined('ABSPATH')) {
    die('-1');
}

/**
 * The reservation shortcode class.
 */
class syn_restaurant_reservation extends syn_shortcode_script_loader {

    /**
     * The shortcode registration button.  This will appear in the shortcodes
     * menu.
     */
    public function register_button() {

        $this->shortcode = array();
        $this->shortcode['name'] = $this->_config->framework_short_prefix . 'restaurant_reservation';
        $this->shortcode['heading'] = __('Restaurant Reservation', 'syn_restaurant_plugin');
        $this->shortcode['modal_editor'] = false;
        $this->shortcode['editor_insert'] = $this->editor_insert();
    }

    /**
     * The form elements for the modal window.
     * @param type $shortcode
     */
    public function form_elements($shortcode = null) {
        
    }

    function editor_insert() {

        $element = '[' . $this->shortcode['name'] . ']';

        return $element;
    }

    /**
     * This functions builds the html for the shortcode.
     * @param type $atts
     * @param type $content
     * @return type
     */
    public function handle_shortcode($atts, $content = null) {

        $submitted = syn_restaurant_manager_process_reservation_form();

        $atts = shortcode_atts(array(
            'glyph' => 'character'
                ), $atts);

        extract($atts);

        $session = new session();
        $group_size = get_option($this->_config->plugin_prefix . 'group_size', 0);

        ob_start();
        ?>
        <div id="syn_restaurant_manager">
            <?php
            if (!$submitted) {
                ?>

                <form id="syn_restaurant_manager_reservation_form" class="synth-form" action="<?php echo $session->current_page_url(false) ?>" method="POST">
                    <div class="form-field first-name-field">
                        <div class="form-label">
                            <label for="first_name">First Name</label>  
                        </div>
                        <div class="form-control">
                            <span class="inline-error"></span>
                            <input id="first_name" type="text" name="first_name" data-rule-required="true" data-msg-required="<?php _e('Please enter your first name.', 'syn_restaurant_plugin') ?>" />
                        </div>
                    </div>
                    <div class="form-field last-name-field">
                        <div class="form-label">
                            <label>Last Name</label>
                        </div>
                        <div class="form-control">
                            <span class="inline-error"></span>
                            <input id="last_name" type="text" name="last_name" data-rule-required="true" data-msg-required="<?php _e('Please enter your last name.', 'syn_restaurant_plugin') ?>" />
                        </div>
                    </div>
                    <div class="form-field telephone-field">
                        <div class="form-label">
                            <label>Telephone Number</label>
                        </div>
                        <div class="form-control">
                            <span class="inline-error"></span>
                            <input id="telephone" class="required" type="text" name="telephone" data-rule-required="true" data-msg-required="<?php _e('Please enter your contact telephone number.', 'syn_restaurant_plugin') ?>" data-rule-digits="true" data-msg-digits="<?php _e('Your phone number must be digits only without spaces.', 'syn_restaurant_plugin') ?>"/>
                        </div>
                    </div>
                    <div class="form-field email-field">
                        <div class="form-label">
                            <label>Email Address</label>
                        </div>
                        <div class="form-control">
                            <span class="inline-error"></span>
                            <input id="email_address" type="text" name="email_address" data-rule-required="true" data-msg-required="<?php _e('Please enter your email address.', 'syn_restaurant_plugin') ?>"  data-rule-email="true" data-msg-email="<?php _e('Your email address which you have entered is in the incorrect format', 'syn_restaurant_plugin') ?>"/>
                        </div>
                    </div>
                    <div class="form-field guests-count-field">
                        <div class="form-label">
                            <label>Number of Guests</label>
                        </div>
                        <div class="form-control">
                            <span class="inline-error"></span>
                            <input id="guests_count" type="text" name="guests_count" data-rule-required="true" data-msg-required="<?php _e('Please tell us how many people will be in your party.', 'syn_restaurant_plugin') ?>" data-rule-max="<?php echo $group_size ?>" data-msg-max="<?php printf(__('Your party exceeds the maximum group size of %d.', 'syn_restaurant_plugin'), $group_size) ?>" />
                        </div>
                    </div>
                    <div class="form-field reservation-date-field">
                        <div class="form-label">
                            <label>Date</label>
                        </div>
                        <div class="form-control">
                            <span class="inline-error"></span>
                            <input id="reservation_date" class="syn-datepicker" type="text" name="reservation_date" data-rule-required="true" data-msg-required="<?php _e('Please choose your reservation date.', 'syn_restaurant_plugin') ?>" readonly/>
                        </div>
                    </div>
                    <div class="form-field reservation-time-field">
                        <div class="form-label">
                            <label>Time</label>
                        </div>
                        <div class="form-control">
                            <span class="inline-error"></span>
                            <input id="reservation_time" class="syn-timepicker" type="text" name="reservation_time" data-rule-required="true" data-msg-required="<?php _e('Please choose your reservation time.', 'syn_restaurant_plugin') ?>" readonly/>
                        </div>
                    </div>
                    <div class="form-field notes-field">
                        <div class="form-label">
                            <label>Notes</label>
                        </div>
                        <div class="form-control">
                            <textarea id="notes" name="notes"></textarea>
                        </div>
                    </div>
                    <?php do_action('syn_restaurant_reservation_form_after') ?>
                    <div>
                        <?php wp_nonce_field('request_booking'); ?>
                        <input type="hidden" name="form_action" value="request_booking">
                        <input type="submit" name="form_submit" value="<?php _e('Request Booking', 'syn_restaurant_plugin') ?>">
                    </div>
                </form>
                <?php
            } else {
                $reservation_success_message = get_option($this->_config->plugin_prefix . 'reservation_success_message');
                ?>
                <div class="success-message">
                    <p>
                        <?php echo $reservation_success_message ?>
                    </p>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * If the shortcode requires any scripts or styles they can be
     * enqueued here.
     */
    public function add_script() {

        if ($this->do_add_script) {

            wp_enqueue_script('restaurant-manager-script', $this->_config->plugin_url . '/assets/js/synth-restaurant.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-pickdate-picker-script', 'jquery-pickdate-date-script', 'jquery-pickdate-time-script'), false, true);
            wp_enqueue_script('jquery-validation-script', $this->_config->plugin_url . '/framework/js/validation/jquery.validate.min.js', array('jquery'), '1.11.1', true);
            wp_enqueue_script('synth-validation-script', $this->_config->plugin_url . '/framework/js/synth-validation.js', array('jquery'), false, true);

            $scheduler = get_option($this->_config->plugin_prefix . 'scheduler');

            wp_localize_script('restaurant-manager-script', 'syn_restaurant_manager', array(
                'scheduler' => json_encode($scheduler)
            ));

            $this->do_add_script = false;
        }
    }

}
?>