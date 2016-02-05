<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-reservation-post-type
 *
 * @author Ryan
 */
class reservation_post_type {

    private $_post_type;

    /**
     * The restaurant reservation post type.
     */
    public function __construct() {

        $this->_post_type = 'syn_rest_reservation';

        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_post_status'));
        add_action('save_post', array($this, 'save_post'), 20);
        add_filter('post_updated_messages', array($this, 'post_updated_messages'));
        add_filter('manage_posts_columns', array($this, 'column_headers'), 10);
        add_action('manage_posts_custom_column', array($this, 'column_content'), 10, 2);
        add_filter("manage_edit-syn_rest_reservation_sortable_columns", array($this, 'column_sort'));
        add_filter('request', array($this, 'column_orderby'));
    }

    /**
     * Register the post type.
     */
    public function register_post_type() {

        register_post_type('syn_rest_reservation', array(
            'labels' => array(
                'name' => __('Reservations', 'syn_restaurant_plugin'),
                'singular_name' => __('Reservation', 'syn_restaurant_plugin'),
                'all_items' => __('Reservations', 'syn_restaurant_plugin'),
                'add_new' => __('Add New', 'syn_restaurant_plugin'),
                'add_new_item' => __('Add New Reservation', 'syn_restaurant_plugin'),
                'edit' => __('Edit', 'syn_restaurant_plugin'),
                'edit_item' => __('Edit Reservation', 'syn_restaurant_plugin'),
                'new_item' => __('New Reservation', 'syn_restaurant_plugin'),
                'view' => __('View Reservation', 'syn_restaurant_plugin'),
                'view_item' => __('View Reservations', 'syn_restaurant_plugin'),
                'search_items' => __('Search Reservations', 'syn_restaurant_plugin'),
                'not_found' => __('No booking found', 'syn_restaurant_plugin'),
                'not_found_in_trash' => __('No booking found in trash', 'syn_restaurant_plugin'),
                'parent' => __('Parent Reservation', 'syn_restaurant_plugin'),
                'menu_name' => 'Restaurant'
            ),
            'description' => __('This is where reservation items are stored', 'syn_restaurant_plugin'),
            'public' => false,
            'map_meta_cap' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'hierarchical' => false,
            'rewrite' => false,
            'query_var' => false,
            'supports' => array('title'),
            'has_archive' => false,
            'menu_position' => 201,
            'show_ui' => true,
            'show_in_menu' => false,
            'capability_type' => array('reservation', 'reservations')
        ));
    }

    /**
     * Register the post status for this post type.
     */
    public function register_post_status() {

        register_post_status('pending', array(
            'label' => _x('Pending', 'Reservation Status', 'syn_restaurant_plugin'),
            'label_count' => _n_noop('Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>', 'syn_restaurant_plugin'),
            'public' => true,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'exclude_from_search' => false
        ));

        register_post_status('confirmed', array(
            'label' => _x('Confirm', 'Reservation Status', 'syn_restaurant_plugin'),
            'label_count' => _n_noop('Confirmed <span class="count">(%s)</span>', 'Confirmed <span class="count">(%s)</span>', 'syn_restaurant_plugin'),
            'public' => true,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'exclude_from_search' => false
        ));

        register_post_status('rejected', array(
            'label' => _x('Reject', 'Reservation Status', 'syn_restaurant_plugin'),
            'label_count' => _n_noop('Rejected <span class="count">(%s)</span>', 'Rejected <span class="count">(%s)</span>', 'syn_restaurant_plugin'),
            'public' => true,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'exclude_from_search' => false
        ));
    }

    /**
     * Save the post
     * 
     * @global type $syn_restaurant_config
     * @global type $post
     * @global type $wpdb
     * @return type
     */
    public function save_post($post_id) {
        
    }

    /**
     * These are the messages which are displayed when the post is saved.
     * 
     * @global \syntaxthemes\restaurant\type $post
     * @global type $post_ID
     * @param type $messages
     * @return string
     */
    public function post_updated_messages($messages) {

        global $post, $post_ID;

        switch ($post->post_status) {
            case 'pending': $reservation_status = __('Status Pending.', 'syn_restaurant_plugin');
                break;
            case 'confirmed': $reservation_status = __('Status Confirmed.', 'syn_restaurant_plugin');
                break;
            case 'rejected': $reservation_status = __('Status Rejected.', 'syn_restaurant_plugin');
                break;
            case 'completed': $reservation_status = __('Status Completed.', 'syn_restaurant_plugin');
                break;
            default: $reservation_status = __('Status Unknown.', 'syn_restaurant_plugin');
                break;
        }

        $messages[$this->_post_type] = array(
            0 => '',
            1 => sprintf(__('Reservation successfully updated. %s', 'syn_restaurant_plugin'), $reservation_status),
            2 => __('Custom field updated.', 'syn_restaurant_plugin'),
            3 => __('Custom field deleted.', 'syn_restaurant_plugin'),
            4 => sprintf(__('Reservation successfully updated. %s', 'syn_restaurant_plugin'), $reservation_status),
            5 => isset($_GET['revision']) ? sprintf(__('Reservation restored to revision from %s', 'syn_restaurant_plugin'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
            6 => sprintf(__('Reservation successfully created. %s', 'syn_restaurant_plugin'), $reservation_status),
            7 => __('Reservation saved.', 'syn_restaurant_plugin'),
            8 => __('Reservation submitted.', 'syn_restaurant_plugin'),
            9 => __('Reservation scheduled.', 'syn_restaurant_plugin'),
            10 => __('Reservation draft updated. Email notification has not been sent for this status.', 'syn_restaurant_plugin'),
        );

        return $messages;
    }

    /**
     * Create the column headers for the post type.
     * 
     * @global type $post_type
     * @param type $defaults
     * @return type
     */
    public function column_headers($defaults) {

        global $post_type;

        if ($post_type === 'syn_rest_reservation') {

            unset(
                    $defaults['title'], $defaults['date']
            );
            $column_array = array(
                'reservation-name' => __('Name', 'syn_restaurant_plugin'),
                'reservation-guests' => __('Guests', 'syn_restaurant_plugin'),
                'reservation-phone' => __('Phone', 'syn_restaurant_plugin'),
                'reservation-email' => __('Email', 'syn_restaurant_plugin'),
                'reservation-arrival' => __('Arrival', 'syn_restaurant_plugin'),
                'reservation-status' => __('Status', 'syn_restaurant_plugin'),
                'reservation-actions' => __('Actions', 'syn_restaurant_plugin'),
            );

            $offset = 2;

            $defaults = array_slice($defaults, 0, $offset, true) +
                    $column_array +
                    array_slice($defaults, $offset, NULL, true);
        }

        return $defaults;
    }

    /**
     * Create the column content for the post type.
     * 
     * @global \syntaxthemes\restaurant\type $post
     * @global \syntaxthemes\restaurant\type $post_type
     * @param type $column
     * @param type $post_id
     */
    public function column_content($column, $post_id) {

        global $post, $post_type, $syn_restaurant_config;

        if ($post_type === 'syn_rest_reservation') {
            if ($column == 'reservation-name') {

                $first_name = get_post_meta($post_id, 'first_name', true);
                $last_name = get_post_meta($post_id, 'last_name', true);

                echo "{$first_name} {$last_name}";
            }
            if ($column == 'reservation-guests') {

                $guests = 0;
                $guests_count = get_post_meta($post_id, 'guests_count', true);

                if ($guests_count == 1) {
                    $guests = __('1 Guest', 'syn_restaurant_plugin');
                } elseif ($guests_count > 1) {
                    $guests = $guests_count . ' ' . __('Guests', 'syn_restaurant_plugin');
                }

                echo "{$guests}";
            }
            if ($column == 'reservation-phone') {

                $phone_number = get_post_meta($post_id, 'phone_number', true);

                echo "{$phone_number}";
            }
            if ($column == 'reservation-email') {

                $readmore = '&nbsp;&nbsp;<a href="' . get_edit_post_link($post_id) . '">' . __('...read more', 'syn_restaurant_plugin') . '</a>';
                $email_address = get_post_meta($post_id, 'email_address', true);
                $content = wp_trim_words(wpautop($post->post_content), 20, $readmore);

                echo "<span class=\"email-address\"><i class=\"email-icon rman-envelope\"></i>{$email_address}</span><p class=\"reservation-notes\">{$content}</p>";
            }
            if ($column == 'reservation-arrival') {

                $date_format = get_option('date_format');
                $time_format = get_option('time_format');
                $arrival_time = get_post_meta($post_id, 'arrival_time', true);

                echo date("{$date_format} - {$time_format}", strtotime($arrival_time));
            }
            if ($column == 'reservation-status') {

                $status = $post->post_status;

                switch ($status) {
                    case 'draft' : $status_text = __('Draft', 'syn_restaurant_plugin');
                        break;
                    case 'pending' : $status_text = __('Pending', 'syn_restaurant_plugin');
                        break;
                    case 'confirmed' : $status_text = __('Confirmed', 'syn_restaurant_plugin');
                        break;
                    case 'rejected' : $status_text = __('Rejected', 'syn_restaurant_plugin');
                        break;
                    case 'complete-reservation' : $status_text = __('Completed', 'syn_restaurant_plugin');
                        break;
                    default : $status_text = __('Unknown', 'syn_restaurant_plugin');
                        break;
                }

                echo "<span class=\"status-{$status}\">{$status_text}</span>";
            }
            if ($column == 'reservation-actions') {

                $edit_link = '<a class="edit-reservation-button button secondary-button" title="' . __('Edit Reservation', 'syn_restaurant_plugin') . '" href="' . get_edit_post_link($post_id) . '"><span class="rman-pencil"></span></a>';
                $delete_link = '<a class="delete-reservation-button button button-secondary" title="' . __('Delete Reservation', 'syn_restaurant_plugin') . '" href="' . get_delete_post_link($post_id) . '" onclick="return confirm(\'' . __('Are you sure you would like to delete this reservation?', 'syn_restaurant_plugin') . '\')"><span class="rman-trash-o"></span></a>';

                if (current_user_can('edit_reservations') && current_user_can('delete_reservations')) {
                    echo $edit_link . ' ' . $delete_link;
                } else if (current_user_can('edit_reservations')) {
                    echo $edit_link;
                }
            }
        }
    }

    /**
     * Enable column sorting.
     * 
     * @global \syntaxthemes\restaurant\type $post
     * @global \syntaxthemes\restaurant\type $post_type
     * @param type $columns
     * @return string
     */
    public function column_sort($columns) {

        global $post, $post_type;

        if ($post_type === 'syn_rest_reservation') {
            $columns['reservation-guests'] = 'guests';
            $columns['reservation-arrival'] = 'arrival';
        }

        return $columns;
    }

    /**
     * Create the orderby query for the column.
     * 
     * @param type $vars
     * @return type
     */
    public function column_orderby($vars) {

        if (isset($vars['orderby']) && 'guests' == $vars['orderby']) {

            $vars = array_merge($vars, array(
                'meta_key' => 'guests_count',
                'orderby' => 'meta_value'
            ));
        }
        if (isset($vars['orderby']) && 'arrival' == $vars['orderby']) {

            $vars = array_merge($vars, array(
                'meta_key' => 'arrival_time',
                //'orderby' => 'meta_value_num', // does not work
                'orderby' => 'meta_value'
                    //'order' => 'asc' // don't use this; blocks toggle UI
            ));
        }

        return $vars;
    }

}

new reservation_post_type();
?>