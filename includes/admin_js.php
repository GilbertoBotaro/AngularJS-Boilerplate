<?php

class admin_js_app_scripts {

    function load_scripts() {
        wp_enqueue_script( 'admin-app', JS_APP_URL . '/build/js/admin-app-scripts.js', array( 'jquery' ), PLUGIN_VERSION, false );
        wp_enqueue_style( 'admin-app-styles', JS_APP_URL . '/build/css/styles.css', array(), PLUGIN_VERSION, 'all' );

        wp_localize_script( 'admin-app', 'admin_app_local',
            array(
                'api_url' => get_rest_url(),
                'template_directory' => JS_APP_URL . 'templates'
            )
        );
    }

}