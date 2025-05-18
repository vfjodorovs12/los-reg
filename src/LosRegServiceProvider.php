<?php

namespace Vfjodorovs12\LosReg;

use Seat\Services\AbstractSeatPlugin;

class LosRegServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'losreg');
    }

    public function register()
    {
        //
    }

    public function getName(): string
    {
        return 'los-reg';
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
}
