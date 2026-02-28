<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<article class="static-page">
    <header class="page-header">
        <h1><?php the_title(); ?></h1>
    </header>

    <div class="page-content">
        <?php the_content(); ?>
    </div>
</article>

<?php endwhile; ?>

<?php get_footer(); ?>
