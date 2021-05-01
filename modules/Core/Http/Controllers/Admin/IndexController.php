<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zotop\Modules\Routing\AdminController;

class IndexController extends AdminController
{
    /**
     * Display a listing of the resource.
     * 
     * @return Response
     */
    public function index()
    {
        return $this->view()->with('title', trans('core::core.index'));
    }
}
