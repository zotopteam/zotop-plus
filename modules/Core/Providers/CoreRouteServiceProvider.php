<?php

namespace Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Filter;

abstract class CoreRouteServiceProvider extends ServiceProvider
{
    /**
     * 根命名空间，必须通过模块中的路由子类覆盖
     *
     * @var string
     */
    protected $namespace = '';

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
     * These routes are typically stateless.
     *
     * @return void
     */
    private function mapApiRoutes(Router $router)
    {
        $apiRouteFile = $this->getApiRouteFile();

        if ($apiRouteFile && file_exists($apiRouteFile)) {
            $router->group([
                'namespace'  => $this->namespace.'\Api',
                'prefix'     => Filter::fire('router.api.prefix', 'api'),
                'middleware' => Filter::fire('router.api.middleware', ['api']),
            ], function (Router $router) use ($apiRouteFile) {
                require $apiRouteFile;
            });
        }
    }       

    /**
     * 定义前端路由
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    private function mapFrontRoutes(Router $router)
    {
        $frontRouteFile = $this->getFrontRouteFile();
        
        if ($frontRouteFile && file_exists($frontRouteFile)) {
            $router->group([
                'namespace'  => $this->namespace,
                'prefix'     => Filter::fire('router.front.prefix', ''),
                'middleware' => Filter::fire('router.front.middleware', ['web','module','front']),      
            ], function (Router $router) use ($frontRouteFile) {
                require $frontRouteFile;
            });
        }
    }  

    /**
     * 定义后端路由
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    private function mapAdminRoutes(Router $router)
    {
        $adminRouteFile = $this->getAdminRouteFile();

        if ($adminRouteFile && file_exists($adminRouteFile)) {

            $router->group([
                'namespace'  => $this->namespace.'\Admin',
                'prefix'     => Filter::fire('router.admin.prefix', $this->app['config']->get('app.admin_prefix','admin')),
                'middleware' => Filter::fire('router.admin.middleware', ['web','module','admin']),      
            ], function (Router $router) use ($adminRouteFile) {
                require $adminRouteFile;
            });
        }
    }

    private function mapConsoleRoutes()
    {
        $consoleRouteFile = $this->getConsoleRouteFile();

        // 如果命令行路由文件存在则加载
        if ($consoleRouteFile && file_exists($consoleRouteFile)) {
            require $consoleRouteFile;
        }
    }   
}
