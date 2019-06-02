<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;

class RouteController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('developer::route.title');
        
        $this->routes = collect(app('router')->getRoutes())->filter(function(){
            return true;
        })->all();

        debug($this->routes);

        return $this->view();
    }
}
