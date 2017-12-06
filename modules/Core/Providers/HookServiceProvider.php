<?php
namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;

use Modules\Core\Support\Action;
use Modules\Core\Support\Filter;

use Blade;

class HookServiceProvider extends ServiceProvider
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
        Blade::directive('action', function($expression) {
            return "<?php Action::fire$expression; ?>";
        });

        /**
         * Adds a directive in Blade for filters
         */
        Blade::directive('filter', function($expression) {
            return "<?php echo Filter::fire$expression; ?>";
        });
    }

    /**
     * Registers the eventy singleton
     *
     * @return void
     */
    public function register()
    {
    	$this->app->singleton('hook.action', function ($app) {
		    return new Action();
		});

        $this->app->singleton('hook.filter', function ($app) {
            return new Filter();
        });        
    }
}
