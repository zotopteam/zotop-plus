<?php

namespace App\Modules\Routing;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;

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
     * 定义路由绑定
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

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
     * 前端路由文件地址
     * 
     * @return mixed
     */
    protected function getFrontRouteFile()
    {
        return false;
    }

    /**
     * 后端路由文件地址
     * 
     * @return mixed
     */
    protected function getAdminRouteFile()
    {
        return false;
    }

    /**
     * Api路由文件地址
     * 
     * @return mixed
     */
    protected function getApiRouteFile()
    {
        return false;
    }

    /**
     * 闭包命令行文件地址
     * @return mixed
     */
    public function getConsoleRouteFile()
    {
        return false;
    }

    /**
     * 定义路由
     *
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapApiRoutes($router);
        $this->mapFrontRoutes($router);
        $this->mapAdminRoutes($router);
    }

    /**
     * 定义api路由
     *
     * @return void
     */
    private function mapApiRoutes(Router $router)
    {
        if ($apiRouteFile = $this->getApiRouteFile()) {
            $router->group([
                'module'     => $this->module,
                'namespace'  => $this->namespace . '\\' . $this->app['config']->get('modules.types.api.dirs.controller'),
                'prefix'     => $this->app['config']->get('modules.types.api.prefix'),
                'middleware' => $this->app['config']->get('modules.types.api.middleware'),
            ], function (Router $router) use ($apiRouteFile) {
                require $apiRouteFile;
            });
        }
    }

    /**
     * 定义前端路由
     *
     * @return void
     */
    private function mapFrontRoutes(Router $router)
    {
        if ($frontRouteFile  = $this->getFrontRouteFile()) {
            $router->group([
                'module'     => $this->module,
                'namespace'  => $this->namespace,
                'prefix'     => $this->app['config']->get('modules.types.frontend.prefix'),
                'middleware' => $this->app['config']->get('modules.types.frontend.middleware'),
            ], function (Router $router) use ($frontRouteFile) {
                require $frontRouteFile;
            });
        }
    }

    /**
     * 定义后端路由
     *
     * @return void
     */
    private function mapAdminRoutes(Router $router)
    {
        if ($adminRouteFile = $this->getAdminRouteFile()) {
            $router->group([
                'module'     => $this->module,
                'namespace'  => $this->namespace . '\\' . $this->app['config']->get('modules.types.backend.dirs.controller'),
                'prefix'     => $this->app['config']->get('modules.types.backend.prefix'),
                'middleware' => $this->app['config']->get('modules.types.backend.middleware'),
            ], function (Router $router) use ($adminRouteFile) {
                require $adminRouteFile;
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
        if ($consoleRouteFile  = $this->getConsoleRouteFile()) {
            require $consoleRouteFile;
        }
    }
}
