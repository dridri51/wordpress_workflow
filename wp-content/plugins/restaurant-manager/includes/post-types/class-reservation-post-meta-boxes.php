<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-reservation-post-meta-boxes
 *
 * @author Ryan
 */
class reservation_post_meta_boxes {

    private $_config;

    /**
     * The post meta box constructor.
     */
    public function __construct() {

        global $syn_restaurant_config;
        $this->_config = $syn_restaurant_config;

        add_action('admin_menu', array($this, 'remove_meta_boxes'), 10);
        add_action('add_meta_boxes', array($this, 'rename_meta_boxes'), 20);
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'), 30);
        add_action('save_post', array($this, 'save_meta_boxes'), 30);

        add_action('edit_form_after_title', array($this, 'metabox_positions'));
    }

    /**
     * Reorganize the metaboxes.
     * @global type $post
     * @global type $wp_meta_boxes
     */
    public function metabox_positions() {

        global $post, $wp_meta_boxes;

        do_meta_boxes(get_current_screen(), 'advanced', $post);
        unset($wp_meta_boxes[get_post_type($post)]['advanced']);
    }

    /**
     * Remove any unwanted metaboxes.
     */
    public function remove_meta_boxes() {

        remove_meta_box('submitdiv', 'syn_rest_reservation', 'normal');
    }

    /**
     * Rename any metaboxes.
     */
    public function rename_meta_boxes() {
        
    }

    /**
     * Add enw metaboxes to the post type.
     */
    public function add_meta_boxes() {

        add_meta_box('restaurant_customer_notes_metabox', __('Customer Details', 'syn_restaurant_plugin'), array($this, 'customer_notes_meta_box'), 'syn_rest_reservation', 'advanced', 'high');
        add_meta_box('restaurant_reservation_status_metabox', __('Reservation Status', 'syn_restaurant_plugin'), array($this, 'reservation_status_meta_box'), 'syn_rest_reservation', 'side', 'default');
        add_meta_box('restaurant_events_metabox', __('Event Log', 'syn_restaurant_plugin'), array($this, 'events_meta_box'), 'syn_rest_reservation', 'advanced', 'low');
    }

    public function save_meta_boxes($post_id) {

        global $post_type, $wpdb;

        $session = new session();

        if ('POST' !== strtoupper($session->server_var('REQUEST_METHOD'))) {
            return false;
        }

        if ($post_type !== 'syn_rest_reservation') {
            return false;
        }

        $first_name = $session->post_var('first_name');
        $last_name = $session->post_var('last_name');
        $phone_number = $session->post_var('phone_number');
        $email_address = $session->post_var('email_address');
        $guests_count = $session->post_var('guests_count');
        $reservation_date = $session->post_var('reservation_date');
        $reservation_time = $session->post_var('reservation_time');

        update_post_meta($post_id, 'first_name', $first_name);
        update_post_meta($post_id, 'last_name', $last_name);
        update_post_meta($post_id, 'phone_number', $phone_number);
        update_post_meta($post_id, 'email_address', $email_address);
        update_post_meta($post_id, 'guests_count', $guests_count);
        update_post_meta($post_id, 'reservation_date', $reservation_date);
        update_post_meta($post_id, 'reservation_time', $reservation_time);

        $arrival_time = date('Y-m-d H:i:s', strtotime("$reservation_date, $reservation_time"));
        update_post_meta($post_id, 'arrival_time', $arrival_time);

        $title = 'Reservation: ' . $first_name . ' ' . $last_name;
        $where = array('ID' => $post_id);
        $wpdb->update($wpdb->posts, array('post_title' => $title), $where);
    }

    /**
     * The customer details metabox.
     * @global type $syn_restaurant_config
     * @return boolean
     */
    public function customer_notes_meta_box($post) {

        $first_name = get_post_meta($post->ID, 'first_name', true);
        $last_name = get_post_meta($post->ID, 'last_name', true);
        $phone_number = get_post_meta($post->ID, 'phone_number', true);
        $email_address = get_post_meta($post->ID, 'email_address', true);
        $guests_count = get_post_meta($post->ID, 'guests_count', true);
        $reservation_date = get_post_meta($post->ID, 'reservation_date', true);
        $reservation_time = get_post_meta($post->ID, 'reservation_time', true);
        ?>
        <div id="customer_details_metabox" class="metabox-columns">
            <div class="column metabox-content">
                <p>
                    <label for="first_name"><?php _e('First Name', 'syn_restaurant_plugin') ?></label>
                    <input id="first_name" name="first_name" type="text" value="<?php echo $first_name ?>" data-rule-required="true" /> 
                </p>
                <p>
                    <label for="last_name"><?php _e('Last Name', 'syn_restaurant_plugin') ?></label>
                    <input id="last_name" name="last_name" type="text" value="<?php echo $last_name ?>" data-rule-required="true" /> 
                </p>
                <p>
                    <label for="phone_number"><?php _e('Phone Number', 'syn_restaurant_plugin') ?></label>
                    <input id="phone_number" name="phone_number" type="text" value="<?php echo $phone_number ?>" data-rule-required="true" /> 
                </p>
                <p>
                    <label for="email_address"><?php _e('Email Address', 'syn_restaurant_plugin') ?></label>
                    <input id="email_address" name="email_address" type="text" value="<?php echo $email_address ?>" data-rule-required="true" data-rule-email="true" data-msg-email="Your email address which you have entered is in the incorrect format" /> 
                </p>
                <p>
                    <label for="guests_count"><?php _e('Number of Guests', 'syn_restaurant_plugin') ?></label>
                    <input id="guests_count" name="guests_count" type="text" value="<?php echo $guests_count ?>" data-rule-required="true" /> 
                </p>
                <p>
                    <label for="reservation_date"><?php _e('Reservation Date', 'syn_restaurant_plugin') ?></label>
                    <input id="reservation_date" class="syn-datepicker" name="reservation_date" type="text" value="<?php echo $reservation_date ?>" data-rule-required="true" readonly/> 
                </p>
                <p>
                    <label for="reservation_time"><?php _e('Reservation Time', 'syn_restaurant_plugin') ?></label>
                    <input id="reservation_time" class="syn-timepicker" name="reservation_time" type="text" value="<?php echo $reservation_time ?>" data-rule-required="true" readonly/> 
                </p>

            </div>
            <div class="column metabox-content">                
            </div>            
        </div>
        <div id="customer_notes_box" class="metabox-content">
            <p id="customer_notes_field">
                <label for="notes"><?php _e('Customer Notes', 'syn_restaurant_plugin') ?></label>
                <textarea id="notes" name="content" type="text"><?php echo $post->post_content ?></textarea>
            </p>
        </div>
        <?php
    }

    public function events_meta_box($post) {
        ?>
        <div id="restaurant_manager_events">                
            <?php
            $events_list_table = new events_list_table();
            $events_list_table->prepare_items($post->ID);
            $events_list_table->display();
            ?>                
        </div>
        <?php
    }

    /**
     * The reservation status box for pending, confirmed and rejected.
     * @global type $action
     * @param \syntaxthemes\restaurant\type $post
     * @param type $metabox
     */
    public function reservation_status_meta_box($post) {

        $post_status = $post->post_status;
        ?>
        <div class="metabox-content submitbox">
            <div id="reservation_status_field" class="field-control">
                <p>
                    <label for="reservation_status"><?php _e('Status', 'syn_restaurant_plugin') ?></label>
                    <select id="reservation_status" name="post_status">
                        <?php
                        $statuses = $this->reservation_statuses();

                        foreach ($statuses as $key => $status) {
                            ?>
                            <option value="<?php echo $key ?>"<?php selected($post_status, $key, true) ?>><?php echo $status ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <span class="status-light status-<?php echo $post_status ?>"></span>                    
                </p>                
            </div>  
            <div id="major-publishing-actions">
                <?php
                /**
                 * Fires at the beginning of the publishing actions section of the Publish meta box.
                 *
                 * @since 2.7.0
                 */
                do_action('post_submitbox_start');
                ?>
                <div id="delete-action">
                    <?php
                    if (current_user_can("delete_post", $post->ID)) {
                        if (!EMPTY_TRASH_DAYS)
                            $delete_text = __('Delete Permanently');
                        else
                            $delete_text = __('Move to Trash');
                        ?>
                        <a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php }
                    ?>
                </div>

                <div id="publishing-action">
                    <span class="spinner"></span>      
                    <?php
                    $post_type = $post->post_type;
                    $post_type_object = get_post_type_object($post_type);
                    $can_publish = current_user_can($post_type_object->cap->publish_posts);

                    if (!in_array($post->post_status, array('pending', 'confirmed', 'rejected')) || 0 == $post->ID) {
                        if ($can_publish) {
                            ?>
                            <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Publish') ?>" />
                            <?php submit_button(__('Save Reservation'), 'primary button-large', 'submit', false, array('accesskey' => 'p')); ?>
                            <?php
                        }
                    } else {
                        ?>
                        <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Update') ?>" />
                        <input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php esc_attr_e('Update Reservation') ?>" />
                    <?php } ?>

                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php
    }

    /**
     * The reservation statuses.
     * @return type
     */
    public function reservation_statuses() {

        $statuses = array(
            'draft' => _x('Draft Reservation', 'Reservation Status', 'syn_restaurant_plugin'),
            'pending' => _x('Pending Reservation', 'Reservation Status', 'syn_restaurant_plugin'),
            'confirmed' => _x('Confirm Reservation', 'Reservation Status', 'syn_restaurant_plugin'),
            'rejected' => _x('Reject Reservation', 'Reservation Status', 'syn_restaurant_plugin')
        );

        return $statuses;
    }

}

//Initialize the class.
new reservation_post_meta_boxes();
?>