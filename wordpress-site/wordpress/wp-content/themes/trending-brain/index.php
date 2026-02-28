<?php get_header(); ?>

<section class="hero">
    <span class="hero-label">AI Research & Insights</span>
    <h1>Trending<br>Brain</h1>
    <p>Exploring the frontier of artificial intelligence, machine learning, and the ideas shaping tomorrow.</p>
</section>

<main class="posts-section">
    <div class="section-header">
        <h2>Latest Articles</h2>
        <a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>" class="view-all-btn">
            View All Articles
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        </a>
    </div>

    <?php
    $latest = new WP_Query( [
        'posts_per_page' => 5,
        'post_status'    => 'publish',
    ] );
    ?>

    <?php if ( $latest->have_posts() ) : ?>
    <div class="post-stack">
        <?php while ( $latest->have_posts() ) : $latest->the_post(); ?>
        <a href="<?php the_permalink(); ?>" class="post-card">
            <div class="post-card-image">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'large' ); ?>
                <?php else : ?>
                    <div style="width:100%;height:100%;background:var(--light-gray);"></div>
                <?php endif; ?>
            </div>
            <div class="post-card-content">
                <div class="post-card-meta">
                    <?php
                    $categories = get_the_category();
                    if ( $categories ) :
                    ?>
                        <span class="post-card-category"><?php echo esc_html( $categories[0]->name ); ?></span>
                    <?php endif; ?>
                    <span class="post-card-date"><?php echo get_the_date( 'M j, Y' ); ?></span>
                </div>
                <h3><?php the_title(); ?></h3>
                <p><?php echo get_the_excerpt(); ?></p>
                <span class="read-more">
                    Read Article
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </span>
            </div>
        </a>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    </div>

    <div class="view-all-bottom">
        <a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>" class="view-all-btn view-all-btn--large">
            Browse All Articles
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        </a>
    </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
