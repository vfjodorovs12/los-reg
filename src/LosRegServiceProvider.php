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

    private function add_routes()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php'); // или '/Http/routes.php' если твои в Http
    }

    private function add_views()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'losreg');
    }

    public function register()
    {
        // если нужны биндинги/мердж конфига — сюда
    }
}
