<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS di production (Railway pakai reverse proxy)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Set Carbon locale ke Bahasa Indonesia
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID');

        // Gunakan Bootstrap untuk pagination
        Paginator::useBootstrapFive();
    }
}
