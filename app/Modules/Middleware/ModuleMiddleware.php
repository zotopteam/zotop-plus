<?php

namespace App\Modules\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Foundation\Application;

class ModuleMiddleware
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
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
        // 获取当前路由数据
        $action = $this->app['router']->getCurrentRoute()->getAction();

        // 从路由信息中获取模块名称
        $this->app->singleton('current.module',function() use ($action) {
            return is_array($action['module']) ? end($action['module']) : $action['module'];
        });
        
        // 从路由中获取控制器    
        $this->app->singleton('current.controller',function() use ($action) {
            return strtolower(substr($action['controller'], strrpos($action['controller'], "\\") + 1, strrpos($action['controller'], "@")-strlen($action['controller'])-10));
        });

        // 从路由信息中获取模块名称
        $this->app->singleton('current.action',function() use ($action) {
            return strtolower(substr($action['controller'], strpos($action['controller'], "@") + 1));
        });              

        return $next($request);
    }
}
