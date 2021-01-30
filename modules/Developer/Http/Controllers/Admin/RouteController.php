<?php

namespace Modules\Developer\Http\Controllers\Admin;

use App\Modules\Routing\AdminController;

class RouteController extends AdminController
{

    /**
     * 路由列表
     *
     * @return \Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2021-01-16
     */
    public function index()
    {
        $this->title = trans('developer::route.title');

        $this->routes = collect(app('router')->getRoutes())->filter(function () {
            return true;
        })->all();

        debug($this->routes);

        return $this->view();
    }
}
