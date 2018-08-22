<?php

namespace Modules\Site\Providers;

use Illuminate\Support\ServiceProvider;

class SiteServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        // 注册中间件
        $this->registerMiddleware();
    }    

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * 注册中间件, 替换Core中的front中间件
     *
     * @param  Router $router
     * @return void
     */
    public function registerMiddleware()
    {
        $this->app['router']->aliasMiddleware('front', "Modules\\Site\\Http\\Middleware\\FrontMiddleware");
    }    
}
