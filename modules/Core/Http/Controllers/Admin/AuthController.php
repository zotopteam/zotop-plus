<?php

namespace Modules\Core\Http\Controllers\Admin;

use Zotop\Modules\Routing\AdminController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends AdminController
{
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * 表单验证
     * @param  Request $request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * 尝试类型用户登录
     * @param  \Illuminate\Http\Request  $request
     * @param  string $type 用户类型
     * @return boolean
     */
    protected function attemptLogin(Request $request, $type)
    {
        return $this->guard()->attempt([
            'username' => $request->username,
            'password' => $request->password,
            'type' => $type,
            'disabled' => 0,
        ], $request->filled('remember'));
    }

    /**
     * 登陆成功
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function afterLogin(Request $request)
    {
        // 重新生成session
        $request->session()->regenerate();

        // 记录用户信息
        $user = $this->guard()->user();

        $user->increment('login_times');
        $user->update([
            'login_at' => \Carbon\Carbon::now(),
            'login_ip' => $request->ip()
        ]);
    }


    /**
     * 登录
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if ($request->isMethod('POST')) {

            // 表单验证
            $this->validateLogin($request);

            // 尝试super类型用户或者admin类型用户登录
            if ($this->attemptLogin($request, 'super') || $this->attemptLogin($request, 'admin')) {
                $this->afterLogin($request);
                return $this->success(trans('core::auth.success'), route('admin.index'));
            }

            return $this->error(trans('core::auth.failed'));
        }

        return $this->view('core::auth.login')->with('title', trans('core::auth.login'));
    }

    /**
     * 登出
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();
        $request->session()->regenerate();

        return $this->success(trans('core::auth.logout.success'), route('admin.login'));
    }
}
