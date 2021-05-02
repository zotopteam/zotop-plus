<?php

namespace Modules\Core\Http\Controllers\Admin;

use Zotop\Modules\Routing\AdminController;

class IndexController extends AdminController
{
    /**
     * 后台首页
     *
     * @return \Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2021-05-02
     */
    public function index()
    {
        return $this->view()->with('title', trans('core::core.index'));
    }
}
