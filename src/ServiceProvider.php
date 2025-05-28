<?php

namespace Vfjodorovs12\LosReg;

use Seat\Services\AbstractSeatPlugin;

class ServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
        $this->add_routes();
        $this->add_views();
        $this->addMigrations();
    }

    public function add_routes()
    {
        if (! $this->app->routesAreCached()) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }
    }

    public function add_views()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'los-reg');
    }

    private function addMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations/');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/losreg.sidebar.php',
            'package.sidebar'
        );
    }

    // ОБЯЗАТЕЛЬНЫЕ МЕТОДЫ:
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
}
