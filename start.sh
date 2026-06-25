#!/bin/bash

# KUNCI MUTLAK: Paksa terminal untuk menyuapkan variabel MySQL sebelum Laravel berjalan
export DB_CONNECTION=mysql

# Bersihkan ampas cache lama
php artisan optimize:clear

# Hancurkan dan bangun ulang database dengan paksa
php artisan migrate:fresh --force

# Amankan jalur storage gambar (abaikan jika error sudah ada)
php artisan storage:link || true

# Nyalakan server web utama
apache2-foreground