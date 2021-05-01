<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zotop\Modules\Routing\AdminController;
use Modules\Core\Models\Log;
use Modules\Core\Models\QueryFilters\LogFilter;

class LogController extends AdminController
{
    /**
     * 列表
     *
     * @param \Illuminate\Http\Request $request
     * @param \Modules\Core\Models\QueryFilters\LogFilter $filter
     * @return \Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2020-11-23
     */
    public function index(Request $request, LogFilter $filter)
    {
        $this->title = trans('core::log.title');
        $this->logs = Log::with('user')->filter($filter)->orderBy('id', 'desc')->paginate(15);

        return $this->view();
    }

    /**
     * 清理日志
     *
     * @param \Illuminate\Http\Request $request
     * @return \Zotop\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2020-11-23
     */
    public function clean(Request $request)
    {
        // 删除超出有效期的日志
        Log::where('created_at', '<', now()->modify('-' . config('core.log.expire', 30) . ' days'))->delete();
        return $this->success('master.operated', $request->referer());
    }
}
