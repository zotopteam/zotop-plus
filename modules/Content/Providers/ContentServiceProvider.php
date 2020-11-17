<?php

namespace Modules\Content\Providers;

use Modules\Content\Models\Model;
use Modules\Content\Models\Content;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\Content\View\Components\ContentList;
use Modules\Content\View\Components\ContentAdminList;
use Modules\Content\View\Components\ContentAdminBreadcrumb;


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
        // $this->app['events']->listen('modules.content.installed', function($module) {
        //     // 导入系统自带的模型
        //     foreach (['category','page','article','link','gallery'] as $model) {
        //         ModelHelper::import($module->getPath().'/Support/models/'.$model.'.model', true);
        //     }
        // });        

        // 监听卸载
        $this->app['events']->listen('modules.content.uninstalling', function ($module) {

            //内容数据大于5条时候，禁止卸载模块
            abort_if(Content::count() > 5, 403, trans('content::module.uninstall.forbidden'));

            // 卸载所有自动生成的表
            Model::all()->each(function ($model) {
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
        // 模板扩展
        Blade::component(ContentAdminList::class, 'content-admin-list');
        Blade::component(ContentAdminBreadcrumb::class, 'content-admin-breadcrumb');
        Blade::component(ContentList::class, 'content-list');
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

        // 解析{content:list ……}
        Blade::tag('content:list', function ($attrs, $vars) {

            $id = array_get($attrs, 'id', 0); // 内容编号
            $slug = array_get($attrs, 'slug', null);  // 内容slug
            $image = array_get($attrs, 'image', null); // 图片模式 true=有图模式 false=无图模式
            $model = array_get($attrs, 'model', null);  // 内容模型 article 或者 article,page，为空显示所有节点类型
            $subdir = array_get($attrs, 'subdir', false);  // 是否显示子目录内容
            $paginate = array_get($attrs, 'paginate', false); // 是否分页
            $size = array_get($attrs, 'size', 10); // 显示条数
            $sort = array_get($attrs, 'sort', null);  // 排序方式
            $with = array_get($attrs, 'with', null); // 关联
            $view = array_get($attrs, 'view', $paginate ? 'content::tag.paginate' : 'content::tag.list'); //模板

            // slug 转换id
            $id = $id ? $id : Content::where('slug', $slug)->value('id');

            // 查询
            $query = $with ? Content::with(explode(',', $with)) : Content::query();
            $query->publish()->model($model)->sort($sort);

            // 查询子节点
            $query->when($subdir, function ($query, $subdir) use ($id) {
                return $query->whereSmart('parent_id', Content::childrenIds($id, true, 'category'));
            }, function ($query) use ($id) {
                return $query->where('parent_id', $id);
            });

            // 获取带封面图片的数据
            if ($image === true) {
                $query->whereNotNull('image');
            }

            // 获取无封面图片的数据
            if ($image === false) {
                $query->whereNull('image');
            }

            // 获取分页或者列表数据
            if ($paginate) {
                $list = $query->paginate($size);
            } else {
                $list = $query->limit($size)->get();
            }

            return app('view')->make($view)
                ->with($vars)
                ->with('attrs', ['id' => $id] + $attrs)
                ->with('list', $list)
                ->render();
        });

        // 解析{content_navbar ……}
        Blade::tag('content:navbar', function ($attrs, $vars) {

            $id = array_get($attrs, 'id', 0);
            $size = array_get($attrs, 'size', 0); // 显示条数 0=显示全部
            $view = array_get($attrs, 'view', 'content::tag.navbar'); //模板

            // 查询
            $query = Content::publish()->sort()->where('parent_id', $id);
            $query->when($size, function ($query, $size) {
                $query->limit($size);
            });

            $navbar = $query->get();

            return app('view')->make($view)
                ->with($vars)
                ->with('attrs', $attrs)
                ->with('navbar', $navbar)
                ->render();
        });

        // 路径 {content_path id="$content->id" self="true" view="content::tag.path"}
        Blade::tag('content:path', function ($attrs, $vars) {

            $id = array_get($attrs, 'id', 0); // 内容编号
            $self = array_get($attrs, 'self', true); // 是否显示自身
            $view = array_get($attrs, 'view', "content::tag.path"); //模板

            if ($id) {
                return app('view')->make($view)
                    ->with($vars)
                    ->with('attrs', $attrs)
                    ->with('path', Content::path($id, $self))
                    ->render();
            }

            return null;
        });
    }
}
