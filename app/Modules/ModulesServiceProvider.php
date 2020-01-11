<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(BootstrapServiceProvider::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('modules', function ($app) {
            return new Repository($app);
        });

        $this->app->singleton('modules.activator', function ($app) {
            return new Activator($app);
        });
    } 
}
