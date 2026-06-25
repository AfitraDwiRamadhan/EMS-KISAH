#!/bin/bash

# 1. Bersihkan cache sistem agar membaca variabel Railway terbaru
php artisan config:clear
php artisan cache:clear

# 2. Hancurkan database yang setengah jadi dan bangun ulang dari nol (Aman karena belum ada data produksi)
php artisan migrate:fresh --force

# 3. Hubungkan folder penyimpanan gambar
php artisan storage:link

# 4. Jalankan server Apache di depan layar
apache2-foreground