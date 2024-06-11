FROM php:8-fpm-alpine

# instalar dependencias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# instalar configuración de php
RUN ln -s /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
# RUN ln -s /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# establecer volumen
RUN rm -rf /var/www/html
