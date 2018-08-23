<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Route;

class AdminMiddleware
{
    /**
     * app实例
     * 
     * @var mixed|\Illuminate\Foundation\Application
     */       
    protected $app;    

    /**
     * 初始化
     */
    public function __construct() {
        $this->app = app();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 当前主题
        $this->app->singleton('current.theme',function() {
            return config('core.theme', 'admin');
        });

        // 当前语言
        $this->app->singleton('current.locale',function() {
            return config('core.locale', $this->app->getLocale());
        });              

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
