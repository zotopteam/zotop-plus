<?php

namespace Modules\Block\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\FrontController;
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
        $block = Block::findOrFail($id)->toArray();

        debug($block);

        return $this->view()->with($block);
    }
}
