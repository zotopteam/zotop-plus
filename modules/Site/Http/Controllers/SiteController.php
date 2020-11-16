<?php

namespace Modules\Site\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Routing\FrontController;

class SiteController extends FrontController
{
    /**
     * 首页
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return $this->view('index', $request->all());
    }
}
