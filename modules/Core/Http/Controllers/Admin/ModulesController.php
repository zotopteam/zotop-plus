<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Module;
use Artisan;

class ModulesController extends AdminController
{
    /**
     * 模块管理
     *
     * @return Response
     */
    public function index()
    {
        // 获取全部模块
        $modules = Module::all();

        // 默认安装顺序排序
        $direction = 'asc';

        // 模块排序
        uasort($modules, function ($a, $b) use ($direction) {
            if ($a->order == $b->order) {
                return 0;
            }

            if ($direction == 'desc') {
                return $a->order < $b->order ? 1 : -1;
            }

            return $a->order > $b->order ? 1 : -1;
        });   


        foreach ($modules as $name=>$module) {
            
            $namespace = strtolower($name);
            
            if ( $module->active ) {

                // 图标
                $module->icon = Module::asset($namespace.':module.png');
                
            } else {

                // 加载未启用模块语言包
                App('translator')->addNamespace($namespace, $module->getPath() . '/Resources/lang');

                // 预览图标
                $module->icon = $module->getExtraPath('Assets/module.png');
                $module->icon = preview($module->icon);
            }

            // 标题和描述语言化
            $module->title       = trans($module->title);
            $module->description = trans($module->description);                     
        }

        $this->title   = trans('core::modules.title');
        $this->modules = $modules;

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
        Module::enable($name);

        return $this->success(trans('core::master.actived'), $request->referer());
    }

    /**
     * 启用模块
     * 
     * @param  string $name 模块名称
     * @return json
     */
    public function disable(Request $request, $name)
    {
        // 核心模块不能禁用
        if (in_array(strtolower($name), config('modules.cores',['core']))) {
            return $this->error(trans('core::modules.core_operate_forbidden'));
        }

        Module::disable($name);

        return $this->success(trans('core::master.disabled'), $request->referer());
    }

    /**
     * 安装模块
     * 
     * @param  string $name 模块名称
     * @return json
     */
    public function install(Request $request, $name)
    {
        // Find Module
        $module = Module::find($name);

        // TODO ：依赖检查，某些模块互相依赖，如果安装前应进行依赖检查
        // coding……
        
        // Publish Assets
        Artisan::call('module:publish', ['module' => $name]);

        // Publish Config
        // Artisan::call('module:publish-config', ['module' => $name]);

        // Migrate
        Artisan::call('module:migrate', ['module' => $name]);
        
        // Update module.json
        $module->json()->set('active', 1)->set('installed', 1)->save();

        return $this->success(trans('core::modules.installed'), $request->referer());
    }

    /**
     * 卸载模块
     * 
     * @param  string $name 模块名称
     * @return json
     */
    public function uninstall(Request $request, $name)
    {
        // 核心模块不能卸载
        if (in_array(strtolower($name), config('modules.cores',['core']))) {
            return $this->error(trans('core::modules.core_operate_forbidden'));
        }
        
        // Find Module
        $module = Module::find($name);

        // TODO ：依赖检查，某些模块互相依赖，如果卸载并删除数据表，会导致错误，所以卸载前应进行依赖检查
        // coding……
        
        // migrate-reset
        Artisan::call('module:migrate-reset', ['module' => $name]);

        // 删除发布的配置和assets文件
        app('files')->deleteDirectory(config_path('cms/modules/'.strtolower($name)));
        app('files')->deleteDirectory(Module::assetPath($name));
        
        // update module.json
        $module->json()->set('active', 0)->set('installed', 0)->save();

        return $this->success(trans('core::modules.uninstalled'), $request->referer());
    }

    /**
     * 删除模块
     * 
     * @param  string $name 模块名称
     * @return json
     */
    public function delete(Request $request, $name)
    {
        // 核心模块不能卸载
        if (in_array(strtolower($name), config('modules.cores',['core']))) {
            return $this->error(trans('core::modules.core_operate_forbidden'));
        }
        
        // Find Module
        $module = Module::find($name);

        // 已安装模块禁止删除
        if ($module->active or $module->installed) {
            return $this->error(trans('core::modules.core_operate_forbidden'));
        }

        $module->delete();

        return $this->success(trans('core::master.deleted'), $request->referer());
    }       
}
