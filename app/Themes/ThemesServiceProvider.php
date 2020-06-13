<?php

namespace App\Themes;

use App\Themes\DotArrayCompiler;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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
        // 覆盖系统默认的BladeCompiler
        $this->app->singleton('blade.compiler', function ($app) {
            return new \App\Themes\BladeCompiler(
                $app['files'],
                $app['config']['view.compiled']
            );
        });

        // 模板编译扩展，解析点格式的数组 $a.b.c => $a['b']['c'], @$a.b.c => $a.b.c
        $this->app['blade.compiler']->extend(function ($view) {
            return $this->app[DotArrayCompiler::class]->compile($view);
        });

        // 注册themes
        $this->app->singleton('themes', function ($app) {
            return new Repository($app);
        });

        // 合并配置
        $this->mergeConfigFrom(__DIR__ . '/Config/themes.php', 'themes');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 注册主题
        $this->app->register(BootstrapServiceProvider::class);



        //设置默认分页代码
        Paginator::defaultView('pagination.default');
        Paginator::defaultSimpleView('pagination.simple');
    }
}
