<?php

namespace Zotop\Themes;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 注册主题
        $this->app->register(BootstrapServiceProvider::class);

        //设置默认分页代码
        Paginator::defaultView('pagination.default');
        Paginator::defaultSimpleView('pagination.simple');
    }
}
