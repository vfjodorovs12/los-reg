<?php

namespace Vfjodorovs12\LosReg;

use Seat\Services\AbstractSeatPlugin;

class LosRegServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
        $this->add_routes();
        $this->add_views();
    }

    public function register()
    {
        //
    }

    private function add_routes()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    private function add_views()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'losreg');
    }

    // ОБЯЗАТЕЛЬНЫЕ методы для SEAT! ----
    public function getName(): string
    {
        return 'los-reg';
    }

    public function getPackagistVendorName(): string
    {
        return 'vfjodorovs12';
    }

    public function getPackagistPackageName(): string
    {
        return 'vfjodorovs12/los-reg';
    }

    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/vfjodorovs12/los-reg';
    }
}
