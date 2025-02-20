FROM composer:lts AS composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

COPY . .


FROM dunglas/frankenphp:1.0-php8.3

WORKDIR /app

COPY --from=composer /app /app

EXPOSE 80

CMD ["frankenphp", "php-server"]
