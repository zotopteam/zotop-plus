<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Modules\Routing\AdminController;
use Module;
use Artisan;
use File;
use Filter;

class CommandController extends AdminController
{
    private function commands($command, $key=null)
    {
        static $commands = [];

        if (empty($commands)) {
            $commands = Module::data('developer::module.commands');
        }

        if (isset($commands[$command])) {
            return $key ? array_get($commands[$command], $key) : $commands[$command];
        }

        return null;
    }

    /**
     * 获取命令完整名称，加上Command后缀
     * 
     * @param  string $command 命令名称
     * @return string
     */
    private function fullname($command, $name)
    {
        $append = $this->commands($command, 'name.append');

        // 如果不包含名称后缀
        if ($append && ends_with(strtolower($name), strtolower($append)) === false) {
            $name .= $append;
        }

        return Str::studly($name);
    }  

    /**
     * 根据 模块、和命令名称获取命令的文件路径
     * 
     * @param  string $module  模块名称
     * @param  string $command 控制名称，可以不包含Command后缀
     * @return string
     */
    private function fullpath($module, $command, $name)
    {
        $dir = $this->commands($command, 'dir');

        return module_path($module).'/'.$dir.'/'.$this->fullname($command, $name).'.php';
    }

    /**
     * 命令列表
     * 
     * @param  Request $request
     * @param  string $module 模块名称
     * @return mixed
     */
    public function index(Request $request, $module, $command)
    {
        $this->title   = $this->commands($command, 'title');
        $this->dir     = $this->commands($command, 'dir');
        $this->artisan = $this->commands($command, 'artisan'). ' '.$this->fullname($command, 'test').' '.$module;
        $this->help    = $this->commands($command, 'help');
        
        $this->command = $command;
        $this->module  = module($module);
        $this->path    = $this->module->getExtraPath($this->dir);
        $this->files   = File::isDirectory($this->path) ? File::files($this->path) : [];

        return $this->view();
    }

    /**
     * 创建命令
     * 
     * @param  Request $request
     * @param  string $module 模型名称
     * @return mixed
     */
    public function create(Request $request, $module, $command)
    {
        // 表单提交时
        if ($request->isMethod('POST')) {
            
            $name  = $request->input('name');

            // 判断是否已经存在
            $path = $this->fullpath($module, $command, $name);
            $name = $this->fullname($command, $name);

            if (File::exists($path)) {
                return $this->error(trans('master.existed',[$name]));
            }

            $artisan = $this->commands($command, 'artisan');

            Artisan::call($artisan, [
                'module'  => $module,
                'name'    => $name,
            ]);

            return $this->success(trans('master.saved'), route('developer.command.index', [$module, $command]));
        }


        $this->title   = trans('master.create');
        $this->module  = $module;
        $this->command = $command;
        $this->label   = $this->commands($command, 'name.label');
        $this->help    = $this->commands($command, 'name.help');

        return $this->view();
    }    
}
