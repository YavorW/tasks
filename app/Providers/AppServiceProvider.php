<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // задължителен редирект към https версия
        if (config('app.debug') != true) {
            URL::forceScheme('https');
        }

        // ако го има проблема с базата с данни
        Schema::defaultStringLength(191);

        // алтернатива на @dump в блейд без форматиране
        Blade::directive('var_dump', function ($var) {
            return "<pre><?php var_dump($var); ?></pre>";
        });

        // за да се ползва bootstrap странициране
        Paginator::useBootstrap();
    }
}
