# Imagen base: PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias para Laravel + PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Habilitar mod_rewrite (necesario para Laravel)
RUN a2enmod rewrite

# Cambiar el DocumentRoot de Apache a /var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar todos los archivos del proyecto al contenedor
COPY . .

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de Laravel (sin dev) y optimizar
RUN composer install --no-dev --optimize-autoloader

# Opcional pero recomendado: cachear config y rutas
RUN php artisan config:cache || true \
 && php artisan route:cache || true

# Exponer el puerto 80 (Apache)
EXPOSE 80

# Comando para levantar Apache
CMD ["apache2-foreground"]
