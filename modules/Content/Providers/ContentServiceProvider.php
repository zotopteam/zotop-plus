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

            $id    = array_get($attrs, 'id', 0); // 内容编号
            $slug  = array_get($attrs, 'slug', '');  // 内容slug
            $image = array_get($attrs, 'image', null); // 图片模式 true=有图模式 false=无图模式           
            $model = array_get($attrs, 'model', '');  // 节点模型 article 或者 article,page
            $type  = array_get($attrs, 'type', 'list'); //类型 list=列表 paginate=分页列表 parents=父节点
            $self  = array_get($attrs, 'self', true); // 是否获取自身
            $size  = array_get($attrs, 'size', 10); // 显示条数
            $sort  = array_get($attrs, 'sort', '');  // 排序方式
            $with  = array_get($attrs, 'with', ''); // 关联
            $view  = array_get($attrs, 'view', "content::tag.{$type}"); //模板

            // 列表及分页列表
            if (in_array($type, ['list', 'paginate'])) {

                $content = collect([]);

                if ($self && $slug) {
                    $content= Content::publish()->where('slug', $slug)->firstOrFail();
                    $id = $content->id;
                } elseif ($self && $id) {
                    $content= Content::publish()->where('id', $id)->firstOrFail();
                } else {
                    $id = $slug ? Content::where('slug', $slug)->value('id') : $id;
                }

                $query = Content::with(str_array($with, ','));
                $query->publish()->where('parent_id', $id);
                $query->model($model);
                $query->sort($sort);

                // 获取带封面图片的数据
                if ($image === true) {
                    $query->whereNotNull('image');
                }

                // 获取无封面图片的数据
                if ($image === false) {
                    $query->whereNull('image');
                }

                // 获取分页或者列表数据
                if ($type == 'paginate') {
                    $children = $query->paginate($size);
                } else {
                    $children = $query->limit($size)->get();
                }

                return app('view')->make($view)
                    ->with('attrs', $attrs)
                    ->with('id', $id)
                    ->with('content', $content)
                    ->with('children', $children)
                    ->render();
            }

            // 父路径
            if ($type == 'parents') {

                // 获取当前内容编号
                $id = $id ? $id : Content::where('slug', $slug)->value('id');
                
                return app('view')->make($view)
                    ->with('attrs', $attrs)
                    ->with('id', $id)
                    ->with('parents', Content::parents($id, $self))
                    ->render();
            }
            
            return null;
        });                  
    }    
}
