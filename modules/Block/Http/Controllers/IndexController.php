<?php

namespace Modules\Block\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Routing\FrontController;
use Modules\Block\Models\Block;


class IndexController extends FrontController
{

    /**
     * 区块预览
     * 
     * @param  Request $request
     * @param  int  $id 区块编号
     * @return View
     */
    public function preview(Request $request, $id)
    {
        $this->id = $id;

        return $this->view();
    }
}
