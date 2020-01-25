<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Routing\AdminController;
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

    /**
     * 清理日志
     * @param  Request $request
     * @return Response
     */
    public function clean(Request $request)
    {
        // 删除超出有效期的日志
        Log::where('created_at', '<', now()->modify('-'.config('core.log.expire', 30).' days'))->delete();
        return $this->success('master.operated', $request->referer());
    }
}
