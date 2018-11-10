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
        $this->modules = module();

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
        // 启用前置Hook
        Action::fire('module.enabling', $module);

        Module::enable($module);

        return $this->success(trans('core::master.actived'), $request->referer());
    }

    /**
     * 启用模块
     * 
     * @param  string $name 模块名称
     * @return json
     */
    public function disable(Request $request, $module)
    {
        // 禁用前置Hook
        Action::fire('module.disabling', $module);

        Module::disable($module);

        return $this->success(trans('core::master.disabled'), $request->referer());
    }

    /**
     * 安装模块
     * 
     * @param  string $name 模块名称
     * @return json
     */
    public function install(Request $request, $module)
    {
        // 安装前置Hook
        Action::fire('module.installing', $module);

        // install
        Artisan::call('module:execute', [
            'action'  => 'install',
            'module'  => $module,
            '--force' => true,
            '--seed'  => false,
        ]);

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
        // 卸载前置Hook
        Action::fire('module.uninstalling', $module);

        // install
        Artisan::call('module:execute', [
            'action'  => 'uninstall',
            'module'  => $module,
            '--force' => true,
            '--seed'  => false,
        ]);        

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
        // 删除前置Hook
        Action::fire('module.deleting', $module);
        
        // Find Module
        $module = Module::find($module);

        // 已安装模块禁止删除
        if ($module->active or $module->installed) {
            return $this->error(trans('core::module.core_operate_forbidden'));
        }

        $module->delete();

        return $this->success(trans('core::master.deleted'), $request->referer());
    }

    /**
     * 资源发布
     *
     * @return Response
     */
    public function publish($module='')
    {
        // 发布前置Hook
        Action::fire('module.publish', $module);

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
