<?php

namespace Modules\Content\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Content\Models\Content;
use Modules\Content\Models\Model;
use Illuminate\Support\Facades\Schema;
use Modules\Content\Support\ModelHelper;


class ContentServiceProvider extends ServiceProvider
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
        // 定时任务
        $this->app->booted(function () {
            $schedule = $this->app->make(\Illuminate\Console\Scheduling\Schedule::class);
            $schedule->command('content:publish-future')->name('content-publish-future')->withoutOverlapping()->everyMinute();
        });

        // 监听安装
        $this->app['events']->listen('modules.content.installed', function($module) {
            // 导入系统自带的模型
            foreach (['category','page','article','link'] as $model) {
                ModelHelper::import($module->getPath().'/Support/models/'.$model.'.model', true);
            }
        });        

        // 监听卸载
        $this->app['events']->listen('modules.content.uninstalling', function($module) {
            abort_if(Content::count() > 5, 403, trans('content::module.uninstall.forbidden'));

            // 卸载所有自动生成的表
            Model::all()->each(function($model) {
                $model->table && Schema::dropIfExists($model->table);
            });
        });
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
}
