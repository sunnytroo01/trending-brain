<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<nav class="site-nav">
    <div class="nav-inner">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo">
            Trending<span>Brain</span>
        </a>
        <ul class="nav-links">
            <li><a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>">Journal</a></li>
            <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
            <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
        </ul>
    </div>
</nav>
