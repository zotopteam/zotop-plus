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
        Blade::extend(function($value)
        {
            $pattern = sprintf('/(@)?%scontent(\s+[^}]+?)\s*%s/s', '{', '}');

            $callback = function ($matches)  {

                // 如果有@符号，@{content……} ，直接去掉@符号返回标签
                if ($matches[1]) {
                    return substr($matches[0], 1);
                }

                // 将属性转化为参数
                $arguments = Blade::convertAttrsToArray($matches[2]);

                // 获取循环变量，默认值为item
                $item = array_get($arguments, 'item', 'item');

                $content_tag = "\$_contentTagData = content_tag(".Blade::convertArrayToString($arguments).");"; 
                $content_tag .= "\$__env->addLoop(\$_contentTagData);";
                $content_tag .= "if(\$_contentTagData) {";
                $content_tag .= "foreach(\$_contentTagData as \$".$item.") { ";
                $content_tag .= "\$__env->incrementLoopIndices(); \$loop = \$__env->getLastLoop();";

                // 返回解析
                return '<?php '.$content_tag.' ?>';
            };

            return preg_replace_callback($pattern, $callback, $value);
        });

        // 解析 {/content}
        Blade::extend(function($value)
        {
            $pattern = sprintf('/(@)?%s(empty)%s/s', '{', '}');

            $callback = function ($matches)  {
                return '<?php } else { ?>';
            };

            return preg_replace_callback($pattern, $callback, $value);
        });          

        // 解析 {/content}
        Blade::extend(function($value)
        {
            $pattern = sprintf('/(@)?%s(\/content)%s/s', '{', '}');

            $callback = function ($matches)  {
                return '<?php } $__env->popLoop(); $loop = $__env->getLastLoop(); } ?>';
            };

            return preg_replace_callback($pattern, $callback, $value);
        });              
    }    
}
