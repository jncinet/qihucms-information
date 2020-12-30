<?php

namespace Qihucms\Information;

use Illuminate\Support\ServiceProvider;

class InformationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'information');
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/information'),
        ]);
    }
}