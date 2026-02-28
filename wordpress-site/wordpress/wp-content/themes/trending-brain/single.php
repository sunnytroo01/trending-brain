<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<article class="single-post">
    <header class="single-header">
        <div class="post-card-meta">
            <?php
            $categories = get_the_category();
            if ( $categories ) :
            ?>
                <span class="post-card-category"><?php echo esc_html( $categories[0]->name ); ?></span>
            <?php endif; ?>
            <span class="post-card-date"><?php echo get_the_date( 'F j, Y' ); ?></span>
        </div>
        <h1><?php the_title(); ?></h1>
        <?php if ( has_excerpt() ) : ?>
            <p class="excerpt"><?php echo get_the_excerpt(); ?></p>
        <?php endif; ?>
    </header>

    <?php if ( has_post_thumbnail() ) : ?>
    <div class="single-featured-image">
        <?php the_post_thumbnail( 'full' ); ?>
    </div>
    <?php endif; ?>

    <div class="single-content">
        <?php the_content(); ?>
    </div>

    <nav class="post-nav">
        <div>
            <?php
            $prev = get_previous_post();
            if ( $prev ) :
            ?>
                <a href="<?php echo get_permalink( $prev ); ?>">&larr; <?php echo esc_html( get_the_title( $prev ) ); ?></a>
            <?php endif; ?>
        </div>
        <div>
            <?php
            $next = get_next_post();
            if ( $next ) :
            ?>
                <a href="<?php echo get_permalink( $next ); ?>"><?php echo esc_html( get_the_title( $next ) ); ?> &rarr;</a>
            <?php endif; ?>
        </div>
    </nav>
</article>

<?php endwhile; ?>

<?php get_footer(); ?>
