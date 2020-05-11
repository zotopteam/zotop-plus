<?php

namespace App\Support;

use App\Support\Action;
use App\Support\Filter;
use App\Support\Form;
use App\Support\Html;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('html', function($app) {
            return new Html($app);
        });

        $this->app->singleton('form', function($app) {
            return new Form($app);
        });

        $this->app->singleton('hook.action', function ($app) {
            return new Action($app);
        });

        $this->app->singleton('hook.filter', function ($app) {
            return new Filter($app);
        });            
    }

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
}
