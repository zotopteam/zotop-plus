<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Modules\Routing\AdminController;
use Modules\Core\Models\User;
use Modules\Core\Http\Requests\AdministratorRequest;

class AdministratorController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        $this->title = trans('core::administrator.title');
        $this->users = User::with('roles')->whereIn('model_id',['super','admin'])->orderby('id','asc')->paginate(10);

        return $this->view();
    }

    /**
     * 新建
     * 
     * @return Response
     */
    public function create()
    {
        $this->title = trans('core::administrator.create');
        $this->user = User::findOrNew(0);

        return $this->view();
    }

    /**
     * 保存
     *
     * @param  Request $request
     * @return Response
     */
    public function store(AdministratorRequest $request)
    {
        $user = new User;
        $user->fill($request->all());
        $user->password = \Hash::make($user->password);
        $user->save();

        if ($request->model_id == 'super') {
            $user->roles()->detach();
        } else {
            $user->roles()->attach($request->input('roles'));
        }        
        
        return $this->success(trans('master.created'), route('core.administrator.index'));
    }

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit(AdministratorRequest $request, $id)
    {
        
        $this->title = trans('core::administrator.edit');
        $this->id    = $id;
        $this->user  = User::findOrFail($id);

        $this->super_count = User::where('model_id', 'super')->count();

        return $this->view();
    }

    /**
     * 更新
     *
     * @param  Request $request
     * @return Response
     */
    public function update(AdministratorRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if (User::where('model_id', 'super')->count() == 1 && $user->model_id == 'super' && $request->model_id != 'super' ) {
            return $this->error(trans('core::administrator.model.super.required'));  
        }

        $user->fill($request->all());

        // 修改密码
        if ($password_new = $request->input('password_new')) {
            $user->password = \Hash::make($password_new);
        }
        
        $user->save();

        if ($request->model_id == 'super') {
            $user->roles()->detach();
        } else {
            $user->roles()->sync($request->input('roles'));
        }

        return $this->success(trans('master.updated'), route('core.administrator.index'));  
    }

    /**
     * 禁用和启用
     *
     * @return Response
     */
    public function status(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // 禁止禁用super
        if ( $user->isSuper() ) {
            return $this->error(trans('master.forbidden'));
        }

        // 如果已经禁用，启用
        if ( $user->disabled ) {
            $user->disabled = 0;
            $user->save();
            return $this->success(trans('master.actived'), $request->referer());         
        }

        // 禁用
        $user->disabled = 1;
        $user->save();

        return $this->success(trans('master.disabled'), $request->referer());
    }    

    /**
     * 删除
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // 禁止操作名单 TODO  $user->id==1 不是好方法，应该判断只剩下最后一个超级管理员
        if ($user->isSuper()) {
            return $this->error(trans('master.forbidden'));
        }
        // 解除权限关系
        $user->roles()->detach();
        $user->delete();

        return $this->success(trans('master.deleted'), route('core.administrator.index'));
    }
}
