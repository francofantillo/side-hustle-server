<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## How to Run This Project

Here's how to run this Laravel project based on the Docker configuration:

1. First, make sure Docker is installed on your system.

2. Copy the environment file:
```sh
cp .env.example .env
```

3. Build and start the Docker containers:
```sh
docker-compose up -d --build
```

4. The docker-compose.yml defines 3 services:
- app - PHP application (Laravel)  
- webserver - Nginx server running on port 8000
- db - MySQL database running on port 3306

5. After containers are running, install dependencies and set up Laravel:
```sh
# Install PHP dependencies
docker-compose exec app composer install

# Generate app key
docker-compose exec app php artisan key:generate

# Run database migrations
docker-compose exec app php artisan migrate

# Install frontend dependencies and build assets
docker-compose exec app npm install
docker-compose exec app npm run build
```

6. The application should now be accessible at:
- Website: http://localhost:8000
- Database: localhost:3306
  - Username: root
  - Password: secret
  - Database: laravel

7. To stop the project:
```sh
docker-compose down
```

The initial database structure is defined in side_hustle_DB.sql which will be automatically imported when the containers start up.
