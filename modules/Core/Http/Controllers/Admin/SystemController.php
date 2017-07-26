<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Action;
use Artisan;

class SystemController extends AdminController
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

    /**
     * 一键刷新
     * 
     * @return Response
     */
    public function refresh()
    {
        $success = trans('core::master.operated');

        //Hook
        Action::fire('system.refresh');
        

        // 清除模板缓存
        Artisan::call('cache:clear');

        // 如果是本地或者测试模式或者处于debug状态下，不缓存路由和配置
        if ( app()->environment('local','testing') OR config('app.debug') ) {

            // 清除路由缓存
            Artisan::call('route:clear');
            
            // 清除配置缓存
            Artisan::call('config:clear');

        } else {

            // 重建路由缓存
            Artisan::call('route:cache');
            
            // 重建配置缓存
            Artisan::call('config:cache');
        }

        return $this->success($success);
    }

    /**
     * 系统环境
     *
     * @return Response
     */
    public function environment()
    {
        $this->title = trans('core::system.environment.title');
        
        return $this->view();
    }

    /**
     * 关于
     *
     * @return Response
     */
    public function about()
    {
        $this->title = trans('core::system.about.title');

        return $this->view();
    }    
}
