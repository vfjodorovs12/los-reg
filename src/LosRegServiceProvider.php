<?php

namespace Vfjodorovs12\LosReg;

use Seat\Services\AbstractSeatPlugin;

class LosRegServiceProvider extends AbstractSeatPlugin
{
    public function getMenu()
    {
        return [
            'name' => 'Лос Рег',
            'icon' => 'fa fa-magic',
            'route' => 'los-reg.index', // Имя роутера из routes/web.php
        ];
    }

    public function register()
    {
        // Можно регистрировать сервисы, если нужно
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'los-reg');
    }
}
