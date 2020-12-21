<?php

namespace Modules\Site\Providers;

use App\Modules\Support\ServiceProvider;
use App\Support\Facades\Form;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;
use Modules\Site\View\Controls\View;

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
        $this->app['router']->aliasMiddleware('front', "Modules\\Site\\Http\\Middleware\\FrontMiddleware");

        // 禁止禁用
        $this->app['events']->listen('modules.site.disabling', function ($module) {
            abort(403, trans('core::module.disable.forbidden', [$module->getTitle()]));
        });

        // 禁止卸载
        $this->app['events']->listen('modules.site.uninstalling', function ($module) {
            abort(403, trans('core::module.uninstall.forbidden', [$module->getTitle()]));
        });

        // 禁止删除
        $this->app['events']->listen('modules.site.deleting', function ($module) {
            abort(403, trans('core::module.delete.forbidden', [$module->getTitle()]));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // 前端主题
        $theme = $this->app['config']->get('site.theme');

        // 移动端主题：通过移动端网址或者设备匹配
        if (Str::startsWith(Request::url(), $this->app['config']->get('site.wap.url')) || Agent::isMobile()) {
            $theme = $this->app['config']->get('site.wap.theme');
        }

        // 注册前端主题
        $this->app['config']->set('modules.types.frontend.theme', $theme);

        // 注册控件
        Form::control(View::class, 'view');
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
