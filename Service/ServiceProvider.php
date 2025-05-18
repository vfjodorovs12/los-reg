namespace Modules\LosReg;

use Illuminate\Support\ServiceProvider;

class LosRegServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'LosReg');
    }

    public function register()
    {
        //
    }
}
