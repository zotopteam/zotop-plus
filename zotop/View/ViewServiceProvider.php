<?php

namespace Zotop\View;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Zotop\View\Compilers\DotArrayCompiler;
use Zotop\View\Compilers\ZFormCompiler;

class ViewServiceProvider extends ServiceProvider
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

        // 注册别名
        AliasLoader::getInstance()->alias('Html', Facades\Html::class);
        AliasLoader::getInstance()->alias('Form', Facades\Form::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
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

}
