<?php

function syn_restaurant_manager_the_content_filter($content) {

    // array of custom shortcodes requiring the fix 
    $block = join("|", array('syn_restaurant_reservation', 'syn_restaurant_menu'));

    // opening tag
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content);

    // closing tag
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep);

    return $rep;
}

add_filter("the_content", "syn_restaurant_manager_the_content_filter");

/**
 * This is the default template for the admin notification email.
 * @return string
 */
function syn_restaurant_manager_default_admin_email() {

    $template = '<p>
                    Dear Administrator
                </p>
                <p>
                    A new booking request has been made at %site_name%
                </p>
                <p>
                    <strong>Customer Details:</strong>
                    %first_name% %last_name%
                    For: %guests_count% people
                </p>
                <p>    
                    <strong>Contact Details:</strong>
                    Telephone: %telephone%
                    Email: %email_address%
                </p>
                <p>
                    <strong>Reservation Details:</strong>
                    Date: %reservation_date% at %reservation_time%
                </p>
                <p>
                    <strong>Customer Request:</strong>
                    %message%
                </p>
                <p>
                    Thank you.
                </p>
                <p>
                    <em>Reservation created at %current_time% sent by %site_link%<em>
                </p>';

    return $template;
}

/**
 * This is the default reservation email for a new booking.
 * @return string
 */
function syn_restaurant_manager_default_reservation_email() {

    $template = '<p>
                    Dear %first_name% %last_name%
                </p>
                <p>
                    You have requested a table reservation with %site_name%.  Please await your confirmation email from us to confirm that your table has been reserved.
                </p>
                <p>
                    <strong>Customer Details:</strong>
                    %first_name% %last_name%
                    For: %guests_count% people
                </p>
                <p>
                    <strong>Contact Details:</strong>
                    Telephone: %telephone%
                    Email: %email_address%
                </p>
                <p>
                    <strong>Reservation Details:</strong>
                    Date: %reservation_date% at %reservation_time%
                </p>
                <p>
                    <strong>Your Request:</strong>
                    %message%
                </p>
                <p>
                    Thank you.
                </p>
                <p>
                    <em>Reservation created at %current_time% sent by %site_link%<em>
                </p>';

    return $template;
}

/**
 * The default email when a reservation has been confirmed.
 * @return string
 */
function syn_restaurant_manager_default_reservation_confirmed_email() {

    $template = '<p>
                    Dear %first_name% %last_name%
                </p>
                <p>
                    This is your confirmation email for %site_name%.  We look forward to seeing you soon.  Please contact us if you need to on %restaurant_telephone%
                </p>
                <p>
                    <strong>Customer Details:</strong>
                    %first_name% %last_name%
                    For: %guests_count% people
                </p>
                <p>
                    <strong>Contact Details:</strong>
                    Telephone: %telephone%
                    Email: %email_address%
                </p>
                <p>
                    <strong>Reservation Details:</strong>
                    Date: %reservation_date% at %reservation_time%
                </p>
                <p>
                    <strong>Your Request:</strong>
                    %message%
                </p>
                <p>
                 Thank you.
                </p>
                <p>
                   Reservation created at %current_time% sent by %site_link%
                </p>';

    return $template;
}

/**
 * The default email when a reservation has been rejected.
 * @return string
 */
function syn_restaurant_manager_default_reservation_rejected_email() {

    $template = '<p>
                    Dear %first_name% %last_name%
                </p>
                <p>
                    We unfortunately cannot make your reservation at this time.  We are very sorry about this please call us on %restaurant_telephone% to make a new booking or another arrangement.
                </p>
                <p>
                    <strong>Customer Details:</strong>
                    %first_name% %last_name%
                    For: %guests_count% people
                </p>
                <p>
                    <strong>Contact Details:</strong>
                    Telephone: %telephone%
                    Email: %email_address%
                </p>
                <p>
                    <strong>Reservation Details:</strong>
                    Date: %reservation_date% at %reservation_time%
                </p>
                <p>
                    <strong>Your Request:</strong>
                    %message%
                </p>
                <p>
                    Thank you.
                </p>
                <p>
                    Reservation created at %current_time% sent by %site_link%
                </p>';

    return $template;
}

/**
 * This function will get the schedular objects and template.  The schedular
 * object will map to the template and produce the scheduler view.
 * @global type $syn_restaurant_config
 */
function syn_restaurant_manager_get_scheduler() {

    global $syn_restaurant_config;

    $scheduler = get_option($syn_restaurant_config->plugin_prefix . 'scheduler', null);

    if (!empty($scheduler)) {

        foreach ($scheduler as $key => $schedule) {

            $monday = (isset($schedule['weekday']['monday'])) ? $schedule['weekday']['monday'] : false;
            $tuesday = (isset($schedule['weekday']['tuesday'])) ? $schedule['weekday']['tuesday'] : false;
            $wednesday = (isset($schedule['weekday']['wednesday'])) ? $schedule['weekday']['wednesday'] : false;
            $thursday = (isset($schedule['weekday']['thursday'])) ? $schedule['weekday']['thursday'] : false;
            $friday = (isset($schedule['weekday']['friday'])) ? $schedule['weekday']['friday'] : false;
            $saturday = (isset($schedule['weekday']['saturday'])) ? $schedule['weekday']['saturday'] : false;
            $sunday = (isset($schedule['weekday']['sunday'])) ? $schedule['weekday']['sunday'] : false;

            $starttime = (isset($schedule['timeslot']['starttime'])) ? $schedule['timeslot']['starttime'] : false;
            $endtime = (isset($schedule['timeslot']['endtime'])) ? $schedule['timeslot']['endtime'] : false;

            $monday_text = (isset($schedule['weekday']['monday'])) ? __('Mon', 'syn_restaurant_plugin') : null;
            $tuesday_text = (isset($schedule['weekday']['tuesday'])) ? __('Tue', 'syn_restaurant_plugin') : null;
            $wednesday_text = (isset($schedule['weekday']['wednesday'])) ? __('Wed', 'syn_restaurant_plugin') : null;
            $thursday_text = (isset($schedule['weekday']['thursday'])) ? __('Thu', 'syn_restaurant_plugin') : null;
            $friday_text = (isset($schedule['weekday']['friday'])) ? __('Fri', 'syn_restaurant_plugin') : null;
            $saturday_text = (isset($schedule['weekday']['saturday'])) ? __('Sat', 'syn_restaurant_plugin') : null;
            $sunday_text = (isset($schedule['weekday']['sunday'])) ? __('Sun', 'syn_restaurant_plugin') : null;

            $days_obj = array($monday_text, $tuesday_text, $wednesday_text, $thursday_text, $friday_text, $saturday_text, $sunday_text);
            $days_text = implode(', ', array_filter($days_obj));
            $days_text = (!empty($days_text)) ? '' . $days_text : null;
            $time_text = date('H:i A', strtotime($starttime)) . ' - ' . date('H:i A', strtotime($endtime));

            $parameters = array(
                'days_text' => $days_text,
                'time_text' => $time_text,
                'monday' => $monday,
                'tuesday' => $tuesday,
                'wednesday' => $wednesday,
                'thursday' => $thursday,
                'friday' => $friday,
                'saturday' => $saturday,
                'sunday' => $sunday,
                'start_time' => $starttime,
                'end_time' => $endtime,
                'content_open' => ' closed'
            );
            syn_restaurant_manager_schedule_template($parameters, $key);
            ?>
            <?php
        }
    }
}

/**
 * The schedule template.
 * @param type $parameters
 * @param type $key
 */
function syn_restaurant_manager_schedule_template($parameters = array(), $key = '%index%') {
    ?>
    <div class="scheduler<?php echo $parameters['content_open'] ?>">
        <div class="schedule-header">
            <span class="schedule-days"><i class="days-icon rman-calendar"></i><?php echo $parameters['days_text'] ?></span>
            <span class="schedule-time"><i class="time-icon rman-clock-o"></i><?php echo $parameters['time_text'] ?></span>
            <a class="delete-schedule-button" href="javascrip:void(0)"></a> 
            <a class="toggle-schedule-button" href="javascrip:void(0)"></a>
        </div>
        <div class="schedule-content">
            <div class="weekdays">
                <label><?php _e('Days of the week', 'syn_restaurant_plugin') ?></label>
                <ul>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][monday]" value="true" <?php checked($parameters['monday'], 'true', true) ?> /><label><?php _e('Monday', 'syn_restaurant_plugin') ?></label>
                    </li>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][tuesday]" value="true" <?php checked($parameters['tuesday'], 'true', true) ?> /><label><?php _e('Tuesday', 'syn_restaurant_plugin') ?></label>
                    </li>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][wednesday]" value="true" <?php checked($parameters['wednesday'], 'true', true) ?> /><label><?php _e('Wednesday', 'syn_restaurant_plugin') ?></label>
                    </li>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][thursday]" value="true" <?php checked($parameters['thursday'], 'true', true) ?> /><label><?php _e('Thursday', 'syn_restaurant_plugin') ?></label>
                    </li>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][friday]" value="true" <?php checked($parameters['friday'], 'true', true) ?> /><label><?php _e('Friday', 'syn_restaurant_plugin') ?></label>
                    </li>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][saturday]" value="true" <?php checked($parameters['saturday'], 'true', true) ?> /><label><?php _e('Saturday', 'syn_restaurant_plugin') ?></label>
                    </li>
                    <li>
                        <input type="checkbox" name="scheduler[<?php echo $key ?>][weekday][sunday]" value="true" <?php checked($parameters['sunday'], 'true', true) ?> /><label><?php _e('Sunday', 'syn_restaurant_plugin') ?></label>
                    </li>
                </ul>
            </div>
            <div class="timeslot">
                <label><?php _e('Open Times', 'syn_restaurant_plugin') ?></label>
                <div>
                    <input class="syn-scheduler-timepicker" type="text" name="scheduler[<?php echo $key ?>][timeslot][starttime]" value="<?php echo $parameters['start_time'] ?>" readonly/>
                    <input class="syn-scheduler-timepicker" type="text" name="scheduler[<?php echo $key ?>][timeslot][endtime]" value="<?php echo $parameters['end_time'] ?>" readonly/>
                </div>
            </div>
        </div>                
    </div>
    <?php
}

/**
 * Process the notifcation email when the reservation status changes.
 * @param type $status
 */
function syntaxthemes_process_notification_email($status, $email_address, $post_id) {

    $syn_email = new \syntaxthemes\restaurant\email_notifications();
    $result = false;

    switch ($status) {
        case 'pending':

            $admin_email = get_bloginfo('admin_email');
            $admin_sent = $syn_email->admin_booking_notification($admin_email, $post_id);

            if ($admin_sent) {
                $result = $syn_email->customer_pending_booking_notification($email_address, $post_id);
            }
            break;
        case 'confirmed': $result = $syn_email->customer_confirmed_booking_notification($email_address, $post_id);
            break;
        case 'rejected': $result = $syn_email->customer_rejected_booking_notification($email_address, $post_id);
            break;
        default: null;
            break;
    }

    return $result;
}

//menu functions
function syn_restaurant_menu_the_content_filter($content) {

    // array of custom shortcodes requiring the fix 
    $block = join("|", array("syn_restaurant_menu"));

    // opening tag
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content);

    // closing tag
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep);

    return $rep;
}

add_filter("the_content", "syn_restaurant_menu_the_content_filter");

function syn_restaurant_menu_portfolio_featured_image($post_id) {

    $post_thumbnail_id = get_post_thumbnail_id($post_id);

    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');

        return $post_thumbnail_img[0];
    }
}

function syn_restaurant_menus_add_shortcodes($shortcode_classes) {

    $shortcodes = array(
            //'syntaxthemes\restaurant\menus\syn_restaurant_menu'
    );

    $shortcode_classes = array_merge($shortcode_classes, $shortcodes);

    return $shortcode_classes;
}

add_filter('syn_restaurant_manager_add_shortcodes', 'syn_restaurant_menus_add_shortcodes', 10, 1);

if (!function_exists('syn_restaurant_menus_get_all_terms_options')) {

    function syn_restaurant_menus_get_all_terms_options($taxonomies = array(), $args = array()) {

        $options = array();

        $defaults = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => true,
            'exclude' => array(),
            'exclude_tree' => array(),
            'include' => array(),
            'number' => '',
            'fields' => 'all',
            'slug' => '',
            'parent' => '',
            'hierarchical' => true,
            'child_of' => 0,
            'get' => '',
            'name__like' => '',
            'description__like' => '',
            'pad_counts' => false,
            'offset' => '',
            'search' => '',
            'cache_domain' => 'core'
        );

        $merged_args = array_merge($defaults, $args);

        $terms = get_terms($taxonomies, $merged_args);

        $options[] = array('text' => __('All Categories', 'synth_taurus_theme'), 'value' => '');

        foreach ($terms as $term) {
            $options[] = array('text' => $term->name, 'value' => $term->term_id);
        }

        return $options;
    }

}

function syn_restaurant_menus_get_spice_rating($spice_rating) {

    $rating = '';

    switch ($spice_rating) {
        case 0: $rating = '';
            break;
        case 1:
            $rating = '<span class="rating-star rman-flame"></span>';
            break;
        case 2:
            $rating = '<span class="rating-star rman-flame"></span><span class="rating-star rman-flame"></span>';
            break;
        case 3:
            $rating = '<span class="rating-star rman-flame"></span><span class="rating-star rman-flame"></span><span class="rating-star rman-flame"></span>';
            break;
        default:
            $rating = '';
            break;
    }

    return $rating;
}

if (!function_exists('syn_restaurant_menus_get_all_meal_options')) {

    function syn_restaurant_menus_get_all_meal_options() {

        global $syn_restaurant_config;

        $options = array();

        $args = array(
            'post_type' => 'syn_rest_meal',
            'posts_per_page' => '-1',
            'post_status' => 'publish'
        );

        $query = new \WP_Query($args);

        while ($query->have_posts()) {
            global $post;
            $query->the_post();

            $post_id = get_the_ID();

            $currency_symbol = get_option($syn_restaurant_config->plugin_prefix . 'currency_symbol', '£');
            $full_price = get_post_meta($post_id, 'full_price', true);

            $text = '<span class="meal-title">' . $post->post_title . '</span><span class="meal-price">' . $currency_symbol . $full_price . '</span>';

            $options[] = array('text' => $text, 'value' => $post->ID);
        }

        wp_reset_query();

        return $options;
    }

}

if (!function_exists('syn_restaurant_manager_get_meal_options')) {

    function syn_restaurant_manager_get_meal_options() {

        global $syn_restaurant_config;

        $session = new \syntaxthemes\restaurant\session();

        $menu_id = $session->post_var('menu_id');
        $course_id = $session->post_var('course_id');

        $relation = (empty($menu_id) || empty($course_id)) ? 'OR' : 'AND';

        if (!empty($menu_id) || !empty($course_id)) {
            $args = array(
                'post_type' => 'syn_rest_meal',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'tax_query' => array(
                    'relation' => $relation,
                    array(
                        'taxonomy' => 'syn_rest_menu',
                        'field' => 'id',
                        'terms' => $menu_id
                    ),
                    array(
                        'taxonomy' => 'syn_rest_course',
                        'field' => 'id',
                        'terms' => $course_id
                    )
                )
            );
        } else {
            $args = array(
                'post_type' => 'syn_rest_meal',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            );
        }

        $query = new \WP_Query($args);

        $html = '';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                global $post;

                $post_id = get_the_ID();

                $currency_symbol = get_option($syn_restaurant_config->plugin_prefix . 'currency_symbol', '£');
                $full_price = get_post_meta($post_id, 'full_price', true);

                $html .= "<li><input type=\"checkbox\" name=\"syn_restaurant_menu_ids\" value=\"{$post->ID}\">&nbsp;<label><span class=\"meal-title\">{$post->post_title}</span><span class=\"meal-price\">{$currency_symbol}{$full_price}</span></label></li>";
            }
        }

        $xml_response = new WP_Ajax_Response();

        $response = array(
            'id' => 1,
            'what' => 'syn_restaurant_menu_ids',
            'action' => 'update_meal_items',
            'data' => $html
        );
        $xml_response->add($response);
        $xml_response->send();

        die();
    }

    add_action('wp_ajax_restaurant_manager_get_meal_options', 'syn_restaurant_manager_get_meal_options');
}

function syn_restaurant_manager_country_codes() {

    $countries = array(
        'AF' => 'AFGHANISTAN',
        'AL' => 'ALBANIA',
        'DZ' => 'ALGERIA',
        'AS' => 'AMERICAN SAMOA',
        'AD' => 'ANDORRA',
        'AO' => 'ANGOLA',
        'AI' => 'ANGUILLA',
        'AQ' => 'ANTARCTICA',
        'AG' => 'ANTIGUA AND BARBUDA',
        'AR' => 'ARGENTINA',
        'AM' => 'ARMENIA',
        'AW' => 'ARUBA',
        'AU' => 'AUSTRALIA',
        'AT' => 'AUSTRIA',
        'AZ' => 'AZERBAIJAN',
        'BS' => 'BAHAMAS',
        'BH' => 'BAHRAIN',
        'BD' => 'BANGLADESH',
        'BB' => 'BARBADOS',
        'BY' => 'BELARUS',
        'BE' => 'BELGIUM',
        'BZ' => 'BELIZE',
        'BJ' => 'BENIN',
        'BM' => 'BERMUDA',
        'BT' => 'BHUTAN',
        'BO' => 'BOLIVIA',
        'BA' => 'BOSNIA AND HERZEGOVINA',
        'BW' => 'BOTSWANA',
        'BV' => 'BOUVET ISLAND',
        'BR' => 'BRAZIL',
        'IO' => 'BRITISH INDIAN OCEAN TERRITORY',
        'BN' => 'BRUNEI DARUSSALAM',
        'BG' => 'BULGARIA',
        'BF' => 'BURKINA FASO',
        'BI' => 'BURUNDI',
        'KH' => 'CAMBODIA',
        'CM' => 'CAMEROON',
        'CA' => 'CANADA',
        'CV' => 'CAPE VERDE',
        'KY' => 'CAYMAN ISLANDS',
        'CF' => 'CENTRAL AFRICAN REPUBLIC',
        'TD' => 'CHAD',
        'CL' => 'CHILE',
        'CN' => 'CHINA',
        'CX' => 'CHRISTMAS ISLAND',
        'CC' => 'COCOS (KEELING) ISLANDS',
        'CO' => 'COLOMBIA',
        'KM' => 'COMOROS',
        'CG' => 'CONGO',
        'CD' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE',
        'CK' => 'COOK ISLANDS',
        'CR' => 'COSTA RICA',
        'CI' => 'COTE D IVOIRE',
        'HR' => 'CROATIA',
        'CU' => 'CUBA',
        'CY' => 'CYPRUS',
        'CZ' => 'CZECH REPUBLIC',
        'DK' => 'DENMARK',
        'DJ' => 'DJIBOUTI',
        'DM' => 'DOMINICA',
        'DO' => 'DOMINICAN REPUBLIC',
        'TP' => 'EAST TIMOR',
        'EC' => 'ECUADOR',
        'EG' => 'EGYPT',
        'SV' => 'EL SALVADOR',
        'GQ' => 'EQUATORIAL GUINEA',
        'ER' => 'ERITREA',
        'EE' => 'ESTONIA',
        'ET' => 'ETHIOPIA',
        'FK' => 'FALKLAND ISLANDS (MALVINAS)',
        'FO' => 'FAROE ISLANDS',
        'FJ' => 'FIJI',
        'FI' => 'FINLAND',
        'FR' => 'FRANCE',
        'GF' => 'FRENCH GUIANA',
        'PF' => 'FRENCH POLYNESIA',
        'TF' => 'FRENCH SOUTHERN TERRITORIES',
        'GA' => 'GABON',
        'GM' => 'GAMBIA',
        'GE' => 'GEORGIA',
        'DE' => 'GERMANY',
        'GH' => 'GHANA',
        'GI' => 'GIBRALTAR',
        'GR' => 'GREECE',
        'GL' => 'GREENLAND',
        'GD' => 'GRENADA',
        'GP' => 'GUADELOUPE',
        'GU' => 'GUAM',
        'GT' => 'GUATEMALA',
        'GN' => 'GUINEA',
        'GW' => 'GUINEA-BISSAU',
        'GY' => 'GUYANA',
        'HT' => 'HAITI',
        'HM' => 'HEARD ISLAND AND MCDONALD ISLANDS',
        'VA' => 'HOLY SEE (VATICAN CITY STATE)',
        'HN' => 'HONDURAS',
        'HK' => 'HONG KONG',
        'HU' => 'HUNGARY',
        'IS' => 'ICELAND',
        'IN' => 'INDIA',
        'ID' => 'INDONESIA',
        'IR' => 'IRAN, ISLAMIC REPUBLIC OF',
        'IQ' => 'IRAQ',
        'IE' => 'IRELAND',
        'IL' => 'ISRAEL',
        'IT' => 'ITALY',
        'JM' => 'JAMAICA',
        'JP' => 'JAPAN',
        'JO' => 'JORDAN',
        'KZ' => 'KAZAKSTAN',
        'KE' => 'KENYA',
        'KI' => 'KIRIBATI',
        'KP' => 'KOREA DEMOCRATIC PEOPLES REPUBLIC OF',
        'KR' => 'KOREA REPUBLIC OF',
        'KW' => 'KUWAIT',
        'KG' => 'KYRGYZSTAN',
        'LA' => 'LAO PEOPLES DEMOCRATIC REPUBLIC',
        'LV' => 'LATVIA',
        'LB' => 'LEBANON',
        'LS' => 'LESOTHO',
        'LR' => 'LIBERIA',
        'LY' => 'LIBYAN ARAB JAMAHIRIYA',
        'LI' => 'LIECHTENSTEIN',
        'LT' => 'LITHUANIA',
        'LU' => 'LUXEMBOURG',
        'MO' => 'MACAU',
        'MK' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF',
        'MG' => 'MADAGASCAR',
        'MW' => 'MALAWI',
        'MY' => 'MALAYSIA',
        'MV' => 'MALDIVES',
        'ML' => 'MALI',
        'MT' => 'MALTA',
        'MH' => 'MARSHALL ISLANDS',
        'MQ' => 'MARTINIQUE',
        'MR' => 'MAURITANIA',
        'MU' => 'MAURITIUS',
        'YT' => 'MAYOTTE',
        'MX' => 'MEXICO',
        'FM' => 'MICRONESIA, FEDERATED STATES OF',
        'MD' => 'MOLDOVA, REPUBLIC OF',
        'MC' => 'MONACO',
        'MN' => 'MONGOLIA',
        'MS' => 'MONTSERRAT',
        'MA' => 'MOROCCO',
        'MZ' => 'MOZAMBIQUE',
        'MM' => 'MYANMAR',
        'NA' => 'NAMIBIA',
        'NR' => 'NAURU',
        'NP' => 'NEPAL',
        'NL' => 'NETHERLANDS',
        'AN' => 'NETHERLANDS ANTILLES',
        'NC' => 'NEW CALEDONIA',
        'NZ' => 'NEW ZEALAND',
        'NI' => 'NICARAGUA',
        'NE' => 'NIGER',
        'NG' => 'NIGERIA',
        'NU' => 'NIUE',
        'NF' => 'NORFOLK ISLAND',
        'MP' => 'NORTHERN MARIANA ISLANDS',
        'NO' => 'NORWAY',
        'OM' => 'OMAN',
        'PK' => 'PAKISTAN',
        'PW' => 'PALAU',
        'PS' => 'PALESTINIAN TERRITORY, OCCUPIED',
        'PA' => 'PANAMA',
        'PG' => 'PAPUA NEW GUINEA',
        'PY' => 'PARAGUAY',
        'PE' => 'PERU',
        'PH' => 'PHILIPPINES',
        'PN' => 'PITCAIRN',
        'PL' => 'POLAND',
        'PT' => 'PORTUGAL',
        'PR' => 'PUERTO RICO',
        'QA' => 'QATAR',
        'RE' => 'REUNION',
        'RO' => 'ROMANIA',
        'RU' => 'RUSSIAN FEDERATION',
        'RW' => 'RWANDA',
        'SH' => 'SAINT HELENA',
        'KN' => 'SAINT KITTS AND NEVIS',
        'LC' => 'SAINT LUCIA',
        'PM' => 'SAINT PIERRE AND MIQUELON',
        'VC' => 'SAINT VINCENT AND THE GRENADINES',
        'WS' => 'SAMOA',
        'SM' => 'SAN MARINO',
        'ST' => 'SAO TOME AND PRINCIPE',
        'SA' => 'SAUDI ARABIA',
        'SN' => 'SENEGAL',
        'SC' => 'SEYCHELLES',
        'SL' => 'SIERRA LEONE',
        'SG' => 'SINGAPORE',
        'SK' => 'SLOVAKIA',
        'SI' => 'SLOVENIA',
        'SB' => 'SOLOMON ISLANDS',
        'SO' => 'SOMALIA',
        'ZA' => 'SOUTH AFRICA',
        'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
        'ES' => 'SPAIN',
        'LK' => 'SRI LANKA',
        'SD' => 'SUDAN',
        'SR' => 'SURINAME',
        'SJ' => 'SVALBARD AND JAN MAYEN',
        'SZ' => 'SWAZILAND',
        'SE' => 'SWEDEN',
        'CH' => 'SWITZERLAND',
        'SY' => 'SYRIAN ARAB REPUBLIC',
        'TW' => 'TAIWAN, PROVINCE OF CHINA',
        'TJ' => 'TAJIKISTAN',
        'TZ' => 'TANZANIA, UNITED REPUBLIC OF',
        'TH' => 'THAILAND',
        'TG' => 'TOGO',
        'TK' => 'TOKELAU',
        'TO' => 'TONGA',
        'TT' => 'TRINIDAD AND TOBAGO',
        'TN' => 'TUNISIA',
        'TR' => 'TURKEY',
        'TM' => 'TURKMENISTAN',
        'TC' => 'TURKS AND CAICOS ISLANDS',
        'TV' => 'TUVALU',
        'UG' => 'UGANDA',
        'UA' => 'UKRAINE',
        'AE' => 'UNITED ARAB EMIRATES',
        'GB' => 'UNITED KINGDOM',
        'US' => 'UNITED STATES',
        'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
        'UY' => 'URUGUAY',
        'UZ' => 'UZBEKISTAN',
        'VU' => 'VANUATU',
        'VE' => 'VENEZUELA',
        'VN' => 'VIET NAM',
        'VG' => 'VIRGIN ISLANDS, BRITISH',
        'VI' => 'VIRGIN ISLANDS, U.S.',
        'WF' => 'WALLIS AND FUTUNA',
        'EH' => 'WESTERN SAHARA',
        'YE' => 'YEMEN',
        'YU' => 'YUGOSLAVIA',
        'ZM' => 'ZAMBIA',
        'ZW' => 'ZIMBABWE',
    );

    return $countries;
}

function syn_restaurant_manager_get_all_image_sizes() {

    global $_wp_additional_image_sizes;

    $default_image_sizes = array('thumbnail', 'medium', 'large');

    foreach ($default_image_sizes as $size) {
        $image_sizes[$size]['width'] = intval(get_option("{$size}_size_w"));
        $image_sizes[$size]['height'] = intval(get_option("{$size}_size_h"));
        $image_sizes[$size]['crop'] = get_option("{$size}_crop") ? get_option("{$size}_crop") : false;
    }

    if (isset($_wp_additional_image_sizes) && count($_wp_additional_image_sizes)) {
        $image_sizes = array_merge($image_sizes, $_wp_additional_image_sizes);
    }

    return $image_sizes;
}

function syn_restaurant_manager_image_size_options() {

    $image_sizes = syn_restaurant_manager_get_all_image_sizes();
    $options = array();

    foreach ($image_sizes as $image_size_name => $image_size) {

        $size_name = ucwords(str_replace(array('-', '_'), ' ', $image_size_name));
        $width = $image_size['width'];
        $height = $image_size['height'];
        $name = $size_name . ' (' . $width . ' x ' . $height . ')';

        $options[] = array('text' => $name, 'value' => $image_size_name);
    }

    return $options;
}

//function syn_restaurant_manager_get_status($status) {
//
//    $reservation_status = __('Status Unknown', 'syn_restaurant_plugin');
//
//    switch ($status) {
//        case 'pending': $reservation_status = __('Status Pending', 'syn_restaurant_plugin');
//            break;
//    }
//}

function syn_restaurant_manager_get_user_role() {
    global $current_user;

    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

    return $user_role;
}
?>