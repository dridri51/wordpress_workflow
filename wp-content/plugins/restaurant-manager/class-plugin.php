<?php

namespace syntaxthemes\restaurant;

/**
 * Description of the plugin
 *
 * @author Ryan
 */
class plugin extends plugin_base {

    /**
     * The main plugin constructor
     * 
     * @global type $syn_restaurant_config
     */
    public function __construct() {

        global $syn_restaurant_config;
        parent::__construct($syn_restaurant_config);

        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
        add_action('init', array($this, 'add_actions_and_filters'));
        add_action('init', array($this, 'register_script_files'));
        add_action('init', array($this, 'register_roles'), 5);
    }

    /**
     * When the plugin activates install any database tables which are required.
     */
    public function install_database_tables() {
        
    }

    /**
     * When the plugin activates add any default options which are required
     * by the plugin.
     */
    public function install_options() {

        //General Settings
        $group_size = 15;
        $reservation_success_message = "Thank you, We have successfully received your booking request.  Your booking is awaiting to be confirmed with us.  We will send you updates to the email address provided.";

        add_option($this->_config->plugin_prefix . 'group_size', $group_size);
        add_option($this->_config->plugin_prefix . 'reservation_success_message', $reservation_success_message);

        //Notifications Settings
        $admin_email = syn_restaurant_manager_default_admin_email();
        $reservation_email = syn_restaurant_manager_default_reservation_email();
        $reservation_confirmed_email = syn_restaurant_manager_default_reservation_confirmed_email();
        $reservation_rejected_email = syn_restaurant_manager_default_reservation_rejected_email();

        add_option($this->_config->plugin_prefix . 'admin_email', $admin_email);
        add_option($this->_config->plugin_prefix . 'reservation_email', $reservation_email);
        add_option($this->_config->plugin_prefix . 'reservation_confirmed_email', $reservation_confirmed_email);
        add_option($this->_config->plugin_prefix . 'reservation_rejected_email', $reservation_rejected_email);
    }

    /**
     * When the plugin is updated to a newer version the code here
     * will make any ammendments which are required.
     */
    public function upgrade() {

        global $wpdb;

        if (version_compare($this->plugin_installed_version(), '1.1.3') < 0) {

            $result = $wpdb->update($wpdb->term_taxonomy, array('taxonomy' => 'syn_rest_menu'), array('taxonomy' => 'syn_rest_menu'));
            $result = $wpdb->update($wpdb->term_taxonomy, array('taxonomy' => 'syn_rest_course'), array('taxonomy' => 'syn_menu_course'));
            $result = $wpdb->update($wpdb->term_taxonomy, array('taxonomy' => 'syn_rest_diet'), array('taxonomy' => 'syn_dietary_type'));
            $result = $wpdb->update($wpdb->term_taxonomy, array('taxonomy' => 'syn_rest_cuisine'), array('taxonomy' => 'syn_cuisine_type'));
        }

        if (version_compare($this->plugin_installed_version(), '1.2.0') < 0) {

            $events_data = new events_data();
            $events_data->create_table();

            $eventmeta_data = new eventmeta_data();
            $eventmeta_data->create_table();
        }
    }

    /**
     * Register the plugin custom roles and permissions.
     */
    public function register_roles() {

        //Restaurant Manager Roles
        add_role('syn_manager', __('Manager', 'syn_restaurant_plugin'), array(
            'read' => true
        ));

        add_role('syn_staff', 'Staff', array(
            'read' => true
        ));

        $role_names = array(
            'administrator',
            'editor',
            'syn_manager',
            'syn_staff'
        );

        foreach ($role_names as $role_name) {
            $role = get_role($role_name);

            $role->add_cap('read');
            $role->add_cap('edit_posts');
            $role->add_cap('manage_restaurant');

            if (in_array($role_name, array('administrator', 'editor', 'syn_manager'))) {

                $role->add_cap('upload_files');
                
                $role->add_cap('read_private_meals');
                $role->add_cap('edit_meals');
                $role->add_cap('edit_others_meals');
                $role->add_cap('edit_private_meals');
                $role->add_cap('edit_published_meals');
                $role->add_cap('publish_meals');
                $role->add_cap('delete_meals');
                $role->add_cap('delete_private_meals');
                $role->add_cap('delete_published_meals');
                $role->add_cap('delete_others_meals');

                $role->add_cap('manage_syn_rest_menu');
                $role->add_cap('manage_syn_rest_course');
                $role->add_cap('manage_syn_rest_diet');
                $role->add_cap('manage_syn_rest_cuisine');

                $role->add_cap('read_private_reservations');
                $role->add_cap('edit_reservations');
                $role->add_cap('edit_others_reservations');
                $role->add_cap('edit_private_reservations');
                $role->add_cap('edit_published_reservations');
                $role->add_cap('publish_reservations');
                $role->add_cap('delete_reservations');
                $role->add_cap('delete_private_reservations');
                $role->add_cap('delete_published_reservations');
                $role->add_cap('delete_others_reservations');

                $role->add_cap('manage_restaurant_options');                
            }

            if (in_array($role_name, array('syn_staff'))) {

                $role->add_cap('read_private_reservations');
                $role->add_cap('edit_reservations');
                $role->add_cap('edit_others_reservations');
                $role->add_cap('edit_private_reservations');
                $role->add_cap('edit_published_reservations');
                $role->add_cap('publish_reservations');
                
                $role->add_cap('manage_restriction');
            }
        }
    }

    /**
     * Any actions or filters required by the plugin will go here.
     */
    public function add_actions_and_filters() {

        /*
         * Translations can be added to the /languages/ directory.
         */
        load_plugin_textdomain('syn_restaurant_plugin', false, $this->_config->plugin_path . '/languages/');
    }

    /**
     * Create and manage the admin menu here.
     */
    public function add_admin_menu() {
        
    }

    /**
     * Register the plugin script files here.
     */
    public function register_script_files() {

        wp_register_style('restaurant-manager-admin-style', plugins_url('/assets/css/admin-style.css', __FILE__));
        wp_register_style('restaurant-manager-style', plugins_url('/assets/css/style.css', __FILE__));

        wp_register_script('jquery-validation', plugins_url('framework/js/validation/jquery.validate.min.js', __FILE__), array('jquery'), null, true);
        wp_register_script('jquery-timepicker-script', plugins_url('/assets/js/jquery-ui-timepicker.js', __FILE__), array('jquery'), false, true);
        wp_register_script('jquery-pickdate-picker-script', plugins_url('/assets/js/pickdate/picker.js', __FILE__), array('jquery'), false, true);
        wp_register_script('jquery-pickdate-date-script', plugins_url('/assets/js/pickdate/picker.date.js', __FILE__), array('jquery'), false, true);
        wp_register_script('jquery-pickdate-time-script', plugins_url('/assets/js/pickdate/picker.time.js', __FILE__), array('jquery'), false, true);
        wp_register_script('jquery-pickdate-legacy-script', plugins_url('/assets/js/pickdate/legacy.js', __FILE__), array('jquery'), false, true);

        wp_register_script('restaurant-menus-script', plugins_url('/assets/js/min/restaurant-menus-script.min.js', __FILE__), array('jquery'), false, true);
    }

    /**
     * Register the admin scripts and styles here.
     * 
     * @global type $wp_scripts
     * @param type $hook
     */
    public function admin_scripts($hook) {

        global $post_type, $wp_scripts;

        wp_enqueue_script('jquery-validation');
        wp_enqueue_script('synth-validation-script', plugins_url('framework/js/synth-validation.js', __FILE__), array('jquery'), false, true);

        wp_enqueue_script('synth-restaurant-manager-admin-script', plugins_url('framework/js/min/synth-admin.min.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-datepicker', 'jquery-timepicker-script'), false, true);
        wp_enqueue_script('synth-restaurant-admin-script', plugins_url('assets/js/min/synth-restaurant-admin.min.js', __FILE__), array('jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-datepicker', 'jquery-timepicker-script'), false, true);

        wp_enqueue_style('restaurant-manager-admin-style');

        $queryui = $wp_scripts->query('jquery-ui-core');

        // load the jquery ui theme
        $url = "http://ajax.googleapis.com/ajax/libs/jqueryui/" . $queryui->ver . "/themes/smoothness/jquery-ui.css";
        wp_enqueue_style('jquery-ui-smoothness', $url, false, null);

        if ($hook === 'post.php' || $hook === 'post-new.php') {

            wp_enqueue_script('restaurant-menus-script');
        }

        if ($post_type === 'syn_rest_meal' || $hook === 'syn_rest_meal_page_syn_restaurant_menus_settings') {
            wp_enqueue_style('restaurant-menus-admin-style');
        }
    }

    /**
     * Enqueue any scripts or styles required for the frontend website here.
     */
    public function frontend_scripts() {

        global $wp_scripts;

        wp_enqueue_style('restaurant-manager-style');
        wp_enqueue_style('jquery-pickdate-style', $this->_config->plugin_url . '/assets/js/pickdate/themes/pickdate.css');

        //wp_enqueue_script('syntaxthemes-controls-script', plugins_url('/assets/js/synth-controls.js', __FILE__), array('jquery', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-datepicker', 'jquery-timepicker-script'), false, true);
        // get the jquery ui object
        $queryui = $wp_scripts->query('jquery-ui-core');
        // load the jquery ui theme
        $url = "http://ajax.googleapis.com/ajax/libs/jqueryui/" . $queryui->ver . "/themes/smoothness/jquery-ui.css";
        wp_enqueue_style('jquery-ui-smoothness', $url, false, null);
    }

}

?>