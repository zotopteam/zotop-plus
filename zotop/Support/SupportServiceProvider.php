<?php

namespace Zotop\Support;

use Illuminate\Support\ServiceProvider;
use Zotop\Support\Compilers\DotArrayCompiler;
use Zotop\Support\Compilers\ZFormCompiler;
use Zotop\Support\ImageFilters\Fit;
use Zotop\Support\ImageFilters\Resize;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('html', function ($app) {
            return new Html($app);
        });

        $this->app->singleton('form', function ($app) {
            return new Form($app);
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bladeExtend();
        $this->imageFilter();
    }

    /**
     * 模板扩展
     *
     * @author Chen Lei
     * @date 2020-11-25
     */
    private function bladeExtend()
    {
        // 模板编译扩展，解析点格式的数组 $a.b.c => $a['b']['c'], @$a.b.c => $a.b.c
        $this->app['blade.compiler']->extend(function ($view) {
            return $this->app[DotArrayCompiler::class]->compile($view);
        });


        // 模板编译扩展，解析z-form标签 <z-form bind="$model"> <z-field type="input"/> </z-form>
        $this->app['blade.compiler']->extend(function ($view) {
            return $this->app[ZFormCompiler::class]->compile($view);
        });

    }

    /**
     * 图片滤器
     *
     * @author Chen Lei
     * @date 2020-11-25
     */
    private function imageFilter()
    {
        // 定义图片滤器
        ImageFilter::set('fit', Fit::class);
        ImageFilter::set('resize', Resize::class);
    }

}
