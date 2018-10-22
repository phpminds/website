FROM composer:1.7.2 as composer
FROM pavlakis/xdebug:2.6.1 AS xdebug
FROM phpminds/php:7.2.11

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

COPY --from=xdebug /usr/local/lib/php/extensions/no-debug-non-zts-20170718/xdebug.so /usr/local/lib/php/extensions/no-debug-non-zts-20170718/xdebug.so




