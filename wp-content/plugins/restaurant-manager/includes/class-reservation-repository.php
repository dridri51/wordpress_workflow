<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-reservation-repository
 *
 * @author Ryan
 */
class reservation {

    public $Id;
    public $FirstName;
    public $LastName;
    public $telephone;
    public $EmailAddress;
    public $GuestsCount;
    public $ReservationDate;
    public $ReservationTime;
    public $ArrivalTime;
    public $Notes;
    public $Status;
    public $RefTitle;

    public function __construct() {
        
        
    }

}

/**
 * The reservation repository to create, update and delete a reservation.
 */
class reservation_repository {

    public function __construct() {
        
    }

    /**
     * Create a reservation.
     * @param reservation $object
     */
    public function create(reservation $object) {

        global $wpdb;

        //install the default set of pages
        $post = array(
            'menu_order' => 0, //If new post is a page, it sets the order in which it should appear in the tabs.
            'comment_status' => 'closed', // 'closed' means no comments.
            'ping_status' => 'closed', // 'closed' means pingbacks or trackbacks turned off
            'pinged' => '', //?
            'post_author' => get_current_user_id(), //The user ID number of the author.            
            'post_content' => $object->Notes, //The full text of the post.
            'post_excerpt' => '', //For all your post excerpt needs.
            'post_parent' => '', //Sets the parent of the new post.
            'post_password' => '', //password for post?
            'post_status' => $object->Status, //Set the status of the new post.
            'post_title' => $object->RefTitle, //The title of your post.
            'post_name' => sanitize_title($object->RefTitle),
            'post_type' => 'syn_rest_reservation', //You may want to insert a regular post, page, link, a menu item or some custom post type
            'to_ping' => ''
        );

        $object->Id = wp_insert_post($post);

        $object->RefTitle = 'Reservation: ' . $object->FirstName . ' ' . $object->LastName;
        $where = array('ID' => $object->Id);
        $wpdb->update($wpdb->posts, array('post_title' => $object->RefTitle), $where);

        $object->Id = wp_insert_post($post);

        $object->ArrivalTime = date('Y-m-d H:i:s', strtotime("$object->ReservationDate, $object->ReservationTime"));

        update_post_meta($object->Id, 'first_name', $object->FirstName);
        update_post_meta($object->Id, 'last_name', $object->LastName);
        update_post_meta($object->Id, 'phone_number', $object->telephone);
        update_post_meta($object->Id, 'email_address', $object->EmailAddress);
        update_post_meta($object->Id, 'guests_count', $object->GuestsCount);
        update_post_meta($object->Id, 'reservation_date', $object->ReservationDate);
        update_post_meta($object->Id, 'reservation_time', $object->ReservationTime);
        update_post_meta($object->Id, 'arrival_time', $object->ArrivalTime);
        update_post_meta($object->Id, 'notes', $object->Notes);
    }

}

?>