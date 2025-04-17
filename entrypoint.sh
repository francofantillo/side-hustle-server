#!/bin/bash

# Get the PORT from Heroku's environment variable
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

# Generate Nginx config from template - explicitly substitute the PORT variable
echo "Generating Nginx configuration with PORT=$PORT..."
envsubst '$PORT' < /etc/nginx/conf.d/nginx.template.conf > /etc/nginx/conf.d/default.conf

# Debug - check if the config has the correct port
echo "Checking generated Nginx config:"
cat /etc/nginx/conf.d/default.conf

# Make sure storage directories are writable
echo "Setting directory permissions..."
chmod -R 777 storage || true
chmod -R 777 bootstrap/cache || true

# Laravel application setup
echo "Setting up Laravel application..."

# Clear caches
echo "Clearing application caches..."
php artisan config:clear || true
php artisan cache:clear || true

# Run migrations only in production, no fresh migrations to avoid data loss
echo "Running database migrations..."
php artisan migrate --force || true

# Create storage link if it doesn't exist
php artisan storage:link || true

# Start Nginx with the correct port
echo "Starting Nginx on PORT=$PORT..."
nginx -g 'daemon off;' &
NGINX_PID=$!

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm -F

# Wait for any process to exit
wait $NGINX_PID