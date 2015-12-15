<?php

class js_app_book_cpt {

    function create_cpt() {

        /*
         * Create Book CPT
         * Book CPT to be hidden from menu
         */

        $labels = array(
            'name'               => _x( 'Books', 'post type general name', 'js-app-plugin' ),
            'singular_name'      => _x( 'Book', 'post type singular name', 'js-app-plugin' ),
            'menu_name'          => _x( 'Books', 'admin menu', 'js-app-plugin' ),
            'name_admin_bar'     => _x( 'Book', 'add new on admin bar', 'js-app-plugin' ),
            'add_new'            => _x( 'Add New', 'book', 'js-app-plugin' ),
            'add_new_item'       => __( 'Add New Book', 'js-app-plugin' ),
            'new_item'           => __( 'New Book', 'js-app-plugin' ),
            'edit_item'          => __( 'Edit Book', 'js-app-plugin' ),
            'view_item'          => __( 'View Book', 'js-app-plugin' ),
            'all_items'          => __( 'All Books', 'js-app-plugin' ),
            'search_items'       => __( 'Search Books', 'js-app-plugin' ),
            'parent_item_colon'  => __( 'Parent Books:', 'js-app-plugin' ),
            'not_found'          => __( 'No books found.', 'js-app-plugin' ),
            'not_found_in_trash' => __( 'No books found in Trash.', 'js-app-plugin' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'js-app-plugin' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'book' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        register_post_type( 'book', $args );


    }

    function create_book_content() {

        /*
         * Check if fake content created, if not create 10 fake posts with 'book' post_type
         */

        if( get_option( 'created_fake_content' ) ) { return; }

        $i = 0;
        while( $i < 11 ) {

            $post = array( 'post_title' => 'Book ' . $i );
            $post['post_content'] = '<p>Testing some amazing content here...</p>';
            $post['post_type'] = 'book';
            $post['post_status'] = 'publish';

            $new_post = wp_insert_post( $post );
            $i++;
        }
        update_option( 'created_fake_content', true );

    }
}

?>