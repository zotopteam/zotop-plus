<?php

namespace App\Support;

use App\Support\Compilers\DotArrayCompiler;
use App\Support\Compilers\ZFormCompiler;
use App\Support\ImageFilters\Fit;
use App\Support\ImageFilters\Resize;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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

        $this->app->singleton('hook.action', function ($app) {
            return new Action($app);
        });

        $this->app->singleton('hook.filter', function ($app) {
            return new Filter($app);
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

        /**
         * Adds a directive in Blade for actions
         */
        Blade::directive('action', function ($expression) {
            return "<?php Action::fire($expression); ?>";
        });

        /**
         * Adds a directive in Blade for filters
         */
        Blade::directive('filter', function ($expression) {
            return "<?php echo Filter::fire($expression); ?>";
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
