<?php

namespace Zotop\Modules;

use Zotop\Modules\Middleware\AdminMiddleware;
use Zotop\Modules\Middleware\AllowMiddleware;
use Zotop\Modules\Middleware\FrontMiddleware;
use Zotop\Modules\Middleware\ModuleMiddleware;
use Zotop\Modules\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * 中间件
     *
     * @var array
     */
    protected $middlewares = [
        'module' => ModuleMiddleware::class,
        'admin'  => AdminMiddleware::class,
        'front'  => FrontMiddleware::class,
        'allow'  => AllowMiddleware::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('modules', function ($app) {
            return new Repository($app);
        });

        $this->app->singleton('modules.activator', function ($app) {
            return new Activator($app);
        });

        // 加载默认配置
        $this->mergeConfigFrom(__DIR__ . '/Config/modules.php', 'modules');

        // 加载全部命令行
        $this->loadCommands(__DIR__ . '/Commands');

        // 别名
        $this->aliases([
            'Module' => Facades\Module::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //注册中间件
        $this->registerMiddleware();

        // 启动全部模块
        $this->app->register(BootstrapServiceProvider::class);
    }

    /**
     * 注册中间件
     *
     * @author Chen Lei
     * @date 2021-05-01
     */
    public function registerMiddleware()
    {
        foreach ($this->middlewares as $name => $class) {
            $this->app['router']->aliasMiddleware($name, $class);
        }
    }

}
