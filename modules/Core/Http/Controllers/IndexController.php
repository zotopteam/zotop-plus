<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Routing\FrontController;

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
