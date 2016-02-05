<?php

/**
 * When the reservation form has been filled and submitted this function
 * will process the form.
 * @global type $syn_restaurant_config
 * @return boolean
 */
function syn_restaurant_manager_process_reservation_form() {

    global $syn_restaurant_config;

    $session = new \syntaxthemes\restaurant\session();

    if ('POST' !== strtoupper($session->server_var('REQUEST_METHOD'))) {
        return false;
    }
    if ('request_booking' !== $session->post_var('form_action')) {
        return false;
    }

    $nonce = $session->post_var('_wpnonce');

    if (!wp_verify_nonce($nonce, 'request_booking')) {
        return false;
    }

    $first_name = $session->post_var('first_name');
    $last_name = $session->post_var('last_name');
    $telephone = $session->post_var('telephone');
    $email_address = $session->post_var('email_address');
    $guests_count = $session->post_var('guests_count');
    $reservation_date = $session->post_var('reservation_date');
    $reservation_time = $session->post_var('reservation_time');
    $notes = $session->post_var('notes');

    $post_title = 'Reservation: ' . $first_name . ' ' . $last_name;
    $post_status = 'pending';

    //install the default set of pages
    $post = array(
        'menu_order' => 0, //If new post is a page, it sets the order in which it should appear in the tabs.
        'comment_status' => 'closed', // 'closed' means no comments.
        'ping_status' => 'closed', // 'closed' means pingbacks or trackbacks turned off
        'pinged' => '', //?
        'post_author' => get_current_user_id(), //The user ID number of the author.            
        'post_content' => $notes, //The full text of the post.
        'post_excerpt' => '', //For all your post excerpt needs.
        'post_parent' => '', //Sets the parent of the new post.
        'post_password' => '', //password for post?
        'post_status' => $post_status, //Set the status of the new post.
        'post_title' => $post_title, //The title of your post.
        'post_name' => sanitize_title($post_title),
        'post_type' => 'syn_rest_reservation', //You may want to insert a regular post, page, link, a menu item or some custom post type
        'to_ping' => ''
    );

    $post_id = wp_insert_post($post);

    //insert arrival time in MySql dateformat.
    $arrival_time = date('Y-m-d H:i:s', strtotime("$reservation_date, $reservation_time"));

    update_post_meta($post_id, 'first_name', $first_name);
    update_post_meta($post_id, 'last_name', $last_name);
    update_post_meta($post_id, 'phone_number', $telephone);
    update_post_meta($post_id, 'email_address', $email_address);
    update_post_meta($post_id, 'guests_count', $guests_count);
    update_post_meta($post_id, 'reservation_date', $reservation_date);
    update_post_meta($post_id, 'reservation_time', $reservation_time);
    update_post_meta($post_id, 'arrival_time', $arrival_time);
    update_post_meta($post_id, 'notes', $notes);

    $syn_email = new syntaxthemes\restaurant\email_notifications();

    $site_name = get_bloginfo('name');
    $site_link = site_url();
    $date_format = get_option('date_format');
    $time_format = get_option('time_format');
    $current_time = date("{$date_format} - {$time_format}");
    $reservation_date = date("{$date_format}", strtotime($reservation_date));
    $reservation_time = date("{$time_format}", strtotime($reservation_time));

    $replace = array(
        $site_name,
        $first_name,
        $last_name,
        $telephone,
        $email_address,
        $guests_count,
        $reservation_date,
        $reservation_time,
        $current_time,
        $site_link
    );

    $customer_sent = syntaxthemes_process_notification_email($post_status, $email_address, $post_id);

    $details = array(
        $first_name,
        $last_name,
        $email_address,
    );

    apply_filters('syn_restaurant_reservation_form_processing_end', $details);

    return $customer_sent;
}

add_action('syn_restaurant_manager_process_form', 'syn_restaurant_manager_process_reservation_form');

/**
 * The general settings form processing.
 * @global type $syn_restaurant_config
 * @return type
 */
function syn_restaurant_manager_process_general_settings_form() {

    global $syn_restaurant_config;

    $session = new \syntaxthemes\restaurant\session();

    if ('POST' !== strtoupper($session->server_var('REQUEST_METHOD'))) {
        return;
    }
    if ('save_general_settings' !== $session->post_var('form_action')) {
        return;
    }

    $nonce = $session->post_var('_wpnonce');

    if (!wp_verify_nonce($nonce, 'save_general_settings')) {
        return;
    }

    $group_size = $session->post_var('group_size');
    $reservation_success_message = $session->post_var('reservation_success_message');
    $restaurant_telephone = $session->post_var('restaurant_telephone');
    $currency_symbol = $session->post_var('currency_symbol');
    $country_code = $session->post_var('country_code');

    update_option($syn_restaurant_config->plugin_prefix . 'group_size', $group_size);
    update_option($syn_restaurant_config->plugin_prefix . 'reservation_success_message', $reservation_success_message);
    update_option($syn_restaurant_config->plugin_prefix . 'restaurant_telephone', $restaurant_telephone);
    update_option($syn_restaurant_config->plugin_prefix . 'currency_symbol', $currency_symbol);
    update_option($syn_restaurant_config->plugin_prefix . 'country_code', $country_code);

    echo '<div class="updated"><p>' . __('Your general settings have been successfully updated.', 'syn_restaurant_plugin') . '</p></div>';
}

add_action('syn_restaurant_manager_process_form', 'syn_restaurant_manager_process_general_settings_form');

/**
 * The reservation schedule form variable processing.
 * @global type $syn_restaurant_config
 * @return type
 */
function syn_restaurant_manager_process_reservation_schedule_settings_form() {

    global $syn_restaurant_config;

    $session = new \syntaxthemes\restaurant\session();

    if ('POST' !== strtoupper($session->server_var('REQUEST_METHOD'))) {
        return;
    }
    if ('save_schedule_settings' !== $session->post_var('form_action')) {
        return;
    }

    $nonce = $session->post_var('_wpnonce');

    if (!wp_verify_nonce($nonce, 'save_schedule_settings')) {
        return;
    }

    $scheduler = $session->post_var('scheduler', null, true);
    update_option($syn_restaurant_config->plugin_prefix . 'scheduler', $scheduler);

    echo '<div class="updated"><p>' . __('Your reservation schedule settings have been successfully updated.', 'syn_restaurant_plugin') . '</p></div>';
}

add_action('syn_restaurant_manager_process_form', 'syn_restaurant_manager_process_reservation_schedule_settings_form');

/**
 * The notifications form variable processing.
 * @global type $syn_restaurant_config
 * @return type
 */
function syn_restaurant_manager_process_notifications_settings_form() {

    global $syn_restaurant_config;

    $session = new \syntaxthemes\restaurant\session();

    if ('POST' !== strtoupper($session->server_var('REQUEST_METHOD'))) {
        return;
    }
    if ('save_notification_settings' !== $session->post_var('form_action')) {
        return;
    }

    $nonce = $session->post_var('_wpnonce');

    if (!wp_verify_nonce($nonce, 'save_notification_settings')) {
        return;
    }

    $reply_to_name = $session->post_var('reply_to_name');
    $reply_to_email = $session->post_var('reply_to_email');
    $admin_email_subject = $session->post_var('admin_email_subject');
    $admin_email = wpautop($_POST['admin_email']);
    $reservation_email_subject = $session->post_var('reservation_email_subject');
    $reservation_email = wpautop($_POST['reservation_email']);
    $reservation_confirmed_email_subject = $session->post_var('reservation_confirmed_email_subject');
    $reservation_confirmed_email = wpautop($_POST['reservation_confirmed_email']);
    $reservation_rejected_email_subject = $session->post_var('reservation_rejected_email_subject');
    $reservation_rejected_email = wpautop($_POST['reservation_rejected_email']);


    update_option($syn_restaurant_config->plugin_prefix . 'reply_to_name', $reply_to_name);
    update_option($syn_restaurant_config->plugin_prefix . 'reply_to_email', $reply_to_email);
    update_option($syn_restaurant_config->plugin_prefix . 'admin_email_subject', $admin_email_subject);
    update_option($syn_restaurant_config->plugin_prefix . 'admin_email', $admin_email);
    update_option($syn_restaurant_config->plugin_prefix . 'reservation_email_subject', $reservation_email_subject);
    update_option($syn_restaurant_config->plugin_prefix . 'reservation_email', $reservation_email);
    update_option($syn_restaurant_config->plugin_prefix . 'reservation_confirmed_email_subject', $reservation_confirmed_email_subject);
    update_option($syn_restaurant_config->plugin_prefix . 'reservation_confirmed_email', $reservation_confirmed_email);
    update_option($syn_restaurant_config->plugin_prefix . 'reservation_rejected_email_subject', $reservation_rejected_email_subject);
    update_option($syn_restaurant_config->plugin_prefix . 'reservation_rejected_email', $reservation_rejected_email);

    echo '<div class="updated"><p>' . __('Your notifications settings have been successfully updated.', 'syn_restaurant_plugin') . '</p></div>';
}

add_action('syn_restaurant_manager_process_form', 'syn_restaurant_manager_process_notifications_settings_form');
