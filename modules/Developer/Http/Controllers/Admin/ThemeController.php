<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;

class ThemeController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        return $this->view();
    }
}
