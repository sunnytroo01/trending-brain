FROM wordpress:6-apache

# Copy our custom theme
COPY wordpress/wp-content/themes/trending-brain/ /var/www/html/wp-content/themes/trending-brain/

# Copy mu-plugins (local mail logger — auto-disabled in production)
COPY wordpress/wp-content/mu-plugins/ /var/www/html/wp-content/mu-plugins/

# Allow WordPress to write to wp-content
RUN chown -R www-data:www-data /var/www/html/wp-content

# Runtime script to configure Apache port from Railway's PORT env var
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["start.sh"]
