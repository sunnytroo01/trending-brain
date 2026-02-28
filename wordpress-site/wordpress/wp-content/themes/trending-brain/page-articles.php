<?php
/**
 * Template Name: All Articles
 */
get_header();
?>

<section class="archive-hero">
    <h1>All Articles</h1>
    <p>Every piece we've published — filtered to find exactly what you're looking for.</p>
</section>

<main class="archive-section">

    <?php
    $categories    = get_categories( [ 'hide_empty' => true ] );
    $current_cat   = isset( $_GET['category'] ) ? absint( $_GET['category'] ) : 0;
    $current_search = isset( $_GET['q'] ) ? sanitize_text_field( $_GET['q'] ) : '';
    $current_order  = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'date';
    $paged          = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
    $page_url       = get_permalink();

    // Build query args
    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'paged'          => $paged,
    ];

    if ( $current_cat ) {
        $args['cat'] = $current_cat;
    }

    if ( $current_search ) {
        $args['s'] = $current_search;
    }

    if ( $current_order === 'oldest' ) {
        $args['orderby'] = 'date';
        $args['order']   = 'ASC';
    } elseif ( $current_order === 'title' ) {
        $args['orderby'] = 'title';
        $args['order']   = 'ASC';
    }

    $articles = new WP_Query( $args );
    ?>

    <div class="archive-toolbar">
        <div class="filter-group">
            <span class="filter-label">Filter</span>
            <div class="filter-pills">
                <a href="<?php echo esc_url( $page_url ); ?>"
                   class="filter-pill <?php echo ! $current_cat ? 'active' : ''; ?>">
                    All
                </a>
                <?php foreach ( $categories as $cat ) : ?>
                    <a href="<?php echo esc_url( add_query_arg( 'category', $cat->term_id, $page_url ) ); ?>"
                       class="filter-pill <?php echo ( $current_cat == $cat->term_id ) ? 'active' : ''; ?>">
                        <?php echo esc_html( $cat->name ); ?>
                        <span class="pill-count"><?php echo $cat->count; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="sort-dropdown">
            <span class="filter-label">Sort</span>
            <select onchange="window.location.href=this.value">
                <?php $sort_base = $current_cat ? add_query_arg( 'category', $current_cat, $page_url ) : $page_url; ?>
                <option value="<?php echo esc_url( remove_query_arg( 'orderby', $sort_base ) ); ?>" <?php selected( $current_order, 'date' ); ?>>Newest</option>
                <option value="<?php echo esc_url( add_query_arg( 'orderby', 'oldest', $sort_base ) ); ?>" <?php selected( $current_order, 'oldest' ); ?>>Oldest</option>
            </select>
        </div>

        <form class="archive-search" method="get" action="<?php echo esc_url( $page_url ); ?>">
            <?php if ( $current_cat ) : ?>
                <input type="hidden" name="category" value="<?php echo $current_cat; ?>" />
            <?php endif; ?>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z" />
            </svg>
            <input type="text" name="q" placeholder="Search articles..." value="<?php echo esc_attr( $current_search ); ?>" />
        </form>
    </div>

    <div class="archive-results-bar">
        <span class="results-count"><?php echo $articles->found_posts; ?> article<?php echo $articles->found_posts != 1 ? 's' : ''; ?></span>
        <?php if ( $current_cat || $current_search ) : ?>
            <a href="<?php echo esc_url( $page_url ); ?>" class="clear-filter">Clear filters &times;</a>
        <?php endif; ?>
    </div>

    <?php if ( $articles->have_posts() ) : ?>
    <div class="archive-grid">
        <?php while ( $articles->have_posts() ) : $articles->the_post(); ?>
        <a href="<?php the_permalink(); ?>" class="archive-card">
            <div class="archive-card-image">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'medium_large' ); ?>
                <?php else : ?>
                    <div style="width:100%;height:100%;background:var(--light-gray);"></div>
                <?php endif; ?>
            </div>
            <div class="archive-card-body">
                <div class="post-card-meta">
                    <?php
                    $cats = get_the_category();
                    if ( $cats ) :
                    ?>
                        <span class="post-card-category"><?php echo esc_html( $cats[0]->name ); ?></span>
                    <?php endif; ?>
                    <span class="post-card-date"><?php echo get_the_date( 'M j, Y' ); ?></span>
                </div>
                <h3><?php the_title(); ?></h3>
                <p><?php echo get_the_excerpt(); ?></p>
            </div>
        </a>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    </div>

    <?php
    $total_pages = $articles->max_num_pages;
    if ( $total_pages > 1 ) :
    ?>
    <div class="pagination">
        <?php
        echo paginate_links( [
            'total'     => $total_pages,
            'current'   => $paged,
            'prev_text' => '&larr; Prev',
            'next_text' => 'Next &rarr;',
        ] );
        ?>
    </div>
    <?php endif; ?>

    <?php else : ?>
    <div class="archive-empty">
        <h2>No articles found</h2>
        <p>Try adjusting your filters or search terms.</p>
        <a href="<?php echo esc_url( $page_url ); ?>" class="view-all-btn view-all-btn--large">
            View All Articles
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
        </a>
    </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
