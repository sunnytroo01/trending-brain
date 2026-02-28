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

    // Create sample posts if none exist
    if ( ! get_posts( [ 'numberposts' => 1 ] ) ) {
        require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
        $cat_id = wp_create_category( 'Artificial Intelligence' );

        $posts = [
            [
                'title'   => 'The Rise of Multimodal AI: When Machines See, Hear, and Understand',
                'excerpt' => 'The latest AI models process text, images, and audio simultaneously — fundamentally changing how machines understand the world.',
                'content' => '<p>AI is no longer confined to text. The latest multimodal models interpret images, audio, video, and text simultaneously — mirroring how humans perceive the world.</p><h2>Why Multimodality Matters</h2><p>The real world is a continuous stream of sensory data. By training on multiple modalities, researchers build systems that reason holistically — from medical diagnostics analyzing X-rays alongside patient notes, to autonomous vehicles fusing camera and lidar data.</p><h2>The Technical Challenge</h2><p>Modern approaches use contrastive learning and cross-attention mechanisms to align different modalities, ensuring a model\'s understanding of "cat" in text corresponds to its visual representation.</p>',
            ],
            [
                'title'   => 'Understanding Transformer Architecture: The Engine Behind Modern AI',
                'excerpt' => 'The Transformer architecture revolutionized AI. Here\'s how self-attention works and why it powers everything from chatbots to image generators.',
                'content' => '<p>Every major AI breakthrough traces back to the Transformer architecture, introduced in "Attention Is All You Need" (2017).</p><h2>The Attention Mechanism</h2><p>Self-attention lets the model weigh importance of different input parts simultaneously. Unlike sequential approaches, it considers entire context at once — understanding "bank" differs in "river bank" vs "bank account."</p><h2>Beyond Language</h2><p>Vision Transformers treat image patches as tokens. Audio, video, and protein structures use the same architecture. The pattern is remarkably universal.</p>',
            ],
            [
                'title'   => 'AI Ethics in Practice: Navigating Bias, Privacy, and Accountability',
                'excerpt' => 'From algorithmic bias to data privacy, the ethical challenges of AI are no longer theoretical.',
                'content' => '<p>As AI deploys in hiring, healthcare, and finance, ethics has moved from academic debate to urgent practical concern.</p><h2>The Bias Problem</h2><p>Models learn from historical data that reflects historical biases. A hiring algorithm may perpetuate discrimination. Addressing this requires diverse data, careful evaluation, and ongoing monitoring.</p><h2>Accountability</h2><p>When an AI makes a harmful decision, who is responsible? The EU AI Act creates clear accountability lines based on application risk level.</p>',
            ],
            [
                'title'   => 'Retrieval-Augmented Generation: Giving AI Access to Real-Time Knowledge',
                'excerpt' => 'RAG connects AI models to real-time data, reducing hallucination and enabling enterprise-grade applications.',
                'content' => '<p>Language models only know their training data. RAG solves this by connecting models to external knowledge at inference time.</p><h2>How RAG Works</h2><p>The system searches a knowledge base for relevant information, then feeds that context to the model. Responses are grounded in retrieved facts, dramatically reducing hallucination.</p><h2>Enterprise Impact</h2><p>RAG is the standard for enterprise AI — connecting models to internal docs and databases without fine-tuning, keeping proprietary data secure.</p>',
            ],
            [
                'title'   => 'The Future of AI Agents: From Chatbots to Autonomous Problem Solvers',
                'excerpt' => 'AI agents go beyond conversation — they plan, use tools, and execute multi-step tasks autonomously.',
                'content' => '<p>The next frontier isn\'t just generating text — it\'s taking action. AI agents combine language models with tools: browsing, coding, managing files, calling APIs.</p><h2>Current Capabilities</h2><p>Today\'s agents research topics across sources, write and debug software, analyze datasets, and manage workflows — combining multiple skills in sequence.</p><h2>The Road Ahead</h2><p>As agent architectures mature, we\'re moving from chatbots to genuine digital assistants that manage projects and collaborate on complex work.</p>',
            ],
        ];

        foreach ( $posts as $i => $p ) {
            wp_insert_post( [
                'post_type'     => 'post',
                'post_status'   => 'publish',
                'post_title'    => $p['title'],
                'post_content'  => $p['content'],
                'post_excerpt'  => $p['excerpt'],
                'post_category' => [ $cat_id ],
                'post_date'     => date( 'Y-m-d H:i:s', strtotime( "-{$i} days" ) ),
            ] );
        }
    }
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
