<?php

namespace Modules\Core\Http\Controllers\Admin;

use App\Modules\Facades\Module;
use App\Modules\Routing\AdminController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

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
        Artisan::call('module:enable', [
            'module' => $module,
        ]);

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
        Artisan::call('module:disable', [
            'module' => $module,
        ]);

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
        Artisan::call('module:install', [
            'module' => $module,
        ]);

        return $this->success(trans('core::module.installed'), $request->referer());
    }

    /**
     * 安装模块
     * 
     * @param  string $name 模块名称
     * @return json
     */
    public function upgrade(Request $request, $module)
    {        
        Artisan::call('module:upgrade', [
            'module' => $module,
        ]);

        return $this->success(trans('core::module.upgraded'), $request->referer());
    }

    /**
     * 卸载模块
     * 
     * @param  string $name 模块名称
     * @return json
     */
    public function uninstall(Request $request, $module)
    {       
        Artisan::call('module:uninstall', [
            'module' => $module,
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
        Artisan::call('module:delete', [
            'module' => $module,
        ]);

        return $this->success(trans('master.deleted'), $request->referer());
    }

    /**
     * 资源发布
     *
     * @return Response
     */
    public function publish($module=null)
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
        //
    }             
}
