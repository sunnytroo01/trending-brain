#!/bin/bash
set -e

# Railway provides a PORT env var — update Apache to listen on it
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf

# Auto-configure WordPress URLs from DOMAIN env var (set on Railway)
if [ -n "$DOMAIN" ]; then
    export WORDPRESS_CONFIG_EXTRA="${WORDPRESS_CONFIG_EXTRA:-}
define('WP_HOME', 'https://${DOMAIN}');
define('WP_SITEURL', 'https://${DOMAIN}');
define('FORCE_SSL_ADMIN', true);"
fi

# Run the official WordPress entrypoint (creates wp-config.php from env vars)
exec docker-entrypoint.sh apache2-foreground
