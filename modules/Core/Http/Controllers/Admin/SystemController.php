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

        // 优化
        Artisan::call('optimize');

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

        $this->server = [
            'php'                 => PHP_VERSION,
            'os'                  => PHP_OS,
            'server'              => $_SERVER['SERVER_SOFTWARE'],
            'db'                  => config('database.default'),
            
            'root'                => $_SERVER['DOCUMENT_ROOT'],
            'agent'               => $_SERVER['HTTP_USER_AGENT'],
            'protocol'            => $_SERVER['SERVER_PROTOCOL'],
            'laravel'             => app()::VERSION,
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time'  => ini_get('max_execution_time').'s',
            'server_timezone'     => config('app.timezone'),
            'server_datetime'     => date('Y-m-d H:i:s'),
            // 'local_date'       => gmdate('Y年n月j日 H:i:s', time() + 8 * 3600),
            'server_name'         => $_SERVER['SERVER_NAME'],
            'port'                => $_SERVER['SERVER_PORT'],
            'server_addr'         => $_SERVER['SERVER_ADDR'],
            'remote_addr'         => $_SERVER['REMOTE_ADDR'],
            'disk'                => round((disk_free_space('.') / (1024 * 1024)), 2).'M',
        ];        
        
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
