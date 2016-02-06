<?php


add_action('wp_enqueue_scripts','get_main_script');

function get_main_script(){
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style('style_app',get_stylesheet_directory_uri()."/app.min.css");
}

?>