<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-emails-data
 *
 * @author Ryan
 */
class events_data {

    private $_wpdb;
    private $_charset_collate;
    private $_table_name;
    private $_table_version;

    public function __construct() {

        global $syn_restaurant_config, $wpdb;

        $this->_config = $syn_restaurant_config;
        $this->_wpdb = $wpdb;
        $this->_table_name = $wpdb->prefix . 'restaurant_manager_events';
        $this->_table_version = '1.0.0';

        if (!empty($wpdb->charset)) {
            $this->_charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        }
        if (!empty($wpdb->collate)) {
            $this->_charset_collate .= " COLLATE $wpdb->collate";
        }
    }

    public function create_table() {

        /*
         * We'll set the default character set and collation for this table.
         * If we don't do this, some characters could end up being converted 
         * to just ?'s when saved in our table.
         */
        if ($this->_wpdb->get_var("SHOW TABLES LIKE '{$this->_table_name}'") != $this->_table_name) {

            $sql = "CREATE TABLE {$this->_table_name} (
                    id bigint(20) unsigned NOT NULL auto_increment,
                    post_id bigint(20) NOT NULL default '0',
                    author_id bigint(20) NOT NULL default '0',
                    author tinytext NOT NULL,
                    content text NOT NULL,
                    event_type varchar(20) NOT NULL default '',
                    created_date datetime NOT NULL default '0000-00-00 00:00:00',
                    updated_date datetime NOT NULL default '0000-00-00 00:00:00',
                    PRIMARY KEY  (id),
                    KEY post_id (post_id),
                    KEY author_id (author_id)
                    ) {$this->_charset_collate};";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            $result = dbDelta($sql);

            add_option($this->_config->plugin_prefix . 'events_datatable_version', $this->_table_version);
        }
    }

    public function update_table() {

        $installed_version = get_option($this->_config->plugin_prefix . 'events_datatable_version');

        if (version_compare($installed_version, $this->_table_version) <= 0) {

            //Update the table here.
            $sql = "CREATE TABLE {$this->_table_name} (
                    id bigint(20) unsigned NOT NULL auto_increment,
                    post_id bigint(20) NOT NULL default '0',
                    author_id bigint(20) NOT NULL default '0',
                    author tinytext NOT NULL,
                    content text NOT NULL,
                    event_type varchar(20) NOT NULL default '',
                    created_date datetime NOT NULL default '0000-00-00 00:00:00',
                    updated_date datetime NOT NULL default '0000-00-00 00:00:00',
                    PRIMARY KEY  (id),
                    KEY post_id (post_id),
                    KEY author_id (author_id)
                    ) {$this->_charset_collate};";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            $result = dbDelta($sql);

            update_option($this->_config->plugin_prefix . 'events_datatable_version', $this->_table_version);
        }
    }

    public function get_all($id) {

        $sql = "SELECT * 
                FROM {$this->_table_name}
                WHERE id = '{$id}'";

        $results = $this->_wpdb->get_results($sql);

        return $results;
    }

    public function count($data) {

        $sql = "SELECT COUNT(*) 
                FROM {$this->_table_name}
                WHERE post_id = '{$data['post_id']}'";

        $results = $this->_wpdb->get_var($sql);

        return $results;
    }

    public function insert(array $data) {

        if (empty($data)) {
            return false;
        }

        $inserted = $this->_wpdb->insert($this->_table_name, $data);

        if ($inserted) {
            return $this->_wpdb->insert_id;
        }

        return $inserted;
    }

    public function update(array $data, array $condition) {

        if (empty($data)) {
            return false;
        }

        $updated = $this->_wpdb->update($this->_table_name, $data, $condition);

        return $updated;
    }

    public function delete(array $condition) {

        $deleted = $this->_wpdb->update($this->_table_name, $condition);

        return $deleted;
    }

    public function query(array $data) {

        $sql = "SELECT * 
                FROM {$this->_table_name}
                WHERE post_id = '{$data['post_id']}'";

        if (!empty($data['orderby'])) {
            $sql .= " ORDER BY {$data['orderby']}";
        }
        if (!empty($data['order'])) {
            $sql .= " {$data['order']}";
        }
        if (!empty($data['limit'])) {
            $sql .= " LIMIT {$data['limit']}";
        }
        if (!empty($data['offset'])) {
            $sql .= " OFFSET {$data['offset']}";
        }

        $results = $this->_wpdb->get_results($sql);

        return $results;
    }

}

?>