<?php

namespace App\Themes;

use Illuminate\Support\ServiceProvider;

class ThemesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('themes', function($app){
            return new Repository($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(BootstrapServiceProvider::class);

        $this->bladeExtend();
    }

    /**
     * 模板扩展
     * @return void
     */
    public function bladeExtend()
    {
        // 覆盖系统默认的BladeCompiler
        $this->app->singleton('blade.compiler', function ($app) {
            return new \App\Themes\BladeCompiler(
                $app['files'], $app['config']['view.compiled']
            );
        });  
    }  
}
