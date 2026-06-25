#!/bin/bash

# Bersihkan semua cache agar Laravel 100% membaca Variabel Railway
php artisan optimize:clear

# Paksa migrasi ulang dari nol
php artisan migrate:fresh --force

# Buat storage link (Abaikan error jika sudah ada dengan perintah || true)
php artisan storage:link || true

# Jalankan server
apache2-foreground