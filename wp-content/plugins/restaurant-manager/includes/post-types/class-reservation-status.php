<?php

namespace syntaxthemes\restaurant;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class-reservation-status
 *
 * @author Ryan
 */
class reservation_status {

    private $_config;

    public function __construct() {

        global $syn_restaurant_config;
        $this->_config = $syn_restaurant_config;

        add_action('save_post', array($this, 'process_status'), 40);
    }

    public function process_status($post_id) {

        global $post_type;

        $session = new session();

        if ('POST' !== strtoupper($session->server_var('REQUEST_METHOD'))) {
            return false;
        }

        if ($post_type !== 'syn_rest_reservation') {
            return false;
        }

        $event_log = new event_log();

        $old_post_status = $session->post_var('original_post_status');

        $post = get_post($post_id);
        $post_status = $post->post_status;
        $email_address = get_post_meta($post_id, 'email_address', true);

        //check if the post status has changed
        if ($post_status !== $old_post_status) {
            $result = syntaxthemes_process_notification_email($post_status, $email_address, $post_id);
            $event_log->status_event($post_id, $old_post_status, $post_status, $result);
        }
    }

}

new reservation_status();
?>