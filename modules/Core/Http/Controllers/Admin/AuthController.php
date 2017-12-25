<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Base\AdminController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class AuthController extends AdminController
{
    use AuthenticatesUsers;

    /**
     * 登录
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return $this->view('auth.login')->with('title',trans('core::auth.login'));
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // protected function credentials(Request $request)
    // {
    //     $credentials = $request->only($this->username(), 'password');
    //     $credentials = array_merge($credentials, [
    //         'modelid'  => ['super','admin'],
    //         'disabled' => 0
    //     ]);

    //     return $credentials;
    // }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
         $credentials = $request->only($this->username(), 'password');
         $credentials = $credentials + ['disabled'=>0];

        return $this->guard()->attempt(
            $credentials + ['modelid'=>'super'], $request->filled('remember')
        ) || $this->guard()->attempt(
            $credentials + ['modelid'=>'admin'], $request->filled('remember')
        );
    }

    /**
     * 登陆成功
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $user->increment('login_times');
        $user->update([
            'login_at' => \Carbon\Carbon::now(),
            'login_ip' => $request->ip()
        ]);

        if ($request->expectsJson()) {
            return $this->success(trans('core::auth.success'), $this->redirectPath());
        }

         return redirect()->intended($this->redirectPath()); 
    }


    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    protected function redirectPath()
    {
        return route('admin.index');
    }     

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('core::auth.failed')];

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }    

    /**
     * Log the user out of the application.
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
        //return redirect(route('admin.login'));
    }
}
