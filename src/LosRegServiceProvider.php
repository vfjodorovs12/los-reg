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
        // Подключение маршрутов
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Подключение шаблонов
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'losreg');
    }

    /**
     * Регистрация сервисов и биндингов.
     */
    public function register()
    {
        // Оставь пустым, если ничего не нужно
    }
}
