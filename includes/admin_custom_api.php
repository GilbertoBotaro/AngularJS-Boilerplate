<?php

class admin_js_app_api {

    function register() {
        register_rest_route( 'js-admin-app', '/books', array(
            'methods'  => 'GET',
            'callback' => array( $this, 'get_books' )
        ));
        register_rest_route( 'js-admin-app', '/books/(?P<id>\d+)', array(
            'methods'  => 'GET',
            'callback' => array( $this, 'get_books' )
        ));
    }

    function get_books( $data ) {

        if( isset( $data['id'] ) ) {
            $books = get_post( $data['id'] );
        } else {
            $args = array( 'post_type' => 'book', 'posts_per_page' => -1 );
            $books = new WP_Query( $args );
            $books = $books->posts;
        }

        return new WP_REST_Response( $books, 200 );
    }

}

?>