FROM php:fpm-alpine

RUN wget -O /usr/local/bin/composer https://getcomposer.org/composer.phar \
    && chmod +x /usr/local/bin/composer \
    && wget -O /usr/local/bin/phpunit https://phar.phpunit.de/phpunit-7.phar \
    && chmod +x /usr/local/bin/phpunit

WORKDIR /app
