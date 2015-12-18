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
        register_rest_route( 'js-admin-app', '/books/(?P<id>\d+)', array(
            'methods'  => 'POST',
            'callback' => array( $this, 'save_book' )
        ));
        register_rest_route( 'js-admin-app', '/books/(?P<id>\d+)', array(
            'methods'  => 'DELETE',
            'callback' => array( $this, 'delete_book' )
        ));
    }

    function get_books( $data ) {
        global $post;
        /*
         * Check if id set
         */
        if( isset( $data['id'] ) ) {
            /*
             * Get Single Post & add Post Meta
             */
            $books = get_post( $data['id'] );
            $books->meta = get_post_meta( $books->ID );
        } else {
            /*
             * Get All Posts
             */
            $args = array( 'post_type' => 'book', 'posts_per_page' => -1 );
            $allbooks = new WP_Query( $args );
            $books = array();
            /*
             * If no posts, return error, else loop and add Post Meta
             */
            if( $allbooks->have_posts() ) : while( $allbooks->have_posts() ) : $allbooks->the_post();
                $post->meta = get_post_meta( $post->ID );
                array_push( $books, $post );
            endwhile;
            else:
                return new WP_Error( 'noBooks', __( 'No Books Found', 'js-app-plugin' ) );
            endif;

        }
        return new WP_REST_Response( $books, 200 );
    }

    function save_book( WP_REST_Request $data ) {

        /*
         * Check if ID set & set $post
         */
        if( !$data['ID'] )
            return new WP_Error( 'noID', __( 'No Book ID', 'js-app-plugin' ) );
        $post = $data->get_params();
        $meta = $post['meta'];
        unset( $post[0] );
        /*
         * Save Post
         */
        $book['save'] = wp_update_post( $post, true );
        if( is_wp_error( $book['save'] ) )
            return new WP_Error( 'saveError', __( $book['save']->get_error_messages(), 'js-app-plugin' ) );

        /*
         * Save Post Meta
         */
        foreach( $meta as $key => $value ) {
            $book['meta-' . $key] = update_post_meta( $post['ID'], $key, $value[0] );
        }

        /*
         * Get post and return
         */
        $book['post'] = get_post( $post['ID'] );
        $book['post']->meta = get_post_meta( $post['ID'] );

        return new WP_REST_Response( $book, 200 );
    }

    function delete_book( $data ) {

        $post_id = $data['id'];
        $delete = wp_delete_post( $post_id, true );

        if( $delete !== false ) {
            $book = array( 'post_id' => $data['id'] , 'delete' => true );
            return new WP_REST_Response( $book, 200 );
        } else {
            return new WP_Error( 'delete_error', __( 'Error Deleting Post' . $data['id'], 'js-app-plugin' ) );
        }

    }

    function register_custom_fields() {

        register_api_field( 'book',
            'meta',
            array(
                'get_callback'    => array( $this, 'get_book_meta' ),
                'update_callback' => array( $this, 'update_book_meta' ),
                'schema'          => null,
            )
        );

    }

    function get_book_meta( $object, $field_name, $request ) {

        $return = array(
            'isbn' => get_post_meta( $object['id'], 'isbn', true ),
            'price' => get_post_meta( $object['id'], 'price', true )
        );

        return $return;

    }

    function update_book_meta( $value, $object, $field_name ) {

        foreach( $value as $key => $new_value ) {
            update_post_meta( $object->ID, $key, $new_value );
        }
        return;
    }

}

?>