<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-email-notification
 *
 * @author Ryan Haworth
 */
class email_notifications extends syn_email {

    /**
     * This is a general email processing function.
     * @param type $reply_name
     * @param type $reply_to
     * @param type $to
     * @param type $subject
     * @param type $replace
     * @param type $body
     * @return type
     */
    public function process_email($to, $subject, $message) {

        $reply_to_name = get_option($this->_config->plugin_prefix . 'reply_to_name');
        $reply_to_email = get_option($this->_config->plugin_prefix . 'reply_to_email');

        $this->add_from($reply_to_name, $reply_to_email);

        $this->_to = $to;
        $this->_subject = $subject;

        $this->_headers = array(
            'Content-Type: text/html'
        );

        $this->_message = stripslashes($message);

        return $this->send_mail();
    }

    /**
     * process the subject template tags
     * 
     * @param type $post_id
     * @param type $body
     * @return type
     */
    private function process_template_tags_subject($post_id, $subject) {

        $email_subject = $subject;
        $post = get_post($post_id);

        if (!empty($post)) {

            $first_name = get_post_meta($post_id, 'first_name', true);
            $last_name = get_post_meta($post_id, 'last_name', true);
            $guests_count = get_post_meta($post_id, 'guests_count', true);
            $reservation_date = get_post_meta($post_id, 'reservation_date', true);
            $reservation_time = get_post_meta($post_id, 'reservation_time', true);

            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            $formatted_reservation_date = date("{$date_format}", strtotime($reservation_date));
            $formatted_reservation_time = date("{$time_format}", strtotime($reservation_time));

            $search = array(
                '%first_name%',
                '%last_name%',
                '%guests_count%',
                '%reservation_date%',
                '%reservation_time%'
            );

            $replace = array(
                $first_name,
                $last_name,
                $guests_count,
                $formatted_reservation_date,
                $formatted_reservation_time
            );

            $email_subject = str_replace($search, $replace, $subject);
        }

        return $email_subject;
    }

    /**
     * Process the body template tags
     * 
     * @param type $post_id
     * @param type $body
     * @return type
     */
    private function process_template_tags_body($post_id, $body) {

        $email_body = $body;
        $post = get_post($post_id);

        if (!empty($post)) {

            $first_name = get_post_meta($post_id, 'first_name', true);
            $last_name = get_post_meta($post_id, 'last_name', true);
            $telephone = get_post_meta($post_id, 'phone_number', true);
            $email_address = get_post_meta($post_id, 'email_address', true);
            $guests_count = get_post_meta($post_id, 'guests_count', true);
            $reservation_date = get_post_meta($post_id, 'reservation_date', true);
            $reservation_time = get_post_meta($post_id, 'reservation_time', true);
            $message = $post->post_content;

            $restaurant_telephone = get_option($this->_config->plugin_prefix . 'restaurant_telephone', '');
            $site_name = get_bloginfo('name');
            $site_link = site_url();
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            $current_time = date("{$date_format} - {$time_format}");
            $formatted_reservation_date = date("{$date_format}", strtotime($reservation_date));
            $formatted_reservation_time = date("{$time_format}", strtotime($reservation_time));

            $search = array(
                '%first_name%',
                '%last_name%',
                '%telephone%',
                '%email_address%',
                '%guests_count%',
                '%reservation_date%',
                '%reservation_time%',
                '%message%',
                '%site_name%',
                '%site_link%',
                '%restaurant_telephone%',
                '%current_time%'
            );

            $replace = array(
                $first_name,
                $last_name,
                $telephone,
                $email_address,
                $guests_count,
                $formatted_reservation_date,
                $formatted_reservation_time,
                $message,
                $site_name,
                $site_link,
                $restaurant_telephone,
                $current_time
            );

            $email_body = str_replace($search, $replace, $body);
        }

        return $email_body;
    }

    /**
     * When a reservation booking request has been made this function creates
     * the admin email.
     * @param type $to
     * @param type $replace
     * @return type
     */
    public function admin_booking_notification($to, $post_id) {

        $reply_to_name = get_option($this->_config->plugin_prefix . 'reply_to_name');
        $reply_to_email = get_option($this->_config->plugin_prefix . 'reply_to_email');
        $subject = get_option($this->_config->plugin_prefix . 'admin_email_subject');
        $body = get_option($this->_config->plugin_prefix . 'admin_email');

        $subject_template = $this->process_template_tags_subject($post_id, $subject);
        $body_template = $this->process_template_tags_body($post_id, $body);

        $this->add_from($reply_to_name, $reply_to_email);

        $this->_headers[] = 'Content-Type: text/html';
        $this->_to = $to;
        $this->_subject = $subject_template;
        $this->_message = $body_template;

        return $this->send_mail();
    }

    /**
     * When a booking has been made a pending reservation email is sent to
     * the customer.
     * @param type $to
     * @param type $replace
     * @return type
     */
    public function customer_pending_booking_notification($to, $post_id) {

        $reply_to_name = get_option($this->_config->plugin_prefix . 'reply_to_name');
        $reply_to_email = get_option($this->_config->plugin_prefix . 'reply_to_email');
        $subject = get_option($this->_config->plugin_prefix . 'reservation_email_subject');
        $body = get_option($this->_config->plugin_prefix . 'reservation_email');

        $subject_template = $this->process_template_tags_subject($post_id, $subject);
        $body_template = $this->process_template_tags_body($post_id, $body);

        $this->add_from($reply_to_name, $reply_to_email);

        $this->_headers[] = 'Content-Type: text/html';
        $this->_to = $to;
        $this->_subject = $subject_template;
        $this->_message = $body_template;

        return $this->send_mail();
    }

    /**
     * When the restaurant confirms the booking this email is sent to the
     * customer.
     * @param type $to
     * @param type $replace
     * @return type
     */
    public function customer_confirmed_booking_notification($to, $post_id) {

        $reply_to_name = get_option($this->_config->plugin_prefix . 'reply_to_name');
        $reply_to_email = get_option($this->_config->plugin_prefix . 'reply_to_email');
        $subject = get_option($this->_config->plugin_prefix . 'reservation_confirmed_email_subject');
        $body = get_option($this->_config->plugin_prefix . 'reservation_confirmed_email');

        $subject_template = $this->process_template_tags_subject($post_id, $subject);
        $body_template = $this->process_template_tags_body($post_id, $body);

        $this->add_from($reply_to_name, $reply_to_email);

        $this->_headers[] = 'Content-Type: text/html';
        $this->_to = $to;
        $this->_subject = $subject_template;
        $this->_message = $body_template;

        return $this->send_mail();
    }

    /**
     * When the restaurant rejects a booking this email is sent to the
     * customer.
     * @param type $to
     * @param type $replace
     * @return type
     */
    public function customer_rejected_booking_notification($to, $post_id) {

        $reply_to_name = get_option($this->_config->plugin_prefix . 'reply_to_name');
        $reply_to_email = get_option($this->_config->plugin_prefix . 'reply_to_email');
        $subject = get_option($this->_config->plugin_prefix . 'reservation_rejected_email_subject');
        $body = get_option($this->_config->plugin_prefix . 'reservation_rejected_email');

        $subject_template = $this->process_template_tags_subject($post_id, $subject);
        $body_template = $this->process_template_tags_body($post_id, $body);

        $this->add_from($reply_to_name, $reply_to_email);

        $this->_headers[] = 'Content-Type: text/html';
        $this->_to = $to;
        $this->_subject = $subject_template;
        $this->_message = $body_template;

        return $this->send_mail();
    }

}
