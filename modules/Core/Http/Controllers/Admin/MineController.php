<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Modules\Core\Models\User;
use Modules\Core\Http\Requests\MineRequest;
use Modules\Core\Http\Requests\MinePasswordRequest;
use Auth;

class MineController extends AdminController
{
    /**
     * 首页
     *
     * @return Response
     */
    public function index()
    {
        return $this->view();
    }

    /**
     * 编辑
     *
     * @return Response
     */
    public function edit()
    {
        $this->user = Auth::user();

        return $this->view()->with('title',trans('core::mine.edit'));
    }

    /**
     * 更新
     *
     * @param  Request $request
     * @return Response
     */
    public function update(MineRequest $request)
    {
        $this->user = Auth::user();
        $this->user->fill($request->all());
        $this->user->save();

        return $this->success(trans('core::master.saved'));
    }

    /**
     * 修改我的密码
     *
     * @return Response
     */
    public function password()
    {
        $this->user = Auth::user();

        return $this->view()->with('title',trans('core::mine.password'));
    }

    /**
     * 修改我的密码
     *
     * @return Response
     */
    public function updatePassword(MinePasswordRequest $request)
    {
        $this->user = Auth::user();
        $this->user->password = \Hash::make($request->input('password_new'));
        $this->user->save();

        return $this->success(trans('core::master.saved'));
    }     

}
