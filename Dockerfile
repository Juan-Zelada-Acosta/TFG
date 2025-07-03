FROM php:8.1-apache

# Instala Composer y extensiones útiles, incluyendo mysqli y pdo_mysql
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install zip pdo pdo_mysql mysqli

# Copia Composer desde imagen oficial
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Habilita mod_rewrite
RUN a2enmod rewrite

# Cambia el DocumentRoot a public/
WORKDIR /var/www/html
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Copia todo al contenedor
COPY . /var/www/html

# Da permisos correctos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80



