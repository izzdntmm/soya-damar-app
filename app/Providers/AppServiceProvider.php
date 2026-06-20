<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Set locale Indonesia untuk Carbon (format tanggal)
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID');
    }
}