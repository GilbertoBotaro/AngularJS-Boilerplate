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

define( 'JS_APP_DIR', plugin_dir_path( __FILE__ ) );
define( 'JS_APP_URL', plugin_dir_url( __FILE__ ) );
define( 'PLUGIN_VERSION', '0.1' );



require_once 'includes/cpt.php';
require_once 'includes/admin_menu.php';
require_once 'includes/admin_js.php';
require_once 'includes/admin_custom_api.php';

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

    function rest_support() {
        global $wp_post_types;
        /*
         * Add support for custom post types
         * Add your CPT slugs to the $post_type_names array to add REST support
         * Docs: http://v2.wp-api.org/extending/custom-content-types/
         */
        $post_type_names = ['book'];
        foreach( $post_type_names as $post_type_name ) {
            if (isset($wp_post_types[$post_type_name])) {
                $wp_post_types[$post_type_name]->show_in_rest = true;
                $wp_post_types[$post_type_name]->rest_base = $post_type_name;
                $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
            }
        }

    }

    function custom_api() {
        $api = new admin_js_app_api();
        $api->register();
    }

    function custom_api_fields() {
        $api = new admin_js_app_api();
        $api->register_custom_fields();
    }

    function admin_menu() {
        $menu = new admin_js_app_page();
        $menu->register_menu();
    }

    function admin_scripts( $hook ) {
        if( $hook !== 'toplevel_page_admin-js-app' )
            return;
        $scripts = new admin_js_app_scripts();
        $scripts->load_scripts();
    }


}

$js_app = new admin_js_app();

/*
 * SAMPLE: Create CPT and Fake Content
 */
add_action( 'init', array( $js_app, 'create_cpt' ) );
add_action( 'init', array( $js_app, 'create_book_content' ) );

/*
 * ADD REST Support to Custom Post Type
 */
add_action( 'init', array( $js_app, 'rest_support' ) );

/*
 * CUSTOM API: Custom API Endpoints for our app
 */
//add_action( 'rest_api_init', array( $js_app, 'custom_api' ) );

/*
 * CUSTOM API: Custom API Fields
 */

add_action( 'rest_api_init', array( $js_app, 'custom_api_fields' ) );

/*
 * ADMIN PAGE: Register and Create the Admin Page
 */
add_action( 'admin_menu', array( $js_app, 'admin_menu' ) );

/*
 * ADMIN APP: Enqueue JavaScript for application
 */
add_action( 'admin_enqueue_scripts', array( $js_app, 'admin_scripts' ) );



?>