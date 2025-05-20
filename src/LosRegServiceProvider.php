<?php

namespace Vfjodorovs12\LosReg;

use Seat\Services\AbstractSeatPlugin;

class LosRegServiceProvider extends AbstractSeatPlugin
{
    public function getName(): string
    {
        return 'Лос Рег';
    }

    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/vfjodorovs12/los-reg';
    }

    public function getPackagistPackageName(): string
    {
        return 'los-reg';
    }

    public function getPackagistVendorName(): string
    {
        return 'vfjodorovs12';
    }

    public function register()
    {
        // Регистрируем меню
        $this->mergeConfigFrom(__DIR__ . '/Config/losreg.sidebar.php', 'package.sidebar');
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'los-reg');
    }
}
