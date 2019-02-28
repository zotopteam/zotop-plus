<?php

namespace Modules\Content\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Content\Models\Content;
use Modules\Content\Models\Model;
use Illuminate\Support\Facades\Schema;
use Modules\Content\Support\ModelHelper;
use Blade;


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

        // 解析模板
        $this->baldeContentTag();
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

    private function baldeContentTag()
    {
        // 解析{content ……}
        Blade::tag('content', function($attrs) {

            $id       = array_pull($attrs, 'id');
            $slug     = array_pull($attrs, 'slug');
            $template = array_pull($attrs, 'template');
            $model    = array_pull($attrs, 'model', '');
            $children = array_pull($attrs, 'children', 'children');
            $paginate = array_pull($attrs, 'paginate', false);
            $size     = array_pull($attrs, 'size', 10);
            $sort     = array_pull($attrs, 'sort', '');

            $content = Content::where('slug', $slug)->Orwhere('id', $id)->firstOrFail();

            // 如果block存在，解析并返回
            if ($content) {

                // 获取子节点
                if ($children) {
                    $contents = Content::where('parent_id', $content->id);

                    if ($model) {
                        $models = explode(',', $model);
                        if (count($models) > 1) {
                            $contents->whereIn('model_id', $models);
                        } else {
                            $contents->where('model_id', $model);
                        }
                    }

                    if ($sort) {

                    } else {
                        $contents->sort();
                    }

                    if ($paginate) {
                        $children = $contents->paginate($size);
                    } else {
                        $children = $contents->limit($size)->get();
                    }
                }

                return app('view')->make($template)->with('content', $content)->with('children', $children)->render();
            }
            
            return null;
        });                     
    }    
}
