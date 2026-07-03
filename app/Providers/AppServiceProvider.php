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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Konfigurasi dinamis path cache untuk Vercel / serverless environment
        if (env('VERCEL_URL') || env('APP_ENV') === 'production') {
            config(['view.compiled' => '/tmp/views']);
            config(['session.files' => '/tmp/sessions']);
        }
    }
}
