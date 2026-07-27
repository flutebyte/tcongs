FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
        git unzip libicu-dev libzip-dev libexif-dev libpng-dev libjpeg-dev libfreetype6-dev libwebp-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp \
    && docker-php-ext-install -j"$(nproc)" intl zip exif gd pdo_mysql bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan storage:link || true; \
    php artisan migrate --force; \
    (while true; do php artisan queue:work --queue=default --tries=3 --max-time=3600 --sleep=3; sleep 2; done &) ; \
    php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
