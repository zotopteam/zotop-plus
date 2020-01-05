<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\ModuleServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerModules();
        $this->bladeExtend();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSingleton();
    }

    /**
     * 注册模块
     * @return void
     */
    protected function registerModules()
    {
        $this->app->register(ModuleServiceProvider::class);
    }

    /**
     * 在服务容器里注册
     * @return void
     */
    protected function registerSingleton()
    {
        $this->app->singleton('format', function ($app) {
            return new \App\Support\Extend\Format($app);
        });

        $this->app->singleton('hook.action', function ($app) {
            return new \App\Support\Hook\Action($app);
        });

        $this->app->singleton('hook.filter', function ($app) {
            return new \App\Support\Hook\Filter($app);
        });

        $this->app->singleton('modules', function ($app) {
            return new \App\Support\Modules\Repository($app);
        });

        $this->app->singleton('modules.activator', function ($app) {
            return new \App\Support\Modules\Activator($app);
        });        

        $this->app->singleton('theme', function($app){
            return new \App\Support\Theme\Theme($app);
        });
    }

    /**
     * 模板扩展
     * @return void
     */
    public function bladeExtend()
    {
        // 覆盖系统默认的BladeCompiler
        $this->app->singleton('blade.compiler', function ($app) {
            return new \App\Support\Extend\BladeCompiler(
                $app['files'], $app['config']['view.compiled']
            );
        });  
    }  
}
