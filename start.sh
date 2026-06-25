#!/bin/bash

# Hapus cache konfigurasi secara harfiah (Bypass Artisan)
rm -f bootstrap/cache/*.php

# Terapkan ulang cache dengan variabel baru dari Railway
php artisan config:clear
php artisan cache:clear

# Migrasi ulang
php artisan migrate:fresh --force

# Amankan storage
php artisan storage:link || true

# Nyalakan server
apache2-foreground