<?php

namespace syntaxthemes\restaurant;

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Description of class-events-list-table
 *
 * @author Ryan
 */
class events_list_table extends \WP_List_Table {

    public function __construct($args = array()) {

        $args = array(
            'singular' => 'Event', //Singular label
            'plural' => 'Events', //plural label, also this well be one of the table css class
            'ajax' => false
        );

        parent::__construct($args);
    }

    function display_tablenav($which) {
        ?>
        <div class="tablenav <?php echo esc_attr($which); ?>">
            <div class="alignleft actions bulkactions">
                <?php $this->bulk_actions($which); ?>
            </div>
            <?php
            $this->extra_tablenav($which);
            $this->pagination($which);
            ?>
            <br class="clear" />
        </div>
        <?php
    }

    function get_columns() {

        return $columns = array(
            'author' => __('Author', 'syn_restaurant_plugin'),
            'content' => __('Content', 'syn_restaurant_plugin'),
            'event_type' => __('Event', 'syn_restaurant_plugin'),
            'created_date' => __('Created Date', 'syn_restaurant_plugin')
        );
    }

    public function get_sortable_columns() {

        return $sortable = array(
            'author' => array('author', false),
            'event_type' => array('event', false),
            'created_date' => array('created_date', false),
        );
    }

    function prepare_items($post_id) {

        $screen = get_current_screen();

        $data = array(
            'post_id' => $post_id
        );

        $events_data = new events_data();

        /* -- Ordering parameters -- */
//Parameters that are going to be used to order the result
        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'created_date';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'DESC';

        /* -- Pagination parameters -- */
//Number of elements in your table?
        $totalitems = $events_data->count($data); //return the total number of affected rows
//
//How many to display per page?
        $perpage = 5;

//Which page is this?
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';

//Page Number
        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }

        $page_offset = ($perpage * ($paged - 1));

//How many pages do we have in total?
        $totalpages = ceil($totalitems / $perpage);
//adjust the query to take pagination into account

        /* -- Register the pagination -- */
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));

        $this->_column_headers = array(
            $this->get_columns(), // columns
            array(), // hidden
            $this->get_sortable_columns(), // sortable
        );

//The pagination links are automatically built according to those parameters
        /* -- Fetch the items -- */
        $data = array(
            'post_id' => $post_id,
            'orderby' => $orderby,
            'order' => $order,
            'limit' => $perpage,
            'offset' => $page_offset
        );

        $this->items = $events_data->query($data);
    }

    function display_rows() {

        $even_odd = 'odd alt';
        $records = $this->items;

        list($columns, $hidden) = $this->get_column_info();

        if (!empty($records)) {
            foreach ($records as $rec) {

                $even_odd = ('odd alt' != $even_odd) ? 'odd alt' : 'even';

//Open the line
                echo '<tr id="record_' . $rec->id . '" class="' . $even_odd . ' ' . $rec->event_type . '">';

                foreach ($columns as $column_name => $column_display_name) {

                    //Style attributes for each col
                    $class = "class='$column_name column-$column_name'";

                    $style = "";

                    if (in_array($column_name, $hidden)) {
                        $style = ' style="display:none;"';
                    }

                    $attributes = $class . $style;

                    $date_format = get_option('date_format');
                    $time_format = get_option('time_format');

                    //edit link
                    $editlink = '/wp-admin/link.php?action=edit&link_id=' . (int) $rec->id;
                    $comment_date = date("{$date_format}", strtotime($rec->created_date));
                    $comment_time = date("{$time_format}", strtotime($rec->created_date));
                    $event_created = sprintf("%s at %s", $comment_date, $comment_time);
                    $meta_data = $event_type = '';

                    $eventmeta_data = new eventmeta_data();

                    switch ($rec->event_type) {
                        case 'email': {

                                $recipient_name = $eventmeta_data->get($rec->id, 'recipient_name');
                                $email_address = $eventmeta_data->get($rec->id, 'email_address');
                                $email_subject = $eventmeta_data->get($rec->id, 'email_subject');
                                $email_status = $eventmeta_data->get($rec->id, 'email_status');

                                $meta_data = '<span class="recipient">' . $recipient_name . '</span><span class="email-address">' . $email_address . '</span><span class="email-subject">' . $email_subject . '</span>';
                                $event_type = '<span class="event-icon rman-envelope"></span>';
                            }
                            break;
                        case 'sms': {

                                $recipient_name = $eventmeta_data->get($rec->id, 'recipient_name');
                                $mobile_number = $eventmeta_data->get($rec->id, 'mobile_number');
                                $sms_status = $eventmeta_data->get($rec->id, 'sms_status');

                                $meta_data = '<span class="recipient">' . $recipient_name . '</span><span class="mobile-number">' . $mobile_number . '</span>';
                                $event_type = '<span class="event-icon rman-mobile-phone"></span>';
                            }
                            break;
                        case 'reservation_status': {

                                $recipient_name = $eventmeta_data->get($rec->id, 'recipient_name');
                                $old_status = $eventmeta_data->get($rec->id, 'old_status');
                                $new_status = $eventmeta_data->get($rec->id, 'new_status');
                                $email_sent = $eventmeta_data->get($rec->id, 'email_sent');

                                $meta_data = '<span class="status-light status-' . $old_status . '"></span> ' . $old_status . ' &RightArrow; ' . '<span class="status-light status-' . $new_status . '"></span> ' . $new_status . '   |   ' . __('Email Notification: ') . (($email_sent) ? __('Sent') : __('Not Sent'));
                                $event_type = '<span class="status-light status-' . $new_status . '"></span>';
                            }
                            break;
                    }

//Display the cell
                    switch ($column_name) {
                        case "author": echo '<td ' . $attributes . '>' . stripslashes($rec->author) . '</td>';
                            break;
                        case "content": echo '<td ' . $attributes . '>' . $meta_data . '<p class="content">' . $rec->content . '</p></td>';
                            break;
                        case "event_type": echo '<td ' . $attributes . '>' . $event_type . '</td>';
                            break;
                        case "created_date": echo '<td ' . $attributes . '>' . $event_created . '</td>';
                            break;
                    }
                }

//Close the line
                echo '</tr>';
            }
        }
    }

}
?>