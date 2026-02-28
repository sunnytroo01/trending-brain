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

// ── Theme Setup: auto-create pages, posts, permalinks (version-gated) ──
add_action( 'init', function () {
    $current_version = '1.1';
    if ( get_option( 'tb_setup_version' ) === $current_version ) return;
    tb_theme_activate();
    update_option( 'tb_setup_version', $current_version );
});
add_action( 'after_switch_theme', 'tb_theme_activate' );
function tb_theme_activate() {
    // Set pretty permalinks
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure( '/%postname%/' );
    $wp_rewrite->flush_rules();

    // Helper: create page if it doesn't already exist
    $create_page = function ( $slug, $title, $content = '', $template = '' ) {
        if ( get_page_by_path( $slug ) ) return;
        $id = wp_insert_post( [
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_name'    => $slug,
            'post_title'   => $title,
            'post_content' => $content,
        ] );
        if ( $template && ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_wp_page_template', $template );
        }
    };

    $create_page( 'articles', 'Articles', '', 'page-articles.php' );

    $create_page( 'about', 'About',
        '<p>Trending Brain is a digital journal exploring the frontiers of artificial intelligence, machine learning, and the technology shaping our future.</p>' .
        '<p>We break down complex AI concepts into clear, engaging stories — from neural networks and natural language processing to the ethics of autonomous systems.</p>' .
        '<h2>Our Mission</h2>' .
        '<p>To make cutting-edge AI accessible to everyone. We believe understanding technology is the first step to shaping it responsibly.</p>' .
        '<h2>What We Cover</h2>' .
        '<ul><li><strong>Deep Dives</strong> — Long-form analysis of breakthroughs</li>' .
        '<li><strong>Explainers</strong> — Complex ideas made simple</li>' .
        '<li><strong>Opinion</strong> — Where AI meets ethics and society</li>' .
        '<li><strong>Tools &amp; Tutorials</strong> — Hands-on guides for builders</li></ul>'
    );

    $create_page( 'contact', 'Contact', '', 'page-contact.php' );

    $create_page( 'privacy-policy', 'Privacy Policy',
        '<h2>Introduction</h2>' .
        '<p>Trending Brain respects your privacy. This policy explains how we collect, use, and protect information when you visit trendingbrain.com.</p>' .
        '<h2>Information We Collect</h2>' .
        '<p><strong>Contact Form:</strong> Name, email, and message — solely to respond to your inquiry.</p>' .
        '<p><strong>Cookies:</strong> WordPress may set essential cookies. We do not use advertising or tracking cookies.</p>' .
        '<h2>How We Use Your Information</h2>' .
        '<ul><li>To respond to contact form submissions</li><li>To improve site content and experience</li><li>To ensure security</li></ul>' .
        '<h2>Data Sharing</h2>' .
        '<p>We do not sell, trade, or share personal information with third parties, except as required by law.</p>' .
        '<h2>Your Rights</h2>' .
        '<p>You may request deletion of any personal data by contacting us.</p>' .
        '<p><em>Last updated: 2026</em></p>'
    );

    $create_page( 'terms-of-service', 'Terms of Service',
        '<h2>Acceptance of Terms</h2>' .
        '<p>By using trendingbrain.com, you agree to these terms. If you do not agree, please do not use the site.</p>' .
        '<h2>Use of Content</h2>' .
        '<p>All content is property of Trending Brain unless otherwise stated. Reproducing full articles without permission is prohibited.</p>' .
        '<h2>User Conduct</h2>' .
        '<p>You agree not to submit false information, spam, or malicious content, or attempt to interfere with site functionality.</p>' .
        '<h2>Disclaimer</h2>' .
        '<p>Content is provided for informational purposes. We make no warranties about completeness or accuracy.</p>' .
        '<h2>Limitation of Liability</h2>' .
        '<p>Trending Brain shall not be liable for damages arising from use of this site.</p>' .
        '<h2>Changes</h2>' .
        '<p>We may modify these terms at any time. Continued use constitutes acceptance.</p>' .
        '<p><em>Last updated: 2026</em></p>'
    );

}

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
