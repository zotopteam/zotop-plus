<?php

namespace Modules\Media\Providers;

use App\Modules\Routing\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * 根命名空间
     *
     * @var string
     */
    protected $namespace = 'Modules\Media\Http\Controllers';

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
