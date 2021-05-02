<?php

namespace Zotop\Modules\Routing;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Zotop\Modules\Facades\Module;

abstract class ServiceProvider extends RouteServiceProvider
{
    /**
     * 控制器根命名空间
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * 模块名称
     *
     * @var string
     */
    protected $module = '';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        // 注册闭包命令行
        $this->mapConsoleRoutes();
    }

    /**
     * 定义路由绑定
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * 定义路由
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapChannelsRoutes($router);
    }

    /**
     * 加载全部类型的路由
     *
     * @param \Illuminate\Routing\Router $router
     * @author Chen Lei
     * @date 2021-03-25
     */
    protected function mapChannelsRoutes(Router $router)
    {
        // 模块路由文件夹的基本路径
        $routesBasePath = Module::path("{$this->module}::Routes");

        // 获取所有类型
        foreach ($this->app['config']->get('modules.channels') as $channel => $setting) {

            // 获取对应的route完整路径，如果文件存在，则加载该路由
            $routeFile = $routesBasePath . DIRECTORY_SEPARATOR . Arr::get($setting, 'route', "{$channel}.php");

            // 如果路由文件不存在，直接跳过
            if (!$this->app['files']->exists($routeFile)) {
                continue;
            }

            // 控制器命名空间
            $namespace = Arr::get($setting, 'dirs.controller')
                ? $this->namespace . '\\' . Arr::get($setting, 'dirs.controller')
                : $this->namespace;

            // 加载路由文件
            $router->group([
                'module'     => $this->module,
                'channel'    => $channel,
                'namespace'  => $namespace,
                'prefix'     => Arr::get($setting, 'prefix'),
                'middleware' => Arr::get($setting, 'middleware'),
            ], function (Router $router) use ($routeFile) {
                require $routeFile;
            });

        }
    }

    /**
     * 加载命令行
     *
     * @return void
     */
    private function mapConsoleRoutes()
    {
        $consoleRouteFile = Module::path("{$this->module}::Routes/console.php");

        if ($this->app['files']->exists($consoleRouteFile)) {
            require $consoleRouteFile;
        }
    }
}
