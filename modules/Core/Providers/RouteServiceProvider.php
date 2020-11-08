<?php

namespace Modules\Core\Providers;

use App\Modules\Routing\ServiceProvider;


class RouteServiceProvider extends ServiceProvider
{
    /**
     * 控制器命名空间
     *
     * @var string
     */
    protected $namespace = 'Modules\Core\Http\Controllers';

    /**
     * 模块名称
     *
     * @var string
     */
    protected $module = 'core';

    /**
     * 前台路由，无前台路由返回false
     *
     * @return mixed
     */
    protected function getFrontRouteFile()
    {
        return __DIR__ . '/../Routes/front.php';
    }

    /**
     * 后台路由，无后台路由返回false
     *
     * @return mixed
     */
    protected function getAdminRouteFile()
    {
        return __DIR__ . '/../Routes/admin.php';
    }

    /**
     * Api路由，无Api路由返回false
     *
     * @return mixed
     */
    protected function getApiRouteFile()
    {
        return __DIR__ . '/../Routes/api.php';
    }

    /**
     * 闭包命令行文件地址，没有返回false
     *
     * @return void
     */
    public function getConsoleRouteFile()
    {
        require __DIR__ . '/../Routes/console.php';
    }
}
