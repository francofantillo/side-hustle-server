# Use the official PHP 8.2 FPM image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    pkg-config \
    nginx \
    gettext-base

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Set working directory
WORKDIR /var/www

# Copy the entire application
COPY extracted/ .

# Make artisan executable
RUN chmod +x artisan

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Ensure proper permissions for public directory
RUN chmod -R 755 public

# Copy the NGINX template and entrypoint script
COPY nginx.template.conf /templates/nginx.template.conf
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose the port
EXPOSE ${PORT:-80}

# Set environment variables for Laravel
ENV APP_ENV=production
ENV LOG_CHANNEL=errorlog

# Run the entrypoint script
CMD ["/usr/local/bin/entrypoint.sh"]