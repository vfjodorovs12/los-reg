<?php

namespace Modules\LosReg;

use Illuminate\Support\ServiceProvider;

class LosRegServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Загрузка маршрутов
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Загрузка представлений
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'LosReg');

        // Публикация конфигураций
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('losreg.php'),
        ], 'config');

        // Загрузка миграций
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Регистрация конфигурации
        $this->mergeConfigFrom(
            __DIR__ . '/config/config.php', 'losreg'
        );
    }
}
