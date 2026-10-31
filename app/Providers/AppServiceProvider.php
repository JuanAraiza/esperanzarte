<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

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
        //
        //
        \Gate::define('Administradores', function ($user) {
            if ($user->tipo == '1') {
                return true;
            }
            return false;
        });

        \Gate::define('Docentes', function ($user) {
            if ($user->tipo == '2') {
                return true;
            }
            return false;
        });

        \Gate::define('Entregas', function ($user) {
            if ($user->tipo == '3') {
                return true;
            }
            return false;
        });

        \Gate::define('Reportes', function ($user) {
            if ($user->tipo == '4') {
                return true;
            }
            return false;
        });
 /*
        Paginator::useBootstrapFour();

        URL::forceRootUrl(Config::get('app.url'));

        // Opcional: Forzar el esquema HTTPS si es necesario
       if (Str::contains(Config::get('app.url'), 'https://')) {
            URL::forceScheme('https');
        }*/

    }

}
