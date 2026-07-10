#!/usr/bin/env sh
set -e

cd /var/www/html

if [ ! -f vendor/autoload.php ]; then
  if [ "${APP_ENV}" = "production" ]; then
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
  else
    composer install --no-interaction --prefer-dist --optimize-autoloader
  fi
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

if [ "${APP_ENV}" = "production" ]; then
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
fi

exec "$@"
