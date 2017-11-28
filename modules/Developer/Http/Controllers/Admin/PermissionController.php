<?php

namespace Modules\Developer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use File;
use Module;

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
        $this->permissions = Module::getFileData($module, 'permission.php');
        $this->permissions = $this->permission_trans(strtolower($module), $this->permissions);
        return $this->view();
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
