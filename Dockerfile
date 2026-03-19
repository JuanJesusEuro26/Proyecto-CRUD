FROM php:7.1-fpm-alpine

# 1. Instalamos extensiones necesarias
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

# 2. Instalamos Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# 3. Copiamos el proyecto
COPY . .

# 4. PASO CLAVE: Ejecutamos composer install durante la construcción de la imagen.
# Esto genera el bootstrap.php.cache y descarga los vendors automáticamente.
RUN composer install --no-interaction --optimize-autoloader

# 5. PASO CLAVE: Aseguramos que las carpetas existan y tengan permisos totales
RUN mkdir -p app/cache app/logs && chmod -R 777 app/cache app/logs