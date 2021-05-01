<?php

namespace Zotop\Themes;

use Illuminate\Support\ServiceProvider;
use Blade;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // 注册当前主题
        $this->app->singleton('current.theme', function($app) {
            $theme = $app['config']->get('modules.types.'.$app['current.type'].'.theme');
            return $app['hook.filter']->fire('current.theme', $theme, $app);
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function boot()
    {
        $this->app['themes']->active($this->app['current.theme']);

        // 只执行一次 @once('……') @endonce
        Blade::directive('once', function ($expression) {
            $expression = strtoupper($expression);
            return "<?php if (! isset(\$__env->once[{$expression}])) : \$__env->once[{$expression}] = true; ?>";
        });

        Blade::directive('endonce', function () {
            return "<?php endif; ?>";
        });

        // 只加载一次js文件 @loadjs('……')
        Blade::directive('loadjs', function ($expression) {
            return "<?php \$loadjs = {$expression}; if (! isset(\$__env->loadjs[\$loadjs])) : \$__env->loadjs[\$loadjs] = true;?>\r\n<script src=\"<?php echo \$loadjs; ?>\"></script>\r\n<?php endif; ?>";
        });

        // 只加载一次css文件 @loadcss('……')
        Blade::directive('loadcss', function ($expression) {
            return "<?php \$loadcss = {$expression}; if (! isset(\$__env->loadcss[\$loadcss])) : \$__env->loadcss[\$loadcss] = true;?>\r\n<link rel=\"stylesheet\" href=\"<?php echo \$loadcss; ?>\" rel=\"stylesheet\">\r\n<?php endif; ?>";
        });
    }

}
