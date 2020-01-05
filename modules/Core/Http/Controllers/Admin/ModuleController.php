<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Module;
use Artisan;
use Filter;
use Action;

class ModuleController extends AdminController
{
    /**
     * 模块管理
     *
     * @return Response
     */
    public function index()
    {
        $this->title   = trans('core::module.title');
        $this->modules = Module::all();

        return $this->view();
    }

    /**
     * 启用模块
     * 
     * @param  string $module 模块名称
     * @return json
     */
    public function enable(Request $request, $module)
    {
        Module::findOrFail($module)->enable(); 

        return $this->success(trans('master.actived'), $request->referer());
    }

    /**
     * 启用模块
     * 
     * @param  string $name 模块名称
     * @return json
     */
    public function disable(Request $request, $module)
    {
        Module::findOrFail($module)->disable(); 

        return $this->success(trans('master.disabled'), $request->referer());
    }

    /**
     * 安装模块
     * 
     * @param  string $name 模块名称
     * @return json
     */
    public function install(Request $request, $module)
    {        
        Module::findOrFail($module)->install(); 

        return $this->success(trans('core::module.installed'), $request->referer());
    }

    /**
     * 卸载模块
     * 
     * @param  string $name 模块名称
     * @return json
     */
    public function uninstall(Request $request, $module)
    {       
        Module::findOrFail($module)->uninstall(); 
        
        return $this->success(trans('core::module.uninstalled'), $request->referer());
    }

    /**
     * 删除模块
     * 
     * @param  string $module 模块名称
     * @return json
     */
    public function delete(Request $request, $module)
    {
        // Find Module
        $module = Module::findOrFail($module);

        // 已安装模块禁止删除
        if ($module->active || $module->installed) {
            return $this->error('Enabled or installed module are forbidden to delete!');
        }

        $module->delete();

        return $this->success(trans('master.deleted'), $request->referer());
    }

    /**
     * 资源发布
     *
     * @return Response
     */
    public function publish($module='')
    {
        if ($module) {
            Artisan::call("module:publish", [
                'module' => $module
            ]);
        } else {
            Artisan::call('module:publish');
        }

        return $this->success(trans('core::module.publish.success'));    
    }

    /**
     * 上传主题
     *
     * @return Response
     */
    public function upload()
    {
  
    }             
}
