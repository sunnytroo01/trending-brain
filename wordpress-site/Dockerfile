FROM wordpress:6-apache

# Copy our custom theme
COPY wordpress/wp-content/themes/trending-brain/ /var/www/html/wp-content/themes/trending-brain/

# Copy mu-plugins (local mail logger — auto-disabled in production)
COPY wordpress/wp-content/mu-plugins/ /var/www/html/wp-content/mu-plugins/

# HTTPS reverse proxy detection — Railway terminates SSL at its proxy,
# so PHP needs to know the original request was HTTPS
RUN echo '<?php if(isset($_SERVER["HTTP_X_FORWARDED_PROTO"])&&$_SERVER["HTTP_X_FORWARDED_PROTO"]==="https"){$_SERVER["HTTPS"]="on";}' \
    > /usr/src/https-proxy.php \
    && echo "auto_prepend_file=/usr/src/https-proxy.php" \
    > /usr/local/etc/php/conf.d/https-proxy.ini

# Allow WordPress to write to wp-content
RUN chown -R www-data:www-data /var/www/html/wp-content

# Runtime script to configure Apache port and domain
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["start.sh"]
