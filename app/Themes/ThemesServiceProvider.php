<?php

namespace App\Themes;

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
        $this->app->singleton('themes', function($app) {
            return new Repository($app);
        });

        $this->app->singleton('html', function($app) {
            return new Html($app);
        });

        $this->app->singleton('form', function($app) {
            return new Form($app);
        });


        $this->mergeConfigFrom(__DIR__.'/Config/themes.php', 'themes');        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(BootstrapServiceProvider::class);

        $this->bladeExtend();
        $this->paginatorDefault();       

    }

    /**
     * 模板扩展
     * @return void
     */
    public function bladeExtend()
    {
        // 覆盖系统默认的BladeCompiler
        $this->app->singleton('blade.compiler', function ($app) {
            return new \App\Themes\BladeCompiler(
                $app['files'], $app['config']['view.compiled']
            );
        });

        // 解析{form ……}
        Blade::extend(function ($value) {
            $pattern = sprintf('/(@)?%sform(\s+[^}]+?)\s*%s/s', '{', '}');

            return preg_replace_callback($pattern, function($matches){
                $attrs = Blade::convertAttrs($matches[2]);
                return $matches[1] ? substr($matches[0], 1) : "<?php echo Form::open(".$attrs."); ?>";
            }, $value);
        });

        // 解析{/form}
        Blade::extend(function ($value) {
            $pattern = sprintf('/(@)?%s(\/form)%s/s', '{', '}');

            return preg_replace_callback($pattern, function ($matches)  {
                return $matches[1] ? substr($matches[0], 1) : "<?php echo Form::close(); ?>";
            }, $value);
        });        

        // 解析{field ……}
        Blade::extend(function ($value) {
            $pattern = sprintf('/(@)?%sfield(\s+[^}]+?)\s*%s/s', '{', '}');

            return preg_replace_callback($pattern, function ($matches)  {
                $attrs = Blade::convertAttrs($matches[2]);
                return $matches[1] ? substr($matches[0], 1) : "<?php echo Form::field(".$attrs."); ?>";
            }, $value);
        });          
    }

    /**
     * 设置默认分页代码
     * @return null
     */
    public function paginatorDefault()
    {
        Paginator::defaultView('pagination.default');
        Paginator::defaultSimpleView('pagination.simple');     
    }        
}
