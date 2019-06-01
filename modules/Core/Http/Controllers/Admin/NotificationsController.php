<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Auth;
use Filter;

class NotificationsController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('core::notification.title');

        // 获取当前用户的全部通知
        $this->notifications = Auth::user()->notifications()->paginate(25);
        
        return $this->view();
    }

    public function check()
    {
        \Debugbar::disable();
        
        // 获取全部未读通知
        $notification_count = Filter::fire('notification.count', Auth::user()->unreadNotifications->count());

        return ['count'=>$notification_count];
    }
}
