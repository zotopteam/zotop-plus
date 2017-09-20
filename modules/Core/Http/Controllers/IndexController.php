<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Core\Base\FrontController;

class IndexController extends FrontController
{
    /**
     * 系统前台首页
     * 
     * @return Response
     */
    public function index()
    {
        return $this->view();
    }
}
