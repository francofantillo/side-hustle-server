#!/bin/bash

# Ensure PORT is set
export PORT=${PORT:-80}
echo "Using PORT: $PORT"

# Enable PHP error logging
echo "Configuring PHP error logging..."
echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/error-logging.ini
echo "display_errors = On" >> /usr/local/etc/php/conf.d/error-logging.ini
echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/error-logging.ini
echo "log_errors = On" >> /usr/local/etc/php/conf.d/error-logging.ini
echo "error_log = /dev/stderr" >> /usr/local/etc/php/conf.d/error-logging.ini

# Remove any existing default Nginx config that might conflict
echo "Cleaning up Nginx configurations..."
rm -f /etc/nginx/sites-enabled/default || true
rm -f /etc/nginx/conf.d/default.conf || true

# Generate Nginx config from template
echo "Generating Nginx configuration..."
envsubst '${PORT}' < /etc/nginx/conf.d/nginx.template.conf > /etc/nginx/conf.d/default.conf

# Make sure storage directories are writable
echo "Setting directory permissions..."
chmod -R 777 /var/www/storage || true
chmod -R 777 /var/www/bootstrap/cache || true

# Create test files for debugging
echo "<?php phpinfo(); ?>" > /var/www/public/info.php
echo "Creating simple test files..."
echo "<html><body><h1>Nginx Test Page</h1></body></html>" > /var/www/public/test.html

# Start Nginx - use the full path and foreground mode for better reliability
echo "Starting Nginx in foreground mode..."
nginx -g 'daemon off;' &
NGINX_PID=$!

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm

# Wait for any process to exit
wait $NGINX_PID