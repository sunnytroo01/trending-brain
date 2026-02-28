<?php
/**
 * Trending Brain theme functions.
 */

// Theme setup
add_action( 'after_setup_theme', function () {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
    set_post_thumbnail_size( 1200, 750, true );

    register_nav_menus( [
        'primary' => 'Primary Menu',
    ] );
});

// Enqueue Google Fonts (Inter)
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'trending-brain-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap',
        [],
        null
    );
    wp_enqueue_style( 'trending-brain-style', get_stylesheet_uri(), [ 'trending-brain-fonts' ], '1.0.0' );
});

// Custom excerpt length
add_filter( 'excerpt_length', function () {
    return 28;
});

add_filter( 'excerpt_more', function () {
    return '...';
});

// Handle custom sort on archive/category pages
add_action( 'pre_get_posts', function ( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( $query->is_archive() || $query->is_category() || $query->is_search() ) {
        $orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : '';

        if ( $orderby === 'oldest' ) {
            $query->set( 'order', 'ASC' );
            $query->set( 'orderby', 'date' );
        } elseif ( $orderby === 'title' ) {
            $query->set( 'orderby', 'title' );
            $query->set( 'order', 'ASC' );
        }
    }
});
