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
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'losreg');
    }

    /**
     * Регистрация сервисов и биндингов.
     */
    public function register()
    {
        //
    }
}
