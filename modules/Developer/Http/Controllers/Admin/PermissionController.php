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
    public function index(Request $request, $module, $type)
    {
        $this->title   = trans('developer::command.title');
        
        $this->name        = $module;
        $this->module      = module($module);
        $this->path        = $this->module->getExtraPath('permission.php');
        $this->permissions = $this->getModulePermissions();

        return $this->view();
    }

    /**
     * 获取模块所有的allow中间件
     * @param  [type] $module [description]
     * @return [type]         [description]
     */
    public function getModuleAllows($module)
    {  
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
     * Get before filters.
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

        return $allow;
    }

    public function getModulePermissions()
    {
        $module      = $this->module->getLowerName();
        $allows      = $this->getModuleAllows($module);
        $permissions = [];

        // 如果permission文件不存在，直接写入
        if (! File::exists($this->path)) {
            
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

            File::put($this->path, "<?php\nreturn ".var_export($permissions,true).";\n");
        } else {
            $permissions = $this->module->getFileData('permission.php');
        }

        return $this->formatPermisstions($permissions);
    }

    public function formatPermisstions($permissions)
    {
        return $permissions;
    }


    protected function permission_trans($module, $permissions)
    {
        $permission_trans = [];

        foreach ($permissions as $controller => $actions) {
            // controller
            $permission_trans[$module.'.'.$controller] = [
                'module'     => $module,
                'controller' => $controller,
                'name'      => trans($module.'::'.$controller.'.title'),
                'depth'      => 0
            ];
             // action 
            foreach ($actions as $action) {
                $permission_trans[$module.'.'.$controller.'.'.$action] = [
                    'module'     => $module,
                    'controller' => $controller,
                    'action'     => $action,
                    'name'      => trans($module.'::'.$controller.'.'.$action),
                    'depth'      => 1
                ];
            }
        }
        return $permission_trans;
    }
}
