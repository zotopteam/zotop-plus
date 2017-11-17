<?php
namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;

use Modules\Core\Support\Format;
use Blade;

class FormatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Adds a directive in Blade for actions
         */
        Blade::directive('size', function($expression) {
            return "<?php echo Format::size($expression); ?>";
        });
    }

    /**
     * Registers the eventy singleton
     *
     * @return void
     */
    public function register()
    {
    	$this->app->singleton('format', function ($app) {
		    return new Format();
		});   
    }
}
