<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Action;
use Artisan;
use Module;

class SystemController extends AdminController
{
    /**
     * 首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return $this->view();
    }

    /**
     * 统计文件夹大小
     * 
     * @param  Request $request
     * @return mixed
     */    
    public function size(Request $request)
    {
        if ($request->directory) {
            $path = base_path($request->directory);
            $size = dirsize($path, true);

            return $size;
        }

        return $this->error('Directory required');
    }

    /**
     * 系统管家
     * 
     * @param  Request $request [description]
     * @return mixed
     */
    public function manage(Request $request)
    {
        // 保存数据
        if ($request->isMethod('POST')) {

            if ( $artisan = $request->artisan ) {
                
                // 执行artisan命令
                Artisan::call($artisan);

                // artisan 的 config:cache 和 route:cache 会导致app('current.module')清空，暂时关闭改操作的日志
                config(['core.log.enabled'=>false]);
            }

            return $this->success(trans('master.operated'), $request->referer());
        }        

        $this->title   = trans('core::system.manage.title');
        $this->manages = Module::data('core::system.manage');

        return $this->view();
    }

    /**
     * 系统环境
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function environment()
    {
        $this->title = trans('core::system.environment.title');

        $this->server = [
            'php'                 => PHP_VERSION,
            'os'                  => PHP_OS . '( ' .php_uname('s') . php_uname('r') . php_uname('v') .' )',
            'server'              => $_SERVER['SERVER_SOFTWARE'],
            'db'                  => config('database.default'),
            
            'root'                => $_SERVER['DOCUMENT_ROOT'],
            'agent'               => $_SERVER['HTTP_USER_AGENT'],
            'protocol'            => $_SERVER['SERVER_PROTOCOL'],
            'laravel'             => app()::VERSION,
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time'  => ini_get('max_execution_time').'s',
            'disable_functions'   => ini_get('disable_functions'),
            'server_timezone'     => config('app.timezone'),
            'server_datetime'     => now(),
            'server_name'         => $_SERVER['SERVER_NAME'],
            'port'                => $_SERVER['SERVER_PORT'],
            'server_addr'         => $_SERVER['SERVER_ADDR'],
            'remote_addr'         => $_SERVER['REMOTE_ADDR'],
            'disk'                => \Format::size(disk_free_space('.')),
        ];

        $filesystem = [];

        foreach (app('files')->directories(base_path()) as $path) {
            $filesystem[] = [
                'type' => 'folder',
                'icon' => 'fa-folder',
                'path' => $path,
                'perms' => substr(sprintf('%o', fileperms($path)), -4)
            ];
        }

        foreach (app('files')->files(base_path()) as $path) {
            $filesystem[] = [
                'type' => 'file',
                'icon' => 'fa-file',
                'path' => $path,
                'perms' => substr(sprintf('%o', fileperms($path)), -4)
            ];
        }

        $this->filesystem = $filesystem;         
        
        return $this->view();
    }

    /**
     * 关于
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function about()
    {
        $this->title = trans('core::system.about.title');

        return $this->view();
    }    
}
