#!/bin/sh
set -e

echo "==> Waiting for MySQL to be ready..."
while ! nc -z mysql 3306; do
  sleep 1
done
echo "==> MySQL is ready!"

echo "==> Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Caching configuration for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Starting PHP-FPM..."
exec php-fpm
