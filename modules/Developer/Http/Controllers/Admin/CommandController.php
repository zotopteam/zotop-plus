<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Modules\Core\Base\AdminController;
use Module;
use Artisan;
use File;
use Filter;

class CommandController extends AdminController
{
    /**
     * 获取命令完整名称，加上Command后缀
     * 
     * @param  string $command 命令名称
     * @return string
     */
    private function fullname($command)
    {
        // 如果不包含Command后缀
        if (strtolower($command) == 'command' || ends_with(strtolower($command), 'command') === false) {
            $command .= 'Command';
        }

        return Str::studly($command);
    }  

    /**
     * 根据 模块、和命令名称获取命令的文件路径
     * 
     * @param  string $module  模块名称
     * @param  string $command 控制名称，可以不包含Command后缀
     * @return string
     */
    private function fullpath($module, $command)
    {
        return module_path($module).'/'.$this->fullname($command).'.php';
    }

    /**
     * 命令列表
     * 
     * @param  Request $request
     * @param  string $module 模块名称
     * @return mixed
     */
    public function index(Request $request, $module)
    {
        $this->title   = trans('developer::command.title');
        
        $this->name    = $module;
        $this->module  = module($module);
        $this->path    = $this->module->getExtraPath('Console');
        $this->files   = File::exists($this->path) ? File::files($this->path) : [];

        return $this->view();
    }

    /**
     * 创建命令
     * 
     * @param  Request $request
     * @param  string $module 模型名称
     * @return mixed
     */
    public function create(Request $request, $module)
    {
        $this->module = $module;

        // 表单提交时
        if ($request->isMethod('POST')) {
            
            $name  = $request->input('name');

            // 判断是否已经存在
            $path = $this->fullpath($module, $name);
            $name = $this->fullname($name);

            if (File::exists($path)) {
                return $this->error(trans('core::master.existed'));
            }

            Artisan::call('module:make-command', [
                'module'  => $module,
                'name'    => $name,
            ]);

            return $this->success(trans('core::master.saved'),route('developer.command.index',[$module]));
        }


        $this->title      = trans('core::master.create');


        return $this->view();
    }    
}
