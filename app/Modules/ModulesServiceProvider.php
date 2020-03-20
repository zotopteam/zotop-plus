<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * 中间件
     *
     * @var array
     */
    protected $middlewares = [
        'module'      => 'ModuleMiddleware',
        'admin'       => 'AdminMiddleware',
        'admin.guest' => 'AdminGuestMiddleware',
        'front'       => 'FrontMiddleware',
        'allow'       => 'AllowMiddleware',
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //注册中间件
        foreach ($this->middlewares as $name => $middleware) {
            $this->app['router']->aliasMiddleware($name, "App\\Modules\\Middleware\\{$middleware}");
        }    

        // 启动全部模块
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

        $this->mergeConfigFrom(__DIR__.'/Config/modules.php', 'modules');        
    } 
}
