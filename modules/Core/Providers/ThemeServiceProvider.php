<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;

class ThemeServiceProvider extends ServiceProvider
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
        $this->bladeDirectiveOnce();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->singletonTheme();
        $this->registerThemes();
        $this->registerCommands();
    }

    /**
     * 绑定实例
     * @return [type] [description]
     */
    public function singletonTheme()
    {
        // 绑定theme与Theme类的实例
        $this->app->singleton('theme', function($app){
            return new \Modules\Core\Support\Theme($app);
        });
    }

    /**
     * 注册主题
     *
     * @return void
     */
    protected function registerThemes()
    {
        // 获取主题目录并注册全部主题        
        $path = $this->app['config']->get('themes.paths.themes', base_path('/themes'));

        $dirs = $this->app['files']->directories($path);

        foreach ($dirs as $dir) {
            $this->app['theme']->registerPath($dir);
        }
    }

    /**
     * 注册命令行
     * 
     * @return void
     */
    public function registerCommands()
    {
        $this->commands([
            \Modules\Core\Console\PublishThemeCommand::class,
        ]);
    }

    /**
     * 添加once命令
     * 
     * @return [type] [description]
     */
    public function bladeDirectiveOnce()
    {
        Blade::directive('once', function ($expression) {

            $expression = strtoupper($expression);

            return "<?php if (! defined({$expression})): ?>\n<?php define({$expression},true); ?>";
        }); 

        Blade::directive('endonce', function () {
            return "<?php endif; ?>";
        });             
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
