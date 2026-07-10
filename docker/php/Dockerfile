FROM php:8.3-fpm-alpine AS base

RUN apk add --no-cache \
    bash \
    curl \
    git \
    icu-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    unzip \
    zip \
    && docker-php-ext-install \
    bcmath \
    exif \
    intl \
    mbstring \
    opcache \
    pdo_mysql \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .
COPY docker/php/entrypoint.sh /usr/local/bin/brill-entrypoint
COPY docker/php/php.ini /usr/local/etc/php/conf.d/brill.ini

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

RUN chmod +x /usr/local/bin/brill-entrypoint \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

ENTRYPOINT ["brill-entrypoint"]
CMD ["php-fpm"]
