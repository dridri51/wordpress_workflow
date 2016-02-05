<?php

namespace syntaxthemes\restaurant;

/**
 * Description of class-synth-session
 *
 * @author Ryan Haworth
 */
class session {

    public function __construct() {
        
    }

    public function server_var($id) {

        $value = null;

        if (isset($_SERVER[$id]) && !empty($_SERVER[$id])) {
            $value = filter_var($_SERVER[$id], FILTER_SANITIZE_STRING);
        }

        return $value;
    }

    public function post_var($id, $value = null, $is_array = false) {

        if (isset($_POST[$id]) && !empty($_POST[$id]) && !$is_array) {
            $value = filter_var($_POST[$id], FILTER_SANITIZE_STRING);
        } else if (isset($_POST[$id]) && !empty($_POST[$id])) {
            $value = $_POST[$id];
        }

        return $value;
    }

    public function get_var($id, $value = null, $is_array = false) {

        if (isset($_GET[$id]) && !empty($_GET[$id]) && !$is_array) {
            $value = filter_var($_GET[$id], FILTER_SANITIZE_STRING);
        } else if (isset($_GET[$id]) && !empty($_GET[$id])) {
            $value = $_GET[$id];
        }

        return $value;
    }

    public function request_var($id, $value = null, $is_array = false) {

        if (isset($_REQUEST[$id]) && !empty($_REQUEST[$id]) && !$is_array) {
            $value = filter_var($_REQUEST[$id], FILTER_SANITIZE_STRING);
        } else if (isset($_REQUEST[$id]) && !empty($_REQUEST[$id])) {
            $value = $_REQUEST[$id];
        }

        return $value;
    }

    public function empty_var($id) {

        if (isset($_REQUEST[$id]) && !empty($_REQUEST[$id])) {
            return false;
        }

        return true;
    }

    public function set_session($name, $value) {

        if (!headers_sent() && session_id()) {
            $_SESSION[$name] = $value;
        }
    }

    public function get_session($name) {

        $value = null;

        if (isset($_SESSION[$name]) && !empty($_SESSION[$name]) && session_id()) {
            $value = $_SESSION[$name];
        }

        return $value;
    }

    public function delete_session($name) {

        if (isset($_SESSION[$name]) && !empty($_SESSION[$name]) && session_id()) {
            unset($_SESSION[$name]);
            return true;
        }
        return false;
    }

    public function set_cookie($name, $value, $time) {

        if (!headers_sent()) {
            setcookie($name, $value, $time, COOKIEPATH, COOKIE_DOMAIN);
        }
    }

    public function get_cookie($name) {

        $value = null;

        if (isset($_COOKIE[$name]) && !empty($_COOKIE[$name])) {
            $value = $_COOKIE[$name];
        }

        return $value;
    }

    public function delete_cookie($name) {

        if (isset($_COOKIE[$name]) && !empty($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
            setcookie($name, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
            return true;
        }
        return false;
    }

    public function current_page_url($querystring = false) {

        $page_url = 'http';

        if (isset($_SERVER["HTTPS"])) {
            if ($_SERVER["HTTPS"] == "on") {
                $page_url .= "s";
            }
        }

        $page_url .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $page_url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            if (!$querystring) {
                $page_url .= $_SERVER["SERVER_NAME"] . strtok($_SERVER["REQUEST_URI"], '?');
            } else {
                $page_url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            }
        }

        return htmlentities($page_url);
    }

}