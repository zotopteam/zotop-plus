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

class MigrationController extends AdminController
{
    /**
     * 获取命令完整名称，加上Command后缀
     * 
     * @param  string $migration 命令名称
     * @return string
     */
    private function fullname($migration)
    {
        return Str::snake($migration);
    }  

    /**
     * 根据 模块、和命令名称获取命令的文件路径
     * 
     * @param  string $module  模块名称
     * @param  string $migration 控制名称，可以不包含Command后缀
     * @return string
     */
    private function fullpath($module, $migration)
    {
        return module_path($module).'/'.$this->fullname($migration).'.php';
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
        $this->title   = trans('developer::migration.title');
        
        $this->name    = $module;
        $this->module  = module($module);
        $this->path    = $this->module->getExtraPath('Database/Migrations');
        $this->files   = File::files($this->path);


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

            Artisan::call('module:make-migration', [
                'module'  => $module,
                'name'    => $name,
            ]);

            return $this->success(trans('core::master.saved'),route('developer.migration.index',[$module]));
        }


        $this->title      = trans('developer::migration.create');


        return $this->view();
    }

    /**
     * 执行命令
     * 
     * @param  Request $request
     * @param  string $module 模型名称
     * @return mixed
     */
    public function execute(Request $request, $module, $action)
    {
        $actions = [
            'migrate'  => 'module:migrate',
            'rollback' => 'module:migrate-rollback',
            'reset'    => 'module:migrate-reset',
            'refresh'  => 'module:migrate-refresh',
            'seed'     => 'module:seed',
        ];

        $command = $actions[$action] ?? reset($actions);

        Artisan::call($command, [
            'module'  => $module,
            '--force' => true
        ]);

        return $this->success(trans('core::master.operated'));
    }        
}
