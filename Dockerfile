FROM php:8.2-apache

# Solusi Mutlak Error Apache AH00534: Hapus ekstensi .load dan .conf sampai ke akar
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
    && rm -f /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork

# Install dependensi sistem
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zip \
    curl \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql pdo_pgsql gd zip

# Mengaktifkan Apache mod_rewrite
RUN a2enmod rewrite

# Kunci utama untuk koneksi database
ENV DB_CONNECTION=mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy semua file ke dalam server
COPY . .

# HANCURKAN file .env bawaan agar Laravel 100% bergantung pada Variabel Railway
RUN rm -f .env

# Install dependensi Laravel
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Setting DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Berikan izin akses folder
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80