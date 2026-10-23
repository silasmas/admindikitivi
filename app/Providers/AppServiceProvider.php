<?php

namespace App\Providers;

use App\Mail\Transport\PhpMailTransport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
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
        Carbon::setLocale('fr');
        setlocale(LC_TIME, 'fr_FR.UTF-8');

        // Transport « phpmail » : envoi via mail() quand SMTP Hostinger refuse l’auth
        Mail::extend('phpmail', function () {
            return new PhpMailTransport();
        });
    }
}
