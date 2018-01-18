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

class ModelController extends AdminController
{
    /**
     * 获取命令完整名称，加上Model后缀
     * 
     * @param  string $model 命令名称
     * @return string
     */
    private function realname($model)
    {
        return Str::studly($model);
    }  

    /**
     * 根据 模块、和命令名称获取命令的文件路径
     * 
     * @param  string $module  模块名称
     * @param  string $model 控制名称，可以不包含Model后缀
     * @return string
     */
    private function realpath($module, $model)
    {
        return module_path($module).'/'.$this->realname($model).'.php';
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
        $this->title   = trans('developer::model.title');
        
        $this->name    = $module;
        $this->module  = module($module);
        $this->path    = $this->module->getExtraPath('Models');
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
            $path = $this->realpath($module, $name);
            $name = $this->realname($name);

            if (File::exists($path)) {
                return $this->error(trans('core::master.existed'));
            }

            Artisan::call('module:make-model', [
                'module'    => $module,
                'model'     => $name,
                '--fillable'  => $request->input('fillable'),
                '--migration' => $request->input('migration') ? true : false
            ]);

            return $this->success(trans('core::master.saved'),route('developer.model.index',[$module]));
        }


        $this->title      = trans('core::master.create');


        return $this->view();
    }    
}
