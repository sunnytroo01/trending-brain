#!/bin/bash
set -e

# Railway provides a PORT env var — update Apache to listen on it
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/000-default.conf

# Run the official WordPress entrypoint (sets up wp-config.php from env vars)
exec docker-entrypoint.sh apache2-foreground
