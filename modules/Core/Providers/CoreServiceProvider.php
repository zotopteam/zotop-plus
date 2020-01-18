<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Blade;
use Filter;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * 重载admin和allow中间件
     *
     * @var array
     */
    protected $middlewares = [
        'admin'       => 'AdminMiddleware',
        'allow'       => 'AllowMiddleware',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->setBackend();
    }
    
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMiddleware();
        $this->setLocale();
        $this->eventsListen();
        $this->bladeExtend();
    }

    /**
     * 设置主题和后台后缀
     * @return void
     */
    public function setBackend()
    {
        $this->app['config']->set('modules.types.backend.prefix', $this->app['config']->get('core.backend.prefix'));
        $this->app['config']->set('modules.types.backend.theme', $this->app['config']->get('core.backend.theme'));
    }

    /**
     * 注册中间件
     *
     * @param  Router $router
     * @return void
     */
    public function registerMiddleware()
    {
        foreach ($this->middlewares as $name => $middleware) {
            $this->app['router']->aliasMiddleware($name, "Modules\\Core\\Http\\Middleware\\{$middleware}");
        }
    }


    /**
     * 设置当前语言
     */
    public function setLocale()
    {
        $locale = $this->app['current.locale'];

        // Carbon 语言转换
        $carbon_locale = Arr::get($this->app['hook.filter']->fire('carbon.locale.transform', [
            'zh-Hans' => 'zh',
            'zh-Hant' => 'zh_TW'
        ]), $locale, $locale);
        
        Carbon::setLocale($carbon_locale);

        // Faker 语言转换
        $faker_locale = Arr::get($this->app['hook.filter']->fire('faker.locale.transform', [
            'en'      => 'en_US',
            'zh-Hans' => 'zh_CN',
            'zh-Hant' => 'zh_TW'
        ]), $locale, $locale);

        $this->app['config']->set('app.faker_locale', $faker_locale);
    }

    /**
     * 事件监听
     * @return null
     */
    public function eventsListen()
    {
        // 禁止禁用和卸载核心模块
        $this->app['events']->listen('modules.*.*', function($event, $modules) {
            if (ends_with($event, 'uninstalling') || ends_with($event, 'disabling')) {
                foreach ($modules as $module) {
                    if (in_array($module->getLowerName(), config('modules.cores', ['core']))) {
                        abort(403,trans('core::module.core_operate_forbidden'));
                    }
                }
            }
        });        
    }


    // 模板扩展
    public function bladeExtend()
    {
        // 模板中的权限指令
        Blade::if('allow', function ($permission) {
            return allow($permission);
        });
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
}
