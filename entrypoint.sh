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

# Comprehensive debugging for asset directories
echo "DEBUGGING: Listing all directories in /var/www/public"
find /var/www/public -type d | sort

echo ""
echo "DEBUGGING: Checking for specific missing files reported in logs"
ls -la /var/www/public/admin/assets/vendor/fonts/boxicons.css 2>/dev/null || echo "boxicons.css not found"

echo ""
echo "DEBUGGING: Checking admin directory structure"
if [ -d "/var/www/public/admin" ]; then
    echo "✅ /var/www/public/admin directory exists"
    echo "Contents of /var/www/public/admin:"
    ls -la /var/www/public/admin
    
    if [ -d "/var/www/public/admin/assets" ]; then
        echo "✅ /var/www/public/admin/assets directory exists"
        echo "Contents of /var/www/public/admin/assets:"
        ls -la /var/www/public/admin/assets
        
        if [ -d "/var/www/public/admin/assets/vendor" ]; then
            echo "✅ /var/www/public/admin/assets/vendor directory exists"
            echo "Contents of /var/www/public/admin/assets/vendor:"
            ls -la /var/www/public/admin/assets/vendor
            
            if [ -d "/var/www/public/admin/assets/vendor/fonts" ]; then
                echo "✅ /var/www/public/admin/assets/vendor/fonts directory exists"
                echo "Contents of /var/www/public/admin/assets/vendor/fonts:"
                ls -la /var/www/public/admin/assets/vendor/fonts
            else
                echo "❌ /var/www/public/admin/assets/vendor/fonts directory is MISSING!"
            fi
        else
            echo "❌ /var/www/public/admin/assets/vendor directory is MISSING!"
        fi
    else
        echo "❌ /var/www/public/admin/assets directory is MISSING!"
    fi
else
    echo "❌ /var/www/public/admin directory is MISSING!"
fi

# Remove any existing default Nginx config that might conflict
echo "Cleaning up Nginx configurations..."
rm -f /etc/nginx/sites-enabled/default || true
rm -f /etc/nginx/conf.d/default.conf || true

# Generate Nginx config from template - with proper variable substitution
echo "Generating Nginx configuration with PORT=$PORT..."
envsubst '$PORT' < /templates/nginx.template.conf > /etc/nginx/conf.d/default.conf

# Debug - check if the config has the correct port
echo "Checking generated Nginx config:"
grep "listen" /etc/nginx/conf.d/default.conf

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

# Skip database migrations for now - uncomment when ready for database operations
# php artisan migrate --force || true

# Create storage link if it doesn't exist
php artisan storage:link || true

# Check if Vite manifest exists
echo "Checking for Vite manifest..."
if [ -f "public/build/manifest.json" ]; then
    echo "Vite manifest found."
else
    echo "Warning: Vite manifest not found. CSS may not load correctly."
fi

# Start Nginx with the correct port
echo "Starting Nginx on PORT=$PORT..."
nginx -g 'daemon off;' &
NGINX_PID=$!

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm -F &
PHP_FPM_PID=$!

# Wait for any process to exit
wait $NGINX_PID $PHP_FPM_PID