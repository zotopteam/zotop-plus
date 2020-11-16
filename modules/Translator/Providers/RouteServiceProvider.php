<?php

namespace Modules\Translator\Providers;

use App\Modules\Routing\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * 控制器命名空间
     *
     * @var string
     */
    protected $namespace = 'Modules\Translator\Http\Controllers';

    /**
     * 模块名称
     *
     * @var string
     */
    protected $module = 'translator';

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
}
