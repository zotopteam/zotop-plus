<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Route;

class AdminMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 管理员已经或者登录页面运行继续运行
        if ( Route::is('admin.login','admin.login.post') || (Auth::check() && Auth::user()->isModel(['super','admin'])) ) {
            return $next($request);
        }

        // Ajax 禁止    
        if ($request->ajax()) {
            return response('Unauthorized.', 401);
        }
        
        // 转向登录页面
        return redirect()->guest(route('admin.login'));
    }
}
