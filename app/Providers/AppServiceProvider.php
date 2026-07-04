<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Konfigurasi dinamis path cache untuk Vercel / serverless environment
        $isVercel = isset($_SERVER['VERCEL_URL']) || env('VERCEL_URL') || str_starts_with(base_path(), '/var/task') || env('APP_ENV') === 'production';

        if ($isVercel) {
            // Pastikan direktori write-temp ada di /tmp
            if (!is_dir('/tmp/views')) {
                @mkdir('/tmp/views', 0755, true);
            }
            if (!is_dir('/tmp/sessions')) {
                @mkdir('/tmp/sessions', 0755, true);
            }

            config(['view.compiled' => '/tmp/views']);
            config(['session.files' => '/tmp/sessions']);
            config(['app.debug' => true]);
            config(['app.key' => 'base64:nzG785sNWY2tvVt+zvcPbiGz8ite+ZF5MHuvmiGv2uA=']); // Set default key jika env Vercel belum dipasang

            // Paksa driver stateless untuk serverless agar tidak bergantung pada file .env
            config(['logging.default' => 'stderr']);
            config(['session.driver' => 'cookie']);
            config(['cache.default' => 'array']);
            config(['queue.default' => 'sync']);

            // Salin SQLite database ke /tmp agar writable oleh Vercel Serverless
            $dbPath = database_path('database.sqlite');
            $tmpDbPath = '/tmp/database.sqlite';
            if (file_exists($dbPath)) {
                if (!file_exists($tmpDbPath)) {
                    copy($dbPath, $tmpDbPath);
                }
                config(['database.connections.sqlite.database' => $tmpDbPath]);
            }
        }
    }
}
