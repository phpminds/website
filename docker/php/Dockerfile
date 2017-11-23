FROM php:7.1-fpm

RUN apt-get update && apt-get install -y \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql
RUN pecl install xdebug-2.5.5
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
EXPOSE 9009
CMD ["php-fpm","-F"]

