#!/bin/sh
# Esperar a que MySQL esté listo (opcional pero recomendado)
echo "Esperando a que la base de datos conecte..."

# Ejecutar la actualización del esquema
php bin/console doctrine:schema:update --force --no-interaction

# Iniciar el servidor PHP-FPM original
php-fpm