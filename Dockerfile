# ---- フロントエンド資産のビルド ----
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# ---- PHPアプリ本体 ----
FROM php:8.3-cli
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libsqlite3-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite pdo_pgsql mbstring exif pcntl bcmath gd zip curl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .
COPY --from=frontend /app/public/build ./public/build

RUN composer install --optimize-autoloader --no-dev --no-interaction
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000
CMD sh -c "php artisan migrate --force; php artisan storage:link; php artisan serve --host 0.0.0.0 --port ${PORT:-10000}"




