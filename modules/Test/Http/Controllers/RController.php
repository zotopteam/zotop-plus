<?php

namespace Modules\Test\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\FrontController;

class RController extends FrontController
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