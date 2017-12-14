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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return $this->view();
    }

    /**
     * 一键刷新
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function refresh(Request $request, $mode=null)
    {
        if ($request->isMethod('POST')) {
            
            // Hook
            Action::fire('system.refresh', $mode);

            // 重启系统
            Artisan::call('reboot');

            return $this->success(trans('core::master.operated'));
        }

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
            'server_datetime'     => now(),
            // 'local_date'       => gmdate('Y年n月j日 H:i:s', time() + 8 * 3600),
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
