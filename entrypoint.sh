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

# Laravel application setup
echo "Setting up Laravel application..."

# Clear caches
echo "Clearing application caches..."
php artisan config:clear
php artisan cache:clear

# =========================================================
# WARNING: DEVELOPMENT/STAGING ENVIRONMENT ONLY
# The following commands will reset the database and seed it with fresh data.
# 
# IMPORTANT: REMOVE BEFORE PRODUCTION DEPLOYMENT
# In production:
# 1. Replace 'migrate:fresh' with just 'migrate'
# 2. Remove all seed commands
# 3. Or implement conditional seeding that only runs on first deployment
# =========================================================

# Set up database - add the --force flag to run in production
echo "Setting up database..."
php artisan migrate:fresh --force

# Seed initial data - add the --force flag to run in production
echo "Seeding database with initial data..."
php artisan db:seed --class=RolesTableSeeder --force
php artisan db:seed --class=UsersTableSeeder --force
php artisan db:seed --class=PlanSeeder --force
# =========================================================

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