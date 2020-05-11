<?php

namespace Modules\Core\Http\Controllers\Admin;

use App\Modules\Facades\Module;
use App\Modules\Routing\AdminController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Modules\Core\Models\Role;

class RoleController extends AdminController
{
    /**
     * 获取全部权限
     * @return array
     */
    public function permissions()
    {
        $permissions = [];

        // 获取所有启用的模块权限
        foreach (Module::enabled() as $module) {
            
            $permission = [];

            // 从权限文件获取权限设置数据
            $path = $module->getPath('permission.php');

            if (File::exists($path)) {
                $permission = require $path;
            }

            // 无权限则不显示
            if ($permission && is_array($permission)) {
                $name = $module->getLowerName();
                $permissions[$name] = [
                    'title'       => $module->getTitle(),
                    'description' => $module->getDescription(),
                    'permissions' => $permission
                ];
            }     
        }

        return $permissions;        
    }

    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('core::role.title');
        $this->roles = Role::orderby('id','asc')->get();

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create()
    {
        $this->title       = trans('core::role.create');
        $this->role        = Role::findOrNew(0);
        $this->permissions = $this->permissions();
        return $this->view();
    }

    /**
     * 保存
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // 表单验证
        $this->validate($request, [
            'name'        => 'required|unique:roles',
            'description' => 'required'
        ],[],[
            'name'        => trans('core::role.name.label'),
            'description' => trans('core::role.description.label')
        ]);

        $role = new Role;
        $role->fill($request->all());
        $role->save();

        return $this->success(trans('master.created'), route('core.role.index'));
    }

    /**
     * 显示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }    

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit($id)
    {
        $this->title       = trans('core::role.edit');
        $this->id          = $id;
        $this->role        = Role::findOrFail($id);
        $this->permissions = $this->permissions();

        return $this->view();
    }

    /**
     * 更新
     *
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // 表单验证
        $this->validate($request, [
            'name'        => 'required|unique:roles,name,'.$id,
            'description' => 'required'
        ],[],[
            'name'        => trans('core::role.name.label'),
            'description' => trans('core::role.description.label')
        ]);

        $role = Role::findOrFail($id);
        $role->fill($request->all());        
        $role->save();

        return $this->success(trans('master.updated'), route('core.role.index'));  
    }

    /**
     * 禁用和启用
     *
     * @return Response
     */
    public function status(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // 如果已经禁用，启用
        if ($role->disabled) {
            $role->disabled = 0;
            $role->save();
            return $this->success(trans('master.actived'), $request->referer());         
        }

        // 禁用
        $role->disabled = 1;
        $role->save();

        return $this->success(trans('master.disabled'), $request->referer());
    } 
    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // 如果已经关联用户，则禁止删除
        if ($role->users()->count()) {
            return $this->error(trans('core::role.destroy.forbidden'));
        }

        $role->delete();

        return $this->success(trans('master.deleted'), route('core.role.index'));        //
    }
}
