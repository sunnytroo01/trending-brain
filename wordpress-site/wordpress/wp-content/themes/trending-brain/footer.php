<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-left">
            <div class="footer-brand">Trending<span>Brain</span></div>
            <p class="footer-text">&copy; <?php echo date( 'Y' ); ?> Trending Brain. All rights reserved.</p>
        </div>
        <div class="footer-right">
            <ul class="footer-links">
                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                <li><a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>">Journal</a></li>
                <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
                <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
            </ul>
            <ul class="footer-links footer-legal">
                <li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a></li>
                <li><a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>">Terms of Service</a></li>
            </ul>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
