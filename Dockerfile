FROM node:22-bookworm-slim AS assets

WORKDIR /app

COPY package.json ./
RUN npm install

COPY resources/ ./resources/
COPY vite.config.js ./
COPY public/ ./public/
RUN npm run build

FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public \
    PORT=10000

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev unzip \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql pdo_pgsql \
    && a2enmod headers rewrite \
    && sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf \
    && sed -ri '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .
COPY --from=assets /app/public/build/ ./public/build/

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-progress \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 10000

CMD ["sh", "-c", "port=\"${PORT:-10000}\"; sed -ri \"s/Listen 80/Listen ${port}/; s/<VirtualHost \\*:80>/<VirtualHost *:${port}>/\" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf; exec apache2-foreground"]