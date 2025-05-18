<?php

namespace Vfjodorovs12\LosReg;

use Illuminate\Support\ServiceProvider;

class LosRegServiceProvider extends ServiceProvider
{
    /**
     * Выполняется после регистрации всех сервис-провайдеров.
     */
    public function boot()
    {
        // Подключение маршрутов, если есть
        // $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Подключение миграций, если есть
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Публикация файлов конфига, если есть
        // $this->publishes([
        //     __DIR__.'/../config/losreg.php' => config_path('losreg.php'),
        // ], 'config');
    }

    /**
     * Регистрация сервисов и биндингов.
     */
    public function register()
    {
        // Пример биндинга (если надо что-то регистрировать в контейнер)
        // $this->app->singleton(SomeService::class, function ($app) {
        //     return new SomeService();
        // });
    }
}
