FROM php:8.2-apache

# Solusi Mutlak Error AH00534: Matikan modul event/worker dengan aman, lalu paksa prefork
RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork || true

# Install sistem dependensi dan ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zip \
    curl \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql pdo_pgsql gd zip

# Mengaktifkan Apache mod_rewrite untuk routing
RUN a2enmod rewrite

# KUNCI MUTLAK: Paksa mesin Docker mengenali MySQL sebagai standar absolut
ENV DB_CONNECTION=mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory ke folder web
WORKDIR /var/www/html

# Copy seluruh file proyek
COPY . .

# Install dependensi Laravel
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Ubah DocumentRoot Apache agar mengarah ke folder /public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Berikan izin akses maksimal pada folder storage dan cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80