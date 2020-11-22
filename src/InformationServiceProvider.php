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
//        if ($this->app->runningInConsole()) {
//            $this->commands([
//                InstallCommand::class,
//                UninstallCommand::class,
//                UpgradeCommand::class,
//                // 将原商城数据复制到新表中
//                CopyOrdersCommand::class,
//                CopyDatabaseCommand::class,
//                CopyGoodsCommand::class
//            ]);
//        }

        $this->loadRoutesFrom(__DIR__ . '/../routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'information');
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/information'),
        ]);
    }
}
