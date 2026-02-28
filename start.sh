#!/bin/bash
set -e

PORT="${PORT:-80}"

# Overwrite Apache port config (cleaner than sed replacement)
echo "Listen $PORT" > /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:$PORT>/" /etc/apache2/sites-available/000-default.conf

# Fix MPM conflict — ensure only prefork is loaded
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Auto-configure WordPress URLs from DOMAIN env var (set on Railway)
if [ -n "$DOMAIN" ]; then
    export WORDPRESS_CONFIG_EXTRA="${WORDPRESS_CONFIG_EXTRA:-}
define('WP_HOME', 'https://${DOMAIN}');
define('WP_SITEURL', 'https://${DOMAIN}');
define('FORCE_SSL_ADMIN', true);"
fi

# Ensure uploads directory exists and is writable (for Railway volume mount)
mkdir -p /var/www/html/wp-content/uploads
chown -R www-data:www-data /var/www/html/wp-content/uploads

# Run the official WordPress entrypoint (creates wp-config.php from env vars)
exec docker-entrypoint.sh apache2-foreground
