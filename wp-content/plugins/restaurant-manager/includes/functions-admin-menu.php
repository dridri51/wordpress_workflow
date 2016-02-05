<?php

/**
 * The admin menu for the restaurant plugin is created here.
 * @global type $syn_restaurant_config
 */
function syn_restaurant_manager_admin_menu() {

    global $syn_restaurant_config;

    if (current_user_can('manage_restaurant')) {

        $parent_menu_slug = 'syn_restaurant_manager';

        //Settings for the custom admin
        $page_title = __('Restaurant', 'syn_restaurant_plugin');
        $menu_title = __('Restaurant', 'syn_restaurant_plugin');
        $capability = 'manage_restaurant';
        $menu_slug = $parent_menu_slug;
        $function = null; // Callback function which displays the page content.
        $icon_url = 'dashicons-syntaxstudio';
        $position = 27.15496;

        //Add custom admin menu
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);

        $submenu_pages = array(
            // Avoid duplicate pages. Add submenu page with same slug as parent slug.            
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => __('Dashboard', 'syn_restaurant_plugin'),
                'menu_title' => __('Welcome', 'syn_restaurant_plugin'),
                'capability' => 'manage_restaurant',
                'menu_slug' => $parent_menu_slug,
                'function' => 'syn_restaurant_dashboard_page', // Uses the same callback function as parent menu. 
            ),
            // Post Type :: View Meals
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => __('All Meals', 'syn_restaurant_plugin'),
                'menu_title' => __('Meals', 'syn_restaurant_plugin'),
                'capability' => 'edit_meals',
                'menu_slug' => 'edit.php?post_type=syn_rest_meal',
                'function' => null, // Uses the same callback function as parent menu. 
            ),
            // Post Type :: Add New Meal
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => __('Add New Meal', 'syn_restaurant_plugin'),
                'menu_title' => __('Add Meal', 'syn_restaurant_plugin'),
                'capability' => 'edit_meals',
                'menu_slug' => 'post-new.php?post_type=syn_rest_meal',
                'function' => null, // Doesn't need a callback function.
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => __('Menus', 'syn_restaurant_plugin'),
                'menu_title' => __('Menus', 'syn_restaurant_plugin'),
                'capability' => 'edit_meals',
                'menu_slug' => 'edit-tags.php?taxonomy=syn_rest_menu&post_type=syn_rest_meal',
                'function' => null, // Uses the same callback function as parent menu. 
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => __('Courses', 'syn_restaurant_plugin'),
                'menu_title' => __('Courses', 'syn_restaurant_plugin'),
                'capability' => 'edit_meals',
                'menu_slug' => 'edit-tags.php?taxonomy=syn_rest_course&post_type=syn_rest_meal',
                'function' => null, // Uses the same callback function as parent menu. 
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => __('Diets', 'syn_restaurant_plugin'),
                'menu_title' => __('Diets', 'syn_restaurant_plugin'),
                'capability' => 'edit_meals',
                'menu_slug' => 'edit-tags.php?taxonomy=syn_rest_diet&post_type=syn_rest_meal',
                'function' => null, // Uses the same callback function as parent menu. 
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => __('Cuisines', 'syn_restaurant_plugin'),
                'menu_title' => __('Cuisines', 'syn_restaurant_plugin'),
                'capability' => 'edit_meals',
                'menu_slug' => 'edit-tags.php?taxonomy=syn_rest_cuisine&post_type=syn_rest_meal',
                'function' => null, // Uses the same callback function as parent menu. 
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => __('All Reservations', 'syn_restaurant_plugin'),
                'menu_title' => __('Reservations', 'syn_restaurant_plugin'),
                'capability' => 'edit_reservations',
                'menu_slug' => 'edit.php?post_type=syn_rest_reservation',
                'function' => null, // Uses the same callback function as parent menu. 
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => __('Settings', 'syn_restaurant_plugin'),
                'menu_title' => __('Settings', 'syn_restaurant_plugin'),
                'capability' => 'manage_restaurant_options',
                'menu_slug' => 'syn_restaurant_manager_settings',
                'function' => 'syn_restaurant_manager_settings_page', // Uses the same callback function as parent menu. 
            ),
            array(
                'parent_slug' => $parent_menu_slug,
                'page_title' => __('Add-ons', 'syn_restaurant_plugin'),
                'menu_title' => __('Add-ons', 'syn_restaurant_plugin'),
                'capability' => 'manage_restaurant_options',
                'menu_slug' => 'syn_restaurant_manager_add_ons',
                'function' => 'syn_restaurant_manager_add_ons_page', // Uses the same callback function as parent menu. 
            ),
        );

        // Add each submenu item to custom admin menu.
        foreach ($submenu_pages as $submenu) {

            add_submenu_page($submenu['parent_slug'], $submenu['page_title'], $submenu['menu_title'], $submenu['capability'], $submenu['menu_slug'], $submenu['function']);
        }

        $role = syn_restaurant_manager_get_user_role();

        if (in_array($role, array('syn_manager', 'syn_staff'))) {
            remove_menu_page('edit.php');           //Posts
            remove_menu_page('upload.php');         //Media
            remove_menu_page('edit-comments.php');  //Comments
            remove_menu_page('tools.php');          //Tools
            remove_menu_page('options-general.php'); //Settings
        }
    }
}

add_action('admin_menu', 'syn_restaurant_manager_admin_menu');

if (!function_exists('syn_restaurant_manager_set_current_menu')) {

    function syn_restaurant_manager_set_current_menu($parent_file) {

        global $submenu_file, $current_screen, $pagenow;

        // Set the submenu as active/current while anywhere in your Custom Post Type (nwcm_news)
        if ($current_screen->post_type == 'syn_rest_meal') {

            if ($pagenow == 'post.php') {
                $submenu_file = 'edit.php?post_type=' . $current_screen->post_type;
            }

            if ($pagenow == 'edit-tags.php') {
                $submenu_file = 'edit-tags.php?taxonomy=' . $current_screen->taxonomy . '&post_type=' . $current_screen->post_type;
            }

            $parent_file = 'syn_restaurant_manager';
        }

        return $parent_file;
    }

    add_filter('parent_file', 'syn_restaurant_manager_set_current_menu');
}

function syn_restaurant_dashboard_page() {

    global $syn_restaurant_config;
    ?>
    <div class="wrap">
        <div id="restaurant_dashboard">
            <div class="section-introduction">
                <div class="column-image">
                    <img class="main-image" src="http://www.syntaxthemes.co.uk/wp-content/uploads/2014/11/taurus_restaurant_manager_display.png" alt="SyntaxThemes Restaurant Manager Themes and Plugin" />    
                </div>
                <div class="column-text">
                    <h2 class="main-title"><?php echo sprintf(__('Welcome to<br />Restaurant Manager %s', 'syn_restaurant_plugin'), $syn_restaurant_config->version) ?></h2>
                    <p>
                        <?php
                        _e('Thank you for using Restaurant Manager WordPress plugin. You can now manage your restaurant meals, '
                                . 'menus and table reservations.', 'syn_restaurant_plugin')
                        ?>
                    </p>    
                    <p> 
                        Please <a href="https://wordpress.org/plugins/restaurant-manager/" target="_blank" title="Rate the restaurant manager plugin.">rate</a> this plugin if 
                        you like it and use it!! It is free... For continued development I need your support.
                    </p>
                    <p>
                        If you have any problems or you would like to put forward ideas for extra features or ideas please <a href="http://www.syntaxthemes.co.uk/forums/" target="_blank" title="Restaurant Manager Support.">let us know</a>
                    </p>
                </div>
            </div>
            <!--            <div class="section-information">
                            <div class="column-features features">
                                <h2>Features</h2>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. 
                                    In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor 
                                    libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti.
                                </p>
                                <h4>Lorem ipsum dolor sit amet</h4>
                                <p>
                                    Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.
                                </p>    
                            </div>
                            <div class="column-addons addons">
                                <h2>Features</h2>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. 
                                    In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor 
                                    libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti.
                                </p>
                                <h4>Lorem ipsum dolor sit amet</h4>
                                <p>
                                    Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.
                                </p>  
                            </div>
                            <div class="column-themes themes">
                                <h2>Features</h2>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. 
                                    In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor 
                                    libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti.
                                </p>
                                <h4>Lorem ipsum dolor sit amet</h4>
                                <p>
                                    Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.
                                </p>  
                            </div>
                        </div>-->
        </div>
    </div>
    <?php
}

/**
 * The settings page is created here. A tabbed navigation control has also been created 
 * for these pages.
 */
function syn_restaurant_manager_settings_page() {

    global $syn_restaurant_config;

    $tab = (isset($_REQUEST['tab']) && $_REQUEST['tab'] !== null) ? $_REQUEST['tab'] : 'general';

    $version = get_option($syn_restaurant_config->plugin_prefix . 'version');
    ?>

    <div class="wrap">
        <div class="nav-tab-container">
            <h2 class="nav-tab-wrapper">
                <a <?php echo ($tab === 'general' ? 'class="nav-tab nav-tab-active"' : 'class="nav-tab"') ?> href="admin.php?page=syn_restaurant_manager_settings&amp;tab=general"><?php _e('General', 'syn_restaurant_plugin') ?></a>
                <a <?php echo ($tab === 'reservation_schedule' ? 'class="nav-tab nav-tab-active"' : 'class="nav-tab"') ?> href="admin.php?page=syn_restaurant_manager_settings&amp;tab=reservation_schedule"><?php _e('Reservation Schedule', 'syn_restaurant_plugin') ?></a>
                <a <?php echo ($tab === 'notifications' ? 'class="nav-tab nav-tab-active"' : 'class="nav-tab"') ?> href="admin.php?page=syn_restaurant_manager_settings&amp;tab=notifications"><?php _e('Notifications', 'syn_restaurant_plugin') ?></a>
                <?php
                apply_filters('syn_restaurant_manager_add_setting_tab_navigation', $tab);
                ?>
                <span class="plugin-version"><?php echo $version ?></span>
            </h2>             
            <?php if ($tab === 'general') { ?>
                <div class="nav_tab_content">       
                    <?php syn_restaurant_manager_general_page() ?>
                </div> 
            <?php } ?>
            <?php if ($tab === 'reservation_schedule') { ?>
                <div class="nav_tab_content">
                    <?php syn_restaurant_manager_reservation_schedule_page() ?>
                </div>
            <?php } ?>
            <?php if ($tab === 'notifications') { ?>
                <div class="nav_tab_content">
                    <?php syn_restaurant_manager_notifications_page() ?>
                </div>
            <?php } ?>
            <?php
            apply_filters('syn_restaurant_manager_add_setting_tab', $tab);
            ?>
        </div>
    </div>
    <?php
}

/**
 * The general settings page.
 * @global type $syn_restaurant_config
 */
function syn_restaurant_manager_general_page() {

    global $syn_restaurant_config;

    do_action('syn_restaurant_manager_process_form');

    $session = new syntaxthemes\restaurant\session();

    $group_size = get_option($syn_restaurant_config->plugin_prefix . 'group_size', '');
    $reservation_success_message = get_option($syn_restaurant_config->plugin_prefix . 'reservation_success_message', '');
    $restaurant_telephone = get_option($syn_restaurant_config->plugin_prefix . 'restaurant_telephone', '');
    $currency_symbol = get_option($syn_restaurant_config->plugin_prefix . 'currency_symbol', '£');
    $country_code = get_option($syn_restaurant_config->plugin_prefix . 'country_code', null);

    if (empty($reservation_success_message)) {
        $reservation_success_message = __('Thank you, We have successfully received your booking request.  Your booking is awaiting to be confirmed with us.  We will send you updates to the email address provided.', 'syn_restaurant_plugin');
    }
    ?>
    <h3><?php _e('General Settings', 'syn_restaurant_plugin') ?></h3>
    <form id="syn_restaurant_manager_general_form" action="<?php $session->current_page_url(true) ?>" method="POST">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php _e('Maximum Group Size', 'syn_restaurant_plugin') ?></th>
                    <td>
                        <input class="regular-text" id="group_size" name="group_size" type="text" value="<?php echo $group_size ?>" placeholder="<?php _e('No limit', 'syn_restaurant_plugin') ?>">
                        <p class="description"><?php _e('Set your maximum allowed size for your group bookings.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Reservation Success Message', 'syn_restaurant_plugin') ?></th><td>
                        <textarea class="large-text" id="reservation_success_message" name="reservation_success_message" rows="6"><?php echo $reservation_success_message ?></textarea>
                        <p class="description"><?php _e('Enter the message which is displayed when a reservation is made.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Restaurant Telephone Number', 'syn_restaurant_plugin') ?></th>
                    <td>
                        <input class="regular-text" id="restaurant_telephone" name="restaurant_telephone" type="text" value="<?php echo $restaurant_telephone ?>" />
                        <p class="description"><?php _e('Set your restaurant telephone contact number.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="country_code"><?php _e('Country Code', 'syn_restaurant_sms_plugin') ?></label>
                    </th>
                    <td>
                        <?php
                        $country_codes = syn_restaurant_manager_country_codes();
                        ?>
                        <select id="country_code" name="country_code">
                            <?php foreach ($country_codes as $code => $country) { ?>
                                <option value="<?php echo $code ?>" <?php selected($country_code, $code, true) ?>><?php echo $country ?></option>
                            <?php } ?>
                        </select>
                        <p class="description"><?php _e('Set your country code for your restaurant location.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"> 
                        <label for="currency_symbol"><?php _e('Currency Symbol', 'syn_restaurant_plugin') ?></label>
                    </th>
                    <td>
                        <input id="currency_symbol" class="regular-text" name="currency_symbol" type="text" value="<?php echo $currency_symbol ?>" /> 
                        <p class="description"><?php _e('Set the currency symbol for your menu prices.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>
    <!--                <tr>
                    <th scope="row"> 
                        <label for="date_format"><?php _e('Date Format', 'syn_restaurant_plugin') ?></label>
                    </th>
                    <td>
                        <select id="date_format" name="date_format">
                            <option value="F j, Y"<?php selected($date_format, 'F j, Y', true) ?>><?php echo date('F j, Y') ?></option>
                            <option value="j F, Y"<?php selected($date_format, 'j F, Y', true) ?>><?php echo date('j F, Y') ?></option>
                            <option value="m/d/Y"<?php selected($date_format, 'm/d/Y', true) ?>><?php echo date('m/d/Y') ?></option>
                            <option value="d/m/Y"<?php selected($date_format, 'd/m/Y', true) ?>><?php echo date('d/m/Y') ?></option>
                        </select>
                        <p class="description"><?php _e('Set the date format for dates displayed in your emails, bookings and reservation table.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>-->
    <!--                <tr>
                    <th scope="row"> 
                        <label for="time_format"><?php _e('Time Format', 'syn_restaurant_plugin') ?></label>
                    </th>
                    <td>
                        <select id="time_format" name="time_format">
                            <option value="g:i a"<?php selected($time_format, 'g:i a', true) ?>><?php echo date('g:i a') ?></option>
                            <option value="g:i A"<?php selected($time_format, 'g:i A', true) ?>><?php echo date('g:i A') ?></option>
                            <option value="G:i a"<?php selected($time_format, 'G:i a', true) ?>><?php echo date('G:i a') ?></option>
                            <option value="G:i A"<?php selected($time_format, 'G:i A', true) ?>><?php echo date('G:i A') ?></option>
                            <option value="h:i a"<?php selected($time_format, 'h:i a', true) ?>><?php echo date('h:i a') ?></option>
                            <option value="h:i A"<?php selected($time_format, 'h:i A', true) ?>><?php echo date('h:i A') ?></option>
                            <option value="H:i a"<?php selected($time_format, 'H:i a', true) ?>><?php echo date('H:i a') ?></option>
                            <option value="H:i A"<?php selected($time_format, 'H:i A', true) ?>><?php echo date('H:i A') ?></option>                            
                            <option value="H:i"<?php selected($time_format, 'H:i', true) ?>><?php echo date('H:i') ?></option>
                        </select>
                        <p class="description"><?php _e('Set the time format for dates displayed in your emails, bookings and reservation table.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>-->
            </tbody>
        </table>
        <p class="submit">
            <?php wp_nonce_field('save_general_settings'); ?>
            <input type="hidden" name="form_action" value="save_general_settings">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'syn_restaurant_plugin') ?>">
        </p>
    </form>
    <?php
}

/**
 * The reservation schedule settings page.
 * @global type $syn_restaurant_config
 */
function syn_restaurant_manager_reservation_schedule_page() {

    global $syn_restaurant_config;

    do_action('syn_restaurant_manager_process_form');

    $session = new syntaxthemes\restaurant\session();
    ?>
    <h3><?php _e('Reservation Schedule Settings', 'syn_restaurant_plugin') ?></h3>
    <form id="syn_restaurant_manager_schedule_form" action="<?php $session->current_page_url(true) ?>" method="POST">
        <table class="form-table">
            <tbody>
                <tr id="scheduler">
                    <th scope="row"><?php _e('Schedule', 'syn_restaurant_plugin') ?></th>
                    <td>
                        <div id="schedule_container">
                            <?php
                            syn_restaurant_manager_get_scheduler();
                            ?>
                        </div>
                        <a id="add_schedule_button" class="button" href="javascript:void(0)"><?php _e('Add New Schedule', 'syn_restaurant_plugin') ?></a>
                        <p class="description"><?php _e('Set your days and times for your schedule.  You can have multiple schedules.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <script id="schedule_template" type="text/template">
    <?php
    $parameters = array(
        'days_text' => '',
        'time_text' => __('Schedule your days and times', 'syn_restaurant_plugin'),
        'monday' => '',
        'tuesday' => '',
        'wednesday' => '',
        'thursday' => '',
        'friday' => '',
        'saturday' => '',
        'sunday' => '',
        'start_time' => '',
        'end_time' => '',
        'content_open' => ' add-schedule'
    );
    syn_restaurant_manager_schedule_template($parameters);
    ?>
        </script>
        <p class="submit">
            <?php wp_nonce_field('save_schedule_settings'); ?>
            <input type="hidden" name="form_action" value="save_schedule_settings">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'syn_restaurant_plugin') ?>">
        </p>
    </form>
    <?php
}

/**
 * The notifications settings page.
 * @global type $syn_restaurant_config
 */
function syn_restaurant_manager_notifications_page() {

    global $syn_restaurant_config;

    do_action('syn_restaurant_manager_process_form');

    $session = new syntaxthemes\restaurant\session();

    $reply_to_name = get_option($syn_restaurant_config->plugin_prefix . 'reply_to_name', '');
    $reply_to_email = get_option($syn_restaurant_config->plugin_prefix . 'reply_to_email', '');
    $admin_email_subject = get_option($syn_restaurant_config->plugin_prefix . 'admin_email_subject', '');
    $admin_email = get_option($syn_restaurant_config->plugin_prefix . 'admin_email', '');
    $reservation_email_subject = get_option($syn_restaurant_config->plugin_prefix . 'reservation_email_subject', '');
    $reservation_email = get_option($syn_restaurant_config->plugin_prefix . 'reservation_email', '');
    $reservation_confirmed_email_subject = get_option($syn_restaurant_config->plugin_prefix . 'reservation_confirmed_email_subject', '');
    $reservation_confirmed_email = get_option($syn_restaurant_config->plugin_prefix . 'reservation_confirmed_email', '');
    $reservation_rejected_email_subject = get_option($syn_restaurant_config->plugin_prefix . 'reservation_rejected_email_subject', '');
    $reservation_rejected_email = get_option($syn_restaurant_config->plugin_prefix . 'reservation_rejected_email', '');
    ?>
    <h3><?php _e('Notification Settings', 'syn_restaurant_plugin') ?></h3>
    <form id="syn_restaurant_manager_notifications_form" action="<?php $session->current_page_url(true) ?>" method="POST">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php _e('Reply To Name', 'syn_restaurant_plugin') ?></th>
                    <td>
                        <input id="reply_to_name" class="regular-text" name="reply_to_name" type="text" placeholder="<?php bloginfo('name') ?>" value="<?php echo $reply_to_name ?>" />
                        <p class="description"><?php _e('Set the reply to email name.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Reply To Email', 'syn_restaurant_plugin') ?></th>
                    <td>
                        <input id="reply_to_email" class="regular-text" name="reply_to_email" type="text" placeholder="<?php bloginfo('admin_email') ?>" value="<?php echo $reply_to_email ?>" />
                        <p class="description"><?php _e('Set the reply to email address.', 'syn_restaurant_plugin') ?></p>
                    </td>
                </tr>
        </table>
        <h3><?php _e('Email Templates', 'syn_restaurant_plugin') ?></h3>
        <p>
            <?php _e('The following emails are automatically sent to your customers when the status of their reservation changes.  You can change these templates to suit your own needs.', 'syn_restaurant_plugin') ?>
        </p>
        <h4><?php _e('Template Tags', 'syn_restaurant_plugin') ?></h4>
        <p>
            <?php _e('Use the follwing template tags in your emails to automatically add your customer and their booking data to the emails. Tags labeled with an asterisk(*) can be used in the email subject.', 'syn_restaurant_plugin') ?>
        </p>
        <ul id="email_template_tags_list">
            <li class="template-tag-item">
                <span class="tag-name">%first_name%</span>
                <span class="tag-description"><?php _e('* This is your customers first name.', 'syn_restaurant_plugin') ?></span>
            </li>
            <li class="template-tag-item">
                <span class="tag-name">%last_name%</span>
                <span class="tag-description"><?php _e('* This is your customers last name.', 'syn_restaurant_plugin') ?></span>
            </li>
            <li class="template-tag-item">
                <span class="tag-name">%telephone%</span>
                <span class="tag-description"><?php _e('The telephone number of the customer.', 'syn_restaurant_plugin') ?></span>
            </li>
            <li class="template-tag-item">
                <span class="tag-name">%email_address%</span>
                <span class="tag-description"><?php _e('The email address of the customer.', 'syn_restaurant_plugin') ?></span>
            </li>
            <li class="template-tag-item">
                <span class="tag-name">%guests_count%</span>
                <span class="tag-description"><?php _e('* The size of the party for the reservation.', 'syn_restaurant_plugin') ?></span>
            </li>
            <li class="template-tag-item">
                <span class="tag-name">%reservation_date%</span>
                <span class="tag-description"><?php _e('* The reservation date of the customer booking .', 'syn_restaurant_plugin') ?></span>
            </li>
            <li class="template-tag-item">
                <span class="tag-name">%reservation_time%</span>
                <span class="tag-description"><?php _e('* The reservation time of the customer booking .', 'syn_restaurant_plugin') ?></span>
            </li>
            <li class="template-tag-item">
                <span class="tag-name">%message%</span>
                <span class="tag-description"><?php _e('The message left by the customer when creating the reservation.', 'syn_restaurant_plugin') ?></span>
            </li>
            <li class="template-tag-item">
                <span class="tag-name">%site_name%</span>
                <span class="tag-description"><?php _e('The name of your restaurant set in the General Settings tab.', 'syn_restaurant_plugin') ?></span>
            </li>
            <li class="template-tag-item">
                <span class="tag-name">%site_link%</span>
                <span class="tag-description"><?php _e('A link to your website.', 'syn_restaurant_plugin') ?></span>
            </li>
            <li class="template-tag-item">
                <span class="tag-name">%current_time%</span>
                <span class="tag-description"><?php _e('This is the current date and time. Used for audit purposes.', 'syn_restaurant_plugin') ?></span>
            </li>
        </ul>    
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Administrator Email Subject', 'syn_restaurant_plugin') ?></th>
                <td>
                    <input id="admin_email_subject" class="regular-text" name="admin_email_subject" type="text" placeholder="<?php _e('You have a booking request', 'syn_restaurant_plugin') ?>" value="<?php echo $admin_email_subject ?>" />
                    <p class="description"><?php _e('Set the administrator email subject.', 'syn_restaurant_plugin') ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Administrator Email', 'syn_restaurant_plugin') ?></th>
                <td>
                    <?php
                    $admin_email_settings = array(
                        'textarea_name' => 'admin_email',
                        'textarea_rows' => 10,
                        'media_buttons' => false,
                        'teeny' => true,
                        'wpautop' => true
                    );

                    wp_editor($admin_email, 'admin_email', $admin_email_settings);
                    ?>
                    <p class="description"><?php _e('Create the email which your administrator will receive.', 'syn_restaurant_plugin') ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('New Reservation Email Subject', 'syn_restaurant_plugin') ?></th>
                <td>
                    <input id="new_reservation_email_subject" class="regular-text" name="reservation_email_subject" type="text" placeholder="<?php _e('You have a booking request', 'syn_restaurant_plugin') ?>" value="<?php echo $reservation_email_subject ?>" />
                    <p class="description"><?php _e('Set the new reservation email subject.', 'syn_restaurant_plugin') ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('New Reservation Email', 'syn_restaurant_plugin') ?></th>
                <td>
                    <?php
                    $reservation_email_settings = array(
                        'textarea_name' => 'reservation_email',
                        'textarea_rows' => 10,
                        'media_buttons' => false,
                        'teeny' => true,
                        'wpautop' => true
                    );

                    wp_editor($reservation_email, 'new_reservation_email', $reservation_email_settings);
                    ?>
                    <p class="description"><?php _e('Create the email which your customer will receive.', 'syn_restaurant_plugin') ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Reservation Confirmed Email Subject', 'syn_restaurant_plugin') ?></th>
                <td>
                    <input id="reservation_confirmed_email_subject" class="regular-text" name="reservation_confirmed_email_subject" type="text" placeholder="<?php _e('Your reservation request has been confirmed', 'syn_restaurant_plugin') ?>" value="<?php echo $reservation_confirmed_email_subject ?>" />
                    <p class="description"><?php _e('Set the new reservation email subject.', 'syn_restaurant_plugin') ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Reservation Confirmed Email', 'syn_restaurant_plugin') ?></th>
                <td>
                    <?php
                    $reservation_confirmed_email_settings = array(
                        'textarea_name' => 'reservation_confirmed_email',
                        'textarea_rows' => 10,
                        'media_buttons' => false,
                        'teeny' => true,
                        'wpautop' => true
                    );

                    wp_editor($reservation_confirmed_email, 'reservation_confirmed_email', $reservation_confirmed_email_settings);
                    ?>
                    <p class="description"><?php _e('Create the email which your customer will receive.', 'syn_restaurant_plugin') ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Reservation Rejected Email Subject', 'syn_restaurant_plugin') ?></th>
                <td>
                    <input id="reservation_rejected_email_subject" class="regular-text" name="reservation_rejected_email_subject" type="text" placeholder="<?php _e('Unfortunately we cannot make your reservation', 'syn_restaurant_plugin') ?>" value="<?php echo $reservation_rejected_email_subject ?>" />
                    <p class="description"><?php _e('Set the email subject for reservation rejections.', 'syn_restaurant_plugin') ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Reservation Rejected Email', 'syn_restaurant_plugin') ?></th>
                <td>
                    <?php
                    $reservation_rejected_email_settings = array(
                        'textarea_name' => 'reservation_rejected_email',
                        'textarea_rows' => 10,
                        'media_buttons' => false,
                        'teeny' => true,
                        'wpautop' => true
                    );

                    wp_editor($reservation_rejected_email, 'reservation_rejected_email', $reservation_rejected_email_settings);
                    ?>
                    <p class="description"><?php _e('Create the email which your customer will receive.', 'syn_restaurant_plugin') ?></p>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <?php wp_nonce_field('save_notification_settings'); ?>
            <input type="hidden" name="form_action" value="save_notification_settings">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'syn_restaurant_plugin') ?>">
        </p>
    </form>
    <?php
}

/**
 * The restaurant manager addons page.
 */
function syn_restaurant_manager_add_ons_page() {
    ?>
    <div class="wrap">
        <h2><?php _e('Add-ons', 'syn_restaurant_plugin') ?></h2>
        <div id="syn_restaurant_manager_addons">
            <ul class="addon-list">
                <li class="addon-item">
                    <div class="addon-block">                
                        <h3 class="addon-title"><?php _e('Taurus Restaurant Theme', 'syn_restaurant_plugin') ?></h3>
                        <div class="addon-content">
                            <img class="addon-image" src="http://www.syntaxthemes.co.uk/wp-content/uploads/2014/11/taurus-theme-thumbnail.jpg" />
                            <p>
                                <?php _e('Taurus Theme has been designed to work beautifully with our Restaurant Manager plugin.  It has styled the reservation form nicely with the
                                theme layout.  Check out the', 'syn_restaurant_plugin') ?> <a href="http://www.syntaxthemes.co.uk/exhibitions?theme=taurus-theme" target="_blank">demo</a>. 
                            </p>
                        </div> 
                        <div class="addon-footer">
                            <a class="read-more-link" href="https://creativemarket.com/syntaxthemes/109450-Taurus-Restaurant-Responsive-Theme" alt="Read more about this plugin" target="_blank"><?php _e('Read More', '') ?></a>
                            <a class="buy-now button-primary" href="https://creativemarket.com/syntaxthemes/109450-Taurus-Restaurant-Responsive-Theme" alt="Buy this plugin" target="_blank"><?php _e('Buy Now $35', '') ?></a>
                        </div>
                    </div>
                </li>
                <li class="addon-item">                                          
                </li>
                <li class="addon-item">                                         
                </li>
            </ul>
        </div>
        <div id="syn_restaurant_manager_addons">
            <ul class="addon-list">
                <li class="addon-item">
                    <div class="addon-block">                
                        <h3 class="addon-title"><?php _e('Restaurant MailChimp Subscribe User', 'syn_restaurant_plugin') ?></h3>
                        <div class="addon-content">
                            <img class="addon-image" src="http://www.syntaxthemes.co.uk/wp-content/uploads/2014/11/mailchimp-logo.png" />
                            <p>
                                <?php _e('This MailChimp plugin allows you to quickly and easily add a signup checkbox to your reservation form. 
                                This allows your customers to signup to your services by allowing their email to be added to your MailChimp list. 
                                Its is very easy to use, just install and on the settings tab enter your MailChimp account API Key and choose your email list.', 'syn_restaurant_plugin') ?>                                
                            </p>
                        </div> 
                        <div class="addon-footer">
                            <a class="read-more-link" href="http://www.syntaxthemes.co.uk/shop/plugins/restaurant-mailchimp-subscribe/" alt="Read more about this plugin" target="_blank"><?php _e('Read More', '') ?></a>
                            <a class="buy-now button-primary" href="http://www.syntaxthemes.co.uk/shop/plugins/restaurant-mailchimp-subscribe/" alt="Buy this plugin" target="_blank"><?php _e('Buy Now $19.99', '') ?></a>
                        </div>
                    </div>
                </li>
                <li class="addon-item">
                    <div class="addon-block">                
                        <h3 class="addon-title"><?php _e('Customer Email Support', 'syn_restaurant_plugin') ?></h3>
                        <div class="addon-content">
                            <img class="addon-image" src="http://www.syntaxthemes.co.uk/wp-content/uploads/2014/11/restaurant-emails-logo-450x450.jpg" />
                            <p>
                                <?php _e('Now you can email your customers directly from their saved reservation booking record.  All emails sent are
                                recorded on the booking so you can keep track of communication sent from your restaurant. Improve your customer
                                relations without the need for external email clients.', 'syn_restaurant_plugin') ?>                                  
                            </p>
                        </div> 
                        <div class="addon-footer">
                            <a class="read-more-link" href="http://www.syntaxthemes.co.uk/shop/plugins/restaurant-customer-emailer/" alt="Read more about this plugin" target="_blank"><?php _e('Read More', '') ?></a>
                            <a class="buy-now button-primary" href="http://www.syntaxthemes.co.uk/shop/plugins/restaurant-customer-emailer/" alt="Buy this plugin" target="_blank"><?php _e('Buy Now $19.99', '') ?></a>
                        </div>
                    </div>                       
                </li>
                <li class="addon-item">
                    <div class="addon-block">                
                        <h3 class="addon-title"><?php _e('Customer SMS Support', 'syn_restaurant_plugin') ?></h3>
                        <div class="addon-content">
                            <img class="addon-image" src="http://www.syntaxthemes.co.uk/wp-content/uploads/2014/11/restaurant-sms-logo-450x450.jpg" />
                            <p>
                                <?php _e('Now you can send SMS directly to your customer from your reservation screen. All SMS messages are recorded in your reservation screen
                                so that you can see what has been sent to your customers. You will need a Nexmo SMS account this is free to set up and you will
                                receive some free credit to try.  Check out', 'syn_restaurant_plugin') ?> <a href="https://www.nexmo.com/" title="Nexmo SMS" target="_blank">Nexmo SMS</a>
                            </p>
                        </div> 
                        <div class="addon-footer">
                            <a class="read-more-link" href="http://www.syntaxthemes.co.uk/shop/plugins/customer-sms-support/" alt="Read more about this plugin" target="_blank"><?php _e('Read More', '') ?></a>
                            <a class="buy-now button-primary" href="http://www.syntaxthemes.co.uk/shop/plugins/customer-sms-support/" alt="Buy this plugin" target="_blank"><?php _e('Buy Now $19.99', '') ?></a>
                        </div>
                    </div>                       
                </li>
            </ul>
        </div>
    </div>
    <?php
}
?>