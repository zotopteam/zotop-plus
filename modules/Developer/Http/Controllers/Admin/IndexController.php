<?php

namespace Modules\Developer\Http\Controllers\Admin;

use App\Modules\Routing\AdminController;
use Illuminate\Http\Request;


class IndexController extends AdminController
{

    /**
     * 开发助手首页
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2020-12-23
     */
    public function index(Request $request)
    {
        $this->title = trans('developer::developer.title');

        return $this->view();
    }
}
