FROM php:7.1-fpm-alpine

# Instalamos extensiones necesarias para Symfony viejo (intl, pdo_mysql, gd)
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    libpng-dev \
    zip \
    && docker-php-ext-install \
    intl \
    pdo_mysql \
    zip \
    gd

# Instalamos Composer (versión compatible con PHP 7.1)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# COPIA DEL PROYECTO: Metemos tu carpeta app, src, web, etc. dentro de la imagen
COPY . .

# Permisos críticos para Symfony: app/cache y app/logs deben ser escribibles
RUN chmod -R 777 app/cache app/logs