FROM php:fpm-alpine

RUN apk add --no-cache zlib-dev && docker-php-ext-install zip \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && wget -O /usr/local/bin/composer https://getcomposer.org/composer.phar \
    && chmod +x /usr/local/bin/composer \
    && wget -O /usr/local/bin/phpunit https://phar.phpunit.de/phpunit-7.phar \
    && chmod +x /usr/local/bin/phpunit

WORKDIR /app
