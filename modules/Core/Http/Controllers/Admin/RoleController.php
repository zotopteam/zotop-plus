<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Core\Models\Role;
use Modules\Core\Support\Permission;

class RoleController extends AdminController
{
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
    public function create(Permission $permission)
    {
        $this->title       = trans('core::role.create');
        $this->role        = Role::findOrNew(0);
        $this->permissions = $permission->all();
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

        return $this->success(trans('core::master.created'), route('core.role.index'));
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
    public function edit(Permission $permission, $id)
    {
        $this->title       = trans('core::role.edit');
        $this->id          = $id;
        $this->role        = Role::findOrFail($id);
        $this->permissions = $permission->all();

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

        return $this->success(trans('core::master.updated'), route('core.role.index'));  
    }

    /**
     * 禁用和启用
     *
     * @return Response
     */
    public function status(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ( $role->id==1 ) {
            return $this->error(trans('core::master.forbidden'));
        }

        // 如果已经禁用，启用
        if ( $role->disabled ) {
            $role->disabled = 0;
            $role->save();
            return $this->success(trans('core::master.actived'), $request->referer());         
        }

        // 禁用
        $role->disabled = 1;
        $role->save();

        return $this->success(trans('core::master.disabled'), $request->referer());
    } 
    /**
     * 删除
     *
     * @return Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // 禁止操作
        if ( $role->id==1 ) {
            return $this->error(trans('core::master.forbidden'));
        }

        $role->delete();

        return $this->success(trans('core::master.deleted'), route('core.role.index'));        //
    }
}
