#!/bin/bash

# Jalankan migrasi database
php artisan migrate --force

# Buat link storage (opsional jika nanti pakai Cloudinary, tapi kita jalankan saja)
php artisan storage:link

# Jalankan server Apache bawaan di depan layar
apache2-foreground