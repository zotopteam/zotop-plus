<?php

namespace Modules\Core\Providers;

use App\Support\ImageFilter;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Core\View\Components\UploadChunk;

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
        $this->imageFilter();
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
        // 禁止禁用
        $this->app['events']->listen('modules.core.disabling', function($module) {
            abort(403, trans('core::module.disable.forbidden', [$module->getTitle()]));
        });

        // 禁止卸载
        $this->app['events']->listen('modules.core.uninstalling', function($module) {
            abort(403, trans('core::module.uninstall.forbidden', [$module->getTitle()]));
        });

        // 禁止删除
        $this->app['events']->listen('modules.core.deleting', function($module) {
            abort(403, trans('core::module.delete.forbidden', [$module->getTitle()]));
        });                    
    }


    // 模板扩展
    public function bladeExtend()
    {
        // 模板中的权限指令
        Blade::if('allow', function ($permission) {
            return allow($permission);
        });

        Blade::component(UploadChunk::class, 'upload-chunk');
    }

    // 图片滤器
    public function imageFilter()
    {
        ImageFilter::set('core-resize', \Modules\Core\Support\ImageFilters\Resize::class);     
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
