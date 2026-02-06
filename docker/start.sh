#!/bin/sh
set -e

echo "==> Waiting for MySQL to be ready..."
while ! nc -z mysql 3306; do
  sleep 1
done
echo "==> MySQL is ready!"

echo "==> Clearing config cache..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Seeding database..."
php artisan db:seed --force

echo "==> Caching configuration for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Starting PHP-FPM..."
exec php-fpm

