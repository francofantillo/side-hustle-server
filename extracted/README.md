<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## How to Run This Project

This Laravel project uses Docker for local development. Here's how to run it:

1. First, make sure Docker is installed on your system.

2. Copy the environment file:
```sh
cp .env.example .env
```

3. Build and start the Docker containers:
```sh
docker-compose up -d --build
```

Note: The Dockerfile automatically handles:
- Installing PHP dependencies (Composer)
- Installing Node.js dependencies (npm)
- Building frontend assets
- Setting required permissions
- Setting up entrypoint script for database initialization

4. After containers are running, the entrypoint script will automatically:
```sh
# Clear application caches
php artisan config:clear
php artisan cache:clear

# Set up database
php artisan migrate:fresh

# Seed initial data
php artisan db:seed --class=RolesTableSeeder    # Creates user roles
php artisan db:seed --class=UsersTableSeeder    # Creates admin user
php artisan db:seed --class=PlanSeeder         # Sets up subscription plans
```

If you need to run these commands manually:
```sh
# Generate app key (if needed)
docker-compose exec app php artisan key:generate

# Manually run migrations and seeders
docker-compose exec app php artisan migrate:fresh
docker-compose exec app php artisan db:seed --class=RolesTableSeeder
docker-compose exec app php artisan db:seed --class=UsersTableSeeder
docker-compose exec app php artisan db:seed --class=PlanSeeder
```

5. The application should now be accessible at:
- Website: http://localhost:8000
- Database: localhost:3306
  - Username: root
  - Password: secret
  - Database: laravel

6. To stop the project:
```sh
docker-compose down
```

7. For development, if you make changes to frontend assets:
```sh
docker-compose exec app npm run dev
```

The initial database structure is defined in side_hustle_DB.sql which will be automatically imported when the containers start up.

## Checking Service Status

To verify services are running:
```sh
# List all running containers
docker ps

# Check service status
docker-compose ps

# View logs
docker-compose logs app       # PHP-FPM logs
docker-compose logs webserver # Nginx logs
docker-compose logs db       # MySQL logs
```
