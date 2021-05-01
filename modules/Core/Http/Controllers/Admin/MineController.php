<?php

namespace Modules\Core\Http\Controllers\Admin;

use Zotop\Modules\Routing\AdminController;
use Auth;
use Hash;
use Modules\Core\Http\Requests\MinePasswordRequest;
use Modules\Core\Http\Requests\MineRequest;

class MineController extends AdminController
{
    /**
     * 我的首页
     *
     * @return \Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2021-01-13
     */
    public function index()
    {
        return $this->view();
    }

    /**
     * 编辑
     *
     * @return \Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2021-01-13
     */
    public function edit()
    {
        $this->user = Auth::user();

        return $this->view()->with('title', trans('core::mine.edit'));
    }

    /**
     * 更新信息
     *
     * @param \Modules\Core\Http\Requests\MineRequest $request
     * @return \Zotop\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2021-01-13
     */
    public function update(MineRequest $request)
    {
        $this->user = Auth::user();
        $this->user->fill($request->all());
        $this->user->save();

        return $this->success(trans('master.saved'));
    }


    /**
     * 修改我的密码
     *
     * @return \Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2021-01-13
     */
    public function password()
    {
        $this->user = Auth::user();

        return $this->view()->with('title', trans('core::mine.password'));
    }

    /**
     * 修改我的密码
     *
     * @param \Modules\Core\Http\Requests\MinePasswordRequest $request
     * @return \Zotop\Modules\Routing\JsonMessageResponse|\Illuminate\Contracts\View\View
     * @author Chen Lei
     * @date 2021-01-13
     */
    public function updatePassword(MinePasswordRequest $request)
    {
        $this->user = Auth::user();
        $this->user->password = Hash::make($request->input('password_new'));
        $this->user->save();

        return $this->success(trans('master.saved'));
    }

}
