<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Core\Base\FrontController;

class IndexController extends FrontController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return $this->view();
    }
}
