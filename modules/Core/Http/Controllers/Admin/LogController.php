<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Core\Models\Log;

class LogController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->title = trans('core::log.title');
        $this->logs = Log::with('user')->when($request->keywords, function($query, $keywords) {
            $query->searchIn('url,request', $keywords);
        })->orderBy('id','desc')->paginate(15);

        return $this->view();
    }
}
