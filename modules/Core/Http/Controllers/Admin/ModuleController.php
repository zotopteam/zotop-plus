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
     * @param  string $name 模块名称
     * @return json
     */
    public function enable(Request $request, $name)
    {
        // 禁用前置Hook
        if (! Filter::fire('module.enabling', $this, $module) ) {
            return $this->error($this->error ?? trans('core::module.enable.failed', [$module]));
        }

        Module::enable($name);

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
        if (! Filter::fire('module.disabling', $this, $module) ) {
            return $this->error($this->error ?? trans('core::module.disable.failed', [$module]));
        }    

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
        // 按照前置Hook
        if (! Filter::fire('module.installing', $this, $module) ) {
            return $this->error($this->error ?? trans('core::module.install.failed', [$module]));
        }

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
        if (! Filter::fire('module.uninstalling', $this, $module) ) {
            return $this->error($this->error ?? trans('core::module.uninstall.failed', [$module]));
        }

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
        // 卸载前置Hook
        if (! Filter::fire('module.deleting', $this, $module) ) {
            return $this->error($this->error ?? trans('core::module.delete.failed', [$module]));
        }
        
        // Find Module
        $module = Module::find($module);

        // 已安装模块禁止删除
        if ($module->active or $module->installed) {
            return $this->error(trans('core::module.core_operate_forbidden'));
        }

        $module->delete();

        return $this->success(trans('core::master.deleted'), $request->referer());
    }       
}
