# Use the official PHP 8.1 FPM image as the base image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    curl

# Install Node.js and npm
# RUN apt-get update && apt-get upgrade -y && \
# apt-get install -y nodejs \
# npm

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    npm   

# Verify Node.js and npm installation
RUN node -v
RUN npm -v || echo "npm installation failed"

# Install composer:
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Verify Composer installation
RUN composer --version

# Install additional PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install PHP dependencies
RUN composer install

# Install Node.js dependencies
RUN npm install

# Build assets
RUN npm run build

# Set permissions
RUN chmod -R 777 storage bootstrap/cache

# Start PHP-FPM
CMD ["php-fpm"]