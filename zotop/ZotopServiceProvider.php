<?php

namespace Zotop;

use Illuminate\Support\ServiceProvider;

class ZotopServiceProvider extends ServiceProvider
{
    /**
     * 服务提供者
     *
     * @var array
     */
    protected $providers = [
        Modules\ModulesServiceProvider::class,
        Hook\HookServiceProvider::class,
        Themes\ThemesServiceProvider::class,
        View\ViewServiceProvider::class,
        Image\ImageServiceProvider::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

}
