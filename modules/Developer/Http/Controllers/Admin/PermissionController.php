<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use File;
use Module;
use Route;
use Closure;

class PermissionController extends AdminController
{
   /**
     * 权限
     * 
     * @param  Request $request
     * @param  string $module 模块名称
     * @return mixed
     */
    public function index(Request $request, $module)
    {
        $this->title       = trans('developer::command.title');
        
        $this->name        = $module;
        $this->module      = module($module);
        $this->path        = $this->module->getExtraPath('permission.php');
        $this->permissions = $this->module->getFileData('permission.php');

        // 获取模块所有的allows中的节点，用于和当前权限比对
        $this->allows      = $this->getRoutesPermissions($module);


        return $this->view();
    }

    /**
     * 扫描路由生成权限，原有权限将重命名为permission_bak.php
     * 
     * @param  string $module 模块名称 
     * @return mixed
     */
    public function scan(Request $request, $module)
    {        
        $this->module      = module($module);
        $this->path        = $this->module->getExtraPath('permission.php');

        // 如果permission文件存在，写入备份
        if (File::exists($this->path)) {
            $bak = $this->module->getExtraPath('permission_'.now()->format('YmdHis').'.php');
            File::move($this->path, $bak);
        } 

        $permissions = $this->getRoutesPermissions($module);

        if ($permissions) {
            File::put($this->path, "<?php\nreturn ".var_export($permissions,true).";\n");
            return $this->success(trans('developer::permission.scan.success'), $request->referer());
        }

        return $this->error(trans('developer::permission.scan.empty',[$this->module->getLowerName()]));
    }

    /**
     * 从路由中获取allow权限节点并组装成权限数组
     * 
     * @param  string $module 模块名称
     * @return array
     */
    public function getRoutesPermissions($module)
    {
        $permissions = [];
        $module = strtolower($module);

        $allows = $this->getModuleAllows($module);

        foreach ($allows as $allow) {
            $keys = explode('.', $allow);

            // 当前模块的权限必须以模块小写名称开头，如果第一个节点不是module，直接跳过
            if ( array_shift($keys) != $module ) {
                continue;
            }

            // 如果是module.edit类型，翻译默认存在module.php中
            if (count($keys) == 1) {
                $permissions[$allow] = $module.'::'.$module.'.'.implode('.', $keys);
            } else {
                $permissions[$allow] = $module.'::'.implode('.', $keys);
            }
        }

        return  $permissions;      
    }

    /**
     * 获取模块所有的allow中间件权限节点
     * 
     * @param  [type] $module [description]
     * @return [type]         [description]
     */
    public function getModuleAllows($module)
    {
        $module = strtolower($module);

        $routes = Route::getRoutes();
        $allows = collect($routes)->filter(function($route) use ($module) {
            $action = $route->getAction();
            return isset($action['module']) && $action['module'] == $module;
        })->map(function ($route) {
            return $this->getAllowFromMiddleware($route);
        })->filter()->unique()->all();

        return array_values($allows);
    }

    /**
     * 从单个路由中获取allow中的权限节点部分
     *
     * @param  \Illuminate\Routing\Route  $route
     * @return string
     */
    public function getAllowFromMiddleware($route)
    {
        $allow = collect($route->gatherMiddleware())->filter(function ($middleware) {
            return  $middleware && is_string($middleware) && starts_with($middleware, 'allow');
        })->map(function ($middleware) {
            return substr($middleware, 6);
        })->implode(',');

        return strtolower($allow);
    }
}
