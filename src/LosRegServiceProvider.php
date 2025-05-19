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
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'los-reg');

        // Новый способ для меню в SeAT 5.x
        if (function_exists('menu')) {
            menu('sidebar')
                ->group('Tools', function ($menu) {
                    $menu->route('los-reg.index', 'Лос Рег', [], ['icon' => 'fa fa-magic']);
                });
        }
    }
}
