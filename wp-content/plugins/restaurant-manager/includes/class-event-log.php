<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-event-log
 *
 * @author Ryan
 */
class event_log {

    private $_event_data;
    private $_eventmeta_data;
    private $_last_event_id;
    private $_last_eventmeta_id;

    public function __construct() {

        $this->_event_data = new events_data();
        $this->_eventmeta_data = new eventmeta_data();
    }

    public function email_event($post_id, $recipient_name, $email_address, $email_subject, $content, $email_status) {

        $user = get_user_by('email', get_option('admin_email'));
        $author_id = $user->ID;
        $author = ((!empty($user->first_name) && !empty($user->last_name)) ? "$user->first_name $user->last_name" : $user->user_nicename);
        $created_date = current_time('mysql');

        $data = array(
            'post_id' => $post_id,
            'author_id' => $author_id,
            'author' => $author,
            'content' => $content,
            'event_type' => 'email',
            'created_date' => $created_date
        );

        $this->_last_event_id = $this->_event_data->insert($data);
        $data['id'] = $this->_last_event_id;

        $eventmeta_data = array(
            'event_id' => $this->_last_event_id,
            'meta' => array(
                'recipient_name' => $recipient_name,
                'email_address' => $email_address,
                'email_subject' => $email_subject,
                'email_status' => $email_status
            )
        );

        $this->_last_eventmeta_id = $this->_eventmeta_data->insert($eventmeta_data);
        $data['meta'] = $eventmeta_data['meta'];

        return $data;
    }

    public function sms_event($post_id, $recipient_name, $mobile_number, $content, $sms_status) {

        $user = get_user_by('email', get_option('admin_email'));
        $author_id = $user->ID;
        $author = ((!empty($user->first_name) && !empty($user->last_name)) ? "$user->first_name $user->last_name" : $user->user_nicename);
        $created_date = current_time('mysql');

        $data = array(
            'post_id' => $post_id,
            'author_id' => $author_id,
            'author' => $author,
            'content' => $content,
            'event_type' => 'sms',
            'created_date' => $created_date
        );

        $this->_last_event_id = $this->_event_data->insert($data);
        $data['id'] = $this->_last_event_id;

        $eventmeta_data = array(
            'event_id' => $this->_last_event_id,
            'meta' => array(
                'recipient_name' => $recipient_name,
                'mobile_number' => $mobile_number,
                'sms_status' => $sms_status
            )
        );

        $this->_last_eventmeta_id = $this->_eventmeta_data->insert($eventmeta_data);
        $data['meta'] = $eventmeta_data['meta'];

        return $data;
    }

    public function status_event($post_id, $old_status, $new_status, $email_sent) {

        $user = get_user_by('email', get_option('admin_email'));
        $author_id = $user->ID;
        $author = ((!empty($user->first_name) && !empty($user->last_name)) ? "$user->first_name $user->last_name" : $user->user_nicename);
        $created_date = current_time('mysql');
        $content = __('The reservation status has been changed', 'syn_restaurant_plugin');

        $data = array(
            'post_id' => $post_id,
            'author_id' => $author_id,
            'author' => $author,
            'content' => $content,
            'event_type' => 'reservation_status',
            'created_date' => $created_date
        );

        $this->_last_event_id = $this->_event_data->insert($data);
        $data['id'] = $this->_last_event_id;

        $eventmeta_data = array(
            'event_id' => $this->_last_event_id,
            'meta' => array(
                'old_status' => $old_status,
                'new_status' => $new_status,
                'email_sent' => $email_sent
            )
        );

        $this->_last_eventmeta_id = $this->_eventmeta_data->insert($eventmeta_data);
        $data['meta'] = $eventmeta_data['meta'];

        return $data;
    }

}

?>