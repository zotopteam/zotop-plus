<?php

namespace App\Hook;

use Illuminate\Support\ServiceProvider;
use Blade;

class HookServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('hook.action', function ($app) {
            return new \App\Hook\Action($app);
        });

        $this->app->singleton('hook.filter', function ($app) {
            return new \App\Hook\Filter($app);
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
