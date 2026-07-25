FROM php:8.4-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    default-mysql-client

RUN docker-php-ext-install \
    pdo \
    pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

CMD php artisan serve --host=0.0.0.0 --port=$PORT