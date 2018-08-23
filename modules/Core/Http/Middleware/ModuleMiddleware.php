<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Route;

class ModuleMiddleware
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
        // 获取当前路由数据
        $action = $this->app['router']->getCurrentRoute()->getAction();

        // 从路由信息中获取模块名称
        $this->app->singleton('current.module',function() use ($action) {
            return is_array($action['module']) ? end($action['module']) : $action['module'];
        });

        // 从路由信息中获取动作类型，如： admin,api,front
        $this->app->singleton('current.type',function() use ($action) {
            return is_array($action['type']) ? end($action['type']) : $action['type'];
        });

        // 从路由中获取控制器    
        $this->app->singleton('current.controller',function() use ($action) {
            return strtolower(substr($action['controller'], strrpos($action['controller'], "\\") + 1, strrpos($action['controller'], "@")-strlen($action['controller'])-10));
        });

        // 从路由信息中获取模块名称
        $this->app->singleton('current.action',function() use ($action) {
            return strtolower(substr($action['controller'], strpos($action['controller'], "@") + 1));
        });

        // 当前主题
        $this->app->singleton('current.theme',function() use ($action) {
            return 'default';
        });

        // 当前语言
        $this->app->singleton('current.locale',function() use ($action) {
            return $this->app->getLocale();
        });                  

        return $next($request);
    }
}
