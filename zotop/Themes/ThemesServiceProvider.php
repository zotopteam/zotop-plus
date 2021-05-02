<?php

namespace Zotop\Themes;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Zotop\Themes\Middleware\ThemeMiddleware;

class ThemesServiceProvider extends ServiceProvider
{
    /**
     * 命令行
     *
     * @var array
     */
    protected $commands = [
        Commands\MakeCommand::class,
        Commands\PublishCommand::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 注册themes
        $this->app->singleton('themes', function ($app) {
            return new Repository($app);
        });

        // 合并配置
        $this->mergeConfigFrom(__DIR__ . '/Config/themes.php', 'themes');

        // 注册命令行
        $this->commands($this->commands);

        // 注册别名
        AliasLoader::getInstance()->alias('Theme', Facades\Theme::class);

        // 注册中间件
        $this->app['router']->aliasMiddleware('theme', ThemeMiddleware::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //设置默认分页代码
        Paginator::defaultView('pagination.default');
        Paginator::defaultSimpleView('pagination.simple');

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
