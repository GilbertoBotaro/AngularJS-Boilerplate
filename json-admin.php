<?php
/**
 * Plugin Name: ADMIN JS APP BOILERPLATE
 * Description: A JS SPA App in WordPress Admin
 * Author: Roy Sivan
 * Author URI: http://www.roysivan.com
 * Version: 0.1
 * Plugin URI: https://github.com/WP-API/WP-API
 * License: GPL2+
 * Text Domain: js-app-plugin
 */

require_once 'includes/cpt.php';

class admin_js_app {

    function __construct() {

        //Create CPT & Content
        $this->book_cpt = new js_app_book_cpt();

    }

    function create_cpt() {
        $this->book_cpt->create_cpt();
    }

    function create_book_content() {
        $this->book_cpt->create_book_content();
    }


}

$js_app = new admin_js_app();

/*
 * SAMPLE: Create CPT and Fake Content
 */
add_action( 'init', array( $js_app, 'create_cpt' ) );
add_action( 'init', array( $js_app, 'create_book_content' ) );

?>