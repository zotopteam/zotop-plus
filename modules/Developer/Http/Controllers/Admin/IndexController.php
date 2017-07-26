<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;


class IndexController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->title  = trans('developer::module.title');

        return $this->view();
    }
}
